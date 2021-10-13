<?php 
session_start();
$title = 'Kick⚽ff - Delete User';
require '../templates/header.php'; 
require '../database.php';

//Checks if session variable is set
if (isset($_SESSION['loggedin'])){

    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();
    
    if ($login['access'] === 'owner'){
        $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_GET['id']);
        $stmt->execute();
        $user = $stmt->fetch();
        if (isset($_GET['id'])) {
            if (isset($_POST['submit'])){
            //Delete all user comments first
            $stmtCom = $pdo->prepare('DELETE FROM comment WHERE user_id = ' . $_GET['id']);
            $stmtCom->execute();

            //Delete user account
            $stmtUser = $pdo->prepare('DELETE FROM user WHERE id = ' . $_GET['id']);
            $stmtUser->execute();

            header('location: accounts.php');
            }

            //Delete validation form and warning
            else {
                echo 'Are you sure you wish to delete <b>' . $user['username'] . '</b> account? All comments will be deleted alongside the account.';
                ?>
                <form action ="" method="POST">
                    <input type="submit" name="submit" value="Delete" />
                </form>
                <?php
            }
        }
        //If ID is not set redirect to admin page
        else {
            header('location: admin.php');
        }
    }
    //if user access is not owner, redirect to homepage
    else{
        header('location: /');
    }
}

//if session variable isn't set redirect to homepage
else{
    header('location: /');
}

require '../templates/footer.php';