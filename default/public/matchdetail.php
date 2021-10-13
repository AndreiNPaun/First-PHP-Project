<link rel="stylesheet" type="text/css" href="custom.css">
<?php 
session_start();
$title = 'Kick⚽ff - Match Details';
require '../templates/header.php'; 
require '../database.php';

//If ID is set on the URL, query DB using the ID and fetch data
if (isset($_GET['id'])){
    $stmt = $pdo->prepare('SELECT * FROM game WHERE id = ' . $_GET['id']);
    $stmt->execute();
    $match = $stmt->fetch();

    //Queries used to retrieve data based on the foreign keys stored within a retrieved column
    $stmtTeam1 = $pdo->prepare('SELECT * FROM team WHERE id = ' . $match['team1_id']);
    $stmtTeam2 = $pdo->prepare('SELECT * FROM team WHERE id = ' . $match['team2_id']);

    $stmtTeam1->execute();
    $stmtTeam2->execute();

    $team1 = $stmtTeam1->fetch();
    $team2 = $stmtTeam2->fetch();

    //Comments table query for retrieving data
    $stmtCom = $pdo->prepare('SELECT * FROM comment WHERE game_id = ' . $_GET['id']);
    $stmtCom->execute();
    $comment = $stmtCom->fetchAll();
    
    //User table query for retrieving user data based on the foreign key stored in comments table
    $userCom = $pdo->prepare('SELECT * FROM user WHERE id = :id');

    //Match details viewable by users based on the data retrieved using the above queries
    echo '<h1>Match Details</h1>';
    echo '<ul>';
    echo '<li>' . $match['name'] . '</li>';
    echo '<li>' . $match['datetime'] . '</li>';
    echo '<li>' . $team1['name'] . ' vs ' . $team2['name'] . '</li>';
    echo '<li>' . $match['team1_score'] . ' - ' . $match['team2_score'] . '</li>';
    echo '</ul>';

    //Data stored in arries making it easier to combine data retrieved from the two tables
    $arrayT1 = [
        'score' => $match['team1_score'],
        'name' => $team1['name']
    ];

    $arrayT2 = [
        'score' => $match['team2_score'],
        'name' => $team2['name']
    ];

    //Logic which will decide based on the score, which team won
    if ($arrayT1['score'] > $arrayT2['score']){
        echo $arrayT1['name'] . ' has won with ' . $arrayT1['score'] . ' to ' . $arrayT2['score'] . '.';
    }

    else if ($arrayT2['score'] > $arrayT1['score']){
        echo $arrayT2['name'] . ' has won with ' . $arrayT2['score'] . ' to ' . $arrayT1['score'] . '.';
    }

    //If none of the teams score more goals than the other, print following message
    else {
        echo 'It\'s a DRAW!';
    }

    //Date to be stored in comments table
    $date = new DateTime();

    if(isset($_SESSION['loggedin'])) {

        //Check if user is logged in
        $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
        $stmt->execute();
        $login = $stmt->fetch();

        if (isset($_POST['submit'])){
            $stmt = $pdo->prepare('INSERT INTO comment (comment, datetime, user_id, game_id) 
                                VALUES (:comment, :datetime, :user_id, :game_id)');
            $values = [
                'comment' => $_POST['comment'],
                'datetime' => $date->format('Y-m-d H:i:s'),
                'user_id' => $_POST['user_id'],
                'game_id' => $_POST['game_id']
            ];
            $stmt->execute($values);
            header('location: matchdetail.php?id=' . $match['id']);
        }
    
        //Post comment form
        else{ ?>
            <form action="" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['loggedin'] ?>" />
                <input type="hidden" name="game_id" value="<?php echo $match['id'] ?>" />
                <label>Write your comment</label>
                <textarea class="commentBox" id="comment" name="comment"></textarea>
    
                <input class="commentButton" type="submit" name="submit" value="Submit" />
        </form>
     <?php }
    
        //If user access is either owner or admin, the user will reveive additional functions; delete/edit
        if ($login['access'] === 'owner' || $login['access'] === 'admin'){
    
            foreach ($comment as $comments) {
                //ID to look up for each time the loop runs, printing out a different username based on the ID
                $values = [
                    'id' => $comments['user_id']
                ];
        
                $userCom->execute($values);
                $user = $userCom->fetch();
        
                echo '<ul class="comments">';
                echo'<li><blockquote>';
                echo $comments['comment'];
                echo '</blockquote>';
                //Linked user name that redirects a user to the user page which s/he clicked on
                echo '<p><a href="userdetails.php?id=' . $user['id'] . '">' . $user['username'] . '</a>' . ' ' . $comments['datetime'] . '</p>';
                echo '</li>';
                echo '</ul>';
                echo '<a href="deletecomment.php?id=' . $comments['id'] . '">Delete </a>';
                if ($_SESSION['loggedin'] == $comments['user_id']) {
                    echo '<a href="editcomment.php?id=' . $comments['id'] . '">Edit</a>';
                }
            }
        }
    
        //If user access is basic, give him access to delete/edit only his comment
        else if ($login['access'] === 'basic') {
            foreach ($comment as $comments){
    
                //ID to look up for each time the loop runs, printing out a different username based on the ID
                $values = [
                    'id' => $comments['user_id']
                ];
        
                $userCom->execute($values);
                $user = $userCom->fetch();
        
                echo '<ul class="comments">';
                echo'<li><blockquote>';
                echo $comments['comment'];
                echo '</blockquote>';
                echo '<p><a href="userdetails.php?id=' . $user['id'] . '">' . $user['username'] . '</a>' . ' ' . $comments['datetime'] . '</p>';
                echo '</li>';
                echo '</ul>';
                //If the ID of the logged in user is equal to one of the comments, allow user to delete/edit his comment
                if ($_SESSION['loggedin'] == $comments['user_id']){
                    echo '<a href="deletecomment.php?id=' . $comments['id'] . '">Delete </a>';
                    echo '<a href="editcomment.php?id=' . $comments['id'] . '">Edit</a>';
                }
            }
        }
    }
    //Users that are not logged in are only able to view posted comments
    else {
        foreach ($comment as $comments){
            //ID to look up for each time the loop runs, printing out a different username based on the ID
            $values = [
                'id' => $comments['user_id']
            ];

            $userCom->execute($values);
            $user = $userCom->fetch();
    
            echo '<ul class="comments">';
            echo'<li><blockquote>';
            echo $comments['comment'];
            echo '</blockquote>';
            echo '<p><a href="userdetails.php?id=' . $user['id'] . '">' . $user['username'] . '</a>' . ' ' . $comments['datetime'] . '</p>';
            echo '</li>';
            echo '</ul>';
        }
    }
}

//If ID can't be retrieved from the URL redirect back to matches list
else {
    echo 'No match has been selected.';
    header('refresh: 3; url=matches.php');
}

require '../templates/footer.php';