<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) 
    {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

    if (!isset($_GET['user_id'])) 
    {
        echo "<script>alert('No user selected.'); window.location='userMessageInbox.php';</script>";
        exit;
    }

    $chatWithID = $_GET['user_id'];

    // Fetch chat partner details
    $chatPartnerQuery = "SELECT * FROM users WHERE UserID = '$chatWithID'";
    $chatPartnerResult = mysqli_query($dbConnect, $chatPartnerQuery);

    if (mysqli_num_rows($chatPartnerResult) > 0) 
    {
        $chatPartnerRow = mysqli_fetch_assoc($chatPartnerResult);
    } 
    else 
    {
        echo "<script>alert('User not found.'); window.location='userMessageInbox.php';</script>";
        exit;
    }

    // Fetch messages
    $messagesQuery = "SELECT * FROM messages 
                    WHERE (MessageSenderID = '$uID' AND MessageReceiverID = '$chatWithID')
                    OR (MessageSenderID = '$chatWithID' AND MessageReceiverID = '$uID')
                    ORDER BY MessageTimeSent ASC";
    $messagesResult = mysqli_query($dbConnect, $messagesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooma - Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="roomaStyle.css">
</head>
<body>
    <div class="messageWrapper">
        <section class="chatArea">
            <header>
                <img src="<?php echo $chatPartnerRow['UserPp']; ?>" alt="">
                
                <div class="details">
                    <span><?php echo $chatPartnerRow['UserName']; ?></span>
                </div>
            </header>

            <div class="chatBox">
                <?php
                while($array = mysqli_fetch_assoc($messagesResult))
                {
                    if($array['MessageSenderID'] == $uID)
                    {
                        echo '<div class="chat outgoing">
                                <div class="details">
                                    <p>'.$array['MessageText'].'</p>
                                </div>
                              </div>';
                    } 
                    
                    else 
                    {
                        echo '<div class="chat incoming">
                                <img src="'.$chatPartnerRow['UserPp'].'" alt="">
                                <div class="details">
                                    <p>'.$array['MessageText'].'</p>
                                </div>
                              </div>';
                    }
                }
                ?>
            </div>

            <form action="#" class="typeArea" id="messageForm">
                <input type="text" class="incomingID" name="incomingID" value="<?php echo $chatWithID; ?>" hidden>
                <input type="text" name="message" class="inputField" placeholder="Type a message here..." autocomplete="off">
                <button type="button" id="sendBtn"><i class="fab fa-telegram-plane"></i></button>
            </form>
        </section>
    </div>

    <script>
        const form = document.querySelector("#messageForm"),
        incomingID = form.querySelector(".incomingID").value,
        inputField = form.querySelector(".inputField"),
        sendBtn = document.querySelector("#sendBtn"),
        chatBox = document.querySelector(".chatBox");

        inputField.focus();
        inputField.onkeyup = ()=>{
          if(inputField.value != ""){
            sendBtn.classList.add("active");
          }else{
            sendBtn.classList.remove("active");
          }
        }

        sendBtn.onclick = ()=>{
          let xhr = new XMLHttpRequest();
          xhr.open("POST", "userMessageAction.php", true);
          xhr.onload = ()=>{
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    inputField.value = "";
                    scrollToBottom();
                    loadMessages();
                }
            }
          }
          let formData = new FormData(form);
          xhr.send(formData);
        }

        chatBox.onmouseenter = ()=>{
          chatBox.classList.add("active");
        }

        chatBox.onmouseleave = ()=>{
          chatBox.classList.remove("active");
        }

        function loadMessages(){
          let xhr = new XMLHttpRequest();
          xhr.open("POST", "userMessageAction.php", true);
          xhr.onload = ()=>{
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                  let data = xhr.response;
                  chatBox.innerHTML = data;
                  if(!chatBox.classList.contains("active")){
                      scrollToBottom();
                  }
                }
            }
          }
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhr.send("incomingID="+incomingID);
        }

        loadMessages();

        setInterval(loadMessages, 500);

        function scrollToBottom(){
          chatBox.scrollTop = chatBox.scrollHeight;
        }
        </script>
</body>
</html>

