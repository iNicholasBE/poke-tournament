<?php
// schedule_data.php

// Round Robin Schedule (5 Weeks)
$schedule = [
    1 => [
        ['Wikit_wikit', 'Nxken'],
        ['AlvenBleken', 'Wasmachien1337'],
        ['MattiLeMattiBE', 'P4ulfiction'],
    ],
    2 => [
        ['Wikit_wikit', 'AlvenBleken'],
        ['Nxken', 'P4ulfiction'], 
        ['Wasmachien1337', 'MattiLeMattiBE'],
    ],
    3 => [
        ['Wikit_wikit', 'MattiLeMattiBE'],
        ['P4ulfiction', 'Wasmachien1337'],
        ['AlvenBleken', 'Nxken'],
    ],
    4 => [
        ['Wikit_wikit', 'P4ulfiction'],
        ['Wasmachien1337', 'Nxken'],
        ['MattiLeMattiBE', 'AlvenBleken'],
    ],
    5 => [
        ['Wikit_wikit', 'Wasmachien1337'],
        ['AlvenBleken', 'P4ulfiction'],
        ['Nxken', 'MattiLeMattiBE'],
    ],
];