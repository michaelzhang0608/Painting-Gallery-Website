<?php
include "includes/header.php";

$all_tags = exec_sql_query($db, "SELECT * FROM tags");
$all_tags = $all_tags->fetchAll();
$all_tags = array_values($all_tags);

$sticky_title = '';
$sticky_artist = '';
$sticky_buyer = '';
$sticky_seller = '';
$sticky_year = '';
$sticky_sold = '';
$sticky_price = '';
$title_feedback_class = 'hidden';
$artist_feedback_class = 'hidden';
$buyer_feedback_class = 'hidden';
$seller_feedback_class = 'hidden';
$year_feedback_class = 'hidden';
$sold_feedback_class = 'hidden';
$price_feedback_class = 'hidden';
$file_feedback_class = 'hidden';
$edit_feedback_class = 'hidden';

define("MAX_FILE_SIZE", 1000000);


$upload_title = NULL;
$upload_artist = NULL;
$upload_buyer = NULL;
$upload_seller = NULL;
$upload_year = NULL;
$upload_sold = NULL;
$upload_price = NULL;
$upload_filename = NULL;
$upload_ext = NULL;



$sticky_title = '';
$sticky_desc = '';
$sticky_source = '';
$success = FALSE;

if (isset($_POST["submit"])) {
  $upload_title = trim($_POST['title']);
  $upload_artist = trim($_POST['artist']);
  $upload_buyer = trim($_POST['buyer']);
  $upload_seller = trim($_POST['seller']);
  $upload_year = (int)trim($_POST['year']);
  $upload_sold = trim($_POST['sold']);
  $upload_price = (int)trim($_POST['price']);
  $upload = $_FILES["file_upload"];
  $new_tag_bool = FALSE;
  $form_valid = True;

  if (($_POST['new_tag']) != '') { //if text input is not empty
    $new_tag = trim($_POST['new_tag']);
    $get_tag = exec_sql_query(
      $db,
      "SELECT * FROM tags WHERE tag_name = :new_tag",
      array(":new_tag" => $new_tag)
    )->fetchAll();
    $new_tag_bool = TRUE;
    if (count($get_tag) > 0) {
      $edit_feedback_class = '';
      $formValid = FALSE;
    }
  }



  if (!$upload['error'] == UPLOAD_ERR_OK) {
    $form_valid = False;
  }

  //set $upload_filename and $upload_ext
  $upload_filename = basename($upload['name']);
  $upload_ext = strtolower(pathinfo($upload_filename, PATHINFO_EXTENSION));

  if (empty($upload_title)) {
    $form_valid = False;
    $title_feedback_class = '';
  }

  if (empty($upload_artist)) {
    $form_valid = False;
    $artist_feedback_class = '';
  }

  if (empty($upload_buyer)) {
    $form_valid = False;
    $buyer_feedback_class = '';
  }
  if (empty($upload_seller)) {
    $form_valid = False;
    $seller_feedback_class = '';
  }
  if (empty($upload_year)) {
    $form_valid = False;
    $year_feedback_class = '';
  }
  if (empty($upload_sold)) {
    $form_valid = False;
    $sold_feedback_class = '';
  }
  if (empty($upload_price)) {
    $form_valid = False;
    $price_feedback_class = '';
  }

  if ($form_valid) {
    $db->beginTransaction();
    $result = exec_sql_query(
      $db,
      "INSERT INTO art (user_id, title, artist, buyer, seller, created_date, sale_date, price, file_name, file_ext) VALUES (:user_id, :title, :artist, :buyer, :seller, :created_date, :sale_date, :price, :file_name, :file_ext)",
      array(
        ':user_id' => $current_user['id'],
        ':title' => $upload_title,
        ':artist' => $upload_artist,
        ':file_name' => $upload_filename,
        ':file_ext' => $upload_ext,
        ':buyer' => $upload_buyer,
        ':seller' => $upload_seller,
        ':created_date' => $upload_year,
        ':sale_date' => $upload_sold,
        ':price' => $upload_price
      )
    );
    if ($result) {
      $id = $db->lastInsertId("id");
      if ($new_tag_bool) {
        exec_sql_query($db, "INSERT INTO tags (tag_name) VALUES (:new_tag);", array(":new_tag" => $new_tag)); //insert into tags table
        $new_tag_id = $db->lastInsertId("id");
        exec_sql_query($db, "INSERT INTO art_tags (tag_id, art_id) VALUES (:new_id, :art_id);", array(":new_id" => $new_tag_id, ":art_id" => $id));
      }
      foreach ($all_tags as $tag) { //for all of the  tag names
        if (isset($_POST[$tag["tag_name"]])) { //if checkbox is checked
          exec_sql_query($db, "INSERT INTO art_tags (tag_id, art_id) VALUES (:tag_id, :art_id);", array(":art_id" => $id, ":tag_id" => (int) $tag[0]["id"]));
        }
      }
      $new_path = "public/uploads/art/" . $id . "." . $upload_ext;
      move_uploaded_file($upload["tmp_name"], $new_path);
    }

    $db->commit();
    $success = TRUE;
  } else {
    $file_feedback_class = '';

    $sticky_title = $upload_title;
    $sticky_desc = $upload_desc;
    $sticky_source = $upload_source;
    $sticky_artist = $upload_artist;
    $sticky_price = $upload_price;
    $sticky_seller = $upload_seller;
    $sticky_year = $upload_year;
  }
}



if (is_user_logged_in()) {
  if (is_user_member_of($db, 1)) {
    if (!$success) {
?>
      <div>
        <form class="submit_form" enctype="multipart/form-data" style="right: 40%;position: absolute; top: 450px;" id="submit" action="/submit" method="post" novalidate>
          <p id="title_feedback" class="feedback <?php echo $title_feedback_class; ?>">Please provide the valid title of the painting.</p>
          <div class="title_input">
            <label> Title:</label>
            <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($sticky_title); ?>" required />
          </div>
          <br>
          <p id="artist_feedback" class="feedback <?php echo $artist_feedback_class; ?>">Please provide the artist of the painting.</p>
          <div class="artist_input">
            <label> Artist :</label>
            <input id="artist" type="text" name="artist" value="<?php echo htmlspecialchars($sticky_artist); ?>" required />
          </div>
          <br>
          <p id="buyer_feedback" class="feedback <?php echo $buyer_feedback_class; ?>">Please provide the name of the buyer.</p>
          <div class="buyer_input">
            <label> Buyer :</label>
            <input id="buyer" type="text" name="buyer" value="<?php echo htmlspecialchars($sticky_buyer); ?>" required />
          </div>
          <br>
          <p id="seller_feedback" class="feedback <?php echo $seller_feedback_class; ?>">Please provide the name of the seller.</p>
          <div class="seller_input">
            <label> Seller :</label>
            <input id="seller" type="text" name="seller" value="<?php echo htmlspecialchars($sticky_seller); ?>" required />
          </div>
          <br>
          <p id="year_feedback" class="feedback <?php echo $year_feedback_class; ?>">Please provide the year the painting was created.</p>
          <div class="year_input">
            <label> Year Created :</label>
            <input id="year" type="number" name="year" value="<?php echo htmlspecialchars($sticky_year); ?>" required />
          </div>
          <br>
          <p id="sold_feedback" class="feedback <?php echo $sold_feedback_class; ?>">Please provide the date the painting was sold.</p>
          <div class="sold_input">
            <label> Month + Year Sold (e.g., "November 2014"):</label>
            <input id="sold" type="text" name="sold" value="<?php echo htmlspecialchars($sticky_sold); ?>" required />
          </div>
          <br>
          <p id="price_feedback" class="feedback <?php echo $price_feedback_class; ?>">Please provide the sale amount in millions of dollars.</p>
          <div class="price_input">
            <label> Price (in millions of USD):</label>
            <input id="price" type="number" name="price" <?php echo $sticky_price; ?> />
          </div>
          <br>
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
          <p class="feedback <?php echo $file_feedback_class; ?>">Please select a file.</p>
          <div class="file_input">
            <label for="upload-file">Image Upload:</label>
            <input accept="image/png, image/jpeg, image/svg, .png, .jpg, .svg, .jpeg " style="color: transparent; background-color: white;" id="upload-file" type="file" name="file_upload" required />
          </div>
          <h2> Add Tags:</h2>
          <?php foreach ($all_tags as $tag) {
          ?> <label style="position: relative; left: 20px;"><?php echo $tag['tag_name']; ?></label>
            <div style="display: block;">
              <input name=<?php echo $tag['tag_name'] ?> style="position: relative;bottom: 30px; right: 4px;" type="checkbox" value=<?php echo $tag['tag_name'] ?>>
            </div><?php
                } ?>
          <strong class="feedback <?php echo $edit_feedback_class ?>">This tag already exists. Please enter a unique tag name.</strong>

          <div style="margin-top: 30px;">
            <label style="position: relative; right: 4px;">Add a new tag: </label>
            <input style="position: relative;  bottom: 3px;" name="new_tag" type="text">
          </div>


          <div style="margin-top: 90px;margin-bottom: 100px;">
            <input value="Submit Painting" class="submit_painting" type="submit" name="submit">
          </div>

        </form>
      </div> <?php
            } else { ?>
      <div style="left: 32%; bottom: 20%;" class="submit_form">
        <h2>Painting successfully uploaded.</h2>
      </div>
    <?php }
          } else { ?>
    <div style="position: absolute;bottom: 10%;left: 10%;" class="submit_form">
      <h2 style="margin-top: 500px; text-align: center;"> Only users with administrator status can upload to the catalog.</h2>
    </div>
  <?php }
        } else {
  ?><div style="position: absolute;bottom: 10%;left: 10%;" class="submit_form">
    <h2 style="margin-top: 500px; text-align: center;"> Please log in. </h2>
  </div>
<?php }

?>
