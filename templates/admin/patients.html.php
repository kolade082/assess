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
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">NHS ID</th> <!-- make id a hyperlink to connect to a edit patient page for future implementation -->
                        <th scope="col">First name</th>
                        <th scope="col">Last name</th>
                        <th scope="col">DOB</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($patients as $patient): ?>
                    <tr scope="row">
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
        </div>
    </section>
</main>