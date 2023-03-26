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
            ?>
            <script>
                setTimeout(function(){
                alert("Istnieje użytkownik z takim samym loginem!");
                window.location.href = "index.php";
                },500);
            </script>
            <?php
                
            }elseif(mysqli_num_rows($result2) > 0 ){
                ?>
                <script>
                    setTimeout(function(){
                    alert("Istnieje użytkownik z takim samym emailem!");
                    window.location.href = "index.php";
                    },1000);    
                </script>
                <?php
            }elseif($login == ""){
                ?>
                <script>
                    setTimeout(function(){
                    alert("Prosze wprowadzić login!");
                    window.location.href = "index.php";
                    },1000);  
                </script>
                <?php
            }elseif($username == ""){
                ?>
                <script>
                    setTimeout(function(){
                    alert("Prosze wprowadzić nazwe uzytkownika!");
                    window.location.href = "index.php";
                    },1000);  
                </script>
                <?php
            }elseif($password == ""){
                ?>
                <script>
                    setTimeout(function(){
                    alert("Prosze wprowadzić haslo");
                    window.location.href = "index.php";
                    },1000);    
                </script>
                <?php
                    
            }elseif($email == ""){
                ?>
                <script>
                        setTimeout(function(){
                        alert("Prosze wprowadzić email");
                        window.location.href = "index.php";
                        },1000);  

                </script>
                <?php
                    
            }
            else{
                if($password == $password2){
                    $userInsertQuery = "INSERT INTO users (login, password, email, username) Values('$login','$password','$email', '$username')";
                    $result = mysqli_query($conn, $userInsertQuery);
                    ?>
                    <script>
                        setTimeout(function(){
                        alert("Sukces! Teraz możesz się zalogować!");
                        window.location.href = "index.php";
                        },1000);
                    </script>
                    <?php
                }else{
                    ?>
                    <script>
                        setTimeout(function(){
                        alert("Podane hasła nie są takie same!");
                        window.location.href = "index.php";
                        },1000);
                    </script>
                    <?php
                        
                }
            }
            mysqli_close($conn);
        }
    ?>