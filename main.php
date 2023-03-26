<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hehe czat</title>
    <style>
        .nav_bar{
            height: 4rem;
            background-color: rgb(31, 31, 153);
            display: flex;
            justify-content: center;
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
    </style>
</head>
<body>
    <div class="nav_bar">
        <form action="logout.php" class="logoutButton">
            <button type="submit" id="logoutButton">Logout</button>
        </form>
        <form>
            <button>My account</button>
            <button>Cost tam</button>
            <button>Cos tam2</button>
        </form>
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
                // pobierz informacje o rozmówcy
                $recipient_id = mysqli_real_escape_string($conn, $_GET['recipient_id']);
                $query = "SELECT * FROM users WHERE id = $recipient_id";
                $result = mysqli_query($conn, $query);
                $recipient = mysqli_fetch_assoc($result);
                
                // wyświetl nagłówek z informacją o rozmówcy
                echo "<h2>Rozmowa z " . $recipient['username'] . "</h2>";
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
                    } else {
                        echo "<p>Wybierz użytkownika, aby rozpocząć konwersację.</p>";
                    }
                ?>
            </div>
            <!-- Formularz do wysyłania wiadomości -->
            <?php if (isset($_GET['recipient_id'])) { ?>
                <form method="post" action="">
                    <input type="hidden" name="recipient_id" value="<?php echo $recipient_id; ?>">
                    <textarea name="message"></textarea>
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