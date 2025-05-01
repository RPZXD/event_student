<?php
session_start();

// เช็ค session และ role
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'ครู') {
    header('Location: ../login.php');
    exit;
}

require_once('header.php');
?>
<body class="hold-transition sidebar-mini layout-fixed light-mode">
<div class="wrapper">
    <?php require_once('wrapper.php');?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h5 class="m-0">ตารางกิจกรรม</h5>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="card">  
                    <div class="card-body">
                        <p class="text-gray-700 mb-4 text-lg font-semibold flex items-center gap-2">📋 ตารางกิจกรรม</p>
                        <div class="overflow-x-auto">
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
                </div>
            </div>
        </section>
    </div>
    <?php require_once('../footer.php');?>
</div>
<!-- QRCode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // โหลดข้อมูลกิจกรรมจาก controller
    function loadEvents() {
        fetch('../controllers/EventController.php')
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('event-table-body');
                tbody.innerHTML = '';
                const currentUser = <?= json_encode($_SESSION['username']) ?>;

                if (data.success && Array.isArray(data.events)) {
                    data.events.forEach(ev => {
                        let qrcodeBtn = '';
                        const status = ev.max_students
                                        ? `${ev.current_students || 0} / ${ev.max_students}`
                                        : "ไม่จำกัด";
                        let progressBar = '';
                        if (ev.max_students > 0) {
                            const current = ev.current_students || 0;
                            const percent = Math.min((current / ev.max_students) * 100, 100);
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
                }
            });
    }
    loadEvents();
});
</script>
<?php require_once('script.php');?>
</body>
</html>
