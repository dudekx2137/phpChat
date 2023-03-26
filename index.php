<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hehe czat-logowanie</title>
    <style>

        .test{
            color: blue;
        }
        fieldset{
            width: 10%;
        }
    </style>
</head>
<body>
    <button id="loginButton">Logowanie</button>
    <button id="registerButton">Rejestracja</button>
    <form id = "login_form" action="login.php" method="post" style="display: block;">
        <fieldset>
            <legend>Sign In</legend>
            <label class="test">Login:
                <input type="text" placeholder="Login" name = "username">
            </label>
            <label class="test">Password:
                <input type="password" placeholder="Password" name = "password">
            </label>
            <button type="submit">Zaloguj</button>
            
        </fieldset>
    </form>
    <form id = "register_form" action="register.php" method="post" style="display: none;">
        <fieldset>
            <legend>Register</legend>
            <label class="test">Login:
                <input type="text" placeholder="Login" name = "username">
            </label>
            <label class="test">Username:
                <input type="text" placeholder="Username" name = "username2">
            </label>
            <label class="test">Password:
                <input type="password" placeholder="Password" name = "password">
            </label>
            <label class="test">Password:
                <input type="password" placeholder="Repeat Password" name = "password2">
            </label>
            <label class="test">Email:
                <input type="email" placeholder="Email" name = "email">
            </label>
            <button type="submit">Zarejestruj</button>
        </fieldset>
    </form>
    <script>
        document.getElementById("loginButton").addEventListener("click", function() {
            document.getElementById("login_form").style.display = "block";
            document.getElementById("register_form").style.display = "none";
          });
          
          document.getElementById("registerButton").addEventListener("click", function() {
            document.getElementById("register_form").style.display = "block";
            document.getElementById("login_form").style.display = "none";
          });
    </script>
    <?php
        session_start();
        if(isset($_SESSION['username']) && isset($_SESSION['password'])){
            header('Location: main.php');   
        }
    ?>
</body>
</html>