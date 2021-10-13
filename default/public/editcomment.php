<?php 
session_start();
$title = 'Kick⚽ff - Edit Comment';
require '../templates/header.php'; 
require '../database.php';

//Checks if session variable is set
if (isset($_SESSION['loggedin'])){

    //date object to be inserted into a newly modified comments
    $date = new DateTime();

    $stmt = $pdo->prepare('SELECT * FROM comment WHERE id = :id');
    $values = [
        'id' => $_GET['id']
    ];
    $stmt->execute($values);
    $comment = $stmt->fetch();

    if (isset($_GET['id'])) {
    
        //Query needed to send user back to match details page
        $stmtGame = $pdo->prepare('SELECT * FROM game WHERE id = :id');
        $values = [
            'id' => $comment['game_id']
        ];
        $stmtGame->execute($values);
        $match = $stmtGame->fetch();

        //If submit is pressed, updated the comment with new information
        if (isset($_POST['submit'])){
            $stmt = $pdo->prepare('UPDATE comment SET comment = :comment, user_id = :user_id, game_id = :game_id, 
                            datetime = :datetime  WHERE id = :id');
            $values = [
                'id' => $_GET['id'],
                'comment' => $_POST['comment'],
                'user_id' => $_POST['user_id'],
                'game_id' => $_POST['game_id'],
                'datetime' => $date->format('Y-m-d H:i:s')
            ];
            $stmt->execute($values);

            header('location: matchdetail.php?id=' . $match['id']);
        }
        //User comment edit form
        else{ ?>
            <h1>Edit Comment</h1>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
                <input type="hidden" name="user_id" value="<?php echo $comment['user_id'] ?>" />
                <input type="hidden" name="game_id" value="<?php echo $comment['game_id'] ?>" />
                <label>Comment</label>
                <input type="text" name="comment" value="<?php echo $comment['comment'] ?>" />

                <input type="submit" name="submit" value="Submit" />
            </form>
        <?php    
        }
    }

    else {
        header('location: matches.php');
    }
    
}

else{
    header('location: /');
}

require '../templates/footer.php';