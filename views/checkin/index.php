<!-- Checkin Page Content - ลงทะเบียนกิจกรรม -->
<div class="space-y-8">
    <!-- Page Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-500 p-6 sm:p-8 md:p-10">
        <div class="absolute inset-0 bg-grid-white/10 [mask-image:linear-gradient(0deg,transparent,black)]"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl -mr-36 -mt-36 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-56 h-56 bg-white/10 rounded-full blur-3xl -ml-28 -mb-28 animate-pulse" style="animation-delay: 1s;"></div>
        
        <div class="relative text-center text-white">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm text-sm font-medium mb-4">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                พร้อมลงทะเบียน
            </div>
            <h1 class="text-3xl md:text-4xl font-bold mb-3">
                📲 เข้าร่วมกิจกรรม
            </h1>
            <p class="text-lg text-white/80">
                กรอกรหัสกิจกรรมเพื่อลงทะเบียนเข้าร่วม
            </p>
        </div>
    </div>

    <!-- Checkin Form Card -->
    <div class="max-w-2xl mx-auto">
        <div class="glass rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <div class="flex items-center gap-3 text-white">
                    <div class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-xl">
                        <i class="fas fa-qrcode text-xl"></i>
                    </div>
                    <h2 class="text-lg font-semibold">ลงทะเบียนกิจกรรม</h2>
                </div>
            </div>
            
            <div class="p-4 sm:p-6">
                <!-- Search Form -->
                <form method="get" class="mb-6">
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
                        <i class="fas fa-key mr-2"></i>กรอกรหัสกิจกรรม
                    </label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" name="code" value="<?= htmlspecialchars($code) ?>" required 
                            class="flex-1 border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-500 transition-all text-gray-900 dark:text-white" 
                            placeholder="รหัสกิจกรรม">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-lg flex items-center gap-2">
                            <i class="fas fa-search"></i>
                            <span>ค้นหา</span>
                        </button>
                    </div>
                </form>
                
                <?php if ($code): ?>
                <!-- Activity Details -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        รายละเอียดกิจกรรม
                    </h3>
                    
                    <?php if ($activity): ?>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5 mb-4">
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="text-xl">🎯</span>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">ชื่อกิจกรรม</span>
                                    <p class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($activity['title']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-xl">📝</span>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">รายละเอียด</span>
                                    <p class="text-gray-700 dark:text-gray-300"><?= htmlspecialchars($activity['description'] ?? '-') ?></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">📅</span>
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">วันที่จัด</span>
                                        <p class="font-medium text-gray-900 dark:text-white"><?= $thaiDate ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">⏰</span>
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">ชั่วโมง</span>
                                        <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($activity['hours']) ?> ชั่วโมง</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">🏷️</span>
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">ประเภท</span>
                                        <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($activity['category'] ?? '-') ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">👥</span>
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">จำนวนสูงสุด</span>
                                        <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($activity['max_students'] ?? 'ไม่จำกัด') ?> คน</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($can_register && $activity_id): ?>
                    <button id="register-btn" 
                        class="w-full py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold text-lg rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2"
                        data-activity-id="<?= $activity_id ?>" data-code="<?= htmlspecialchars($code) ?>">
                        <i class="fas fa-check-circle"></i>
                        ลงทะเบียนกิจกรรม
                    </button>
                    <?php endif; ?>
                    
                    <?php if ($already_registered): ?>
                    <div class="w-full py-4 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-700 dark:text-green-400 font-semibold text-center rounded-xl flex items-center justify-center gap-2">
                        <i class="fas fa-check-double"></i>
                        คุณได้ลงทะเบียนกิจกรรมนี้แล้ว
                    </div>
                    <?php endif; ?>
                    
                    <?php else: ?>
                    <div class="text-center py-8">
                        <div class="text-6xl mb-4">❌</div>
                        <p class="text-red-500 dark:text-red-400 font-semibold text-lg">ไม่พบกิจกรรมที่ตรงกับรหัสนี้</p>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">กรุณาตรวจสอบรหัสอีกครั้ง</p>
                    </div>
                    <?php endif; ?>
                    
                    <div id="register-result" class="mt-4"></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="max-w-2xl mx-auto">
        <div class="glass rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="text-xl">🔗</span> ลิงก์ที่เกี่ยวข้อง
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="index.php" class="group p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-home"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">หน้าหลัก</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">กลับไปหน้าหลัก</p>
                        </div>
                    </div>
                </a>
                <a href="login.php" class="group p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">เข้าสู่ระบบ</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">สำหรับนักเรียน</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-grid-white\/10 {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgba(255,255,255,0.1)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const regBtn = document.getElementById('register-btn');
    if (regBtn) {
        regBtn.onclick = function() {
            Swal.fire({
                title: 'กรอกเลขประจำตัวนักเรียน',
                input: 'text',
                inputLabel: 'เลขประจำตัวนักเรียน',
                inputPlaceholder: 'กรอกเลขประจำตัว',
                inputAttributes: {
                    maxlength: 20,
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#10b981',
                inputValidator: (value) => {
                    if (!value) {
                        return 'กรุณากรอกเลขประจำตัว';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    regBtn.disabled = true;
                    regBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังลงทะเบียน...';
                    fetch('controllers/EventController.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'register_activity',
                            activity_id: regBtn.dataset.activityId,
                            code: regBtn.dataset.code,
                            student_id: result.value.trim()
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: 'ลงทะเบียนกิจกรรมเรียบร้อย',
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'ผิดพลาด',
                                text: res.message || 'ไม่สามารถลงทะเบียนได้',
                                confirmButtonColor: '#ef4444'
                            });
                            regBtn.disabled = false;
                            regBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>ลงทะเบียนกิจกรรม';
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์',
                            confirmButtonColor: '#ef4444'
                        });
                        regBtn.disabled = false;
                        regBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>ลงทะเบียนกิจกรรม';
                    });
                }
            });
        };
    }
});
</script>
