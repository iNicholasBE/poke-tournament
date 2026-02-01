<?php
// schedule_data.php

// All trainers for Season 2
$trainers = [
    'Wikit_wikit',
    'Nxken',
    'AlvenBleken',
    'Wasmachien1337',
    'MattiLeMattiBE',
    'P4ulfiction',
    'PVG'
];

// Round Robin Schedule (7 Weeks for 7 Trainers)
// Each week has 3 matches, one trainer has a bye
$schedule = [
    1 => [
        ['Nxken', 'PVG'],
        ['AlvenBleken', 'P4ulfiction'],
        ['Wasmachien1337', 'MattiLeMattiBE'],
    ],
    2 => [
        ['Wikit_wikit', 'PVG'],
        ['Nxken', 'MattiLeMattiBE'],
        ['AlvenBleken', 'Wasmachien1337'],
    ],
    3 => [
        ['Wikit_wikit', 'P4ulfiction'],
        ['PVG', 'MattiLeMattiBE'],
        ['Nxken', 'AlvenBleken'],
    ],
    4 => [
        ['Wikit_wikit', 'MattiLeMattiBE'],
        ['P4ulfiction', 'Wasmachien1337'],
        ['PVG', 'AlvenBleken'],
    ],
    5 => [
        ['Wikit_wikit', 'Wasmachien1337'],
        ['MattiLeMattiBE', 'AlvenBleken'],
        ['P4ulfiction', 'Nxken'],
    ],
    6 => [
        ['Wikit_wikit', 'AlvenBleken'],
        ['Wasmachien1337', 'Nxken'],
        ['P4ulfiction', 'PVG'],
    ],
    7 => [
        ['Wikit_wikit', 'Nxken'],
        ['Wasmachien1337', 'PVG'],
        ['MattiLeMattiBE', 'P4ulfiction'],
    ],
];

// Bye per week (trainer who doesn't play)
$byes = [
    1 => 'Wikit_wikit',
    2 => 'P4ulfiction',
    3 => 'Wasmachien1337',
    4 => 'Nxken',
    5 => 'PVG',
    6 => 'MattiLeMattiBE',
    7 => 'AlvenBleken',
];

/**
 * Validates that the schedule is a valid round-robin:
 * - Each player plays exactly 6 matches (n-1 for 7 players)
 * - Every pair meets exactly once
 * Returns array of errors, empty if valid
 */
function validate_schedule($trainers, $schedule) {
    $errors = [];
    $matchups = [];
    $player_matches = array_fill_keys($trainers, 0);

    foreach ($schedule as $week => $matches) {
        foreach ($matches as $match) {
            $p1 = $match[0];
            $p2 = $match[1];

            // Create sorted key for matchup
            $key = $p1 < $p2 ? "$p1 vs $p2" : "$p2 vs $p1";

            if (isset($matchups[$key])) {
                $errors[] = "Duplicate matchup: $key (Week $week and Week {$matchups[$key]})";
            } else {
                $matchups[$key] = $week;
            }

            $player_matches[$p1]++;
            $player_matches[$p2]++;
        }
    }

    // Check each player plays 6 matches
    foreach ($player_matches as $player => $count) {
        if ($count !== 6) {
            $errors[] = "$player plays $count matches instead of 6";
        }
    }

    // Check we have all 21 matchups
    $expected_matchups = (count($trainers) * (count($trainers) - 1)) / 2;
    if (count($matchups) !== $expected_matchups) {
        $errors[] = "Expected $expected_matchups matchups, got " . count($matchups);
    }

    return $errors;
}
