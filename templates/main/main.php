<?php include __DIR__ . '/../header.php';?>
<!--            <h2>Статья 1</h2>-->
<!--            <p>Всем привет, это текст первой статьи</p>-->
<!--            <hr>-->
<!---->
<!--            <h2>Статья 2</h2>-->
<!--            <p>Всем привет, это текст второй статьи</p>-->
<?php foreach ($articles as $article): ?>
    <h2><a href="/articles/<?=$article->getId();?>"><?= $article->getName();?></a></h2>
    <p><?= $article->getText();?></p>
    <hr>
<?php endforeach; ?>
<?php include __DIR__ .'/../footer.php';?>