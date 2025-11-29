<?php
// config.php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'pokemon');
define('DB_PASS', '$NA!6qri2eQn7eoq');
define('DB_NAME', 'poketournament_');

// League Configuration
// De competitie start op de week van 1 december. Dit moet een datum in het verleden of heden zijn.
define('LEAGUE_START_DATE', '2025-12-01'); // 1 december 2025 is een maandag

// Connect to Database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Berekent het huidige weeknummer en het datumbereik voor die week.
 * De competitie duurt maximaal 5 weken.
 * @return array{week_number: int, start_date: string, end_date: string}
 */
function get_week_info() {
    $start_date_str = LEAGUE_START_DATE;
    $start_date_obj = new DateTime($start_date_str);
    $current_date = new DateTime();

    // 1. Zorg ervoor dat de startdatum het begin van de Week 1 (Maandag) is.
    // 1=Maandag. Als de startdatum geen Maandag is, gaan we terug naar de laatste Maandag.
    if ($start_date_obj->format('N') != 1) {
        $start_date_obj->modify('last Monday');
    }
    
    // 2. Bereken het verschil in dagen sinds de start van Week 1 (op maandag)
    $diff = $start_date_obj->diff($current_date);
    $days_since_start_monday = $diff->days;
    
    // Als de huidige datum vóór de startdatum ligt, behandelen we dit als Week 1 (in afwachting)
    if ($current_date < $start_date_obj) {
        $days_since_start_monday = 0; 
    }

    // 3. Bereken het huidige weeknummer
    // (Dagen / 7) + 1 voor de eerste week
    $week_number = floor($days_since_start_monday / 7) + 1;

    // Beperk tot maximaal 5 weken
    $max_weeks = 5;
    $week_number = min($week_number, $max_weeks);

    // 4. Bereken het datumbereik voor de bepaalde week
    // Tel (weeknummer - 1) * 7 dagen op bij de basis startdatum
    $week_start_obj = clone $start_date_obj;
    $week_start_obj->modify('+' . ($week_number - 1) * 7 . ' days');
    
    $week_end_obj = clone $week_start_obj;
    $week_end_obj->modify('+6 days'); // Week eindigt 6 dagen later (zondag)

    return [
        'week_number' => $week_number,
        'start_date' => $week_start_obj->format('d/m/Y'),
        'end_date' => $week_end_obj->format('d/m/Y'),
    ];
}

$week_info = get_week_info();
$current_week = $week_info['week_number'];
$week_start_date = $week_info['start_date'];
$week_end_date = $week_info['end_date'];