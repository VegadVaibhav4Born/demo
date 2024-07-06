<?php
// Include your database configuration file
include 'config.php';

// Start the session
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Function to fetch messages from database
function fetchMessages($con) {
    $messages = array();
    $messages_query = mysqli_query($con, "SELECT messages.content, messages.timestamp, users.username FROM messages JOIN users ON messages.user_id = users.id ORDER BY messages.timestamp ");
    while ($row = mysqli_fetch_assoc($messages_query)) {
        $messages[] = $row;
    }
    return $messages;
}

// Handle form submission to insert new message
if (isset($_POST['message'])) {
    $content = mysqli_real_escape_string($con, $_POST['message']);
    $user_id = $_SESSION['user_id'];
    $insert = mysqli_query($con, "INSERT INTO messages (user_id, content) VALUES ('$user_id', '$content')");
    if (!$insert) {
        $error = "Failed to insert message: " . mysqli_error($con);
    } else {
        // Redirect to avoid resubmission on page refresh
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch messages for display
$messages = fetchMessages($con);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>WhatsApp-like Chat App</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-CZZ7aJ86n13hDSF4WURq2Yp4X3O/nhL5a8bLZzkmI2df4brRYz4dPmY27FyMNBh2nJkC4Uzm73rD2C2d0sMBhA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        /* Custom styles for WhatsApp-like design */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-top: 20px;
        }
        .chat-box {
            height: 400px;
            overflow-y: scroll;
            padding: 10px;
            border-radius: 10px;
            background-color: #f5f5f5;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            max-width: 100%;
            position: relative;
        }
        .message p {
            margin: 0;
            word-wrap: break-word;
        }
        .message.sent {
            background-color: #dcf8c6;
            align-self: flex-end;
        }
        .message.received {
            background-color: #fff;
            align-self: flex-start;
        }
        .message .meta {
            font-size: 12px;
            color: #999;
            position: absolute;
            bottom: 5px;
            right: 5px;
        }
        .message.sent .meta {
            left: auto;
            right: 5px;
        }
        #messageInput {
            width: calc(100% - 60px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            font-size: 14px;
        }
        #messageForm button {
            padding: 10px 15px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">WhatsApp-like Chat App</h2>
        <div class="chat-box" id="chatBox">
            <?php foreach ($messages as $message): ?>
                <div class="message <?= ($message['username'] == $_SESSION['username']) ? 'sent' : 'received' ?>">
                    <p><?= htmlspecialchars($message['content']) ?></p>
                    <div class="meta"><?= htmlspecialchars($message['username']) ?> • <?= date('M d, Y H:i', strtotime($message['timestamp'])) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <form id="messageForm" method="POST" class="mt-3">
            <input type="text" name="message" id="messageInput" placeholder="Type a message..." required>
            <button type="submit"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>

    <!-- Bootstrap JS (bundle including Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" integrity="sha512-S7mO63Me6EVKm3js4YyUn9ONa6nsg5r8h0ysjEjt7R2FJx0dOZp2O7nK2mjXkdzK1rf9N1lnsrTndK2mr5Uogw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Function to scroll chat box to the bottom
        function scrollChatToBottom() {
            var chatBox = document.getElementById('chatBox');
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Scroll to bottom initially
        scrollChatToBottom();

        // Scroll to bottom after form submission
        document.getElementById('messageForm').addEventListener('submit', function() {
            setTimeout(scrollChatToBottom, 0); // Wait for DOM update
        });

        // Auto-refresh every 5 seconds
        setInterval(function() {
            location.reload();
        }, 5000); // Adjust interval as needed
    </script>
</body>
</html>
