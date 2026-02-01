<?php
// schedule_data.php

// Round Robin Schedule (7 Weeks for 7 Trainers)
// Each week has 3 matches, one trainer has a bye
$schedule = [
    1 => [
        // Bye: Wikit_wikit
        ['Nxken', 'PVG'],
        ['AlvenBleken', 'P4ulfiction'],
        ['Wasmachien1337', 'MattiLeMattiBE'],
    ],
    2 => [
        // Bye: P4ulfiction
        ['Wikit_wikit', 'PVG'],
        ['Nxken', 'MattiLeMattiBE'],
        ['AlvenBleken', 'Wasmachien1337'],
    ],
    3 => [
        // Bye: Wasmachien1337
        ['Wikit_wikit', 'P4ulfiction'],
        ['PVG', 'MattiLeMattiBE'],
        ['Nxken', 'AlvenBleken'],
    ],
    4 => [
        // Bye: Nxken
        ['Wikit_wikit', 'MattiLeMattiBE'],
        ['P4ulfiction', 'Wasmachien1337'],
        ['PVG', 'AlvenBleken'],
    ],
    5 => [
        // Bye: PVG
        ['Wikit_wikit', 'Wasmachien1337'],
        ['MattiLeMattiBE', 'AlvenBleken'],
        ['P4ulfiction', 'Nxken'],
    ],
    6 => [
        // Bye: MattiLeMattiBE
        ['Wikit_wikit', 'AlvenBleken'],
        ['Wasmachien1337', 'Nxken'],
        ['P4ulfiction', 'PVG'],
    ],
    7 => [
        // Bye: AlvenBleken
        ['Wikit_wikit', 'Nxken'],
        ['Wasmachien1337', 'PVG'],
        ['MattiLeMattiBE', 'P4ulfiction'],
    ],
];
