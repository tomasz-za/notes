<?php
session_start();

$signed_in = false;
//CHECK IF CONNECTION CAN BE MAKE
require_once "includes/connect.php";
include 'includes/classes/notes.php';

include 'sign.php';

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

if (isset($_SESSION['user_type']) and $_SESSION['user_type'] == 'user'){
    header('Location:index.php');
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
        <a href="#" class="navbar-brand mb-0 h1 yellow">
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



            if (isset($_SESSION['signed_in']) and $_SESSION['user_type'] == 'user'){
                echo '
            <ul class="navbar-nav">
                <li class="nav-item active" >
                    <a href="my_notes.php" class="nav-link">my notes</a>
                </li>
                <li class="nav-item active" >
                    <a href="" class="nav-link">calendar</a>
                </li>
                <li class="nav-item active" >
                    <a href="" class="nav-link">notifications</a>
                </li>
            </ul>';

            } elseif (isset($_SESSION['user_type']) and $_SESSION['user_type'] == 'admin'){
                echo '
            <ul class="navbar-nav">
                <li class="nav-item active" >
                    <a href="my_notes.php" class="nav-link">my notes</a>
                </li>
                <li class="nav-item active" >
                    <a href="" class="nav-link">calendar</a>
                </li>
                <li class="nav-item active" >
                    <a href="" class="nav-link">notifications</a>
                </li>
                <li class="nav-item active" >
                    <a href="" class="nav-link">admin panel</a>
                </li>
            </ul>';
            }
            if (isset($_SESSION['signed_in'])){
                echo " <a href='profile.php' class='nav-link yellow'> Profile </a> <p> ____ </p> <a href='sign.php?sign_out=true' class='nav-link yellow' >".$lang['sign_out']."</a>";
            } else {
                echo "<a href='sign_in.php' class='nav-link yellow'>".$lang['sign_in']."</a> <p>  ____  </p> <a href='sign_up.php' class='nav-link yellow' >".$lang['sign_up']."</a>";
            }
            ?>

            <div class="px-5">
                <a href="index.php?lang=pl" class='nav-link yellow'>pl</a>
                <a href="index.php?lang=en" class='nav-link yellow'>en</a>
            </div>
        </div>
</nav>

<div class="container" style="margin-top: 200px">

    <h1>Admin panel</h1>
    <h3><?php print_r($_SESSION)?></h3>
    <?php
    if ($connection_status){
        echo $lang['connected']."\n";
        if (isset($_SESSION['username'])){echo $_SESSION['username'];} else { echo "username error";}

    }
    ?>
    <h2>users Lists</h2>
    <?php notes::print_all_lists();?>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>