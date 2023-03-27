<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpleChat</title>
    
    <style>
        .nav_bar{
            height: 4rem;
            background-color: rgb(31, 31, 153);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 20px;
            box-shadow: 10px 5px 5px grey;
        }   
        .nav_bar > h2{
            display: flex;
            align-items: center;
            height: 100%;
            color: whitesmoke;
        }
        .nav_bar > .navbuttons{
            display: flex;
            align-items: center;
            height: 100%;
            margin-right: 10px;
            
        }
        .left_container{
            float: left;
            background-color: silver;
            height: 40rem;
            width: 25%;
            box-shadow: 10px 5px 5px grey;
            margin-left: 25px;
            margin-top: 25px;
            border-radius: 25px;

        }
        .right_container{
            float: right;
            background-color: silver;
            height: 40rem;
            width: 60%;
            box-shadow: 10px 5px 5px grey;
            margin-left: 25px;
            margin-top: 25px;
            border-radius: 25px;
        }
        
        .welcome{
            font-size: 23px;
            padding-left: 20%;
        }
        .users_list{
            list-style: none;
            padding-left: 0;
        }
        .button5 {
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
    <div class="nav_bar">
        <h2>SimpleChat</h2>
        <div class="navbuttons">
            <form class = "navbuttons">
                <button class = "button5" style="margin-right: 10px;">My account</button>
            </form>
            <form action="logout.php" class = "navbuttons">
                <button type="submit" id="logoutButton" class="button5">Logout</button>
            </form>
        </div>
    </div>
    <div class = "left_container">
        <?php  
            include'is_user_logged.php';
            echo "<h2 class = 'welcome'>Witaj " . $_SESSION['username2'] . "</h2>";  
            
        ?>
        <p>Available users:</p><br>
        <!-- lista użytkowników -->
        <ul>
            <?php
                include'connect.php';
                $conn = @mysqli_connect($host, $db_user, $db_password, $db_name);
                $query = "SELECT * FROM users";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<li><a href='main.php?recipient_id=" . $row['id'] . "'>" . $row['username'] . "</a></li>";
                    }
                }
            ?>
        </ul>
    </div>
    <div class="right_container">
                    
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
                    echo "<h2>Rozmowa z " . $recipient['username'] . "</h2>";
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
                <form method="post" action="">
                    <input type="hidden" name="recipient_id" value="<?php echo $recipient_id; ?>">
                    <textarea name="message" required></textarea>
                    <button type="submit" name="send">Wyślij</button>
                </form>
            <?php } ?>
            <?php
            
            // Obsługa wysyłania wiadomości
            if (isset($_POST['send'])) {
                $sender_id = $_SESSION['id'];
                $query = "INSERT INTO conversations (user1_id, user2_id) VALUES ($sender_id, $recipient_id)";
                $result = mysqli_query($conn, $query);
                $conversation_id = mysqli_insert_id($conn);
                $recipient_id = mysqli_real_escape_string($conn, $_POST['recipient_id']);
                $message = mysqli_real_escape_string($conn, $_POST['message']);
                $sender_id = $_SESSION['id'];
                $query2 = "INSERT INTO messages (conversation_id, sender_id, receiver_id, message) 
                        VALUES ( $conversation_id, $sender_id, $recipient_id, '$message')";
                $result2 = mysqli_query($conn, $query2);
                if ($result2) {
                    header("Refresh:0");
                
                }
                else {
                    echo "<p>Błąd podczas wysyłania wiadomości.</p>";
                }
            }
        ?>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>

        </script>

</body>
</html>