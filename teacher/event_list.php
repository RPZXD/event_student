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
                            <select id="activity-select" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">-- เลือกกิจกรรม --</option>
                                <!-- กิจกรรมจะถูกเติมโดย JS -->
                            </select>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="participant-table" class="min-w-full bg-white border border-gray-200 rounded shadow display">
                                <thead>
                                    <tr class="bg-blue-100 text-blue-900">
                                        <th class="px-4 py-2 text-left">🔢 #</th>
                                        <th class="px-4 py-2 text-left">👤 ชื่อ-นามสกุล</th>
                                        <th class="px-4 py-2 text-left">🏛️ ชั้น</th>
                                        <th class="px-4 py-2 text-left">📅 วันที่ลงทะเบียน</th>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const activitySelect = document.getElementById('activity-select');
    const participantTableBody = document.getElementById('participant-table-body');

    // โหลดกิจกรรมที่ครูสร้าง
    function loadTeacherActivities() {
        fetch('../controllers/ActivityController.php?action=getTeacherActivities')
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.activities)) {
                    data.activities.forEach(activity => {
                        const option = document.createElement('option');
                        option.value = activity.id;
                        option.textContent = activity.title;
                        activitySelect.appendChild(option);
                    });
                }
            });
    }

    // โหลดผู้ลงทะเบียนสำหรับกิจกรรมที่เลือก
    activitySelect.addEventListener('change', function() {
        const activityId = this.value;
        if (activityId) {
            fetch(`../controllers/ActivityController.php?action=getParticipants&activity_id=${activityId}`)
                .then(res => res.json())
                .then(data => {
                    participantTableBody.innerHTML = '';
                    if (data.success && Array.isArray(data.participants)) {
                        data.participants.forEach((participant, index) => {
                            const row = `
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-4 py-2">${index + 1}</td>
                                    <td class="px-4 py-2">${participant.name}</td>
                                    <td class="px-4 py-2">${participant.email}</td>
                                    <td class="px-4 py-2">${new Date(participant.registered_at).toLocaleDateString('th-TH')}</td>
                                </tr>
                            `;
                            participantTableBody.innerHTML += row;
                        });
                    }
                });
        } else {
            participantTableBody.innerHTML = '';
        }
    });

    loadTeacherActivities();
});
</script>
<?php require_once('script.php');?>
</body>
</html>
