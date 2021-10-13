<?php 
session_start();
$title = 'Kick⚽ff - Admin';
require '../templates/header.php'; 
require '../database.php';

//Checks if session is set, otherwise redirect back to homepage
if (isset($_SESSION['loggedin'])) {

    //Retrieves user column data based on the session id
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();
    
    //Owner access area only
    if ($login['access'] === 'owner') {
        $stmt = $pdo->prepare('SELECT * FROM user');
        $stmt->execute();
        $user = $stmt->fetchAll();
        
        //Unset admin account from array, since it can't be deleted
        unset($user[1]);

        //Looping through all accounts stored
        echo '<h1>Accounts</h1>
        <ul>';
        foreach ($user as $users){
            echo '<li>';
            echo ' <a href="userdetails.php?id=' . $users['id'] . '">' . $users['username'] . '</a>';
            echo ' <a href="useraccess.php?id=' . $users['id'] . '">Update Access Level</a>';
            echo ' <a href="deleteuser.php?id=' . $users['id'] . '">Delete User Account</a>';
            echo '</li>';
        }
        echo '</ul>';
    }

    else {
        header('location: /');
    }
}

//if session variable isn't set redirect to homepage
else{
    header('location: /');
}

require '../templates/footer.php';