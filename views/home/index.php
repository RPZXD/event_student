<!-- Home Page Content - ระบบลงทะเบียนกิจกรรมนักเรียน -->
<div class="space-y-4 sm:space-y-6 lg:space-y-8">
    <!-- Hero Section with Animated Background -->
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-br from-green-600 via-emerald-600 to-teal-500 p-4 sm:p-6 md:p-8 lg:p-12">
        <div class="absolute inset-0 bg-grid-white/10 [mask-image:linear-gradient(0deg,transparent,black)]"></div>
        <div class="absolute top-0 right-0 w-48 sm:w-72 lg:w-96 h-48 sm:h-72 lg:h-96 bg-white/10 rounded-full blur-3xl -mr-24 sm:-mr-36 lg:-mr-48 -mt-24 sm:-mt-36 lg:-mt-48 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-36 sm:w-56 lg:w-72 h-36 sm:h-56 lg:h-72 bg-white/10 rounded-full blur-3xl -ml-18 sm:-ml-28 lg:-ml-36 -mb-18 sm:-mb-28 lg:-mb-36 animate-pulse" style="animation-delay: 1s;"></div>
        
        <div class="relative flex flex-col items-center gap-4 sm:gap-6 lg:flex-row lg:gap-8">
            <div class="flex-shrink-0">
                <div class="relative">
                    <div class="absolute inset-0 bg-white/20 rounded-full blur-xl animate-pulse"></div>
                    <img src="dist/img/<?php echo $global['logoLink'] ?? 'logo-phicha.png'; ?>" 
                        alt="<?php echo $global['nameschool']; ?> Logo"
                        class="relative w-20 h-20 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:w-40 lg:h-40 rounded-full shadow-2xl border-4 border-white/30 hover:scale-110 transition-transform duration-500">
                </div>
            </div>
            <div class="text-center lg:text-left text-white">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-white/20 backdrop-blur-sm text-xs sm:text-sm font-medium mb-3 sm:mb-4">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    ระบบพร้อมใช้งาน
                </div>
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold mb-2 sm:mb-3">
                    🎓 ระบบลงทะเบียนกิจกรรมนักเรียน
                </h1>
                <p class="text-sm sm:text-base md:text-lg lg:text-xl text-white/80 mb-4 sm:mb-6">
                    <?php echo $global['nameschool']; ?>
                </p>
                <div class="flex flex-wrap gap-2 sm:gap-3 justify-center lg:justify-start">
                    <span class="px-2.5 py-1.5 sm:px-4 sm:py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium flex items-center gap-1 sm:gap-2">
                        <span>📋</span> <span class="hidden xs:inline">ลงทะเบียน</span><span class="xs:hidden">ลงทะเบียน</span>
                    </span>
                    <span class="px-2.5 py-1.5 sm:px-4 sm:py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium flex items-center gap-1 sm:gap-2">
                        <span>📅</span> <span class="hidden xs:inline">ปฏิทิน</span><span class="xs:hidden">ปฏิทิน</span>
                    </span>
                    <span class="px-2.5 py-1.5 sm:px-4 sm:py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium flex items-center gap-1 sm:gap-2">
                        <span>📊</span> <span class="hidden xs:inline">ติดตาม</span><span class="xs:hidden">ติดตาม</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="glass rounded-xl sm:rounded-2xl p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-12 h-12 sm:w-14 sm:h-14 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl sm:rounded-2xl text-white text-xl sm:text-2xl">
                📝
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">ยินดีต้อนรับ!</h2>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">กรุณาเลือกกิจกรรมที่คุณสนใจและลงทะเบียนเพื่อเข้าร่วมกิจกรรมต่าง ๆ ของโรงเรียน 🚀</p>
            </div>
        </div>
    </div>

    <!-- Activity Calendar Section -->
    <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg sm:rounded-xl text-white text-lg sm:text-xl">
                        📅
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">ปฏิทินกิจกรรม</h2>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">กิจกรรมที่เปิดรับลงทะเบียน</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-4 sm:p-6">
            <div id="activityLoading" class="text-center py-8 sm:py-12">
                <div class="inline-block w-10 h-10 sm:w-12 sm:h-12 border-4 border-green-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-3 sm:mt-4 text-sm sm:text-base text-gray-500 dark:text-gray-400">กำลังโหลดกิจกรรม...</p>
            </div>
            
            <div id="activityEmpty" class="hidden text-center py-8 sm:py-12">
                <div class="text-5xl sm:text-6xl mb-3 sm:mb-4">📭</div>
                <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400">ยังไม่มีกิจกรรมที่เปิดรับลงทะเบียน</p>
            </div>
            
            <!-- Mobile Cards View -->
            <div id="activityCardsWrapper" class="hidden md:hidden space-y-3">
                <!-- Cards will be inserted here for mobile -->
            </div>
            
            <!-- Desktop Table View -->
            <div class="overflow-x-auto hidden md:block" id="activityTableWrapper">
                <table id="activity-table" class="w-full text-center text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30">
                            <th class="py-3 px-3 text-left font-semibold text-gray-700 dark:text-gray-300 rounded-l-lg whitespace-nowrap">📅 วันที่</th>
                            <th class="py-3 px-3 text-left font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">🎯 ชื่อกิจกรรม</th>
                            <th class="py-3 px-3 text-left font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap hidden lg:table-cell">👩‍🏫 ผู้รับผิดชอบ</th>
                            <th class="py-3 px-3 text-center font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">⏰ ชั่วโมง</th>
                            <th class="py-3 px-3 text-center font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">👥 จำนวน</th>
                            <th class="py-3 px-3 text-center font-semibold text-gray-700 dark:text-gray-300 rounded-r-lg whitespace-nowrap hidden xl:table-cell">🏷️ ประเภท</th>
                        </tr>
                    </thead>
                    <tbody id="activity-table-body" class="divide-y divide-gray-100 dark:divide-gray-700">
                        <!-- ข้อมูลจะถูกเติมโดย JS -->
                    </tbody>
                </table>
            </div>
            <div class="text-xs sm:text-sm text-gray-400 mt-3 sm:mt-4 hidden" id="activityNote">* กิจกรรมที่ครูได้สร้างไว้</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass rounded-xl sm:rounded-2xl p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4 sm:mb-6 flex items-center gap-2">
            <span class="text-xl sm:text-2xl">⚡</span> เข้าสู่ระบบเพื่อใช้งาน
        </h3>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
            <a href="login.php" class="group p-4 sm:p-5 rounded-xl sm:rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 text-white hover:shadow-xl hover:-translate-y-1 transition-all active:scale-95">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 flex-shrink-0 flex items-center justify-center bg-white/20 backdrop-blur rounded-lg sm:rounded-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-sign-in-alt text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-base sm:text-lg">เข้าสู่ระบบ</h4>
                        <p class="text-xs sm:text-sm text-white/80">สำหรับนักเรียน</p>
                    </div>
                </div>
            </a>
            
            <a href="teacher/" class="group p-4 sm:p-5 rounded-xl sm:rounded-2xl border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all active:scale-95">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 flex-shrink-0 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg sm:rounded-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-chalkboard-teacher text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-base sm:text-lg text-gray-900 dark:text-white">ครู/บุคลากร</h4>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Teacher Portal</p>
                    </div>
                </div>
            </a>
            
            <a href="admin/" class="group p-4 sm:p-5 rounded-xl sm:rounded-2xl border-2 border-gray-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all active:scale-95 sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 flex-shrink-0 flex items-center justify-center bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg sm:rounded-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-shield text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-base sm:text-lg text-gray-900 dark:text-white">ผู้ดูแลระบบ</h4>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Admin Portal</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="glass rounded-xl sm:rounded-2xl p-4 sm:p-6 text-center">
        <div class="text-3xl sm:text-4xl mb-2 sm:mb-3">🎓</div>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 font-medium"><?php echo $global['nameschool']; ?></p>
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-500 mt-1">Student Event Registration System v1.0</p>
    </div>
</div>

<style>
    .bg-grid-white\/10 {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgba(255,255,255,0.1)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
    }
    
    /* Custom breakpoint for extra small devices */
    @media (min-width: 480px) {
        .xs\:inline { display: inline; }
        .xs\:hidden { display: none; }
    }
</style>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
$(document).ready(function() {
    loadActivities();
});

function loadActivities() {
    fetch('controllers/EventController.php?order_by=event_date_desc')
        .then(res => res.json())
        .then(data => {
            $('#activityLoading').addClass('hidden');
            
            if (data.success && Array.isArray(data.events) && data.events.length > 0) {
                const tbody = document.getElementById('activity-table-body');
                const cardsWrapper = document.getElementById('activityCardsWrapper');
                tbody.innerHTML = '';
                cardsWrapper.innerHTML = '';
                
                data.events.forEach(ev => {
                    const thaiDate = new Date(ev.event_date).toLocaleDateString('th-TH', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                    
                    // Progress bar
                    let progressBar = '';
                    let statusBadge = '';
                    let progressBarMobile = '';
                    if (ev.max_students && ev.max_students > 0) {
                        const current = ev.current_students || 0;
                        const percent = Math.min((current / ev.max_students) * 100, 100);
                        const isFull = current >= ev.max_students;
                        
                        progressBar = `
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-2">
                                <div class="h-2 rounded-full transition-all ${isFull ? 'bg-red-500' : 'bg-green-500'}" style="width: ${percent}%;"></div>
                            </div>
                        `;
                        
                        progressBarMobile = `
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5 mt-1">
                                <div class="h-1.5 rounded-full transition-all ${isFull ? 'bg-red-500' : 'bg-green-500'}" style="width: ${percent}%;"></div>
                            </div>
                        `;
                        
                        if (isFull) {
                            statusBadge = '<span class="inline-block px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs rounded-full ml-2">เต็ม</span>';
                        }
                    }
                    
                    // Mobile Card
                    cardsWrapper.innerHTML += `
                        <div class="bg-white dark:bg-slate-700 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-600">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-gray-900 dark:text-white text-sm flex-1 pr-2">🎯 ${ev.title}</h4>
                                <span class="inline-block px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-medium whitespace-nowrap">
                                    ${ev.category ?? '-'}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-1">
                                    <span>📅</span> ${thaiDate}
                                </div>
                                <div class="flex items-center gap-1">
                                    <span>⏰</span> ${ev.hours ?? '-'} ชม.
                                </div>
                                <div class="flex items-center gap-1 col-span-2">
                                    <span>👩‍🏫</span> ${ev.teacher_name || ev.teacher_id}
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-600">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600 dark:text-gray-400">
                                        👥 ${ev.max_students && ev.max_students > 0 ? (ev.current_students || 0) + '/' + ev.max_students : 'ไม่จำกัด'}
                                    </span>
                                    ${statusBadge}
                                </div>
                                ${progressBarMobile}
                            </div>
                        </div>
                    `;
                    
                    // Desktop Table Row
                    tbody.innerHTML += `
                        <tr class="hover:bg-green-50 dark:hover:bg-green-900/10 transition-colors">
                            <td class="py-3 px-3 text-left whitespace-nowrap">
                                <span class="font-medium text-gray-900 dark:text-white text-sm">${thaiDate}</span>
                            </td>
                            <td class="py-3 px-3 text-left">
                                <span class="font-semibold text-gray-900 dark:text-white text-sm">🎯 ${ev.title}</span>
                            </td>
                            <td class="py-3 px-3 text-left text-gray-600 dark:text-gray-400 text-sm hidden lg:table-cell">
                                ${ev.teacher_name || ev.teacher_id}
                            </td>
                            <td class="py-3 px-3 text-center text-gray-600 dark:text-gray-400 text-sm">
                                ${ev.hours ?? '-'}
                            </td>
                            <td class="py-3 px-3 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-gray-600 dark:text-gray-400 text-sm">
                                        ${ev.max_students && ev.max_students > 0 ? (ev.current_students || 0) + '/' + ev.max_students : 'ไม่จำกัด'}
                                    </span>
                                    ${progressBar}
                                </div>
                            </td>
                            <td class="py-3 px-3 text-center hidden xl:table-cell">
                                <span class="inline-block px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-medium">
                                    ${ev.category ?? '-'}
                                </span>
                            </td>
                        </tr>
                    `;
                });
                
                // Show appropriate view based on screen size
                $('#activityCardsWrapper').removeClass('hidden');
                $('#activityNote').removeClass('hidden');
                
                // Initialize DataTables for desktop only
                if (window.innerWidth >= 768) {
                    $('#activity-table').DataTable({
                        order: [[0, 'desc']],
                        pageLength: 10,
                        responsive: true,
                        language: {
                            search: "ค้นหา:",
                            lengthMenu: "แสดง _MENU_ รายการ",
                            info: "แสดง _START_ - _END_ จาก _TOTAL_",
                            paginate: {
                                first: "«",
                                last: "»",
                                next: "›",
                                previous: "‹"
                            },
                            zeroRecords: "ไม่พบข้อมูล",
                            infoEmpty: "ไม่มีข้อมูล",
                            infoFiltered: "(จาก _MAX_)"
                        }
                    });
                }
            } else {
                $('#activityEmpty').removeClass('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading activities:', error);
            $('#activityLoading').addClass('hidden');
            $('#activityEmpty').removeClass('hidden').html('<div class="text-5xl sm:text-6xl mb-3 sm:mb-4">❌</div><p class="text-sm sm:text-base text-gray-500 dark:text-gray-400">ไม่สามารถโหลดกิจกรรมได้</p>');
        });
}
</script>
