<?php 
session_start();
$title = 'Kick⚽ff - Add Team';
require '../templates/header.php'; 
require '../database.php';

//Checks if session is set, otherwise redirect back to homepage
if (isset($_SESSION['loggedin'])){

    //Retrieves user column data based on the session id
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();
    
    if ($login['access'] === 'owner' || $login['access'] === 'admin'){
        if (isset($_POST['submit'])){
            $stmt = $pdo->prepare('INSERT INTO team (name) VALUES (:name)');
            $values = [
                'name' => $_POST['name']
            ];
            $stmt->execute($values);

            header('location: teams.php');
        }

        else{
            ?>
            <h1>Add Team Form</h1>
            <form action="addteam.php" method="POST">
                <label>Team name</label>
                <input type="text" name="name"/>
                    
                <input type="submit" name="submit" value="Add"/>
            </form>
            <?php
        }
    }
    
    else{
        header('location: /');
    }
}

//if session variable isn't set redirect to homepage
else{
    header('location: /');
}


require '../templates/footer.php';