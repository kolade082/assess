<main class="main">
    <section class="section-auth">
        <h2>Contact Us</h2>
        <?php if (isset($me)) {
            echo $me;
        } ?>
        <form action="submit-contact.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" placeholder="Enter subject">
            </div>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" placeholder="Enter your message" required></textarea>
            </div>

            <div class="form-group">
                <input type="submit" value="Send Message">
            </div>
        </form>
    </section>
</main>