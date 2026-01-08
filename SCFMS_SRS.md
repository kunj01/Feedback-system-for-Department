# Software Requirements Specification (SRS)
# Semester-wise Course Feedback Management System (SCFMS)

**Version:** 1.0  
**Date:** December 23, 2025  
**Project:** MERN Stack Implementation

---

## Table of Contents

1. [Introduction](#1-introduction)
   - 1.1 [Purpose](#11-purpose)
   - 1.2 [Scope](#12-scope)
   - 1.3 [Definitions, Acronyms, and Abbreviations](#13-definitions-acronyms-and-abbreviations)
   - 1.4 [Overview](#14-overview)
2. [Overall Description](#2-overall-description)
   - 2.1 [Product Perspective](#21-product-perspective)
   - 2.2 [Product Functions](#22-product-functions)
   - 2.3 [User Classes and Characteristics](#23-user-classes-and-characteristics)
   - 2.4 [Operating Environment](#24-operating-environment)
   - 2.5 [Design and Implementation Constraints](#25-design-and-implementation-constraints)
   - 2.6 [Assumptions and Dependencies](#26-assumptions-and-dependencies)
3. [Specific Requirements](#3-specific-requirements)
   - 3.1 [Functional Requirements](#31-functional-requirements)
   - 3.2 [External Interface Requirements](#32-external-interface-requirements)
   - 3.3 [Non-Functional Requirements](#33-non-functional-requirements)
4. [Use Case Overview](#4-use-case-overview)
5. [Other Requirements](#5-other-requirements)

---

## 1. Introduction

### 1.1 Purpose

The purpose of this Software Requirements Specification (SRS) document is to define the requirements for the **Semester-wise Course Feedback Management System (SCFMS)** to be developed using the **MERN stack** (MongoDB, Express.js, React.js, Node.js).

### 1.2 Scope

The SCFMS is a web-based application that enables a college to:

- **Collect course-wise feedback** from students every semester
- **Allow students** to submit structured feedback for each registered course
- **Allow faculty and HODs/Administrators** to view feedback reports, analytics, and trends
- **Help college management** in monitoring teaching quality and course effectiveness

#### Key Features Include:

- **Role-based access:** Admin, Faculty, Student, HOD/Coordinator
- **Course & semester setup** and mapping students to courses
- **Anonymous course feedback submission**
- **Feedback question templates** with Likert-scale (e.g., 1–5)
- **Consolidated statistics** and graphical reports
- **Export of reports** (PDF/Excel)

The system will be deployed on a web server and will be accessible via web browsers.

### 1.3 Definitions, Acronyms, and Abbreviations

| Term | Definition |
|------|------------|
| **SCFMS** | Semester-wise Course Feedback Management System |
| **MERN** | MongoDB, Express.js, React.js, Node.js |
| **Admin** | System administrator managing users, courses, and settings |
| **HOD** | Head of Department |
| **API** | Application Programming Interface |
| **JWT** | JSON Web Token (for authentication) |
| **SPA** | Single Page Application |
| **REST** | Representational State Transfer |
| **CRUD** | Create, Read, Update, Delete |

### 1.4 Overview

This SRS describes:
- Overall system description (Section 2)
- Specific functional and non-functional requirements (Section 3)
- Database and interface requirements (Sections 3.2)
- Use cases and system workflows (Section 4)

---

## 2. Overall Description

### 2.1 Product Perspective

The SCFMS is a new, standalone web application, but it may integrate with existing college systems (e.g., student information system) in the future.

#### High-level Architecture:

```
┌─────────────────────────────────────────────────────────┐
│                    Client Layer                         │
│              (React.js SPA - Browser)                   │
└────────────────────┬────────────────────────────────────┘
                     │ HTTP/HTTPS (REST API)
┌────────────────────┴────────────────────────────────────┐
│                  Application Layer                      │
│            (Node.js + Express.js APIs)                  │
│         - Authentication (JWT)                          │
│         - Business Logic                                │
│         - Data Validation                               │
└────────────────────┬────────────────────────────────────┘
                     │ MongoDB Driver
┌────────────────────┴────────────────────────────────────┐
│                   Database Layer                        │
│                  (MongoDB Database)                     │
└─────────────────────────────────────────────────────────┘
```

### 2.2 Product Functions (High-Level)

#### 1. User Management
- Create, update, delete users (Admin)
- Assign roles: Admin, Faculty, Student, HOD
- Import students and courses from CSV/Excel

#### 2. Course & Semester Management
- Create academic years and semesters
- Add/edit courses and sections
- Assign faculty to courses
- Map students to courses for each semester

#### 3. Feedback Form Management
- Create and manage feedback templates (question sets)
- Configure question types (rating) and weightages
- Assign templates to courses/semesters
- Define feedback submission window (start & end date)

#### 4. Feedback Submission
- Students login, view eligible courses
- Submit feedback once per course per semester
- Anonymous submission (no teacher can see who gave what)

#### 5. Reporting & Analytics
- Course-wise feedback summary (average rating per question)
- Faculty-wise and department-wise reports
- Overall semester feedback trends
- Export reports to PDF/Excel

#### 6. Administration & Security
- Role-based access control
- System configuration (college name, logo, grading scale)
- Backup and data export
- Audit logs (who did what)

### 2.3 User Classes and Characteristics

#### 1. Admin
- **Type:** Technical or admin staff
- **Access Level:** Full control
- **Capabilities:**
  - System configuration
  - User management
  - Course setup
  - Feedback configuration
  - All reports

#### 2. HOD / Department Coordinator
- **Type:** Academic users
- **Access Level:** Department-specific
- **Capabilities:**
  - View department-specific reports
  - View course and faculty feedback within their department
  - Cannot modify system settings

#### 3. Faculty
- **Type:** Teaching staff
- **Access Level:** Personal courses only
- **Capabilities:**
  - View feedback reports for their own courses
  - Cannot see individual student identities
  - Cannot modify feedback or courses

#### 4. Student
- **Type:** Primary feedback providers
- **Access Level:** Personal dashboard only
- **Capabilities:**
  - Access their own dashboard
  - View assigned courses
  - Submit feedback within time window
  - View submission status

### 2.4 Operating Environment

- **Frontend:** Runs in web browsers (Chrome, Firefox, Edge, Safari)
- **Backend:** Node.js server with Express.js
- **Database:** MongoDB (local or cloud-hosted like MongoDB Atlas)
- **Deployment OS:** Linux/Windows server
- **Network:** Internet/Intranet with HTTPS
- **Client Requirements:** Modern web browser with JavaScript enabled

### 2.5 Design and Implementation Constraints

- **Technology Stack:** Must be implemented using MERN stack
- **Authentication:** Must use JWT-based authentication and authorization
- **Branding:** Must follow college branding guidelines (logo, colors)
- **Privacy:** Data privacy laws (no exposing student identity to faculty for feedback)
- **Responsive Design:** Must work on desktop, tablet, and mobile devices
- **Browser Compatibility:** Must support modern browsers (Chrome, Firefox, Edge)
- **Security:** Must follow OWASP security best practices

### 2.6 Assumptions and Dependencies

- Student/course data will be provided correctly by college administration (e.g., via CSV/Excel)
- Users have basic knowledge of computer and browser usage
- Stable internet/intranet connectivity is available
- College has web hosting infrastructure or cloud services
- Email service is available for notifications (SMTP)
- MongoDB database hosting is available

---

## 3. Specific Requirements

### 3.1 Functional Requirements

#### 3.1.1 Authentication and Authorization

**FR-1: User Login**

- **FR-1.1:** The system shall allow users (Admin, HOD, Faculty, Student) to log in using a unique username and password (or institute email).
- **FR-1.2:** The system shall validate credentials against the user records stored in Database.
- **FR-1.3:** On successful login, the system shall issue a JWT token for subsequent API calls.
- **FR-1.4:** The system shall support "Remember Me" functionality.
- **FR-1.5:** The system shall lock accounts after 5 failed login attempts.

**FR-2: Role-based Access Control**

- **FR-2.1:** The system shall restrict access to features based on user role.
- **FR-2.2:** Students shall only access feedback submission and their own submission history.
- **FR-2.3:** Faculty shall only access feedback reports for their own assigned courses.
- **FR-2.4:** HODs shall only access feedback reports for courses within their department.
- **FR-2.5:** Admin shall have access to all modules and settings.
- **FR-2.6:** Unauthorized access attempts shall be logged and denied with appropriate error messages.

#### 3.1.2 User Management

**FR-3: Manage Users (Admin)**

- **FR-3.1:** The system shall allow Admin to create new users with following information:
  - Full Name
  - Email/Username
  - Role (Admin, HOD, Faculty, Student)
  - Department
  - Contact Number
  - Employee/Student ID
- **FR-3.2:** The system shall allow Admin to edit user details and deactivate users.
- **FR-3.3:** The system shall allow Admin to reset user passwords.
- **FR-3.4:** The system shall support bulk import of student and faculty data via CSV/Excel with validation.
- **FR-3.5:** The system shall prevent duplicate user creation based on email/username.
- **FR-3.6:** The system shall maintain user status (Active/Inactive/Suspended).

#### 3.1.3 Course & Semester Setup

**FR-4: Academic Year and Semester Management**

- **FR-4.1:** The system shall allow Admin to define academic years (e.g., 2025–26).
- **FR-4.2:** The system shall allow Admin to create semesters within each academic year:
  - Semester 1 (Odd)
  - Semester 2 (Even)
  - Summer Term (if applicable)
- **FR-4.3:** The system shall allow Admin to mark a semester as "Active", "Upcoming", or "Closed".
- **FR-4.4:** The system shall allow only one active semester at a time.
- **FR-4.5:** The system shall store semester start and end dates.

**FR-5: Course Management**

- **FR-5.1:** The system shall allow Admin to create, view, edit, and delete course records with:
  - Course Code (unique)
  - Course Name
  - Department
  - Semester (1-8)
  - Credits
  - Course Type (Theory/Practical/Elective)
  - Description
- **FR-5.2:** The system shall allow Admin to assign one or more faculty members to each course.
- **FR-5.3:** The system shall allow Admin to create multiple sections for a course (e.g., Section A, B, C).
- **FR-5.4:** The system shall allow Admin to assign students to courses for each semester via:
  - Manual assignment
  - CSV/Excel import
  - Bulk selection
- **FR-5.5:** The system shall validate course prerequisites before assignment.

#### 3.1.4 Feedback Form / Template Management

**FR-6: Feedback Template Creation**

- **FR-6.1:** The system shall allow Admin/HOD to create reusable feedback templates containing:
  - Template Name
  - Description
  - Target (Course/Faculty/Department)
  - Questions List
- **FR-6.2:** Each question shall have:
  - Question Text
  - Question Type (Rating 1–5, Text Comment, Yes/No)
  - Is Mandatory (Yes/No)
  - Weightage/Priority
  - Category (Teaching, Course Content, Infrastructure, etc.)
- **FR-6.3:** The system shall support the following rating scales:
  - 1-5 Likert Scale
  - 1-10 Scale
  - Strongly Disagree to Strongly Agree
- **FR-6.4:** The system shall allow reordering of questions in a template via drag-and-drop.
- **FR-6.5:** The system shall allow Admin to create default templates for different course types.
- **FR-6.6:** The system shall allow cloning of existing templates.

**FR-7: Feedback Assignment**

- **FR-7.1:** The system shall allow Admin/HOD to assign a feedback template to:
  - Specific courses
  - All courses in a department
  - All courses in a semester
- **FR-7.2:** The system shall allow Admin to configure a feedback period with:
  - Start Date & Time
  - End Date & Time
  - Grace Period (optional)
- **FR-7.3:** The system shall prevent feedback submission outside the defined feedback period.
- **FR-7.4:** The system shall send automatic reminders to students before the feedback deadline.
- **FR-7.5:** The system shall allow Admin to extend feedback deadline if needed.

#### 3.1.5 Feedback Submission (Student)

**FR-8: Display Eligible Courses**

- **FR-8.1:** After login, students shall see a list of all courses for which they are eligible to submit feedback in the current active semester.
- **FR-8.2:** Each course card shall display:
  - Course Code & Name
  - Faculty Name(s)
  - Credits
  - Section
  - Feedback Status (Not Submitted / Submitted / Pending)
  - Deadline
- **FR-8.3:** The system shall display a progress indicator showing how many courses have pending feedback.
- **FR-8.4:** The system shall highlight courses with approaching deadlines.

**FR-9: Feedback Form Filling**

- **FR-9.1:** When a student selects a course, the system shall display the assigned feedback template for that course.
- **FR-9.2:** The system shall allow the student to provide a rating (e.g., 1–5) for each rating question.
- **FR-9.3:** The system shall provide text areas for comment-type questions.
- **FR-9.4:** The system shall ensure mandatory questions are answered before submission.
- **FR-9.5:** The system shall show a confirmation dialog before final submission.
- **FR-9.6:** The system shall allow students to save draft responses and continue later.
- **FR-9.7:** The system shall validate all inputs before submission.

**FR-10: Feedback Submission Rules**

- **FR-10.1:** Each student shall be allowed to submit feedback only once per course per semester.
- **FR-10.2:** The system shall store the feedback in the database, without exposing student identity to faculty/HOD.
- **FR-10.3:** The system shall store mapping of student–course–submission internally for preventing duplicates, accessible only to Admin.
- **FR-10.4:** After successful submission, the course status for that student shall update to "Submitted".
- **FR-10.5:** The system shall not allow editing of feedback once submitted.
- **FR-10.6:** The system shall display a success message with submission timestamp.

#### 3.1.6 Feedback Analysis and Reporting

**FR-11: Course-wise Feedback Report**

- **FR-11.1:** The system shall compute average rating for each question at the course level.
- **FR-11.2:** The system shall provide count distribution (e.g., how many 5s, 4s, 3s, etc.) per rating question.
- **FR-11.3:** The system shall display the aggregated results in:
  - Tabular format with statistics
  - Bar charts
  - Pie charts
  - Line graphs (for trends)
- **FR-11.4:** The system shall provide a list of anonymized text comments.
- **FR-11.5:** The system shall calculate:
  - Overall course rating
  - Response rate percentage
  - Standard deviation
  - Median and Mode
- **FR-11.6:** The system shall allow comparison with previous semesters.

**FR-12: Faculty-wise and Department-wise Report**

- **FR-12.1:** The system shall allow HOD/Admin to view aggregated feedback of all courses handled by a particular faculty.
- **FR-12.2:** The system shall show overall teaching performance indicators:
  - Average rating across all courses
  - Trend over semesters
  - Strengths and areas of improvement
- **FR-12.3:** The system shall allow department-wise filter (e.g., IT, CSE, ECE).
- **FR-12.4:** The system shall generate faculty comparison reports within a department.
- **FR-12.5:** The system shall highlight top-performing and underperforming courses.

**FR-13: Semester-wise Summary**

- **FR-13.1:** The system shall display overall feedback statistics for a semester:
  - Average ratings per course
  - Top-performing courses
  - Courses needing improvement
  - Overall response rate
- **FR-13.2:** The system shall allow filtering by:
  - Department
  - Course Type
  - Faculty
  - Section
  - Rating Range
- **FR-13.3:** The system shall provide year-over-year comparison.
- **FR-13.4:** The system shall generate executive summary dashboards for management.

**FR-14: Export and Download**

- **FR-14.1:** The system shall allow Admin/HOD/Faculty to export feedback reports as:
  - PDF (formatted reports with charts)
  - Excel (raw data with pivot tables)
  - CSV (for external analysis)
- **FR-14.2:** The system shall ensure that exported reports do not contain student identities.
- **FR-14.3:** The system shall include report generation timestamp and filters applied.
- **FR-14.4:** The system shall allow batch export of multiple reports.

#### 3.1.7 Notifications

**FR-15: Feedback Notifications**

- **FR-15.1:** The system shall send email notifications to students:
  - When feedback period starts
  - Reminder 3 days before deadline
  - Reminder 1 day before deadline
  - Final reminder 2 hours before deadline
- **FR-15.2:** The system shall send dashboard notifications (in-app) for all events.
- **FR-15.3:** The system shall notify Admin/HOD:
  - When feedback period is starting
  - When feedback period is ending
  - When response rate is below threshold
- **FR-15.4:** The system shall notify faculty when their feedback reports are ready.
- **FR-15.5:** The system shall allow users to configure notification preferences.

#### 3.1.8 System Administration & Logs

**FR-16: System Settings**

- **FR-16.1:** The system shall allow Admin to configure global settings:
  - College Name
  - Logo Upload
  - Default Rating Scale
  - Email Templates
  - Notification Settings
  - Minimum Response Rate Threshold
- **FR-16.2:** The system shall allow Admin to manage:
  - Departments list
  - Roles and permissions
  - Academic calendars
  - System maintenance windows

**FR-17: Audit Logs**

- **FR-17.1:** The system shall maintain logs of key actions:
  - User login/logout
  - User creation/modification/deletion
  - Course creation/modification/deletion
  - Feedback template creation/modification
  - Feedback period changes
  - Report generation and exports
  - Settings changes
- **FR-17.2:** Logs shall include:
  - Timestamp
  - User ID and Name
  - Action Type
  - IP Address
  - Brief Description
  - Before/After values (for modifications)
- **FR-17.3:** The system shall allow Admin to view and search audit logs.
- **FR-17.4:** The system shall retain logs for at least 1 year.

**FR-18: Dashboard and Analytics**

- **FR-18.1:** The system shall provide role-specific dashboards:
  - **Admin Dashboard:**
    - Total users, courses, active feedback
    - Overall response rates
    - System health metrics
    - Recent activities
  - **HOD Dashboard:**
    - Department statistics
    - Faculty performance overview
    - Course-wise response rates
    - Pending approvals
  - **Faculty Dashboard:**
    - Personal course feedback
    - Teaching performance trends
    - Student engagement metrics
  - **Student Dashboard:**
    - Pending feedback count
    - Submitted feedback list
    - Upcoming deadlines
- **FR-18.2:** All dashboards shall include visual charts and graphs.
- **FR-18.3:** Dashboards shall be customizable with widgets.

---

### 3.2 External Interface Requirements

#### 3.2.1 User Interface (UI)

The UI shall be built using **React.js** with the following requirements:

**General UI Requirements:**
- Modern, clean, and intuitive design
- Consistent navigation across all pages
- Breadcrumb navigation for deep pages
- Loading indicators for async operations
- Error messages displayed clearly
- Success confirmations for all actions

**Responsive Design:**
- **Desktop (Primary):** Full feature access, optimized for 1920x1080 and 1366x768
- **Tablet:** Optimized layout for iPad and Android tablets (768px-1024px)
- **Mobile:** Essential features accessible on smartphones (320px-767px)

**Key Screens:**

1. **Login Page**
   - Username/Email and Password fields
   - Remember Me checkbox
   - Forgot Password link
   - College logo and branding
   - Error messages for invalid credentials

2. **Student Dashboard**
   - Course cards with feedback status
   - Progress indicator
   - Quick stats (pending, submitted, total)
   - Deadline countdown
   - Search and filter options

3. **Feedback Form Page**
   - Course details header
   - Question navigation (if multi-page)
   - Progress indicator
   - Save draft button
   - Submit button with confirmation
   - Cancel/Back option

4. **Admin Dashboard**
   - Statistics cards (users, courses, feedback)
   - Response rate charts
   - Recent activities timeline
   - Quick action buttons
   - System notifications

5. **User Management Page**
   - User list with search and filters
   - Add new user button
   - Bulk import option
   - Edit/Delete/View actions
   - Status indicators
   - Pagination

6. **Course & Semester Management Page**
   - Academic year selector
   - Semester tabs
   - Course list with faculty assignment
   - Add/Edit/Delete actions
   - Student mapping interface
   - Import/Export options

7. **Template Management Page**
   - Template list
   - Create new template form
   - Question builder with drag-and-drop
   - Preview mode
   - Clone/Edit/Delete actions

8. **Reports & Analytics Page**
   - Filter panel (department, course, faculty, date range)
   - Report type selector
   - Data visualization (charts, graphs)
   - Export options
   - Print preview
   - Comparison view

**UI Components:**
- Reusable component library (buttons, forms, modals)
- Consistent color scheme matching college branding
- Icons from Material-UI or Font Awesome
- Data tables with sorting, filtering, pagination
- Form validation with real-time feedback
- Confirmation dialogs for critical actions
- Toast notifications for system messages

#### 3.2.2 Hardware Interfaces

**Server Requirements:**
- **CPU:** Minimum quad-core processor (2.5 GHz+)
- **RAM:** Minimum 8 GB (16 GB recommended)
- **Storage:** Minimum 100 GB SSD for application and database
- **Network:** Gigabit Ethernet connection

**Client Requirements:**
- **Desktop/Laptop:** Any modern PC with 4 GB RAM
- **Mobile/Tablet:** iOS 12+ or Android 8+
- **Network:** Stable internet connection (minimum 2 Mbps)

#### 3.2.3 Software Interfaces

**Backend Dependencies:**
- **Node.js:** Version 18.x or higher
- **Express.js:** Version 4.x for REST API
- **MongoDB:** Version 6.x or higher
- **Mongoose:** ODM for MongoDB
- **JWT:** jsonwebtoken library for authentication
- **Bcrypt:** Password hashing
- **Nodemailer:** Email service integration
- **Multer:** File upload handling
- **Express-validator:** Input validation

**Frontend Dependencies:**
- **React.js:** Version 18.x
- **React Router:** Version 6.x for routing
- **Axios:** HTTP client for API calls
- **Redux/Context API:** State management
- **Chart.js/Recharts:** Data visualization
- **Material-UI/Ant Design:** UI component library
- **React-Hook-Form:** Form handling
- **Date-fns/Moment.js:** Date manipulation

**External Services:**
- **SMTP Server:** For email notifications (Gmail, SendGrid, or custom)
- **File Storage:** Local storage or cloud (AWS S3, optional)
- **PDF Generation:** PDFKit or Puppeteer
- **Excel Processing:** ExcelJS or XLSX

**Future Integration (Optional):**
- College ERP system via REST API
- LDAP/Active Directory for SSO
- SMS gateway for mobile notifications

#### 3.2.4 Communication Interfaces

**Protocol:** HTTP/HTTPS

**API Architecture:** RESTful API

**Data Format:** JSON

**Base URL Structure:**
```
https://college-domain.com/api/v1/
```

**API Endpoints Structure:**

**Authentication:**
- `POST /api/v1/auth/login` - User login
- `POST /api/v1/auth/logout` - User logout
- `POST /api/v1/auth/refresh` - Refresh token
- `POST /api/v1/auth/forgot-password` - Password reset request
- `POST /api/v1/auth/reset-password` - Reset password

**Users:**
- `GET /api/v1/users` - Get all users (Admin)
- `GET /api/v1/users/:id` - Get user by ID
- `POST /api/v1/users` - Create new user
- `PUT /api/v1/users/:id` - Update user
- `DELETE /api/v1/users/:id` - Delete user
- `POST /api/v1/users/bulk-import` - Import users from CSV

**Courses:**
- `GET /api/v1/courses` - Get all courses
- `GET /api/v1/courses/:id` - Get course details
- `POST /api/v1/courses` - Create new course
- `PUT /api/v1/courses/:id` - Update course
- `DELETE /api/v1/courses/:id` - Delete course
- `POST /api/v1/courses/:id/assign-faculty` - Assign faculty
- `POST /api/v1/courses/:id/assign-students` - Assign students

**Semesters:**
- `GET /api/v1/semesters` - Get all semesters
- `GET /api/v1/semesters/active` - Get active semester
- `POST /api/v1/semesters` - Create semester
- `PUT /api/v1/semesters/:id` - Update semester
- `PUT /api/v1/semesters/:id/activate` - Activate semester

**Feedback Templates:**
- `GET /api/v1/templates` - Get all templates
- `GET /api/v1/templates/:id` - Get template details
- `POST /api/v1/templates` - Create template
- `PUT /api/v1/templates/:id` - Update template
- `DELETE /api/v1/templates/:id` - Delete template
- `POST /api/v1/templates/:id/assign` - Assign to courses

**Feedback Submission:**
- `GET /api/v1/feedback/my-courses` - Get student's eligible courses
- `GET /api/v1/feedback/form/:courseId` - Get feedback form
- `POST /api/v1/feedback/submit` - Submit feedback
- `POST /api/v1/feedback/save-draft` - Save draft

**Reports:**
- `GET /api/v1/reports/course/:courseId` - Course feedback report
- `GET /api/v1/reports/faculty/:facultyId` - Faculty report
- `GET /api/v1/reports/department/:deptId` - Department report
- `GET /api/v1/reports/semester/:semId` - Semester summary
- `POST /api/v1/reports/export` - Export report (PDF/Excel)

**Security Headers:**
```
Authorization: Bearer <JWT_TOKEN>
Content-Type: application/json
```

**Response Format:**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... },
  "timestamp": "2025-12-23T10:30:00Z"
}
```

**Error Response Format:**
```json
{
  "success": false,
  "error": {
    "code": "ERROR_CODE",
    "message": "Human readable error message",
    "details": [ ... ]
  },
  "timestamp": "2025-12-23T10:30:00Z"
}
```

---

### 3.3 Non-Functional Requirements

#### 3.3.1 Performance Requirements

- **NFR-1:** The system shall support at least **500 concurrent users** without noticeable slowdown during peak feedback periods.
- **NFR-2:** Average page load time should be less than **3 seconds** on a standard campus network (10 Mbps).
- **NFR-3:** Feedback submission transaction should be completed within **2 seconds** in normal load conditions.
- **NFR-4:** API response time shall not exceed **500ms** for 95% of requests.
- **NFR-5:** Database queries shall be optimized with proper indexing; no query should take more than **2 seconds**.
- **NFR-6:** The system shall handle peak loads (all students submitting feedback simultaneously) with graceful degradation.
- **NFR-7:** Report generation (with charts) shall complete within **10 seconds** for typical datasets.
- **NFR-8:** File uploads (CSV/Excel) shall support files up to **10 MB** in size.

#### 3.3.2 Security Requirements

- **NFR-9:** The system shall use **JWT-based authentication** for all protected API endpoints.
- **NFR-10:** Passwords shall be stored in the database in **hashed form** using bcrypt with minimum 10 salt rounds.
- **NFR-11:** Feedback data must be protected against unauthorized access; **role-based access control** must be enforced.
- **NFR-12:** Student identities must **not be visible** to faculty or HODs in feedback reports (anonymization is mandatory).
- **NFR-13:** System should provide **server-side validation** for all forms to avoid malicious input (XSS, NoSQL injection).
- **NFR-14:** All API endpoints shall require valid authentication tokens except login and public pages.
- **NFR-15:** Session tokens shall expire after **24 hours** of inactivity.
- **NFR-16:** The system shall use **HTTPS** for all communications in production.
- **NFR-17:** Sensitive data (passwords, tokens) shall never be logged in plain text.
- **NFR-18:** The system shall implement **CORS** policy to prevent unauthorized cross-origin requests.
- **NFR-19:** The system shall implement **rate limiting** to prevent brute force attacks (max 5 login attempts per minute).
- **NFR-20:** All file uploads shall be scanned and validated for malicious content.

#### 3.3.3 Reliability and Availability

- **NFR-21:** The system should be available **99.5% of the time** during active semester periods (allowing max 3.6 hours downtime per month).
- **NFR-22:** System should handle unexpected failures gracefully and show meaningful error messages to users.
- **NFR-23:** Regular **automated backups** of the database should be taken daily at midnight.
- **NFR-24:** The system shall provide backup retention for at least **6 months**.
- **NFR-25:** The system shall have automated health monitoring and alerting mechanisms.
- **NFR-26:** Critical errors shall be logged and administrators shall be notified immediately.
- **NFR-27:** The system shall implement retry mechanisms for failed email notifications.

#### 3.3.4 Usability

- **NFR-28:** The UI shall be intuitive and easy to use even for **non-technical users** (students and faculty).
- **NFR-29:** The system shall provide **tooltips/help text** where necessary.
- **NFR-30:** The application should follow **consistent layout, colors, and typography** throughout.
- **NFR-31:** Form fields shall have clear labels and validation messages.
- **NFR-32:** The system shall support **keyboard navigation** for accessibility.
- **NFR-33:** Error messages shall be clear, actionable, and non-technical.
- **NFR-34:** The system shall provide **contextual help** documentation.
- **NFR-35:** First-time users shall be able to complete feedback submission within **5 minutes** without training.
- **NFR-36:** The system shall be compliant with **WCAG 2.1 Level AA** accessibility standards (where feasible).

#### 3.3.5 Maintainability

- **NFR-37:** Code should be **modular** and follow standard folder structure for MERN projects.
- **NFR-38:** Proper **comments and documentation** shall be included in the code.
- **NFR-39:** The system should be designed so that new features can be added with **minimal changes** to existing code.
- **NFR-40:** All API endpoints shall have **Swagger/OpenAPI documentation**.
- **NFR-41:** The codebase shall follow **ESLint** and **Prettier** standards for code consistency.
- **NFR-42:** Database schema shall be properly documented with entity-relationship diagrams.
- **NFR-43:** The system shall use **environment variables** for configuration (no hardcoded values).
- **NFR-44:** Code shall follow **separation of concerns** principle (routes, controllers, services, models).
- **NFR-45:** Unit tests shall cover at least **70% of critical business logic**.

#### 3.3.6 Portability

- **NFR-46:** The application should be deployable on different platforms such as **Linux or Windows** servers.
- **NFR-47:** The frontend should run on major browsers:
  - Google Chrome (latest 2 versions)
  - Mozilla Firefox (latest 2 versions)
  - Microsoft Edge (latest 2 versions)
  - Safari (latest 2 versions on macOS/iOS)
- **NFR-48:** The system shall use **Docker containers** for easy deployment and portability.
- **NFR-49:** The database should be portable between MongoDB deployments (local, Atlas, self-hosted).
- **NFR-50:** The system shall maintain compatibility with Node.js LTS versions.

#### 3.3.7 Scalability

- **NFR-51:** The system architecture shall support **horizontal scaling** by adding more server instances.
- **NFR-52:** Database design shall support efficient querying even with **10,000+ students** and **1000+ courses**.
- **NFR-53:** The system shall use **pagination** for all list views to handle large datasets.
- **NFR-54:** The system shall implement **caching** mechanisms (Redis/Memcached) for frequently accessed data.

#### 3.3.8 Backup and Recovery

- **NFR-55:** The system shall support **manual backup** triggers by administrators.
- **NFR-56:** Backup files shall be stored in **secure, separate location** from the main database.
- **NFR-57:** The system shall provide a **restore mechanism** to recover from backups.
- **NFR-58:** Recovery Time Objective (RTO) shall be **less than 4 hours**.
- **NFR-59:** Recovery Point Objective (RPO) shall be **less than 24 hours** (daily backups).

---

## 4. Use Case Overview

### 4.1 Use Case: Student Submits Feedback

**Use Case ID:** UC-01  
**Use Case Name:** Submit Course Feedback  
**Actor:** Student  
**Precondition:** 
- Student is logged in
- Feedback period is active
- Student is enrolled in the course
- Student has not previously submitted feedback for this course

**Main Flow:**

1. Student logs in to the system
2. System displays student dashboard with list of registered courses
3. System shows feedback status for each course (Submitted/Pending)
4. Student selects a course with status "Not Submitted"
5. System displays the feedback form for that course with:
   - Course details (name, code, faculty)
   - List of questions from assigned template
   - Rating scales/text fields
6. Student provides ratings for each question
7. Student optionally adds text comments
8. Student clicks "Submit Feedback" button
9. System validates that all mandatory questions are answered
10. System shows confirmation dialog "Are you sure you want to submit?"
11. Student confirms submission
12. System saves feedback to database anonymously
13. System updates course status to "Submitted" for that student
14. System displays success message with submission timestamp
15. System redirects to dashboard showing updated status

**Alternative Flows:**

**AF-1: Save Draft**
- At step 6-7, student clicks "Save Draft"
- System saves partial responses
- Student can return later to complete

**AF-2: Validation Failure**
- At step 9, if mandatory questions are not answered
- System highlights unanswered questions in red
- System displays error message "Please answer all mandatory questions"
- Student completes missing fields and resubmits

**AF-3: Outside Feedback Period**
- At step 4, if feedback period has ended
- System displays message "Feedback period has ended for this course"
- Submit button is disabled

**Postcondition:** 
- Feedback is stored anonymously in database
- Student cannot edit or resubmit feedback
- Course status is marked as "Submitted"

---

### 4.2 Use Case: Admin Creates Feedback Template

**Use Case ID:** UC-02  
**Use Case Name:** Create Feedback Template  
**Actor:** Admin  
**Precondition:** Admin is logged in with appropriate permissions

**Main Flow:**

1. Admin logs in and navigates to "Template Management" section
2. Admin clicks "Create New Template" button
3. System displays template creation form
4. Admin enters template details:
   - Template Name (e.g., "Theory Course Feedback")
   - Description
   - Target Type (Course/Faculty/Department)
5. Admin clicks "Add Question" button
6. System displays question configuration dialog
7. Admin configures question:
   - Question text
   - Question type (Rating/Comment/Yes-No)
   - Rating scale (if applicable)
   - Mandatory flag (Yes/No)
   - Weightage/Category
8. Admin clicks "Add Question" to add to template
9. Admin repeats steps 5-8 to add more questions
10. Admin reorders questions using drag-and-drop if needed
11. Admin clicks "Preview Template"
12. System displays how the template will appear to students
13. Admin clicks "Save Template"
14. System validates template (must have at least 5 questions)
15. System saves template to database
16. System displays success message
17. System shows updated template list

**Alternative Flows:**

**AF-1: Clone Existing Template**
- At step 2, Admin selects existing template and clicks "Clone"
- System creates a copy with all questions
- Admin modifies as needed

**AF-2: Validation Error**
- At step 14, if validation fails (e.g., no questions added)
- System displays error message
- Admin adds required questions

**Postcondition:** New template is created and available for assignment

---

### 4.3 Use Case: HOD Views Course Feedback Report

**Use Case ID:** UC-03  
**Use Case Name:** View Course Feedback Report  
**Actor:** HOD  
**Precondition:** 
- HOD is logged in
- Feedback has been collected for courses
- HOD has permission to view department reports

**Main Flow:**

1. HOD logs in and navigates to "Reports" section
2. System displays report filter panel
3. HOD selects filters:
   - Department (pre-selected to HOD's department)
   - Semester/Academic Year
   - Course (specific or all)
4. HOD clicks "Generate Report"
5. System retrieves aggregated feedback data
6. System displays report with:
   - Course information (name, code, faculty, section)
   - Number of responses / Total students (response rate %)
   - Question-wise statistics:
     - Average rating
     - Rating distribution (chart)
     - Standard deviation
   - Overall course rating
   - Anonymous text comments list
   - Comparison with previous semester (if available)
7. System displays visual charts (bar graphs, pie charts)
8. HOD reviews the report
9. HOD clicks "Export to PDF" or "Export to Excel"
10. System generates formatted report file
11. System downloads the report to HOD's computer
12. System logs the report generation in audit trail

**Alternative Flows:**

**AF-1: No Data Available**
- At step 5, if no feedback data exists
- System displays message "No feedback data available for selected filters"
- HOD adjusts filters and retries

**AF-2: Compare Multiple Courses**
- At step 3, HOD selects multiple courses
- System displays side-by-side comparison report

**AF-3: View Faculty-wise Report**
- At step 3, HOD selects specific faculty instead of course
- System shows all courses taught by that faculty

**Postcondition:** 
- Report is viewed/downloaded
- No student identities are exposed
- Audit log is updated

---

### 4.4 Use Case: Faculty Views Own Feedback

**Use Case ID:** UC-04  
**Use Case Name:** View Personal Course Feedback  
**Actor:** Faculty  
**Precondition:** 
- Faculty is logged in
- Feedback period has ended
- Sufficient number of students have submitted feedback (minimum threshold met)

**Main Flow:**

1. Faculty logs in to the system
2. System displays faculty dashboard
3. Dashboard shows list of courses taught in current semester
4. Each course card displays:
   - Course name and code
   - Number of students
   - Response rate
   - Overall average rating
   - Status (Feedback Available/Pending)
5. Faculty clicks on a course card
6. System displays detailed feedback report for that course
7. Report includes:
   - Overall rating (average)
   - Question-wise breakdown with charts
   - Strengths (highest rated areas)
   - Areas for improvement (lowest rated areas)
   - Trend comparison with previous semesters
   - Anonymous student comments
8. Faculty reviews the feedback
9. Faculty can download report as PDF
10. Faculty may add self-reflection notes (optional feature)

**Alternative Flows:**

**AF-1: Insufficient Responses**
- At step 6, if response rate < 50%
- System displays message "Feedback report will be available when minimum response threshold is met"
- Faculty can see response count but not detailed feedback

**AF-2: View Trend Analysis**
- At step 6, Faculty clicks "View Trend"
- System shows performance trend over last 3 semesters

**Postcondition:** 
- Faculty has viewed their performance feedback
- No student identities are exposed

---

### 4.5 Use Case: Admin Assigns Feedback Template to Courses

**Use Case ID:** UC-05  
**Use Case Name:** Assign Feedback Template  
**Actor:** Admin  
**Precondition:** 
- Admin is logged in
- At least one feedback template exists
- Courses are created and active

**Main Flow:**

1. Admin navigates to "Feedback Assignment" section
2. System displays list of available templates
3. Admin selects a template
4. System shows template details and preview
5. Admin clicks "Assign to Courses"
6. System displays course selection interface with filters:
   - Department
   - Semester
   - Course Type (Theory/Practical)
7. Admin selects target courses (individual or bulk)
8. Admin configures feedback period:
   - Start Date & Time
   - End Date & Time
   - Grace Period (optional)
9. Admin enables notification settings:
   - Send reminder emails (Yes/No)
   - Reminder schedule
10. Admin clicks "Assign Template"
11. System validates:
    - Dates are in future
    - No overlapping feedback periods
    - Selected courses exist
12. System creates feedback assignments
13. System schedules notification emails
14. System displays confirmation message
15. System sends initial notification to affected students

**Alternative Flows:**

**AF-1: Extend Deadline**
- If feedback period is already active
- Admin can select existing assignment and click "Extend Deadline"
- System updates end date and notifies students

**AF-2: Assign to All Department Courses**
- At step 7, Admin selects "All courses in [Department]"
- System applies template to all matching courses

**Postcondition:** 
- Template is assigned to selected courses
- Students can now access feedback forms
- Notifications are scheduled

---

### 4.6 Use Case: Student Views Feedback Submission Status

**Use Case ID:** UC-06  
**Use Case Name:** View Feedback Status  
**Actor:** Student  
**Precondition:** Student is logged in

**Main Flow:**

1. Student logs in
2. System displays student dashboard
3. Dashboard shows:
   - Total courses enrolled
   - Feedback submitted count
   - Pending feedback count
   - Progress bar
4. System lists all courses with cards showing:
   - Course name, code, faculty
   - Feedback status badge (Submitted/Pending/Expired)
   - Deadline (for pending)
   - Submission timestamp (for submitted)
5. Pending courses are highlighted
6. Expired feedbacks are grayed out
7. Student can click on any pending course to submit
8. Student can filter courses by status

**Postcondition:** Student is aware of pending feedback

---

## 5. Other Requirements

### 5.1 Future Enhancements

The system should be designed to be easily extensible for the following future features:

1. **Teacher Self-Evaluation**
   - Faculty can submit self-assessment forms
   - Compare self-evaluation with student feedback
   - Identify perception gaps

2. **Peer Evaluation**
   - Faculty can evaluate other faculty members
   - HOD can provide performance reviews
   - 360-degree feedback mechanism

3. **Student Exit Survey**
   - Final-year students can provide overall program feedback
   - Graduate outcome survey
   - Alumni tracking

4. **Advanced Analytics**
   - Predictive analytics for teaching quality
   - Machine learning for sentiment analysis of comments
   - Correlation analysis (feedback vs. student performance)

5. **Integration with College ERP**
   - Automatic student-course mapping from ERP
   - Single Sign-On (SSO) integration
   - Grade and attendance correlation

6. **Mobile Application**
   - Native iOS and Android apps
   - Push notifications
   - Offline feedback submission

7. **Multi-language Support**
   - Interface in regional languages
   - Template translation

8. **Gamification**
   - Reward students for timely feedback submission
   - Leaderboards for response rates
   - Badges and achievements

9. **Advanced Reporting**
   - Custom report builder
   - Scheduled report generation
   - Automated email distribution of reports

10. **Integration with Learning Management Systems**
    - Link feedback with course materials
    - Correlate with student engagement metrics

### 5.2 Legal and Compliance Requirements

- **Data Privacy:** Comply with local data protection laws (GDPR, if applicable)
- **Student Privacy:** Ensure complete anonymity in feedback submission
- **Data Retention:** Define clear policies for how long feedback data is stored
- **Right to Access:** Students should be able to request their submitted feedback data
- **Consent:** Students should consent to feedback data being used for quality improvement

### 5.3 Training Requirements

- **Student Orientation:** Brief session on how to use the feedback system
- **Faculty Training:** Understanding feedback reports and using insights
- **Admin Training:** Comprehensive training on system configuration and management
- **Documentation:** User manuals for each role

### 5.4 Support and Maintenance

- **Help Desk:** Designated support contact for technical issues
- **FAQ Section:** Common questions and answers in the application
- **Ticketing System:** For bug reports and feature requests
- **Regular Updates:** Quarterly system updates and patches
- **Monitoring:** 24/7 system monitoring during feedback periods

---

## 6. Database Schema Overview

### 6.1 Collections/Entities

The MongoDB database will contain the following collections:

1. **Users**
   - _id, name, email, password, role, department, status, createdAt, updatedAt

2. **Departments**
   - _id, name, code, hod (ref: Users), createdAt

3. **AcademicYears**
   - _id, year, startDate, endDate, status

4. **Semesters**
   - _id, academicYear (ref), semesterNumber, name, startDate, endDate, status

5. **Courses**
   - _id, code, name, department (ref), semester, credits, type, description, faculty (ref: Users array)

6. **CourseEnrollments**
   - _id, student (ref: Users), course (ref: Courses), semester (ref), section

7. **FeedbackTemplates**
   - _id, name, description, targetType, questions (array), createdBy (ref: Users), isActive

8. **FeedbackAssignments**
   - _id, template (ref), courses (array), startDate, endDate, graceperiod, reminderSettings

9. **FeedbackSubmissions**
   - _id, student (ref: Users - encrypted/hashed), course (ref), template (ref), responses (array), submittedAt, isAnonymized

10. **AuditLogs**
    - _id, user (ref: Users), action, entity, entityId, changes, ipAddress, timestamp

11. **Notifications**
    - _id, recipient (ref: Users), type, message, isRead, createdAt

12. **SystemSettings**
    - _id, key, value, updatedBy, updatedAt

### 6.2 Indexing Strategy

- Index on email (Users) - unique
- Index on course code (Courses) - unique
- Index on student + course + semester (FeedbackSubmissions) - unique compound
- Index on createdAt/timestamp (AuditLogs, Notifications) for efficient time-based queries
- Index on status fields for filtering

---

## 7. Appendices

### Appendix A: Glossary

| Term | Definition |
|------|------------|
| Anonymous Feedback | Feedback where the identity of the provider is not revealed to the recipient |
| Response Rate | Percentage of students who submitted feedback out of total enrolled |
| Likert Scale | A rating scale (typically 1-5) used to measure agreement or satisfaction |
| JWT | JSON Web Token - a secure way to transmit authentication data |
| CRUD | Create, Read, Update, Delete operations |
| API | Application Programming Interface |
| SPA | Single Page Application |

### Appendix B: References

- MERN Stack Documentation (MongoDB, Express, React, Node.js)
- JWT Authentication Best Practices
- OWASP Security Guidelines
- WCAG 2.1 Accessibility Standards
- MongoDB Schema Design Best Practices

### Appendix C: Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2025-12-23 | Development Team | Initial SRS document creation |

---

**End of Document**
