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
require_once "pdo.php";
  if ( isset($_REQUEST['name']) ) {
      echo htmlentities($_REQUEST['name']);
      echo "</h1>\n";
  }
  if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){
    if(strlen($_POST['make']) < 1){
      $_SESSION["error"] = 'Make is required';
      header("Location: add.php");
      return;
    }
    else if (!is_numeric($_POST['year']) && !is_numeric($_POST['mileage'])){
      $_SESSION["error"] = 'Mileage and year must be numeric';
      header("Location: add.php");
      return;
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
      $_SESSION["success"] = 'Record inserted';
      header("Location: view.php");
      return;

    }
  }
  if ( isset($_SESSION["error"])) {
      // Look closely at the use of single and double quotes
      echo('<p style="color: red;">'.htmlentities($_SESSION["error"])."</p>\n");
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
<input type="submit" name="cancel" value="Cancel">
</form>
</ul>
</div>
</body>
</html>
