<?php
include_once('db.php');

if (isset($_COOKIE['user_name'])) {
  header('Location: index.php');
  exit();
} else {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['pass'];
    $sql = "SELECT * FROM users WHERE email ='$email' AND password='$password'";
    $que = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($que);
    if ($que->num_rows == 1) {
      setcookie('name', $row['name'], (time() + (60 * 60  * 60 * 24 * 30)));
      setcookie('user_name', $row['user_name'], (time() + (60 * 60  * 60 * 24 * 30)));
      setcookie('email', $row['email'], (time() + (60 * 60  * 60 * 24 * 30)));
      setcookie('img_user', $row['image'], (time() + (60 * 60 * 24 * 30)));
      header('Location: index.php');
    } else {
      $error = 'Error In Your Email Or Password.';
    }
  }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/format_css_code.css">
  <link rel="stylesheet" href="css/sign.css">
  <title>Sign Up</title>
</head>

<body>
  <div class="box">
    <form action="" method="POST">
      <h2 class="title">Sign Up</h2>
      <input type="email" name="email" placeholder="Email..." value="<?php if (isset($email)) {
                                                                        echo $email;
                                                                      } ?>">
      <input type="password" name="pass" placeholder="Password...">
      <input type="submit" value="Log">
      <?php
      if (isset($error)) {
        echo "<p class='error'>$error</p>";
      }
      ?>
      <a href="sign-in.php" class="trans_page">Don't Have Account?!</a>

    </form>
  </div>
</body>

</html>