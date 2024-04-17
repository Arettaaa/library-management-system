<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wikrama Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./assets/css/tailwind.output.css" />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="./assets/js/init-alpine.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" defer></script>
    <script src="./assets/js/charts-lines.js" defer></script>
    <script src="./assets/js/charts-pie.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-jzQ+4fw24bXbU9W7frSX8o/fjUz6gsmcwVjG9TkpPb3I4IcFOo3fD0wG6Oe1f6t2OLv2bE4cDcxG1O47u6fF0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<main class="h-full pb-16 overflow-y-auto">
    <div class="container flex flex-col items-center px-6 mx-auto">
        <svg class="w-12 h-12 mt-8 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
        </svg>
        <h1 class="text-6xl font-semibold text-gray-700 dark:text-gray-200">
            404
        </h1>
        <p class="text-gray-700 dark:text-gray-300">
            Page not found. Check the address or
            <a class="text-purple-600 hover:underline dark:text-purple-300" href="/">
                go back
            </a>
            .
        </p>
    </div>
</main>
