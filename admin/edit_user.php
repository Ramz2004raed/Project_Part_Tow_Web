<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../Front_Page.php");
    exit();
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $sql = "SELECT * FROM user WHERE id = $userId";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
} else {
    header("Location: manage_users.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($password == '') {
        $password = $user['password'];
    }

    $sql = "UPDATE user SET name = '$name', email = '$email', password = '$password', role = '$role' WHERE id = $userId";
    mysqli_query($conn, $sql);

    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل المستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>تعديل المستخدم</h1>
        <form method="POST" action="edit_user.php?id=<?php echo $user['id']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">الاسم</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور (اختياري)</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">الدور</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="editor" <?php if ($user['role'] == 'editor') echo 'selected'; ?>>محرر</option>
                    <option value="author" <?php if ($user['role'] == 'author') echo 'selected'; ?>>كاتب</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">تحديث المستخدم</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>