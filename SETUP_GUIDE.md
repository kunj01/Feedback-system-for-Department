# Quick Setup Guide - NAAC Feedback Analysis System

## Installation Steps

### Step 1: Install DomPDF Package

Run the following command in your project root:

```bash
composer require barryvdh/laravel-dompdf
```

### Step 2: Clear Application Cache

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Step 3: Verify Installation

Check if the package is installed:

```bash
composer show barryvdh/laravel-dompdf
```

Expected output: Version 3.x or higher

### Step 4: Test the System

1. **Login as Admin**
   - Navigate to your application
   - Login with admin credentials

2. **Access Feedback Responses**
   - Go to: `http://your-domain/admin/speakers/feedback/responses`
   - You should see two new buttons:
     - "Generate NAAC Analysis Report"
     - "Export PDF"

3. **Generate Report**
   - Click "Generate NAAC Analysis Report"
   - You should see a comprehensive analysis report
   - Use browser print or click "Export PDF"

### Step 5: Test PDF Export

1. Click "Export PDF" button
2. PDF should download automatically
3. Filename format: `Curriculum_Feedback_Analysis_YYYY_YYYY_DATE.pdf`
4. Open PDF to verify formatting

## Troubleshooting

### Issue: Composer Install Fails

**Solution:**
```bash
composer update
composer require barryvdh/laravel-dompdf --with-all-dependencies
```

### Issue: PDF Shows Blank or Errors

**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

### Issue: Routes Not Found (404)

**Solution:**
```bash
php artisan route:cache
php artisan config:cache
```

### Issue: Permission Denied

**Solution (Windows):**
```powershell
# Ensure storage and bootstrap/cache are writable
```

**Solution (Linux/Mac):**
```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: No Feedback Data Available

**Solution:**
- Ensure at least one external speaker has submitted feedback
- Check database table: `speaker_feedback`
- Verify feedback was actually saved

## Testing the Complete Workflow

### 1. Add a Test Speaker

```
Admin Panel → Speakers → Add Speaker
- Name: Dr. Test Expert
- Email: test@example.com
- Department: Computer Science
- Date: Today's date
```

### 2. Approve Speaker

```
Click "Approve" button
- Email will be sent with temporary feedback link
- Link valid for 24 hours
```

### 3. Submit Feedback (as Speaker)

```
Open the feedback link from email
Fill the 10-question form
Submit feedback
```

### 4. Generate Analysis Report

```
Admin Panel → Feedback Responses
Click "Generate NAAC Analysis Report"
View comprehensive analysis
Click "Export PDF" to download
```

## Expected Features Working

✅ Service generates statistical analysis
✅ Descriptive analysis bullets appear
✅ Question-wise statistics table displays
✅ Interactive charts render
✅ Overall summary calculates correctly
✅ Interpretations generate dynamically
✅ Recommendations appear with priorities
✅ Conclusion adapts to data
✅ PDF exports with proper formatting
✅ Print functionality works

## Configuration Options

### Customize Institute Name

Edit: `app/Http/Controllers/Admin/SpeakerController.php`

```php
public function generateAnalysisReport(FeedbackAnalysisService $analysisService)
{
    $reportData = $analysisService->generateAnalysisReport(
        academicYear: '2024-2025',
        department: 'Your Department Name',
        institute: 'Your Institute Name'
    );
    // ...
}
```

### Customize Academic Year

The system auto-detects academic year based on June-May cycle.

To override, pass explicit parameter:

```php
$reportData = $analysisService->generateAnalysisReport(
    academicYear: '2025-2026'
);
```

## Verification Checklist

- [ ] DomPDF installed successfully
- [ ] Routes accessible without 404 errors
- [ ] Analysis report page loads
- [ ] Statistical table displays correctly
- [ ] Charts render properly
- [ ] PDF downloads successfully
- [ ] PDF formatting looks good
- [ ] All sections appear in report
- [ ] Calculations are accurate
- [ ] Recommendations generate
- [ ] Conclusion text appears

## Next Steps

1. **Collect Real Feedback**
   - Add actual external speakers
   - Send feedback links
   - Collect responses

2. **Generate Reports**
   - Generate analysis after collecting feedback
   - Review report quality
   - Export PDF for records

3. **Use for Accreditation**
   - Submit to NAAC/NBA
   - Include in AQAR
   - Present to academic committees

## Support

If you encounter issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify database has feedback data
3. Test with sample data first
4. Ensure all dependencies installed

## Quick Commands Reference

```bash
# Install dependencies
composer install

# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Generate app key (if needed)
php artisan key:generate

# Start development server
php artisan serve
```

## File Locations

```
Service:     app/Services/FeedbackAnalysisService.php
Controller:  app/Http/Controllers/Admin/SpeakerController.php
Web View:    resources/views/admin/speakers/analysis-report.blade.php
PDF View:    resources/views/admin/speakers/analysis-report-pdf.blade.php
Routes:      routes/web.php
```

## System Requirements

- PHP 8.2 or higher
- Laravel 12.x
- MySQL/PostgreSQL database
- Composer
- Internet connection (for Chart.js CDN)

---

**Setup Complete!** 

You now have a fully functional NAAC/NBA-compliant Curriculum Feedback Analysis Engine.

Generate your first report by collecting feedback from external experts!
