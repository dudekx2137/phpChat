<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpleChat</title>
    <link rel="stylesheet" href="main_styles.css">
</head>
<body>
    <div class="main_container">
        <div class = "left_container">
            <?php  
                include'is_user_logged.php';
            ?>
    <div class="search-container">
        <input class="search-input" type="text" placeholder="Wyszukaj użytkownika...">
    </div>
            <br>
            <!-- lista użytkowników -->
                <div class="user_list_mother">
                    <?php
                        
                        include'connect.php';
                        $conn = @mysqli_connect($host, $db_user, $db_password, $db_name);
                        $query = "SELECT * FROM users";
                        $result = mysqli_query($conn, $query);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {

                                echo "<a style='text-decoration: none; color: black' href='main.php?recipient_id=" . $row['id'] . "'>";
                                    echo '<div class = "user_list" data-user-id="' . $row['id'] . '">';
                                        echo '<img src="' . $row['avatar_path'] . '" class = "mini_avatar"/>';
                                        echo '<p>' . $row['username'] . '</p>';
                                    echo '</div>';
                                echo "</a>";


                            }
                        }
                    ?>
                </div>

            
        </div>
        <div class="middle_container">             
            <?php
            // info o rozmowcy
            if (isset($_GET['recipient_id'])) {
                $recipient_id = mysqli_real_escape_string($conn, $_GET['recipient_id']);
                $query = "SELECT * FROM users WHERE id = $recipient_id";
                $result = mysqli_query($conn, $query);
                $recipient = mysqli_fetch_assoc($result);
            }
            // naglowek z info o rozmowcy
            if (isset($recipient)) {
                echo "<h2 class = 'nameOfActualConversation'>Rozmowa z " . $recipient['username'] . "</h2>";
            } else {
                echo "<h2>Wybierz uzytkownika, z ktorym chcesz rozpoczac konwersacje</h2>";
            }
            ?>
            <!-- Wyświetlanie wiadomości -->
            <div id="messages-container">
                <?php
                    if (isset($_GET['recipient_id'])) {
                        $recipient_id = mysqli_real_escape_string($conn, $_GET['recipient_id']);
                        $query = "SELECT messages.*, users.username AS sender_name, DATE_FORMAT(messages.created_at, '%H:%i') AS sent_time
                        FROM messages
                        JOIN users ON messages.sender_id = users.id
                        WHERE (messages.sender_id = ".$_SESSION['id']." AND messages.receiver_id = $recipient_id) 
                        OR (messages.sender_id = $recipient_id AND messages.receiver_id = ".$_SESSION['id'].") 
                        ORDER BY messages.created_at ASC;";
                        $result = mysqli_query($conn, $query);
                        if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<p><strong>" .$row["sent_time"] . " " . $row["sender_name"] . ":</strong> " . $row["message"] . "</p>";
                        } 
                        } else {
                            echo "<p>Rozpocznij nową konwersację.</p>";
                        }
                    }
                ?>
            </div>
            <!-- Formularz do wysyłania wiadomości -->
            <?php if (isset($_GET['recipient_id'])) { ?>
                <div class="tareadiv">
                    <form method="post" action="">
                        <input type="hidden" name="recipient_id" value="<?php echo $recipient_id; ?>">
                        <textarea name="message" required></textarea>
                        <button type="submit" name="send" class="send_button">Wyślij</button>
                    </form>
                </div>
            <?php } ?>

            <?php
// Obsługa wysyłania wiadomości
                    if (isset($_POST['send'])) {
                        $recipient_id = mysqli_real_escape_string($conn, $_POST['recipient_id']);
                        $message = mysqli_real_escape_string($conn, $_POST['message']);
                        $sender_id = $_SESSION['id'];
                        $query = "SELECT id FROM conversations WHERE (user1_id=$sender_id AND user2_id=$recipient_id) OR (user1_id=$recipient_id AND user2_id=$sender_id)";
                        $result = mysqli_query($conn, $query);
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $conversation_id = $row['id'];
                        } else {
                            $query = "INSERT INTO conversations (user1_id, user2_id) VALUES ($sender_id, $recipient_id)";
                            $result = mysqli_query($conn, $query);
                            $conversation_id = mysqli_insert_id($conn);
                        }
                        $query2 = "SELECT id FROM messages WHERE conversation_id=$conversation_id AND sender_id=$sender_id AND receiver_id=$recipient_id AND message='$message' AND created_at=NOW()";
                        $result2 = mysqli_query($conn, $query2);
                        if (mysqli_num_rows($result2) == 0) {
                            $query3 = "INSERT INTO messages (conversation_id, sender_id, receiver_id, message, created_at) 
                                    VALUES ( $conversation_id, $sender_id, $recipient_id, '$message', NOW())";
                            $result3 = mysqli_query($conn, $query3);
                            if ($result3) {
                                header("Refresh:0");
                            } else {
                                echo "<p>Błąd podczas wysyłania wiadomości.</p>";
                            }
                        }
                    }
                
            ?>
        </div>
        <div class="right_container">
            <?php
                
                 //Dodawanie/wyswietlanie avataru uzytkownika
                 //id zalogowanego uzytkownika
                $user_id = $_SESSION['id'];
                //sprawdza czy przeslano plik z awatarem
                if(isset($_FILES['avatar'])){
                    //informacje o przeslanym pliku
                    $file_name = $_FILES['avatar']['name'];
                    $file_tmp_name = $_FILES['avatar']['tmp_name'];
                    $file_size = $_FILES['avatar']['size'];
                    $file_type = $_FILES['avatar']['type'];
                    //sprawdza czy plik jest obrazkiem
                    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
                    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    if(!in_array($file_extension, $allowed_types)){
                        echo "Nieprawidlowy format pliku. Dozwolone formaty to: jpg, jpeg, png, gif.";
                    }else{
                        //zapis pliku na serwerze
                        $avatar_path = "avatars/" . $user_id . "." . $file_extension;
                        move_uploaded_file($file_tmp_name, $avatar_path);
                        //zapis sciezki pliku w bazie
                        //$db = new mysqli('localhost', 'username', 'password', 'test');
                        $stmt = $conn->prepare("UPDATE users SET avatar_path = ? where id = ?");
                        $stmt->bind_param('si', $avatar_path, $user_id);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
                //pobranie sciezki do pliku z bazy
                echo "<h2 class = 'welcome'>Witaj " . $_SESSION['username2'] . "</h2>"; 
                $stmt = $conn->prepare("SELECT avatar_path FROM users WHERE id = ?");
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $stmt->bind_result($avatar_path);
                $stmt->fetch();
                $stmt->close();
                //wyswietlanie awatara
                if($avatar_path){
                    echo '<img src="' . $avatar_path . '" alt = "Avatar" class = "avatar"/>';
                }else{
                    echo "Możesz dodać swój awatar!";  
                    
            
    
                }
                        echo '<form action="" method="post" enctype="multipart/form-data">';
                            echo '<input type="file" name="avatar" class = "avatarbutton" />';
                            echo '<input type="submit" value="Zapisz" class="avatarbutton" id="submit-button" disabled onclick = "hidebutt()"/>';
                        echo '</form>';
                    
            ?>
            <button class = "show_avatar_buttons" onclick = "showbutt()">Zmien awatar</button>
            <?php

            
            ?>
            <div class="navbuttons">
                <form class = "navbuttons">
                    </form>
                    <form action="logout.php" class = "navbuttons">
                    <button type="submit" id="logoutButton" class="button5">Logout</button>
                </form>
            </div>
            <h2>tu dac cos o aktualnie zalogowanym uzytkowniku</h2>
        </div>
    </div>
    <div class="footer">
        <p class="footertext">Site made by dudek</p
    </div>
    <script>
            //zablokowanie przysicku submit, do momentu wprowadzenia pliku przez uzytkownika
            const fileInput = document.querySelector('input[type="file"]');
            const submitButton = document.getElementById('submit-button');
            fileInput.addEventListener('change', () => {
            submitButton.removeAttribute('disabled');
            });
            //pokazywanie/ukrywanie przyciskow do zmieniania badz dodawania avatara
            function showbutt() {
                var buttons = document.getElementsByClassName("avatarbutton");
                for (var i = 0; i < buttons.length; i++) {
                    buttons[i].style.display = "block";
                }
            }
            function hidebutt(){
                var buttons = document.getElementsByclassName("avatarbutton");
                for(var i = 0; i < buttons.length; i++){
                    buttons[i].style.display = "none";
                }
            }
            //zmiana koloru aktualnie wybranego usera
            const urlParam = new URLSearchParams(window.location.search);
            const recipientId = urlParam.get('recipient_id');

            const selectedUsers = document.querySelectorAll('.user_list');

            for(let i = 0; i < selectedUsers.length; i++){
                const selectedUser = selectedUsers[i];
                const userId = selectedUser.dataset.userId;

                if(recipientId && userId == recipientId){
                    selectedUser.style.backgroundColor = "#151922";
                    selectedUser.style.color = "white";
                } else if(selectedUser.classList.contains('hovered')){
                    selectedUser.style.backgroundColor = "#151922";
                    selectedUser.style.color = "white";
                } else {
                    selectedUser.style.backgroundColor = "#ffd966";
                    selectedUser.style.color = "#151922";
                }

                selectedUser.addEventListener('click', () => {
                    window.location.href = `main.php?recipient_id=${userId}`;
                });

                selectedUser.addEventListener('mouseover', () => {
                    selectedUser.classList.add('hovered');
                    if(!recipientId || userId != recipientId){
                        selectedUser.style.backgroundColor = "#151922";
                        selectedUser.style.color = "white";
                    }
                });

                selectedUser.addEventListener('mouseout', () => {
                    selectedUser.classList.remove('hovered');
                    if(!recipientId || userId != recipientId){
                        selectedUser.style.backgroundColor = "#ffd966";
                        selectedUser.style.color = "#151922";
                    }
                });
            }

            //sortowanie uzytkownikow w left cointainerze
            const searchInput = document.querySelector('.search-container input');
            const userList = document.querySelector('.user_list_mother');
            searchInput.addEventListener('input', (e) => {
                const searchString = e.target.value.toLowerCase();

                const filteredUsers = Array.from(userList.children).filter((user) =>
                    user.querySelector('p').textContent.toLowerCase().includes(searchString)
                );

                Array.from(userList.children).forEach((user) => {
                    if (!filteredUsers.includes(user)) {
                        user.style.display = 'none';
                    } else {
                        user.style.display = 'block';
                    }
                });
            });
    </script>

</body>
</html>