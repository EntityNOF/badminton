<?php require_once "header.php"; ?>

    <div class="slideshow">
        <a href="court_booking.php"><img src="images/slide.png" alt="Logo"></a>
    </div>

    <!-- ส่วนต้อนรับ -->
    <div class="welcome-section">
        <div class="welcome-content">
            <h1>ยินดีต้อนรับสู่ ศูนย์จองสนามแบดมินตัน</h1>
            <p>สถานที่อีกหนึ่งแห่งสำหรับการเล่นแบดมินตันแบบเป็นมืออาชีพ</p>
            <p>เรามีสนามแบดมินตันที่ได้มาตรฐานสากล พร้อมอุปกรณ์ที่ดีที่สุด</p>
            <a href="court_booking.php" class="btn-primary">จองสนามเลย</a>
        </div>
    </div>

    <!-- ส่วนจุดเด่น -->
    <div class="features-section">
        <h2>ทำไมต้องเลือกเรา</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">⏰</div>
                <h3>จองได้ตลอดเวลา</h3>
                <p>จองสนามได้ตลอด 24 ชั่วโมง ผ่านทางเว็บไซต์ของเรา</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏸</div>
                <h3>สนามมาตรฐาน</h3>
                <p>สนามทั้งหมดได้มาตรฐานสากล พร้อมอุปกรณ์ครบครัน</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3>ราคาถูกที่สุด</h3>
                <p>ราคาลดพิเศษสำหรับสมาชิกและการจองหลายชั่วโมง</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>บริการดี</h3>
                <p>บริการจากพนักงานที่มีคุณภาพและยิ้มแย้มอยู่เสมอ</p>
            </div>
        </div>
    </div>

    <!-- ส่วนเรียกร้องให้ลงมือจอง -->
    <div class="cta-section">
        <h2>พร้อมที่จะเล่นแบดมินตันแล้วหรือ?</h2>
        <p>ไปดูสนามของเรา และจองสนามโปรดของคุณเลย!</p>
        <?= isset($_SESSION['username']) ? '<a href="court_booking.php" class="btn-primary">ไปจองสนาม</a>' : '<a href="login.php" class="btn-primary">เข้าสู่ระบบเพื่อจอง</a>' ?>
    </div>

<?php require_once "footer.php"; ?>