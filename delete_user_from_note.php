<?php
session_start();

include 'includes/classes/notes.php';
include 'includes/connect.php';

if (isset($_GET['user'])){
    $username = $_GET['user'];
    $list_id = $_SESSION['list_id'];
    $note_id = $_SESSION['note_id'];

    notes::delete_user_from_note($list_id, $note_id, $username);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

