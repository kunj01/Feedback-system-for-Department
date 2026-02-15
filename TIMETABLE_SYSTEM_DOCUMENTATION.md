# Timetable & Batch Management System - Documentation

## Overview

A complete timetable and batch management system integrated with the Student Feedback Analysis System. This module allows administrators to manage academic schedules, organize students into batches, and automatically generate feedback allocations based on the timetable.

## Features Implemented

### 1. Database Schema ✅

#### Tables Created:
- **divisions** - Manages semester-branch-division combinations (e.g., 4-IT-1)
- **batches** - Manages practical batches (A1, B1, C2, etc.) per division
- **timetable** - Stores class schedules with time slots, subjects, faculty, and rooms
- **subjects** (updated) - Added branch, subject_type, credits fields
- **faculties** (updated) - Added faculty_name, short_code, email, contact fields
- **students** (updated) - Added enrollment_no, division_id, batch_id, semester, branch

#### Relationships:
- Division → hasMany → Batches, Students, Timetable Entries
- Batch → belongsTo → Division; hasMany → Students
- Timetable → belongsTo → Division, Subject, Faculty, Batch
- Student → belongsTo → Division, Batch

### 2. Admin Features

#### a) Timetable Management (`/admin/timetable`)
- **Filter by**: Semester, Branch, Division
- **View**: Weekly timetable grid (Monday-Saturday)
- **Time Slots**: 09:10-10:10 through 03:20-04:20
- **Add/Edit/Delete**: Timetable entries
- **Visual Coding**:
  - Blue: Lecture classes (whole division)
  - Green: Lab classes (specific batch)
- **Display Format**: `DBMS - RSK - 609 - A1`
  - Subject Code - Faculty Short Code - Room No - Batch (if lab)

#### b) Batch Management (`/admin/batches`)
- **View**: All divisions and their batches
- **Create**: New batches for divisions
- **View Students**: Click batch to see enrolled students
- **Statistics**: Student count per batch
- **Delete**: Remove empty batches

#### c) Division Management (`/admin/divisions`)
- **Auto-creation**: Divisions created automatically during student upload
- **Format**: `{semester}-{branch}-{division_number}` (e.g., 4-IT-2)
- **Manage**: Activate/deactivate divisions

#### d) Student Bulk Upload (`/admin/students/upload`)
- **CSV Upload**: Bulk import students with division and batch assignment
- **Template Download**: Pre-formatted CSV with sample data
- **Auto-creation**: Creates divisions and batches if they don't exist
- **Validation**: Checks for duplicates, validates data
- **Update Mode**: Updates existing students by enrollment number

**CSV Format:**
```csv
enrollment_no,name,semester,branch,division,batch,email,contact
22IT001,John Doe,4,IT,2,A1,john@example.com,9876543210
22IT002,Jane Smith,4,IT,2,A1,jane@example.com,9876543211
```

#### e) Generate Feedback Allocations
- **Auto-generate**: Creates feedback forms from timetable
- **Logic**:
  - Groups by: Division + Subject + Faculty + Batch
  - Lecture classes → All students in division
  - Lab classes → Only students in specific batch
- **Duplicate Check**: Prevents creating duplicate allocations

### 3. Student Features

#### a) Student Dashboard (`/student/dashboard`)
- **Academic Info**: Enrollment, Semester, Branch, Division, Batch
- **Quick Actions**: View Timetable, Submit Feedback
- **Pending Feedbacks**: Count of pending feedback forms
- **My Subjects**: List of enrolled subjects
- **Today's Schedule**: Classes for current day

#### b) Student Timetable (`/student/timetable`)
- **Personalized View**: Shows only classes for student's division and batch
- **Lecture Classes**: All division-wide lectures
- **Lab Classes**: Only classes for student's assigned batch
- **Color Coding**: Blue (Lecture), Green (Lab)
- **Full Details**: Subject name, faculty, room number

### 4. Feedback System Integration ✅

#### Auto-detection Logic:
1. **Timetable Analysis**: System reads all timetable entries
2. **Grouping**: Groups by Division → Subject → Faculty → Batch
3. **Allocation Rules**:
   - Lecture (no batch) → All students in division
   - Lab (with batch) → Only students in that batch
4. **Student Login**: Automatically filters feedback forms based on:
   - Student's division_id
   - Student's batch_id (for labs)

#### Database Integration:
- `form_assignments` table includes:
  - `division_id` - Target division
  - `batch_id` (nullable) - Target batch for labs
- Student sees only forms matching their division AND (no batch OR their batch)

## API Endpoints

### Admin APIs
```
GET  /admin/timetable              - View timetable page
POST /admin/timetable              - Create timetable entry
PUT  /admin/timetable/{id}         - Update entry
DELETE /admin/timetable/{id}       - Delete entry
POST /admin/timetable/generate-feedback - Auto-generate feedback allocations

GET  /admin/batches                - View batches
POST /admin/batches                - Create batch
PUT  /admin/batches/{id}           - Update batch
DELETE /admin/batches/{id}         - Delete batch

GET  /admin/students/upload        - Upload page
POST /admin/students/upload        - Process CSV
GET  /admin/students/upload/template - Download template
```

### Student APIs
```
GET /student/dashboard             - Dashboard
GET /student/timetable             - Timetable view
GET /student/feedback              - Pending feedbacks
```

### AJAX APIs
```
GET /api/divisions?semester=4&branch=IT  - Get divisions
GET /api/timetable/subjects              - Get subjects
GET /api/timetable/faculties             - Get faculties
GET /api/timetable/batches/{divisionId}  - Get batches
```

## Usage Guide

### For Administrators:

#### Step 1: Upload Students
1. Navigate to `/admin/students/upload`
2. Download CSV template
3. Fill with student data:
   - enrollment_no, name, semester, branch, division, batch
4. Upload CSV file
5. System auto-creates divisions and batches

#### Step 2: Add Timetable Entries
1. Navigate to `/admin/timetable`
2. Select Division from dropdown
3. Click "Add Entry"
4. Fill in:
   - Day (Monday-Saturday)
   - Time Slot (09:10-10:10, etc.)
   - Subject
   - Faculty
   - Room Number
   - Batch (only for labs, leave empty for lectures)
5. Save

#### Step 3: Generate Feedback Allocations
1. On timetable page, click "Generate Feedback Allocations"
2. System automatically:
   - Reads timetable
   - Groups by division, subject, faculty, batch
   - Creates feedback assignments
   - Avoids duplicates

### For Students:

#### Step 1: Login
1. Use enrollment number or email
2. System detects division and batch

#### Step 2: View Timetable
1. Dashboard shows today's schedule
2. Click "View Timetable" for full weekly view
3. See only your classes (lectures + your batch labs)

#### Step 3: Submit Feedback
1. Dashboard shows pending feedback count
2. Click "Submit Feedback"
3. See only subjects/faculty from your timetable
4. Submit feedback forms

## Sample Data

The system includes sample data for:
- **Semester 4, IT**: DBMS, SE, COA, DAA, DSA, HNY, SBS, PMP
- **Semester 6, IT**: Cryptography, Language Processors, Mobile Development, Cloud Computing, etc.
- **Faculty**: 18 faculty members with short codes
- **Divisions**: 4-IT-1, 4-IT-2, 6-IT-1, 6-IT-2
- **Batches**: A1, A2, B1, B2, C1, C2, D1, D2 per division

## Database Migrations

All migrations have been run. Schema includes:
1. `2026_02_15_000001_update_subjects_table`
2. `2026_02_15_000002_update_faculties_table`
3. `2026_02_15_000003_create_divisions_table`
4. `2026_02_15_000004_create_batches_table`
5. `2026_02_15_000005_create_timetable_table`
6. `2026_02_15_000006_add_division_batch_to_students_table`

## Models

All Eloquent models created with relationships:
- `Division.php` - Division model with batches, students, timetable
- `Batch.php` - Batch model with students, division
- `Timetable.php` - Timetable entries with subject, faculty, batch
- `Subject.php` (updated) - Added timetable relationship
- `Faculty.php` (updated) - Added faculty info and timetable
- `Student.php` (updated) - Added division and batch relationships

## Controllers

### Admin Controllers:
- `TimetableController` - Manage timetable, generate feedback
- `BatchController` - Manage batches and view students
- `DivisionController` - Manage divisions
- `StudentUploadController` - Bulk CSV upload with auto-creation

### Student Controllers:
- `DashboardController` - Student dashboard with personalized data
  - Shows timetable filtered by division/batch
  - Shows pending feedbacks
  - Today's schedule

## Views

### Admin Views:
- `admin/timetable/index.blade.php` - Timetable grid with filters
- `admin/batches/index.blade.php` - Batch management
- `admin/students/upload.blade.php` - CSV upload interface

### Student Views:
- `student/dashboard.blade.php` - Dashboard with quick info
- `student/timetable.blade.php` - Weekly timetable view

## Security Features

- **Authentication**: All routes protected with `auth` middleware
- **Role-based Access**: Admin routes separate from student routes
- **CSRF Protection**: All forms include CSRF tokens
- **SQL Injection Prevention**: Using Eloquent ORM and prepared statements
- **Data Validation**: Server-side validation on all inputs

## Advanced Features

### 1. Smart Student Import
- Updates existing students (by enrollment_no)
- Creates new students
- Auto-creates divisions if missing
- Auto-creates batches if missing
- Reports errors with line numbers

### 2. Timetable Intelligence
- Detects lecture vs lab automatically
- Color-coded display
- Hover tooltips with full information
- Click to edit functionality

### 3. Feedback Integration
- Reads timetable structure
- Matches students to subjects via division/batch
- Auto-generates feedback allocations
- Prevents duplicate allocations

### 4. Student Personalization
- Dashboard shows only relevant data
- Timetable filtered by division and batch
- Feedback forms filtered automatically
- Today's schedule on dashboard

## UI/UX Features

### Design:
- **Bootstrap 5** with Tailwind utility classes
- **Responsive** design for mobile/tablet/desktop
- **Color Coding**:
  - Blue: Lecture classes
  - Green: Lab classes
  - Orange: (Reserved for special classes)

### Interactive Elements:
- **Modals**: For adding/editing entries
- **Dropdown Filters**: Semester, Branch, Division
- **AJAX Loading**: Dynamic dropdowns for subjects, faculty, batches
- **Hover Tooltips**: Show full names on timetable cells
- **Click to Edit**: Quick editing of timetable entries

## Testing

### To Test the System:

1. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

2. **Seed Sample Data**:
   ```bash
   php artisan db:seed --class=TimetableSeeder
   ```

3. **Access Admin Panel**:
   - Login as admin
   - Navigate to `/admin/timetable`
   - Select a division and add timetable entries

4. **Upload Students**:
   - Go to `/admin/students/upload`
   - Download template
   - Upload filled CSV

5. **Test Student View**:
   - Login as a student (create user with student record)
   - View dashboard at `/student/dashboard`
   - Check timetable at `/student/timetable`

## Deployment Checklist

- [x] Database migrations created and run
- [x] Models with relationships implemented
- [x] Controllers with full CRUD logic
- [x] Views with responsive design
- [x] Routes registered in web.php
- [x] Seeder for sample data
- [x] CSV upload functionality
- [x] Feedback integration
- [x] Student personalization
- [x] API endpoints for AJAX

## Future Enhancements (Optional)

1. **Timetable Import**: Upload timetable via CSV
2. **Conflict Detection**: Alert when faculty/room double-booked
3. **Print Timetable**: PDF export of schedules
4. **Attendance Integration**: Link with attendance system
5. **Mobile App**: React Native or Flutter app
6. **Notifications**: Email/SMS for schedule changes
7. **Analytics**: Usage statistics and reports

## Support

For issues or questions:
- Check error logs at `storage/logs/laravel.log`
- Verify database connections in `.env`
- Ensure all migrations have run
- Check sample data with seeder

## Summary

✅ **Complete Timetable & Batch Management System** with:
- Full CRUD operations
- Student bulk upload
- Automatic feedback generation
- Personalized student views
- Responsive UI with color coding
- Production-ready code
- Sample data included

**All deliverables completed as per requirements!**
