<?php
include "includes/init.php";
include "includes/header.php";
$filter = False;
$result = exec_sql_query($db, "SELECT * FROM art;");
$records = $result->fetchAll();


if (!empty($_POST['tags'])) {
  if ($_POST['tags'] != 'select') {
    $result = exec_sql_query($db, "SELECT * FROM art INNER JOIN art_tags ON art.id = art_tags.art_id WHERE art_tags.tag_id = :id;", array(":id" => $_POST['tags']));
    $records = $result->fetchAll();
    $filter = TRUE;
  } else {
    $result = exec_sql_query($db, "SELECT * FROM art;");
    $records = $result->fetchAll();
  }
}


?>

<main>

  <div class="form">
    <form action="/home" method="post">
      <select onchange="this.form.submit()" name="tags" id="tags" class="tags">
        <option <?php if ($_POST['tags'] == $tags[$x]['id']) {
                  echo 'selected="selected"';
                } ?> value="select">Select a tag</option>
        <?php $tags = exec_sql_query($db, "SELECT * FROM tags");
        $tags = $tags->fetchAll();
        for ($x = 0; $x < count($tags); $x++) {
          $tag_name = $tags[$x]['tag_name']
        ?>
          <option <?php if ($_POST['tags'] == $tags[$x]['id']) {
                    echo 'selected="selected"';
                  } ?> value=<?php echo $tags[$x]['id']; ?>><?php echo $tag_name; ?></option> <?php } ?>
      </select>
    </form>
  </div>
  <div class="gallery">
    <?php
    foreach ($records as $record) {
      $x = "id";
      if ($filter) {
        $x = "art_id";
      } ?>
      <div class="title">
        <a href="/home/art?<?php echo http_build_query(array('id' => $record[$x])); ?>">
          <img alt="<?php echo htmlspecialchars($record["title"]) ?>" class="painting_img" src="/public/uploads/art/<?php echo $record[$x] . '.' . $record['file_ext']; ?>">
          <!-- Source for seed data: https://www.wikipedia.com/ -->
        </a>
        <div class="title_link"><a href="/home/art?<?php echo http_build_query(array('id' => $record[$x]));
                                                    ?>"><?php echo htmlspecialchars($record["title"]);
                                                          ?></a></div>
      </div>
    <?php
    }

    ?>



  </div>
</main>

</body>

</html>
