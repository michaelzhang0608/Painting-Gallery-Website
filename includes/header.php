<?php
include_once("includes/db.php");
include_once("includes/sessions.php");
include("includes/init.php");
$result = exec_sql_query($db, "SELECT * FROM art");
$records = $result->fetchAll();
$page = $_SERVER['REQUEST_URI'];
$border = 'border';





?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" />
  <title>Most Expensive Art Ever Sold</title>
</head>

<div class="header">
  <div class="bar">
    <nav>
      <ul>
        <li><a href="/home" class="<?php if ("/home" == $page) {
                                      echo $border;
                                    } ?>">Home</a></li>
        <?php if (is_user_member_of($db, 1)) { ?>
          <li><a href="/submit" class="<?php if ("/submit" == $page) {
                                          echo $border;
                                        } ?>">Submit An Entry</a></li> <?php } ?>
      </ul>
    </nav>
    <?php if (is_user_logged_in()) { ?>
      <form action="<?php echo logout_url(); ?>">
        <input name="logout" class="login_submit" type="submit" value="Log Out">
      </form>
    <?php
    } else {
      echo_login_form('/home', $session_messages);
    } ?>
  </div>
  <h1> Most Expensive Paintings Ever Sold </h1>
</div>
