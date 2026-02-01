-- ============================================
-- END OF SEASON MIGRATION SCRIPT
-- Run this at the end of each season to archive data
-- ============================================

-- SET THE SEASON NUMBER BEFORE RUNNING!
SET @season_number = 1;

-- ============================================
-- 1. Create archive tables if they don't exist
-- ============================================

CREATE TABLE IF NOT EXISTS archived_matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    season INT NOT NULL,
    week INT NOT NULL,
    player1_name VARCHAR(100) NOT NULL,
    player2_name VARCHAR(100) NOT NULL,
    p1_games_won INT DEFAULT 0,
    p2_games_won INT DEFAULT 0,
    is_played BOOLEAN DEFAULT FALSE,
    archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS archived_standings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    season INT NOT NULL,
    player_name VARCHAR(100) NOT NULL,
    final_rank INT NOT NULL,
    points INT DEFAULT 0,
    wins INT DEFAULT 0,
    losses INT DEFAULT 0,
    draws INT DEFAULT 0,
    games_won INT DEFAULT 0,
    games_lost INT DEFAULT 0,
    game_diff INT DEFAULT 0,
    is_champion BOOLEAN DEFAULT FALSE,
    is_magikarp BOOLEAN DEFAULT FALSE,
    archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- 2. Archive current matches
-- ============================================

INSERT INTO archived_matches (season, week, player1_name, player2_name, p1_games_won, p2_games_won, is_played)
SELECT @season_number, week, player1_name, player2_name, p1_games_won, p2_games_won, is_played
FROM matches;

-- ============================================
-- 3. Archive current standings with final ranks
-- ============================================

INSERT INTO archived_standings (season, player_name, final_rank, points, wins, losses, draws, games_won, games_lost, game_diff, is_champion, is_magikarp)
SELECT
    @season_number,
    name,
    @rank := @rank + 1 AS final_rank,
    points,
    wins,
    losses,
    draws,
    games_won,
    games_lost,
    (games_won - games_lost) AS game_diff,
    (@rank = 1) AS is_champion,
    FALSE AS is_magikarp
FROM players, (SELECT @rank := 0) r
ORDER BY points DESC, (games_won - games_lost) DESC, name ASC;

-- Mark the last place player as Magikarp
UPDATE archived_standings
SET is_magikarp = TRUE
WHERE season = @season_number
AND final_rank = (SELECT MAX(final_rank) FROM archived_standings WHERE season = @season_number);

-- ============================================
-- 4. Clear matches table for new season
-- ============================================

DELETE FROM matches;

-- Reset auto-increment (optional)
ALTER TABLE matches AUTO_INCREMENT = 1;

-- ============================================
-- 5. Reset player stats for new season
-- ============================================

UPDATE players SET
    points = 0,
    wins = 0,
    losses = 0,
    draws = 0,
    games_won = 0,
    games_lost = 0;

-- ============================================
-- 6. Show archived data confirmation
-- ============================================

SELECT 'Archived Matches:' AS '';
SELECT COUNT(*) AS total_matches FROM archived_matches WHERE season = @season_number;

SELECT 'Final Standings:' AS '';
SELECT final_rank, player_name, points, wins, losses, game_diff,
       CASE WHEN is_champion THEN '🏆 CHAMPION' WHEN is_magikarp THEN '🐟 MAGIKARP' ELSE '' END AS award
FROM archived_standings
WHERE season = @season_number
ORDER BY final_rank;

SELECT 'Season migration complete! Ready for new season.' AS status;
