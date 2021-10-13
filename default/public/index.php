<?php 
//Session starts to check whenever a user has high access or is logged on.
session_start();
//Page specific title
$title = 'Kick⚽ff - Home';
//Data object so the data will always up to actual date on footer copyright section.
$copyright = new DateTime();
//Prints the HTML code for website header.
require '../templates/header.php';
require '../database.php'; 
?>

<h1>Home Page</h1>

<p>Welcome to the KickOff League. See how your favourite team is doing and comment on matches.</p>

<hr />

<h1>Team List</h1>

<table class="teams">
    <thead>
        <tr>
            <td>Team</td>
            <!--<td>Points</td>-->
        </tr>
    </thead>
    <tbody>
    <?php
        //DB query to retrieve 10 colums from team table
        $stmt = $pdo->prepare('SELECT * FROM team LIMIT 10');
        $stmt->execute();
        foreach ($stmt as $row) {
            echo '<tr>';
            echo '<td>' . $row['name'] . '</td>';
            //<td>88</td> POINTS DELETE IF NOT USED
            echo '</tr>';
        }
    ?>
    </tbody>
</table>

<hr />

<h1>Matches</h1>

<table class="matches">
    <tbody>
    <?php
        //Queries to print 10 games filtering the oldest ones out
        $stmtGame = $pdo->prepare('SELECT * FROM game ORDER BY datetime DESC LIMIT 10');
        $stmtGame->execute();

        //Query to retrieve teams by name where ID is equal to foreign key stored in game
        $stmtTeam1 = $pdo->prepare('SELECT * FROM team WHERE id = :id');
        $stmtTeam2 = $pdo->prepare('SELECT * FROM team WHERE id = :id');

        foreach ($stmtGame as $match){
            $values1 = ['id' => $match['team1_id']];
            $values2 = ['id' => $match['team2_id']];

            $stmtTeam1->execute($values1);
            $stmtTeam2->execute($values2);

            $team1 = $stmtTeam1->fetch();
            $team2 = $stmtTeam2->fetch();

            //Printing data stored in objects
            echo '<tr>';
            echo '<td>' . $team1['name'] . '</td>';
            echo '<td>' . $match['team1_score'] . '</td>';
            echo '<td>' . $team2['name'] . '</td>';
            echo '<td>' . $match['team2_score'] . '</td>';
            //If Else If Else statements checking whenever a team won, lost or the game ended with a draw
            if ($match['team1_score'] > $match['team2_score']) {
                echo '<td>' . $team1['name'] . ' has won!</td>';
            }

            else if ($match['team1_score'] < $match['team2_score']) {
                echo '<td>' . $team2['name'] . ' has won!</td>';
            }

            else {
                echo '<td>Draw</td>';
            }
            echo '</tr>';
        }
    ?>
    </tbody>
</table>

<hr />

<h1>Match Page</h1>
<?php
    
    //Queries to print 10 games filtering the oldest ones out
    $stmtGame = $pdo->prepare('SELECT * FROM game ORDER BY datetime DESC LIMIT 10');
    $stmtGame->execute();

    //Query to retrieve teams by name where ID is equal to foreign key stored in game
    $stmtTeam1 = $pdo->prepare('SELECT * FROM team WHERE id = :id');
    $stmtTeam2 = $pdo->prepare('SELECT * FROM team WHERE id = :id');

    //Query for comments, limiting comments to be showed to index on up to 5
    $stmtCom = $pdo->prepare('SELECT * FROM comment WHERE game_id = :id LIMIT 5');
    //Query for printing out the username of the user that has posted the comment
    $userCom = $pdo->prepare('SELECT * FROM user WHERE id = :id');
    
    //loop that will print out values from game and team table, decide a team has won or the game ended with a draw and display each team's score
    foreach ($stmtGame as $match) {
        $team1Val = ['id' => $match['team1_id']];
        $team2Val = ['id' => $match['team2_id']];
        $commentVal = ['id' => $match['id']];

        $stmtTeam1->execute($team1Val);
        $stmtTeam2->execute($team2Val);
        $stmtCom->execute($commentVal);

        $team1 = $stmtTeam1->fetch();
        $team2 = $stmtTeam2->fetch();
        $comment = $stmtCom->fetchAll();

        echo '<h2>' . $team1['name'] . ' - ' . $match['team1_score'] . ' ' . $team2['name'] . ' - ' . $match['team2_score'] . '</h2>';

        if ($match['team1_score'] > $match['team2_score']) {
            echo '<h4>' . $team1['name'] . ' has won!</h4></br>';
        }

        else if ($match['team1_score'] < $match['team2_score']) {
            echo '<h4>' . $team2['name'] . ' has won!</h4></br>';
        }

        else {
            echo '<h4>Draw</h4></br>';
        }

        //On each game prineted, print all comments, limit to 5 comments per game (query above), alongside the username of the user who posted the comment
        foreach ($comment as $comments){
            $userVal = ['id' => $comments['user_id']];
            $userCom->execute($userVal);
            $user = $userCom->fetch();
        ?>
            <ul class="comments">
            <li>
            <blockquote>
                <?php echo $comments['comment'] ?>
            </blockquote>
            <p><?php echo $user['username'] ?></p>
            </li>
            </ul>
        <?php
        }
    }
?>

<!--Prints the HTML code for website footer.-->
<?php require '../templates/footer.php'; ?>