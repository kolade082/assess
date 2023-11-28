<main class="main-dashboard">
<div class="dashboard-sidebar">
        <div class="dashboard-links">
            <a class="btn btn-custom my-3" href="dashboard">Dashboard</a>
            <a class="btn btn-custom my-3" href="patients">Manage Patients</a>
            <a class="btn btn-custom my-3" href="#">Search Patients</a> <!-- add links search - do anomalies check - display using the current method of viewing anomalies-->
            <a class="btn btn-custom my-3" href="#">Anomalies</a> <!-- should link to the current method of resolving anomalies -->
            <a class="btn btn-custom my-3" href="#">Help?</a>  <!-- should link to the contact form -->
        </div>
    </div>
    <section class="dashboard-content">
        <h1>Patient Records</h1>
        <table class="patient-details-table">
            <thead>
                <tr>
                    <th>NHS ID</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone Number</th>

                </tr>

            </thead>
            <tbody>
            <?php foreach ($patients as $patient): ?>
                <tr>
                    <td><?= htmlspecialchars($patient['id']) ?></td>
                    <td><?= htmlspecialchars($patient['firstname']) ?></td>
                    <td></td>
                    <!-- <td><?= htmlspecialchars($patient['lastname']) ?></td> -->
                    <td><?= htmlspecialchars($patient['dob']) ?></td>
                    <td><?= htmlspecialchars($patient['gender']) ?></td>
                    <td><?= htmlspecialchars($patient['email']) ?></td>
                    <td><?= htmlspecialchars($patient['phone_number']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>