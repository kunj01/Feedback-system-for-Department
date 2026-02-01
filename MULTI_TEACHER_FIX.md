# Multi-Teacher Form Submission Fix

## Issue
Student had 3 assignments for the same form (1 completed, 2 pending) but was blocked from submitting with message "You have already submitted this form."

## Root Cause
The `view()` method in FormController was checking if the **first assignment** was completed and blocking access, instead of checking if **ALL assignments** were completed.

## Changes Made

### 1. Fixed FormController View Method (Lines 183-210)
**Before:**
```php
// Use first assignment as default
$assignment = $allAssignments->first();

// Check if form is active
if (!$assignment->isActive()) { ... }

// If already completed, show message
if ($assignment->status === 'completed') {
    return redirect()->route('forms.index')
        ->with('info', 'You have already submitted this form.');
}
```

**After:**
```php
// Use first pending assignment as default, or first assignment if all completed
$assignment = $allAssignments->where('status', 'pending')->first() ?? $allAssignments->first();

// Check if ALL assignments are completed (for multi-teacher forms)
$allCompleted = $allAssignments->every(function($a) {
    return $a->status === 'completed';
});

// If all assignments are completed, show message
if ($allCompleted) {
    return redirect()->route('forms.index')
        ->with('info', 'You have already submitted this form for all assigned teachers.');
}

// Check if form is active (using first pending assignment)
if (!$assignment->isActive()) { ... }
```

### 2. Made teacher_assignment_id Required for Multi-Teacher Forms (Line 393)
**Before:**
```php
'teacher_assignment_id' => 'nullable|exists:form_assignments,id',
```

**After:**
```php
'teacher_assignment_id' => 'required|exists:form_assignments,id',
```

## How It Works Now

### Student Workflow:
1. **View Assigned Forms Page** (`/forms`)
   - Shows all 3 assignments grouped together
   - Displays: "Total: 3, Pending: 2, Completed: 1"
   - "Fill Form" button is active because not all assignments completed

2. **Click "Fill Form"**
   - Opens curriculum feedback form
   - Teacher selection dropdown shows:
     - ✓ Teacher 1 (Submitted) - disabled
     - ● Teacher 2 (Remaining) - enabled
     - ● Teacher 3 (Remaining) - enabled

3. **Select Teacher & Submit**
   - Student selects Teacher 2
   - Fills all 10 curriculum questions
   - Submits form
   - Only that specific assignment marked as completed

4. **After Submission**
   - Redirected to forms page
   - Shows: "Total: 3, Pending: 1, Completed: 2"
   - "Fill Form" still active (one pending)
   - Can submit again for Teacher 3

5. **After All Teachers Submitted**
   - Shows: "Total: 3, Pending: 0, Completed: 3"
   - Form shows "Completed" badge
   - "Fill Form" button disappears
   - Message: "You have already submitted this form for all assigned teachers."

## Files Modified
1. `app/Http/Controllers/Web/FormController.php`
   - Line 183-210: Fixed multi-teacher assignment checking logic
   - Line 393: Made teacher_assignment_id required for curriculum feedback

## Testing Checklist
- [x] Student can access form when some assignments pending
- [x] Teacher selection dropdown works correctly
- [x] Completed teachers shown as disabled
- [x] Pending teachers shown as enabled
- [x] Submission only marks selected teacher's assignment as completed
- [x] Can submit multiple times (once per teacher)
- [x] Blocked only when ALL teachers completed
- [x] Proper error message shown when all completed

## Admin View
Admins can see all submissions by:
1. Go to Forms → Select form → "View Submissions (X)"
2. See table with all submissions including teacher/subject info
3. Click "View Details" to see individual responses

## Database Structure
- One FormAssignment record per student-teacher-subject-form combination
- Each assignment tracks its own status (pending/completed)
- FormResponse records link to specific assignment via `form_assignment_id`
