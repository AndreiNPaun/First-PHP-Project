<?php 
session_start();
$title = 'Kick⚽ff - Add Match';
require '../templates/header.php'; 
require '../database.php';

//Checks if session is set, otherwise redirect back to homepage
if (isset($_SESSION['loggedin'])){

    //Retrieves user column data based on the session id
    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();
    
    if ($login['access'] === 'owner' || $login['access'] === 'admin'){

        if(isset($_POST['submit'])){

            //If team1 id is the same as team2 id, then this instance of match can't be stored
            if($_POST['team1_id'] !== $_POST['team2_id']){
                
                //DB insert query for all data written into the add form
                $stmtMatch = $pdo->prepare('INSERT INTO game (name, team1_score, team2_score, datetime, team1_id, team2_id) 
                                    VALUES (:name, :team1_score, :team2_score, :datetime, :team1_id, :team2_id)');
    
                $values = [
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
            //Error message in case team1 and team2 have the same ID
            else{
                header('refresh: 3; url=matches.php');
                echo 'You cannot have a team against itself.';
            }
    
        }
        
        //Add form
        else{
            ?>
            <h1>Add Match</h1>
            <form action="addmatch.php" method="POST">
                <label>Match Name</label> <input type="text" name="name"/>
                <label>Date (Has to be of this format "YYYY-MM-DD")</label> <input type="text" name="datetime"/>
                <label>Score 1 (For Team1)</label> <input type="text" name="team1_score"/>
                <label>Score 2 (For Team2)</label> <input type="text" name="team2_score"/>
                
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
                <!--Same logic as the one above for Team1-->
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

    }
    
    //if session variable isn't set redirect to homepage
    else{
        header('location: /');
    }
}

else{
    header('location: /');
}

require '../templates/footer.php';