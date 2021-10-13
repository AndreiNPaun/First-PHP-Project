<?php 
session_start();
$title = 'Kick⚽ff - Edit Match';
require '../templates/header.php'; 
require '../database.php';

//Checks if session variable is set
if (isset($_SESSION['loggedin'])){

    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();
    
    if ($login['access'] === 'owner' || $login['access'] === 'admin'){

    //If an ID is set on the URL, query DB for the column the ID belongs to
    if(isset($_GET['id'])){

        $stmt = $pdo->prepare('SELECT * FROM game WHERE id = :id');
        $values = ['id' => $_GET['id']];
        $stmt->execute($values);
        $game = $stmt->fetch();    

        //Update match form
        ?>
        <form action="editmatch.php" method="POST">
            <input type="hidden" name="id" value="<?php  echo $_GET['id'] ?>"/>
            <label>Name</label> <input type="text" name="name" value="<?php  echo $game['name'] ?>"/>
            <label>Score 1</label> <input type="text" name="team1_score" value="<?php  echo $game['team1_score'] ?>"/>
            <label>Score 2</label> <input type="text" name="team2_score" value="<?php  echo $game['team2_score'] ?>"/>
            <label>Date</label> <input type="text" name="datetime" value="<?php  echo $game['datetime'] ?>"/>
            
            <!--Drop boxes which will pop up a selectable list of team names, and based on the name an ID will be stored as foreign key-->
            <label>Team 1</label> <select name="team1_id">

                <?php

                    $stmtTeam1 = $pdo->prepare('SELECT * FROM team');
                    $stmtTeam1->execute();

                    foreach($stmtTeam1 as $row){
                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }

                ?>
            </select>
            
            <!--Same logic as above-->
            <label>Team 2</label> <select name="team2_id">
                <?php

                    $stmtTeam2 = $pdo->prepare('SELECT * FROM team');
                    $stmtTeam2->execute();

                    foreach($stmtTeam2 as $row){
                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }

                ?>
            </select>
            <input type="submit" name="submit" value="Submit"/>
        </form>
        <?php
    }

    //If submit has been pressed, override the stored value in the DB with the new ones
    else if(isset($_POST['submit'])){

        if($_POST['team1_id'] !== $_POST['team2_id']){

            $stmtMatch = $pdo->prepare('UPDATE game SET name = :name, team1_score = :team1_score, team2_score = :team2_score, 
                                        datetime = :datetime, team1_id = :team1_id, team2_id = :team2_id WHERE id = :id');
            $values = [
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'team1_score' => $_POST['team1_score'],
                'team2_score' => $_POST['team2_score'],
                'datetime' => $_POST['datetime'],
                'team1_id' => $_POST['team1_id'],
                'team2_id' => $_POST['team2_id']
            ];
            $stmtMatch->execute($values);
    
            header('location: matches.php');
        }

        //Error message in case team1 and team2 ID are the same
        else{
            header('refresh: 3; url=matches.php');
            echo 'You cannot have a team against itself.';
        }

    }
    
    //Error message in case there hasn't been an ID assigned on the URL request
    else{
        header('refresh: 3; url=matches.php');
        echo 'No match has been selected.';
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