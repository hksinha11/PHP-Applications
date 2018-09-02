<?php
session_start();
if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Harshit Sinha Automobile Tracker</title>

<!-- Latest compiled and minified CSS -->
<?php
require_once "bootstrap.php";
?>
</head>
<body>
<div class="container">
<h1>Tracking Autos for
  <?php

    if ( isset($_SESSION['name']) ) {
        echo htmlentities($_SESSION['name']);

    }
  echo "</h1>\n";
?>
<?php
    require_once "pdo.php";
    if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['model']) && isset($_POST['mileage'])){

      if(strlen($_POST['make']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1 || strlen($_POST['model']) < 1 ){
        $_SESSION["error"] = 'All fields are required';
        header("Location: add.php");
        return;
      }

      elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])){
        $_SESSION["error"] = 'Year must be an integer';
        header("Location: add.php");
        return;
      }
      else{
        $_SESSION['make'] = $_POST['make'];
        $_SESSION['year'] = $_POST['year'];
        $_SESSION['mileage'] = $_POST['mileage'];
        $sql = "INSERT INTO autos (make, model, year, mileage)
                  VALUES (:mk, :ml, :yr, :mlge)";
                  //echo("<li>\n".$sql."\n</li>\n");
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute(array(
                      ':mk' => htmlentities($_POST['make']),
                      ':ml' => htmlentities($_POST['model']),
                      ':yr' => htmlentities($_POST['year']),
                      ':mlge' => htmlentities($_POST['mileage'])));
        $_SESSION["success"] = 'Record added';
        header("Location: index.php");
        return;

      }
    }
    /*
    $stmt = $pdo->prepare("SELECT * FROM autos WHERE auto_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['auto_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for autos_id';
      header( 'Location: index.php' ) ;
      return;
    }
    */
    if ( isset($_SESSION["error"])) {
        // Look closely at the use of single and double quotes
        echo('<p style="color: red;">'.htmlentities($_SESSION["error"])."</p>\n");
        unset($_SESSION['error']);
    }
  ?>

  <form method="post">
  <p>Make:

  <input type="text" name="make" size="40"/></p>
  <p>Model:

  <input type="text" name="model" size="40"/></p>
  <p>Year:

  <input type="text" name="year" size="10"/></p>
  <p>Mileage:

  <input type="text" name="mileage" size="10"/></p>
  <input type="submit" name='add' value="Add">
  <input type="submit" name="cancel" value="Cancel">
  </form>
</ul>
</div>
</body>
</html>
