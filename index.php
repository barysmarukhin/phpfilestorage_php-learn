<?php
require_once("includes/initialize.php");

if($session->is_logged_in()) {
  redirect_to("upload.php");
}

// Remember to give your form's submit tag a name="submit" attribute!
if (isset($_POST['submit'])) { // Form has been submitted.

  $usermail = trim($_POST['usermail']);
  $password = trim($_POST['password']);
  
  // Check database to see if usermail/password exist.
  $found_user = User::authenticate($usermail, $password);
  
  if ($found_user) {
    $session->login($found_user);
    redirect_to("upload.php");
  } else {
    // usermail/password combo was not found in the database
    $message = "Неверная комбинация Email/пароль.";
  }
  
} else { // Form has not been submitted.
  $usermail = "";
  $password = "";
}

?>
<?php include_layout_template('header.php'); ?>

    <h2>Авторизуйтесь</h2>
    <?php echo output_message($message); ?>

    <form action="index.php" method="post">
      <table>
        <tr>
          <td>Ваш email:</td>
          <td>
            <input type="email" name="usermail" maxlength="30" value="<?php echo htmlentities($usermail); ?>" />
          </td>
        </tr>
        <tr>
          <td>Ваш пароль:</td>
          <td>
            <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <input type="submit" name="submit" value="Войти" />
          </td>
        </tr>
      </table>
    </form>

<?php include_layout_template('footer.php'); ?>
