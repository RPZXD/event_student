<!-- Student Dashboard - หน้าหลัก -->
<?php 
// ดึงข้อมูลนักเรียนจาก session
$user = $_SESSION['user'] ?? [];
$studentName = trim(($user['Stu_pre'] ?? '') . ($user['Stu_name'] ?? '') . ' ' . ($user['Stu_sur'] ?? ''));
$term = $_SESSION['term'] ?? '-';
$pee = $_SESSION['pee'] ?? '-';
?>
<div class="space-y-4 sm:space-y-6 lg:space-y-8">
    
    <!-- Hero Section -->
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-br from-emerald-500 via-green-500 to-teal-500 p-4 sm:p-6 md:p-8 lg:p-12">
        <div class="absolute inset-0 bg-grid-white/10 [mask-image:linear-gradient(0deg,transparent,black)]"></div>
        <div class="absolute top-0 right-0 w-48 sm:w-72 lg:w-96 h-48 sm:h-72 lg:h-96 bg-white/10 rounded-full blur-3xl -mr-24 sm:-mr-36 lg:-mr-48 -mt-24 sm:-mt-36 lg:-mt-48 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-36 sm:w-56 lg:w-72 h-36 sm:h-56 lg:h-72 bg-white/10 rounded-full blur-3xl -ml-18 sm:-ml-28 lg:-ml-36 -mb-18 sm:-mb-28 lg:-mb-36 animate-pulse" style="animation-delay: 1s;"></div>
        
        <div class="relative flex flex-col items-center gap-4 sm:gap-6 lg:flex-row lg:gap-8">
            <div class="flex-shrink-0">
                <div class="relative">
                    <div class="absolute inset-0 bg-white/20 rounded-full blur-xl animate-pulse"></div>
                    <?php if (!empty($user['Stu_picture'])): ?>
                    <img src="https://std.phichai.ac.th/<?php echo htmlspecialchars($user['Stu_picture']); ?>" 
                         class="relative w-20 h-20 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:w-40 lg:h-40 rounded-full object-cover shadow-2xl border-4 border-white/30"
                         onerror="this.parentElement.innerHTML='<div class=\'relative w-20 h-20 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:w-40 lg:h-40 rounded-full bg-gradient-to-br from-white/30 to-white/10 flex items-center justify-center shadow-2xl border-4 border-white/30\'><span class=\'text-4xl sm:text-5xl md:text-6xl lg:text-7xl\'>👨‍🎓</span></div>'">
                    <?php else: ?>
                    <div class="relative w-20 h-20 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:w-40 lg:h-40 rounded-full bg-gradient-to-br from-white/30 to-white/10 flex items-center justify-center shadow-2xl border-4 border-white/30">
                        <span class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl">👨‍🎓</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-center lg:text-left text-white">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-white/20 backdrop-blur-sm text-xs sm:text-sm font-medium mb-3 sm:mb-4">
                    <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                    ปีการศึกษา <?php echo htmlspecialchars($pee); ?> ภาคเรียน <?php echo htmlspecialchars($term); ?>
                </div>
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold mb-2 sm:mb-3">
                    สวัสดี, <?php echo htmlspecialchars($studentName); ?>
                </h1>
                <p class="text-sm sm:text-base md:text-lg lg:text-xl text-white/80 mb-4 sm:mb-6">
                    Student Portal - ระบบลงทะเบียนกิจกรรมนักเรียน
                </p>
                <div class="flex flex-wrap gap-2 sm:gap-3 justify-center lg:justify-start">
                    <span class="px-2.5 py-1.5 sm:px-4 sm:py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium flex items-center gap-1 sm:gap-2">
                        <span>📝</span> ลงทะเบียน
                    </span>
                    <span class="px-2.5 py-1.5 sm:px-4 sm:py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium flex items-center gap-1 sm:gap-2">
                        <span>📊</span> ติดตามผล
                    </span>
                    <span class="px-2.5 py-1.5 sm:px-4 sm:py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium flex items-center gap-1 sm:gap-2">
                        <span>📈</span> สะสมชั่วโมง
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="event_regis.php" class="group glass rounded-xl sm:rounded-2xl p-4 sm:p-6 card-hover border-2 border-transparent hover:border-emerald-500 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 sm:w-16 sm:h-16 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl sm:rounded-2xl text-white text-2xl sm:text-3xl group-hover:scale-110 transition-transform shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg sm:text-xl text-gray-900 dark:text-white">ลงทะเบียนกิจกรรม</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">ลงทะเบียนเข้าร่วมกิจกรรม</p>
                </div>
            </div>
        </a>
        
        <a href="event_list.php" class="group glass rounded-xl sm:rounded-2xl p-4 sm:p-6 card-hover border-2 border-transparent hover:border-blue-500 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 sm:w-16 sm:h-16 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl sm:rounded-2xl text-white text-2xl sm:text-3xl group-hover:scale-110 transition-transform shadow-lg shadow-blue-500/30">
                    <i class="fas fa-list-check"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg sm:text-xl text-gray-900 dark:text-white">กิจกรรมของฉัน</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">ดูกิจกรรมที่ลงทะเบียน</p>
                </div>
            </div>
        </a>
        
        <a href="../index.php" class="group glass rounded-xl sm:rounded-2xl p-4 sm:p-6 card-hover border-2 border-transparent hover:border-gray-500 transition-all sm:col-span-2 lg:col-span-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 sm:w-16 sm:h-16 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-gray-600 to-gray-700 rounded-xl sm:rounded-2xl text-white text-2xl sm:text-3xl group-hover:scale-110 transition-transform shadow-lg shadow-gray-500/30">
                    <i class="fas fa-home"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg sm:text-xl text-gray-900 dark:text-white">หน้าหลัก</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">กลับไปหน้าเว็บหลัก</p>
                </div>
            </div>
        </a>
    </div>

    <!-- User Guide Section -->
    <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg sm:rounded-xl text-white text-lg sm:text-xl">
                    📖
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">คู่มือการใช้งาน</h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">ขั้นตอนการลงทะเบียนกิจกรรม</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 sm:p-6">
            <div class="grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Step 1 -->
                <div class="flex gap-4 p-4 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-100 dark:border-blue-800">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-blue-500 text-white font-bold rounded-full text-lg">1</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">เข้าสู่ระบบ</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">ล็อกอินด้วยบัญชีนักเรียน</p>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="flex gap-4 p-4 rounded-xl bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-100 dark:border-green-800">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-green-500 text-white font-bold rounded-full text-lg">2</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">ไปที่ลงทะเบียน</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">เลือกเมนูลงทะเบียนกิจกรรม</p>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="flex gap-4 p-4 rounded-xl bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-100 dark:border-yellow-800">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-yellow-500 text-white font-bold rounded-full text-lg">3</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">เลือกกิจกรรม</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">เลือกกิจกรรมที่ต้องการ</p>
                    </div>
                </div>
                
                <!-- Step 4 -->
                <div class="flex gap-4 p-4 rounded-xl bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 border border-purple-100 dark:border-purple-800">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-purple-500 text-white font-bold rounded-full text-lg">4</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">กรอกรหัส</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">ใส่รหัสกิจกรรมจากครู</p>
                    </div>
                </div>
                
                <!-- Step 5 -->
                <div class="flex gap-4 p-4 rounded-xl bg-gradient-to-br from-pink-50 to-rose-50 dark:from-pink-900/20 dark:to-rose-900/20 border border-pink-100 dark:border-pink-800">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-pink-500 text-white font-bold rounded-full text-lg">5</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">ยืนยันลงทะเบียน</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">กดยืนยันและเสร็จสิ้น</p>
                    </div>
                </div>
                
                <!-- Step 6 -->
                <div class="flex gap-4 p-4 rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border border-emerald-100 dark:border-emerald-800">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-emerald-500 text-white font-bold rounded-full text-lg">6</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">ตรวจสอบสถานะ</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">ดูกิจกรรมที่ลงทะเบียนแล้ว</p>
                    </div>
                </div>
            </div>
            
            <!-- Note -->
            <div class="mt-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">💡</span>
                    <div>
                        <h4 class="font-semibold text-emerald-900 dark:text-emerald-300">หมายเหตุ</h4>
                        <p class="text-sm text-emerald-700 dark:text-emerald-400">1 กิจกรรมใช้ได้ 1 รหัสต่อ 1 คน หากมีปัญหา กรุณาติดต่อครูผู้ดูแลกิจกรรม</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="glass rounded-xl sm:rounded-2xl p-4 sm:p-6 text-center">
        <div class="text-3xl sm:text-4xl mb-2 sm:mb-3">👨‍🎓</div>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 font-medium"><?php echo $global['nameschool']; ?></p>
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-500 mt-1">Student Portal v2.0</p>
    </div>
</div>

<style>
    .bg-grid-white\/10 {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgba(255,255,255,0.1)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
    }
</style>
