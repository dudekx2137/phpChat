<?php
   
    require_once'connect.php';
    
    error_reporting(~E_WARNING); 
    $conn = @mysqli_connect($host, $db_user, $db_password, $db_name);
    if (mysqli_connect_errno()!=0)
    {
        echo "Debug: ".mysqli_connect_errno();
    }else{
        $login = $_POST['username'];
        $username = $_POST['username2'];
        $password = $_POST['password'];
        $id = $_POST['id'];
        $queryCheck = "SELECT * FROM users WHERE login = '$login' and password = '$password'";
        $result = mysqli_query($conn, $queryCheck);
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            session_start(); 
            echo 'success';
            $_SESSION['username'] = $row['login'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['username2'] = $row['username'];
            $_SESSION['id'] = $row['id'];
        } else {
            echo 'error';
        }
    }

   
?>