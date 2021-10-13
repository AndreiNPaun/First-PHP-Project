<?php 
session_start();
$title = 'Kick⚽ff - Register';
require '../templates/header.php'; 
require '../database.php';

if (!isset($_SESSION['loggedin'])){

    if (isset($_POST['submit'])){

        //Form cannot be submited if fields are empty
        if ($_POST['username'] !== '' && $_POST['email'] !=='' && $_POST['password'] !== ''){

            //Check if the submited email is of format email@email.com and if not redirect back to register page
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                header('refresh: 4; url=register.php');
                echo 'Invalid email, redirecting to register page.';
            }
            else{
                
                //Password hashing for additional protection on accounts
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                //DB insert query
                $stmt = $pdo->prepare('INSERT INTO user (id, username, email, password, access) 
                                VALUES (:id, :username, :email, :password, :access)');
        
                $values = [
                    'id' => $_POST['id'],
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'password' => $hash,
                    'access' => $_POST['access'],
                ];
    
                $stmt->execute($values);
            
                //After successfuly registering, user will be taken to login page
                header('refresh: 3; url=login.php');
                echo 'Account created.';
            }
        }
        //Error message in case fields are not completed
        else{
            header('refresh: 3; url=register.php');
            echo 'All fields must be completed.';
        }
    }
    
    //If the form hasn't been submited, display it
    else{
    ?>
        <h1>Register</h1>
        <form action="" method="POST">
            <input type="hidden" name="id" />
            <label>Username</label>
            <input type="text" name="username" />
            <label>Email</label>
            <input type="text" name="email" />
            <label>Password</label>
            <input type="password" name="password" />
            <input type="hidden" name="access" value="basic"/>
    
            <input type="submit" name="submit" value="Submit"/>
        </form>
    
    <?php
    }
    
}

//if User is already logged in, redirect to homepage
else{
    header('location: /');
}


require '../templates/footer.php';