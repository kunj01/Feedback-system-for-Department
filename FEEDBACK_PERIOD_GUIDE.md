# Feedback Period Management System - Complete Guide

## 🎯 Overview
This system allows administrators to control when students can access and submit feedback forms by configuring start dates, end dates, and grace periods for each form assignment.

---

## ✅ Features Implemented

### 1. **Database Schema**
- **Table:** `form_assignments`
- **New Columns:**
  - `start_date` (datetime, nullable) - When form becomes available
  - `end_date` (datetime, nullable) - Submission deadline
  - `grace_period_hours` (integer, default: 0) - Extra hours after deadline

### 2. **Admin Interface**
- Date/time pickers in form assignment screen
- Intuitive configuration with helpful hints
- Optional fields (leave empty for "always available")
- Visual help modal with examples

### 3. **Student Experience**
- **Status Badges:**
  - 🔵 Upcoming - Form not yet available
  - 🟢 Active - Currently accepting submissions
  - 🟡 Grace Period - Late submissions allowed
  - 🔴 Ended - Deadline passed
- **Countdown Timer:** Shows time remaining for active forms
- **Smart Buttons:**
  - "Fill Form" - Only when active
  - "Not Yet Available" (disabled) - For upcoming forms
  - "Deadline Passed" (disabled) - For ended forms

### 4. **Validation & Security**
- Access blocked before start date
- Access blocked after end date + grace period
- Submission validation on backend
- Friendly error messages

### 5. **Automatic Notifications** *(New!)*
- **Command:** `php artisan feedback:send-reminders`
- **Schedule:** Runs hourly automatically
- **Notification Triggers:**
  - 24 hours before form becomes available
  - 3 days before deadline
  - 1 day before deadline
  - 2 hours before deadline (final reminder)

---

## 📋 Usage Guide

### For Administrators

#### **Assigning Forms with Time Periods:**

1. Navigate to **Forms Management** → Select a form
2. Click on the form name to open assignment screen
3. Configure the feedback period:

   ```
   Start Date & Time:    [Jan 1, 2026 09:00 AM]
   End Date & Time:      [Jan 15, 2026 11:59 PM]
   Grace Period (Hours): [48]
   ```

4. Select students from the list
5. Click "Assign to Selected Students"

#### **Configuration Options:**

| Option | Purpose | Example |
|--------|---------|---------|
| **Start Date** | When form opens | `Dec 28, 2025 00:00` |
| **End Date** | Official deadline | `Dec 31, 2025 23:59` |
| **Grace Period** | Extra time for late submissions | `24` (hours) |

#### **Example Scenarios:**

**Scenario 1: Semester-End Feedback**
```
Start Date:    Jan 1, 2026 00:00
End Date:      Jan 15, 2026 23:59
Grace Period:  48 hours
Result:        Students can submit until Jan 17, 11:59 PM
```

**Scenario 2: Always Available Form**
```
Start Date:    (leave empty)
End Date:      (leave empty)
Grace Period:  0 hours
Result:        No time restrictions
```

**Scenario 3: Quick Survey**
```
Start Date:    Today 2:00 PM
End Date:      Tomorrow 2:00 PM
Grace Period:  2 hours
Result:        24-hour window + 2-hour grace
```

---

### For Students

#### **Form Status Indicators:**

When you view your assigned forms, you'll see:

- **Period Information:**
  - 🕐 Opens: Dec 28, 2025 09:00
  - 🕐 Closes: Dec 31, 2025 23:59 (+24h grace)

- **Countdown Timer:**
  - "2 days 5 hours remaining"
  - "10 hours remaining"
  - "Only 2 hours left!"

- **Action Buttons:**
  - ✅ **Fill Form** (green) - Form is active
  - 🔒 **Not Yet Available** (gray, disabled) - Form hasn't started
  - ❌ **Deadline Passed** (gray, disabled) - Too late to submit

---

## 🔧 Technical Details

### **Model Methods (FormAssignment.php)**

```php
$assignment->isActive()         // Check if currently accepting submissions
$assignment->isUpcoming()       // Check if not started yet
$assignment->hasEnded()         // Check if deadline passed (including grace)
$assignment->isInGracePeriod()  // Check if in grace period window
$assignment->getStatusLabel()   // Get status text: Upcoming/Active/Grace Period/Ended
$assignment->getStatusColor()   // Get badge color: blue/green/yellow/red
```

### **Validation Rules**

```php
'start_date' => 'nullable|date'
'end_date' => 'nullable|date|after:start_date'
'grace_period_hours' => 'nullable|integer|min:0|max:168'
```

### **Notification System**

**Command File:** `app/Console/Commands/SendFeedbackReminders.php`

**Scheduled Task:** Runs every hour via Laravel scheduler

**How to Run Manually:**
```bash
php artisan feedback:send-reminders
```

**Setting Up Cron Job (Production):**
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🚀 Deployment Checklist

- [x] Migration executed: `php artisan migrate`
- [x] Form assignment UI updated
- [x] Student dashboard enhanced
- [x] Validation logic implemented
- [x] Notification command created
- [x] Scheduler configured
- [ ] **Configure cron job** (for production)
- [ ] **Test email notifications** (optional)

---

## 📊 Database Migration

**File:** `database/migrations/2025_12_27_000002_add_feedback_periods_to_form_assignments.php`

**SQL Schema:**
```sql
ALTER TABLE form_assignments ADD COLUMN start_date DATETIME NULL;
ALTER TABLE form_assignments ADD COLUMN end_date DATETIME NULL;
ALTER TABLE form_assignments ADD COLUMN grace_period_hours INTEGER DEFAULT 0;
```

**Status:** ✅ **Executed Successfully**

---

## 🎓 Best Practices

1. **Grace Period Recommendations:**
   - Coursework feedback: 24-48 hours
   - Semester feedback: 48-72 hours
   - Quick surveys: 0-2 hours

2. **Notification Timing:**
   - Set deadlines at least 7 days out to allow all reminders
   - Avoid weekend deadlines if possible

3. **Always Available Forms:**
   - Use for: Registration forms, complaint forms, suggestion boxes
   - Don't use for: Time-sensitive feedback, graded assignments

4. **Testing:**
   - Create test assignment with short periods
   - Verify status badges change correctly
   - Check notification delivery

---

## 🆘 Troubleshooting

### **Issue:** Students can't see the form
**Solution:** Check if form is marked as "Upcoming". Verify start_date in database.

### **Issue:** Students still see "Fill Form" button after deadline
**Solution:** Clear cache: `php artisan cache:clear` and `php artisan config:clear`

### **Issue:** No notifications being sent
**Solution:** 
1. Check if scheduler is running: `php artisan schedule:list`
2. Run manually: `php artisan feedback:send-reminders`
3. Verify cron job is configured

### **Issue:** Grace period not working
**Solution:** Verify `grace_period_hours` column exists in database. Run migration if needed.

---

## 📞 Support

For issues or questions, contact the system administrator or refer to:
- Laravel Documentation: https://laravel.com/docs
- Project README: `README.md`
- API Documentation: `API_DOCUMENTATION.md`

---

**Version:** 1.0  
**Last Updated:** December 27, 2025  
**Developer:** GitHub Copilot for Training & Placement System
