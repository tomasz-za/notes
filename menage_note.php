<?php
session_start();

include 'includes/classes/notes.php';
include 'includes/connect.php';

print_r($_GET);
print_r($_POST);
echo "||";
print_r($_SESSION);

if (isset($_SESSION['list_id'])){
    $list_id = $_SESSION['list_id'];
}

/**
if (!isset($_SESSION['signed_in'])){
header('Location:sign_in.php');
}
 **/
if ($_POST['submit']){
    if ($_POST['submit'] == 'add_note' and isset($_SESSION['list_id'])){
        notes::add_note($_SESSION['list_id'], $_POST['title'], $_POST['description'], $_POST['priority'], $_POST['execution_date'] );

    }
    if ($_POST['submit'] == 'update_note' and isset($_SESSION['note_id'])){
        notes::update_note($_SESSION['note_id'], $_POST['title'], $_POST['description'], $_POST['priority'], $_POST['execution_date']);
        //header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    if ($_POST['submit'] == 'add_username' and isset($_SESSION['list_id']) and isset($_SESSION['note_id'])){
        notes::add_user_to_note($_SESSION['list_id'], $_SESSION['note_id'],$_POST['username']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    header('Location:list.php?id='.$list_id);
}
