<?php

include 'includes/classes/notes.php';
include 'includes/connect.php';

if (isset($_GET['id'])){
    $id = $_GET['id'];

    notes::delete_note($id);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

