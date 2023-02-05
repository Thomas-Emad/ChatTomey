<?php
include_once('db.php');

$errors = [];

// IF You Log In, Don't Sign In OR Sign Up Agin.
if (isset($_COOKIE['user_name'])) {
  header('Location: index.php');
  exit();
} else {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $username = strtolower($_POST['username']);
    $email = filter_var(strtolower($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Check Any Thing Bad ??
    if (strlen($name) < 3) {
      $errors['name'] = "The Name Sholud > 3 Leters";
    }

    // Check Is Taht's Email Exist In DB.
    $sql = "SELECT * FROM `users` WHERE `user_name` = '$username'";
    $que = mysqli_query($db, $sql);
    $rows = mysqli_fetch_row($que);
    if (strlen($username) < 3) {
      $errors['username'] = "The Username Sholud > 3 Leters, And Unique.";
    } elseif (!empty($rows)) {
      $errors['username'] = "It's Used, Try Agin.";
    }

    if (strlen($password) < 5) {
      $errors['password'] = "It's Need Be Stronger, That's Not > 5";
    }
    // Check Is Taht's Email Exist In DB.
    $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
    $que = mysqli_query($db, $sql);
    $rows = mysqli_fetch_row($que);
    if (!empty($rows)) {
      $errors['email'] = "That's Email Exist";
    }


    // If You Don't Have Any Error Creat Account.
    if (empty($errors)) {
      // IF The User Don't Upload Image Do Defalut.
      if (!empty($_FILES['image']['tmp_name'])) {
        // Rname Image Profile And Copy It In His Folder.
        $img = 'img_user/' . $username . '.png';
        $rename = rename($_FILES['image']['tmp_name'], 'img_user/' . $username . '.png');
      } else {
        $img = 'img_user/someone.png';
      }

      $sql = "INSERT INTO `users` (`id`, `name`, `image`, `user_name`, `email`, `password`) VALUES
                (NULL, '$name', ' $img', '$username', '$email', '$password');";
      $que = mysqli_query($db, $sql);
      header('Location: sign-up.php');
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
  <title>Sign In</title>
</head>

<body>
  <div class="box">
    <form action="" method="POST" enctype="multipart/form-data">
      <h2 class="title">Sign In</h2>
      <input type="text" name="name" placeholder="Your Name" value="<?php if (isset($name)) : echo $name;
                                                                    endif; ?>">
      <?php if (isset($errors['name'])) : echo "<span class='error'>- " . $errors['name'] . "</span>";
      endif; ?>

      <input type="text" name="username" placeholder="UserName" value="<?php if (isset($username)) : echo $username;
                                                                        endif; ?>">
      <?php if (isset($errors['username'])) : echo "<span class='error'>- " . $errors['username'] . "</span>";
      endif; ?>

      <input type="email" name="email" placeholder="Email" value="<?php if (isset($email)) : echo $email;
                                                                  endif; ?>">
      <?php if (isset($errors['email'])) : echo "<span class='error'>- " . $errors['email'] . "</span>";
      endif; ?>

      <input type="password" name="password" placeholder="Password">
      <?php if (isset($errors['password'])) : echo "<span class='error'>- " . $errors['password'] . "</span>";
      endif; ?>
      <input type="file" name="image">
      <input type="submit" value="Sign In">
      <a href="sign-up.php" class="trans_page">I Have Account!!</a>
    </form>
  </div>
</body>

</html>