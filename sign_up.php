<?php
session_start();

$signed_in = false;
//CHECK IF CONNECTION CAN BE MAKE
require_once "includes/connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);
try {
    if ($connection->connect_errno) {
        throw Exception(mysqli_connect_errno());
    } else {

        $connection_status = true;

    }
} catch (Exception $e) {
    echo 'Cannot connect to the server';
    //echo 'Exeption : '.$e->getCode().'comunicate : '.$e->getMessage();
}

// SETTING LANGUAGE

if (!isset($_SESSION['lang'])){
    $_SESSION['lang']='en';
} elseif ($_GET['lang'] && $_SESSION['lang'] != $_GET['lang']){
    $_SESSION['lang'] = $_GET['lang'];
}
require_once "includes/languages/".$_SESSION['lang'].".php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="css/master.css">
</head>
<body style="height: 2000px" >

<nav class="navbar fixed-top navbar-expand-sm navbar-light bg-light white">
    <div class="container">
        <a href="index.php" class="navbar-brand mb-0 h1 yellow">
            <h2>Notes</h2>
        </a>
    </div>
    <div class="container">
        <button type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                class="navbar-toggler"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <?php
            if ($signed_in){
                echo '
            <ul class="navbar-nav">
                <li class="nav-item active" >
                    <a href="" class="nav-link">my notes</a>
                </li>
                <li class="nav-item active" >
                    <a href="" class="nav-link">calendar</a>
                </li>
                <li class="nav-item active" >
                    <a href="" class="nav-link">notifications</a>
                </li>
            </ul>';
            }
            if ($signed_in){
                echo " <a href='profile.php' class='nav-link yellow'> Profile </a> ";
            } else {
                echo "<a href='sign_in.php' class='nav-link yellow'>".$lang['sign_in']."</a> <p>  ____  </p> <a href='sign_up.php' class='nav-link yellow' >".$lang['sign_up']."</a>";
            }
            ?>

            <div class="px-5">
                <a href="sign_up.php?lang=pl" class='nav-link yellow'>pl</a>
                <a href="sign_up.php?lang=en" class='nav-link yellow'>en</a>
            </div>
        </div>
</nav>

<div class="container" style="margin-top: 200px">


    <div class="center-screen">
        <div class="box">
            <form method="post" action="sign.php">
                <h2><?php echo $lang['sign_up']; ?></h2> <br>
                <p><?php echo $lang['enter_your_email']; ?></p>
                <input type="email" name="email" value=""> <br>
                <?php
                if (isset($_SESSION['e_email'])) {
                    echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
                    unset($_SESSION['e_email']);
                }
                ?>
                <br>
                <p><?php echo $lang['create_a_password']; ?></p>
                <input type="password" name="password" value=""> <br>
                <?php
                if (isset($_SESSION['e_password'])) {
                    echo '<div class="error">' . $_SESSION['e_password'] . '</div>';
                    unset($_SESSION['e_password']);
                }
                ?>
                <br>
                <p> <?php echo $lang['enter_a_username']; ?></p>
                <input type="text" name="username" value=""> <br>
                <?php
                if (isset($_SESSION['e_username'])) {
                    echo '<div class="error">' . $_SESSION['e_username'] . '</div>';
                    unset($_SESSION['e_username']);
                }
                ?>
                <br>
                <br>

                <br>
                <label>
                    <input type="checkbox" name="terms_and_conditions">
                    <p><?php echo $lang['terms']; ?></p>
                </label>
                <?php
                if (isset($_SESSION['e_terms_and_conditions'])) {
                    echo '<div class="error">' . $_SESSION['e_terms_and_conditions'] . '</div>';
                    unset($_SESSION['e_terms_and_conditions']);
                }
                ?>
                <br><br>
                <button id="submit" name="submit" value="sign_up"><?php echo $lang['Sign_up']; ?></button>
                <br><br>
                <p><?php echo $lang['Already_have_an_account']; ?><a href="sign_in.php" class="yellow"> <?php echo $lang['Sign_in']; ?> </a> . </p>
            </form>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>