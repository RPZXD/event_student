<!-- Event List Page - รายชื่อผู้ลงทะเบียน -->
<div class="space-y-4 sm:space-y-6 lg:space-y-8">
    
    <!-- Header Section -->
    <div class="glass rounded-xl sm:rounded-2xl p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl text-white text-xl shadow-lg">
                    👥
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">รายชื่อผู้ลงทะเบียน</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">ดูผู้เข้าร่วมกิจกรรมของคุณ</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Selector -->
    <div class="glass rounded-xl sm:rounded-2xl p-4 sm:p-6">
        <label for="activity-select" class="block mb-3 font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
            <span class="text-lg">📋</span> เลือกกิจกรรม
        </label>
        <select id="activity-select" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-slate-700 text-gray-900 dark:text-white transition-all text-center font-medium">
            <option value="">-- เลือกกิจกรรมที่ต้องการดู --</option>
        </select>
    </div>

    <!-- Participants Table Section -->
    <div class="glass rounded-xl sm:rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-purple-500 to-violet-600 rounded-lg text-white text-lg">
                        📊
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">ตารางผู้ลงทะเบียน</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400" id="participant-count">เลือกกิจกรรมเพื่อดูรายชื่อ</p>
                    </div>
                </div>
                <button id="export-btn" class="hidden btn-success px-4 py-2 rounded-lg text-white font-medium flex items-center gap-2">
                    <i class="fas fa-file-excel"></i>
                    <span class="hidden sm:inline">ส่งออก Excel</span>
                </button>
            </div>
        </div>
        
        <div class="p-4 sm:p-6">
            <!-- Empty State -->
            <div id="participantEmpty" class="text-center py-8">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500 dark:text-gray-400">เลือกกิจกรรมเพื่อดูรายชื่อผู้ลงทะเบียน</p>
            </div>
            
            <!-- Loading State -->
            <div id="participantLoading" class="hidden text-center py-8">
                <div class="inline-block w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-4 text-gray-500 dark:text-gray-400">กำลังโหลดข้อมูล...</p>
            </div>
            
            <!-- No Participants State -->
            <div id="noParticipants" class="hidden text-center py-8">
                <div class="text-5xl mb-4">👤</div>
                <p class="text-gray-500 dark:text-gray-400">ยังไม่มีผู้ลงทะเบียนกิจกรรมนี้</p>
            </div>
            
            <!-- Mobile Cards View -->
            <div id="participantCardsWrapper" class="hidden md:hidden space-y-3">
                <!-- Cards will be inserted here for mobile -->
            </div>
            
            <!-- Desktop Table View -->
            <div class="overflow-x-auto hidden" id="participantTableWrapper">
                <table id="participant-table" class="w-full text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-purple-100 to-violet-100 dark:from-purple-900/30 dark:to-violet-900/30">
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 dark:text-gray-300 rounded-l-lg w-16">🔢 #</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-700 dark:text-gray-300">👤 ชื่อ-นามสกุล</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 dark:text-gray-300">🏛️ ชั้น</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 dark:text-gray-300 rounded-r-lg">📅 วันที่ลงทะเบียน</th>
                        </tr>
                    </thead>
                    <tbody id="participant-table-body" class="divide-y divide-gray-100 dark:divide-gray-700">
                        <!-- Data will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const activitySelect = document.getElementById('activity-select');
    const participantTableBody = document.getElementById('participant-table-body');
    const participantCardsWrapper = document.getElementById('participantCardsWrapper');
    const exportBtn = document.getElementById('export-btn');
    let currentParticipants = [];
    let currentActivityName = '';

    // Load teacher's activities
    fetch('../controllers/ActivityController.php?action=teacher_activities')
        .then(res => res.json())
        .then(data => {
            if (data.success && Array.isArray(data.activities)) {
                data.activities.forEach(act => {
                    const opt = document.createElement('option');
                    opt.value = act.id;
                    opt.textContent = `${act.title} (${act.current_students || 0} คน)`;
                    opt.dataset.title = act.title;
                    activitySelect.appendChild(opt);
                });
            }
        });

    // Show states
    function showState(state) {
        document.getElementById('participantEmpty').classList.add('hidden');
        document.getElementById('participantLoading').classList.add('hidden');
        document.getElementById('noParticipants').classList.add('hidden');
        document.getElementById('participantCardsWrapper').classList.add('hidden');
        document.getElementById('participantTableWrapper').classList.add('hidden');
        exportBtn.classList.add('hidden');
        
        if (state === 'empty') {
            document.getElementById('participantEmpty').classList.remove('hidden');
        } else if (state === 'loading') {
            document.getElementById('participantLoading').classList.remove('hidden');
        } else if (state === 'no-participants') {
            document.getElementById('noParticipants').classList.remove('hidden');
        } else if (state === 'data') {
            document.getElementById('participantCardsWrapper').classList.remove('hidden');
            document.getElementById('participantTableWrapper').classList.remove('hidden');
            exportBtn.classList.remove('hidden');
        }
    }

    // On activity change
    activitySelect.addEventListener('change', function() {
        const activityId = this.value;
        const selectedOption = this.options[this.selectedIndex];
        currentActivityName = selectedOption.dataset?.title || 'กิจกรรม';
        
        if (!activityId) {
            showState('empty');
            document.getElementById('participant-count').textContent = 'เลือกกิจกรรมเพื่อดูรายชื่อ';
            return;
        }
        
        showState('loading');
        
        fetch(`../controllers/ActivityController.php?action=participants&activity_id=${activityId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.participants)) {
                    currentParticipants = data.participants;
                    
                    if (data.participants.length === 0) {
                        showState('no-participants');
                        document.getElementById('participant-count').textContent = 'ไม่มีผู้ลงทะเบียน';
                        return;
                    }
                    
                    // Clear previous data
                    participantTableBody.innerHTML = '';
                    participantCardsWrapper.innerHTML = '';
                    
                    // Populate data
                    data.participants.forEach((p, idx) => {
                        const thaiDate = new Date(p.checked_in_at).toLocaleDateString('th-TH', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        
                        // Mobile Card
                        participantCardsWrapper.innerHTML += `
                            <div class="bg-white dark:bg-slate-700 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full text-white font-bold">
                                        ${idx + 1}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 dark:text-white truncate">${p.Stu_name}</h4>
                                        <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                            <span>🏛️ ${p.Stu_class}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-600 text-xs text-gray-500 dark:text-gray-400">
                                    📅 ${thaiDate}
                                </div>
                            </div>
                        `;
                        
                        // Desktop Table Row
                        participantTableBody.innerHTML += `
                            <tr class="hover:bg-purple-50 dark:hover:bg-purple-900/10 transition-colors">
                                <td class="py-3 px-4 text-center">
                                    <span class="w-8 h-8 inline-flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold rounded-full text-sm">
                                        ${idx + 1}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">${p.Stu_name}</td>
                                <td class="py-3 px-4 text-center text-gray-600 dark:text-gray-400">${p.Stu_class}</td>
                                <td class="py-3 px-4 text-center text-gray-600 dark:text-gray-400">${thaiDate}</td>
                            </tr>
                        `;
                    });
                    
                    showState('data');
                    document.getElementById('participant-count').textContent = `พบ ${data.participants.length} คน`;
                    
                    // Initialize or refresh DataTable
                    if ($.fn.dataTable.isDataTable('#participant-table')) {
                        $('#participant-table').DataTable().destroy();
                    }
                    $('#participant-table').DataTable({
                        order: [[0, 'asc']],
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
                } else {
                    showState('no-participants');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showState('no-participants');
            });
    });

    // Export to Excel
    exportBtn.addEventListener('click', function() {
        if (currentParticipants.length === 0) {
            Swal.fire('ไม่มีข้อมูล', 'ไม่มีข้อมูลสำหรับส่งออก', 'warning');
            return;
        }
        
        const data = currentParticipants.map((p, idx) => ({
            'ลำดับ': idx + 1,
            'ชื่อ-นามสกุล': p.Stu_name,
            'ชั้น': p.Stu_class,
            'วันที่ลงทะเบียน': p.checked_in_at
        }));
        
        const ws = XLSX.utils.json_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'รายชื่อผู้ลงทะเบียน');
        XLSX.writeFile(wb, `รายชื่อ-${currentActivityName}.xlsx`);
        
        Swal.fire({
            icon: 'success',
            title: 'ส่งออกสำเร็จ',
            text: 'ดาวน์โหลดไฟล์ Excel เรียบร้อยแล้ว',
            timer: 2000,
            showConfirmButton: false
        });
    });
});
</script>
