<main class="sidebar">

    <section class="left">
        <?php require "navCat.php"; ?>
    </section>

    <section class="right">
        <?php
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width: 40%">Enquiries</th>';
        echo '<th style="width: 15%">Action</th>';
        echo '<th style="width: 15%">Status</th>';
        echo '</tr>';

        foreach ($contacts as $contact) {
            echo '<tr>';
            echo '<td>' . $contact['enquiry'] . '</td>';
            if (isset($contact['adminId'])) {
                echo '<td>Completed by ' . $contact['fullname'] . '</td>';
                echo '<td>Complete</td>';
            } else {
                echo '<td><form method="post" action="updateEnquiry">
				<input type="hidden" name="id" value="' . $contact['id'] . '" />
				<input type="submit" name="submit" value="Mark as Complete" />
				</form></td>';
                echo '<td>Not Complete</td>';
            }
            echo '</tr>';
        }
        echo '</thead>';
        echo '</table>';


        ?>
    </section>
</main>