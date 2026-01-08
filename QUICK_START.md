# Quick Start Guide - Training & Placement System

## ✅ Application is Now Running!

**URL:** http://127.0.0.1:8000

---

## 🔐 Default Admin Credentials

Check the `DefaultAdminSeeder.php` file for credentials, or use:
- **Email:** `admin@example.com` (verify in seeder)
- **Password:** Check the seeder file at `database/seeders/DefaultAdminSeeder.php`

---

## 📊 Application Overview

### **What This System Does:**
A comprehensive Training & Placement Management System for universities with:

- **Student Management** - Profiles, academics, project assignments
- **Company Management** - Placement partner information  
- **Project Tracking** - Training/internship projects with guide allocation
- **Evaluation System** - Marks tracking (15+75), auto-grade calculation
- **Placement Management** - Job applications, offers, confirmations
- **Role-Based Access** - 5 user roles with specific permissions

### **User Roles:**
1. **Admin** - Full system access
2. **T&P Officer** - Training & Placement management
3. **HOD** - Head of Department oversight
4. **Faculty Guide** - Project guidance and evaluation
5. **Student** - View profile, projects, placements

---

## 🛠️ Development Commands

### Start the Server
```powershell
cd "d:\UGSF sem 6\Main\training-placement"
php artisan serve
```

### Build Frontend Assets
```powershell
npm run build          # Production build
npm run dev            # Development with hot reload
```

### Database Commands
```powershell
php artisan migrate              # Run new migrations
php artisan migrate:fresh --seed # Reset database with sample data
php artisan db:seed              # Seed data only
```

### Clear Cache
```powershell
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📁 Key File Locations

### Configuration
- `.env` - Environment variables (database, app settings)
- `config/database.php` - Database connections
- `routes/web.php` - Web routes
- `routes/api.php` - API endpoints

### Application Code
- `app/Models/` - Eloquent models (13 models)
- `app/Http/Controllers/` - Controllers (Web & API)
- `app/Policies/` - Authorization policies
- `database/migrations/` - Database schema
- `resources/views/` - Blade templates

### Frontend
- `resources/css/app.css` - Tailwind CSS styles
- `resources/js/app.js` - JavaScript entry point
- `public/build/` - Compiled assets

---

## 🌐 Key Features

### Dashboard Views
- Admin Dashboard - System overview
- T&P Officer Dashboard - Placement statistics
- HOD Dashboard - Department metrics
- Faculty Guide Dashboard - Assigned projects
- Student Dashboard - Personal progress

### Management Modules
1. **Users** - CRUD with role assignment
2. **Students** - Comprehensive profiles, bulk import
3. **Departments** - Department management
4. **Companies** - Placement partners
5. **Projects** - Training/internship tracking
6. **Evaluations** - Marks and grading
7. **Placements** - Job offer management

### API Features
- RESTful API with 40+ endpoints
- Token-based authentication (Sanctum)
- Policy-based authorization
- JSON responses

---

## 📖 API Documentation

View full API documentation: `API_DOCUMENTATION.md`

### Authentication
```
POST /api/login
POST /api/register
POST /api/logout
GET  /api/user
```

### Example API Call
```bash
# Login
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"yourpassword"}'

# Get Students (with token)
curl -X GET http://127.0.0.1:8000/api/students \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🐛 Troubleshooting

### Port Already in Use
```powershell
php artisan serve --port=8001
```

### Database Issues
```powershell
# Reset database
php artisan migrate:fresh --seed
```

### Permission Errors
```powershell
# Windows - Give write permissions to storage
icacls "storage" /grant Everyone:F /T
icacls "bootstrap\cache" /grant Everyone:F /T
```

### Clear All Caches
```powershell
php artisan optimize:clear
```

---

## 📚 Additional Documentation

- `README.md` - Laravel framework info
- `API_DOCUMENTATION.md` - Complete API reference
- `AUTHORIZATION_SUMMARY.md` - Roles and permissions
- `DEVELOPMENT_STATUS.md` - Project progress
- `Training_and_Placement_Tracking_SRS.md` - Requirements specification
- `TROUBLESHOOTING.md` - Common issues and fixes

---

## 🔧 Technology Stack

- **Backend:** Laravel 12 (PHP 8.5.1)
- **Frontend:** Blade + Tailwind CSS v4 + Alpine.js
- **Database:** SQLite (development) / MySQL (production)
- **Authentication:** Laravel Sanctum
- **Authorization:** Spatie Laravel Permission
- **Excel Import:** Maatwebsite Excel

---

## 📝 Notes

- The server is running at **http://127.0.0.1:8000**
- Sample data has been seeded into the database
- Check seeders for default credentials
- PHP deprecation warnings are normal with PHP 8.5 (non-critical)

---

## 🚀 Next Steps

1. Login with admin credentials
2. Explore the dashboard
3. Create test users with different roles
4. Test CRUD operations for all modules
5. Try the API endpoints with Postman/Insomnia
6. Review the documentation files

---

**Happy Coding! 🎉**
