<?php

include 'includes/classes/notes.php';
include 'includes/connect.php';

/**
if (!isset($_SESSION['signed_in'])){
header('Location:sign_in.php');
}
 **/

if (isset($_GET['id'])){
    $id = $_GET['id'];


    $status = notes::get_task_status($id);
    if ($status == "todo") {
        notes::change_note_status($id, 'done');

    }
    if ($status == "done"){
        notes::change_note_status($id, 'todo' );
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    //header("location:javascript://history.go(-1)");
    //header('Location:list.php?id='.$id);
}