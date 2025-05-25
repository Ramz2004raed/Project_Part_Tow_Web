<?php
session_start();
include '../config/db.php';

if ($_SESSION['user_role'] != 'author') {
    header("Location: login.php");
    exit();
}

$author_id = $_SESSION['user_id'];

$sql = "SELECT news.*, category.name AS category_name FROM news INNER JOIN category ON news.category_id = category.id WHERE author_id = $author_id ORDER BY dateposted DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المؤلف</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .sidebar {
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            padding-top: 30px;
            position: fixed;
            width: 250px;
            top: 0;
            right: 0;
        }

        .sidebar h4 {
            font-size: 1.5rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 15px 25px;
            display: block;
        }

        .sidebar a:hover {
            background-color: #3498db;
            padding-right: 30px;
        }

        .main-content {
            margin-right: 270px;
            padding: 40px 20px;
        }

        .main-content h1 {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .table th, .table td {
            text-align: center;
            font-size: 1rem;
        }

        .table thead {
            background-color: #2c3e50;
            color: #fff;
        }

        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #218838;
        }

        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>لوحة التحكم</h4>
        <a href="author_dashboard.php">لوحة تحكم المؤلف</a>
        <a href="add_news.php">إضافة خبر جديد</a>
        <a href="../Front_Page.php">تسجيل الخروج</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h1>لوحة تحكم المؤلف</h1>

            <div class="d-flex justify-content-end mb-4">
                <a href="add_news.php" class="btn btn-success">إضافة خبر جديد</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>العنوان</th>
                            <th>التصنيف</th>
                            <th>تاريخ النشر</th>
                            <th>الحالة</th>
                            <th>خيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['category_name']; ?></td>
                                <td><?php echo date("Y-m-d", strtotime($row['dateposted'])); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'approved') { ?>
                                        <span class="badge bg-success">مقبول</span>
                                    <?php } elseif ($row['status'] == 'pending') { ?>
                                        <span class="badge bg-warning">قيد الانتظار</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">مرفوض</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="edit_news.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">تعديل</a>
                                    <a href="delete_news.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">حذف</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>