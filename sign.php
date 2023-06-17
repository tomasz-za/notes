<?php
/**
class sign {



}
**/

session_start();
class sign_in {



    private $username;
    private $password;

    function __construct($username, $password){
        $this->username = htmlentities($username, ENT_QUOTES, "UTF-8");
        $this->password = $password;
    }

    function sign_in_(){
        include 'includes/connect.php'; // require is safe

        //$connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno){
            //echo "Error :".$connection->connect_errno;
            throw new Exception($connection->error);
        } else {
            //echo "połączono";
            //$username = $_POST['username'];
            //$password = $_POST['password'];

            //$username = htmlentities($username, ENT_QUOTES, "UTF-8");

            if ($result = @$connection->query(sprintf("SELECT * FROM users WHERE username='%s';",
                mysqli_real_escape_string($connection, $this->username),
                mysqli_real_escape_string($connection, $this->password)))){
                $num_users = $result->num_rows;

                if ($num_users>0){
                    echo "znaleziono urzytkownika";
                    $row = $result->fetch_assoc();
                    if (password_verify($this->password, $row['password'])){
                                                             //echo "hasła się zgadzają";
                        $_SESSION['signed_in'] = true;

                        $_SESSION['id'] = $row['username'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['email'] = $row['email'];

                        unset($_SESSION['error']);
                        //echo $username;

                        //$result->free();
                        header('Location:index.php');
                    } else { // INCORRECT PASSWORD
                        $_SESSION['error'] = '<span style="color:red">invalid password</span>';
                        header('Location:sign_in.php');
                        //echo "złe hasło";
                    }
                } else { // INCORRECT PASSWORD AND LOGIN
                    $_SESSION['error'] = '<span style="color:red">invalid username</span>';
                    header('Location:sign_in.php');
                    //echo "brak urzytkownika";
                }
            }
            //$connection->free();
        }
    }

    static function sign_out (){
        session_unset();
        header('Location: index.php');
    }

}
class sign_up {
    private $username;
    private $password;
    private $email;



    function __construct($username, $password, $email){
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    function get_username(){
        return $this->username;
    }

    function get_password_hash(){
        return password_hash($this->password, PASSWORD_DEFAULT);
    }

    function get_email(){
        return $this->email;
    }

    // OUTPUT BOOL
    function username_exists(){

        include 'includes/connect.php';

        mysqli_report(MYSQLI_REPORT_STRICT);
        try {
            if ($connection->connect_errno) {
                throw Exception(mysqli_connect_errno());
            } else {
                $result = $connection->query("SELECT username FROM users WHERE username='$this->username'");
                if (!$result) throw new Exception($connection->error);
                $username_num = $result->num_rows;
                if ($username_num > 0){
                    return true;
                } else {
                    return false;
                }
            }
        } catch (Exception $e){
            echo 'Cannot connect to the server';
        }
    }


    // RETURN HASH
    function password_validation(){
        $password_re = '/^.*(?=.{8,30})(?=.*[a-zA-Z]+$)(?=.*\d)(?=.*[!*#$%&? "]).*$/';
        if (!preg_match($password_re, $this->password, $matches, PREG_OFFSET_CAPTURE, 0)) {
            //return false;
            return false;
            //$_SESSION['e_password'] = "Password have to contain small and strong letters, .....";
        } else {
            return true;
        }
    }



    //RETURN BOOL
    function email_validation(){
        $email_filtered = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        if ((filter_var($email_filtered, FILTER_VALIDATE_EMAIL) == false) || ($email_filtered != $this->email)) {
            $_SESSION['e_email'] = "Please enter correct email";
            return false;
        } else {
            return true;
        }
    }
}

//_______________________________________________________________________________________________

//session_start();
//require_once "includes/connect.php";
include 'includes/connect.php';


if (isset($_GET['sign_out'])){
    sign_in::sign_out();
}
if (isset($_POST['submit'])){
    if ($_POST['submit'] == 'sign_in'){

        if (!isset($_POST['username']) || (!isset($_POST['password']))){
            header('Location: index.php');
            exit();
        }

        $user = New sign_in($_POST['username'], $_POST['password']);
        try {
            $user->sign_in_();
        } catch (Exception $e) {
        }
    }

    if ($_POST['submit'] == 'sign_up'){

        $user = New sign_up($_POST['username'],$_POST['password'],$_POST['email']);

        if (!$user->username_exists() && $user->password_validation() && $user->email_validation()){
            echo "all pass";
            $username = $user->get_username();
            $password_hash = $user->get_password_hash();
            $email= $user->get_email();

            if ($connection->query("INSERT INTO users VALUES ('$username','$email','$password_hash','user','')")){
                $_SESSION['singed_up']=true;

                header('Location: sign_in.php');
            } else {
                throw new Exception($connection->error);
            }
        } else {
            echo "not";
        }
}

}

