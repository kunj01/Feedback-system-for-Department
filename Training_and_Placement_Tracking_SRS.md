# Training & Placement Tracking Software — Software Requirements Specification (SRS)

Version: 1.0  
Date: 2025-12-06  
Author: GitHub Copilot (for sbs2083)

Contents
1. Introduction
2. System Overview & Scope
3. Stakeholders & User Roles
4. Functional Requirements (by role)
5. Business Rules & Assumptions
6. Non-functional Requirements
7. Data Requirements — Logical Data Model & Tables (MySQL)
8. API Endpoints (Laravel REST resources)
9. UI / UX — Screens & Workflows
10. Security, Authentication & Authorization
11. File Uploads & Storage
12. Notifications
13. Reporting & Dashboards
14. Testing Strategy & Acceptance Criteria
15. Deployment & Operational Considerations
16. Appendix: ER Diagram (text), Sample SQL snippets, Glossary

---

1. Introduction
This SRS documents requirements for a Training & Placement (T&P) Tracking System built with Laravel (PHP) and MySQL. The system supports five role types: Admin, T&P Officer, Head of Department (HOD / Head), Guide, and Student. It manages projects/training assignments, evaluations, multi-company placements, progress tracking and reporting.

Important rules (system-wide)
- If any field is empty → treat as NULL in database.
- If the field is marked "NA" → the literal string "NA" is a valid entry (for fields where NA is permitted).
- Students may have multiple placement entries; T&P Officer confirms final placement(s).
- HOD and T&P can also act as Guides (roles can be combined).
- Role-based access control implemented via Laravel Policies & Gates (or spatie/laravel-permission).

2. System Overview & Scope
Scope:
- Manage users, roles, permissions and master data (companies, departments, courses).
- Assign projects/training (company or in-house), singly or to groups.
- Track evaluations (marks out of 15), attendance, progress notes, and internal exam grade.
- Accept uploads: weekly/monthly reports, logbooks, offer letters, completion certificates.
- Allow multiple placement records per student with final confirmation by T&P Officer.
- Provide dashboards and reports for Admin, HOD, T&P and Guides.
- Notifications by email and on-platform.

Out of scope (initial release):
- Integration with external placement portals (unless requested).
- Full LMS features (quizzes, grading beyond internal exam).

3. Stakeholders & User Roles
- Admin: system owner, master-data manager, report generator, user & role manager.
- T&P Officer: assigns Project IDs, updates placement(s), finalizes placements, reviews evaluations.
- Head (HOD): views progress, comments/approves evaluations, may act as Guide.
- Guide: assigned to students or groups; evaluates, enters marks (out of 15), grade, comments.
- Student: views assigned project, uploads reports, sees placement status and history.

4. Functional Requirements (by role)

4.1 Admin (FR-A-*)
- FR-A-01: CRUD for users, assign roles (Admin, T&P, Head, Guide, Student) and assign departments.
- FR-A-02: CRUD for master data: departments, courses, companies, training categories.
- FR-A-03: Configure system-wide settings (max group size, project ID format, file upload limits).
- FR-A-04: Generate PDF/CSV reports: placements, project statuses, evaluation summaries, guide workloads.
- FR-A-05: View audit logs (user actions, uploads, evaluation edits).

4.2 T&P Officer (FR-T*)
- FR-T-01: Create Project entries (auto-generate Project ID) — categories: Company Project or In-House Training.
- FR-T-02: Assign projects to single students or groups; maintain project_students mapping.
- FR-T-03: Update student_placements (create multiple entries), indicate final confirmation flag for final placement record.
- FR-T-04: Review and comment on evaluation reports; mark placements as confirmed/unconfirmed.
- FR-T-05: Search/filter students by placement status, project, company, department.

4.3 Head of Department (FR-H*)
- FR-H-01: View dashboards: department progress, guide performance summary, students' statuses.
- FR-H-02: Approve or comment on evaluation entries submitted by guides.
- FR-H-03: Optionally act as Guide: can be assigned to projects and perform evaluations.

4.4 Guide (FR-G*)
- FR-G-01: Be assigned to one or more students/groups.
- FR-G-02: Enter periodic evaluations (online/offline): marks (integer/decimal) out of 15.
- FR-G-03: Enter attendance percentage, progress notes, observations, comments.
- FR-G-04: Assign Internal Exam Grade using mapping (see Business Rules).
- FR-G-05: Upload evaluation documents (feedback forms, rubrics).
- FR-G-06: Edit evaluations until locked (locking policy configurable); HOD/T&P can request revision.

4.5 Student (FR-S*)
- FR-S-01: View project details, assigned guide(s), and placement status/history.
- FR-S-02: Upload weekly/monthly reports and training logs (file types configurable).
- FR-S-03: Upload documents: Offer letter, Completion certificate, Joining letter, etc.
- FR-S-04: Receive notifications (email & in-app); view evaluation feedback.

4.6 Common Features
- FR-C-01: Search and filter across students, projects, companies.
- FR-C-02: Audit trail of changes to evaluations and placements.
- FR-C-03: Pagination, sorting, CSV/PDF export for lists.
- FR-C-04: Role-based dashboards.

5. Business Rules & Assumptions
- Project ID: auto-generated, unique format e.g., TP-{YEAR}-{DEPT_CODE}-{0001}.
- Project Category: Enum {COMPANY_PROJECT, IN_HOUSE}.
- Project Type: group or single; group size configurable by Admin.
- Evaluation marks: recorded out of 15 (store as decimal(4,2)), null allowed.
- Internal Exam: maximum marks = 75 (this aligns to grade ranges provided). Internal exam marks stored out of 75. If internal exam is NA, store "NA".
- Grade mapping (business rule): internal_exam_marks (0–75) map to grades:
  - A+: 70–75
  - A: 60–69
  - B+: 50–59
  - B: 40–49
  - C: 10–39
  - If marks < 10: "F" (Fail) (optional)
  - If internal exam field is NA → grade can be "NA".
- If any field is empty, treat as NULL.
- For fields that can be NA, accept literal "NA" string.
- A student may have multiple placement rows; T&P Officer marks one as confirmed_final = true (boolean). Only T&P can toggle final confirmation.

6. Non-functional Requirements
- NFR-01: Platform: Laravel 10+ (PHP 8.1+), MySQL 8.x.
- NFR-02: Authentication: use Laravel Sanctum for SPA or default session-based for server-rendered.
- NFR-03: Performance: list endpoints respond under 500ms for <10k records; paginated endpoints required.
- NFR-04: Scalability: prepare for sharding/replication (read-replica friendly).
- NFR-05: Availability: 99.5% uptime target.
- NFR-06: Security: HTTPS required, hashed passwords (bcrypt/argon2), RBAC via policies/gates.
- NFR-07: Backups: daily DB backups, weekly full backups.
- NFR-08: Logging: Laravel logging to files and optional external provider.
- NFR-09: File storage: configurable local or S3-compatible; file virus scanning recommended.

7. Data Requirements — Logical Data Model & Tables (MySQL)
Every nullable field must accept NULL. Wherever the requirement allows "NA", the column accepts the text "NA" (VARCHAR/TEXT).

Notes:
- Use Laravel migrations to create tables with proper constraints.
- Use InnoDB and foreign keys.

7.1 users
- id: BIGINT UNSIGNED AUTO_INCREMENT PK
- name: VARCHAR(255) NOT NULL
- email: VARCHAR(255) NOT NULL UNIQUE
- password: VARCHAR(255) NOT NULL
- phone: VARCHAR(20) NULL
- role_id: BIGINT UNSIGNED NULL (deprecated if using roles table) — accept NULL
- department_id: BIGINT UNSIGNED NULL
- is_active: TINYINT(1) DEFAULT 1
- extra_profile: JSON NULL (for NA-capable fields, can store "NA")
- created_at, updated_at, deleted_at (soft deletes)

7.2 roles
- id, name (Admin, TnP, Head, Guide, Student), guard_name, created_at, updated_at

7.3 permissions
- id, name, guard_name, created_at, updated_at

(If using spatie/laravel-permission, maintain model tables and relationships.)

7.4 departments
- id, code VARCHAR(20) NULL (e.g., CSE), name VARCHAR(255), head_user_id BIGINT NULL, created_at, updated_at

7.5 students (detailed)
- id PK
- user_id (FK users.id) NULL — linking to user account; can be NULL for legacy/import
- roll_no VARCHAR(50) NULL
- registration_no VARCHAR(50) NULL
- dob DATE NULL
- gender ENUM('M','F','O') NULL
- father_name VARCHAR(255) NULL
- mother_name VARCHAR(255) NULL
- address TEXT NULL
- contact VARCHAR(50) NULL
- email VARCHAR(255) NULL
- department_id BIGINT NULL
- course VARCHAR(100) NULL
- batch YEAR NULL
- cgpa DECIMAL(4,2) NULL
- academic_details JSON NULL
- training_status ENUM('NOT_ASSIGNED','IN_TRAINING','COMPLETED') DEFAULT 'NOT_ASSIGNED'
- created_at, updated_at

7.6 companies
- id PK
- name VARCHAR(255) NOT NULL
- type ENUM('RECRUITER','TRAINER','NA') DEFAULT 'RECRUITER'
- address TEXT NULL
- contact_person VARCHAR(255) NULL
- contact_email VARCHAR(255) NULL
- website VARCHAR(255) NULL
- notes TEXT NULL
- created_at, updated_at

7.7 projects
- id PK
- project_id VARCHAR(50) UNIQUE NOT NULL (auto-generated TP-YYYY-DEP-XXXX)
- title VARCHAR(255) NULL
- description TEXT NULL
- category ENUM('COMPANY_PROJECT','IN_HOUSE') NOT NULL
- company_id BIGINT NULL (nullable when IN_HOUSE)
- guide_id BIGINT NULL (FK users.id) — nullable; HOD/T&P may also be guide
- co_guide_ids JSON NULL (for multiple guides) — optional
- start_date DATE NULL
- end_date DATE NULL
- status ENUM('OPEN','IN_PROGRESS','COMPLETED','CANCELLED') DEFAULT 'OPEN'
- is_group TINYINT(1) DEFAULT 0
- max_group_size INT NULL
- created_by BIGINT NULL
- created_at, updated_at

7.8 project_students (many-to-many mapping)
- id PK
- project_id BIGINT NOT NULL FK -> projects.id
- student_id BIGINT NOT NULL FK -> students.id
- assigned_on DATETIME NULL
- role_in_project VARCHAR(100) NULL (e.g., "Leader", can be "NA")
- UNIQUE(project_id, student_id)

7.9 student_placements
- id PK
- student_id BIGINT NOT NULL FK -> students.id
- company_id BIGINT NULL
- project_id BIGINT NULL
- offer_date DATE NULL
- status ENUM('OFFERED','JOINED','REJECTED','WITHDRAWN','NA') DEFAULT 'OFFERED'
- package DECIMAL(10,2) NULL
- position VARCHAR(255) NULL
- joining_date DATE NULL
- documents JSON NULL (paths + meta)
- confirmed_final TINYINT(1) DEFAULT 0 -- only toggled by T&P Officer
- remarks TEXT NULL
- created_by BIGINT NULL
- created_at, updated_at

7.10 evaluations
- id PK
- project_id BIGINT NULL FK
- student_id BIGINT NULL FK
- guide_id BIGINT NULL FK
- evaluation_date DATE NULL
- mode ENUM('ONLINE','OFFLINE','NA') DEFAULT 'ONLINE'
- marks_out_of_15 DECIMAL(4,2) NULL -- store marks the Guide enters
- internal_exam_marks DECIMAL(5,2) NULL -- expectation: out of 75; accept NULL/NA
- internal_exam_grade VARCHAR(5) NULL -- 'A+', 'A', etc. Accept 'NA'
- attendance_percent DECIMAL(5,2) NULL
- remarks TEXT NULL
- locked TINYINT(1) DEFAULT 0
- approved_by_head TINYINT(1) DEFAULT 0
- head_comments TEXT NULL
- created_at, updated_at

7.11 reports_logs (uploads)
- id PK
- student_id BIGINT NULL
- project_id BIGINT NULL
- uploaded_by BIGINT NULL (user id)
- file_path VARCHAR(1024) NULL
- original_name VARCHAR(255) NULL
- file_type VARCHAR(100) NULL
- period_start DATE NULL
- period_end DATE NULL
- status ENUM('PENDING','REVIEWED','REJECTED') DEFAULT 'PENDING'
- notes TEXT NULL
- created_at, updated_at

7.12 notifications
- id PK
- user_id BIGINT NULL
- title VARCHAR(255) NULL
- body TEXT NULL
- link VARCHAR(1024) NULL
- is_read TINYINT(1) DEFAULT 0
- channel ENUM('IN_APP','EMAIL','SMS') DEFAULT 'IN_APP'
- sent_at DATETIME NULL
- created_at, updated_at

7.13 audits / logs
- id PK
- user_id BIGINT NULL
- action VARCHAR(255) NOT NULL
- resource_type VARCHAR(100) NULL
- resource_id BIGINT NULL
- old_value JSON NULL
- new_value JSON NULL
- created_at DATETIME

Indexes + Foreign Keys
- Add FK constraints for FK fields. Add indexes for frequently queried columns (student_id, project_id, company_id, guide_id, status fields, created_at).

Nullability and "NA"
- Wherever a field can be "NA" (e.g., role_in_project, remarks, company fields for in-house), accept either NULL or the string 'NA'. Enforce via front-end UI choices and validation rules: if user selects "NA" the literal "NA" will be stored (not NULL).

8. API Endpoints (Laravel REST resources)
Use resource controllers and route names. Suggested endpoints (examples):

Auth
- POST /api/login
- POST /api/logout
- GET /api/me

Users & Roles (admin-only)
- GET /api/admin/users
- POST /api/admin/users
- PUT /api/admin/users/{id}
- DELETE /api/admin/users/{id}
- GET /api/admin/roles
- POST /api/admin/roles

Students
- GET /api/students
- POST /api/students
- GET /api/students/{id}
- PUT /api/students/{id}
- GET /api/students/{id}/placements
- GET /api/students/{id}/evaluations
- GET /api/students/{id}/reports

Projects
- GET /api/projects
- POST /api/projects (auto-generate project_id)
- GET /api/projects/{id}
- PUT /api/projects/{id}
- POST /api/projects/{id}/assign-students (accept array)
- DELETE /api/projects/{id}/student/{student_id}

Evaluations
- GET /api/evaluations
- POST /api/evaluations
- PUT /api/evaluations/{id} (if not locked)
- POST /api/evaluations/{id}/approve (HOD)
- POST /api/evaluations/{id}/lock (Guide/Admin)

Placements
- GET /api/placements
- POST /api/placements
- PUT /api/placements/{id}
- POST /api/placements/{id}/confirm-final (T&P only)

Reports / Uploads
- POST /api/uploads/report (multipart) — store file, metadata
- GET /api/uploads/{id}/download

Notifications
- GET /api/notifications
- POST /api/notifications/send (Admin/TnP to trigger)

Exports
- GET /api/export/students (CSV/PDF)
- GET /api/export/evaluations

All endpoints protected by middleware: auth:sanctum and authorization via policies/gates. Use request validation classes.

9. UI / UX — Screens & Workflows
Primary screens:
- Login / Forgot Password
- Admin Dashboard (user stats, active projects, pending placements)
- T&P Dashboard (projects needing assignments, pending confirmations)
- HOD Dashboard (department progress, pending approvals)
- Guide Dashboard (assigned students, pending evaluations)
- Student Dashboard (my project(s), uploads, placement history)
- Project Details (project info, students, guide, documents)
- Student Profile (master data, placements, evaluations, uploads)
- Evaluation Form (marks out of 15, internal exam marks, grade dropdown or computed)
- Upload Form (file chooser, tag as weekly/monthly/logbook)
- Notifications panel

Workflows:
- T&P creates a project → optionally assigns students → Guide notified → Guides add evaluations and upload feedback → HOD reviews → T&P updates placements → Student uploads offer letter → T&P confirms final placement.
- When Guide enters evaluation marks, system computes grade (or Guide enters grade). HOD may approve.

10. Security, Authentication & Authorization
- Authentication: Laravel Sanctum (for SPA/mobile) or session-based.
- Passwords hashed (bcrypt/argon2).
- 2FA is optional for Admin/T&P.
- Authorization: Laravel Policies & Gates, or use spatie/laravel-permission to map roles to permissions.
- CSRF protection for web routes.
- File uploads validated by MIME type and size; stored outside web root or protected by signed URLs.
- Input validation & sanitization via Laravel Form Requests.
- Rate limiting for sensitive endpoints.

11. File Uploads & Storage
- Supported types: PDF, DOC/DOCX, ZIP, JPG/PNG (configurable).
- Max file size: configurable by Admin (default 20MB).
- Storage driver: local or S3.
- Store metadata in reports_logs: original_name, storage_path, mime, uploaded_by, period.
- Virus/mime checking recommended on upload.
- If the file field is left empty → NULL.

12. Notifications
- Channels: in-app (notifications table + UI), email (SMTP via Laravel Mail).
- Events:
  - Project assigned to Guide/Student
  - New evaluation posted
  - Evaluation approved/rejected
  - Placement offered/confirmed
  - Document uploaded (T&P / Guide notified)
- Notification templates configurable.

13. Reporting & Dashboards
- Guide-wise progress summary: number of students, average marks, pending evaluations.
- Student-wise evaluation summary: list of evaluations, marks, grades, attendance, placement status.
- HOD overview: department aggregated stats, guide productivity, placement rates.
- Admin reports: historical placements, company-wise hires, export to CSV/PDF.

14. Testing Strategy & Acceptance Criteria
Testing types:
- Unit tests (Laravel/PHPUnit) for models and business rules (grade calculations, placement finalization).
- Feature tests for controllers and APIs (login, CRUD, upload).
- Integration tests (file storage, email).
- Security tests (RBAC enforcement).
Acceptance criteria (examples):
- Admin can create a project and project_id follows format.
- Guide can enter marks out of 15; records saved and visible to student.
- T&P can add multiple placements for student and set one as confirmed_final.
- If a field left empty → DB record shows NULL.
- If a field is set to NA → DB stores string 'NA'.
- File upload test: upload stored, metadata saved, download link works.
- Notifications dispatched on assignment and placement confirmation.

15. Deployment & Operational Considerations
- Environment variables: DB credentials, mail driver, storage driver, APP_KEY.
- Migrations & seeders to bootstrap roles and default admin user.
- Scheduler (cron) for nightly reports/backup and notifications.
- Backups: dump DB daily, rotate 7 days.
- Monitoring: set up uptime & error monitoring (Sentry / Bugsnag).
- CI/CD: run tests on push, migrations on deploy, zero-downtime recommended.

16. Appendix

16.1 ER Diagram (textual)
users --< roles (via pivot)  
departments --< students  
students --< project_students >-- projects  
projects --< evaluations  
students --< student_placements  
students --< reports_logs  
users --< audits

16.2 Grade Calculation (business logic)
- internal_exam_max = 75
- If internal_exam_marks is NOT NULL and not 'NA':
  - if 70 <= marks <= 75 → grade = 'A+'
  - if 60 <= marks <= 69 → grade = 'A'
  - if 50 <= marks <= 59 → grade = 'B+'
  - if 40 <= marks <= 49 → grade = 'B'
  - if 10 <= marks <= 39 → grade = 'C'
  - else grade = 'F'
- Optionally combine marks_out_of_15 and internal_exam_marks to compute overall performance for dashboards. Example: weight internal exam 75 points + evaluation 15 points => total out of 90.

16.3 Sample MySQL CREATE TABLE (evaluation) — illustrative migration schema
```sql
CREATE TABLE `evaluations` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `project_id` BIGINT NULL,
  `student_id` BIGINT NULL,
  `guide_id` BIGINT NULL,
  `evaluation_date` DATE NULL,
  `mode` ENUM('ONLINE','OFFLINE','NA') DEFAULT 'ONLINE',
  `marks_out_of_15` DECIMAL(4,2) NULL,
  `internal_exam_marks` DECIMAL(5,2) NULL,
  `internal_exam_grade` VARCHAR(5) NULL,
  `attendance_percent` DECIMAL(5,2) NULL,
  `remarks` TEXT NULL,
  `locked` TINYINT(1) DEFAULT 0,
  `approved_by_head` TINYINT(1) DEFAULT 0,
  `head_comments` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (guide_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

16.4 Migration & Seeder Notes
- Seeder to create default roles and an Admin account.
- Seeder to add sample departments, companies for demo.
- Provide sample users and a demo dataset for acceptance testing.

16.5 Glossary
- T&P: Training & Placement
- HOD / Head: Head of Department
- Guide: Faculty mentor or industrial guide
- Project: training engagement (company or in-house)
- Placement: job offer / internship record for a student

---

What I did for you
- I produced a complete SRS tailored to your requirements for a Laravel + MySQL Training & Placement Tracking System. The document includes detailed functional requirements for each role (Admin, T&P, Head, Guide, Student), data model and MySQL table designs (with nullability/NA handling), API endpoints, UI screens and workflows, security/backup/deployment recommendations, reporting, and acceptance criteria.

What's next (suggested immediate actions)
- I can generate Laravel migration files and sample seeders for the tables above.
- I can scaffold Laravel models, controllers, policies, and migration files (one-to-one with the database schema here).
- I can produce sample UI wireframes or a Postman collection for the API endpoints.
- Tell me which of the next steps you want me to do first (e.g., create migrations + seeders, scaffold controllers & policies, or produce API documentation / Postman tests), and indicate the GitHub repository (owner/repo) where you want code pushed. If you want migrations created in a repo, please provide the repo in owner/name format and confirm you want me to create a pull request or files.
