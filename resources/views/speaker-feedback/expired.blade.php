<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Expired</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="text-6xl mb-4">⏰</div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Link Expired</h1>
            <p class="text-gray-600 mb-6">
                {{ $message ?? 'This feedback link has expired or is no longer valid.' }}
            </p>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 text-left">
                <p class="text-sm text-yellow-800">
                    If you need a new feedback link, please contact the department administrator.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
