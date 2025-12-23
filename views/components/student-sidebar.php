<?php 
$config = json_decode(file_get_contents(__DIR__ . '/../../config.json'), true);
$global = $config['global'];

// Get current page for active state
$currentPage = basename($_SERVER['PHP_SELF']);

// Menu configuration for Student
$menuItems = [
    [
        'key' => 'home',
        'name' => 'หน้าหลัก',
        'url' => 'index.php',
        'icon' => 'fa-home',
        'gradient' => ['from' => 'blue-500', 'to' => 'blue-600'],
    ],
    [
        'key' => 'event_regis',
        'name' => 'ลงทะเบียนกิจกรรม',
        'url' => 'event_regis.php',
        'icon' => 'fa-calendar-check',
        'gradient' => ['from' => 'green-500', 'to' => 'emerald-600'],
    ],
    [
        'key' => 'event_list',
        'name' => 'กิจกรรมที่ลงทะเบียน',
        'url' => 'event_list.php',
        'icon' => 'fa-list-check',
        'gradient' => ['from' => 'purple-500', 'to' => 'violet-600'],
    ]
];

// Get student name from session
$studentName = '';
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $studentName = trim(($user['Stu_pre'] ?? '') . ($user['Stu_name'] ?? '') . ' ' . ($user['Stu_sur'] ?? ''));
}
?>

<!-- Sidebar Overlay (Mobile) -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 z-40 w-72 sm:w-64 h-screen transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
    <!-- Sidebar Background - Green theme for students -->
    <div class="h-full overflow-y-auto bg-gradient-to-b from-emerald-700 via-emerald-800 to-slate-950 dark:from-slate-900 dark:via-slate-950 dark:to-black">
        
        <!-- Logo Section with Close Button -->
        <div class="px-4 sm:px-6 py-5 border-b border-white/10">
            <div class="flex items-center justify-between">
                <a href="index.php" class="flex items-center space-x-3 group flex-1 min-w-0">
                    <div class="relative flex-shrink-0">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-green-500 rounded-full blur-lg opacity-50 group-hover:opacity-75 transition-opacity"></div>
                        <img src="../dist/img/<?php echo $global['logoLink'] ?? 'logo-phicha.png'; ?>" class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-full ring-2 ring-white/20 group-hover:ring-emerald-400 transition-all" alt="Logo">
                    </div>
                    <div class="min-w-0">
                        <span class="text-base sm:text-lg font-bold text-white truncate block">Student Portal</span>
                        <p class="text-xs text-emerald-300 truncate">ระบบนักเรียน</p>
                    </div>
                </a>
                <!-- Close button for mobile -->
                <button onclick="toggleSidebar()" class="lg:hidden p-2 -mr-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- User Info -->
        <?php if (isset($_SESSION['user'])): ?>
        <div class="px-4 sm:px-6 py-4 border-b border-white/10">
            <div class="flex items-center space-x-3">
                <?php if (!empty($_SESSION['user']['Stu_picture'])): ?>
                <img src="https://std.phichai.ac.th/<?php echo htmlspecialchars($_SESSION['user']['Stu_picture']); ?>" 
                     class="w-10 h-10 rounded-full object-cover border-2 border-emerald-400"
                     onerror="this.outerHTML='<div class=\'w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-white font-bold text-lg shadow-lg\'>👨‍🎓</div>'">
                <?php else: ?>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    👨‍🎓
                </div>
                <?php endif; ?>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-white truncate"><?php echo htmlspecialchars($studentName); ?></p>
                    <p class="text-xs text-emerald-300">ม.<?php echo htmlspecialchars($_SESSION['user']['Stu_major'] ?? 'นักเรียน'); ?>/<?php echo htmlspecialchars($_SESSION['user']['Stu_room'] ?? ''); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Navigation -->
        <nav class="mt-4 sm:mt-6 px-2 sm:px-3 pb-20">
            <p class="px-4 mb-3 text-xs font-semibold text-emerald-400 uppercase tracking-wider">เมนูหลัก</p>
            <ul class="space-y-1">
                <!-- Main Menu Items -->
                <?php foreach ($menuItems as $menu): 
                    $fromColor = $menu['gradient']['from'];
                    $toColor = $menu['gradient']['to'];
                    $colorBase = explode('-', $fromColor)[0];
                    $isActive = ($currentPage === $menu['url']);
                ?>
                <li>
                    <a href="<?php echo htmlspecialchars($menu['url']); ?>" 
                       class="sidebar-item flex items-center px-3 sm:px-4 py-3 rounded-xl group active:scale-95 transition-all <?php echo $isActive ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white'; ?>" 
                       onclick="closeSidebarOnMobile()">
                        <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-gradient-to-br from-<?php echo $fromColor; ?> to-<?php echo $toColor; ?> rounded-lg shadow-lg shadow-<?php echo $colorBase; ?>-500/30 group-hover:shadow-<?php echo $colorBase; ?>-500/50 transition-shadow">
                            <i class="fas <?php echo $menu['icon']; ?> text-white text-sm sm:text-base"></i>
                        </span>
                        <span class="ml-3 font-medium text-sm sm:text-base"><?php echo htmlspecialchars($menu['name']); ?></span>
                        <?php if ($isActive): ?>
                        <span class="ml-auto w-2 h-2 bg-white rounded-full"></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endforeach; ?>
                
                <!-- Divider -->
                <li class="my-4 border-t border-white/10"></li>
                
               
                <!-- Logout -->
                <li>
                    <a href="../logout.php" class="sidebar-item flex items-center px-3 sm:px-4 py-3 text-gray-300 rounded-xl hover:bg-white/10 hover:text-white group active:scale-95 transition-all">
                        <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg shadow-red-500/30 group-hover:shadow-red-500/50 transition-shadow">
                            <i class="fas fa-sign-out-alt text-white text-sm sm:text-base"></i>
                        </span>
                        <span class="ml-3 font-medium text-sm sm:text-base">ออกจากระบบ</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Bottom Section -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10 bg-gradient-to-t from-slate-950 to-transparent">
            <div class="text-center text-xs text-gray-500">
                <p><?php echo $global['nameschool'] ?? ''; ?></p>
                <p class="mt-1">Student Portal v2.0</p>
            </div>
        </div>
    </div>
</aside>
