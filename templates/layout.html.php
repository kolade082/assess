<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- bootstrap import -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="../styles.css" media="screen">
    <link rel="stylesheet" href="../mobile.css" media="screen and (max-width: 800px)">
    <link rel="stylesheet" href="../desktop.css" media="screen and (min-width: 800px)">

    <title>Assess</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<body>

    <header>
        <div>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                <div class="d-flex flex-row justify-content-between p-0">
                    <div id="ogLogo" >
                        <img  src="" alt="nhs logo">
                    </div>
                    <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="auth-links">
                        <p><a href="../admin/logout">Logout</a></p>
                        <p><a href="../admin/dashboard">Dashboard</a></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-links">
                    <p><a href="../admin/register" class="admin-signup">Sign Up</a></p>
                    <p><a href="../admin/login" class="admin-login">Login</a></p>
                </div>
            <?php endif; ?>

            <nav class="navbar navbar-expand-lg bg-body-tertiary navbar-light p-0">
                <div class="container-fluid p-0">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <!--  -->
                            <li class="nav-item " id="moblogo">
                            <a class="nav-link active" aria-current="page" href="#"><img src="#" alt="nhs logo" class=""></a>
                                
                            </li>
                            <li class="nav-item">
                                
                            </li> <!-- should be removed no use -->
                            <li class="nav-item">
                                <a class="nav-link" href="../about">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../contact">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../faqs">FAQs</a>
                            </li>
                            
                        </ul>
                    </div>
                </div>
            </nav>

        </div>
    </header>
    <div>
        <?= $output ?? ""; ?> <!-- main goes here -->
    </div>


    <!-- Chat Box -->
    <div id="chatBox" class="chat-box" style="display: none;">
        <div class="chat-box-header">
            Chat with Us
            <button id="closeChat" class="close-chat">&times;</button>
        </div>
        <div class="chat-box-messages">
            <!-- Preloaded Messages for Demo -->
            <div class="message user-message">Hello, I have a question.</div>
            <div class="message admin-message">Sure, how can we assist you?</div>
            <div class="message user-message">Can you tell me more about your services?</div>
            <div class="message admin-message">Certainly! We offer a wide range of healthcare services...</div>
            <!-- More messages can be added here -->
        </div>
        <input type="text" id="chatInput" placeholder="Type a message..." class="chat-box-input">
    </div>

    <div class="chat-icon-container">
        <button class="chat-icon-button">
            <i class="fas fa-comments"></i>
        </button>
    </div>

    <footer>
        &copy; NHS
        <?= date('Y'); ?>
    </footer>

    <!-- bootstrap import -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

<script>
    let isUser = true;

    document.querySelector('.chat-icon-button').addEventListener('click', function () {
        document.getElementById('chatBox').style.display = 'block';
    });

    document.getElementById('closeChat').addEventListener('click', function () {
        document.getElementById('chatBox').style.display = 'none';
    });

    // Functionality to handle chat input and display messages
    document.getElementById('chatInput').addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            const message = this.value.trim();
            if (message) {
                // Create a message div
                const msgDiv = document.createElement('div');
                msgDiv.classList.add('message');

                // Alternate between 'User' and 'Admin'
                const sender = isUser ? 'User: ' : 'Admin: ';
                msgDiv.textContent = sender + message;
                msgDiv.classList.add(isUser ? 'user-message' : 'admin-message');
                document.querySelector('.chat-box-messages').appendChild(msgDiv);

                // Scroll to the bottom of the chat box
                const chatBox = document.querySelector('.chat-box-messages');
                chatBox.scrollTop = chatBox.scrollHeight;

                this.value = ''; // Clear input field
                isUser = !isUser; // Toggle sender
            }
        }
    });
</script>




</html>