<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$config = json_decode(file_get_contents(__DIR__ . '/../../config.json'), true);
$global = $config['global'];
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle ?? 'Teacher Portal'; ?> | <?php echo $global['nameschool']; ?></title>
    
    <!-- Google Font: Mali -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mali:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS v3 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'mali': ['Mali', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        accent: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'slide-in-left': 'slideInLeft 0.3s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'spin-slow': 'spin 3s linear infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideInLeft: {
                            '0%': { opacity: '0', transform: 'translateX(-20px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        }
                    },
                    backdropBlur: {
                        xs: '2px',
                    }
                }
            }
        }
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <!-- SheetJS for Excel -->
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    
    <style>
        * {
            font-family: 'Mali', sans-serif;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #6366f1 0%, #4f46e5 100%);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #4f46e5 0%, #4338ca 100%);
        }
        
        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .dark .glass {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Gradient Border */
        .gradient-border {
            position: relative;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7) border-box;
            border: 2px solid transparent;
        }
        
        .dark .gradient-border {
            background: linear-gradient(#1e293b, #1e293b) padding-box,
                        linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7) border-box;
        }
        
        /* Sidebar animation */
        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-item:hover {
            transform: translateX(8px);
        }
        
        /* Button animations */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(99, 102, 241, 0.5);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(16, 185, 129, 0.5);
        }
        
        /* Card hover effects */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Loading spinner */
        .loader {
            border: 3px solid rgba(99, 102, 241, 0.2);
            border-top: 3px solid #6366f1;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* DataTables Custom Styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 0.5rem 0;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
        }
        
        .dark .dataTables_wrapper .dataTables_filter input {
            background: #374151;
            border-color: #4b5563;
            color: #fff;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            margin: 0 0.125rem;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            border-color: transparent !important;
            color: white !important;
        }
        
        /* Modal Styling */
        .modal-backdrop {
            backdrop-filter: blur(4px);
        }
        
        /* Print Styles */
        @media print {
            body * {
                visibility: hidden !important;
            }
            
            .print-area, .print-area * {
                visibility: visible !important;
            }
            
            .print-area {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
    
    <link rel="icon" type="image/png" href="../dist/img/<?php echo $global['logoLink'] ?? 'logo-phicha.png'; ?>">
</head>

<body class="font-mali bg-gradient-to-br from-indigo-50 via-purple-50 to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 min-h-screen transition-colors duration-500">
    
    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-slate-900 transition-opacity duration-500">
        <div class="text-center">
            <img src="../dist/img/<?php echo $global['logoLink'] ?? 'logo-phicha.png'; ?>" alt="Logo" class="w-24 h-24 mx-auto animate-bounce-slow">
            <div class="loader mx-auto mt-6"></div>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 animate-pulse">Teacher Portal</p>
        </div>
    </div>
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../components/teacher-sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-64">
            <!-- Navbar -->
            <header class="sticky top-0 z-30 glass border-b border-gray-200/50 dark:border-gray-700/50">
                <div class="px-3 sm:px-4 lg:px-8">
                    <div class="flex items-center justify-between h-14 sm:h-16">
                        <!-- Left Section -->
                        <div class="flex items-center flex-1 min-w-0">
                            <!-- Mobile Menu Button -->
                            <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-1 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors flex-shrink-0">
                                <i class="fas fa-bars text-lg sm:text-xl"></i>
                            </button>
                            
                            <!-- Breadcrumb or Title -->
                            <div class="ml-2 sm:ml-4 lg:ml-0 min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="hidden sm:inline-block px-2 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-medium rounded-full">Teacher</span>
                                    <h1 class="text-sm sm:text-lg font-semibold text-gray-800 dark:text-white truncate">
                                        <?php echo $pageTitle ?? 'Teacher Portal'; ?>
                                    </h1>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Section -->
                        <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                            <!-- Current Time (hidden on mobile) -->
                            <div class="hidden md:flex items-center space-x-2 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <i class="fas fa-clock text-indigo-500"></i>
                                <span id="currentTime" class="text-sm font-medium text-gray-600 dark:text-gray-300"></span>
                            </div>
                            
                            <!-- Dark Mode Toggle -->
                            <button onclick="toggleDarkMode()" class="relative w-12 sm:w-14 h-6 sm:h-7 bg-gray-200 dark:bg-gray-700 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 group">
                                <div class="absolute inset-1 flex items-center justify-between px-1">
                                    <i class="fas fa-sun text-[10px] sm:text-xs text-amber-500 opacity-100 dark:opacity-50 transition-opacity"></i>
                                    <i class="fas fa-moon text-[10px] sm:text-xs text-blue-300 opacity-50 dark:opacity-100 transition-opacity"></i>
                                </div>
                                <div class="absolute top-0.5 left-0.5 w-5 sm:w-6 h-5 sm:h-6 bg-white dark:bg-gray-800 rounded-full shadow-md transform transition-transform dark:translate-x-6 sm:dark:translate-x-7 flex items-center justify-center">
                                    <i class="fas fa-sun text-[10px] sm:text-xs text-amber-500 dark:hidden"></i>
                                    <i class="fas fa-moon text-[10px] sm:text-xs text-blue-300 hidden dark:block"></i>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto animate-fade-in">
                    <?php echo $content ?? ''; ?>
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="py-4 px-6 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
                <p>&copy; <?php echo date('Y') + 543; ?> <?php echo $global['nameschool']; ?> - Teacher Portal</p>
                <p class="mt-1"><?php echo $global['footerCredit'] ?? ''; ?></p>
            </footer>
        </div>
    </div>
    
    <script>
        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.style.opacity = '0';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }, 600);
        });
        
        // Update current time
        function updateTime() {
            const now = new Date();
            const options = { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit',
                hour12: false 
            };
            const timeString = now.toLocaleTimeString('th-TH', options);
            const dateElement = document.getElementById('currentTime');
            if (dateElement) {
                dateElement.textContent = timeString;
            }
        }
        
        updateTime();
        setInterval(updateTime, 1000);
        
        // Dark mode toggle
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        }
        
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true' || 
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
        
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }
        
        // Close sidebar on mobile when clicking a link
        function closeSidebarOnMobile() {
            if (window.innerWidth < 1024) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
        
        // Close sidebar on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
        
        // Handle swipe gestures for mobile
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, false);
        
        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);
        
        function handleSwipe() {
            const swipeThreshold = 80;
            const diff = touchEndX - touchStartX;
            const sidebar = document.getElementById('sidebar');
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            
            // Swipe right to open (from left edge)
            if (diff > swipeThreshold && touchStartX < 30 && !isOpen) {
                toggleSidebar();
            }
            // Swipe left to close
            if (diff < -swipeThreshold && isOpen && window.innerWidth < 1024) {
                toggleSidebar();
            }
        }
    </script>
</body>
</html>
