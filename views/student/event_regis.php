<!-- Student Event Registration - ลงทะเบียนกิจกรรม -->
<?php 
$user = $_SESSION['user'] ?? [];
$studentName = trim(($user['Stu_pre'] ?? '') . ($user['Stu_name'] ?? '') . ' ' . ($user['Stu_sur'] ?? ''));
?>

<div class="space-y-4 sm:space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                📝 ลงทะเบียนกิจกรรม
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">เลือกกิจกรรมที่ต้องการลงทะเบียนและกรอกรหัสเข้าร่วม</p>
        </div>
        <a href="event_list.php" 
           class="btn-primary inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:px-6 sm:py-3 rounded-xl text-white font-semibold shadow-lg hover:shadow-xl transition-all text-sm sm:text-base">
            <i class="fas fa-list-check"></i>
            <span>กิจกรรมของฉัน</span>
        </a>
    </div>

    <!-- Info Alert -->
    <div class="glass rounded-xl p-4 border-l-4 border-emerald-500">
        <div class="flex items-start gap-3">
            <span class="text-2xl">💡</span>
            <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">วิธีลงทะเบียนกิจกรรม</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">คลิกปุ่ม "ลงทะเบียน" และกรอกรหัสกิจกรรมที่ได้รับจากครูผู้ดูแลกิจกรรม</p>
            </div>
        </div>
    </div>

    <!-- Events Container -->
    <div class="glass rounded-xl overflow-hidden">
        <!-- Mobile Card View -->
        <div id="events-cards" class="md:hidden p-4 space-y-3">
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                <p>กำลังโหลดข้อมูล...</p>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div id="events-table-container" class="hidden md:block overflow-x-auto">
            <table id="event-table" class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-emerald-500 to-green-600 text-white">
                        <th class="px-4 py-3 text-left text-sm font-semibold">ชื่อกิจกรรม</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">วันที่จัด</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">ชั่วโมง</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">ประเภท</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">จำนวน</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">สถานะ</th>
                    </tr>
                </thead>
                <tbody id="event-table-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <p>กำลังโหลดข้อมูล...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function getCategoryColor(category) {
    const colors = {
        'จิตอาสา': { bg: 'bg-purple-100 dark:bg-purple-900/30', text: 'text-purple-700 dark:text-purple-300', border: 'border-purple-200 dark:border-purple-700' },
        'วิชาการ': { bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-700 dark:text-blue-300', border: 'border-blue-200 dark:border-blue-700' },
        'กีฬา': { bg: 'bg-green-100 dark:bg-green-900/30', text: 'text-green-700 dark:text-green-300', border: 'border-green-200 dark:border-green-700' },
        'ศิลปะ': { bg: 'bg-pink-100 dark:bg-pink-900/30', text: 'text-pink-700 dark:text-pink-300', border: 'border-pink-200 dark:border-pink-700' },
    };
    return colors[category] || { bg: 'bg-gray-100 dark:bg-gray-800', text: 'text-gray-700 dark:text-gray-300', border: 'border-gray-200 dark:border-gray-700' };
}

function formatThaiDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('th-TH', {
        year: 'numeric', month: 'long', day: 'numeric'
    });
}

function renderMobileCards(events, registeredIds) {
    const container = document.getElementById('events-cards');
    
    if (events.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">ไม่พบกิจกรรมที่เปิดลงทะเบียน</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = events.map(ev => {
        const color = getCategoryColor(ev.category);
        const thaiDate = formatThaiDate(ev.event_date);
        const registered = registeredIds.includes(parseInt(ev.id, 10));
        
        return `
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border ${color.border} card-hover">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 dark:text-white truncate">${ev.title}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <i class="fas fa-calendar-alt mr-1"></i>${thaiDate}
                        </p>
                    </div>
                    <span class="inline-block px-2 py-1 rounded-lg text-xs font-medium ${color.bg} ${color.text}">${ev.category}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <span><i class="fas fa-clock mr-1"></i>${ev.hours} ชม.</span>
                        <span><i class="fas fa-users mr-1"></i>${ev.max_students} คน</span>
                    </div>
                    ${registered 
                        ? `<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 font-semibold text-sm">
                            <i class="fas fa-check-circle"></i>ลงทะเบียนแล้ว
                           </span>`
                        : `<button class="register-btn inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold text-sm shadow hover:shadow-lg transition-all active:scale-95" data-id="${ev.id}">
                            <i class="fas fa-plus"></i>ลงทะเบียน
                           </button>`
                    }
                </div>
            </div>
        `;
    }).join('');
    
    bindRegisterButtons();
}

function renderDesktopTable(events, registeredIds) {
    const tbody = document.getElementById('event-table-body');
    
    // Destroy existing DataTable
    if ($.fn.DataTable.isDataTable('#event-table')) {
        $('#event-table').DataTable().clear().destroy();
    }
    
    if (events.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-4 py-12 text-center">
                    <div class="text-5xl mb-4">📭</div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">ไม่พบกิจกรรมที่เปิดลงทะเบียน</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = events.map(ev => {
        const color = getCategoryColor(ev.category);
        const thaiDate = formatThaiDate(ev.event_date);
        const registered = registeredIds.includes(parseInt(ev.id, 10));
        
        return `
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">${ev.title}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">${thaiDate}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-block px-2 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 font-semibold">${ev.hours}</span>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-block px-2 py-1 rounded-lg text-xs font-medium ${color.bg} ${color.text}">${ev.category}</span>
                </td>
                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">${ev.max_students}</td>
                <td class="px-4 py-3 text-center">
                    ${registered 
                        ? `<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 font-semibold text-sm">
                            <i class="fas fa-check-circle"></i>ลงทะเบียนแล้ว
                           </span>`
                        : `<button class="register-btn inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold text-sm shadow hover:shadow-lg transition-all active:scale-95" data-id="${ev.id}">
                            <i class="fas fa-plus"></i>ลงทะเบียน
                           </button>`
                    }
                </td>
            </tr>
        `;
    }).join('');
    
    // Initialize DataTable
    $('#event-table').DataTable({
        destroy: true,
        pageLength: 10,
        language: {
            search: "ค้นหา:",
            lengthMenu: "แสดง _MENU_ รายการ",
            info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
            paginate: { first: "หน้าแรก", last: "หน้าสุดท้าย", next: "ถัดไป", previous: "ก่อนหน้า" },
            zeroRecords: "ไม่พบข้อมูล",
            infoEmpty: "ไม่มีข้อมูลให้แสดง",
            infoFiltered: "(กรองจาก _MAX_ รายการทั้งหมด)"
        },
        dom: '<"flex flex-wrap items-center justify-between gap-4 p-4"lf>rt<"flex flex-wrap items-center justify-between gap-4 p-4 border-t border-gray-200 dark:border-gray-700"ip>'
    });
    
    bindRegisterButtons();
}

function bindRegisterButtons() {
    document.querySelectorAll('.register-btn').forEach(btn => {
        btn.onclick = function() {
            const eventId = this.dataset.id;
            
            Swal.fire({
                title: 'กรอกรหัสกิจกรรม',
                input: 'text',
                inputLabel: 'กรุณากรอก code สำหรับกิจกรรมนี้',
                inputPlaceholder: 'กรอกรหัสกิจกรรมที่ได้รับ',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                inputValidator: (value) => {
                    if (!value) {
                        return 'กรุณากรอกรหัสกิจกรรม';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const code = result.value.trim();
                    
                    // Show loading
                    Swal.fire({
                        title: 'กำลังลงทะเบียน...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    fetch('../controllers/EventController.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'register_activity',
                            activity_id: eventId,
                            code: code
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
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์',
                            confirmButtonColor: '#ef4444'
                        });
                    });
                }
            });
        };
    });
}

async function loadEvents() {
    try {
        // Fetch all events
        const eventsRes = await fetch('../controllers/EventController.php');
        const eventsData = await eventsRes.json();
        
        if (!eventsData.success || !Array.isArray(eventsData.events)) {
            renderMobileCards([], []);
            renderDesktopTable([], []);
            return;
        }
        
        // Fetch codes for each event (to filter open registrations)
        const fetchCodes = eventsData.events.map(ev =>
            fetch(`../controllers/EventController.php?activity_id=${ev.id}`)
                .then(res => res.json())
                .then(codeRes => ({
                    ...ev,
                    code: codeRes.code
                }))
        );
        
        const eventsWithCode = await Promise.all(fetchCodes);
        
        // Filter only events with codes (open for registration)
        const filtered = eventsWithCode.filter(ev => ev.code);
        
        if (filtered.length === 0) {
            renderMobileCards([], []);
            renderDesktopTable([], []);
            return;
        }
        
        // Fetch registered events
        const regRes = await fetch('../controllers/EventController.php?student_registered=true');
        const regData = await regRes.json();
        
        let registeredIds = [];
        if (Array.isArray(regData.registered)) {
            registeredIds = regData.registered.map(id => parseInt(id, 10));
        }
        
        // Render both views
        renderMobileCards(filtered, registeredIds);
        renderDesktopTable(filtered, registeredIds);
        
    } catch (error) {
        console.error('Error loading events:', error);
        Swal.fire('ผิดพลาด', 'ไม่สามารถโหลดข้อมูลกิจกรรมได้', 'error');
    }
}

document.addEventListener('DOMContentLoaded', loadEvents);
</script>
