<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_id = intval($_POST['news_id']);
    $type = $_POST['type'];

    if ($type === 'like') {
        $sql = "UPDATE news SET likes = likes + 1 WHERE id = $news_id";
    } elseif ($type === 'dislike') {
        $sql = "UPDATE news SET dislikes = dislikes + 1 WHERE id = $news_id";
    }

    $conn->query($sql);

    // إرجاع عدد الإعجابات وعدم الإعجاب الحالي
    $result = $conn->query("SELECT likes, dislikes FROM news WHERE id = $news_id");
    $row = $result->fetch_assoc();

    echo json_encode($row);
}
?>
