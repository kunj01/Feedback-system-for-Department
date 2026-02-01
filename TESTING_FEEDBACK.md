# Testing Feedback Submission

## Quick Test Instructions

### 1. Start the Laravel Server
```bash
php artisan serve
```

### 2. Login to the Application
- Go to: http://localhost:8000/login
- Login with a student account

### 3. Navigate to Feedback
- From the dashboard, click on a subject to provide feedback
- Select a faculty member
- Fill out the feedback form

### 4. Verify Submission
After submitting, you can verify the feedback was saved:

```bash
php artisan tinker --execute="echo json_encode(App\Models\Feedback::with('student')->latest()->first(), JSON_PRETTY_PRINT);"
```

Or check all feedback:
```bash
php artisan tinker --execute="echo 'Total feedback: ' . App\Models\Feedback::count();"
```

### 5. View Feedback Data
To see the actual feedback data:
```bash
php artisan tinker --execute="App\Models\Feedback::all()->each(function(\$f) { echo 'Student: ' . \$f->student_id . ' | Subject: ' . \$f->subject_id . ' | Faculty: ' . \$f->faculty_id . ' | Rating: ' . \$f->overall_rating . PHP_EOL; });"
```

## Expected Behavior

### Success Case
- ✅ Form validates successfully
- ✅ Feedback saved to database
- ✅ User redirected to dashboard
- ✅ Success message displayed: "Feedback submitted successfully!"

### Duplicate Submission
- ⚠️ User tries to submit feedback again for same faculty
- ⚠️ Error message: "You have already submitted feedback for this faculty."
- ⚠️ Form is not submitted

### Validation Errors
- ❌ Missing required questions
- ❌ Invalid rating values
- ❌ Error messages displayed on form

## Database Verification

### Check Table Structure
```bash
php artisan tinker --execute="echo json_encode(DB::select('PRAGMA table_info(feedback)'), JSON_PRETTY_PRINT);"
```

### View All Feedback Records
```bash
php artisan tinker --execute="DB::table('feedback')->get()->each(function(\$row) { print_r(\$row); });"
```

### Count Feedback by Student
```bash
php artisan tinker --execute="DB::table('feedback')->selectRaw('student_id, count(*) as total')->groupBy('student_id')->get()->each(function(\$row) { echo 'Student ' . \$row->student_id . ': ' . \$row->total . ' feedback(s)' . PHP_EOL; });"
```

## Troubleshooting

### If feedback is not saving:
1. Check if you're logged in as a student
2. Verify the student has a profile: `php artisan tinker --execute="echo auth()->user()->student;"`
3. Check Laravel logs: `storage/logs/laravel.log`

### If you get "Student profile not found":
- The logged-in user doesn't have a student record
- Create one or login with a different account

### If you see validation errors:
- Ensure all 8 questions are answered
- Overall rating must be selected
- Values must be between 1-5
