<?php
// schedule_data.php

// Round Robin Schedule (5 Weeks)
$schedule = [
    1 => [
        ['Zeeman', 'Nxken'],
        ['AlvenBleken', 'Wasmachien1337'],
        ['MattiLeMattiBE', 'P4ulfiction'],
    ],
    2 => [
        ['Zeeman', 'AlvenBleken'],
        ['Nxken', 'P4ulfiction'], 
        ['Wasmachien1337', 'MattiLeMattiBE'],
    ],
    3 => [
        ['Zeeman', 'MattiLeMattiBE'],
        ['P4ulfiction', 'Wasmachien1337'],
        ['AlvenBleken', 'Nxken'],
    ],
    4 => [
        ['Zeeman', 'P4ulfiction'],
        ['Wasmachien1337', 'Nxken'],
        ['MattiLeMattiBE', 'AlvenBleken'],
    ],
    5 => [
        ['Zeeman', 'Wasmachien1337'],
        ['AlvenBleken', 'P4ulfiction'],
        ['Nxken', 'MattiLeMattiBE'],
    ],
];