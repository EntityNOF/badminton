<?php
require_once "header.php";
require_once "config.php";

// ตรวจสอบสิทธิ์ - เฉพาะ admin (level 3)
if (!isset($_SESSION['username']) || !isset($_SESSION['level']) || $_SESSION['level'] != 3) {
    $_SESSION['errors_msg'] = "ไม่อนุญาตให้เข้าถึงหน้านี้";
    header("Location: index.php");
    exit;
}

// ดึง user_id จาก GET
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    $_SESSION['errors_msg'] = "ไม่พบผู้ใช้ที่ต้องการแก้ไข";
    header("Location: user_management.php");
    exit;
}

// ดึงข้อมูลผู้ใช้
$editQ = "SELECT u.user_id, u.username, u.level, u.employee_id,
                 e.firstname, e.lastname, e.phone, e.email, e.gender, e.birth_date, e.address
          FROM systemuser u 
          LEFT JOIN employee e ON u.employee_id = e.employee_id 
          WHERE u.user_id = " . $user_id;
$editR = mysqli_query($connect, $editQ);

if (!$editR || mysqli_num_rows($editR) == 0) {
    $_SESSION['errors_msg'] = "ไม่พบผู้ใช้ที่ต้องการแก้ไข";
    header("Location: user_management.php");
    exit;
}
$editRow = mysqli_fetch_assoc($editR);

// จัดการอัปเดตข้อมูลเมื่อ POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $firstname  = $_POST['firstname'];
    $lastname   = $_POST['lastname'];
    $phone      = $_POST['phone'];
    $email      = $_POST['email'];
    $gender     = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $address    = $_POST['address'];
    $level      = intval($_POST['level']);

    if (empty($username)) {
        $_SESSION['errors_msg'] = "ชื่อผู้ใช้ห้ามว่าง";
        header("Location: um_edit.php?user_id=" . $user_id);
        exit;
    }

    // ตรวจสอบ username ซ้ำ
    $checkQ = "SELECT user_id FROM systemuser WHERE username = '$username' AND user_id <> $user_id";
    $checkR = mysqli_query($connect, $checkQ);
    if ($checkR && mysqli_num_rows($checkR) > 0) {
        $_SESSION['errors_msg'] = "มีชื่อผู้ใช้นี้อยู่ในระบบแล้ว";
        header("Location: um_edit.php?user_id=" . $user_id);
        exit;
    }

    // อัปเดต systemuser
    if ($password === '') {
        $updateQ = "UPDATE systemuser SET username = '$username', level = $level WHERE user_id = $user_id";
    } else {
        $updateQ = "UPDATE systemuser SET username = '$username', password = '$password', level = $level WHERE user_id = $user_id";
    }
    mysqli_query($connect, $updateQ);

    // อัปเดต employee (ชื่อ-นามสกุล และข้อมูลส่วนตัว)
    $bdate_sql = $birth_date ? "'$birth_date'" : 'NULL';
    $empQ = "SELECT employee_id FROM systemuser WHERE user_id = $user_id";
    $empR = mysqli_query($connect, $empQ);
    $empRow = mysqli_fetch_assoc($empR);
    if ($empRow && $empRow['employee_id']) {
        mysqli_query($connect, "UPDATE employee SET
            firstname  = '$firstname',
            lastname   = '$lastname',
            phone      = '$phone',
            email      = '$email',
            gender     = '$gender',
            birth_date = $bdate_sql,
            address    = '$address'
            WHERE employee_id = " . $empRow['employee_id']);
    }

    $_SESSION['success_msg'] = "แก้ไขบัญชีเรียบร้อยแล้ว";
    header("Location: user_management.php");
    exit;
}
?>

    <div class="edit-container">
        <h2>แก้ไขบัญชีผู้ใช้</h2>
        <div class="edit-box">
            <form method="post" action="um_edit.php?user_id=<?php echo $editRow['user_id']; ?>">

                <!-- แสดงข้อผิดพลาด -->
                <?php if (isset($_SESSION['errors_msg'])) { ?>
                    <div class="error-message"><?php echo $_SESSION['errors_msg']; ?></div>
                    <?php unset($_SESSION['errors_msg']); ?>
                <?php } ?>

                <table border="0" style="margin: 0 auto;">
                    <tr>
                        <td width="160">ชื่อผู้ใช้</td>
                        <td><input type="text" name="username" value="<?php echo $editRow['username']; ?>" required></td>
                    </tr>
                    <tr>
                        <td>รหัสผ่านใหม่</td>
                        <td><input type="text" name="password" value="" placeholder="ว่างไว้ถ้าไม่ต้องการเปลี่ยน"></td>
                    </tr>
                    <tr>
                        <td>ชื่อ</td>
                        <td><input type="text" name="firstname" value="<?php echo $editRow['firstname']; ?>"></td>
                    </tr>
                    <tr>
                        <td>นามสกุล</td>
                        <td><input type="text" name="lastname" value="<?php echo $editRow['lastname']; ?>"></td>
                    </tr>
                    <tr>
                        <td>เบอร์โทรศัพท์</td>
                        <td><input type="text" name="phone" value="<?php echo $editRow['phone']; ?>"></td>
                    </tr>
                    <tr>
                        <td>อีเมล</td>
                        <td><input type="email" name="email" value="<?php echo $editRow['email']; ?>"></td>
                    </tr>
                    <tr>
                        <td>เพศ</td>
                        <td>
                            <select name="gender">
                                <option value="M" <?php echo $editRow['gender']=='M' ? 'selected' : ''; ?>>ชาย</option>
                                <option value="F" <?php echo $editRow['gender']=='F' ? 'selected' : ''; ?>>หญิง</option>
                                <option value="other" <?php echo $editRow['gender']=='other' ? 'selected' : ''; ?>>ไม่ระบุ</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>วันเกิด</td>
                        <td><input type="date" name="birth_date" value="<?php echo $editRow['birth_date']; ?>"></td>
                    </tr>
                    <tr>
                        <td>ที่อยู่</td>
                        <td><input type="text" name="address" value="<?php echo $editRow['address']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Level</td>
                        <td>
                            <select name="level">
                                <option value="1" <?php echo $editRow['level'] == 1 ? 'selected' : ''; ?>>1 - ผู้ชม</option>
                                <option value="2" <?php echo $editRow['level'] == 2 ? 'selected' : ''; ?>>2 - ผู้ใช้</option>
                                <option value="3" <?php echo $editRow['level'] == 3 ? 'selected' : ''; ?>>3 - ผู้ดูแล</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="edit-action-cell">
                            <input type="submit" value="บันทึก" class="btn-submit">
                            <a href="user_management.php" class="btn-back">← กลับ</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

<?php require_once "footer.php"; ?>
