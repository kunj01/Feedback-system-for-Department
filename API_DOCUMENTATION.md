# Training & Placement API Documentation

**Base URL:** `http://localhost:8000/api`

## Authentication

### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "admin@system.com",
  "password": "admin123"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "System Administrator",
    "email": "admin@system.com",
    "roles": ["Admin"]
  },
  "token": "1|xxxxx...",
  "token_type": "Bearer"
}
```

### Register
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "1234567890"
}
```

### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

## Default Test Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@system.com | admin123 |
| T&P Officer | tnp@system.com | tnp123 |
| HOD | hod@system.com | hod123 |
| Guide | guide@system.com | guide123 |
| Student | student@system.com | student123 |

## Users

### List Users
```http
GET /api/users
Authorization: Bearer {token}
```

**Query Parameters:**
- `search` - Search by name, email, or phone
- `role` - Filter by role name
- `department_id` - Filter by department
- `is_active` - Filter by active status (true/false)
- `sort_by` - Sort field (default: created_at)
- `sort_order` - Sort direction (asc/desc)
- `per_page` - Items per page (default: 15)

### Create User
```http
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "password123",
  "phone": "9876543210",
  "department_id": 1,
  "is_active": true,
  "role": "Student"
}
```

### Get User
```http
GET /api/users/{id}
Authorization: Bearer {token}
```

### Update User
```http
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Jane Smith",
  "phone": "9876543211"
}
```

### Delete User
```http
DELETE /api/users/{id}
Authorization: Bearer {token}
```

### Toggle User Active Status
```http
POST /api/users/{id}/toggle-active
Authorization: Bearer {token}
```

### Assign Role to User
```http
POST /api/users/{id}/assign-role
Authorization: Bearer {token}
Content-Type: application/json

{
  "role": "Guide"
}
```

## Departments

### List Departments
```http
GET /api/departments
Authorization: Bearer {token}
```

**Query Parameters:**
- `search` - Search by name or code
- `sort_by` - Sort field (default: name)
- `sort_order` - Sort direction (asc/desc)
- `per_page` - Items per page (default: 15)

### Create Department
```http
POST /api/departments
Authorization: Bearer {token}
Content-Type: application/json

{
  "code": "CSE",
  "name": "Computer Science & Engineering",
  "head_user_id": 3
}
```

### Get Department
```http
GET /api/departments/{id}
Authorization: Bearer {token}
```

### Update Department
```http
PUT /api/departments/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Computer Science & IT",
  "head_user_id": 5
}
```

### Delete Department
```http
DELETE /api/departments/{id}
Authorization: Bearer {token}
```

## Companies

### List Companies
```http
GET /api/companies
Authorization: Bearer {token}
```

**Query Parameters:**
- `search` - Search by name, contact person, or email
- `type` - Filter by type (RECRUITER/TRAINER/NA)
- `sort_by` - Sort field (default: name)
- `sort_order` - Sort direction (asc/desc)
- `per_page` - Items per page (default: 15)

### Create Company
```http
POST /api/companies
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Tech Corp",
  "type": "RECRUITER",
  "address": "123 Tech Street",
  "contact_person": "HR Manager",
  "contact_email": "hr@techcorp.com",
  "website": "https://techcorp.com",
  "notes": "Leading tech company"
}
```

## Students

### List Students
```http
GET /api/students
Authorization: Bearer {token}
```

**Query Parameters:**
- `search` - Search by roll number, registration number, email, or father's name
- `department_id` - Filter by department
- `batch` - Filter by batch year
- `training_status` - Filter by status (NOT_ASSIGNED/IN_TRAINING/COMPLETED)
- `sort_by` - Sort field (default: created_at)
- `sort_order` - Sort direction (asc/desc)
- `per_page` - Items per page (default: 15)

### Create Student
```http
POST /api/students
Authorization: Bearer {token}
Content-Type: application/json

{
  "user_id": 10,
  "roll_no": "2021CSE001",
  "registration_no": "REG2021001",
  "dob": "2003-05-15",
  "gender": "M",
  "father_name": "John Doe Sr.",
  "mother_name": "Jane Doe",
  "address": "123 Main St, City",
  "contact": "9876543210",
  "email": "student@example.com",
  "department_id": 1,
  "course": "B.Tech",
  "batch": 2021,
  "cgpa": 8.5,
  "academic_details": {
    "10th_marks": 85,
    "12th_marks": 90
  },
  "training_status": "NOT_ASSIGNED"
}
```

## Projects

### List Projects
```http
GET /api/projects
Authorization: Bearer {token}
```

**Query Parameters:**
- `search` - Search by project_id, title, or description
- `category` - Filter by category (COMPANY_PROJECT/IN_HOUSE)
- `status` - Filter by status (OPEN/IN_PROGRESS/COMPLETED/CANCELLED)
- `company_id` - Filter by company
- `guide_id` - Filter by guide
- `sort_by` - Sort field (default: created_at)
- `sort_order` - Sort direction (asc/desc)
- `per_page` - Items per page (default: 15)

### Create Project
```http
POST /api/projects
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "E-Commerce Platform",
  "description": "Build a full-stack e-commerce platform",
  "category": "COMPANY_PROJECT",
  "company_id": 1,
  "guide_id": 4,
  "co_guide_ids": [5, 6],
  "start_date": "2025-01-01",
  "end_date": "2025-06-30",
  "status": "OPEN",
  "is_group": true,
  "max_group_size": 4
}
```

**Note:** `project_id` is auto-generated in format: `TP-{YEAR}-{DEPT_CODE}-{0001}`

### Assign Students to Project
```http
POST /api/projects/{id}/assign-students
Authorization: Bearer {token}
Content-Type: application/json

{
  "student_ids": [1, 2, 3],
  "role_1": "Leader",
  "role_2": "Developer",
  "role_3": "Tester"
}
```

### Remove Student from Project
```http
DELETE /api/projects/{id}/students/{studentId}
Authorization: Bearer {token}
```

### Update Project Status
```http
PATCH /api/projects/{id}/status
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "IN_PROGRESS"
}
```

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email has already been taken."
    ]
  }
}
```

### 404 Not Found
```json
{
  "message": "Resource not found."
}
```

## Evaluations

### List Evaluations
```http
GET /api/evaluations
Authorization: Bearer {token}
```

**Query Parameters:**
- `project_id` - Filter by project
- `student_id` - Filter by student
- `evaluator_id` - Filter by evaluator
- `evaluation_type` - Filter by type (INTERNAL/EXTERNAL/REPORT)
- `from_date` - Filter from date
- `to_date` - Filter to date
- `search` - Search in feedback, remarks, grade
- `sort_by` - Sort field (default: evaluation_date)
- `sort_order` - Sort direction (asc/desc, default: desc)
- `per_page` - Items per page (default: 15)

### Create Evaluation
```http
POST /api/evaluations
Authorization: Bearer {token}
Content-Type: application/json

{
  "project_id": 1,
  "student_id": 1,
  "evaluator_id": 4,
  "evaluation_type": "INTERNAL",
  "evaluation_date": "2025-01-15",
  "marks_obtained": 68,
  "total_marks": 75,
  "feedback": "Excellent work on backend implementation",
  "remarks": "Needs improvement on frontend"
}
```

**Note:** `grade` is auto-calculated from `marks_obtained` if not provided.

### Get Evaluation
```http
GET /api/evaluations/{id}
Authorization: Bearer {token}
```

### Update Evaluation
```http
PUT /api/evaluations/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "marks_obtained": 70,
  "feedback": "Updated feedback"
}
```

### Delete Evaluation
```http
DELETE /api/evaluations/{id}
Authorization: Bearer {token}
```

### Get Project Evaluation Statistics
```http
GET /api/evaluations/project/{projectId}/stats
Authorization: Bearer {token}
```

**Response:**
```json
{
  "total_evaluations": 15,
  "average_marks": 65.5,
  "by_type": [
    {
      "evaluation_type": "INTERNAL",
      "count": 5,
      "avg_marks": 68.2
    }
  ],
  "grade_distribution": [
    {
      "grade": "A+",
      "count": 3
    }
  ]
}
```

### Get Student Evaluation Statistics
```http
GET /api/evaluations/student/{studentId}/stats
Authorization: Bearer {token}
```

## Placements

### List Placements
```http
GET /api/placements
Authorization: Bearer {token}
```

**Query Parameters:**
- `student_id` - Filter by student
- `company_id` - Filter by company
- `placement_type` - Filter by type (INTERNSHIP/FULL_TIME/PART_TIME)
- `is_confirmed` - Filter by confirmation status (true/false)
- `min_package` - Filter by minimum package
- `max_package` - Filter by maximum package
- `from_date` - Filter from date
- `to_date` - Filter to date
- `search` - Search in job title, location
- `sort_by` - Sort field (default: offer_date)
- `sort_order` - Sort direction (asc/desc, default: desc)
- `per_page` - Items per page (default: 15)

### Create Placement
```http
POST /api/placements
Authorization: Bearer {token}
Content-Type: application/json

{
  "student_id": 1,
  "company_id": 1,
  "placement_type": "FULL_TIME",
  "job_title": "Software Engineer",
  "offer_date": "2025-01-10",
  "joining_date": "2025-07-01",
  "package_lpa": 12.5,
  "location": "Bangalore",
  "job_description": "Backend development with Python",
  "is_confirmed": false
}
```

### Get Placement
```http
GET /api/placements/{id}
Authorization: Bearer {token}
```

### Update Placement
```http
PUT /api/placements/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "package_lpa": 13.0,
  "location": "Mumbai"
}
```

### Delete Placement
```http
DELETE /api/placements/{id}
Authorization: Bearer {token}
```

### Confirm Placement
```http
POST /api/placements/{id}/confirm
Authorization: Bearer {token}
```

Sets `is_confirmed=true` and `confirmed_date=now()`.

### Get Placement Statistics
```http
GET /api/placements/stats
Authorization: Bearer {token}
```

**Query Parameters:**
- `from_date` - Optional start date filter
- `to_date` - Optional end date filter

**Response:**
```json
{
  "total_placements": 150,
  "confirmed_placements": 120,
  "pending_placements": 30,
  "average_package": 8.5,
  "highest_package": 25.0,
  "lowest_package": 3.5,
  "by_type": [
    {
      "placement_type": "FULL_TIME",
      "count": 100,
      "avg_package": 9.2
    }
  ],
  "top_companies": [
    {
      "company_id": 1,
      "placement_count": 25
    }
  ]
}
```

### Get Student Placement History
```http
GET /api/placements/student/{studentId}
Authorization: Bearer {token}
```

### Get Company Placement History
```http
GET /api/placements/company/{companyId}
Authorization: Bearer {token}
```

## PowerShell Test Examples

### Login
```powershell
$body = @{email='admin@system.com';password='admin123'} | ConvertTo-Json
$response = Invoke-RestMethod -Uri http://127.0.0.1:8000/api/login -Method POST -Body $body -ContentType 'application/json'
$token = $response.token
```

### Get Users
```powershell
$headers = @{Authorization="Bearer $token"}
Invoke-RestMethod -Uri http://127.0.0.1:8000/api/users -Headers $headers
```

### Create Student
```powershell
$body = @{
    roll_no='2021CSE001'
    registration_no='REG2021001'
    email='student@test.com'
    department_id=1
    batch=2021
    training_status='NOT_ASSIGNED'
} | ConvertTo-Json

$headers = @{Authorization="Bearer $token"}
Invoke-RestMethod -Uri http://127.0.0.1:8000/api/students -Method POST -Body $body -Headers $headers -ContentType 'application/json'
```

### Create Project
```powershell
$body = @{
    title='Mobile App Development'
    description='Create a mobile application'
    category='IN_HOUSE'
    guide_id=4
    status='OPEN'
    is_group=$true
    max_group_size=3
} | ConvertTo-Json

$headers = @{Authorization="Bearer $token"}
Invoke-RestMethod -Uri http://127.0.0.1:8000/api/projects -Method POST -Body $body -Headers $headers -ContentType 'application/json'
```
