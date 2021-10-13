<?php 
session_start();
$title = 'Kick⚽ff - Admin';
require '../templates/header.php'; 
require '../database.php';

//Checks if session variable is set
if (isset($_SESSION['loggedin'])){

    //Retrieves user column data based on the session id
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();

    //checks if the user has enough privileges to access this page
    if ($login['access'] === 'owner' || $login['access'] === 'admin'){
        ?>
        <h1>Admin Page</h1>
        
        <h3>Team Commands</h3>
        <ul>
        <li><a href="addteam.php">Add Team</a></li>
        <li><a href="teams.php">Manage Teams (Edit, Delete)</a></li>
        </ul>
        
        <h3>Match Commands</h3>
        <ul>
        <li><a href="addmatch.php">Add Match</a></li>
        <li><a href="matches.php">Manage Matches (Edit, Delete)</a></li>
        </ul>
    
        <?php

        if ($login['access'] === 'owner'){ ?>
            <h3>Accounts Commands</h3>
            <ul>
            <li><a href="accounts.php">Manage Accounts</a></li>
            </ul>
       <?php }
    }
    
    //If user has a basic account, redirect to homepage
    else{
        header('location: /');
    }
}

//Redirect to index if session is not set
else{
    header('location: /');
}

require '../templates/footer.php';