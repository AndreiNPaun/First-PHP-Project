<?php 
session_start();
$title = 'Kick⚽ff - Delete Team';
require '../templates/header.php'; 

//Checks if session variable is set
if (isset($_SESSION['loggedin'])){

    //Retrieves user column data based on the session id
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();
    
    //Checks user access status
    if ($login['access'] === 'owner' || $login['access'] === 'admin'){

        //If there is an ID set
        if (isset($_GET['id'])) {

            //Query DB for team column based on the ID stored in the URL
            $stmt = $pdo->prepare('SELECT * FROM team WHERE id = ' . $_GET['id']);
            $stmt->execute();
            $team = $stmt->fetch();

            //If submit has been pressed, delete team record based on the ID from the URL
            if (isset($_POST['submit'])) {
                $stmt = $pdo->prepare('DELETE FROM team WHERE id = :id');
            
                $values = [
                    'id' => $_GET['id']
                ];
                $stmt->execute($values);
                    
                header('location: teams.php');
            }
            
            //Display a warning message before deleting the record
            else {
                echo 'Are you sure you wish to delete <b>' . $team['name'] . '</b> team?';
                ?>
                <form action ="" method="POST">
                    <input type="submit" name="submit" value="Delete" />
                </form>
                <?php
            }
        }

        //If ID is not present in the URL redirect to team page
        else{
            header('location: teams.php');
        }
    }
    //if user access is basic redirect back to homepage
    else{
        header('location: /');
    }
}

//if session variable isn't set redirect to homepage
else{
    header('location: /');
}

require '../templates/footer.php';