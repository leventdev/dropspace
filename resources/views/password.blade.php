<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Download | DropSpace</title>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="<?php echo asset('css/app.css') ?>" type="text/css">

    <!-- Primary Meta Tags -->
    <meta name="title" content="Download | DropSpace">
    <meta name="description" content="Download a password protected file from DropSpace file share">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $fileURL }}">
    <meta property="og:title" content="Download | DropSpace">
    <meta property="og:description" content="Download a password protected file from DropSpace file share">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ $fileURL )}}">
    <meta property="twitter:title" content="Download | DropSpace">
    <meta property="twitter:description" content="Download a password protected file from DropSpace file share">
</head>

<body class="h-full">
    <div class="bg-gray-800 min-h-full px-4 py-16 sm:px-6 sm:py-24 md:grid md:place-items-center lg:px-8">
        <div class="max-w-max mx-auto">
            <main class="sm:flex">
                <div data-aos="fade-right" class="md:shadow sm:shadow-none sm:bg-none rounded-lg md:bg-gradient-to-r md:from-indigo-500 md:to-blue-500">
                    <p class="text-4xl font-extrabold md:text-white text-indigo-600 sm:text-5xl p-0 md:p-5">Download</p>
                </div>
                <div class="sm:ml-6 self-center">
                    <div class="sm:border-l sm:border-gray-200 sm:pl-6">
                        <div class="mx-auto max-w-xl transform rounded-xl bg-gray-600 p-2 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
                            <form method="get" action="<?php echo $fileURL; ?>" class="grid grid-rows-2">
                                
                            <span class="row-start-1 text-md font-medium text-gray-200 mb-" id="availability-label">Download password protected file</span>
                                <input type="text" name="password" id="password" class="row-start-2 w-full md:w-80 rounded-md border-0 bg-gray-700 px-4 py-2.5 text-gray-200 placeholder-gray-500 focus:ring-0 sm:text-sm" placeholder="*****" role="combobox" aria-expanded="false" aria-controls="options">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>