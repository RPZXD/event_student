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
            <h1 class="m-0"><?php echo $global['nameschool']; ?> <span class="text-blue-600">| ครู</span></h1>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <section class="content">
            <div class="container-fluid">
                <!-- เนื้อหาสำหรับครู -->
                <div class="alert alert-success"> ยินดีต้อนรับเข้าสู่ระบบ
                </div>
                <!-- คู่มือการใช้งานสำหรับครู -->
            <!-- ขั้นตอนการสร้างรหัสกิจกรรม (สำหรับครู) -->
            <div class="mt-6 max-w-2xl mx-auto bg-white rounded-lg shadow p-6 border border-blue-200">
                    <h2 class="text-xl font-bold text-blue-700 mb-4 flex items-center gap-2">
                        📝 ขั้นตอนการสร้างรหัสกิจกรรม (สำหรับครู)
                    </h2>
                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500 text-lg">1️⃣</span>
                            <span>
                                <b>เข้าสู่ระบบในฐานะครู</b> ผ่านหน้าเข้าสู่ระบบของระบบกิจกรรม
                            </span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-green-500 text-lg">2️⃣</span>
                            <span>
                                <b>ไปที่เมนู "รายการกิจกรรม"</b> แล้วคลิกปุ่ม <span class="font-semibold text-blue-600">"สร้างกิจกรรม"</span>
                            </span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-yellow-500 text-lg">3️⃣</span>
                            <span>
                                <b>กรอกข้อมูลกิจกรรม</b> เช่น ชื่อกิจกรรม รายละเอียด วันที่จัด จำนวนชั่วโมง ประเภทกิจกรรม และจำนวนสูงสุดที่ลงทะเบียนได้ (ถ้ามี)
                            </span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-purple-500 text-lg">4️⃣</span>
                            <span>
                                <b>กด "สร้างกิจกรรม"</b> ระบบจะสร้างรหัสกิจกรรมและ QR Code ให้โดยอัตโนมัติ
                            </span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-pink-500 text-lg">5️⃣</span>
                            <span>
                                <b>ดาวน์โหลดหรือแสดง QR Code</b> เพื่อให้นักเรียนใช้สำหรับเช็คอินเข้าร่วมกิจกรรม
                            </span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-indigo-500 text-lg">6️⃣</span>
                            <span>
                                <b>ตรวจสอบ/แก้ไข/ลบกิจกรรม</b> ได้จากตารางกิจกรรมในหน้าเดียวกัน
                            </span>
                        </li>
                    </ol>
                    <div class="mt-4 text-blue-600 flex items-center gap-2">
                        <span>💡</span>
                        <span>
                            <b>หมายเหตุ:</b> หากกิจกรรมจำกัดจำนวน ระบบจะสร้างรหัสเฉพาะสำหรับแต่ละคนโดยอัตโนมัติ
                        </span>
                    </div>
                </div>
                <!-- เพิ่มเนื้อหาอื่นๆที่ต้องการ -->
            <!-- จบคู่มือ -->
            </div>
        </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('../footer.php');?>
</div>
<!-- ./wrapper -->


<script>

</script>
<?php require_once('script.php');?>
</body>
</html>
