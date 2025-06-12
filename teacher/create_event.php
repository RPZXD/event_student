<?php 
session_start();

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
                <div class="card-header bg-indigo-400 text-white font-semibold text-lg">
                    รายการกิจกรรม
                </div>
                <div class="card-body">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                        <button id="create-event-btn" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow flex items-center gap-2" >
                            <span class="text-base">➕</span>
                            <span class="text-2xl">สร้างกิจกรรม</span>
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
                                <div class="mb-3">
                                    <label class="block mb-1 font-semibold">📆 วันหมดอายุของโค้ด (ถ้ามี)</label>
                                    <input type="date" name="expire_date" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
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
                                <th class="px-4 py-2 text-center">🎯 ชื่อกิจกรรม</th>
                                <th class="px-4 py-2 text-center">📅 วันที่จัด</th>
                                <th class="px-4 py-2 text-center">⏰ ชั่วโมง</th>
                                <th class="px-4 py-2 text-center">👩‍🏫 ผู้สร้าง</th>
                                <th class="px-4 py-2 text-center">จำนวน</th>
                                <th class="px-4 py-2 text-center">🔎 รายละเอียด</th>
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
                            <div class="mb-2">รหัสกิจกรรม: <span id="modal-event-code" class="font-mono text-blue-600"></span></div>
                            <div id="modal-qrcode" class="mb-2"></div>
                            <button id="modal-download-qrcode" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2">
                                <span>⬇️</span>
                                <span>ดาวน์โหลด QR CODE</span>
                            </button>
                        </div>
                    </div>
                    <!-- Modal รายชื่อโค้ด -->
                    <div id="codes-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative flex flex-col max-h-[90vh]">
                            <button id="close-codes-modal" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>
                            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">📄 รายชื่อโค้ดกิจกรรม</h2>
                            <div id="codes-modal-title" class="mb-2 font-semibold"></div>
                            <button id="download-codes-excel" class="mb-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2 self-end">
                                <span>⬇️</span>
                                <span>ดาวน์โหลด Excel</span>
                            </button>
                            <button id="print-codes-table" class="mb-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-2 self-end">
                                <span>🖨️</span>
                                <span>พิมพ์ตาราง</span>
                            </button>
                            <div class="overflow-x-auto flex-1" style="overflow-y:auto; max-height:55vh;">
                                <table class="min-w-full border" id="codes-table">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-2 py-1 border">#</th>
                                            <th class="px-2 py-1 border">โค้ด</th>
                                            <th class="px-2 py-1 border">QR</th>
                                            <th class="px-2 py-1 border">สถานะ</th>
                                            <th class="px-2 py-1 border print-hide">คัดลอก</th>
                                        </tr>
                                    </thead>
                                    <tbody id="codes-table-body"></tbody>
                                </table>
                            </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // โหลดข้อมูลกิจกรรมจาก controller
    function loadEvents() {
        fetch('../controllers/EventController.php')
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('event-table-body');
                // Destroy DataTable ก่อน (ถ้ามี)
                if ($.fn.dataTable.isDataTable('#event-table')) {
                    $('#event-table').DataTable().clear().destroy();
                }
                tbody.innerHTML = '';
                const currentUser = <?= json_encode($_SESSION['username']) ?>;

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
                            qrcodeBtn = ``;
                            // เพิ่มปุ่มดูโค้ดทั้งหมด (เฉพาะกิจกรรมที่จำกัดจำนวน)
                            let codesBtn = '';
                            if (ev.max_students > 0) {
                                codesBtn = `<button class="show-codes-btn inline-flex items-center gap-1 bg-indigo-500 hover:bg-indigo-700 text-white px-3 py-1 rounded shadow transition"
                                    data-id="${ev.id}" data-title="${ev.title}">
                                    <span>📄</span><span>ดูโค้ดทั้งหมด</span>
                                </button>`;
                            }
                            editBtn = `<button class="edit-event-btn inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow transition"
                                data-id="${ev.id}" 
                                data-title="${ev.title}" 
                                data-description="${ev.description || ''}" 
                                data-event_date="${ev.event_date}" 
                                data-hours="${ev.hours}" 
                                data-category="${ev.category || ''}" 
                                data-max_students="${ev.max_students || ''}">
                                <span>✏️</span><span>แก้ไข</span>
                            </button>`;
                            deleteBtn = `<button class="delete-event-btn inline-flex items-center gap-1 bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded shadow transition"
                                data-id="${ev.id}">
                                <span>🗑️</span><span>ลบ</span>
                            </button>`;
                            // เพิ่ม codesBtn เข้าไป
                            qrcodeBtn += codesBtn;
                        } else {
                            qrcodeBtn = ``;
                        }

                        tbody.innerHTML += `
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-4 py-2 text-center">🎯 <span>${ev.title}</span></td>
                                <td class="px-4 py-2 text-center">📅 ${thaiDate}</td>
                                <td class="px-4 py-2 text-center">⏰ ${ev.hours} ชั่วโมง</td>
                                <td class="px-4 py-2 text-left">👩‍🏫 <span>${ev.teacher_name || ev.teacher_id}</span></td>
                                <td class="px-4 py-2 text-center">👥 ${status}
                                                    <span>${progressBar}</span></td>
                                <td class="px-4 py-2 text-center">
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
                            fetch('../controllers/EventController.php?activity_id=' + btn.dataset.id)
                                .then(res => res.json())
                                .then(res => {
                                    let code = res.code || '';
                                    if (!code) {
                                        modalQrDiv.innerHTML = '<div class="text-red-500">ไม่พบรหัสกิจกรรม</div>';
                                    } else {
                                        // สมมติว่าหน้าเช็คอินคือ ../student/checkin.php?code=...
                                        const qrUrl = 'https://eventstd.phichai.ac.th/checkin.php?code=' + encodeURIComponent(code);
                                        new QRCode(modalQrDiv, {
                                            text: qrUrl,
                                            width: 128,
                                            height: 128
                                        });
                                        // set download
                                        document.getElementById('modal-event-code').innerText = code;
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

                    // Bind ปุ่มดูโค้ดทั้งหมด
                    let lastCodesExportData = [];
                    document.querySelectorAll('.show-codes-btn').forEach(btn => {
                        btn.onclick = function() {
                            const codesModal = document.getElementById('codes-modal');
                            const codesTableBody = document.getElementById('codes-table-body');
                            const codesModalTitle = document.getElementById('codes-modal-title');
                            codesModalTitle.textContent = 'กิจกรรม: ' + btn.dataset.title;
                            codesTableBody.innerHTML = '<tr><td colspan="5" class="text-center">กำลังโหลด...</td></tr>';
                            // ดึงโค้ดทั้งหมดจาก backend
                            fetch('../controllers/EventController.php?activity_id=' + btn.dataset.id + '&all_codes=1')
                                .then(res => res.json())
                                .then(res => {
                                    if (Array.isArray(res.codes) && res.codes.length > 0) {
                                        codesTableBody.innerHTML = '';
                                        lastCodesExportData = []; // reset
                                        res.codes.forEach((row, idx) => {
                                            // QR URL สำหรับแต่ละโค้ด
                                            const qrUrl = 'https://eventstd.phichai.ac.th/checkin.php?code=' + encodeURIComponent(row.code);
                                            codesTableBody.innerHTML += `
                                                <tr>
                                                    <td class="border px-2 py-1 text-center">${idx + 1}</td>
                                                    <td class="border px-2 py-1 font-mono">${row.code}</td>
                                                    <td class="border px-2 py-1"><div id="qr-${row.code}" style="display:inline-block;"></div></td>
                                                    <td class="border px-2 py-1 text-center">${row.is_used == 1 ? '<span class="text-red-500">ใช้แล้ว</span>' : '<span class="text-green-600">ยังไม่ใช้</span>'}</td>
                                                    <td class="border px-2 py-1 text-center print-hide">
                                                        <button class="copy-code-btn bg-blue-500 hover:bg-blue-700 text-white px-2 py-1 rounded" data-code="${row.code}">คัดลอก</button>
                                                    </td>
                                                </tr>
                                            `;
                                            setTimeout(() => {
                                                new QRCode(document.getElementById('qr-' + row.code), {
                                                    text: qrUrl,
                                                    width: 96,
                                                    height: 96
                                                });
                                            }, 0);
                                            // สำหรับ export
                                            lastCodesExportData.push({
                                                no: idx + 1,
                                                code: row.code,
                                                status: row.is_used == 1 ? 'ใช้แล้ว' : 'ยังไม่ใช้'
                                            });
                                        });
                                    } else {
                                        codesTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-red-500">ไม่พบโค้ด</td></tr>';
                                        lastCodesExportData = [];
                                    }
                                });
                            codesModal.classList.remove('hidden');
                        };
                    });
                    document.getElementById('close-codes-modal').onclick = function() {
                        document.getElementById('codes-modal').classList.add('hidden');
                    };
                    // คัดลอกโค้ด
                    document.body.addEventListener('click', function(e) {
                        if (e.target.classList.contains('copy-code-btn')) {
                            const code = e.target.dataset.code;
                            navigator.clipboard.writeText(code);
                            e.target.textContent = 'คัดลอกแล้ว!';
                            setTimeout(() => { e.target.textContent = 'คัดลอก'; }, 1000);
                        }
                    });

                    // ปุ่มดาวน์โหลด Excel
                    document.getElementById('download-codes-excel').onclick = function () {
                        if (!lastCodesExportData || lastCodesExportData.length === 0) {
                            Swal.fire('ไม่มีข้อมูล', 'ไม่พบโค้ดสำหรับดาวน์โหลด', 'warning');
                            return;
                        }

                        // สร้างข้อมูลในรูปแบบ 5 โค้ดต่อแถว
                        const codesPerRow = 5;
                        const worksheetData = [];
                        
                        // เพิ่มหัวข้อ
                        const activityTitle = document.getElementById('codes-modal-title').textContent;
                        worksheetData.push([activityTitle]);
                        worksheetData.push([]); // บรรทัดว่าง
                        
                        // จัดเรียงโค้ดเป็นแถวๆ
                        for (let i = 0; i < lastCodesExportData.length; i += codesPerRow) {
                            const row = [];
                            for (let j = 0; j < codesPerRow; j++) {
                                if (i + j < lastCodesExportData.length) {
                                    row.push(lastCodesExportData[i + j].code);
                                } else {
                                    row.push(''); // เซลล์ว่างถ้าไม่มีโค้ด
                                }
                            }
                            worksheetData.push(row);
                        }

                        // สร้าง Worksheet และ Workbook
                        const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
                        
                        // ปรับขนาดคอลัมน์
                        const colWidths = [];
                        for (let i = 0; i < codesPerRow; i++) {
                            colWidths.push({ wch: 15 }); // ความกว้าง 15 ตัวอักษร
                        }
                        worksheet['!cols'] = colWidths;
                        
                        // จัดกึ่งกลางหัวข้อ
                        if (worksheet['A1']) {
                            worksheet['A1'].s = {
                                alignment: { horizontal: 'center' },
                                font: { bold: true, sz: 14 }
                            };
                        }
                        
                        // รวมเซลล์หัวข้อ
                        worksheet['!merges'] = [
                            { s: { r: 0, c: 0 }, e: { r: 0, c: codesPerRow - 1 } }
                        ];

                        const workbook = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(workbook, worksheet, 'โค้ดกิจกรรม');

                        // สร้างไฟล์ .xlsx
                        XLSX.writeFile(workbook, 'activity_codes.xlsx');
                    };

                    // ปุ่มพิมพ์ตาราง
                    const printBtn = document.getElementById('print-codes-table');
                    if (printBtn) {
                        printBtn.onclick = function() {
                            // สร้างหน้าพิมพ์พิเศษสำหรับ QR codes
                            createPrintLayout();
                        };
                    }
                }

                // สร้าง DataTable ใหม่หลังเติมข้อมูลเสร็จ
                $('#event-table').DataTable({
                    language: {
                        search: "ค้นหา:",
                        lengthMenu: "แสดง _MENU_ รายการ",
                        info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                        paginate: {
                            first: "หน้าแรก",
                            last: "หน้าสุดท้าย",
                            next: "ถัดไป",
                            previous: "ก่อนหน้า"
                        },
                        zeroRecords: "ไม่พบข้อมูล",
                        infoEmpty: "ไม่มีข้อมูลให้แสดง",
                        infoFiltered: "(กรองจาก _MAX_ รายการทั้งหมด)"
                    }
                });

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
                // ปรับตรงนี้: ถ้ามี unique_codes ให้แสดง QR เฉพาะ
                eventCodeSpan.textContent = res.code;
                eventQrDiv.innerHTML = '';
                if (res.code) {
                    // สมมติว่าหน้าเช็คอินคือ ../student/checkin.php?code=...
                    const qrUrl = 'https://eventstd.phichai.ac.th/checkin.php?code=' + encodeURIComponent(res.code);
                    new QRCode(eventQrDiv, {
                        text: qrUrl,
                        width: 128,
                        height: 128
                    });
                }
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

    // ฟังก์ชันสร้างเลย์เอาต์สำหรับพิมพ์ QR codes
    function createPrintLayout() {
        // สร้าง div สำหรับพิมพ์
        let printDiv = document.getElementById('print-qr-layout');
        if (printDiv) printDiv.remove();
        
        printDiv = document.createElement('div');
        printDiv.id = 'print-qr-layout';
        printDiv.className = 'print-only-layout';
        
        // ดึง QR codes จากตาราง
        const qrElements = document.querySelectorAll('#codes-table tbody tr td:nth-child(3) div[id^="qr-"]');
        const codes = document.querySelectorAll('#codes-table tbody tr td:nth-child(2)');
        
        if (qrElements.length === 0) {
            alert('ไม่พบ QR Code สำหรับพิมพ์');
            return;
        }
        
        // สร้างหัวเรื่อง
        const activityTitle = document.getElementById('codes-modal-title').textContent;
        let html = `<div class="print-header"><h2>${activityTitle}</h2></div>`;
        html += '<div class="qr-grid">';
        
        qrElements.forEach((qrDiv, index) => {
            const qrImg = qrDiv.querySelector('img') || qrDiv.querySelector('canvas');
            const code = codes[index] ? codes[index].textContent : '';
            
            if (qrImg) {
                const imgSrc = qrImg.tagName === 'CANVAS' ? qrImg.toDataURL() : qrImg.src;
                html += `
                    <div class="qr-item">
                        <img src="${imgSrc}" alt="QR Code">
                        <div class="qr-code-text">${code}</div>
                    </div>
                `;
            }
        });
        html += '</div>';
        
        printDiv.innerHTML = html;
        document.body.appendChild(printDiv);
        
        // พิมพ์
        setTimeout(() => {
            window.print();
        }, 100);
    }
});
</script>
<style>
.print-only-layout {
    display: none;
}

@media print {
    /* ซ่อนทุกอย่างยกเว้น print layout */
    body * {
        visibility: hidden !important;
    }
    
    .print-only-layout, .print-only-layout * {
        visibility: visible !important;
        display: block !important;
    }
    
    .print-only-layout {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        height: 100% !important;
        padding: 10mm !important;
        margin: 0 !important;
        background: white !important;
        display: block !important;
    }
    
    .print-header {
        text-align: center !important;
        margin-bottom: 10mm !important;
    }
    
    .print-header h2 {
        font-size: 18pt !important;
        font-weight: bold !important;
        color: black !important;
        margin: 0 !important;
    }
    
    .qr-grid {
        display: grid !important;
        grid-template-columns: repeat(5, 1fr) !important;
        gap: 3mm !important;
        width: 100% !important;
    }
    
    .qr-item {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        page-break-inside: avoid !important;
        padding: 2mm !important;
        border: 1px solid #ccc !important;
        background: white !important;
    }
    
    .qr-item img {
        width: 25mm !important;
        height: 25mm !important;
        max-width: 25mm !important;
        max-height: 25mm !important;
        margin-bottom: 1mm !important;
    }
    
    .qr-code-text {
        font-size: 8pt !important;
        font-family: monospace !important;
        color: black !important;
        word-break: break-all !important;
        margin-top: 1mm !important;
        line-height: 1.1 !important;
    }
    
    /* ซ่อน modal เดิม */
    #codes-modal {
        display: none !important;
    }
    
    /* ลดระยะขอบหน้ากระดาษ */
    @page {
        margin: 8mm;
        size: A4;
    }
}
</style>

<?php require_once('script.php');?>
</body>
</html>
