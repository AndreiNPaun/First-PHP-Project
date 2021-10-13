<?php 
session_start();
$title = 'Kick⚽ff - Matches';
require '../templates/header.php'; 
require '../database.php';

//Query for retrieving data on game table
$stmtMatch = $pdo->prepare('SELECT * FROM game');
$stmtMatch->execute();
$match = $stmtMatch->fetchAll();

//checks if session variable is set
if (isset($_SESSION['loggedin'])){

    //Query db for user information
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();
    
    //if user access level is either owner or admin, either will have additional options, such as delete or edit for matches
    if ($login['access'] === 'owner' || $login['access'] === 'admin'){

        echo '<h1>Matches</h1>';
        echo '<p><a href="admin.php">Add new match(es)</a></p>';
        echo '<ul>';
        foreach ($match as $matches){
            echo '<li>';
            echo '<a href="matchdetail.php?id=' . $matches['id'] . '">' . $matches['name'] . '</a>';
            echo ' <a href="editmatch.php?id=' . $matches['id'] . '">Edit</a>';
            echo ' <a href="deletematch.php?id=' . $matches['id'] . '">Delete</a>';
            echo '</li>';
        }
        echo '</ul>';
    }

    //If user is loggedin display matches, but without the edit functions
    else{
        echo '<h1>Matches</h1>
        <ul>';
        foreach ($match as $matches){
            echo '<li>';
            echo '<a href="matchdetail.php?id=' . $matches['id'] . '">' . $matches['name'] . '</a>';
            echo '</li>';
        }
        echo '</ul>';
    }
}

//Same as the else statement above, but if session is not set, then display it again
else{
    echo '<h1>Matches</h1>
    <ul>';
    foreach ($match as $matches){
        echo '<li>';
        echo '<a href="matchdetail.php?id=' . $matches['id'] . '">' . $matches['name'] . '</a>';
        echo '</li>';
    }
    echo '</ul>';
}

require '../templates/footer.php';