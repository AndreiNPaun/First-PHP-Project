<?php 
session_start();
$title = 'Kick⚽ff - Login';
require '../templates/header.php'; 
require '../database.php';

//Checks if session variable is set
if (!isset($_SESSION['loggedin'])){
    if (isset($_POST['submit'])){
        //DB query to find out if the user trying to log in actually exists
        $stmt = $pdo->prepare('SELECT * FROM user WHERE username = :username');
        $values = [
            'username' => $_POST['username']
        ];
        $stmt->execute($values);
        $login = $stmt->fetch();
        
        //Log in validation related to the query above
        if ($_POST['username'] === $login['username']){
            
            //Heads back to index.php after the log in has been successful

            if (password_verify($_POST['password'], $login['password'])){
                $_SESSION['loggedin'] = $login['id'];
                
                header('location: /');
            }

            else{
                echo 'Incorrect details, please try again.';
            }
        }

        //If fields are empty or details are incorrect, refresh page
        else{
            header('refresh: 3; url=login.php');
            echo 'Incorrect details, please try again.';
        }
    }
    
    //If form hasn't been submited, display it
    else{
    ?>
        <h1>Login</h1>
        <form action="" method="POST">
            <label>Username</label>
            <input type="text" name="username" />
            <label>Password</label>
            <input type="password" name="password" />
    
            <input type="submit" name="submit" value="Submit"/>
        </form>
    
    <?php
    }
}

//If user is already logged in, redirect him to homepage
else{
    header('location: /');
}


require '../templates/footer.php';