<?php
include_once('db.php');

// Chack If The Cookies Here => Not?, LogIN Now!
if (isset($_COOKIE['user_name'])) {
  $user_name = $_COOKIE['user_name'];
  $name = $_COOKIE['name'];
  $email = $_COOKIE['email'];
  $img_user = $_COOKIE['img_user'];
  $password_now = $_COOKIE['password'];
  $time_create = $_COOKIE['time_create'];
} else {
  header('Location: sign-up.php');
  exit();
}

// Send Message
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if (isset($_POST['mesg'])) {
  $megssage = filter_var($_POST['mesg'], FILTER_SANITIZE_STRING);
  if (strlen($megssage) !== 0) {
    $sql = "INSERT INTO `messages` (`id`, `user_name`, `message`, `time_send`) VALUES
                  (NULL, '$user_name', '$megssage', current_timestamp());";
    $que = mysqli_query($db, $sql);
    $_POST['mesg'] = '';
    header('Location: index.php');
  }
}

// If You Click In Log Out Remove Cookies And Transform You.
if (isset($_POST['logout'])) {
  setcookie('name', '', 0);
  setcookie('user_name', '', 0);
  setcookie('img_user', '', 0);
  setcookie('email', '', 0);
  setcookie('password', '', 0);
  setcookie('time_create', '', 0);
  header('Location: sign-up.php');
}

// Change Your Information For Your Profile
if (isset($_POST['send_change'])) {
  $new_name = $_POST['new_name'];
  $new_password = $_POST['new_password'];

  if (!empty($_FILES['change_img']['tmp_name'])) {
    unlink('img_user/' . $user_name . '.png');
    $img = 'img_user/' . $new_name . '.png';
    $rename = rename($_FILES['change_img']['tmp_name'], $img);
    $sql = "UPDATE `users` SET `name` = '$new_name', `password` = '$new_password', `image` = '$img' WHERE `users`.`user_name` = '$user_name';";
  } else {
    $sql = "UPDATE `users` SET `name` = '$new_name', `password` = '$new_password' WHERE `users`.`user_name` = '$user_name';";
  }
  $que = mysqli_query($db, $sql);

  // Restart Cookies
  setcookie('name', $new_name);
  setcookie('password', $new_password);
  setcookie('img_user', $img);
  header('Location: index.php');
}

// Delete Account
if (isset($_POST['delete_account'])) {
  $sql = "DELETE FROM `users` WHERE `users`.`user_name` = '$user_name';";
  mysqli_query($db, $sql);
  unlink('img_user/' . $user_name . '.png');
  setcookie('name', '', 0);
  setcookie('user_name', '', 0);
  setcookie('img_user', '', 0);
  setcookie('email', '', 0);
  setcookie('password', '', 0);
  setcookie('time_create', '', 0);
  header('Location: sign-up.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- It's Do Deley If The Internet Don't Work -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;500;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/format_css_code.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Chat</title>
</head>

<body>

  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content container">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Profile</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="POST" class="info-profile" enctype="multipart/form-data">
            <?php echo "<img src='$img_user' alt='profile_img' class='img_profile' style='width: 70px; height:70px;'>"; ?>
            <div class="input-group flex-nowrap mb-2">
              <span class="input-group-text" id="addon-wrapping">Username</span>
              <input type="text" class="form-control" value="<?php echo $user_name; ?>" placeholder="Your Username" disabled>
            </div>
            <div class="input-group flex-nowrap mb-2">
              <span class="input-group-text" id="addon-wrapping">Email</span>
              <input type="text" class="form-control" value="<?php echo $email; ?>" placeholder="Your Email" disabled>
            </div>
            <div class="input-group flex-nowrap mb-2">
              <span class="input-group-text" id="addon-wrapping">Your Name</span>
              <input type="text" class="form-control" name="new_name" value="<?php echo $name; ?>" placeholder="Name">
            </div>
            <div class="input-group flex-nowrap mb-2">
              <span class="input-group-text" id="addon-wrapping">Password</span>
              <input type="text" class="form-control" name="new_password" value="<?php echo $password_now; ?>" placeholder="Password">
            </div>
            <div class="input-group mb-2">
              <input type="file" class="form-control" name="change_img">
            </div>
            <div class="input-group flex-nowrap mb-2">
              <span class="input-group-text" id="addon-wrapping">Time Create</span>
              <input type="text" class="form-control" name="img_profile_new" value="<?php echo $time_create; ?>" placeholder="Time Create" disabled>
            </div>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_acc">
              Delete Account
            </button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input type="submit" name="send_change" value="Change" class="btn btn-primary">
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Delete Account -->
  <div class="modal fade" id="delete_acc" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Your Account!!</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Do You Want Real Delete Your Account?!
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <form action="" method="POST">

            <input type="submit" name="delete_account" value="Delete Account" class="btn btn-danger delete_account">
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Chat Box -->
  <div class="chat">
    <div class="title_page">
      <h2>Chat<Span>Tomey</Span></h2>
      <form action="" method="POST">
        <div class="profile-info" data-bs-toggle='modal' data-bs-target='#staticBackdrop'>
          <?php echo "<img src='$img_user' alt='profile_img' class='img_profile'>"; ?>
          <h2 class="username"><?php echo $name;  ?></h2>
        </div>
        <input type="submit" value="LogOut" name="logout" class="logout">
      </form>
    </div>
    <div class="message">
      <div class="box_mess">
        <?php
        // Get All Message, And Ready To Print It.
        $sql = "SELECT * FROM `messages`;";
        $que = mysqli_query($db, $sql);
        $rows = mysqli_fetch_all($que);

        foreach ($rows as $row) {
          if ($row[1] == $user_name) {
            echo "
            <div class='me'>
            <div class='text'>
              <img src='$img_user' class='img_profile'>
                <span class='mess'>$row[2]</span>
              </div>
              
              <span class='time_send'>$row[3], ($name)</span>
            </div>
          ";
          } elseif ($row[1] != $user_name) {
            echo "
            <div class='other'>
              <div class='text'>
                <img src='img_user/$row[1].png'  class='img_profile' onerror='this.onerror=null;this.src=`img_user/someone.png`;'>
                <span class='mess'>$row[2]</span>
              </div>
              <span class='time_send'>$row[3], ($row[1])</span>
            </div>
          ";
          }
        }
        ?>
      </div>
    </div>
    <div class="send_mess">
      <form action="" method="POST">
        <input type="text" name="mesg" placeholder="Write Your Message..." value="<?php if (isset($_POST['mesg'])) {
                                                                                    echo '';
                                                                                  } ?>">
        <input type="submit" value="Send">
      </form>
    </div>
  </div>
  <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>