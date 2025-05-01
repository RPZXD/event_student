<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// เช็ค session และ role
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'ครู') {
    header('Location: ../login.php');
    exit;
}
// Read configuration from JSON file
$config = json_decode(file_get_contents('../config.json'), true);
$global = $config['global'];

require_once('header.php');

?>
<body class="hold-transition sidebar-mini layout-fixed light-mode">
<div class="wrapper">

    <?php require_once('wrapper.php');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h5 class="m-0">รายการกิจกรรม</h5>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">

            <div class="card">
                <div class="card-header bg-blue-600 text-white font-semibold text-lg">
                    รายการกิจกรรม
                </div>
                <div class="card-body">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                        <button id="create-event-btn" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow flex items-center gap-2" >
                            <span class="text-base">➕</span>
                            <span>สร้างกิจกรรม</span>
                        </button>
                    </div>
                    <!-- Modal สร้างกิจกรรม -->
                    <div id="event-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                            <button id="close-modal" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>
                            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">📝 สร้างกิจกรรมใหม่</h2>
                            <form id="event-form" autocomplete="off">
                                <div class="mb-3">
                                    <label class="block mb-1 font-semibold">🎯 ชื่อกิจกรรม</label>
                                    <input type="text" name="title" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="เช่น กิจกรรมจิตอาสา">
                                </div>
                                <div class="mb-3">
                                    <label class="block mb-1 font-semibold">📝 รายละเอียด</label>
                                    <textarea name="description" rows="2" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="รายละเอียดกิจกรรม"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="block mb-1 font-semibold">📅 วันที่จัดกิจกรรม</label>
                                    <input type="date" name="event_date" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <div class="mb-3">
                                    <label class="block mb-1 font-semibold">⏰ จำนวนชั่วโมง</label>
                                    <input type="number" name="hours" min="1" step="1" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="เช่น 2">
                                </div>
                                <div class="mb-3">
                                    <label class="block mb-1 font-semibold">🏷️ ประเภทกิจกรรม</label>
                                    <select name="category" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <option value="">เลือกประเภท</option>
                                        <option value="จิตอาสา">จิตอาสา</option>
                                        <option value="กีฬา">กีฬา</option>
                                        <option value="อบรม">อบรม</option>
                                        <option value="อื่นๆ">อื่นๆ</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block mb-1 font-semibold">👥 จำนวนสูงสุดที่ลงทะเบียนได้</label>
                                    <input type="number" name="max_students" min="1" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="เช่น 50">
                                </div>
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded flex items-center justify-center gap-2">
                                    <span>🚀</span>
                                    <span>สร้างกิจกรรม</span>
                                </button>
                            </form>
                            <!-- แสดงผลลัพธ์หลังสร้าง -->
                            <div id="event-result" class="mt-6 hidden text-center">
                                <div class="mb-2 text-lg font-bold flex items-center justify-center gap-2">🎉 สร้างกิจกรรมสำเร็จ!</div>
                                <div class="mb-2">รหัสกิจกรรม: <span id="event-code" class="font-mono text-blue-600"></span></div>
                                <div class="flex flex-col items-center gap-2">
                                    <span>🔗 QR Code สำหรับกิจกรรม</span>
                                    <div id="event-qrcode" class="mx-auto"></div>
                                    <button id="download-qrcode" class="mt-2 bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2">
                                        <span>⬇️</span>
                                        <span>ดาวน์โหลด QR CODE</span>
                                    </button>
                                </div>
                                <button id="close-result" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">ปิด</button>
                            </div>
                        </div>
                    </div>
                    <!-- จบ Modal -->
                </div>
            </div>



            <div class="card">  
            <div class="card-body">
                <p class="text-gray-700 mb-4 text-lg font-semibold flex items-center gap-2">📋 ตารางกิจกรรม</p>
                <div class="overflow-x-auto">
                    <!-- DataTables CSS -->
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
                    <table id="event-table" class="min-w-full bg-white border border-gray-200 rounded shadow display">
                        <thead>
                            <tr class="bg-blue-100 text-blue-900">
                                <th class="px-4 py-2 text-left">🎯 ชื่อกิจกรรม</th>
                                <th class="px-4 py-2 text-left">📅 วันที่จัด</th>
                                <th class="px-4 py-2 text-left">⏰ ชั่วโมง</th>
                                <th class="px-4 py-2 text-left">👩‍🏫 ผู้สร้าง</th>
                                <th class="px-4 py-2">จำนวน</th>
                                <th class="px-4 py-2 text-left">🔎 รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody id="event-table-body">
                            <!-- ข้อมูลกิจกรรมจะถูกเติมโดย JS -->
                        </tbody>
                    </table>
                    <!-- Modal รายละเอียดกิจกรรม -->
                    <div id="detail-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                            <button id="close-detail-modal" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>
                            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">🔎 รายละเอียดกิจกรรม</h2>
                            <div id="detail-content" class="text-left"></div>
                        </div>
                    </div>
                    <!-- Modal QR Code -->
                    <div id="qrcode-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-xs p-6 relative flex flex-col items-center">
                            <button id="close-qrcode-modal" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>
                            <div class="mb-2 text-lg font-bold flex items-center gap-2">🔗 QR Code กิจกรรม</div>
                            <div id="modal-qrcode" class="mb-2"></div>
                            <button id="modal-download-qrcode" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2">
                                <span>⬇️</span>
                                <span>ดาวน์โหลด QR CODE</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('../footer.php');?>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- QRCode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Define currentUser from PHP session
    const currentUser = <?php echo json_encode($_SESSION['username']); ?>;

    const modal = document.getElementById('event-modal');
    const openBtn = document.getElementById('create-event-btn');
    const closeBtn = document.getElementById('close-modal');
    const form = document.getElementById('event-form');
    const resultDiv = document.getElementById('event-result');
    const eventCodeSpan = document.getElementById('event-code');
    const eventQrDiv = document.getElementById('event-qrcode');
    const closeResultBtn = document.getElementById('close-result');
    const downloadBtn = document.getElementById('download-qrcode');

    openBtn.onclick = () => {
        modal.classList.remove('hidden');
        form.classList.remove('hidden');
        resultDiv.classList.add('hidden');
        form.reset();
    };
    closeBtn.onclick = () => modal.classList.add('hidden');
    if (closeResultBtn) closeResultBtn.onclick = () => modal.classList.add('hidden');
    console.log(<?php echo json_encode($_SESSION['username']); ?>);
    // โหลดข้อมูลกิจกรรมจาก controller
    function loadEvents() {
        fetch('../controllers/EventController.php')
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('event-table-body');
                tbody.innerHTML = '';

                if (data.success && Array.isArray(data.events)) {
                    data.events.forEach(ev => {
                        let qrcodeBtn = '';
                        let editBtn = '';
                        let deleteBtn = '';
                        const status = ev.max_students
                                        ? `${ev.current_students || 0} / ${ev.max_students}`
                                        : "ไม่จำกัด";
                        let progressBar = '';
                            if (ev.max_students > 0) {
                                const current = ev.current_students || 0;
                                const percent = Math.min((current / ev.max_students) * 100, 100); // ป้องกันเกิน 100%
                                progressBar = `
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                        <div class="bg-blue-600 h-2.5 rounded-full transition-all" style="width: ${percent}%;"></div>
                                    </div>
                                `;
                            }
                        const thaiDate = new Date(ev.event_date).toLocaleDateString('th-TH', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        if (ev.teacher_id == currentUser) {
                            qrcodeBtn = `<button class="show-qrcode-btn inline-flex items-center gap-1 bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded shadow transition" 
                                data-id="${ev.id}" data-title="${ev.title}">
                                <span>🔗</span><span>QR Code</span>
                            </button>`;
                            editBtn = `<button class="edit-event-btn inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow transition"
                                data-id="${ev.id}" 
                                data-title="${ev.title}" 
                                data-description="${ev.description || ''}" 
                                data-event_date="${thaiDate}" 
                                data-hours="${ev.hours}" 
                                data-category="${ev.category || ''}" 
                                data-max_students="${ev.max_students || ''}">
                                <span>✏️</span><span>แก้ไข</span>
                            </button>`;
                            deleteBtn = `<button class="delete-event-btn inline-flex items-center gap-1 bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded shadow transition"
                                data-id="${ev.id}">
                                <span>🗑️</span><span>ลบ</span>
                            </button>`;
                        } else {
                            qrcodeBtn = `<button class="inline-flex items-center gap-1 bg-gray-300 text-gray-500 px-3 py-1 rounded shadow cursor-not-allowed" disabled>
                                <span>🔗</span><span>QR Code</span>
                            </button>`;
                        }

                        tbody.innerHTML += `
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-4 py-2 flex items-center gap-2">🎯 <span>${ev.title}</span></td>
                                <td class="px-4 py-2">📅 ${thaiDate}</td>
                                <td class="px-4 py-2">⏰ ${ev.hours} ชั่วโมง</td>
                                <td class="px-4 py-2 flex items-center gap-2">👩‍🏫 <span>${ev.teacher_name || ev.teacher_id}</span></td>
                                <td class="px-4 py-2">👥 ${status}
                                                    <span>${progressBar}</span></td>
                                <td class="px-4 py-2 flex gap-2">
                                    <button class="show-detail-btn inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded shadow transition"
                                        data-title="${ev.title}" data-description="${ev.description || ''}" data-date="${ev.event_date}" data-hours="${ev.hours}" data-category="${ev.category || ''}" data-max="${ev.max_students || ''}" data-teacher="${ev.teacher_name || ev.teacher_id}">
                                        <span>🔎</span>
                                        <span>รายละเอียด</span>
                                    </button>
                                    ${qrcodeBtn}
                                    ${editBtn}
                                    ${deleteBtn}
                                </td>
                            </tr>
                        `;
                    });

                    // Bind รายละเอียด
                    document.querySelectorAll('.show-detail-btn').forEach(btn => {
                        btn.onclick = function() {
                            const detailModal = document.getElementById('detail-modal');
                            const detailContent = document.getElementById('detail-content');
                            const thaiDate = new Date(btn.dataset.date).toLocaleDateString('th-TH', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });
                            detailContent.innerHTML = `
                                <div class="mb-2"><span class="font-bold">🎯 ชื่อกิจกรรม:</span> ${btn.dataset.title}</div>
                                <div class="mb-2"><span class="font-bold">📝 รายละเอียด:</span> ${btn.dataset.description || '-'}</div>
                                <div class="mb-2"><span class="font-bold">📅 วันที่จัด:</span> ${thaiDate}</div>
                                <div class="mb-2"><span class="font-bold">⏰ ชั่วโมง:</span> ${btn.dataset.hours} ชั่วโมง</div>
                                <div class="mb-2"><span class="font-bold">🏷️ ประเภท:</span> ${btn.dataset.category || '-'}</div>
                                <div class="mb-2"><span class="font-bold">👥 จำนวนสูงสุด:</span> ${btn.dataset.max || '-'} คน</div>
                                <div class="mb-2"><span class="font-bold">👩‍🏫 ผู้สร้าง:</span> ${btn.dataset.teacher}</div>
                            `;
                            detailModal.classList.remove('hidden');
                        };
                    });
                    document.getElementById('close-detail-modal').onclick = function() {
                        document.getElementById('detail-modal').classList.add('hidden');
                    };

                    // Bind QR Code
                    document.querySelectorAll('.show-qrcode-btn').forEach(btn => {
                        btn.onclick = function() {
                            const qrcodeModal = document.getElementById('qrcode-modal');
                            const modalQrDiv = document.getElementById('modal-qrcode');
                            modalQrDiv.innerHTML = '';
                            // ดึง code จาก backend ด้วย activity_id
                            console.log(btn.dataset.id);
                            fetch('../controllers/EventController.php?activity_id=' + btn.dataset.id)
                                .then(res => res.json())
                                .then(res => {
                                    let code = res.code || '';
                                    if (!code) {
                                        modalQrDiv.innerHTML = '<div class="text-red-500">ไม่พบรหัสกิจกรรม</div>';
                                    } else {
                                        new QRCode(modalQrDiv, {
                                            text: code,
                                            width: 128,
                                            height: 128
                                        });
                                        // set download
                                        document.getElementById('modal-download-qrcode').onclick = function() {
                                            const qrImg = modalQrDiv.querySelector('img');
                                            if (qrImg) {
                                                const link = document.createElement('a');
                                                link.href = qrImg.src;
                                                link.download = (code || 'qrcode') + '.png';
                                                link.click();
                                            } else {
                                                const qrCanvas = modalQrDiv.querySelector('canvas');
                                                if (qrCanvas) {
                                                    const link = document.createElement('a');
                                                    link.href = qrCanvas.toDataURL('image/png');
                                                    link.download = (code || 'qrcode') + '.png';
                                                    link.click();
                                                }
                                            }
                                        };
                                    }
                                    qrcodeModal.classList.remove('hidden');
                                });
                        };
                    });
                    document.getElementById('close-qrcode-modal').onclick = function() {
                        document.getElementById('qrcode-modal').classList.add('hidden');
                    };

                    // Modal แก้ไขกิจกรรม
                    if (!document.getElementById('edit-event-modal')) {
                        const editModalHtml = `
                        <div id="edit-event-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                                <button id="close-edit-modal" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>
                                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">✏️ แก้ไขกิจกรรม</h2>
                                <form id="edit-event-form" autocomplete="off">
                                    <input type="hidden" name="id" id="edit-id">
                                    <div class="mb-3">
                                        <label class="block mb-1 font-semibold">🎯 ชื่อกิจกรรม</label>
                                        <input type="text" name="title" id="edit-title" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block mb-1 font-semibold">📝 รายละเอียด</label>
                                        <textarea name="description" id="edit-description" rows="2" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block mb-1 font-semibold">📅 วันที่จัดกิจกรรม</label>
                                        <input type="date" name="event_date" id="edit-event_date" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block mb-1 font-semibold">⏰ จำนวนชั่วโมง</label>
                                        <input type="number" name="hours" id="edit-hours" min="1" step="1" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block mb-1 font-semibold">🏷️ ประเภทกิจกรรม</label>
                                        <input type="text" name="category" id="edit-category" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block mb-1 font-semibold">👥 จำนวนสูงสุดที่ลงทะเบียนได้</label>
                                        <input type="number" name="max_students" id="edit-max_students" min="1" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 rounded flex items-center justify-center gap-2">
                                        <span>💾</span>
                                        <span>บันทึกการแก้ไข</span>
                                    </button>
                                </form>
                            </div>
                        </div>`;
                        document.body.insertAdjacentHTML('beforeend', editModalHtml);
                    }

                    // Bind แก้ไขกิจกรรม
                    document.querySelectorAll('.edit-event-btn').forEach(btn => {
                        btn.onclick = function() {
                            // ใส่ข้อมูลเดิมลงในฟอร์ม
                            document.getElementById('edit-id').value = btn.dataset.id;
                            document.getElementById('edit-title').value = btn.dataset.title;
                            document.getElementById('edit-description').value = btn.dataset.description;
                            document.getElementById('edit-event_date').value = btn.dataset.event_date;
                            document.getElementById('edit-hours').value = btn.dataset.hours;
                            document.getElementById('edit-category').value = btn.dataset.category;
                            document.getElementById('edit-max_students').value = btn.dataset.max_students;
                            document.getElementById('edit-event-modal').classList.remove('hidden');
                        };
                    });
                    document.getElementById('close-edit-modal').onclick = function() {
                        document.getElementById('edit-event-modal').classList.add('hidden');
                    };
                    document.getElementById('edit-event-form').onsubmit = function(e) {
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
                                document.getElementById('edit-event-modal').classList.add('hidden');
                                loadEvents();
                            } else {
                                Swal.fire('ผิดพลาด', res.message || 'ไม่สามารถแก้ไขกิจกรรมได้', 'error');
                            }
                        })
                        .catch(() => Swal.fire('ผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์', 'error'));
                    };

                    // Bind ลบกิจกรรม
                    document.querySelectorAll('.delete-event-btn').forEach(btn => {
                        btn.onclick = function() {
                            Swal.fire({
                                title: 'ลบกิจกรรม?',
                                text: 'คุณต้องการลบกิจกรรมนี้หรือไม่',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'ลบ',
                                cancelButtonText: 'ยกเลิก',
                                confirmButtonColor: '#e3342f'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetch('../controllers/EventController.php', {
                                        method: 'DELETE',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ id: btn.dataset.id })
                                    })
                                    .then(res => res.json())
                                    .then(res => {
                                        if (res.success) {
                                            Swal.fire('สำเร็จ', 'ลบกิจกรรมเรียบร้อย', 'success');
                                            loadEvents();
                                        } else {
                                            Swal.fire('ผิดพลาด', res.message || 'ไม่สามารถลบกิจกรรมได้', 'error');
                                        }
                                    })
                                    .catch(() => Swal.fire('ผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์', 'error'));
                                }
                            });
                        };
                    });
                }
            });
    }
    loadEvents();

    form.onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const data = {};
        formData.forEach((v, k) => data[k] = v);

        fetch('../controllers/EventController.php', {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                eventCodeSpan.textContent = res.code;
                eventQrDiv.innerHTML = '';
                new QRCode(eventQrDiv, {
                    text: res.code,
                    width: 128,
                    height: 128
                });
                form.classList.add('hidden');
                resultDiv.classList.remove('hidden');
                loadEvents();
            } else {
                Swal.fire('เกิดข้อผิดพลาด', res.message || 'ไม่สามารถสร้างกิจกรรมได้', 'error');
            }
        })
        .catch(() => Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์', 'error'));
    };

    if (downloadBtn) {
        downloadBtn.onclick = function() {
            const qrImg = eventQrDiv.querySelector('img');
            if (qrImg) {
                const link = document.createElement('a');
                link.href = qrImg.src;
                link.download = (eventCodeSpan.textContent || 'qrcode') + '.png';
                link.click();
            } else {
                const qrCanvas = eventQrDiv.querySelector('canvas');
                if (qrCanvas) {
                    const link = document.createElement('a');
                    link.href = qrCanvas.toDataURL('image/png');
                    link.download = (eventCodeSpan.textContent || 'qrcode') + '.png';
                    link.click();
                }
            }
        };
    }
});
</script>

<?php require_once('script.php');?>
</body>
</html>
