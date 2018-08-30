<!DOCTYPE html>
<html>
<head>
<title>Dr. Chuck's Automobile Tracker</title>

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
    <div class="container">
    <h1>Editing Automobile</h1>
<?php
    require_once "pdo.php";
    session_start();
    if(isset($_POST['make']) && isset($_POST['year'])
    && isset($_POST['model']) && isset($_POST['mileage'])) {
      if ( strlen($_POST['model']) < 1 || strlen($_POST['make']) < 1
        || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
          $_SESSION['error'] = "All fields are required";
          header("Location: edit.php?auto_id=".$_REQUEST['auto_id']);
          return;
    }
    else{
      $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year, mileage = :mileage
            WHERE auto_id = :auto_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':auto_id' => $_POST['auto_id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
    }
    }

    if ( ! isset($_GET['auto_id']) ) {
          $_SESSION['error'] = "Missing auto_id";
          header('Location: index.php');
          return;
        }
    $stmt = $pdo->prepare("SELECT * FROM autos WHERE auto_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['auto_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = 'Bad value for auto_id';
        header( 'Location: index.php' ) ;
        return;
    }

    // Flash pattern
    if ( isset($_SESSION['error']) ) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }

    $n = htmlentities($row['make']);
    $e = htmlentities($row['model']);
    $p = htmlentities($row['year']);
    $q = htmlentities($row['mileage']);
    $a = htmlentities($row['auto_id']);


?>
</head>
<body>

<form method="post">
<p>Make<input type="text" name="make" size="40" value="<?= $n ?>"/></p>
<p>Model<input type="text" name="model" size="40" value="<?= $e ?>"/></p>
<p>Year<input type="text" name="year" size="10" value="<?= $p ?>"/></p>
<p>Mileage<input type="text" name="mileage" size="10" value="<?= $q ?>"/></p>
<input type="hidden" name="auto_id" value="<?= $a ?>">
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
</div>
</body>
</html>
