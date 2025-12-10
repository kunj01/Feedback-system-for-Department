# Training & Placement Tracking Software — Software Requirements Specification (SRS)

Version: 1.1  
Date: 2025-12-09  
Author: GitHub Copilot (for sbs2083) — Updated to include bulk-upload, student gating, multi-reporting and bulk assignment features.

NOTE: This document is an update/extension to the base SRS (version 1.0). All previously defined requirements, assumptions, and tables remain valid unless explicitly reconciled below. New or changed items are clearly labelled and integrated into the original SRS structure.

Contents (high level)
1. Summary of Changes (Quick Summary)
2. Clarifications and Assumptions
3. New & Updated Functional Requirements (numbered FR-*)
4. Business Rules & Grade/Reporting Reconciliation
5. Data Requirements — Schema Changes & New Tables (MySQL / Laravel migration-level)
6. API Endpoints (new/updated)
7. UI / UX Changes & Excel Export/Import Templates
8. Process Flows & Pseudocode (Auto-account generation, Project ID suggestion, Bulk assignment, Reporting storage)
9. Acceptance Criteria & Test Cases (actionable)
10. Migration & Data-migration Considerations
11. Implementation Plan (prioritized steps + complexity & rough estimates)
12. Appendix (sample Excel rows, example migration snippets, example controller endpoint)

--------------------------------------------------------------
1. Quick Summary — What changed and why
- Added bulk Excel upload for T&P officers to create/update student records and auto-generate user accounts (email format: studentidinsmall@charusat.edu.in).
- Introduced a stronger student identity model (student.student_id). To remain compatible with Laravel/Eloquent, we use an immutable unique student_id while preserving internal auto-increment id. (See Assumptions.)
- Added bulk assignment features: faculty assignment as Internal Guide (single/bulk via filters/Excel) and bulk Project ID assignment with next-sequence suggestion.
- Expanded Reporting model: Guides can add at least five periodic reports (each out of 15) and one final project report out of 25. The system aggregates to compute internal marks and map to grades A+..C.
- Student self-service gating: Students must fill required profile fields before "start training" / project acceptance.
- HOD dashboard: reporting matrix (Reporting 1..5, Final Report, Total, Grade) and filtering for pending items.
- Student listing: rich filters and Excel-ready export with NA/NULL rules.
- Added new tables/migrations, API endpoints, sample Excel templates, pseudo-code and acceptance test cases.

--------------------------------------------------------------
2. Clarifications & Assumptions (explicit)
- Student identifier: The uploaded data uses a "Student ID" (alphanumeric string). Reasonable assumption: For Laravel conventions and referential integrity, we will NOT drop the internal auto-increment id column; instead:
  - students.id: BIGINT PK (internal)
  - students.student_id: VARCHAR UNIQUE NOT NULL (business primary key / external id)
  - All external operations (Excel, user generation) use student_id as the authoritative identifier.
  - This keeps compatibility with relationships and avoids foreign-key changes across existing tables.
- Email address for generated users: <studentid in lowercase>@charusat.edu.in (e.g., if student_id = CHS2023CSE001 -> chs2023cse001@charusat.edu.in).
- Password policy: system generates a secure random temporary password when creating a user (or optionally creates a one-time login token link); temporary password emailed to student and must be changed on first login. Passwords are hashed (bcrypt/argon2).
- "NA" handling: Any field that can be NA must accept literal string 'NA'. If field empty -> store NULL.
- Reporting normalization: We will create an evaluations_reports table for each reporting entry and store a final_reports or final_project field. Aggregation logic uses up to 5 periodic reports (15 each) + final 25.
- Partial reporting sets: Business rule choice — Grade is withheld until at least 3 periodic reports and final report exist OR T&P/HOD explicitly mark partial reports as accepted and compute scaled grade. Default: Withhold final grade until all 5 periodic reports OR HOD/T&P explicitly mark "compute-proportionally". See Business Rules section.
- Export formats and date format: Use ISO YYYY-MM-DD for dates in Excel exports.

--------------------------------------------------------------
3. New & Updated Functional Requirements (FR codes)

A. Student Listing & Excel-ready Filters (FR-STDLIST-*)
- FR-STDLIST-01: The Student listing screen must expose column selection and filters sufficient to export to Excel with "almost all details". Default export columns (recommended): student_id, name, email, roll_no, registration_no, department, course, batch, cgpa, training_status, project_id(s) (comma), guide_id(s) (comma), company(ies) (comma), placement_status (latest or list), attendance_percent (latest), last_evaluation_date, HOD_approval_status, custom_tags.
- FR-STDLIST-02: Filtering options: department, batch, course, roll_no, registration_no, project_id, company, placement_status (OFFERED/JOINED/REJECTED/UNASSIGNED), guide_id, HOD approval status, training_status, cgpa range, attendance range, created_at range, custom tags, internship domain, project_domain.
- FR-STDLIST-03: Export format: Excel (.xlsx) header row, date format YYYY-MM-DD, NA represented by literal 'NA', NULL represented by blank cell (or explicit string "<NULL>" if requested by admin in export settings). CSV optional with same rules.
- FR-STDLIST-04: Admin/T&P can save filter presets and default export column sets.

B. T&P Officer Bulk Excel Upload & Account Generation (FR-TNP-BULK-*)
- FR-TNP-BULK-01: T&P can upload an Excel (.xlsx/.csv) file containing student rows. The system performs a dry-run (validation) showing rows OK, rows with warnings (e.g., missing optional fields), and rows rejected (e.g., missing student_id). T&P may then confirm to import.
- FR-TNP-BULK-02: Import rules:
  - If student.student_id does not exist -> create student record and, if no user exists with the email, create associated user with email studentidinsmall@charusat.edu.in and generated secure password OR create user and send one-time login link.
  - If students.student_id exists -> update student record fields from Excel according to chosen merge strategy (overwrite/merge/skip). If user exists, link user to student (students.user_id = users.id).
  - If email derived from student_id already exists and is linked to another student -> import flags conflict and requires manual resolution.
- FR-TNP-BULK-03: Generated user account values:
  - email = lower(student_id) + '@charusat.edu.in'
  - name = concatenation of first_name and last_name columns or 'NA' if missing
  - password = secure random token (min 12 chars) hashed in DB; store ephemeral token for first-login; email sent to student with reset link.
- FR-TNP-BULK-04: Import report saved to import_logs with rows created, updated, skipped, and error reasons; T&P can download import report (.xlsx/.csv).
- FR-TNP-BULK-05: Security: only T&P/ Admin can perform import. All imports are auditable.

C. T&P Assignment Features (FR-TNP-ASSIGN-*)
- FR-TNP-ASSIGN-01: T&P can assign Internal Guide (faculty) to students individually or in bulk.
- FR-TNP-ASSIGN-02: Bulk assignment via UI filters or Excel (columns: student_id, guide_user_id). System validates guide exists and is eligible (role Guide/Faculty or role-combo).
- FR-TNP-ASSIGN-03: When assigning projects: T&P creates project and system suggests the next Project ID. Suggestion algorithm returns next sequential number (see Section 8 Process Flows).
- FR-TNP-ASSIGN-04: Bulk assignment supports conflict resolution rules:
  - Option 1 (Default): Overwrite existing guide assignment only with explicit confirmation.
  - Option 2: Append co-guides (if co_guide_ids permitted).
- FR-TNP-ASSIGN-05: Bulk assignment may be based on Company/Project Domain/Department/Cgpa/Batch filters, and T&P can preview affected students before applying.

D. Student Self-Service Additions (FR-STD-SELF-*)
- FR-STD-SELF-01: Gating: Students cannot accept project assignment or mark training as "started" until required fields are completed. Required field list configurable by Admin; suggested defaults: student_id, name, email, contact, department_id, batch, roll_no, registration_no, cgpa, emergency_contact, address.
- FR-STD-SELF-02: Students can add/edit Internship/Placement records for their own durations: company (link to companies table or free text), external_guide {name, email, phone, designation}, start_date, end_date, stipend, position, responsibilities, files (offer letter etc.), team_members (list).
- FR-STD-SELF-03: Adding team members:
  - Students can add other students by student_id (validation). The system will record team mapping in project_students or reports as appropriate.
  - External team members can be added as free-text entries with email/phone optional.
- FR-STD-SELF-04: If student adds or modifies internship/company/external guide details, T&P receives notification; T&P may confirm/verify.

E. Internal Guide / Faculty Features (FR-GUIDE-NEW-*)
- FR-GUIDE-01: Guides can list their assigned projects and students grouped by project.
- FR-GUIDE-02: Reporting entries:
  - Guides can create multiple report entries per student per project: each entry stores report_number (1..N), reporting_date, marks_out_of_15 (decimal allowed), comments, evidence file(s).
  - The UI forces report_number uniqueness per student/project (i.e., one Reporting 1, one Reporting 2, etc.). Guides can add at least up to 5 periodic reports; system enforces minimum 5 for "complete reporting" unless HOD/T&P relax.
- FR-GUIDE-03: Final report marks:
  - Guides can add final_project_report_mark (marks_out_of_25) for a student's final submission and attach final report file(s).
- FR-GUIDE-04: Internal marks storage:
  - System computes sum_of_periodic_reports (sum of up to 5 x 15 = 75) + final_project_report(25) to produce an internal_total_out_of_100 (or other mapping; see Business Rules for grade mapping).
- FR-GUIDE-05: Guides can edit reports until locked; T&P/HOD may lock or request revision. Editing history kept.
- FR-GUIDE-06: Guides can bulk-add reports via Excel (student_id, report_number, reporting_date, marks, comments).

F. HOD View & Filters (FR-HOD-NEW-*)
- FR-HOD-01: HOD sees a reporting matrix per department: Reporting 1..5 columns, Final Report column, Total (sum), Grade, HOD Approval status.
- FR-HOD-02: HOD can filter students by pending placement/training, pending reporting (missing any of reporting 1..5 or final), incomplete profiles, CGPA range, and more.
- FR-HOD-03: HOD can request corrections or send reminders to guides/students for missing or invalid reports; HOD can export the matrix to Excel.
- FR-HOD-04: HOD can mark “compute-proportionally” for partial reporting sets (if they choose to accept partial reports), otherwise grade remains withheld.

G. Misc / Common (FR-COMMON-*)
- FR-COMMON-01: Logging and audit: all bulk operations (bulk uploads, bulk assignments, bulk report imports) produce audit entries with import_id and summary.
- FR-COMMON-02: Notifications: trigger emails/in-app notifications for account creation, project assignment, guide assignment, missing-report reminders, and import reports.
- FR-COMMON-03: All new flows must respect role-based permissions (Laravel Policies/Gates).

--------------------------------------------------------------
4. Business Rules & Grade/Reporting Reconciliation
- Reporting Model:
  - periodic_reports: up to 5 entries per student per project (marks_out_of_15 each).
  - final_report: marks_out_of_25.
  - internal_total = sum(periodic_reports up to 5) + final_report => maximum 75 + 25 = 100.
- Grade Mapping (adapted to 0–100 internal_total):
  - A+ : 93 – 100  (maps approximately to earlier 70–75 out of 75)
  - A  : 80 – 92
  - B+ : 67 – 79
  - B  : 54 – 66
  - C  : 10 – 53
  - F  : 0 – 9
- Rationale: The earlier SRS specified internal exam ranges (A+:70–75, etc., out of 75). Because reporting + final now total 100, we re-scale grade boundaries proportionally and choose human-friendly bands. Exact mapping is configurable by Admin; the default mapping above is recommended. The mapping should be stored in config/grade_mapping or DB for dynamic updates.
- Partial reporting rule (configurable by Admin):
  - Default behavior: Grade withheld until all 5 periodic reports + final report exist.
  - Alternate (if HOD/T&P enabled): "compute-proportionally": compute (sum_of_reports_present / (number_of_required_reports_present * 15)) * 75 to normalize periodic reports to scale of 75, then add final 25 to form internal_total. This should be explicitly activated per student/project by HOD/T&P.
- Student gating: "Start Training" button is blocked if any required field is NULL or 'NA' is present in required fields (configurable). The UI lists missing required fields with links to edit.
- Student_id uniqueness: student.student_id is REQUIRED and must be unique; Excel import rejects or flags duplicates.

--------------------------------------------------------------
5. Data Requirements — Schema Changes & New Tables

Important: Preserve prior schema. The below items are additive or alterative. All new nullable fields accept NULL. Fields that accept 'NA' are VARCHAR/TEXT and allow string 'NA'.

5.1 students (changes)
- Add column student_id VARCHAR(64) NOT NULL UNIQUE (business identifier) — required for import flows.
- Add column profile_completed TINYINT(1) DEFAULT 0 — computed flag (0 = incomplete, 1 = complete).
- Add columns for emergency_contact, personal_email (optional), external_profiles JSON NULL.
- Migration-level (Laravel migration pseudo):
  - Schema::table('students', function (Blueprint $table) {
      $table->string('student_id', 64)->unique()->after('id');
      $table->boolean('profile_completed')->default(false)->after('academic_details');
      $table->string('emergency_contact',50)->nullable()->after('contact');
      $table->string('personal_email',255)->nullable()->after('email');
      $table->json('external_profiles')->nullable()->after('extra_profile');
    });

5.2 users (no structural change required), but ensure email unique and nullable rules match import logic.

5.3 evaluations_reports (new)
- Purpose: store each periodic report (reporting entries).
- Columns:
  - id BIGINT PK
  - project_id BIGINT NULL (FK projects.id)
  - student_id BIGINT NOT NULL (FK students.id)
  - student_student_id VARCHAR(64) NULL (denormalized for quick lookup)
  - guide_id BIGINT NULL (FK users.id)
  - report_number TINYINT NOT NULL (1..N)
  - reporting_date DATE NULL
  - marks_out_of_15 DECIMAL(4,2) NULL
  - comments TEXT NULL
  - evidence JSON NULL (file paths + meta)
  - locked TINYINT(1) DEFAULT 0
  - created_by BIGINT NULL
  - created_at, updated_at
- Unique constraint: UNIQUE(project_id, student_id, report_number)
- Migration snippet:
  - Schema::create('evaluations_reports', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
      $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
      $table->string('student_student_id',64)->nullable()->index();
      $table->foreignId('guide_id')->nullable()->constrained('users')->nullOnDelete();
      $table->tinyInteger('report_number')->unsigned();
      $table->date('reporting_date')->nullable();
      $table->decimal('marks_out_of_15',4,2)->nullable();
      $table->text('comments')->nullable();
      $table->json('evidence')->nullable();
      $table->boolean('locked')->default(false);
      $table->foreignId('created_by')->nullable()->constrained('users');
      $table->timestamps();
      $table->unique(['project_id','student_id','report_number']);
    });

5.4 final_project_reports (new) — alternative to storing final report in evaluations table
- Columns:
  - id, project_id, student_id, guide_id, marks_out_of_25 DECIMAL(5,2) NULL, comments, files JSON, locked, created_by, timestamps
- Migration:
  - Schema::create('final_project_reports', function (Blueprint $table) { ... });

5.5 student_team_members (new)
- Purpose: store team member links; supports students and external members.
- Columns:
  - id, project_id, student_id (FK) NULL, team_member_student_id (links other students.student_id) NULL, external_name VARCHAR NULL, external_email VARCHAR NULL, role VARCHAR NULL, created_by, timestamps
- Migration example included below.

5.6 bulk_import_logs (new)
- Purpose: log results of Excel imports
- Columns:
  - id, import_type ENUM('STUDENTS','ASSIGNMENT','REPORTS'), uploaded_by BIGINT, filename, total_rows, created_count, updated_count, skipped_count, errors JSON, status ENUM('DRY_RUN','COMPLETED','FAILED'), created_at, updated_at

5.7 assignment_requests / bulk_assignments (new)
- Purpose: track bulk assignments and actions
- Columns:
  - id, criteria JSON, action_type ENUM('GUIDE_ASSIGN','PROJECT_ASSIGN'), assigned_by, affected_count, applied boolean, import_log_id FK, timestamps

5.8 notes on foreign keys & indexes
- Ensure performance indexes on evaluations_reports.student_id, project_id, guide_id, report_number.
- Denormalize student_student_id (string) on report tables to simplify Excel exports and imports.

5.9 NA/NULL rules
- Columns that may accept NA: role_in_project, comments, external fields. Use VARCHAR/TEXT to accept 'NA'.
- Columns left empty in import become NULL.

--------------------------------------------------------------
6. API Endpoints — new & updated (routes & shapes)

Auth & User
- POST /api/login
- POST /api/logout
- GET /api/me

T&P / Admin Bulk Upload
- POST /api/admin/import/students
  - Description: Upload Excel/CSV; supports dry-run parameter ?dry_run=true
  - Request: multipart/form-data file: students.xlsx, body: {dry_run: bool, merge_strategy: 'overwrite'|'merge'|'skip'}
  - Response (dry-run): { import_id, total_rows, valid_rows, invalid_rows: [ {row_number, reason} ] }
  - Response (confirmed import): { import_id, total_rows, created_count, updated_count, skipped_count, errors: [...] }
- GET /api/admin/imports/{import_id}/report
  - Response: link to generated import report .xlsx & JSON summary

Bulk Assignment
- POST /api/tp/bulk/assign-guides
  - Request: { mode: 'filters'|'upload', filters: {...}, upload_file: file(optional), overwrite_existing: bool }
  - Response: { assignment_id, preview_count, applied_count, conflicts: [ {student_id, existing_guide_id} ] }

Project ID Suggestion
- GET /api/tp/projects/next-project-id?dept=CSE&year=2025&category=COMPANY_PROJECT
  - Response: { suggested_project_id: "TP-2025-CSE-0042" }

Reporting (Guide endpoints)
- POST /api/guides/reports/import (bulk via Excel)
- POST /api/guides/reports
  - Body: { project_id, student_id (student_id string), report_number, reporting_date (YYYY-MM-DD), marks_out_of_15, comments, evidence_files[] }
  - Response: created report payload
- PUT /api/guides/reports/{id} (if not locked)
- POST /api/guides/final-report
  - Body: { project_id, student_id, marks_out_of_25, comments, files[] }

Student self-service
- PUT /api/students/{student_id}/complete-profile
  - Body: fields to update
  - Response: { profile_completed: bool, missing_fields: [ ... ] }
- POST /api/students/{student_id}/internships
  - Body: { company_id or free_text_company, external_guide: { name, email, phone, designation }, start_date, end_date, stipend, role, documents[] }
- POST /api/students/{student_id}/team-members
  - Body: { project_id, student_ids: [ ... ], externals: [ {name,email,phone,designation} ] }

HOD endpoints
- GET /api/hod/department/{dept_id}/reporting-matrix?filters...
  - Response: array of students with fields: reporting1..report5 (marks+date), final_report, total, grade, profile_completed, placement_status

Exports
- GET /api/export/students?filters...&columns=...
  - Response: attachment .xlsx

Notifications
- POST /api/notifications/send (Admin/T&P/HOD triggers)

All endpoints require authentication (sanctum/session) and permission checks.

--------------------------------------------------------------
7. UI / UX Changes & Excel Templates

7.1 Student Listing & Export UI
- Top bar: Column selector (checkbox list), saved presets, Export button (.xlsx/.csv)
- Filters panel with fields described in FR-STDLIST-02
- Row context menu: view student, assign guide, export single row

Excel Export Representation
- Date format: YYYY-MM-DD
- NA representation: 'NA' (literal)
- NULL representation: blank cell (by default) or special marker if chosen
- Default columns (recommended):
  - Student ID, Name, Email, Personal Email, Phone, Department, Course, Batch, Roll No, Registration No, CGPA, Training Status, Project IDs (comma-separated), Guide IDs (comma-separated), Companies (comma-separated), Latest Placement Status, Attendance Percent (latest), Last Evaluation Date, Profile Completed (Y/N), HOD Approval, Created At

7.2 Bulk Student Upload Template (students_bulk_upload.xlsx)
- Header row (exact column headers, case-insensitive):
  - student_id (REQUIRED)
  - first_name
  - last_name
  - email (optional — if present, will be validated; otherwise auto-generated)
  - phone
  - roll_no
  - registration_no
  - department_code or department_id
  - course
  - batch
  - cgpa
  - dob (YYYY-MM-DD)
  - gender (M/F/O/NA)
  - address
  - emergency_contact
  - personal_email
  - extra_profile_json (optional)
- Sample row:
  - CHS2025CSE001,John,Doe,,9876543210,23CSE001,REG2025CSE001,CSE,B.Tech,2025,8.32,2003-05-06,M,"123 Street, City",9876512345,john.personal@example.com,"{""hostel"":""NA"",""scholarship"":""Yes""}"

7.3 Bulk Assignment Template (assignments_bulk_upload.xlsx)
- Header row:
  - student_id (REQUIRED)
  - guide_user_id (REQUIRED) or guide_email (alternative)
  - project_id (optional)
  - overwrite_existing (true/false optional)
- Sample:
  - CHS2025CSE001,45,TP-2025-CSE-0042,true

7.4 Bulk Report Upload Template (reports_bulk_upload.xlsx)
- Header row:
  - student_id (REQUIRED)
  - project_id
  - report_number (1..5)
  - reporting_date (YYYY-MM-DD)
  - marks_out_of_15
  - comments
- Sample:
  - CHS2025CSE001,TP-2025-CSE-0042,1,2025-06-15,12,"Good progress"

7.5 Export & Import validation rules summary
- Required fields flagged in UI.
- Date formats validated strictly (YYYY-MM-DD).
- student_id must be unique or present.
- Department must map to existing department_id (or code mapping allowed).
- Failures logged with row numbers and reasons.

--------------------------------------------------------------
8. Process Flows & Pseudocode

8.1 Auto user account generation from Excel (pseudocode)
- Input: parsed_row (dictionary)
- Precondition: parsed_row.student_id exists
- Steps:
  1. studentId = parsed_row['student_id'].trim()
  2. Start DB transaction
  3. student = Students::where('student_id', studentId)->first()
  4. if not student:
       student = Students::create({ student_id: studentId, ...other fields..., created_by: uploader_id })
     else:
       update student fields according to merge_strategy
  5. email = trimmedLower(studentId) + '@charusat.edu.in'
  6. user = Users::where('email', email)->first()
  7. if not user:
       tempPassword = Str::random(16)
       user = Users::create({ name: parsed_name_or_NA, email: email, password: Hash::make(tempPassword), is_active:1 })
       sendEmail(to: email, subject: 'Account Created', body: templateWithOneTimeLinkOrTempPassword(tempPassword))
     else:
       link user if student.user_id is null
  8. student.user_id = user.id; student.save()
  9. commit transaction
  10. log import status per row
- Note: If email derived already exists and belongs to different student_id -> flag for manual resolution.

8.2 Project ID next-sequence suggestion algorithm (pseudocode)
- Format default: TP-{YEAR}-{DEPT_CODE}-{NNNN} (NNNN zero-padded 4 digits)
- Input: dept_code (optional), year (default current year), category
- Steps:
  1. basePrefix = "TP-" + year + "-" + strtoupper(dept_code or 'GEN')
  2. Query: latest = projects->where('project_id','like', basePrefix+"%")->orderByDesc('created_at')->first()
  3. if latest:
       extract numericSuffix = parseInt(last 4 digits)
       next = numericSuffix + 1
     else:
       next = 1
  4. suggestedId = basePrefix + "-" + padLeft(next,4,'0')
  5. Return suggestedId
- Example: latest TP-2025-CSE-0042 => suggestion TP-2025-CSE-0043

8.3 Bulk assignment algorithm (filters & matching)
- Input: filterCriteria (company, project_domain, cgpa_range, batch, dept, tag), guide_id, overwrite boolean
- Steps:
  1. candidate_students = Students::query()->applyFilters(filterCriteria)->get()
  2. preview_count = candidate_students.count()
  3. For each student in candidate_students:
       existingGuide = ProjectStudent mapping or projects.guide_id where project assigned
       if existingGuide and not overwrite:
           add to conflicts list
           continue
       else:
           create/ update project_students mapping or set projects.guide_id accordingly
           notify guide and student
  4. return { preview_count, applied_count, conflicts }
- Excel bulk assignment uses the same validation logic in batch, transactional per-row with per-row error logging.

8.4 Reporting storage & aggregation
- When guide posts a report entry:
  - Validate report_number unique per project+student
  - Save to evaluations_reports
  - Trigger recompute of sums:
    - periodic_sum = evaluations_reports->where(student_id,project_id)->takeUpTo(5)->sum(marks_out_of_15)
    - final_mark = final_project_reports->where(student_id,project_id)->first()->marks_out_of_25
    - if scoring_strategy == 'all_5_required' and periodic_count < 5 or final_mark is null -> internal_status = 'INCOMPLETE'
    - else if 'compute_proportionally' -> normalized_periodic = (periodic_sum / (periodic_count * 15)) * 75 ; internal_total = normalized_periodic + final_mark
    - else internal_total = periodic_sum + final_mark (if periodic_count == 5)
  - Map internal_total to grade via grade_mapping table/config and store computed grade in evaluations_summary table (or in projects_students join row).
  - Notify HOD/T&P if evaluation complete.

--------------------------------------------------------------
9. Acceptance Criteria & Test Cases (actionable)

9.1 Bulk Student Upload & Account Generation
- AC-BULK-01: Given a well-formed Excel containing 10 unique student rows, after confirmed import:
  - 10 student records exist with corresponding students.student_id values.
  - 10 user accounts exist with emails studentidinsmall@charusat.edu.in.
  - Each user receives an email containing a secure one-time login link or temporary password.
  - Import log shows created_count = 10, updated_count = 0, skipped_count = 0.
- Test steps: run import in dry-run; confirm validation OK; run import; verify DB rows + email receipts (can be intercepted in test env).

9.2 Conflict Handling on Bulk Upload
- AC-BULK-02: Given an Excel with duplicate student_id rows or a row with missing student_id:
  - The import dry-run marks duplicates and missing student_id rows as invalid with reasons.
  - The confirmed import does not create records for invalid rows and logs errors correctly.

9.3 Project ID Suggestion
- AC-PROJECTID-01: If existing projects for TP-2025-CSE run up to TP-2025-CSE-0045, GET /api/tp/projects/next-project-id?dept=CSE&year=2025 returns TP-2025-CSE-0046.

9.4 Bulk Guide Assignment
- AC-BULK-GUIDE-01: Using filters (department=CSE, cgpa>=7.0), preview shows 20 candidates; performing bulk assignment with overwrite=false only assigns students without existing guide; conflicts list includes students with pre-existing guides.

9.5 Guide Reporting (periodic + final)
- AC-GUIDE-REPORT-01: Guide submits 5 reports for student with marks 12, 13.5, 14, 15, 11 and final project mark 22. System computes:
  - periodic_sum = 12 + 13.5 + 14 + 15 + 11 = 65.5 (out of 75)
  - internal_total = 65.5 + 22 = 87.5 (out of 100)
  - Grade mapping yields 'A' (per default mapping 80–92).
- AC-GUIDE-REPORT-02: If only 3 periodic reports and final present and default "all_5_required" policy, final grade withheld and status INCOMPLETE.

9.6 Student Gating
- AC-STUDENT-GATE-01: Student attempts to start training but profile_missing required field roll_no -> UI blocks action, shows missing field list; after student fills roll_no, action allowed.

9.7 HOD Reporting Matrix & Filters
- AC-HOD-01: HOD filters for "pending reporting" returns all students with less than 5 periodic reports or missing final mark. Export yields .xlsx with Reporting 1..5 columns showing marks/date or blank if missing.

9.8 Excel Export NA/NULL handling
- AC-EXPORT-01: Export of student where company is NA and some field NULL results in Excel cell for company showing 'NA' and blank cell for NULL field.

--------------------------------------------------------------
10. Migration & Data-migration Considerations
- Add students.student_id for all existing students:
  - Migration script to populate student_id from existing roll_no or registration_no (preferred) or generate deterministic value (e.g., 'LEGACY-' + id) — admin must review.
- Create evaluations_reports and final_project_reports tables; backfill historic evaluations from existing evaluations table if any: map single evaluations entries to periodic report 1 or final report as appropriate (requires manual mapping).
- Run import in staging first; provide rollback SQL for migrations that might affect production.
- Add config flag to toggle strict "student_id required" mode for backward compatibility during migration.

--------------------------------------------------------------
11. Implementation Plan (prioritized steps with complexity & rough time estimates)

Priority 1 — Core Data + Import & Account Generation
1. Add students.student_id column, profile_completed and emergency columns; migration + backfill script (Complexity: Medium; Estimate: 6-12 hours)
2. Create bulk_import_logs and import infrastructure (Complexity: Medium; Estimate: 6-10 hours)
3. Implement POST /api/admin/import/students with dry-run and confirmed import flows (Complexity: High; Estimate: 12-24 hours)
4. Implement email / one-time link mechanics for user creds (Complexity: Medium; Estimate: 6-12 hours)

Priority 2 — Reporting Model & Aggregation
5. Create evaluations_reports and final_project_reports tables + migration (Complexity: Medium; Estimate: 6-12 hours)
6. Guides UI + API endpoints for adding/editing reports (Complexity: Medium; Estimate: 12-20 hours)
7. Aggregation service to compute internal_total & grade (Complexity: Medium; Estimate: 6-10 hours)

Priority 3 — Bulk Assignment & Project ID Suggestion
8. Implement project ID suggestion API (Complexity: Low; Estimate: 2-4 hours)
9. Bulk assignment UI and API (filters + preview + confirm) (Complexity: High; Estimate: 12-24 hours)

Priority 4 — Student Gating & HOD Matrix
10. Student profile gating UI & API (Complexity: Low; Estimate: 4-8 hours)
11. HOD reporting matrix UI + export (Complexity: Medium; Estimate: 10-16 hours)

Priority 5 — Extras & Hardening
12. Excel templates, validation rules, import logs exporter, audits; QA & testing (Complexity: Medium; Estimate: 8-16 hours)
13. Documentation, seeders, and migration runbooks (Complexity: Low; Estimate: 4-8 hours)

Total approximate implementation: 80–150 hours depending on existing codebase, test coverage, and UI complexity.

--------------------------------------------------------------
12. Appendix — sample Excel rows, migration snippets, example controller endpoint

12.1 Sample bulk student upload header & sample CSV row (CSV representation)
Header:
student_id,first_name,last_name,email,phone,roll_no,registration_no,department_code,course,batch,cgpa,dob,gender,address,emergency_contact,personal_email,extra_profile_json

Sample row:
CHS2025CSE001,John,Doe,,9876543210,23CSE001,REG2025CSE001,CSE,B.Tech,2025,8.32,2003-05-06,M,"123 Street, City",9876512345,john.personal@example.com,"{""hostel"":""B6"",""scholarship"":""NA""}"

12.2 Example Laravel migration snippet (students table alteration)
```php
// migration: 2025_12_09_add_student_id_and_profile_fields_to_students.php
public function up()
{
    Schema::table('students', function (Blueprint $table) {
        $table->string('student_id', 64)->unique()->after('id');
        $table->boolean('profile_completed')->default(false)->after('academic_details');
        $table->string('emergency_contact',50)->nullable()->after('contact');
        $table->string('personal_email',255)->nullable()->after('email');
        $table->json('external_profiles')->nullable()->after('extra_profile');
    });
}
```

12.3 Example Laravel migration snippet (evaluations_reports)
```php
// migration: 2025_12_09_create_evaluations_reports_table.php
public function up()
{
    Schema::create('evaluations_reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
        $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
        $table->string('student_student_id',64)->nullable()->index();
        $table->foreignId('guide_id')->nullable()->constrained('users')->nullOnDelete();
        $table->tinyInteger('report_number')->unsigned();
        $table->date('reporting_date')->nullable();
        $table->decimal('marks_out_of_15',4,2)->nullable();
        $table->text('comments')->nullable();
        $table->json('evidence')->nullable();
        $table->boolean('locked')->default(false);
        $table->foreignId('created_by')->nullable()->constrained('users');
        $table->timestamps();
        $table->unique(['project_id','student_id','report_number']);
    });
}
```

12.4 Example Laravel migration snippet (final_project_reports)
```php
public function up()
{
    Schema::create('final_project_reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
        $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
        $table->foreignId('guide_id')->nullable()->constrained('users')->nullOnDelete();
        $table->decimal('marks_out_of_25',5,2)->nullable();
        $table->text('comments')->nullable();
        $table->json('files')->nullable();
        $table->boolean('locked')->default(false);
        $table->foreignId('created_by')->nullable()->constrained('users');
        $table->timestamps();
        $table->unique(['project_id','student_id']);
    });
}
```

12.5 Example migration snippet (bulk_import_logs)
```php
public function up()
{
    Schema::create('bulk_import_logs', function (Blueprint $table) {
        $table->id();
        $table->enum('import_type', ['STUDENTS','ASSIGNMENT','REPORTS']);
        $table->foreignId('uploaded_by')->constrained('users');
        $table->string('filename',255);
        $table->integer('total_rows')->unsigned()->default(0);
        $table->integer('created_count')->unsigned()->default(0);
        $table->integer('updated_count')->unsigned()->default(0);
        $table->integer('skipped_count')->unsigned()->default(0);
        $table->json('errors')->nullable();
        $table->enum('status', ['DRY_RUN','COMPLETED','FAILED'])->default('DRY_RUN');
        $table->timestamps();
    });
}
```

12.6 Example Controller Endpoint (Excel import) — high-level description
Controller: Admin\StudentImportController@import
- Route: POST /api/admin/import/students
- Auth: auth + permission 'import students'
- Request:
  - file: multipart/form-data (Excel)
  - dry_run: boolean (optional)
  - merge_strategy: 'overwrite'|'merge'|'skip' (default 'merge')
- Flow:
  1. Validate request and file mime type.
  2. Parse file (use maatwebsite/excel or PhpSpreadsheet)
  3. For each row validate required fields (student_id). Build validation result array.
  4. If dry_run == true: return import summary with row-level errors and preview of created/updated changes (no DB writes).
  5. If dry_run == false: Begin DB transaction; iterate rows and create/update Students + Users as per pseudocode (auto-account generation). On per-row error, log error and continue (no abort unless critical).
  6. Save bulk_import_log record and return response with import_id and link to detailed import report.
- Response: 200 OK with JSON import summary and link to import report (.xlsx) for download.

12.7 Example API request / response (project id suggestion)
Request:
- GET /api/tp/projects/next-project-id?dept=CSE&year=2025
Response:
{
  "suggested_project_id": "TP-2025-CSE-0043"
}

12.8 Example mapping & grade computation sample (worked example)
- Periodic reports: [12, 13.5, 14, 15, 11] => periodic_sum = 65.5
- Final project: 22
- internal_total = 65.5 + 22 = 87.5 => Grade 'A'

--------------------------------------------------------------
13. Backward compatibility notes & breaking changes
- Adding students.student_id is additive. However, imports that require student_id may be blocked until student_id populated. Migration plan must backfill student_id for existing records.
- New reporting tables are additive; existing evaluations table may be deprecated or migrated.
- Ensure API versioning if existing public APIs change.

--------------------------------------------------------------
14. Next steps & Recommendations
- Implement migrations and backfill on a staging environment first.
- Build automated tests for import logic, project id generation and grade computation.
- Provide detailed UI wireframes for bulk-upload preview/confirmation pages.
- Ensure file upload scanning & rate-limiting for import endpoints.

--------------------------------------------------------------
End of updated SRS (v1.1)

If you want, I can:
- Generate the actual Laravel migration files + seeders for the tables above.
- Scaffold the StudentImportController and services (parsing, validation, user creation).
- Produce sample Excel files (.xlsx) to download (I can provide CSV/TSV content here).
Which should I do next?
