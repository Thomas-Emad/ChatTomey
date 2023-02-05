<?php
include_once('db.php');

// Chack If The Cookies Here => Not?, LogIN Now!
if (isset($_COOKIE['user_name'])) {
  $user_name = $_COOKIE['user_name'];
  $name = $_COOKIE['name'];
  $img_user = $_COOKIE['img_user'];
} else {
  header('Location: sign-up.php');
  exit();
}

// Send Message
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
  setcookie('user_name', '', 0);
  setcookie('name', '', 0);
  setcookie('email', '', 0);
  setcookie('img_user', '', 0);
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
  <link rel="stylesheet" href="css/format_css_code.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Chat</title>
</head>

<body>
  <div class="chat">
    <div class="title_page">
      <h2>Chat<Span>Tomey</Span></h2>
      <form action="" method="POST">
        <?php echo "<img src='$img_user' alt='profile_img' class='img_profile'>"; ?>
        <h2 class="username"><?php echo $name;  ?></h2>
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
</body>

</html>