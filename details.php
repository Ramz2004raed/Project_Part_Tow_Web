<?php
require_once 'config/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: Front_Page.php");
    exit;
}

$id = intval($_GET['id']);

// جلب تفاصيل الخبر
$sql = "SELECT news.*, category.name AS category_name FROM news 
        LEFT JOIN category ON news.category_id = category.id
        WHERE news.id = $id AND news.status = 'approved'";

$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "لا يوجد خبر بهذا الرقم.";
    exit;
}

$news = $result->fetch_assoc();

// جلب التعليقات
$comments_sql = "SELECT * FROM comments WHERE news_id = $id ORDER BY created_at DESC";
$comments_result = $conn->query($comments_sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($news['title']); ?> - أخبار العالم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .article-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin-top: 30px;
        }
        .article-image {
            width: 100%;
            max-height: 450px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .article-content {
            line-height: 1.8;
            font-size: 18px;
        }
        .article-meta {
            color: #6c757d;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .vote-buttons button {
            margin-left: 10px;
        }
        .comment {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="container article-container">
    <h1 class="mb-3"><?php echo htmlspecialchars($news['title']); ?></h1>

    <div class="article-meta">
        <span><?php echo date("d F Y", strtotime($news['dateposted'])); ?></span>
        <span class="mx-2">|</span>
        <span><?php echo htmlspecialchars($news['category_name']); ?></span>
    </div>

    <img src="Uploads/<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" class="article-image">

    <div class="article-content mb-4">
        <?php echo nl2br(htmlspecialchars($news['body'])); ?>
    </div>

    <!-- التعليقات -->
    <div id="comments-section" class="comments-section mt-5">
        <h3 class="mb-4">التعليقات</h3>

        <?php if ($comments_result->num_rows > 0): ?>
            <?php while($comment = $comments_result->fetch_assoc()): ?>
                <div class="comment mb-3 p-3 border rounded">
                    <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">لا توجد تعليقات بعد. كن أول من يعلق!</p>
        <?php endif; ?>
    </div>

    <!-- نموذج إضافة تعليق -->
    <div class="add-comment mt-5">
        <h4 class="mb-3">أضف تعليقك</h4>
        <form action="add_comment.php" method="post">
            <input type="hidden" name="news_id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <label for="username" class="form-label">اسم المستخدم</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">التعليق</label>
                <textarea name="comment" id="comment" rows="4" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">إرسال التعليق</button>
        </form>
    </div>

    <!-- أزرار الإعجاب تحت نموذج التعليق -->
    <div class="vote-buttons mt-4">
        <button id="like-btn" class="btn btn-success" onclick="vote(<?php echo $id; ?>, 'like')">
            إعجاب 👍 <span id="likes-count"><?php echo $news['likes']; ?></span>
        </button>
        <button id="dislike-btn" class="btn btn-danger" onclick="vote(<?php echo $id; ?>, 'dislike')">
            عدم إعجاب 👎 <span id="dislikes-count"><?php echo $news['dislikes']; ?></span>
        </button>
    </div>
</div>

<!-- AJAX Voting Script -->
<script>
function vote(newsId, type) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "vote.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            document.getElementById("likes-count").textContent = response.likes;
            document.getElementById("dislikes-count").textContent = response.dislikes;
        }
    };
    xhr.send("news_id=" + newsId + "&type=" + type);
}
</script>

</body>
</html>
