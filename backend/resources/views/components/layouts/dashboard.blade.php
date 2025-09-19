<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MFW Events' }} - MFW Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if(isset($includeChart) && $includeChart)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
    {{ $additionalHead ?? '' }}
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Include Dashboard Header -->
    @include('components.dashboard-header', ['subtitle' => $headerSubtitle ?? 'Dashboard'])
    
    <!-- Success Message -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif
    
    <!-- Main Content -->
    {{ $slot }}
    
    {{ $additionalScripts ?? '' }}
</body>
</html>
