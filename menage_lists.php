<?php
session_start();

include 'includes/classes/notes.php';
/**
if (!isset($_SESSION['signed_in'])){
    header('Location:sign_in.php');
}
**/
if (isset($_POST['submit'])){
    if ($_POST['submit'] == 'add_list'){
        notes::add_list($_POST['title'], $_POST['description']);
        header('Location:my_notes.php');

    }
    if ($_POST['submit'] == 'edit_list' and isset($_SESSION['list_id'])){
        notes::update_list($_SESSION['list_id'],$_POST['title'], $_POST['description']);
        header('Location:my_notes.php');
    }
    if ($_POST['submit'] == 'add_username' and isset($_SESSION['list_id'])){
        notes::add_user_to_list($_SESSION['list_id'],$_POST['username']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

}
