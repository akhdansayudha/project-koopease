<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - KoopEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #F9FAFB;
        }

        .sidebar-scroll::-webkit-scrollbar {
            display: none;
        }

        .sidebar-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Layout Transitions */
        .sidebar-open {
            width: 16rem;
            transform: translateX(0);
        }

        .sidebar-closed {
            width: 0;
            transform: translateX(-100%);
        }

        .content-expanded {
            width: 100%;
        }

        .content-shrinked {
            width: calc(100% - 16rem);
        }
    </style>
</head>

<body class="text-gray-800 bg-gray-50 overflow-hidden" id="body">
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="flex h-screen w-full">
        @include('admin.components.sidebar')

        <div class="flex flex-col h-screen overflow-hidden flex-1 transition-all duration-300 lg:content-shrinked" id="mainContent">
            @include('admin.components.header')

            <main class="flex-1 overflow-y-auto bg-gray-50 flex flex-col justify-between" id="pageContent">
                <div class="p-6 md:p-8 max-w-full">
                    @yield('content')
                </div>

                @include('admin.components.footer')
            </main>
        </div>
    </div>

</body>

</html>