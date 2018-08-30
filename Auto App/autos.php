<?php

// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}
$failure = false;
$success = false;

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
require_once "pdo.php";
if ( isset($_REQUEST['name']) ) {
    echo htmlentities($_REQUEST['name']);
    echo "</h1>\n";
}
if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){
  if(strlen($_POST['make']) < 1){
    $failure = 'Make is required';
  }
  else if (!is_numeric($_POST['year']) && !is_numeric($_POST['mileage'])){
    $failure = 'Mileage and year must be numeric';
  }
  else{
    $sql = "INSERT INTO autos (make, year, mileage)
              VALUES (:mk, :yr, :mlge)";
              //echo("<li>\n".$sql."\n</li>\n");
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
                  ':mk' => htmlentities($_POST['make']),
                  ':yr' => htmlentities($_POST['year']),
                  ':mlge' => htmlentities($_POST['mileage'])));
    $success = 'Record inserted';
  }
}
if ( $success !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
}
else if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
</form>
<h2>Automobiles</h2>
<ul>
<?php
require_once "pdo.php";
if ( $success !== false ){
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
</html>
