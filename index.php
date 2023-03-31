<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpleChat</title>
    <link rel="stylesheet" href="index_styles.css">
    
    
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <div class="maind">
        <h1 class="appname">Simple Chat</h1>
        <h2 class="signregi">Sign in</h2>
        <div class="inputs">
            <div class="fields">
                <div class="fieldset">
                    <!--Login formula-->
                    <form id = "login_form" method="post" style="display: block;">
                            <label class="test">Login:<br>
                                <input type="text" placeholder="Login" name = "username"><br>
                            </label>
                            <label class="test">Password:<br>
                                <input type="password" placeholder="Password" name = "password"><br>
                            </label><br>
                            <button type="button" class="loginButton" onclick="submitLogin()">Submit</button><br><br>  
                            <input type="hidden" name="username2" value="">
                            <input type="hidden" name="id" value="">
                        </form>
                    <!--register formula-->
                    <form id = "register_form" method="post" style="display: none;">
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
                                <input type="email" placeholder="Email" name = "email" id="email"><br><br>
                            </label>

                            <button type="button" class="loginButton" onclick = "submitRegister()">Submit</button><br><br>
                    </form>
                </div>
            </div>
        </div>
        <button id="loginButton" class="loginButton" style="display: none;">Sign In</button>
        <button id="registerButton" class="loginButton">Register</button>
        <div id="login_error"></div>
        <div id="register_error"></div>
    </div>
    <?php
        session_start();
        if(isset($_SESSION['username']) && isset($_SESSION['password'])){
            header('Location: main.php');
            exit();
        }
    ?>

    <script>
        document.getElementById("loginButton").addEventListener("click", function() {
            document.getElementById("login_form").style.display = "block";
            document.getElementById("register_form").style.display = "none";
            document.querySelector(".signregi").innerHTML = "Sign in";
            document.getElementById("loginButton").style.display = "none";
            document.getElementById("registerButton").style.display = "inline";
            document.getElementById("register_error").innerHTML = "";
        }); 
        document.getElementById("registerButton").addEventListener("click", function() {
            document.getElementById("register_form").style.display = "block";
            document.getElementById("login_form").style.display = "none";
            document.querySelector(".signregi").innerHTML = "Registration";
            document.getElementById("loginButton").style.display = "inline";
            document.getElementById("registerButton").style.display = "none";
            document.getElementById("login_error").innerHTML = "";
          });
          //ajax dla login form
          function submitLogin(){
            // dzieki temu pobiore dane z formularza
            var form_data = new FormData(document.getElementById("login_form"));
            
            //wysylanie danych do pliku login.php
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "login.php", true);
            xhr.onload = function(){
                if(xhr.status == 200){//jesli zadanie zostalo zrealizowane pomyslnie
                    var response = xhr.responseText;
                    if(response == "success"){
                        
                        window.location.href = "main.php";
                    }else{
                        
                        document.getElementById("login_error").innerHTML = "<p>Podałeś zły login bądź hasło!</p>"
                    }
                    console.log(response);
                }
            };
            xhr.send(form_data);
            
          }
          function submitRegister(){
            var email_input = document.getElementById("email");
            var email_value = email_input.value;
            if(email_value.indexOf("@") == -1){
                document.getElementById("register_error").innerHTML = "<p>Adres email musi zawierać znak @!</p>";
                return;
            }
            var form_data = new FormData(document.getElementById("register_form"));
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "register.php", true);
            xhr.onload = function(){
                if(xhr.status == 200){
                    var response = xhr.responseText;
                    if(response == "success"){
                        alert("Rejestracja przebiegła pomyślnie! Teraz możesz się zalogować");
                        window.location.reload();
                    }else if(response == "same_login"){
                        document.getElementById("register_error").innerHTML = "<p>Proszę wprowadzić inny login!</p>";
                    }else if(response == "same_email"){
                        document.getElementById("register_error").innerHTML = "<p>Użytkownik z podanym emailem już istnieje!</p>";
                    }else if(response == "blank_login"){
                        document.getElementById("register_error").innerHTML = "<p>Nie wprowadziłeś loginu!</p>";
                    }else if(response == "blank_username"){
                        document.getElementById("register_error").innerHTML = "<p>Nie wprowadziłeś nazwy użytkownika!</p>"
                    }else if(response == "blank_password"){
                        document.getElementById("register_error").innerHTML = "<p>Nie wprowadziles hasła!</p>"
                    }else if(response == "passwords_not_same"){
                        document.getElementById("register_error").innerHTML = "<p>Podane hasła nie zgadzają się!</p>"
                    }else if(response == "blank_email"){
                        document.getElementById("register_error").innerHTML = "<p>Nie wprowadziłeś e-mailu!</p>"
                    }else if(response == "error"){
                        document.getElementById("register_error").innerHTML = "<p>Coś poszło nie tak!</p>"
                    }
                }
            }
            xhr.send(form_data);
          }
    </script>

</body>
</html>