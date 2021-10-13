<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="styles.css"/>
		<meta charset="utf-8" />
		<title><?=$title?></title>
	</head>
	<body>
	<header>
		<section>
			<h1>Kick⚽ff</h1>

		</section>
	</header>
	<nav>
		<ul>
			<li><a href="/">Home</a></li>
			<li><a href="teams.php">Teams</a></li>
			<li><a href="matches.php">Matches</a></li>
		<?php 
		if (!isset($_SESSION['loggedin'])){ ?>
			<li><a href="login.php">Login</a></li>
			<li><a href="register.php">Register</a></li>
		<?php } 
		else{ 
			require '../database.php';
			$stmt = $pdo->prepare('SELECT * FROM user WHERE id = ' . $_SESSION['loggedin']);
			$stmt->execute();
			$login = $stmt->fetch();
			if($login['access'] === 'owner' || $login['access'] ==='admin'){ ?>
				<li><a href="admin.php">Admin</a></li>
		<?php	}
			?>
			<li><a href="logout.php">Logout</a></li>
		<?php 
		} ?>
		</ul>

	</nav>
<img src="images/randombanner.php"/>
	<main class="home">