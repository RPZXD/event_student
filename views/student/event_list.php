<!-- Student Event List - กิจกรรมที่ลงทะเบียน -->
<?php 
$user = $_SESSION['user'] ?? [];
$studentName = trim(($user['Stu_pre'] ?? '') . ($user['Stu_name'] ?? '') . ' ' . ($user['Stu_sur'] ?? ''));
?>

<div class="space-y-4 sm:space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                📋 กิจกรรมที่ลงทะเบียน
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">รายการกิจกรรมทั้งหมดที่คุณได้ลงทะเบียนแล้ว</p>
        </div>
        <button id="print-transcript-btn" 
                class="btn-success inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:px-6 sm:py-3 rounded-xl text-white font-semibold shadow-lg hover:shadow-xl transition-all text-sm sm:text-base">
            <i class="fas fa-print"></i>
            <span>พิมพ์ Transcript</span>
        </button>
    </div>

    <!-- Summary Cards -->
    <div id="summary-cards" class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        <div class="glass rounded-xl p-3 sm:p-4 text-center">
            <div class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400" id="total-events">0</div>
            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">กิจกรรมทั้งหมด</div>
        </div>
        <div class="glass rounded-xl p-3 sm:p-4 text-center">
            <div class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400" id="total-hours">0</div>
            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">ชั่วโมงรวม</div>
        </div>
        <div class="glass rounded-xl p-3 sm:p-4 text-center hidden sm:block">
            <div class="text-2xl sm:text-3xl font-bold text-purple-600 dark:text-purple-400" id="volunteer-hours">0</div>
            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">จิตอาสา</div>
        </div>
        <div class="glass rounded-xl p-3 sm:p-4 text-center hidden sm:block">
            <div class="text-2xl sm:text-3xl font-bold text-orange-600 dark:text-orange-400" id="other-hours">0</div>
            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">อื่นๆ</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="glass rounded-xl p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-filter text-gray-400"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">กรองข้อมูล:</span>
            </div>
            <div class="flex flex-wrap gap-3 flex-1">
                <div class="flex-1 min-w-[120px] sm:flex-none">
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">ภาคเรียน</label>
                    <select id="term-select" 
                            class="w-full sm:w-auto px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        <option value="">ทั้งหมด</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[120px] sm:flex-none">
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">ปีการศึกษา</label>
                    <select id="pee-select" 
                            class="w-full sm:w-auto px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        <option value="">ทั้งหมด</option>
                    </select>
                </div>
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
            <table id="event-list-table" class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-emerald-500 to-green-600 text-white">
                        <th class="px-4 py-3 text-left text-sm font-semibold">ชื่อกิจกรรม</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">วันที่จัด</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">ชั่วโมง</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">ประเภท</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">ผู้สร้างกิจกรรม</th>
                    </tr>
                </thead>
                <tbody id="event-list-table-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
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
// Stored user data for print
const userData = {
    name: '<?php echo addslashes($user['Stu_pre'] . $user['Stu_name'] . ' ' . $user['Stu_sur']); ?>',
    id: '<?php echo addslashes($user['Stu_id'] ?? ''); ?>',
    room: '<?php echo addslashes(($user['Stu_major'] ?? '') . '/' . ($user['Stu_room'] ?? '')); ?>'
};

let allEvents = [];

function fetchTermsAndPees() {
    return fetch('../controllers/StudentEventController.php?action=terms_pees')
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            const contentType = res.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) throw new Error("Invalid JSON response");
            return res.json();
        })
        .catch(error => {
            console.error("Error fetching terms and pees:", error);
            return { success: false, terms: [], pees: [] };
        });
}

function fetchEvents(term = '', pee = '') {
    let url = `../controllers/StudentEventController.php?term=${encodeURIComponent(term)}&pee=${encodeURIComponent(pee)}`;
    return fetch(url)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            const contentType = res.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) throw new Error("Invalid JSON response");
            return res.json();
        })
        .catch(error => {
            console.error("Error fetching events:", error);
            Swal.fire('Error', 'ไม่สามารถโหลดข้อมูลกิจกรรมได้', 'error');
            return { success: false, events: [] };
        });
}

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

function renderEvents(data) {
    allEvents = data.success && Array.isArray(data.events) ? data.events : [];
    
    // Update summary cards
    const totalEvents = allEvents.length;
    const totalHours = allEvents.reduce((sum, ev) => sum + parseFloat(ev.hours || 0), 0);
    const volunteerHours = allEvents.filter(ev => ev.category === 'จิตอาสา').reduce((sum, ev) => sum + parseFloat(ev.hours || 0), 0);
    const otherHours = totalHours - volunteerHours;
    
    document.getElementById('total-events').textContent = totalEvents;
    document.getElementById('total-hours').textContent = totalHours;
    document.getElementById('volunteer-hours').textContent = volunteerHours;
    document.getElementById('other-hours').textContent = otherHours.toFixed(0);

    // Render mobile cards
    renderMobileCards(allEvents);
    
    // Render desktop table
    renderDesktopTable(allEvents);
}

function renderMobileCards(events) {
    const container = document.getElementById('events-cards');
    
    if (events.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">ยังไม่มีการลงทะเบียนกิจกรรม</p>
                <a href="event_regis.php" class="inline-block mt-4 px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>ลงทะเบียนกิจกรรม
                </a>
            </div>
        `;
        return;
    }
    
    container.innerHTML = events.map(ev => {
        const color = getCategoryColor(ev.category);
        const thaiDate = formatThaiDate(ev.event_date);
        return `
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border ${color.border} card-hover">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 dark:text-white truncate">${ev.title}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <i class="fas fa-calendar-alt mr-1"></i>${thaiDate}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <i class="fas fa-user-tie mr-1"></i>${ev.teacher_name || '-'}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="inline-block px-2 py-1 rounded-lg text-xs font-medium ${color.bg} ${color.text}">${ev.category}</span>
                        <div class="mt-2 text-lg font-bold text-emerald-600 dark:text-emerald-400">${ev.hours} <span class="text-xs font-normal">ชม.</span></div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function renderDesktopTable(events) {
    const tbody = document.getElementById('event-list-table-body');
    
    // Destroy existing DataTable
    if ($.fn.DataTable.isDataTable('#event-list-table')) {
        $('#event-list-table').DataTable().clear().destroy();
    }
    
    if (events.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-4 py-12 text-center">
                    <div class="text-5xl mb-4">📭</div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">ยังไม่มีการลงทะเบียนกิจกรรม</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = events.map(ev => {
        const color = getCategoryColor(ev.category);
        const thaiDate = formatThaiDate(ev.event_date);
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
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">${ev.teacher_name || '-'}</td>
            </tr>
        `;
    }).join('');
    
    // Initialize DataTable
    $('#event-list-table').DataTable({
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
}

function convertThaiDate(thaiDateStr) {
    const thaiMonths = {
        "มกราคม": "01", "กุมภาพันธ์": "02", "มีนาคม": "03", "เมษายน": "04",
        "พฤษภาคม": "05", "มิถุนายน": "06", "กรกฎาคม": "07", "สิงหาคม": "08",
        "กันยายน": "09", "ตุลาคม": "10", "พฤศจิกายน": "11", "ธันวาคม": "12"
    };
    const parts = thaiDateStr.trim().split(" ");
    if (parts.length !== 3 || !thaiMonths[parts[1]]) return null;
    return `${parts[0].padStart(2, "0")}/${thaiMonths[parts[1]]}/${parts[2]}`;
}

function printTranscript() {
    const term = document.getElementById('term-select').value;
    const pee = document.getElementById('pee-select').value;
    
    if (allEvents.length === 0) {
        Swal.fire('ไม่มีข้อมูล', 'ไม่พบข้อมูลกิจกรรมสำหรับพิมพ์', 'info');
        return;
    }

    let tableRowsHtml = '';
    const summary = {};
    let totalHours = 0;
    let rowNum = 1;

    allEvents.forEach(ev => {
        const thaiDate = formatThaiDate(ev.event_date);
        const hours = parseFloat(ev.hours);

        if (!isNaN(hours)) {
            totalHours += hours;
            summary[ev.category] = (summary[ev.category] || 0) + hours;
        }

        tableRowsHtml += `
            <tr>
                <td class="text-center">${rowNum++}</td>
                <td class="text-left">${ev.title}</td>
                <td class="text-center">${thaiDate}</td>
                <td class="text-center">${hours}</td>
                <td class="text-center">${ev.category}</td>
            </tr>
        `;
    });

    // Create summary section
    let summaryHtml = '';
    const categoryColors = {
        'จิตอาสา': '#9333ea',
        'วิชาการ': '#3b82f6',
        'กีฬา': '#22c55e',
        'ศิลปะ': '#ec4899',
        'อื่นๆ': '#6b7280'
    };
    
    for (const type in summary) {
        const color = categoryColors[type] || '#6b7280';
        summaryHtml += `
            <div class="summary-item">
                <span class="summary-label" style="background: ${color};">${type}</span>
                <span class="summary-value">${summary[type]} ชั่วโมง</span>
            </div>
        `;
    }

    const transcriptContent = `
        <!DOCTYPE html>
        <html lang="th">
        <head>
            <meta charset="UTF-8">
            <title>Transcript กิจกรรม - ${userData.name}</title>
            <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: 'Sarabun', sans-serif;
                    background: #fff;
                    color: #1f2937;
                    line-height: 1.6;
                    font-size: 11pt;
                }
                
                .container {
                    max-width: 210mm;
                    margin: 0 auto;
                    padding: 15mm 20mm;
                    position: relative;
                }
                
                /* Watermark */
                .watermark {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    opacity: 0.06;
                    z-index: -1;
                    width: 300px;
                }
                
                /* Header */
                .header {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                    margin-bottom: 25px;
                    padding-bottom: 15px;
                    border-bottom: 2px solid #10b981;
                }
                
                .logo {
                    width: 80px;
                    height: 80px;
                    flex-shrink: 0;
                }
                
                .header-content {
                    flex: 1;
                    text-align: left;
                }
                
                .school-name {
                    font-size: 16pt;
                    font-weight: 700;
                    color: #047857;
                    margin-bottom: 3px;
                }
                
                .document-title {
                    font-size: 14pt;
                    font-weight: 600;
                    color: #1f2937;
                    margin-bottom: 3px;
                }
                
                .document-subtitle {
                    font-size: 10pt;
                    color: #6b7280;
                }
                
                .print-date {
                    text-align: right;
                    font-size: 9pt;
                    color: #6b7280;
                    flex-shrink: 0;
                }
                
                /* Student Info */
                .student-info {
                    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
                    border-radius: 10px;
                    padding: 15px 20px;
                    margin-bottom: 20px;
                    border: 1px solid #a7f3d0;
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 10px 30px;
                }
                
                .info-item {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                
                .info-label {
                    font-weight: 500;
                    color: #6b7280;
                    font-size: 10pt;
                }
                
                .info-value {
                    font-weight: 600;
                    color: #1f2937;
                }
                
                /* Table */
                .table-container {
                    margin-bottom: 20px;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 10pt;
                }
                
                thead th {
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    color: white;
                    font-weight: 600;
                    padding: 12px 10px;
                    text-align: center;
                    border: 1px solid #059669;
                }
                
                thead th:first-child {
                    border-radius: 8px 0 0 0;
                }
                
                thead th:last-child {
                    border-radius: 0 8px 0 0;
                }
                
                tbody td {
                    padding: 10px;
                    border: 1px solid #d1d5db;
                    background: #fff;
                }
                
                tbody tr:nth-child(even) td {
                    background: #f9fafb;
                }
                
                tbody tr:hover td {
                    background: #ecfdf5;
                }
                
                .text-center { text-align: center; }
                .text-left { text-align: left; }
                
                /* Summary Section */
                .summary-section {
                    background: #f8fafc;
                    border-radius: 10px;
                    padding: 15px 20px;
                    margin-bottom: 25px;
                    border: 1px solid #e2e8f0;
                }
                
                .summary-title {
                    font-size: 12pt;
                    font-weight: 600;
                    color: #1f2937;
                    margin-bottom: 12px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                
                .summary-grid {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    margin-bottom: 12px;
                }
                
                .summary-item {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    background: white;
                    padding: 8px 12px;
                    border-radius: 8px;
                    border: 1px solid #e2e8f0;
                }
                
                .summary-label {
                    color: white;
                    font-size: 9pt;
                    padding: 3px 10px;
                    border-radius: 20px;
                    font-weight: 500;
                }
                
                .summary-value {
                    font-weight: 600;
                    color: #1f2937;
                }
                
                .total-box {
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    color: white;
                    padding: 12px 20px;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                
                .total-label {
                    font-size: 12pt;
                    font-weight: 500;
                }
                
                .total-value {
                    font-size: 12pt;
                    font-weight: 700;
                }
                
                /* Signatures */
                .signatures {
                    margin-top: 5px;
                    page-break-inside: avoid;
                }
                
                .sig-row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 25px;
                }
                
                .sig-box {
                    text-align: center;
                    width: 45%;
                }
                
                .sig-line {
                    border-bottom: 1px dotted #374151;
                    margin-bottom: 2px;
                    height: 30px;
                }
                
                .sig-name {
                    font-weight: 600;
                    color: #1f2937;
                    margin-bottom: 3px;
                }
                
                .sig-title {
                    font-size: 9pt;
                    color: #6b7280;
                }
                
                .sig-center {
                    display: flex;
                    justify-content: center;
                }
                
                /* Print Styles */
                @media print {
                    body {
                        print-color-adjust: exact;
                        -webkit-print-color-adjust: exact;
                    }
                    
                    .container {
                        padding: 10mm 15mm;
                    }
                    
                    .watermark {
                        opacity: 0.04;
                    }
                    
                    thead th {
                        background: #10b981 !important;
                        -webkit-print-color-adjust: exact;
                    }
                    
                    table {
                        page-break-inside: auto;
                    }
                    
                    tr {
                        page-break-inside: avoid;
                    }
                    
                    .signatures {
                        page-break-inside: avoid;
                    }
                }
                
                @page {
                    size: A4;
                    margin: 10mm;
                }
            </style>
        </head>
        <body>
            <img src="../dist/img/logo-phicha02.png" alt="" class="watermark">
            
            <div class="container">
                <!-- Header -->
                <div class="header">
                    <img src="../dist/img/logo-phicha.png" alt="Logo" class="logo">
                    <div class="header-content">
                        <div class="school-name">โรงเรียนพิชัย</div>
                        <div class="document-title">📜 Transcript กิจกรรมนักเรียน</div>
                        <div class="document-subtitle">เอกสารบันทึกการเข้าร่วมกิจกรรม</div>
                    </div>
                    <div class="print-date">
                        <div>วันที่พิมพ์</div>
                        <div style="font-weight: 600; color: #1f2937;">${new Date().toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' })}</div>
                    </div>
                </div>
                
                <!-- Student Info -->
                <div class="student-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">ชื่อ-นามสกุล:</span>
                            <span class="info-value">${userData.name}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">รหัสนักเรียน:</span>
                            <span class="info-value">${userData.id}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">ชั้นเรียน:</span>
                            <span class="info-value">ม.${userData.room}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">ปีการศึกษา:</span>
                            <span class="info-value">${pee || 'ทั้งหมด'}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 40px;">ลำดับ</th>
                                <th>ชื่อกิจกรรม</th>
                                <th style="width: 120px;">วันที่จัด</th>
                                <th style="width: 60px;">ชั่วโมง</th>
                                <th style="width: 80px;">ประเภท</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRowsHtml}
                        </tbody>
                    </table>
                </div>
                
                <!-- Summary -->
                <div class="summary-section">
                    <div class="summary-title">📊 สรุปชั่วโมงกิจกรรม</div>
                    <div class="summary-grid">
                        ${summaryHtml}
                    </div>
                    <div class="total-box">
                        <span class="total-label">รวมชั่วโมงกิจกรรมทั้งหมด</span>
                        <span class="total-value">${totalHours} ชั่วโมง</span>
                    </div>
                </div>
                
                <!-- Signatures -->
                <div class="signatures">
                    <div class="sig-row">
                        <div class="sig-box">
                            <div class="sig-line"></div>
                            <div class="sig-name">(นายบุญลือ หนุนนาค)</div>
                            <div class="sig-title">หัวหน้างานกิจกรรมนักเรียน</div>
                        </div>
                        <div class="sig-box">
                            <div class="sig-line"></div>
                            <div class="sig-name">(นางอานุชรา ใจปัญญา)</div>
                            <div class="sig-title">รองผู้อำนวยการกลุ่มบริหารกิจการนักเรียน</div>
                        </div>
                    </div>
                    <div class="sig-center">
                        <div class="sig-box">
                            <div class="sig-line"></div>
                            <div class="sig-name">(นางสาวรสสุคนธ์ อินชัยเขา)</div>
                            <div class="sig-title">ผู้อำนวยการโรงเรียนพิชัย</div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
    `;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(transcriptContent);
    printWindow.document.close();

    printWindow.onload = function () {
        printWindow.focus();
        printWindow.print();
    };
}

document.addEventListener('DOMContentLoaded', function() {
    // Load terms and years
    fetchTermsAndPees().then(resp => {
        if (resp.success) {
            const termSelect = document.getElementById('term-select');
            const peeSelect = document.getElementById('pee-select');
            termSelect.length = 1;
            peeSelect.length = 1;
            resp.terms.forEach(term => {
                const opt = document.createElement('option');
                opt.value = term;
                opt.textContent = term;
                termSelect.appendChild(opt);
            });
            resp.pees.forEach(pee => {
                const opt = document.createElement('option');
                opt.value = pee;
                opt.textContent = pee;
                peeSelect.appendChild(opt);
            });
        }
    });

    function reloadEvents() {
        const term = document.getElementById('term-select').value;
        const pee = document.getElementById('pee-select').value;
        fetchEvents(term, pee).then(renderEvents);
    }

    // Initial load
    reloadEvents();

    // Filter change handlers
    document.getElementById('term-select').addEventListener('change', reloadEvents);
    document.getElementById('pee-select').addEventListener('change', reloadEvents);

    // Print button
    document.getElementById('print-transcript-btn').addEventListener('click', printTranscript);
});
</script>
