<?php 
// Read configuration from JSON file
$config = json_decode(file_get_contents('config.json'), true);
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
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <section class="content">
      <div class="flex flex-col items-center justify-center min-h-[200px] bg-white rounded-lg shadow-md p-8 mt-8">
        <h2 class="text-3xl font-bold text-blue-600 mb-4 flex items-center gap-2">
          🎓 ระบบลงทะเบียนกิจกรรมนักเรียน
        </h2>
        <p class="text-lg text-gray-700 mb-2">
          ยินดีต้อนรับสู่ระบบลงทะเบียนกิจกรรมสำหรับนักเรียนทุกคน! 📝
        </p>
        <p class="text-md text-gray-500">
          กรุณาเลือกกิจกรรมที่คุณสนใจและลงทะเบียนเพื่อเข้าร่วมกิจกรรมต่าง ๆ ของโรงเรียนเรา 🚀
        </p>
      </div>
      <!-- ปฏิทินกิจกรรม -->
      <div class="max-w-6xl mx-auto mt-10 bg-white rounded-lg shadow-md p-6 mb-8 border border-gray-200">
        <h3 class="text-2xl font-semibold text-green-600 mb-4 flex items-center gap-2">
          📅 ปฏิทินกิจกรรม
        </h3>
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <div class="overflow-x-auto">
          <table id="activity-table" class="min-w-full text-center border border-gray-200 rounded-lg shadow-sm">
            <thead>
              <tr class="bg-green-100 text-green-900">
                <th class="py-2 px-3 border text-center">📅 วันที่</th>
                <th class="py-2 px-3 border text-center">🎯 ชื่อกิจกรรม</th>
                <th class="py-2 px-3 border text-center">👩‍🏫 ผู้รับผิดชอบ</th>
                <th class="py-2 px-3 border text-center">⏰ ชั่วโมง</th>
                <th class="py-2 px-3 border text-center">👥 จำนวนที่รับ</th>
                <th class="py-2 px-3 border text-center">🏷️ ประเภท</th>
              </tr>
            </thead>
            <tbody id="activity-table-body">
              <!-- ข้อมูลจะถูกเติมโดย JS -->
            </tbody>
          </table>
        </div>
        <div class="text-sm text-gray-400 mt-2">* ตัวอย่างกิจกรรมที่ครูได้สร้างไว้</div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('footer.php');?>

<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  fetch('controllers/EventController.php?order_by=event_date_desc')
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('activity-table-body');
      tbody.innerHTML = '';
      if (data.success && Array.isArray(data.events)) {
        data.events.forEach(ev => {
          const thaiDate = new Date(ev.event_date).toLocaleDateString('th-TH', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          });
          // Progress bar
          let progressBar = '';
          if (ev.max_students && ev.max_students > 0) {
            const current = ev.current_students || 0;
            const percent = Math.min((current / ev.max_students) * 100, 100);
            progressBar = `
              <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                <div class="bg-green-500 h-2 rounded-full transition-all" style="width: ${percent}%;"></div>
              </div>
            `;
          }
          tbody.innerHTML += `
            <tr class="hover:bg-green-50 transition">
              <td class="py-2 px-3 border">${thaiDate}</td>
              <td class="py-2 px-3 border text-left">🎯 <span class="font-semibold">${ev.title}</span></td>
              <td class="py-2 px-3 border">👩‍🏫 ${ev.teacher_name || ev.teacher_id}</td>
              <td class="py-2 px-3 border">⏰ ${ev.hours ?? '-'}</td>
              <td class="py-2 px-3 border">
                👥 ${ev.max_students && ev.max_students > 0 ? (ev.current_students || 0) + ' / ' + ev.max_students : 'ไม่จำกัด'}
                ${progressBar}
              </td>
              <td class="py-2 px-3 border">🏷️ ${ev.category ?? '-'}</td>
            </tr>
          `;
        });
      } else {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-gray-400">ไม่พบกิจกรรม</td></tr>`;
      }
      // เรียกใช้ DataTables หลังเติมข้อมูล
      $('#activity-table').DataTable({
        order: [[0, 'desc']],
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
});
</script>
<?php require_once('script.php'); ?>
</body>
</html>
