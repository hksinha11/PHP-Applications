<?php
// Demand a GET parameter
session_start();
if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Harshit Sinha Automobile Tracker</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for
<?php
if ( isset($_REQUEST['name']) ) {
    echo htmlentities($_REQUEST['name']);

}
echo "</h1>\n";
if ( isset($_SESSION["success"] )) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: green;">'.htmlentities($_SESSION["success"])."</p>\n");
}
 ?>
<h2>Automobiles</h2>
<ul>
<p>
  <?php
  require_once "pdo.php";
  if ( isset($_SESSION["success"])){

    $stmt = $pdo->query("SELECT make, year, mileage FROM autos");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ( $rows as $row ) {
echo "<li>";
echo($row['make']);
echo " ";
echo($row['year']);
echo " \\";
echo($row['mileage']);
echo "</li>\n";
}
  }
   ?>
</ul>
<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
</div>

</body>
</html>
