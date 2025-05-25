<?php
$conn = new mysqli("localhost", "root", "", "project_part_tow_web");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];


    $sql = "INSERT INTO user (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    mysqli_query($conn, $sql);

    echo "<script>alert('تم إضافة المستخدم');</script>";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة مستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px gray;
        }
        h2 {
            color: darkgray;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>إضافة مستخدم جديد</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">الاسم</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الدور</label>
                <select name="role" class="form-control" required>
                    <option value="admin">مدير</option>
                    <option value="editor">محرر</option>
                    <option value="author">مؤلف</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">إضافة</button>
        </form>
    </div>
</body>
</html>
