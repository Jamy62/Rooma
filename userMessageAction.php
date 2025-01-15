<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['uID'])) {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='userLogin.php'</script>";
    }

    $uID = $_SESSION['uID'];
    $uPp = $_SESSION['uPp'];

    /* Handle sending messages */
    if(isset($_POST['incomingID']) && isset($_POST['message']))
    {
        $incomingID = mysqli_real_escape_string($dbConnect, $_POST['incomingID']);
        $message = mysqli_real_escape_string($dbConnect, $_POST['message']);
        
        if(!empty($message))
        {
            $send = "INSERT INTO messages (MessageSenderID, MessageReceiverID, MessageText)
                    VALUES ({$uID}, {$incomingID}, '{$message}')";
            mysqli_query($dbConnect, $send);
        }
    }

    /* Handle fetching messages */
    if(isset($_POST['incomingID']) && !isset($_POST['message']))
    {
        $incomingID = mysqli_real_escape_string($dbConnect, $_POST['incomingID']);
        $output = "";
        $get = "SELECT * FROM messages 
                WHERE (MessageSenderID = {$uID} AND MessageReceiverID = {$incomingID})
                OR (MessageSenderID = {$incomingID} AND MessageReceiverID = {$uID}) 
                ORDER BY MessageID";
        $query = mysqli_query($dbConnect, $get);

        if(mysqli_num_rows($query) > 0)
        {
            while($array = mysqli_fetch_array($query))
            {
                if($array['MessageSenderID'] === $uID)
                {
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. $array['MessageText'] .'</p>
                                </div>
                                </div>';
                }
                
                else
                {
                    $output .= '<div class="chat incoming">
                                <img src="'. $uPp .'" alt="">
                                <div class="details">
                                    <p>'. $array['MessageText'] .'</p>
                                </div>
                                </div>';
                }
            }
        }
        
        else
        {
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }

        echo $output;
    }