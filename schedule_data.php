<?php
// schedule_data.php

// Round Robin Schedule (5 Weeks)
$schedule = [
    // Week 1: Blijft intact volgens uw wens
    1 => [
        ['Zeeman', 'Nxken'],
        ['AlvenBleken', 'Wasmachien1337'],
        ['MattiLeMattiBE', 'P4ulfiction'],
    ],
    // Week 2: Nieuwe indeling zonder herhalingen
    2 => [
        ['Zeeman', 'AlvenBleken'],
        ['Nxken', 'P4ulfiction'], // Ontbrekende match
        ['Wasmachien1337', 'MattiLeMattiBE'], // Ontbrekende match (was herhaald in uw origineel)
    ],
    // Week 3: Nieuwe indeling zonder herhalingen
    3 => [
        ['Zeeman', 'MattiLeMattiBE'],
        ['P4ulfiction', 'Wasmachien1337'],
        ['AlvenBleken', 'Nxken'],
    ],
    // Week 4: Nieuwe indeling zonder herhalingen
    4 => [
        ['Zeeman', 'P4ulfiction'],
        ['Wasmachien1337', 'Nxken'],
        ['MattiLeMattiBE', 'AlvenBleken'],
    ],
    // Week 5: Nieuwe indeling zonder herhalingen
    5 => [
        ['Zeeman', 'Wasmachien1337'],
        ['AlvenBleken', 'P4ulfiction'],
        ['Nxken', 'MattiLeMattiBE'],
    ],
];