<main class="main">
    <section class="section-auth">
        <h2>Register</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="fullname">Firstname</label>
                <input type="text" name="fullname" id="fullname" />
            </div>

            <div class="form-group">
                <label for="username">Lastname</label>
                <input type="text" name="username" id="username" />
            </div>

            <div class="form-group">
                <label for="usertype">User Type</label>
                <select name="usertype" id="usertype">
                    <option value="ADMIN">ADMIN</option>
                    <option value="CLIENT">CLIENT</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" />
            </div>

            <div class="form-group">
                <input type="submit" name="submit" value="Register" />
            </div>
        </form>
    </section>
</main>