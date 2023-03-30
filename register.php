<?php
        session_start();    
        require_once"connect.php";
        error_reporting(E_ALL ^ E_NOTICE);  
        $conn = @mysqli_connect($host, $db_user, $db_password, $db_name);

        if (mysqli_connect_errno()!=0)
        {
            echo "Debug: ".mysqli_connect_errno();
        }else{
            $login = $_POST['username'];
            $username = $_POST['username2'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $email = $_POST['email'];
            $result = mysqli_query($conn, "SELECT login from users where login = '$login'");
            $result2 = mysqli_query($conn, "SELECT email from users where email = '$email'");
            if(mysqli_num_rows($result) > 0){
                echo 'same_login';         
            }elseif(mysqli_num_rows($result2) > 0 ){
                echo 'same_email';
            }elseif($login == ""){
                echo 'blank_login';
            }elseif($username == ""){
                echo 'blank_username';
            }elseif($password == ""){
                echo 'blank_password';
            }elseif($password2 == ""){
                echo'blank_password';
            }
            elseif($email == ""){
                echo 'blank_email';
            }
            else{
                if($password == $password2){
                    $userInsertQuery = "INSERT INTO users (login, password, email, username) Values('$login','$password','$email', '$username')";
                    $result = mysqli_query($conn, $userInsertQuery);
                    echo 'success';
                }elseif($password != $password2){
                    echo 'passwords_not_same';
                }else{
                    echo 'error';
                }
            }
            mysqli_close($conn);
        }
    ?>