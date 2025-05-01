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
            <h1 class="m-0"><?php echo $global['nameschool']; ?></h1>
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
      <div class="max-w-2xl mx-auto mt-10 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-2xl font-semibold text-green-600 mb-4 flex items-center gap-2">
          📅 ปฏิทินกิจกรรม
        </h3>
        <!-- ตัวอย่างปฏิทินแบบเรียบง่าย -->
        <div class="overflow-x-auto">
          <table class="min-w-full text-center border border-gray-200">
            <thead>
              <tr class="bg-green-100">
                <th class="py-2 px-3 border">วันที่</th>
                <th class="py-2 px-3 border">ชื่อกิจกรรม</th>
                <th class="py-2 px-3 border">ผู้รับผิดชอบ</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="py-2 px-3 border">5 มิ.ย. 2567</td>
                <td class="py-2 px-3 border">กิจกรรมวันสิ่งแวดล้อมโลก 🌱</td>
                <td class="py-2 px-3 border">ครูสมชาย</td>
              </tr>
              <tr class="bg-gray-50">
                <td class="py-2 px-3 border">12 มิ.ย. 2567</td>
                <td class="py-2 px-3 border">แข่งขันตอบปัญหาวิทยาศาสตร์ 🔬</td>
                <td class="py-2 px-3 border">ครูสุภาพร</td>
              </tr>
              <tr>
                <td class="py-2 px-3 border">20 มิ.ย. 2567</td>
                <td class="py-2 px-3 border">กิจกรรมกีฬาโรงเรียน 🏆</td>
                <td class="py-2 px-3 border">ครูประเสริฐ</td>
              </tr>
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
</div>
<!-- ./wrapper -->


<script>

</script>
<?php require_once('script.php');?>
</body>
</html>
