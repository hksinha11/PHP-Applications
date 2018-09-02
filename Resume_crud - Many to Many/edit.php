<?php
require_once "bootstrap.php";
require_once "util.php";
require_once "pdo.php";
session_start();

if(!isset($_REQUEST['profile_id'])){
  $_SESSION['error'] = "Missing profile_id";
  header("Location: index.php");
  return;
}

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$stmt = $pdo->prepare('SELECT * from Profile
WHERE profile_id = :prof AND user_id = :uid');
$stmt->execute(array(':prof' => $_REQUEST['profile_id'],
        ':uid' => $_SESSION['user_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
/*
if($profile === false){
  $_SESSION['error'] = "Could not load profile";
  header('Location: edit.php');
  return;
  aaj chalo woh kia joisse karne mein accha khaasa effort chahiye refactoring
  initial refactoring takes guts and that's pretty important and finally
  having to do it that made me a better programmer chalo theek hai nahi toh kahaan
  baat bann paati hai
}
*/
if(isset($_POST['first_name']) && isset($_POST['last_name'])
    && isset($_POST['email'])
    && isset($_POST['headline']) && isset($_POST['summary'])){
      $msg = validateProfile();
      if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header(" Location: edit.php?profile_id=".$_REQUEST["profile_id"]);
        return;
      }

        $msg = validatePos();
        if(is_string($msg)){
          $_SESSION['error'] = $msg;
          header(" Location: edit.php?profile_id=".$_REQUEST["profile_id"]);
          return;
        }
        $stmt = $pdo->prepare('UPDATE Profile SET
         first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su
         WHERE profile_id = :pid AND user_id = :uid');
    $stmt->execute(array(
        ':pid' => $_REQUEST['profile_id'],
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
    );
    $profile_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare('DELETE FROM Position
        WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare('INSERT INTO Position
            (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $_REQUEST['profile_id'],
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
    }
    $stmt = $pdo->prepare('DELETE FROM EDUCATION
                where profile_id=:pid');
    $stmt->execute(array(':pid' => $_REQUEST['profile_id']));
    insertEducations($pdo, $_REQUEST['profile_id']);
        $_SESSION["success"] = 'Profile updated';
        header("Location: index.php");
        return;


}
$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    // Flash pattern


    $n = htmlentities($row['first_name']);
    $e = htmlentities($row['last_name']);
    $p = htmlentities($row['email']);
    $q = htmlentities($row['headline']);
    $s = htmlentities($row['summary']);
    $a = htmlentities($row['profile_id']);
$positions = loadPos($pdo, $_REQUEST['profile_id']);
$schools = loadEdu($pdo, $_REQUEST['profile_id']);
  ?>

<!DOCTYPE html>
<html>
<head>

<title>Harshit Sinha Profile Edit</title>
<!-- bootstrap.php - this is HTML -->

</head>
<body>
<div class="container">
<h1>Editing Profile for UMSI</h1>
<?php flashMessages(); ?>
<form method="post" action="edit.php">
<p>First Name:
<input type="text" name="first_name" size="60"
value="<?= $n ?>"
/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"
value="<?= $e ?>"
/></p>
<p>Email:
<input type="text" name="email" size="30"
value="<?= $p ?>"
/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"
value="<?= $q ?>"
/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80">
<?= $s ?></textarea>
</p>
<!-- Education: <input type="submit" id="addEdu" value="+">
<div id="edu_fields">
</div>
-->
<?php
  $edu = 0;
  echo('<p>Education: <input type = "submit" id = "addEdu" value = "+">'."\n");
  echo('<div id = "edu_fields">'."\n");
  if(count($schools) > 0){
  foreach($schools as $school){
    $edu++;
    echo('<div id = "edu_fields'.$edu.'">'."\n");
    echo('<p>Year: <input type = "text" name = "year'.$edu.'"');
    echo(' value="'.$school['year'].'" />'."\n");
    echo('<input type = "button" value = "-" ');
    echo('onclick="$(\'#edu_fields'.$edu.'\').remove();return false;">'."\n");
    echo("</p>\n");
    echo('<p>School: <input type = "text" size="80" name = "edu_school'.$edu.'" class="school" value="'.htmlentities($school['name']).'">');
    echo("\n</div>\n");
  }
  }
  echo("</div></p>\n");
 ?>

<p>
<input type="hidden" name="profile_id"
value="<?= $a ?>"
/>
<?php
  $pos = 0;
  echo('<p>Position: <input type = "submit" id = "addPos" value = "+">'."\n");
  echo('<div id = "position_fields">'."\n");
  foreach($positions as $position){
    $pos++;
    echo('<div id = "position'.$pos.'">'."\n");
    echo('<p>Year: <input type = "text" name = "year'.$pos.'"');
    echo(' value="'.$position['year'].'" />'."\n");
    echo('<input type = "button" value = "-" ');
    echo('onclick="$(\'#position'.$pos.'\').remove();return false;">'."\n");
    echo("</p>\n");
    echo('<textarea name = "desc'.$pos.'" rows = "8" cols = "80">'."\n");
    echo(htmlentities($position['description'])."\n");
    echo("\n</textarea>\n</div>\n");
  }
  echo("</div></p>\n");
 ?>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
<script>

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
countPos = <?= $pos ?>;
countEdu = <?= $edu ?>;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
    window.console && console.log('Document ready called');

    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);

        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });

    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);

        // Grab some HTML with hot spots and insert into the DOM
        var source  = $("#edu-template").html();
        $('#edu_fields').append(source.replace(/@COUNT@/g,countEdu));

        // Add the even handler to the new ones
        $('.school').autocomplete({
            source: "school.php"
        });

    });

    $('.school').autocomplete({
        source: "school.php"
    });

});

</script>
<!-- HTML with Substitution hot spots -->
<script id="edu-template" type="text">
  <div id="edu@COUNT@">
    <p>Year: <input type="text" name="edu_year@COUNT@" value="" />
    <input type="button" value="-" onclick="$('#edu@COUNT@').remove();return false;"><br>
    <p>School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value="" />
    </p>
  </div>
</script>
</body>
</html>
