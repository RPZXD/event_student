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

require_once('../classes/DatabaseEvent.php');
use App\DatabaseEvent;

// ตรวจสอบ activity_id
if (!isset($_GET['activity_id'])) {
    header('Location: create_event.php');
    exit;
}

$activity_id = $_GET['activity_id'];

// สร้างการเชื่อมต่อฐานข้อมูล
$db = new DatabaseEvent();
$pdo = $db->getPDO();

// ดึงข้อมูลกิจกรรม
$stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ? AND teacher_id = ?");
$stmt->execute([$activity_id, $_SESSION['username']]);
$activity = $stmt->fetch();

if (!$activity) {
    header('Location: create_event.php');
    exit;
}

// Pagination settings
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 50; // แสดง 50 รายการต่อหน้า
$offset = ($page - 1) * $limit;

// นับจำนวนโค้ดทั้งหมด
$stmt = $pdo->prepare("SELECT COUNT(*) FROM activity_unique_codes WHERE activity_id = ?");
$stmt->execute([$activity_id]);
$totalCodes = $stmt->fetchColumn();
$totalPages = ceil($totalCodes / $limit);

// ดึงรายการโค้ดแบบ pagination - แก้ไข SQL syntax
$sql = "SELECT * FROM activity_unique_codes WHERE activity_id = ? ORDER BY id LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute([$activity_id]);
$codes = $stmt->fetchAll();

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
                        <h5 class="m-0">จัดการ QR Code กิจกรรม</h5>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="create_event.php">กิจกรรม</a></li>
                            <li class="breadcrumb-item active">QR Code</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header bg-indigo-400 text-white font-semibold text-lg">
                        <div class="flex justify-between items-center">
                            <span>🎯 <?= htmlspecialchars($activity['title']) ?></span>
                            <a href="create_event.php" class="bg-white text-indigo-600 px-3 py-1 rounded text-sm hover:bg-gray-100">
                                ← กลับ
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p><strong>📅 วันที่จัด:</strong> <?= date('d/m/Y', strtotime($activity['event_date'])) ?></p>
                                    <p><strong>⏰ ชั่วโมง:</strong> <?= $activity['hours'] ?> ชั่วโมง</p>
                                </div>
                                <div>
                                    <p><strong>👥 จำนวนสูงสุด:</strong> <?= $activity['max_students'] ?> คน</p>
                                    <p><strong>📊 จำนวนโค้ด:</strong> <?= $totalCodes ?> โค้ด</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-2 mb-4">
                                <button id="download-excel" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2">
                                    <span>📊</span>
                                    <span>ดาวน์โหลด Excel ทั้งหมด</span>
                                </button>
                                <button id="print-current-page" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-2">
                                    <span>🖨️</span>
                                    <span>พิมพ์หน้านี้</span>
                                </button>
                                <button id="print-all-qrcodes" class="bg-blue-800 hover:bg-blue-900 text-white px-4 py-2 rounded flex items-center gap-2">
                                    <span>🖨️</span>
                                    <span>พิมพ์ทั้งหมด</span>
                                </button>
                                <button id="generate-qr-batch" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded flex items-center gap-2">
                                    <span>⚡</span>
                                    <span>สร้าง QR หน้านี้</span>
                                </button>
                            </div>

                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                            <div class="flex justify-center mb-4">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?activity_id=<?= $activity_id ?>&page=<?= $page - 1 ?>">« ก่อนหน้า</a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php
                                        $start = max(1, $page - 2);
                                        $end = min($totalPages, $page + 2);
                                        for ($i = $start; $i <= $end; $i++):
                                        ?>
                                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                <a class="page-link" href="?activity_id=<?= $activity_id ?>&page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        
                                        <?php if ($page < $totalPages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?activity_id=<?= $activity_id ?>&page=<?= $page + 1 ?>">ถัดไป »</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="text-center text-gray-600 mb-2">
                                หน้า <?= $page ?> จาก <?= $totalPages ?> (แสดง <?= count($codes) ?> จาก <?= $totalCodes ?> รายการ)
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full border" id="codes-table">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 border text-center">#</th>
                                        <th class="px-4 py-2 border text-center">โค้ด</th>
                                        <th class="px-4 py-2 border text-center">QR Code</th>
                                        <th class="px-4 py-2 border text-center">สถานะ</th>
                                        <th class="px-4 py-2 border text-center print-hide">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($codes as $index => $code): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border text-center"><?= $offset + $index + 1 ?></td>
                                        <td class="px-4 py-2 border text-center font-mono text-sm"><?= htmlspecialchars($code['code']) ?></td>
                                        <td class="px-4 py-2 border text-center">
                                            <div id="qr-<?= $code['id'] ?>" class="inline-block qr-placeholder" data-code="<?= htmlspecialchars($code['code']) ?>">
                                                <div class="bg-gray-200 w-20 h-20 flex items-center justify-center text-xs">QR</div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 border text-center">
                                            <?php if ($code['is_used']): ?>
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">ใช้แล้ว</span>
                                            <?php else: ?>
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">ยังไม่ใช้</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-2 border text-center print-hide">
                                            <button class="copy-code bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" 
                                                    data-code="<?= htmlspecialchars($code['code']) ?>">
                                                📋 คัดลอก
                                            </button>
                                            <button class="download-single-qr bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs ml-1" 
                                                    data-code="<?= htmlspecialchars($code['code']) ?>" 
                                                    data-qr-id="qr-<?= $code['id'] ?>">
                                                ⬇️ QR
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination ล่าง -->
                        <?php if ($totalPages > 1): ?>
                        <div class="flex justify-center mt-4">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?activity_id=<?= $activity_id ?>&page=<?= $page - 1 ?>">« ก่อนหน้า</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = $start; $i <= $end; $i++): ?>
                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?activity_id=<?= $activity_id ?>&page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?activity_id=<?= $activity_id ?>&page=<?= $page + 1 ?>">ถัดไป »</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php require_once('../footer.php');?>
</div>

<!-- Progress Modal -->
<div id="progress-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4">กำลังประมวลผล...</h3>
        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
            <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <div id="progress-text" class="text-center text-sm text-gray-600">0%</div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codes = <?= json_encode($codes) ?>;
    const activityTitle = <?= json_encode($activity['title']) ?>;
    const activityId = <?= $activity_id ?>;
    const totalCodes = <?= $totalCodes ?>;
    let qrGenerated = new Set();

    // ฟังก์ชันสร้าง QR Code แบบ lazy loading
    function generateQRCode(codeData) {
        const qrDiv = document.getElementById('qr-' + codeData.id);
        if (qrDiv && !qrGenerated.has(codeData.id)) {
            qrDiv.innerHTML = '';
            const qrUrl = 'https://eventstd.phichai.ac.th/checkin.php?code=' + encodeURIComponent(codeData.code);
            new QRCode(qrDiv, {
                text: qrUrl,
                width: 80,
                height: 80
            });
            qrGenerated.add(codeData.id);
        }
    }

    // Intersection Observer สำหรับ lazy loading QR codes
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const qrDiv = entry.target;
                const code = qrDiv.dataset.code;
                const codeData = codes.find(c => c.code === code);
                if (codeData) {
                    generateQRCode(codeData);
                    observer.unobserve(qrDiv);
                }
            }
        });
    }, { rootMargin: '50px' });

    // เริ่ม observe QR placeholders
    document.querySelectorAll('.qr-placeholder').forEach(qrDiv => {
        observer.observe(qrDiv);
    });

    // สร้าง QR ทั้งหมดในหน้านี้
    document.getElementById('generate-qr-batch').addEventListener('click', function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span>⏳</span><span>กำลังสร้าง...</span>';
        
        setTimeout(() => {
            codes.forEach(generateQRCode);
            btn.disabled = false;
            btn.innerHTML = '<span>✅</span><span>สร้าง QR แล้ว</span>';
            setTimeout(() => {
                btn.innerHTML = '<span>⚡</span><span>สร้าง QR หน้านี้</span>';
            }, 2000);
        }, 100);
    });

    // คัดลอกโค้ด
    document.querySelectorAll('.copy-code').forEach(btn => {
        btn.addEventListener('click', function() {
            navigator.clipboard.writeText(this.dataset.code).then(() => {
                Swal.fire({
                    title: 'คัดลอกสำเร็จ!',
                    text: 'คัดลอกโค้ดแล้ว',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        });
    });

    // ดาวน์โหลด QR Code เดี่ยว
    document.querySelectorAll('.download-single-qr').forEach(btn => {
        btn.addEventListener('click', function() {
            const qrDiv = document.getElementById(this.dataset.qrId);
            const codeData = codes.find(c => c.code === this.dataset.code);
            
            if (codeData && !qrGenerated.has(codeData.id)) {
                generateQRCode(codeData);
                setTimeout(() => downloadSingleQR(this), 500);
            } else {
                downloadSingleQR(this);
            }
        });
    });

    function downloadSingleQR(btn) {
        const qrDiv = document.getElementById(btn.dataset.qrId);
        const qrImg = qrDiv.querySelector('img') || qrDiv.querySelector('canvas');
        
        if (qrImg) {
            const link = document.createElement('a');
            link.href = qrImg.tagName === 'CANVAS' ? qrImg.toDataURL() : qrImg.src;
            link.download = btn.dataset.code + '.png';
            link.click();
        }
    }

    // ดาวน์โหลด Excel ทั้งหมด
    document.getElementById('download-excel').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span>⏳</span><span>กำลังเตรียมข้อมูล...</span>';

        try {
            // ดึงข้อมูลทั้งหมดจาก API
            const response = await fetch(`../controllers/EventController.php?activity_id=${activityId}&all_codes=1`);
            const result = await response.json();
            
            if (result.codes) {
                // สร้างข้อมูลแบบแถว 5 โค้ดต่อแถว
                const data = [];
                const codes = result.codes.map(code => code.code);
                
                // เพิ่มหัวข้อ
                data.push(['แถวที่ 1', 'แถวที่ 2', 'แถวที่ 3', 'แถวที่ 4', 'แถวที่ 5']);
                
                // จัดกลุ่มโค้ด 5 ตัวต่อแถว
                for (let i = 0; i < codes.length; i += 5) {
                    const row = [];
                    for (let j = 0; j < 5; j++) {
                        row.push(codes[i + j] || ''); // ถ้าไม่มีโค้ดให้ใส่ค่าว่าง
                    }
                    data.push(row);
                }

                const ws = XLSX.utils.aoa_to_sheet(data);
                
                // ปรับความกว้างคอลัมน์
                const colWidths = [
                    { wch: 15 }, // โค้ด 1
                    { wch: 15 }, // โค้ด 2
                    { wch: 15 }, // โค้ด 3
                    { wch: 15 }, // โค้ด 4
                    { wch: 15 }  // โค้ด 5
                ];
                ws['!cols'] = colWidths;
                
                // จัดรูปแบบหัวข้อ
                const headerStyle = {
                    font: { bold: true },
                    fill: { fgColor: { rgb: "CCCCCC" } },
                    alignment: { horizontal: "center" }
                };
                
                // ใส่สไตล์ให้หัวข้อ
                ['A1', 'B1', 'C1', 'D1', 'E1'].forEach(cell => {
                    if (!ws[cell]) ws[cell] = {};
                    ws[cell].s = headerStyle;
                });

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'QR Codes');
                XLSX.writeFile(wb, activityTitle + '_QRCodes_Grid.xlsx');
            }
        } catch (error) {
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถดาวน์โหลดข้อมูลได้', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span>📊</span><span>ดาวน์โหลด Excel ทั้งหมด</span>';
        }
    });

    // พิมพ์หน้าปัจจุบัน
    document.getElementById('print-current-page').addEventListener('click', function() {
        // สร้าง QR ทั้งหมดในหน้านี้ก่อน
        codes.forEach(generateQRCode);
        setTimeout(() => createPrintLayout(codes), 1000);
    });

    // พิมพ์ทั้งหมด
    document.getElementById('print-all-qrcodes').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span>⏳</span><span>กำลังเตรียมข้อมูล...</span>';

        try {
            const response = await fetch(`../controllers/EventController.php?activity_id=${activityId}&all_codes=1`);
            const result = await response.json();
            
            if (result.codes) {
                createPrintLayoutAll(result.codes);
            }
        } catch (error) {
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถดึงข้อมูลได้', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span>🖨️</span><span>พิมพ์ทั้งหมด</span>';
        }
    });

    // ฟังก์ชันสร้างเลย์เอาต์สำหรับพิมพ์หน้าปัจจุบัน
    function createPrintLayout(codesToPrint) {
        let printDiv = document.getElementById('print-qr-layout');
        if (printDiv) printDiv.remove();
        
        printDiv = document.createElement('div');
        printDiv.id = 'print-qr-layout';
        printDiv.className = 'print-only-layout';
        
        let html = `<div class="print-header"><h2>${activityTitle} - QR Codes (หน้า <?= $page ?>)</h2></div>`;
        html += '<div class="qr-grid">';
        
        codesToPrint.forEach(codeData => {
            const qrDiv = document.getElementById('qr-' + codeData.id);
            const qrImg = qrDiv ? (qrDiv.querySelector('img') || qrDiv.querySelector('canvas')) : null;
            
            if (qrImg) {
                const imgSrc = qrImg.tagName === 'CANVAS' ? qrImg.toDataURL() : qrImg.src;
                html += `
                    <div class="qr-item">
                        <img src="${imgSrc}" alt="QR Code">
                        <div class="qr-code-text">${codeData.code}</div>
                    </div>
                `;
            }
        });
        
        html += '</div>';
        printDiv.innerHTML = html;
        document.body.appendChild(printDiv);
        
        setTimeout(() => window.print(), 100);
    }

    // ฟังก์ชันสร้างเลย์เอาต์สำหรับพิมพ์ทั้งหมด
    async function createPrintLayoutAll(allCodes) {
        showProgress();
        
        let printDiv = document.getElementById('print-qr-layout');
        if (printDiv) printDiv.remove();
        
        printDiv = document.createElement('div');
        printDiv.id = 'print-qr-layout';
        printDiv.className = 'print-only-layout';
        
        let html = `<div class="print-header"><h2>${activityTitle} - QR Codes ทั้งหมด</h2></div>`;
        html += '<div class="qr-grid">';
        
        // สร้าง QR codes แบบ batch โดยใช้ div แทน canvas
        for (let i = 0; i < allCodes.length; i++) {
            const codeData = allCodes[i];
            
            // สร้าง temporary div สำหรับ QR code
            const tempDiv = document.createElement('div');
            tempDiv.style.position = 'absolute';
            tempDiv.style.left = '-9999px';
            document.body.appendChild(tempDiv);
            
            // สร้าง QR code
            const qr = new QRCode(tempDiv, {
                text: 'https://eventstd.phichai.ac.th/checkin.php?code=' + encodeURIComponent(codeData.code),
                width: 120,
                height: 120
            });
            
            // รอให้ QR code สร้างเสร็จ
            await new Promise(resolve => setTimeout(resolve, 50));
            
            // ดึงรูป QR code
            const qrImg = tempDiv.querySelector('img') || tempDiv.querySelector('canvas');
            let imgSrc = '';
            
            if (qrImg) {
                imgSrc = qrImg.tagName === 'CANVAS' ? qrImg.toDataURL() : qrImg.src;
            }
            
            html += `
                <div class="qr-item">
                    <img src="${imgSrc}" alt="QR Code">
                    <div class="qr-code-text">${codeData.code}</div>
                </div>
            `;
            
            // ลบ temporary div
            document.body.removeChild(tempDiv);
            
            updateProgress((i + 1) / allCodes.length * 100);
        }
        
        html += '</div>';
        printDiv.innerHTML = html;
        document.body.appendChild(printDiv);
        
        hideProgress();
        setTimeout(() => window.print(), 100);
    }

    function showProgress() {
        document.getElementById('progress-modal').classList.remove('hidden');
    }

    function hideProgress() {
        document.getElementById('progress-modal').classList.add('hidden');
    }

    function updateProgress(percent) {
        document.getElementById('progress-bar').style.width = percent + '%';
        document.getElementById('progress-text').textContent = Math.round(percent) + '%';
    }
});
</script>

<style>
.print-only-layout {
    display: none;
}

@media print {
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
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 5mm !important;
        width: 100% !important;
    }
    
    .qr-item {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        page-break-inside: avoid !important;
        padding: 3mm !important;
        border: 1px solid #ccc !important;
        background: white !important;
    }
    
    .qr-item img {
        width: 30mm !important;
        height: 30mm !important;
        max-width: 30mm !important;
        max-height: 30mm !important;
        margin-bottom: 2mm !important;
    }
    
    .qr-code-text {
        font-size: 9pt !important;
        font-family: monospace !important;
        color: black !important;
        word-break: break-all !important;
        margin-top: 1mm !important;
        line-height: 1.1 !important;
    }
    
    .print-hide {
        display: none !important;
    }
    
    @page {
        margin: 8mm;
        size: A4;
    }
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-item {
    margin: 0 2px;
}

.page-link {
    display: block;
    padding: 8px 12px;
    text-decoration: none;
    color: #3b82f6;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    transition: all 0.2s;
}

.page-link:hover {
    background-color: #f3f4f6;
}

.page-item.active .page-link {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.qr-placeholder {
    transition: all 0.3s ease;
}
</style>

<?php require_once('script.php');?>
</body>
</html>
