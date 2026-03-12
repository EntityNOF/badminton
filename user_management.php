<?php
require_once "header.php";
require_once "config.php";

// Only allow level 3
if (!isset($_SESSION['username']) || !isset($_SESSION['level']) || $_SESSION['level'] != 3) {
    $_SESSION['errors_msg'] = "ไม่อนุญาตให้เข้าถึงหน้านี้";
    header("Location: index.php");
    exit;
}


// Fetch users
$listQuery = "SELECT u.user_id, u.username, u.level, e.firstname, e.lastname FROM systemuser u LEFT JOIN employee e ON u.employee_id = e.employee_id ORDER BY u.user_id ASC";
$listResult = mysqli_query($connect, $listQuery);
?>

<html>
    <link rel="stylesheet" href="style.css">
    <div class="admin-container">
        <br>
        <h2>ระบบจัดการผู้ใช้</h2>
        <br>
            <table>
                <tr>
                    <th class="um-col-id">#</th>
                    <th class="um-col-user">Username</th>
                    <th class="um-col-name">ชื่อ-นามสกุล</th>
                    <th class="um-col-level">Level</th>
                    <th class="um-col-action">Actions</th>
                </tr>
                <?php if ($listResult) { while ($row = mysqli_fetch_assoc($listResult)) { ?>
                <tr class="text-center">
                    <td class="um-col-id"><?php echo $row['user_id']; ?></td>
                    <td class="um-col-user"><?php echo $row['username']; ?></td>
                    <td class="um-col-name"><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
                    <td class="um-col-level"><?php echo $row['level']; ?></td>
                    <td class="um-col-action">
                        <a href="um_edit.php?user_id=<?php echo $row['user_id']; ?>" class="btn-edit">EDIT</a>
                        <?php if ($row['username'] !== $_SESSION['username']) { ?>
                            | <form method="post" action="um_delete.php" class="form-inline-block">
                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                <button type="submit" class="btn-delete">DELETE</button>
                              </form>
                        <?php } ?>
                    </td>
                </tr>
                <?php } } ?>
            </table>
    </div>
</html>

<?php 
if (isset($_SESSION['errors_msg'])) {
    echo "<br><div class='error-message' align='center'>" . $_SESSION['errors_msg'] . "</div>";
    unset($_SESSION['errors_msg']);
}
if (isset($_SESSION['success_msg'])) {
    echo "<br><div class='success-message' align='center'>" . $_SESSION['success_msg'] . "</div>";
    unset($_SESSION['success_msg']);
}
?>

<?php require_once "footer.php"; ?>
