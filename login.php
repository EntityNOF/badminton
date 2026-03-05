<?php require_once "header.php"; ?>

    <div class="login">
        <form action="check-login.php" method="post">
            <h2>เข้าสู่ระบบ</h2>

            <div class="login-group">
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="login-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" id="password" name="password" required>
            </div>

            <?php
            // แสดงข้อความสำเร็จ หรือ ข้อผิดพลาด
            if (isset($_SESSION['success_msg'])) {
                echo "<div class='success-message'>" . $_SESSION['success_msg'] . "</div>";
                unset($_SESSION['success_msg']);
            }
            if (isset($_SESSION['errors_msg'])) {
                echo "<div class='error-message'>" . $_SESSION['errors_msg'] . "</div>";
                unset($_SESSION['errors_msg']);
            }
            ?>

            <div class="login-buttons">
                <input type="submit" value="เข้าสู่ระบบ">
                <input type="reset" value="ล้างข้อมูล">
            </div>
            <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
        </form>
    </div>

<?php require_once "footer.php"; ?>