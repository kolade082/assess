<main class="main-dashboard">
    <!-- can move to a template -->
    <div class="dashboard-sidebar">
        <div class="dashboard-links">
            <a class="btn btn-custom my-3" href="dashboard">Dashboard</a>
            <a class="btn btn-custom my-3" href="patients">Manage Patients</a>
            <a class="btn btn-custom my-3" href="#">Search Patients</a> <!-- add links -->
            <a class="btn btn-custom my-3" href="#">Anomalies</a>
            <a class="btn btn-custom my-3" href="#">Help?</a>
        </div>
    </div>
    <section class="dashboard-content">
        <h1>NHS Patient Record Dashboard</h1>

        <div class="dashboard-section">
            <h2 class="h2 fw-bold">Recent Activity</h2>
            <div class="d-flex flex-row justify-content-between">
            <p class="lead">Overview of recent system usage, updates, and alerts.</p>
            <button class="btn btn-custom">SYNC</button>
            </div>
            
            

        </div>

        <!-- Section for Real-Time Alerts -->
        <div class="dashboard-section">
            <h2 class="h2 fw-bold">Real-Time Alerts</h2>
            <div id="realTimeAlerts">
                <?=$html?> <!-- apply this class on the button - class="btn btn-custom"
                                and p tag -  class="lead" 
                                want to add a toggle switch to change btw current implementaiton and richard's implementaiton along with 'x'-->

            </div>
        </div>

        <!-- Modal Structure -->
        <div id="comparisonModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <div class="comparison-container">
                    <div class="data-pane">
                        <!-- Data from your system will be loaded here -->
                        <h3>Your System Data</h3>
                        <div id="yourSystemData"></div>
                    </div>
                    <div class="data-pane">
                        <!-- Data from NHS system will be loaded here -->
                        <h3>NHS System Data</h3>
                        <div id="nhsSystemData"></div>
                    </div>
                </div>
                <!-- Action buttons -->
                <div class="modal-actions">
                    <button class="keep" onclick="keepData()">Keep</button>
                    <button class="overwrite" onclick="overwriteData()">Overwrite</button>
                </div>

            </div>
        </div>



    </section>
    <script>

        function viewDetails(button) {
            const yourData = JSON.parse(button.getAttribute('data-your-data'));
            const nhsData = JSON.parse(button.getAttribute('data-nhs-data'));

            // Format the data into HTML
            document.getElementById('yourSystemData').innerHTML = formatData(yourData, "Your System Data");
            document.getElementById('nhsSystemData').innerHTML = formatData(nhsData, "NHS Data");

            // Display the modal
            document.getElementById('comparisonModal').style.display = 'block';
        }

        function formatData(yourData, nhsData, title) {
            let comparisonHtml = `<div class="data-table">
<!--        <h3>${title}</h3>-->
        <table>`;

            // List of fields to compare
            const fields = ['id', 'firstname', 'lastname', 'dob', 'phone', 'email'];

            fields.forEach(field => {
                const yourValue = yourData[field] || '';
                const nhsValue = nhsData[field] || '';
                const isCorrect = yourValue === nhsValue;

                comparisonHtml += `<tr>
            <th>${field.charAt(0).toUpperCase() + field.slice(1)}</th>
            <td style="background-color: ${isCorrect ? '#90ee90' : '#ffcccb'}">
                ${yourValue}
            </td>
            <td style="background-color: ${isCorrect ? '#90ee90' : '#ffcccb'}">
                ${nhsValue}
            </td>
        </tr>`;
            });

            comparisonHtml += `</table></div>`;
            return comparisonHtml;
        }



        function closeModal() {
            document.getElementById('comparisonModal').style.display = 'none';
        }

        function keepData() {
            // Implement logic to keep the current data
            console.log("Keep data logic goes here");
            closeModal(); // Close the modal after action
        }

        function overwriteData() {
            // Implement logic to overwrite with NHS data
            console.log("Overwrite data logic goes here");
            closeModal(); // Close the modal after action
        }
    </script>

</main>