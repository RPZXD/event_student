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
                        <div class="mb-4">
                            <label for="activity-select" class="block text-sm font-medium text-gray-700">เลือกกิจกรรม:</label>
                            <select id="activity-select" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md text-center">
                                <option value="">-- เลือกกิจกรรม --</option>
                                <!-- กิจกรรมจะถูกเติมโดย JS -->
                            </select>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="participant-table" class="min-w-full bg-white border border-gray-200 rounded shadow display">
                                <thead>
                                    <tr class="bg-blue-100 text-blue-900">
                                        <th class="px-4 py-2 text-center">🔢 #</th>
                                        <th class="px-4 py-2 text-center">👤 ชื่อ-นามสกุล</th>
                                        <th class="px-4 py-2 text-center">🏛️ ชั้น</th>
                                        <th class="px-4 py-2 text-center">📅 วันที่ลงทะเบียน</th>
                                    </tr>
                                </thead>
                                <tbody id="participant-table-body">
                                    <!-- ข้อมูลผู้ลงทะเบียนจะถูกเติมโดย JS -->
                                </tbody>
                            </table>
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
<!-- DataTables CSS/JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const activitySelect = document.getElementById('activity-select');
    const participantTableBody = document.getElementById('participant-table-body');
    const participantTable = $('#participant-table');
    let dataTable = null;

    // ดึงกิจกรรมที่ครูสร้าง
    fetch('../controllers/ActivityController.php?action=teacher_activities')
        .then(res => res.json())
        .then(data => {
            if (data.success && Array.isArray(data.activities)) {
                data.activities.forEach(act => {
                    const opt = document.createElement('option');
                    opt.value = act.id;
                    opt.textContent = act.title;
                    activitySelect.appendChild(opt);
                });
            }
        });

    // เริ่มต้น DataTable
    function initDataTable() {
        if (dataTable) {
            dataTable.clear().draw();
        } else {
            dataTable = participantTable.DataTable({
                searching: false,
                paging: false,
                info: false,
                ordering: false,
                language: {
                    emptyTable: "ไม่มีข้อมูลผู้ลงทะเบียน"
                },
                columnDefs: [
                    { targets: 0, className: 'text-center' },
                    { targets: 1, className: 'text-left' },
                    { targets: 2, className: 'text-center' },
                    { targets: 3, className: 'text-center' }
                ]
            });
        }
    }

    // เมื่อเลือกกิจกรรม
    activitySelect.addEventListener('change', function() {
        const activityId = this.value;
        if (!dataTable) initDataTable();
        dataTable.clear().draw();
        if (!activityId) return;
        fetch(`../controllers/ActivityController.php?action=participants&activity_id=${activityId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.participants)) {
                    if (data.participants.length === 0) {
                        dataTable.clear().draw();
                        return;
                    }
                    data.participants.forEach((p, idx) => {
                        dataTable.row.add([
                            idx + 1,
                            p.Stu_name,
                            p.Stu_class,
                            p.checked_in_at
                        ]);
                    });
                    dataTable.draw();
                } else {
                    dataTable.clear().draw();
                }
            });
    });

    // เรียกใช้ DataTable เมื่อโหลดหน้า
    $(document).ready(function() {
        initDataTable();
    });
});
</script>
<?php require_once('script.php'); ?>
</body>
</html>
