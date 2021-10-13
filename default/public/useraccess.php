<?php 
session_start();
$title = 'Kick⚽ff - Admin';
require '../templates/header.php'; 
require '../database.php';

if (isset($_SESSION['loggedin'])){

    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
    $stmt->execute();
    $login = $stmt->fetch();

    //Fetch data from DB based on the id stored in the URL
    $stmtUser = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_GET['id']);
    $stmtUser->execute();
    $user = $stmtUser->fetch();
    
    //page available to only owner account
    if ($login['access'] === 'owner'){
        if (isset($_POST['submit'])){
            //Query for update access status on an account
            $stmt = $pdo->prepare('UPDATE user SET access = :access WHERE id = :id');
            $values = [
                'access' => $_POST['access'],
                'id' => $_POST['id']
            ];
            $stmt->execute($values);

            header('location: admin.php');
        }

        //Update status form
        else { 
            echo '<h1>Account Access Level</h1>';
            echo 'Account name: ' . $user['username'];
            ?>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $user['id'] ?>" />

                <label>Access Level</label>
                <select name="access">
                    <option value="basic">Basic</option>
                    <option value="admin">Admin</option>
                </select>
                <input type="submit" name="submit" value="submit" />
            </form>
       <?php }
    }
    
    else{
        header('location: /');
    }
}

else{
    header('location: /');
}

require '../templates/footer.php';