<?php 
session_start();
$title = 'Kick⚽ff - Teams';
require '../templates/header.php'; 
require '../database.php';

//Retrieve all data on from team table from DB
$stmtTeam = $pdo->prepare('SELECT * FROM team');
$stmtTeam->execute();
$team = $stmtTeam->fetchAll();

//Checks if session variable is set
if (isset($_SESSION['loggedin'])){

    //Queries user table based on the session ID for access level
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();
    
    //If access level is high enough, display additional functions on teams, such as delete
    if ($login['access'] === 'owner' || $login['access'] === 'admin'){

        echo '<h1>Teams</h1>';
        echo '<p><a href="admin.php">Add new team(s)</a></p>';
        echo '<ul>';
        foreach ($team as $teams){
            echo '<li>';
            echo $teams['name'];
            echo ' <a href="editteam.php?id=' . $teams['id'] . '">Edit</a>';
            echo ' <a href="deleteteam.php?id=' . $teams['id'] . '">Delete</a>';
            echo '</li>';
        }
        echo '</ul>';
    }
    
    //If logged on user access is basic, display same form without the additional functions
    else{
        echo '<h1>Teams</h1>
        <ul>';
        foreach ($team as $teams){
            echo '<li>';
            echo $teams['name'];
            echo '</li>';
        }
        echo '</ul>';
    }
}

//If user is not logged on, display a list of all teams
else{
    echo '<h1>Teams</h1>
    <ul>';
    foreach ($team as $teams){
        echo '<li>';
        echo $teams['name'];
        echo '</li>';
    }
    echo '</ul>';
}

require '../templates/footer.php';