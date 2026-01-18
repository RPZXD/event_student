<!-- Create Event Page - สร้างกิจกรรม -->
<div class="space-y-4 sm:space-y-6 lg:space-y-8">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl text-white text-xl shadow-lg">
                📝
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">จัดการกิจกรรม</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">สร้างและจัดการกิจกรรมของคุณ</p>
            </div>
        </div>
        <button id="create-event-btn" class="btn-primary px-6 py-3 rounded-xl text-white font-semibold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transition-all">
            <i class="fas fa-plus-circle"></i>
            <span>สร้างกิจกรรมใหม่</span>
        </button>
    </div>

    <!-- Events Table Section -->
    <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg text-white text-lg">
                    📋
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">ตารางกิจกรรม</h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">รายการกิจกรรมทั้งหมดที่สร้าง</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 sm:p-6">
            <!-- Loading State -->
            <div id="eventLoading" class="text-center py-8">
                <div class="inline-block w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-4 text-gray-500 dark:text-gray-400">กำลังโหลดกิจกรรม...</p>
            </div>
            
            <!-- Empty State -->
            <div id="eventEmpty" class="hidden text-center py-8">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500 dark:text-gray-400">ยังไม่มีกิจกรรม</p>
                <button onclick="document.getElementById('create-event-btn').click()" class="mt-4 btn-primary px-6 py-2 rounded-lg text-white font-medium">
                    สร้างกิจกรรมแรก
                </button>
            </div>
            
            <!-- Mobile Cards View -->
            <div id="eventCardsWrapper" class="hidden md:hidden space-y-3">
                <!-- Cards will be inserted here for mobile -->
            </div>
            
            <!-- Desktop Table View -->
            <div class="overflow-x-auto hidden md:block" id="eventTableWrapper">
                <table id="event-table" class="w-full text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30">
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 dark:text-gray-300 rounded-l-lg">🎯 ชื่อกิจกรรม</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 dark:text-gray-300">📅 วันที่</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 dark:text-gray-300">⏰ ชั่วโมง</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 dark:text-gray-300">👥 จำนวน</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 dark:text-gray-300 rounded-r-lg">🔧 จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="event-table-body" class="divide-y divide-gray-100 dark:divide-gray-700">
                        <!-- Data will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Event Modal -->
<div id="event-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm modal-backdrop" onclick="closeEventModal()"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto animate-slide-up">
        <button onclick="closeEventModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors">
            <i class="fas fa-times text-lg"></i>
        </button>
        
        <div class="p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl text-white text-xl">
                    📝
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">สร้างกิจกรรมใหม่</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">กรอกข้อมูลกิจกรรม</p>
                </div>
            </div>
            
            <form id="event-form" class="space-y-4">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">🎯 ชื่อกิจกรรม <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all" placeholder="เช่น กิจกรรมจิตอาสา">
                </div>
                
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">📝 รายละเอียด</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all resize-none" placeholder="รายละเอียดกิจกรรม"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">📅 วันที่ <span class="text-red-500">*</span></label>
                        <input type="date" name="event_date" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">⏰ ชั่วโมง <span class="text-red-500">*</span></label>
                        <input type="number" name="hours" min="1" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all" placeholder="2">
                    </div>
                </div>
                
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">🏷️ ประเภทกิจกรรม <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all">
                        <option value="">เลือกประเภท</option>
                        <option value="จิตอาสา">จิตอาสา</option>
                        <option value="กีฬา">กีฬา</option>
                        <option value="อบรม">อบรม</option>
                        <option value="อื่นๆ">อื่นๆ</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">👥 จำนวนสูงสุด <span class="text-red-500">*</span></label>
                        <input type="number" name="max_students" min="1" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all" placeholder="50">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">📆 วันหมดอายุ</label>
                        <input type="date" name="expire_date" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all">
                    </div>
                </div>
                
                <button type="submit" class="w-full btn-success py-3 rounded-xl text-white font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-rocket"></i>
                    <span>สร้างกิจกรรม</span>
                </button>
            </form>
            
            <!-- Success Result -->
            <div id="event-result" class="hidden text-center">
                <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center bg-gradient-to-br from-green-400 to-emerald-500 rounded-full text-white text-4xl">
                    🎉
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">สร้างกิจกรรมสำเร็จ!</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">รหัสกิจกรรม: <span id="event-code" class="font-mono text-indigo-600 dark:text-indigo-400"></span></p>
                <div id="event-qrcode" class="flex justify-center mb-4"></div>
                <div class="flex gap-3 justify-center">
                    <button id="download-qrcode" class="btn-success px-4 py-2 rounded-lg text-white font-medium flex items-center gap-2">
                        <i class="fas fa-download"></i>
                        <span>ดาวน์โหลด QR</span>
                    </button>
                    <button onclick="closeEventModal()" class="btn-primary px-4 py-2 rounded-lg text-white font-medium">ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detail-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm modal-backdrop" onclick="closeDetailModal()"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto animate-slide-up">
        <button onclick="closeDetailModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors">
            <i class="fas fa-times text-lg"></i>
        </button>
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <span>🔎</span> รายละเอียดกิจกรรม
            </h2>
            <div id="detail-content" class="space-y-3"></div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm modal-backdrop" onclick="closeEditModal()"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto animate-slide-up">
        <button onclick="closeEditModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors">
            <i class="fas fa-times text-lg"></i>
        </button>
        <div class="p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl text-white text-xl">
                    ✏️
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">แก้ไขกิจกรรม</h2>
            </div>
            
            <form id="edit-form" class="space-y-4">
                <input type="hidden" name="id" id="edit-id">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">🎯 ชื่อกิจกรรม</label>
                    <input type="text" name="title" id="edit-title" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all">
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">📝 รายละเอียด</label>
                    <textarea name="description" id="edit-description" rows="2" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">📅 วันที่</label>
                        <input type="date" name="event_date" id="edit-event_date" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">⏰ ชั่วโมง</label>
                        <input type="number" name="hours" id="edit-hours" min="1" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">🏷️ ประเภท</label>
                        <input type="text" name="category" id="edit-category" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">👥 จำนวน</label>
                        <input type="number" name="max_students" id="edit-max_students" min="1" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all">
                    </div>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 py-3 rounded-xl text-white font-semibold flex items-center justify-center gap-2 transition-all">
                    <i class="fas fa-save"></i>
                    <span>บันทึกการแก้ไข</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const currentUser = <?= json_encode($_SESSION['username'] ?? '') ?>;

// Modal functions
function openEventModal() {
    document.getElementById('event-modal').classList.remove('hidden');
    document.getElementById('event-form').classList.remove('hidden');
    document.getElementById('event-result').classList.add('hidden');
    document.getElementById('event-form').reset();
}

function closeEventModal() {
    document.getElementById('event-modal').classList.add('hidden');
}

function closeDetailModal() {
    document.getElementById('detail-modal').classList.add('hidden');
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}

// Open create modal
document.getElementById('create-event-btn').onclick = openEventModal;

// Load events
function loadEvents() {
    fetch('../controllers/EventController.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('eventLoading').classList.add('hidden');
            
            if (data.success && Array.isArray(data.events) && data.events.length > 0) {
                const tbody = document.getElementById('event-table-body');
                const cardsWrapper = document.getElementById('eventCardsWrapper');
                tbody.innerHTML = '';
                cardsWrapper.innerHTML = '';
                
                data.events.forEach(ev => {
                    const thaiDate = new Date(ev.event_date).toLocaleDateString('th-TH', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                    
                    const isOwner = ev.teacher_id == currentUser;
                    const current = ev.current_students || 0;
                    const max = ev.max_students || 0;
                    const percent = max > 0 ? Math.min((current / max) * 100, 100) : 0;
                    const isFull = max > 0 && current >= max;
                    
                    const progressBar = max > 0 ? `
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-1">
                            <div class="h-2 rounded-full transition-all ${isFull ? 'bg-red-500' : 'bg-indigo-500'}" style="width: ${percent}%;"></div>
                        </div>
                    ` : '';
                    
                    // Mobile Card
                    cardsWrapper.innerHTML += `
                        <div class="bg-white dark:bg-slate-700 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-600">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-semibold text-gray-900 dark:text-white">🎯 ${ev.title}</h4>
                                <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 rounded-full text-xs">
                                    ${ev.category || '-'}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-400 mb-3">
                                <div>📅 ${thaiDate}</div>
                                <div>⏰ ${ev.hours} ชม.</div>
                                <div class="col-span-2">👥 ${max > 0 ? current + '/' + max : 'ไม่จำกัด'}</div>
                            </div>
                            ${progressBar}
                            ${isOwner ? `
                            <div class="flex gap-2 mt-3 flex-wrap">
                                <button onclick="showDetail(${JSON.stringify(ev).replace(/"/g, '&quot;')})" class="flex-1 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${max > 0 ? `
                                <a href="qr_code_generate.php?activity_id=${ev.id}&title=${encodeURIComponent(ev.title)}" class="flex-1 px-3 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm rounded-lg transition-colors text-center">
                                    <i class="fas fa-qrcode"></i>
                                </a>
                                ` : ''}
                                <button onclick="editEvent(${JSON.stringify(ev).replace(/"/g, '&quot;')})" class="flex-1 px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteEvent(${ev.id})" class="flex-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            ` : ''}
                        </div>
                    `;
                    
                    // Desktop Table Row
                    tbody.innerHTML += `
                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-semibold text-gray-900 dark:text-white">🎯 ${ev.title}</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">${ev.category || '-'}</span>
                            </td>
                            <td class="py-3 px-4 text-center text-gray-600 dark:text-gray-400" data-order="${ev.event_date}">${thaiDate}</td>
                            <td class="py-3 px-4 text-center text-gray-600 dark:text-gray-400">${ev.hours} ชม.</td>
                            <td class="py-3 px-4 text-center">
                                <span class="text-gray-600 dark:text-gray-400">${max > 0 ? current + '/' + max : 'ไม่จำกัด'}</span>
                                ${progressBar}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex gap-1 justify-center flex-wrap">
                                    <button onclick='showDetail(${JSON.stringify(ev)})' class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors" title="รายละเอียด">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    ${isOwner ? `
                                        ${max > 0 ? `
                                        <a href="qr_code_generate.php?activity_id=${ev.id}&title=${encodeURIComponent(ev.title)}" class="p-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors" title="QR Codes">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                        ` : ''}
                                        <button onclick='editEvent(${JSON.stringify(ev)})' class="p-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors" title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteEvent(${ev.id})" class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors" title="ลบ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    ` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                document.getElementById('eventCardsWrapper').classList.remove('hidden');
                document.getElementById('eventCardsWrapper').classList.add('block', 'md:hidden');
                document.getElementById('eventTableWrapper').classList.remove('hidden');
                document.getElementById('eventTableWrapper').classList.add('hidden', 'md:block');
                
                // Initialize DataTable
                if (!$.fn.dataTable.isDataTable('#event-table')) {
                    $('#event-table').DataTable({
                        order: [[1, 'desc']],
                        responsive: true,
                        language: {
                            search: "ค้นหา:",
                            lengthMenu: "แสดง _MENU_ รายการ",
                            info: "แสดง _START_ - _END_ จาก _TOTAL_",
                            paginate: { first: "«", last: "»", next: "›", previous: "‹" },
                            zeroRecords: "ไม่พบข้อมูล",
                            infoEmpty: "ไม่มีข้อมูล",
                            infoFiltered: "(จาก _MAX_)"
                        }
                    });
                }
            } else {
                document.getElementById('eventEmpty').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('eventLoading').classList.add('hidden');
            document.getElementById('eventEmpty').classList.remove('hidden');
        });
}

// Show detail modal
function showDetail(ev) {
    const thaiDate = new Date(ev.event_date).toLocaleDateString('th-TH', {
        year: 'numeric', month: 'long', day: 'numeric'
    });
    document.getElementById('detail-content').innerHTML = `
        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"><strong>🎯 ชื่อ:</strong> ${ev.title}</div>
        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"><strong>📝 รายละเอียด:</strong> ${ev.description || '-'}</div>
        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"><strong>📅 วันที่:</strong> ${thaiDate}</div>
        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"><strong>⏰ ชั่วโมง:</strong> ${ev.hours} ชม.</div>
        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"><strong>🏷️ ประเภท:</strong> ${ev.category || '-'}</div>
        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"><strong>👥 จำนวน:</strong> ${ev.max_students ? (ev.current_students || 0) + '/' + ev.max_students : 'ไม่จำกัด'}</div>
        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"><strong>👩‍🏫 ผู้สร้าง:</strong> ${ev.teacher_name || ev.teacher_id}</div>
    `;
    document.getElementById('detail-modal').classList.remove('hidden');
}

// Edit event
function editEvent(ev) {
    document.getElementById('edit-id').value = ev.id;
    document.getElementById('edit-title').value = ev.title;
    document.getElementById('edit-description').value = ev.description || '';
    document.getElementById('edit-event_date').value = ev.event_date;
    document.getElementById('edit-hours').value = ev.hours;
    document.getElementById('edit-category').value = ev.category || '';
    document.getElementById('edit-max_students').value = ev.max_students || '';
    document.getElementById('edit-modal').classList.remove('hidden');
}

// Delete event
function deleteEvent(id) {
    Swal.fire({
        title: 'ลบกิจกรรม?',
        text: 'คุณต้องการลบกิจกรรมนี้หรือไม่',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#ef4444'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../controllers/EventController.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('สำเร็จ', 'ลบกิจกรรมเรียบร้อย', 'success');
                    location.reload();
                } else {
                    Swal.fire('ผิดพลาด', res.message || 'ไม่สามารถลบได้', 'error');
                }
            });
        }
    });
}

// Create form submit
document.getElementById('event-form').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = {};
    formData.forEach((v, k) => data[k] = v);
    
    fetch('../controllers/EventController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            document.getElementById('event-code').textContent = res.code;
            const qrDiv = document.getElementById('event-qrcode');
            qrDiv.innerHTML = '';
            if (res.code) {
                const qrUrl = 'https://eventstd.phichai.ac.th/checkin.php?code=' + encodeURIComponent(res.code);
                new QRCode(qrDiv, { text: qrUrl, width: 128, height: 128 });
            }
            document.getElementById('event-form').classList.add('hidden');
            document.getElementById('event-result').classList.remove('hidden');
            
            // Download QR
            document.getElementById('download-qrcode').onclick = function() {
                const qrImg = qrDiv.querySelector('img') || qrDiv.querySelector('canvas');
                if (qrImg) {
                    const link = document.createElement('a');
                    link.href = qrImg.tagName === 'CANVAS' ? qrImg.toDataURL('image/png') : qrImg.src;
                    link.download = (res.code || 'qrcode') + '.png';
                    link.click();
                }
            };
        } else {
            Swal.fire('ผิดพลาด', res.message || 'ไม่สามารถสร้างกิจกรรมได้', 'error');
        }
    });
};

// Edit form submit
document.getElementById('edit-form').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = {};
    formData.forEach((v, k) => data[k] = v);
    
    fetch('../controllers/EventController.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            Swal.fire('สำเร็จ', 'บันทึกการแก้ไขเรียบร้อย', 'success');
            closeEditModal();
            location.reload();
        } else {
            Swal.fire('ผิดพลาด', res.message || 'ไม่สามารถแก้ไขได้', 'error');
        }
    });
};

// Init
$(document).ready(function() {
    loadEvents();
});
</script>
