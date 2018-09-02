<!DOCTYPE html>
<html>
<head>
<title>Harshit Sinha Profile View</title>
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<?php require_once "bootstrap.php";
require_once "pdo.php";
require_once "util.php";
session_start();

 ?>
</head>
<body>
<div class="container">
  <?php
  $stmt = $pdo->prepare('SELECT first_name, last_name, email, headline, summary
                  FROM Profile where profile_id = :pf_id');
  $stmt->execute(array(':pf_id' => $_GET['profile_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  echo "<h1>Profile information</h1>";
  echo "<p>First Name:";
  echo htmlentities($row['first_name']);
  echo "</p>";
  echo "<p>Last Name:";
  echo htmlentities($row['last_name']);
  echo "</p>";
  echo "<p>Email:";
  echo htmlentities($row['email']);
  echo "</p>";
  echo "<p>Headline:<br/>";
  echo htmlentities($row['headline']);
  echo "</p>";
  echo "<p>Summary:<br/>";
  echo htmlentities($row['summary']);
  echo "</p>";
  $schools = loadEdu($pdo, $_REQUEST['profile_id']);
  echo "<ul>";
  foreach($schools as $school){
    echo "<li>".$school['year'].":".$school['name'];
  }
  echo "</ul>";
  $positions = loadPos($pdo, $_REQUEST['profile_id']);
  echo "<ul>";
  foreach($positions as $position){
    echo "<li>".$position['year'].":".$position['description'];
  }
  echo "</ul>"
   ?>
   <p>
   <a href="index.php">Done</a>
   </p>
   </div>
   </body>
   </html>
