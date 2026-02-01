<!DOCTYPE html>
<html>
<head>
    <title>Feedback Submission Test</title>
    <script>
        // Simulate feedback submission to test the endpoint
        function testSubmission() {
            const output = document.getElementById('output');
            output.innerHTML = '<p>Testing feedback submission...</p>';
            
            // Get CSRF token from meta tag or create test data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('subject_id', '1');
            formData.append('faculty_id', '1');
            formData.append('q1', '5');
            formData.append('q2', '4');
            formData.append('q3', '5');
            formData.append('q4', '4');
            formData.append('q5', '5');
            formData.append('q6', '4');
            formData.append('q7', '5');
            formData.append('q8', '4');
            formData.append('overall_rating', '5');
            formData.append('comments', 'Test submission from debug page');
            
            console.log('Submitting to:', '/feedback/submit');
            console.log('Form data:', Object.fromEntries(formData));
            
            fetch('/feedback/submit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', [...response.headers.entries()]);
                
                if (response.redirected) {
                    output.innerHTML += '<p style="color: green;">✓ Form submitted successfully!</p>';
                    output.innerHTML += '<p>Redirected to: ' + response.url + '</p>';
                    return { success: true, redirected: true, url: response.url };
                }
                
                return response.json().catch(() => response.text());
            })
            .then(data => {
                console.log('Response data:', data);
                output.innerHTML += '<p>Response:</p>';
                output.innerHTML += '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                console.error('Error:', error);
                output.innerHTML += '<p style="color: red;">✗ Error: ' + error.message + '</p>';
            });
        }
    </script>
</head>
<body style="font-family: Arial; padding: 20px;">
    <h1>Feedback Submission Debug Page</h1>
    
    <div style="background: #f0f0f0; padding: 15px; margin: 20px 0;">
        <h2>Backend Test Results:</h2>
        <p>Run this to test the submission endpoint:</p>
        <button onclick="testSubmission()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
            Test Feedback Submission
        </button>
    </div>
    
    <div id="output" style="background: white; border: 1px solid #ddd; padding: 15px; margin: 20px 0;"></div>
    
    <div style="background: #fff3cd; padding: 15px; margin: 20px 0;">
        <h3>Instructions:</h3>
        <ol>
            <li>Click the "Test Feedback Submission" button above</li>
            <li>Check the browser console (F12) for detailed logs</li>
            <li>Check the Laravel logs at: <code>storage/logs/laravel.log</code></li>
            <li>If successful, check admin panel: <code>/admin/student-feedback</code></li>
        </ol>
    </div>
    
    <div style="background: #e7f3ff; padding: 15px; margin: 20px 0;">
        <h3>Quick Links:</h3>
        <ul>
            <li><a href="/admin/student-feedback">Admin Panel - View Feedback</a></li>
            <li><a href="/feedback/my-feedback">View My Submitted Feedback (JSON)</a></li>
            <li><a href="/dashboard">Dashboard</a></li>
        </ul>
    </div>
</body>
</html>
