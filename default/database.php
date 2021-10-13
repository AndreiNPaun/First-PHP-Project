<?php
//Database connection
$pdo = new PDO('mysql:dbname=football;host=v.je', 'student', 'student',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
