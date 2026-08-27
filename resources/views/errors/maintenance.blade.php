<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance in Progress</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-lg w-full bg-white p-8 rounded-lg shadow-lg text-center">
        <div class="text-yellow-500 text-6xl mb-6">
            <i class="fas fa-tools"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Maintenance in Progress</h1>
        <p class="text-gray-600 mb-8 text-lg">
            {{ $message }}
        </p>
        <div class="border-t pt-6">
            <p class="text-sm text-gray-500">
                We apologize for the inconvenience. Please check back later.
            </p>
        </div>
    </div>
</body>
</html>
