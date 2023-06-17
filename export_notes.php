<?php
session_start();
include 'includes/connect.php';
include 'includes/classes/notes.php';

if (isset($_SESSION['list_id'])){
    $list_id = $_SESSION['list_id'];
}

notes::print_my_notes_to_file($list_id);


header("Content-Disposition: attachment; filename=".basename('files/file.txt') );
readfile('files/file.txt');
file_put_contents("files/file.txt", "");
exit();