<?php
session_start();

//Unsets session ID for user, making logout possible.
unset($_SESSION['loggedin']);

//Heads back to login after user successfully logged out.
header('location: login.php');