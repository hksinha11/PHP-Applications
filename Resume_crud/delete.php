<!DOCTYPE html>
<html>
<head>
<title>Harshit Sinha Profile Add</title>
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<?php
require_once "bootstrap.php";
require_once "pdo.php";
session_start();
if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}
 ?>
 <script
   src="https://code.jquery.com/jquery-3.2.1.js"
   integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
   crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
<h1>Deleting Profile</h1>
<form method="post" action="delete.php">
<?php
require_once "pdo.php";
if ( ! isset($_GET['profile_id']) ) {
      $_SESSION['error'] = "Missing profile_id";
      header('Location: index.php');
      return;
    }
$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$n = htmlentities($row['first_name']);
$e = htmlentities($row['last_name']);



 ?>
 <p>First Name:
   <?= $n ?>
 </p>
<p>Last Name:
  <?= $e ?>
</p>
<input type="hidden" name="profile_id"
value= "<?= $row['profile_id'] ?>"
/>
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
</html>
