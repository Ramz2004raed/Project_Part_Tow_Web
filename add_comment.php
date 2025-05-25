<?php
// ربط قاعدة البيانات
require_once 'config/db.php';

// التحقق أن المستخدم أرسل النموذج باستخدام POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // نحصل على البيانات من النموذج
    $news_id = $_POST['news_id'];
    $username = $_POST['username'];
    $comment = $_POST['comment'];

    // نتحقق أن الحقول ليست فارغة
    if (!empty($news_id) && !empty($username) && !empty($comment)) {

        // نهرب النصوص لحمايتها من أي أكواد ضارة
        $news_id = intval($news_id); // تأكد أنه رقم فقط
        $username = mysqli_real_escape_string($conn, $username);
        $comment = mysqli_real_escape_string($conn, $comment);

        // نكتب أمر الإدخال في قاعدة البيانات
        $sql = "INSERT INTO comments (news_id, username, comment) 
                VALUES ('$news_id', '$username', '$comment')";

        // ننفذ الأمر
        if (mysqli_query($conn, $sql)) {
            // إذا نجح الحفظ، نعيد المستخدم لصفحة الخبر مع التعليق الجديد
            header("Location: details.php?id=$news_id");
            exit;
        } else {
            echo "حدث خطأ أثناء حفظ التعليق: " . mysqli_error($conn);
        }

    } else {
        echo "يرجى تعبئة كل الحقول.";
    }

} else {
    // إذا دخل المستخدم مباشرة للصفحة بدون إرسال النموذج
    echo "لا يمكنك الدخول لهذه الصفحة مباشرة.";
}
?>
