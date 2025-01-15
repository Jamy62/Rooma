<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

    $userQuery = "SELECT * FROM users WHERE UserID = '$uID'";
    $userResult = mysqli_query($dbConnect, $userQuery);
    $userRow = mysqli_fetch_assoc($userResult);

    /* Select users who has chat history with current user */
    $chatListQuery = "SELECT u.UserID, u.UserName, u.UserPp,
                      (SELECT MAX(MessageTimeSent) 
                      FROM messages 
                      WHERE (MessageSenderID = u.UserID AND MessageReceiverID = '$uID') 
                      OR (MessageSenderID = '$uID' AND MessageReceiverID = u.UserID)) AS LastMessageTime
                      FROM users u
                      WHERE u.UserID IN (
                      SELECT MessageSenderID FROM messages WHERE MessageReceiverID = '$uID'
                      UNION
                      SELECT MessageReceiverID FROM messages WHERE MessageSenderID = '$uID'
                      )
                      AND u.UserID != '$uID'
                      ORDER BY LastMessageTime DESC";
    $chatListResult = mysqli_query($dbConnect, $chatListQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="roomaStyle.css">
</head>
<body>
    <div class="messageWrapper">
        <section class="users">
            <header>
                <div class="content">
                    <img src="<?php echo $userRow['UserPp']; ?>" alt="">
                    <div class="details">
                        <span><?php echo $userRow['UserName']; ?></span>
                    </div>
                </div>
            </header>

            <div class="select">
                <span class="text">Select a user to start chat</span>
            </div>
            
            <div class="userList">
                <?php
                    while($row = mysqli_fetch_assoc($chatListResult))
                    {
                        echo '<a href="userMessage.php?user_id='.$row['UserID'].'">
                                <div class="content">
                                    <img src="'.$row['UserPp'].'" alt="">
                                    <div class="details">
                                        <span>'.$row['UserName'].'</span>
                                    </div>
                                </div>
                            </a>';
                    }
                ?>
            </div>
        </section>
    </div>

    <script>
        usersList = document.querySelector(".userList");

        setInterval(() => {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "userMessageAction.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
            }
            }
        }
        xhr.send();
        }, 2000);
    </script>
</body>
</html>
