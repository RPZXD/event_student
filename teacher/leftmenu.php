<?php
function createNavItem($href, $iconClass, $text) {
    return '
    <li class="nav-item">
        <a href="' . htmlspecialchars($href) . '" class="nav-link">
            <i class="bi ' . htmlspecialchars($iconClass) . '"></i>
            <p>' . htmlspecialchars($text) . '</p>
        </a>
    </li>';
}


    echo createNavItem('index.php', 'bi-house', 'หน้าหลัก');
    echo createNavItem('create_event.php', 'bi-list-check', 'สร้างกิจกรรม');
    // เพิ่มเมนูเฉพาะครู
    echo createNavItem('event_list.php', 'bi-person-badge', 'รายชื่อผู้ลงทะเบียนกิจกรรม');
    echo createNavItem('../logout.php', 'bi-box-arrow-right', 'ออกจากระบบ');

?>