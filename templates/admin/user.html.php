<main class="sidebar">

    <section class="left">
        <?php require "navCat.php" ?>
    </section>
    <section class="right">
        <h2>Users</h2>

        <a class="new" href="register">Add new user</a>

        <?php
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Name</th>';
        echo '<th style="width: 25%">User Type</th>';
        echo '<th style="width: 15%">&nbsp;</th>';
        echo '</tr>';

        foreach ($users as $user) {
            echo '<tr>';
            echo '<td>' . $user['fullname'] . '</td>';
            echo '<td>' . $user['usertype'] . '</td>';
            echo '<td><form method="post" action="deleteuser">
            <input type="hidden" name="id" value="' . $user['id'] . '" />
            <input type="submit" name="submit" value="Delete" />
            </form></td>';
            echo '</tr>';
        }

        echo '</thead>';
        echo '</table>';
        ?>
    </section>


</main>
