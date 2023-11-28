<?php
// getAnomalies.php
session_start();

// Assuming $_SESSION['anomalies'] is where you store the anomalies
if (isset($_SESSION['anomalies'])) {
    header('Content-Type: application/json');
    echo json_encode($_SESSION['anomalies']);
} else {
    echo json_encode([]);
}
