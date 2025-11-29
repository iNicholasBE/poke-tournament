<?php
// config.php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'pokemon');
define('DB_PASS', '$NA!6qri2eQn7eoq');
define('DB_NAME', 'poketournament_');

// League Configuration
// The league starts on the week of December 1st (Monday start of week)
define('LEAGUE_START_DATE', '2025-12-01');

// Connect to Database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Calculates the current week number based on the start date.
 * Week 1 starts on LEAGUE_START_DATE.
 * * @return int The current week number (1 to 5).
 */
function calculate_current_week() {
    $start_date = new DateTime(LEAGUE_START_DATE);
    $current_date = new DateTime();

    // Set the start date to the beginning of its week (Monday)
    // 1=Mon, 7=Sun. We assume a Monday start.
    if ($start_date->format('N') != 1) {
        $start_date->modify('last Monday');
    }

    $interval = $start_date->diff($current_date);
    $days_since_start = $interval->days;

    // Calculate the week number (integer division by 7, plus 1 for Week 1)
    $week_number = floor($days_since_start / 7) + 1;

    // League has 5 rounds max
    return min($week_number, 5);
}

$current_week = calculate_current_week();