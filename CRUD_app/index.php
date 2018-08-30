<!DOCTYPE html>
<html>
<head>
<title>Harshit Sinha Index Page</title>

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
</head>
<body>
<div class="container">
<h2>Welcome to the Automobiles Database</h2>
<?php
require_once "pdo.php";
session_start();
if ( ! isset($_SESSION['name']) ) {
  echo "<p><a href=\"login.php\">Please log in</a></p>";
  echo "<p>Attempt to <a href=\"add.php\">add data</a> without logging in</p>";
} else {
  if ( isset($_SESSION["success"] )) {
      // Look closely at the use of single and double quotes
      echo('<p style="color: green;">'.htmlentities($_SESSION["success"])."</p>\n");
  }
  $stmt = $pdo->query("SELECT make, model, year, mileage, auto_id FROM autos");
  if (! ($row = $stmt->fetch(PDO::FETCH_ASSOC))){
    echo 'No rows found';
  }
  else{
  echo('<table border="1">'."\n");
  echo '<thead><tr>';
  echo '<th>Make</th>';
  echo '<th>Model</th>';
  echo '<th>Year</th>';
  echo '<th>Mileage</th>';
  echo '<th>Action</th>';
  echo '</tr></thead>';
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      echo "<tr><td>";
      echo(htmlentities($row['make']));
      echo("</td><td>");
      echo(htmlentities($row['model']));
      echo("</td><td>");
      echo(htmlentities($row['year']));
      echo("</td><td>");
      echo(htmlentities($row['mileage']));
      echo("</td><td>");
      echo('<a href="edit.php?auto_id='.$row['auto_id'].'">Edit</a> / ');
      echo('<a href="delete.php?auto_id='.$row['auto_id'].'">Delete</a>');
      echo("</td></tr>\n");
    }
  }

  echo '</table>';

  echo "<p><a href=\"add.php\">Add New Entry</a></p>";
  echo "<p><a href=\"logout.php\">Logout</a></p>";
  echo "<p>
  <b>Note:</b> Your implementation should retain data across multiple
  logout/login sessions.  This sample implementation clears all its
  data on logout - which you should not do in your implementation.
  </p>";
}
?>
</div>
</body>
