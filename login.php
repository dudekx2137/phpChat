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
        $row = mysqli_fetch_assoc($result);
        if($row['login'] == $login && $row['password'] == $password){
            session_start(); 
            $_SESSION['username'] = $row['login'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['username2'] = $row['username'];
            $_SESSION['id'] = $row['id'];
            echo 'success';
            

        }else{
            echo 'error';
        }
    }

   
?>