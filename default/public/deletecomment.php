<?php 
session_start();
require '../templates/header.php'; 
require '../database.php';

//Checks if session variable is set
if (isset($_SESSION['loggedin'])){
    
    //Retrieves user column data based on the session id
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();

    if (isset($_GET['id'])){
        
        //Query to make the Foreign Key stored in comment table accessible
        $stmtFK = $pdo->prepare('SELECT * FROM comment WHERE id = :id');
        $valuesFK = [
            'id' => $_GET['id']
        ];
        $stmtFK->execute($valuesFK);
        $foreignKey = $stmtFK->fetch();
    
        //Query needed to send user back to match details page
        $stmt = $pdo->prepare('SELECT * FROM game WHERE id = :id');
        $values = [
            'id' => $foreignKey['game_id']
        ];
        $stmt->execute($values);
        $match = $stmt->fetch();
    
        //Delete query, only executed once the login access of user has been confirmed
        $stmtCom = $pdo->prepare('DELETE FROM comment WHERE id = :id');
        $valuesCom = [
            'id' => $_GET['id']
        ];
        
        //Checks if user access is owner or admin and if so delete the comment
        if ($login['access'] === 'owner' || $login['access'] === 'admin'){
            //Delete function
            if (isset($_POST['submit'])){
                $stmtCom->execute($valuesCom);
                header('location: matchdetail.php?id=' . $match['id']);
            }
            //Delete validation form and warning
            else {
                echo 'Are you sure you wish to delete this comment?';
                ?>
                <form action ="" method="POST">
                    <input type="submit" name="submit" value="Delete" />
                </form>
                <?php
            }
        }
        //Checks if user access is set to basic
        else if ($login['access'] === 'basic'){
            //Checks to see if logged on user ID is stored in the comment table, allowing user to delete his comment
            if ($login['id'] === $foreignKey['user_id']){
                //Delete function
                if (isset($_POST['submit'])){
                    $stmtCom->execute($valuesCom);
                    header('location: matchdetail.php?id=' . $match['id']);
                }
                //Delete validation form and warning
                else {
                    echo 'Are you sure you wish to delete this comment?';
                    ?>
                    <form action ="" method="POST">
                        <input type="submit" name="submit" value="Delete" />
                    </form>
                    <?php
                }

            }
            //If comment doesn't belong to logged on user, redirect
            else {
                header('location: matchdetail.php?id=' . $match['id']);
            }
        }
        else{
            header('location: /');
        }
    }
    else{
        header('location: matches.php');
    }
}
else{
    header('location: /');
}

require '../templates/footer.php';