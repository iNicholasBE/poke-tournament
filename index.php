<?php
// index.php

// Include necessary files
include_once 'config.php';
include_once 'schedule_data.php';
include_once 'functions.php';

// --- Execution ---

// 1. Generate schedule for the current week if necessary
generate_weekly_schedule($conn, $current_week, $schedule);

// 2. Handle form submission
$error = handle_log_result($conn, $current_week);

// 3. Determine current view
$view = $_GET['view'] ?? 'leaderboard'; // Default view is leaderboard

// --- HTML Output Start ---
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokéNerds December League</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>PokéNerds December League</h1>
    <p style="color: white; font-weight: bold;">
        Huidige Week: <?= $current_week ?> van 5<br>
        Loopt van: <?= $week_start_date ?> t/m <?= $week_end_date ?>
    </p>
</header>

<div class="container">
    <div class="nav-tabs">
        <a href="?view=leaderboard" class="<?= $view == 'leaderboard' ? 'active' : '' ?>">🏆 Stand</a>
        <a href="?view=schedule" class="<?= $view == 'schedule' ? 'active' : '' ?>">🗓️ Schema</a>
        <a href="?view=log_match" class="<?= $view == 'log_match' ? 'active' : '' ?>">✅ Resultaat</a>
    </div>

    <?php if (isset($error) && $error !== null): ?>
        <div style="color: var(--poke-red); border: 2px solid var(--poke-red); padding: 10px; margin-bottom: 15px; font-weight: bold;">
            ⚠️ **Fout:** <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($view == 'leaderboard'): ?>
        <h2>🏆 Huidige Stand</h2>
        <div class="leaderboard">
            <?php 
            $leaderboard_sql = "SELECT *, (games_won - games_lost) AS game_diff FROM players ORDER BY points DESC, game_diff DESC, name ASC";
            $result = $conn->query($leaderboard_sql);
            ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Trainer</th>
                        <th>Ptn</th>
                        <th>W-L-D</th>
                        <th>G W-L</th>
                        <th>GD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $rank++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>**<?= $row['points'] ?>**</td>
                        <td><?= $row['wins'] ?>-<?= $row['losses'] ?>-<?= $row['draws'] ?></td>
                        <td><?= $row['games_won'] ?>-<?= $row['games_lost'] ?></td>
                        <td><?= $row['game_diff'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php elseif ($view == 'schedule'): ?>
        <h2>🗓️ Week <?= $current_week ?> Speelschema</h2>
        <p>
            De wedstrijden voor deze week (<?= $week_start_date ?> t/m <?= $week_end_date ?>):
        </p>
        
        <?php
        $matches_sql = "SELECT * FROM matches WHERE week = ? ORDER BY id ASC";
        $stmt = $conn->prepare($matches_sql);
        $stmt->bind_param("i", $current_week);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
             echo "<p style='text-align: center; margin-top: 20px;'>Geen matches gepland voor deze week. De competitie is mogelijk ten einde.</p>";
        }

        while($match = $result->fetch_assoc()):
            $status_color = $match['is_played'] ? 'green' : 'red';
            $status_text = $match['is_played'] ? 
                           "✅ GESPEELD: **" . $match['p1_games_won'] . "-" . $match['p2_games_won'] . "**" : 
                           "❌ OPEN";
        ?>
        <div class="schedule-card" style="border-color: <?= $status_color ?>;">
            <p style="margin: 0;">**<?= htmlspecialchars($match['player1_name']) ?>** vs. **<?= htmlspecialchars($match['player2_name']) ?>**</p>
            <small><?= $status_text ?></small>
        </div>
        <?php endwhile; $stmt->close(); ?>

    <?php elseif ($view == 'log_match'): ?>
        <h2>✅ Log Resultaat (Week <?= $current_week ?>)</h2>
        <form method="POST">
            <div class="form-group">
                <label for="match_id">Selecteer Match:</label>
                <select name="match_id" id="match_id" required>
                    <option value="">-- Kies een open match --</option>
                    <?php
                    $log_sql = "SELECT id, player1_name, player2_name FROM matches WHERE week = ? AND is_played = FALSE";
                    $stmt = $conn->prepare($log_sql);
                    $stmt->bind_param("i", $current_week);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows == 0) {
                        echo "<option disabled>Alle matches voor deze week zijn gelogd!</option>";
                    } else {
                        while($match = $result->fetch_assoc()):
                    ?>
                    <option value="<?= $match['id'] ?>">
                        <?= htmlspecialchars($match['player1_name']) ?> vs. <?= htmlspecialchars($match['player2_name']) ?>
                    </option>
                    <?php endwhile; } $stmt->close(); ?>
                </select>
            </div>

            <p style="text-align: center; font-weight: bold; margin: 15px 0;">Games Gewonnen (Best-of-3)</p>
            
            <div class="form-group">
                <label for="p1_games">Games gewonnen door Speler 1 (Eerste in selectie):</label>
                <input type="number" name="p1_games" id="p1_games" min="0" max="2" required>
            </div>

            <div class="form-group">
                <label for="p2_games">Games gewonnen door Speler 2 (Tweede in selectie):</label>
                <input type="number" name="p2_games" id="p2_games" min="0" max="2" required>
            </div>

            <input type="hidden" name="log_result" value="1">
            <button type="submit">Log Resultaat! (Gotta Catch 'Em All!)</button>
        </form>

    <?php endif; ?>
</div>

</body>
</html>
<?php 
$conn->close();
?>