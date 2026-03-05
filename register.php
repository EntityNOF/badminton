<?php require_once "header.php"; ?>

    <div class="login">
        <form action="check-register.php" method="post">
            <h2>สมัครสมาชิก</h2>

            <div class="login-group">
                <label for="firstname">ชื่อ</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>

            <div class="login-group">
                <label for="lastname">นามสกุล</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>

            <div class="login-group">
                <label for="phone">เบอร์โทรศัพท์</label>
                <input type="text" id="phone" name="phone">
            </div>

            <div class="login-group">
                <label for="email">อีเมล</label>
                <input type="email" id="email" name="email">
            </div>

            <div class="login-group">
                <label for="gender">เพศ</label>
                <select id="gender" name="gender">
                    <option value="M">ชาย</option>
                    <option value="F">หญิง</option>
                    <option value="other">ไม่ระบุ</option>
                </select>
            </div>

            <div class="login-group">
                <label for="birth_date">วันเกิด</label>
                <input type="date" id="birth_date" name="birth_date">
            </div>

            <div class="login-group">
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="login-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" id="password" name="password" required>
            </div>

            <?php
            // แสดงข้อผิดพลาด
            if (isset($_SESSION['errors_msg'])) {
                echo "<div class='error-message'>" . $_SESSION['errors_msg'] . "</div>";
                unset($_SESSION['errors_msg']);
            }
            ?>

            <div class="login-buttons">
                <input type="submit" value="สมัครสมาชิก">
                <input type="reset" value="ล้างข้อมูล">
            </div>
            <p>มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
        </form>
    </div>

<?php require_once "footer.php"; ?>