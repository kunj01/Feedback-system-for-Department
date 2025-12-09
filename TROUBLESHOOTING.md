# Troubleshooting Guide: Navigation Issues

## Issue: "Admin dashboard can not visit any of the pages except Home"

### Verified Components ✅
- All routes are properly defined in `routes/web.php`
- All controllers exist with required methods (index, create, store, show, edit, update, destroy)
- All views exist for all 8 modules (Users, Students, Projects, Companies, Departments, Evaluations, Placements, Reports)
- Navigation links in `layouts/app.blade.php` use correct route names
- All routes are protected by `auth` middleware

### Most Likely Causes

#### 1. Not Logged In or Session Expired
**Solution:**
```powershell
# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Restart development server
php artisan serve
```

Then:
- Visit: `http://localhost:8000/login`
- Login with Admin credentials
- Try accessing pages again

#### 2. Database Not Seeded / No Admin User
**Solution:**
```powershell
# Fresh migration and seed
php artisan migrate:fresh --seed
```

**Default Admin Credentials:**
- Email: `admin@example.com`
- Password: `password`

#### 3. Role/Permission Not Assigned
**Solution:**
```powershell
# Re-seed roles and permissions
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=DefaultAdminSeeder
```

#### 4. Laravel Storage/Cache Permissions
**Solution:**
```powershell
# Clear storage cache
php artisan storage:link

# Clear compiled files
php artisan optimize:clear
```

### Diagnostic Steps

#### Step 1: Check Routes
```powershell
php artisan route:list --path=users
php artisan route:list --path=students
php artisan route:list --path=projects
```

Expected output should show routes like:
- GET users → users.index
- GET users/create → users.create
- POST users → users.store
- etc.

#### Step 2: Check Current User
Add this temporarily to `dashboard.blade.php` at the top of @section('content'):

```blade
<div class="bg-yellow-100 p-4 mb-4">
    <p><strong>Debug Info:</strong></p>
    <p>User: {{ auth()->user()->name }}</p>
    <p>Email: {{ auth()->user()->email }}</p>
    <p>Roles: {{ auth()->user()->getRoleNames()->implode(', ') }}</p>
</div>
```

This will show you if you're logged in and what role you have.

#### Step 3: Test Direct URL Access
Try accessing pages directly in browser:
- `http://localhost:8000/users`
- `http://localhost:8000/students`
- `http://localhost:8000/projects`

**Expected Behavior:**
- If you see a page → Navigation links are the issue
- If you get 404 → Routes not registered
- If you get 403 → Permission issue
- If you redirect to login → Session/auth issue

#### Step 4: Check Browser Console
1. Open browser DevTools (F12)
2. Go to Console tab
3. Click any navigation link
4. Check for JavaScript errors

#### Step 5: Check Laravel Logs
```powershell
# View recent errors
cat storage/logs/laravel.log | Select-Object -Last 50
```

### Quick Fix Script

Run this complete refresh:

```powershell
# Stop server if running (Ctrl+C)

# Clear everything
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Fresh database
php artisan migrate:fresh --seed

# Rebuild assets
npm run build

# Restart server
php artisan serve
```

Then login at `http://localhost:8000/login` with:
- Email: `admin@example.com`
- Password: `password`

### If Still Not Working

#### Check .env File
Ensure these are set correctly:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

#### Verify Middleware
Check `bootstrap/app.php` or `app/Http/Kernel.php` for middleware configuration.

#### Check Browser
- Try incognito/private mode
- Try different browser
- Clear browser cache/cookies for localhost

### Common Error Messages

| Error | Cause | Solution |
|-------|-------|----------|
| 404 Not Found | Routes not loaded | `php artisan route:clear` |
| 403 Forbidden | No permission | Re-seed roles |
| 419 Session Expired | CSRF issue | Clear cache, check session driver |
| 500 Server Error | Code error | Check `storage/logs/laravel.log` |

### Verification Checklist

After fixes, verify:
- [ ] Can login successfully
- [ ] Can see sidebar with all menu items
- [ ] Users link works (shows users list)
- [ ] Students link works
- [ ] Projects link works
- [ ] Companies link works
- [ ] Departments link works
- [ ] Evaluations link works
- [ ] Placements link works
- [ ] Reports link works

### Still Having Issues?

1. **Check exact error message** in browser network tab (F12 → Network)
2. **Check Laravel log** for detailed error
3. **Verify database** has seeded data:
   ```powershell
   php artisan tinker
   ```
   Then in tinker:
   ```php
   \App\Models\User::count()
   \Spatie\Permission\Models\Role::all()
   ```

### Contact Information

If issue persists, provide:
1. Exact error message (screenshot)
2. Browser console errors (F12)
3. Laravel log output
4. Output of `php artisan route:list`
5. Output of checking current user role
