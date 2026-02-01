<?php
// functions.php

function redirect($view = 'leaderboard') {
    header("Location: index.php?view=" . $view);
    exit();
}

/**
 * Ensures the matches for the current week are in the database.
 * Runs once per week implicitly.
 */
function generate_weekly_schedule($conn, $current_week, $schedule) {
    if (isset($schedule[$current_week])) {
        foreach ($schedule[$current_week] as $matchup) {
            $p1 = $matchup[0];
            $p2 = $matchup[1];

            // Check if the match already exists for this week
            $stmt = $conn->prepare("SELECT id FROM matches WHERE week = ? AND ((player1_name = ? AND player2_name = ?) OR (player1_name = ? AND player2_name = ?))");
            $stmt->bind_param("issss", $current_week, $p1, $p2, $p2, $p1);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 0) {
                // Insert new match
                $stmt_insert = $conn->prepare("INSERT INTO matches (week, player1_name, player2_name) VALUES (?, ?, ?)");
                $stmt_insert->bind_param("iss", $current_week, $p1, $p2);
                $stmt_insert->execute();
                $stmt_insert->close();
            }
            $stmt->close();
        }
    }
}

/**
 * Handles the form submission and updates database with points.
 */
function handle_log_result($conn, $current_week) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['log_result'])) {
        return null;
    }

    $match_id = (int)$_POST['match_id'];
    $p1_games = (int)$_POST['p1_games'];
    $p2_games = (int)$_POST['p2_games'];

    // 1. Validation (Must be a 3-0 or 2-1 result - always 3 games played)
    $total_games = $p1_games + $p2_games;
    if ($total_games !== 3 || ($p1_games < 2 && $p2_games < 2)) {
        return "Ongeldig resultaat. Er moeten 3 games gespeeld zijn (3-0 of 2-1).";
    }

    // 2. Fetch Match Details & Check if already played
    $stmt = $conn->prepare("SELECT player1_name, player2_name, is_played FROM matches WHERE id = ?");
    $stmt->bind_param("i", $match_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $match = $result->fetch_assoc();
    $stmt->close();

    if (!$match || $match['is_played']) {
        return "Fout: Match niet gevonden of al gespeeld.";
    }

    $player1 = $match['player1_name'];
    $player2 = $match['player2_name'];
    $p1_points = 0;
    $p2_points = 0;
    $p1_wins = 0; $p1_losses = 0;
    $p2_wins = 0; $p2_losses = 0;
    $draw = 0;

    // 3. Apply Point System Logic (3-0 or 2-1 format)
    if ($p1_games >= 2) { // P1 Wins Match (3-0 or 2-1)
        $p1_wins = 1; $p2_losses = 1;
        $p1_points = 3;
        $p2_points = ($p2_games == 1) ? 1 : 0; // P2 gets 1 point for 2-1 loss, 0 for 3-0 loss
    } elseif ($p2_games >= 2) { // P2 Wins Match (3-0 or 2-1)
        $p2_wins = 1; $p1_losses = 1;
        $p2_points = 3;
        $p1_points = ($p1_games == 1) ? 1 : 0; // P1 gets 1 point for 1-2 loss, 0 for 0-3 loss
    }
    
    // 4. Update Matches Table (Transactionally better, but simple UPDATE for now)
    $stmt = $conn->prepare("UPDATE matches SET p1_games_won = ?, p2_games_won = ?, is_played = TRUE WHERE id = ?");
    $stmt->bind_param("iii", $p1_games, $p2_games, $match_id);
    $stmt->execute();
    $stmt->close();

    // 5. Update Player 1 Stats
    $stmt = $conn->prepare("UPDATE players SET 
        points = points + ?, 
        wins = wins + ?, 
        losses = losses + ?, 
        draws = draws + ?, 
        games_won = games_won + ?, 
        games_lost = games_lost + ? 
        WHERE name = ?");
    $stmt->bind_param("iiiiiss", $p1_points, $p1_wins, $p1_losses, $draw, $p1_games, $p2_games, $player1);
    $stmt->execute();
    $stmt->close();

    // 6. Update Player 2 Stats
    $stmt = $conn->prepare("UPDATE players SET 
        points = points + ?, 
        wins = wins + ?, 
        losses = losses + ?, 
        draws = draws + ?, 
        games_won = games_won + ?, 
        games_lost = games_lost + ? 
        WHERE name = ?");
    $stmt->bind_param("iiiiiss", $p2_points, $p2_wins, $p2_losses, $draw, $p2_games, $p1_games, $player2);
    $stmt->execute();
    $stmt->close();

    redirect('leaderboard');
    return null; // Should not be reached due to redirect
}