<?php

session_start();
include 'includes/connect.php';
include 'includes/classes/notes.php';

if (isset($_FILES['imported_notes'])){
    $file = $_FILES['imported_notes'];
    $list_id = $_SESSION['list_id'];

    $file_name = $_FILES['imported_notes']['name'];
    $file_name_tmp = $_FILES['imported_notes']['tmp_name'];
    $file_size = $_FILES['imported_notes']['size'];
    $file_error = $_FILES['imported_notes']['error'];
    $file_type = $_FILES['imported_notes']['type'];

    $file_explode = explode('.', $file_name);
    $file_extension = strtolower(end($file_explode));

    //$allowed = 'txt';

    if ($file_extension == 'txt'){
        if ($file_error === 0 ){
            if ($file_size < 500000 ){
//------------------------------------------------------------------------------
                move_uploaded_file($file_name_tmp, 'files/upload.txt');

                notes::push_data_from_file('files/upload.txt', $list_id);
                header('Location:list.php?id='.$list_id);

            } else {
                $_SESSION['e'] = 'file is too big';
            }
        } else {
            $_SESSION['e'] = 'problem uploading image';
        }

    } else {
        $_SESSION['e'] = 'wrong extension';
    }

}
