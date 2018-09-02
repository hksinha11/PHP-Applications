

<?php require_once "bootstrap.php";
      require_once "pdo.php";
      require_once "util.php";
      session_start();
      $stmt = $pdo->query('SELECT * FROM Profile');
      $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<html>
<head>
<title>Harshit Sinha Resume Registry</title>
<!-- bootstrap.php - this is HTML -->

</head>
<body>
<div class="container">
<h1>Harshit Sinha Resume Registry</h1>
<?php flashMessages();
 ?>
<?php if (!isset($_SESSION['name'])): ?>
<p><a href="login.php">Please log in</a></p>
<table border="1">
  <thead><tr>
  <th>Name</th>
  <th>Headline</th>
  </tr></thead>
  <?php
  $stmt = $pdo->query("SELECT first_name, last_name, headline FROM profile");
  if (! ($row = $stmt->fetch(PDO::FETCH_ASSOC))){
    echo 'No rows found';
  }
  else{
    $stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM profile");
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      echo "<tr><td>";
      echo '<a href = "view.php?profile_id='.$row['profile_id'].'">';
      echo(htmlentities($row['first_name']));
      echo " ";
      echo(htmlentities($row['last_name']));
      echo "</a>";
      echo("</td><td>");
      echo(htmlentities($row['headline']));
      echo("</td><td>");
      echo("</td></tr>\n");
    }
  }

    ?>
  </table>
<?php else : ?>
  <p><a href="logout.php">Logout</a></p>
  <?php
  flashMessages();
  ?>
    <table border="1">
      <tr><th>Name</th><th>Headline</th><th>Action</th><tr>
        <?php
        $stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM profile");
        if (! ($row = $stmt->fetch(PDO::FETCH_ASSOC))){
          echo 'No rows found';
        }
        else{
        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
            echo "<tr><td>";
            echo('<a href="view.php?profile_id='.$row['profile_id'].'">');
            echo(htmlentities($row['first_name']));
            echo " ";
            echo(htmlentities($row['last_name']));
            echo "</a>";
            echo("</td><td>");
            echo(htmlentities($row['headline']));
            echo("</td><td>");
            echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
            echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
            echo("</td></tr>\n");
          }
        }?>
      </table>
        <p><a href="add.php">Add New Entry</a></p>
<?php endif; ?>
<p>
<b>Note:</b> Your implementation should retain data across multiple
logout/login sessions.  This sample implementation clears all its
data periodically - which you should not do in your implementation.
</p>
</div>
</body>
