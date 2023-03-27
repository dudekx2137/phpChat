<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpleChat</title>
    <style>
        body{
           background-color: #D0D1D2;
            font-family: 'Google Sans','Noto Sans Myanmar UI',arial,sans-serif;
        }
        .maind{
            background-color: white;
            position: absolute;
            top: 50%;
            left:50%;
            transform: translate(-50%,-50%);
            width: 350px;
            min-height: 400px;
            padding:3rem;
            border-radius:0.5rem;
            border:1px solid wheat;
            text-align: center;
        }
        .maind .fields{
            
            display: inline-block;
            height: 90px;
            position: relative;
        }
        h1{
           
            padding-top: 70px;
            font-size: 24px;
            font-weight: 400px;
        }
        input{
            outline: none;
        }
        .loginButton{
            cursor: pointer;
            border:1px solid transparent;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42;
            color:white;
            border-radius: 4px;
            background-color: #1a73e8;
            outline: none;
            min-width: 88px;

}
    </style>
</head>
<body>
    <div class="maind">
        <h1 class="signregi">Sign in</h1>
        <div class="inputs">
            <div class="fields">
                <div class="fieldset">
                    <!--Login formula-->
                    <form id = "login_form" action="login.php" method="post" style="display: block;">
                            <label class="test">Login:<br>
                                <input type="text" placeholder="Login" name = "username"><br>
                            </label>
                            <label class="test">Password:<br>
                                <input type="password" placeholder="Password" name = "password"><br>
                            </label><br>
                            <button type="submit" class="loginButton">Submit</button><br><br>  
                    </form>
                    <!--register formula-->
                    <form id = "register_form" action="register.php" method="post" style="display: none;">
                            <label class="test">Login:<br>
                                <input type="text" placeholder="Login" name = "username"><br>
                            </label>
                            <label class="test">Username:<br>
                                <input type="text" placeholder="Username" name = "username2"><br>
                            </label>
                            <label class="test">Password:<br>
                                <input type="password" placeholder="Password" name = "password"><br>
                            </label>
                            <label class="test">Repeat password:<br>
                                <input type="password" placeholder="Repeat Password" name = "password2"><br>
                            </label>
                            <label class="test">Email:<br>
                                <input type="email" placeholder="Email" name = "email"><br><br>
                            </label>
                            <button type="submit" class="loginButton">Submit</button><br><br>
                    </form>
                </div>
            </div>
        </div>
        <button id="loginButton" class="loginButton" style="display: none;">Sign In</button>
        <button id="registerButton" class="loginButton">Register</button>
        
    </div>


    <script>
        document.getElementById("loginButton").addEventListener("click", function() {
            document.getElementById("login_form").style.display = "block";
            document.getElementById("register_form").style.display = "none";
            document.querySelector(".signregi").innerHTML = "Sign in";
            document.getElementById("loginButton").style.display = "none";
            document.getElementById("registerButton").style.display = "inline";
          });
          
        document.getElementById("registerButton").addEventListener("click", function() {
            document.getElementById("register_form").style.display = "block";
            document.getElementById("login_form").style.display = "none";
            document.querySelector(".signregi").innerHTML = "Registration";
            document.getElementById("loginButton").style.display = "inline";
            document.getElementById("registerButton").style.display = "none";
          });
    </script>
    <?php
         error_reporting(E_ERROR | E_PARSE);
        session_start();
        if(isset($_SESSION['username']) && isset($_SESSION['password'])){
            header('Location: main.php');   
        }
    ?>
</body>
</html>