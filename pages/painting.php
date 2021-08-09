<?php
include("includes/header.php");



$art_id = (int)trim($_GET['id']);
$url = "/home/art/?" . http_build_query(array('id' => $art_id));
$edit_feedback_class = 'hidden';

if ($art_id) {
  $records = exec_sql_query(
    $db,
    "SELECT * FROM art WHERE id = :id;",
    array(':id' => $art_id)
  )->fetchAll();
  if (count($records) > 0) {
    $record = $records[0];
  } else {
    $record = NULL;
  }
}

$all_tags = exec_sql_query($db, "SELECT tag_name FROM tags");
$all_tags = $all_tags->fetchAll();
$all_tags = array_values($all_tags);
$tags = exec_sql_query($db, "SELECT * FROM tags INNER JOIN art_tags ON tags.id = art_tags.tag_id WHERE :id = art_tags.art_id", array(':id' => $art_id));
$tags = $tags->fetchAll();
$curr_tags = array();
for ($x = 0; $x <= count($tags); $x++) {
  array_push($curr_tags, $tags[$x]['tag_name']);
}
$curr_tags = array_unique($curr_tags);



if (isset($_POST['edit'])) {
  if ($_POST['edit'] === "delete") {
    exec_sql_query($db, "DELETE FROM art WHERE id = :id", array(":id" => $art_id));
    exec_sql_query($db, "DELETE FROM art_tags WHERE art_id = :id", array(":id" => $art_id));
    $filepath = trim("public/uploads/art/" . $record['id'] . '.' . $record['file_ext']);
    unlink($filepath); ?>
    <meta http-equiv="refresh" content="0;url=pages/404.php">
  <?php
  } else {
    $edit_mode = True;
  }
}
$formValid = TRUE;
if (isset($_POST['submit_tags'])) {
  $db->beginTransaction();
  if (($_POST['new_tag']) != '') { //if text input is not empty
    $new_tag = trim($_POST['new_tag']);
    $get_tag = exec_sql_query(
      $db,
      "SELECT * FROM tags WHERE tag_name = :new_tag",
      array(":new_tag" => $new_tag)
    )->fetchAll();
    if (count($get_tag) > 0) {
      $edit_feedback_class = '';
      $formValid = FALSE;
      $edit_mode = True;
    } else {
      exec_sql_query($db, "INSERT INTO tags (tag_name) VALUES (:new_tag);", array(":new_tag" => $new_tag)); //insert into tags table
      $new_id = $db->lastInsertId("id");
      exec_sql_query($db, "INSERT INTO art_tags (tag_id, art_id) VALUES (:new_id, :art_id);", array(":new_id" => $new_id, ":art_id" => $art_id));
    } //give current painting this new tag
  }
  foreach ($all_tags as $tag) { //for all of the old tag names
    if (isset($_POST[$tag["tag_name"]])) { //if checkbox is checked
      $tag_id = exec_sql_query($db, "SELECT id FROM tags WHERE tag_name = :tag", array(":tag" => $tag["tag_name"])); //get tag id
      $tag_id = $tag_id->fetchAll();
      $tag_id = $tag_id[0];
      //check if painting already tagged
      $tagged = exec_sql_query($db, "SELECT * FROM art_tags WHERE tag_id = :tag_id AND art_id = :art_id;", array(":tag_id" => (int)$tag_id["id"], ":art_id" => $art_id));
      $tagged = $tagged->fetchAll();
      if (count($tagged) == 0) {
        exec_sql_query($db, "INSERT INTO art_tags (tag_id, art_id) VALUES (:tag_id, :art_id);", array(":art_id" => $art_id, ":tag_id" => (int) $tag_id["id"]));
      }
      //insert to table
    } else { //if checkbox is unchecked
      if (in_array($tag["tag_name"], $curr_tags)) { //if this tag was originally given to this painting
        $tag_id = exec_sql_query($db, "SELECT id FROM tags WHERE tag_name =:new_tag", array(":new_tag" => $tag["tag_name"])); //get tag id
        $tag_id = $tag_id->fetchAll();
        $tag_id = $tag_id[0];
        exec_sql_query($db, "DELETE FROM art_tags WHERE tag_id = :tag_id AND art_id = :art_id", array(":tag_id" => (int) $tag_id["id"], ":art_id" => $art_id)); //delete from table
      }
    }
  }
  $db->commit();
  if ($formValid) {
  ?>
    <meta http-equiv="refresh" content="0;url=<?php echo $url ?>>"><?php
                                                                  }
                                                                }
                                                                    ?>


<main>
  <div class="painting">
    <img src="/public/uploads/art/<?php echo $record['id'] . '.' . $record['file_ext']; ?>" alt="<?php echo $record['title'] ?>" class="big_painting">
    <br>
    <div class="info">
      <h4> Title: <?php echo htmlspecialchars($record["title"]); ?></h4>
      <h4> Artist: <?php echo htmlspecialchars($record["artist"]); ?></h4>
      <h4> Buyer: <?php echo htmlspecialchars($record["buyer"]); ?></h4>
      <h4> Seller: <?php echo htmlspecialchars($record["seller"]); ?></h4>
      <h4> Price (adjusted for inflation): $<?php echo htmlspecialchars(number_format($record["price"] * 1000000)); ?></h4>
      <h4> Date Created: <?php echo htmlspecialchars($record["created_date"]); ?></h4>
      <h4> Date of Sale: <?php echo htmlspecialchars($record["sale_date"]); ?></h4>
      <h4 style="margin-bottom: 69px;">Tags:</h4>
      <?php
      foreach ($tags as $tag) {
      ?> <div class="tag_div">
          <img src="/public/images/tag.png" alt="tag">
          <p><?php echo $tag["tag_name"]; ?> </p>
        </div><?php
            }
              ?>

      <?php if (!is_user_logged_in()) { ?>
        <h3 style="color: black;">** Log in to make edits **</h3>
        <?php } else {
        if (is_user_member_of($db, 1)) { ?>
          <form action=<?php echo $url ?> class="edit_form" method="post" style="position: relative;bottom: 30px;">
            <select onchange="this.form.submit();" name="edit">
              <option>...</option>
              <option value="edit_tags">Edit Tags</option>
              <option value="delete">Delete</option>
            </select>
          </form>
          <?php
          if ($edit_mode) { ?>
            <h3>Add/Remove Tags: </h3>
            <form id="tag_edit_form" action=<?php echo $url ?> method="post" novalidate>
              <?php foreach ($all_tags as $tag) {
              ?> <label><?php echo $tag['tag_name']; ?></label>
                <div class="display: block;">
                  <input name=<?php echo $tag['tag_name'] ?> style="position: relative;bottom: 30px; right: 70px;" type="checkbox" value=<?php echo $tag['tag_name'] ?> <?php if (in_array($tag['tag_name'], $curr_tags)) {
                                                                                                                                                                          echo "checked";
                                                                                                                                                                        } ?>></input>
                </div><?php
                    } ?>
              <strong class="feedback <?php echo $edit_feedback_class ?>">This tag already exists. Please enter a unique tag name.</strong>
              <div style="margin-top: 30px;">
                <label style="position: relative; right: 150px;">Add a new tag: </label>
                <input style="position: relative; right: 70x; bottom: 23px;" name="new_tag" type="text"> </input>
              </div>
              <input style="margin-top: 30px;" name='submit_tags' type="submit" value="Update Tags"></input>
            </form>
          <?php }
        } else { ?>
          <form action=<?php echo $url ?> class="edit_form" method="post" style="position: relative;bottom: 30px;">
            <select onchange="this.form.submit();" name="edit">
              <option>...</option>
              <option value="edit_tags">Edit Tags</option>
            </select>
          </form>
          <?php
          if ($edit_mode) { ?>
            <h3>Add/Remove Tags: </h3>
            <form id="tag_edit_form" action=<?php echo $url ?> method="post" novalidate>
              <?php foreach ($all_tags as $tag) {
              ?> <label><?php echo $tag['tag_name']; ?></label>
                <div class="display: block;">
                  <input name=<?php echo $tag['tag_name'] ?> style="position: relative;bottom: 30px; right: 70px;" type="checkbox" value=<?php echo $tag['tag_name'] ?> <?php if (in_array($tag['tag_name'], $curr_tags)) {
                                                                                                                                                                          echo "checked";
                                                                                                                                                                        } ?>></input>
                </div><?php
                    } ?>
              <strong class="feedback <?php echo $edit_feedback_class ?>">This tag already exists. Please enter a unique tag name.</strong>
              <div style="margin-top: 30px;">
                <label style="position: relative; right: 150px;">Add a new tag: </label>
                <input style="position: relative; right: 70x; bottom: 23px;" name="new_tag" type="text"> </input>
              </div>
              <input class="update_button" name='submit_tags' type="submit" value="Update Tags"></input>
            </form>
      <?php }
        }
      }
      ?>
    </div>
  </div>
</main>
</body>
