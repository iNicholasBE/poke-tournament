-- ============================================
-- SEASON 2 SETUP SCRIPT
-- Run this after end_season.sql
-- ============================================

-- Add new trainer PVG
INSERT INTO players (name, points, wins, losses, draws, games_won, games_lost)
VALUES ('PVG', 0, 0, 0, 0, 0, 0)
ON DUPLICATE KEY UPDATE name = name; -- Ignore if already exists

-- Verify all 7 trainers are ready
SELECT 'Season 2 Trainers:' AS '';
SELECT name FROM players ORDER BY name;

SELECT CONCAT(COUNT(*), ' trainers ready for Season 2!') AS status FROM players;
