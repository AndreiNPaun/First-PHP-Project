<?php 
session_start();
$title = 'Kick⚽ff - User Details';
require '../templates/header.php'; 
require '../database.php';

if (isset($_GET['id'])) {
    //Query DB for data stored within the row requested via GET
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_GET['id']);
    $stmt->execute();
    $user = $stmt->fetch();

    //Find user comments by querying comment using user ID
    $stmtCom = $pdo->prepare('SELECT * FROM comment WHERE user_id = ' . $user['id']);
    $stmtCom->execute();
    $comment = $stmtCom->fetchAll();

    //Display user name, comment, data the comment has been submited, and a link incorporated on the comment
    echo '<h1>Comments</h1>';
    echo '</p>Username: ' . $user['username'] . '</p>';
    echo '</p>Comments: </p>';
    echo '<ul>';
    foreach ($comment as $comments) {
        echo '<li>';
        echo '"<a href="matchdetail.php?id=' . $comments['game_id'] . '">' . $comments['comment'] . '</a>"' . ' - Posted on ' . $comments['datetime'] . '.';
        echo '</li>';
    }
    echo '</ul>';

}

else {
    header('location: matches.php');
}

require '../templates/footer.php';