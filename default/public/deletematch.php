<?php 
session_start();
$title = 'Kick⚽ff - Delete Match';
require '../templates/header.php'; 
require '../database.php';

//Checks if session variable is set
if (isset($_SESSION['loggedin'])){

    //Retrieves user column data based on the session id
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();

    $stmtGame = $pdo->prepare('SELECT * FROM match WHERE id = ' . $_GET['id']);
    $match = $stmtGame->fetch(); 
    
    //If user access is owner or admin, will be able to access delete match page
    if ($login['access'] === 'owner' || $login['access'] === 'admin'){

        //If ID is set on the URL, delete query will run
        if (isset($_GET['id'])){
            //Delete function that deletes both the recorded match and all the comments made on that match
            if (isset($_POST['submit'])) {
                $stmt = $pdo->prepare('DELETE FROM game WHERE id = ' . $_GET['id']);
                $stmtCom = $pdo->prepare('DELETE FROM comment WHERE game_id = ' . $_GET['id']);

                $stmt->execute();
                $stmtCom->execute();
            
                header('location: matches.php');
            }
            //Delete validation form and warning
            else {
                echo 'Are you sure you wish to delete <b>' . $match['name'] . '</b> match? All comments posted on a match page will get deleted as well.';
                ?>
                <form action ="" method="POST">
                    <input type="submit" name="submit" value="Delete" />
                </form>
                <?php
            }
        }

        //If ID is not set on the URL return to match list
        else {
            header('location: matches.php');
        }

    }
    
    //If user access is basic, user will be redirected to homepage
    else {
        header('location: /');
    }
}

//if session variable isn't set redirect to homepage
else {
    header('location: /');
}

require '../templates/footer.php';