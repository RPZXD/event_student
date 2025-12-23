<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $pageTitle ?? $global['pageTitle']; ?> | <?php echo $global['nameschool']; ?></title>
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Font: Mali -->
    <link href="https://fonts.googleapis.com/css2?family=Mali:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    
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
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7',
                            400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857',
                            800: '#065f46', 900: '#064e3b', 950: '#022c22'
                        }
                    },
                    animation: {
                        'bounce-slow': 'bounce 3s infinite',
                        'pulse-slow': 'pulse 3s infinite',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'fade-in': 'fadeIn 0.3s ease-out',
                    },
                    keyframes: {
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: 0 },
                            '100%': { transform: 'translateY(0)', opacity: 1 }
                        },
                        slideDown: {
                            '0%': { transform: 'translateY(-20px)', opacity: 0 },
                            '100%': { transform: 'translateY(0)', opacity: 1 }
                        },
                        fadeIn: {
                            '0%': { opacity: 0 },
                            '100%': { opacity: 1 }
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        * { font-family: 'Mali', sans-serif; }
        
        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .dark .glass {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Card hover effect */
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(16, 185, 129, 0.3);
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(16, 185, 129, 0.5);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s, visibility 0.5s;
        }
        .dark .preloader {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        .preloader.hidden { opacity: 0; visibility: hidden; }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 3px; }
        .dark ::-webkit-scrollbar-track { background: #1e293b; }
        .dark ::-webkit-scrollbar-thumb { background: #059669; }
        
        /* Main content area */
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }
        @media (min-width: 1024px) {
            .main-content { margin-left: 16rem; }
        }
    </style>
</head>
<body class="font-mali bg-gradient-to-br from-slate-50 via-emerald-50 to-green-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 min-h-screen transition-colors duration-500">
    
    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="text-center">
            <div class="relative w-24 h-24 mx-auto mb-4">
                <div class="absolute inset-0 rounded-full border-4 border-emerald-200 dark:border-emerald-800"></div>
                <div class="absolute inset-0 rounded-full border-4 border-emerald-500 border-t-transparent animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-3xl">👨‍🎓</span>
                </div>
            </div>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium animate-pulse">กำลังโหลด...</p>
        </div>
    </div>
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/../components/student-sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content min-h-screen">
        <!-- Top Header -->
        <header class="sticky top-0 z-30 glass border-b border-gray-200/50 dark:border-gray-700/50">
            <div class="flex items-center justify-between px-4 py-3">
                <!-- Mobile menu button -->
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-bars text-gray-600 dark:text-gray-300 text-xl"></i>
                </button>
                
                <!-- Page Title (Mobile) -->
                <h1 class="lg:hidden text-lg font-bold text-gray-800 dark:text-white truncate"><?php echo $pageTitle ?? 'Student Portal'; ?></h1>
                
                <!-- Right side items -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Current time -->
                    <div class="hidden sm:flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-clock"></i>
                        <span id="current-time"></span>
                    </div>
                    
                    <!-- Dark mode toggle -->
                    <button onclick="toggleDarkMode()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="เปลี่ยนธีม">
                        <i class="fas fa-sun text-yellow-500 dark:hidden"></i>
                        <i class="fas fa-moon text-emerald-400 hidden dark:inline"></i>
                    </button>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8">
            <?php echo $content ?? ''; ?>
        </main>
        
        <!-- Footer -->
        <footer class="glass border-t border-gray-200/50 dark:border-gray-700/50 py-4 px-6 text-center text-sm text-gray-500 dark:text-gray-400">
            <p>&copy; <?php echo date('Y'); ?> <?php echo $global['nameschool']; ?> - Student Portal</p>
        </footer>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <!-- QRCode.js -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    
    <script>
        // Hide preloader
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('preloader').classList.add('hidden');
            }, 500);
        });
        
        // Dark mode
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        }
        
        // Apply saved dark mode
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
        
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        
        function closeSidebarOnMobile() {
            if (window.innerWidth < 1024) {
                document.getElementById('sidebar').classList.add('-translate-x-full');
                document.getElementById('sidebar-overlay').classList.add('hidden');
            }
        }
        
        // Update time
        function updateTime() {
            const now = new Date();
            const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            document.getElementById('current-time').textContent = now.toLocaleTimeString('th-TH', options);
        }
        setInterval(updateTime, 1000);
        updateTime();
        
        // Touch gestures for mobile
        let touchStartX = 0;
        document.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
        document.addEventListener('touchend', e => {
            const touchEndX = e.changedTouches[0].screenX;
            const diff = touchEndX - touchStartX;
            if (Math.abs(diff) > 100) {
                const sidebar = document.getElementById('sidebar');
                if (diff > 0 && sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                } else if (diff < 0 && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
                    toggleSidebar();
                }
            }
        }, { passive: true });
    </script>
</body>
</html>
