<main class="main-dashboard">
    <div class="dashboard-sidebar">
        <div class="dashboard-links">
            <a href="dashboard">Dashboard</a>
            <a href="patients">Manage Patients</a>
            <a href="appointments">Appointments</a>
            <a href="reports">Generate Reports</a>
            <a href="analytics">Analytics</a>
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