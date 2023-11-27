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
        <h1>NHS Patient Record Dashboard</h1>

        <!-- Section for Synchronization Status -->
        <div class="dashboard-section">
            <h2>Data Synchronization Status</h2>
            <p>Last sync: <span id="lastSyncTime">[time]</span></p>
            <button onclick="initiateSync()">Sync Now</button>
        </div>


        <div class="dashboard-section">
            <h2>Recent Activity</h2>
            <p>Overview of recent system usage, updates, and alerts.</p>

        </div>

        <!-- Section for Real-Time Alerts -->
        <div class="dashboard-section">
            <h2>Real-Time Alerts</h2>
            <div id="realTimeAlerts">

            </div>
        </div>

    </section>
</main>