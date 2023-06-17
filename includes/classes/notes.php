<?php
//session_start();
class notes {

    static function push_data_from_file($path, $list_id){
        include 'includes/connect.php';
        $file = fopen($path,'r');

        $zawartosc = '';

// przypisanie zawartości do zmiennej
        while(!feof($file))
        {
            $line = fgets($file);
            $line_explode = explode(',',$line);

            if (isset($line_explode[1])){
                print_r($line_explode);
                $title = $line_explode[0];
                $description = $line_explode[1];
                $priority = $line_explode[2];
                $date = $line_explode[3];
            }



            self::add_note($list_id,$title,$description,$priority,$date);
        }
    }

    static function get_my_lists()
    {
        include 'includes/connect.php';
        $username = $_SESSION['username'];

        $result = $connection->query("SELECT list_id FROM connections WHERE username='$username';");


        $list_id_assoc = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list_id_assoc[] = $row;
        }

        $data_size = count($list_id_assoc);
        $list_id_duplicate = array();

        for ($i = 0; $i < $data_size; $i++) {
            array_push($list_id_duplicate, $list_id_assoc[$i]['list_id']);
        }
        $list_id = array_unique($list_id_duplicate);

        return $list_id;
    }

    static function get_list_users($list_id)
    {
        include 'includes/connect.php';
        //$username = $_SESSION['username'];

        $result = $connection->query("SELECT username FROM connections WHERE list_id='$list_id';");


        $list_users_assoc = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list_users_assoc[] = $row;
        }

        $data_size = count($list_users_assoc);
        $list_users_duplicate = array();

        for ($i = 0; $i < $data_size; $i++) {
            array_push($list_users_duplicate, $list_users_assoc[$i]['username']);
        }
        $list_of_users = array_unique($list_users_duplicate);

        return $list_of_users;
    }

    static function get_note_users($note_id)
    {
        include 'includes/connect.php';
        //$username = $_SESSION['username'];

        $result = $connection->query("SELECT username FROM connections WHERE task_id='$note_id';");


        $list_users_assoc = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list_users_assoc[] = $row;
        }

        $data_size = count($list_users_assoc);
        $list_users_duplicate = array();

        for ($i = 0; $i < $data_size; $i++) {
            array_push($list_users_duplicate, $list_users_assoc[$i]['username']);
        }
        $list_of_users = array_unique($list_users_duplicate);

        return $list_of_users;
    }

    static function print_list_users($list_id)
    {
        // include 'includes/connect.php';
        $list_of_users = self::get_list_users($list_id);
        foreach ($list_of_users as $user) {
            //$result = $connection->query("SELECT title FROM lists where id='$id'");
            //$row = mysqli_fetch_array($result);
            //echo "<a href='list.php?id=$id'>".$row['title']."</a><br>";
            echo "<a href=''>".$user."</a><a href='delete_user_from_list.php?user=$user'><i class='bi bi-trash3-fill'></i></a><br>";
        }
    }

    static function print_note_users($note_id)
    {
        // include 'includes/connect.php';
        $list_of_users = self::get_note_users($note_id);
        foreach ($list_of_users as $user) {
            //$result = $connection->query("SELECT title FROM lists where id='$id'");
            //$row = mysqli_fetch_array($result);
            //echo "<a href='list.php?id=$id'>".$row['title']."</a><br>";
            echo "<a href=''>".$user."</a><a href='delete_user_from_note.php?user=$user'><i class='bi bi-trash3-fill'></i></a><br>";
        }
    }

    static function get_list_title($id){
        include 'includes/connect.php';
        $result = $connection->query("SELECT title FROM lists where id='$id'");
        $row = mysqli_fetch_array($result);
        if (isset($row['title'])){
            return $row['title'];
        }

    }

    static function add_user_to_list($list_id, $username){
        include 'includes/connect.php';

        if ($result = $connection->query("SELECT * FROM users WHERE username='$username';")){
            $num_users = $result->num_rows;

            if ($num_users>0){

                if ($connection->query("INSERT INTO connections VALUES ('0',null,'$list_id', '$username')")) {

                } else {
                    throw new Exception($connection->error);
                }

            }
        }

    }

    static function add_user_to_note($list_id,$note_id, $username){
        include 'includes/connect.php';

        if ($result = $connection->query("SELECT * FROM users WHERE username='$username';")){
            $num_users = $result->num_rows;

            if ($num_users>0){

                if ($connection->query("INSERT INTO connections VALUES ('0',$note_id,'$list_id', '$username')")) {

                } else {
                    throw new Exception($connection->error);
                }

            }
        }

    }

    static function delete_user_from_note($list_id, $note_id, $username){
        include 'includes/connect.php';
        if ($connection->query("DELETE FROM connections WHERE username='$username' AND list_id='$list_id' AND task_id='$note_id';")){


        }else {
            throw new Exception($connection->error);
        }
    }

    static function delete_user_from_list($list_id, $username){
        include 'includes/connect.php';
        if ($connection->query("DELETE FROM connections WHERE username='$username' AND list_id='$list_id';")){


        }else {
            throw new Exception($connection->error);
        }
    }

    static function get_list_description($id){
        include 'includes/connect.php';
        $result = $connection->query("SELECT description FROM lists where id='$id'");
        $row = mysqli_fetch_array($result);
        if (isset($row['description'])){
            return $row['description'];
        }

    }

    static function print_my_lists()
    {
       // include 'includes/connect.php';
        $list_id = self::get_my_lists();
        foreach ($list_id as $id) {
            //$result = $connection->query("SELECT title FROM lists where id='$id'");
            //$row = mysqli_fetch_array($result);
            //echo "<a href='list.php?id=$id'>".$row['title']."</a><br>";
            echo "<a href='list.php?id=$id'>".self::get_list_title($id)."</a><a href='edit_list.php?id=$id'><i class='bi bi-pencil-fill'></i></a><a href='delete_list.php?id=$id'><i class='bi bi-trash3-fill'></i></a><br>";
        }
    }

    static function get_number_of_my_notes($username)
    {
        include 'includes/connect.php';

        $result = $connection->query("SELECT task_id FROM connections WHERE username='$username'");
        if (!$result) throw new Exception($connection->error);
        $notes_num = $result->num_rows;
        return $notes_num;
    }

    static function add_list($title, $description)
    {
        include 'includes/connect.php';

        if (isset($_SESSION['username'])){
            $username = $_SESSION['username'];
        }

        if ($connection->query("INSERT INTO lists VALUES ('0','$title','$description', '$username')")) {

            $list_id_result = $connection->query("SELECT id FROM lists WHERE title='$title' AND description='$description' AND author='$username'");
            $row = mysqli_fetch_array($list_id_result);
            $list_id = $row['id'];

            if ($connection->query("INSERT INTO connections VALUES ('0',null,'$list_id','$username')")) {
                echo "ok";
            } else {
                throw new Exception($connection->error);
            }

            //header('Location:my_notes.php');
        } else {
            throw new Exception($connection->error);
        }
    }

    static function add_note($list_id, $title, $description, $priority, $execution_date){
        include 'includes/connect.php';

        if (isset($_SESSION['username'])){
            $username = $_SESSION['username'];
        }

        if ($connection->query("INSERT INTO tasks VALUES ('0','$title','$description', '$priority','$execution_date','todo')")) {

            $note_id_result = $connection->query("SELECT id FROM tasks WHERE title='$title' AND description='$description' AND priority='$priority' AND execution_date='$execution_date'");
            $row = mysqli_fetch_array($note_id_result);
            $note_id = $row['id'];

            if ($connection->query("INSERT INTO connections VALUES ('0','$note_id','$list_id','$username')")) {
                echo "ok";
            } else {
                throw new Exception($connection->error);
            }

            //header('Location:my_notes.php');
        } else {
            throw new Exception($connection->error);
        }
    }

    static function update_note($note_id, $title, $description, $priority, $execution_date){
        include 'includes/connect.php';


        if ($connection->query("UPDATE tasks set title='$title', description='$description', priority='$priority', execution_date='$execution_date' WHERE id ='$note_id';")) {

        } else {
            throw new Exception($connection->error);
        }
    }

    static function update_list($list_id, $title, $description){
        include 'includes/connect.php';

        if ($connection->query("UPDATE lists set title='$title', description='$description' WHERE id ='$list_id';")) {

        } else {
            throw new Exception($connection->error);
        }
    }

    static function get_number_of_my_notes_in_list($list_id){
        include 'includes/connect.php';

        $result = $connection->query("SELECT task_id FROM connections WHERE list_id='$list_id' AND task_id IS NOT NULL");
        //AND username='$username'
        if (!$result) throw new Exception($connection->error);
        $notes_num = $result->num_rows;
        return $notes_num;
    }

    static function get_my_notes($list_id){
        include 'includes/connect.php';
        $username = $_SESSION['username'];

        $result = $connection->query("SELECT task_id FROM connections WHERE username='$username' AND list_id='$list_id';");


        $task_id_assoc = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $task_id_assoc[] = $row;
        }

        $data_size = count($task_id_assoc);
        $list_id_duplicate = array();

        for ($i = 0; $i < $data_size; $i++) {
            array_push($list_id_duplicate, $task_id_assoc[$i]['task_id']);
        }
        $list_id = array_unique($list_id_duplicate);

        return $list_id;
    }

    static function get_task_title($id){
        include 'includes/connect.php';
        $result = $connection->query("SELECT title FROM tasks where id='$id'");
        $row = mysqli_fetch_array($result);

        if (isset($row['title'])){
            return $row['title'];
        }
    }

    static function get_task_description($id){
        include 'includes/connect.php';
        $result = $connection->query("SELECT description FROM tasks where id='$id'");
        $row = mysqli_fetch_array($result);

        if (isset($row['description'])){
            return $row['description'];
        }
    }

    static function get_task_priority($id){
        include 'includes/connect.php';
        $result = $connection->query("SELECT priority FROM tasks where id='$id'");
        $row = mysqli_fetch_array($result);

        if (isset($row['priority'])){
            return $row['priority'];
        }
    }

    static function get_task_execution_date($id){
        include 'includes/connect.php';
        $result = $connection->query("SELECT execution_date FROM tasks where id='$id'");
        $row = mysqli_fetch_array($result);

        if (isset($row['execution_date'])){
            return $row['execution_date'];
        }
    }

    static function get_task_status($id){
        include 'includes/connect.php';
        $result = $connection->query("SELECT status FROM tasks where id='$id'");
        $row = mysqli_fetch_array($result);
        if (isset($row['status'])){
            return $row['status'];
        }
        //return $row['status'];
    }

    static function button_status($status, $priority, $id){
       if ($status == 'done'){
           if ($priority == 'low'){
                return "<a href='change_note_status.php?id=$id'><i class='bi bi-circle-fill low'></i></a>";
           }
           if ($priority == 'mid'){
               return "<a href='change_note_status.php?id=$id'><i class='bi bi-circle-fill mid'></i></a>";
           }
           if ($priority == 'high'){
               return "<a href='change_note_status.php?id=$id'><i class='bi bi-circle-fill high'></i></a>";
           }
       } elseif ($status == 'todo'){
           if ($priority == 'low'){
               return "<a href='change_note_status.php?id=$id'><i class='bi bi-circle low'></i></a>";
           }
           if ($priority == 'mid'){
               return "<a href='change_note_status.php?id=$id'><i class='bi bi-circle mid'></i></a>";
           }
           if ($priority == 'high'){
               return "<a href='change_note_status.php?id=$id'><i class='bi bi-circle high'></i></a>";
           }
       }

    }

    static function print_my_notes($list_id){
        // include 'includes/connect.php';
        $task_id = self::get_my_notes($list_id);
        foreach ($task_id as $id) {
            //$result = $connection->query("SELECT title FROM lists where id='$id'");
            //$row = mysqli_fetch_array($result);
            //echo "<a href='list.php?id=$id'>".$row['title']."</a><br>";
            echo "<div class='d-flex flex-column'>
                    <div class='d-flex flex-row'>
                        <div class='p-2'>".
                            self::button_status(self::get_task_status($id),self::get_task_priority($id),$id).
                        "</div>
                         <div class='p-2'>
                                <h5> <a class='btn btn-link' data-bs-toggle='collapse' href='#collapse".$id."' role='button' aria-expanded='false' aria-controls='collapse".$id."'>".self::get_task_title($id)."</a>
                                    <div class='collapse' id='collapse".$id."'>
                                        
                                         ".self::get_task_description($id)." <br><hr>
                                         <p> priority : ".self::get_task_priority($id)."| date : ". self::get_task_execution_date($id)."</p><hr><a href='edit_note.php?id=$id'><i class='bi bi-pencil-fill'></i></a><a href='delete_note.php?id=$id'><i class='bi bi-trash3-fill'></i></a>
                                    
                                </h5>
                         </div>
                   </div>";
            //echo "<a href='task.php?id=$id'>".self::get_task_title($id).",".self::get_task_execution_date($id)."</a><br>";
        }
    }

    static function print_my_notes_to_file($list_id){

        // include 'includes/connect.php';
        $task_id = self::get_my_notes($list_id);
        foreach ($task_id as $id) {
            $title = self::get_task_title($id);
            $description = self::get_task_description($id);
            $date = self::get_task_execution_date($id);
            $status = self::get_task_status($id);
            $priority = self::get_task_priority($id);
            if (isset($title)){
                file_put_contents('files/file.txt', $id.",".$title.",".$description.",".$priority.",".$date.",".$status."\n", FILE_APPEND | LOCK_EX);

            }

        }
    }

    static function change_note_status($note_id, $set_status){
        include 'includes/connect.php';
        if ($connection->query("UPDATE tasks SET status = '$set_status' WHERE id ='$note_id'; ")) {

        } else {
            throw new Exception($connection->error);
        }
    }

    static function delete_note($note_id){
        include 'includes/connect.php';

        if ($connection->query("DELETE FROM connections WHERE task_id ='$note_id'; ")) {
            if ($connection->query("DELETE FROM tasks WHERE id ='$note_id'; ")) {

            } else {
                throw new Exception($connection->error);
            }
        } else {
            throw new Exception($connection->error);
        }
    }

    static function delete_list($list_id){
        include 'includes/connect.php';
        $notes_array = self::get_my_notes($list_id);

        if ($connection->query("DELETE FROM connections WHERE list_id ='$list_id'; ")) {
            if ($connection->query("DELETE FROM lists WHERE id ='$list_id'; ")) {



                foreach ($notes_array as $note){
                    $connection->query("DELETE FROM notes WHERE id ='$note'; ");
                }

            } else {
                throw new Exception($connection->error);
            }
        } else {
            throw new Exception($connection->error);
        }
    }
}