<?php 
session_start();
$title = 'Kick⚽ff - Edit Team';
require '../templates/header.php'; 
require '../database.php';

//Checks if session variable is set
if (isset($_SESSION['loggedin'])){

    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();
    
    if ($login['access'] === 'owner' || $login['access'] === 'admin'){

        //If link was send with a GET request print edit form
        if(isset($_GET['id'])){

            //Queries DB for information on the column requested through GET
            $stmt = $pdo->prepare('SELECT * FROM team WHERE id = :id');
            $values = ['id' => $_GET['id']];
            $stmt->execute($values);
            $team = $stmt->fetch();

            //Edit form
            ?>
            <h1>Edit Team</h1>
            <form action="editteam.php" method="POST">
                <label>Team name</label>
                <input type="hidden" name="id" value="<?php echo $team['id'] ?>"/>
                <input type="text" name="name" value="<?php echo $team['name'] ?>"/>
                <input type="submit" name="submit" value="Update"/>
            </form>

            <?php
        }

        //If form has been submited updated the requested column
        else if(isset($_POST['submit'])){

            $stmt = $pdo->prepare('UPDATE team SET name = :name WHERE id = :id');
            $values = [
                'name' => $_POST['name'],
                'id' => $_POST['id']
            ];
            $stmt->execute($values);

            header('location: teams.php');
        }
    }
    
    else{
        header('location: /');
    }
}

else{
    header('location: /');
}

require '../templates/footer.php';