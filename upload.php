<?php require_once("includes/initialize.php"); ?>
<?php
  //If the user had not registered, redirect him to index page
  if (!$session->is_logged_in()) { redirect_to("index.php"); }

  // 1. the current page number ($current_page)
  $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

  // 2. records per page ($per_page)
  $per_page = 3;

  // 3. total record count ($total_count)
  $total_count = File::count_all();
  //exit($total_count);
  

  // Find all photos
  // use pagination instead
  //$photos = Photograph::find_all();
  
  $pagination = new Pagination($page, $per_page, $total_count);
  
  // Instead of finding all records, just find the records 
  // for this page
  $sql = "SELECT * FROM files ";
  //$sql .= "ORDER BY id DESC";
  $sql .= "LIMIT {$per_page} ";
  $sql .= "OFFSET {$pagination->offset()}";
  $files = File::find_by_sql($sql);
  
  // Need to add ?page=$page to all links we want to 
  // maintain the current page (or store $page in $session)


  $max_file_size = 1048576;   // expressed in bytes
                              //     10240 =  10 KB
                              //    102400 = 100 KB
                              //   1048576 =   1 MB
                              //  10485760 =  10 MB

  if(isset($_POST['submit'])) {
    $file = new File();
    $file->caption = $_POST['caption'];
    $file->attach_file($_FILES['file_upload'], $session->usermail);
    if($file->save()) {
      // Success
      $session->message("Файл загружен успешно.");
      //redirect_to('upload.php');
    } else {
      // Failure
      $message = join("<br />", $file->errors);
    }
  }
?>

<?php include_layout_template('header.php'); ?>
  <p>Приветствую, Вас, <?php echo $session->first_name;?></p>
  <p><a href="logout.php">Выйти из системы</a></p>
<!-- Show files -->
<?php foreach($files as $file): ?>
  <div style="border:1px solid #000; padding:5px 10px; width:80%;margin-bottom:10px;">
    <h2>Файл:<?php echo $file->id; ?></h2>
    <p>Имя файла:<?php echo $file->filename; ?></p>
    <p>Описание файла:<?php echo $file->caption; ?></p>
    <p>Файл загрузил: <em><?php echo $file->usermail; ?></em></p>
  </div>
<?php endforeach; ?>

<!-- Show pagination -->
<div id="pagination" style="clear: both;">
<?php
  if($pagination->total_pages() > 1) {
    
    if($pagination->has_previous_page()) { 
      echo "<a href=\"upload.php?page=";
      echo $pagination->previous_page();
      echo "\">&laquo; Назад</a> "; 
    }

    for($i=1; $i <= $pagination->total_pages(); $i++) {
      if($i == $page) {
        echo " <span class=\"selected\">{$i}</span> ";
      } else {
        echo " <a href=\"upload.php?page={$i}\">{$i}</a> "; 
      }
    }

    if($pagination->has_next_page()) { 
      echo " <a href=\"upload.php?page=";
      echo $pagination->next_page();
      echo "\">Вперед &raquo;</a> "; 
    }
  }
?>
</div>

<h2>Загрузите файл</h2>
<?php echo output_message($message); ?>
<form action="upload.php" enctype="multipart/form-data" method="POST">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>" />
  <p><input type="file" name="file_upload" /></p>
  <p>Описание: <input type="text" name="caption" value="" required/></p>
  <input type="submit" name="submit" value="Загрузить" />
</form>

<?php include_layout_template('footer.php'); ?>
