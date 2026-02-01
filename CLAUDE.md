# PokéNerds Tournament League

## Project Overview

This is a PHP web application for managing a Pokémon tournament league among friends. The tournament follows a round-robin format where all trainers play against each other over multiple weeks.

## Technology Stack

- **Backend:** PHP (server-side logic and templating)
- **Frontend:** HTML5 + CSS3 (no JavaScript)
- **Database:** MySQL (`poketournament_` database)
- **Web Server:** Apache (with .htaccess)
- **Font:** "Press Start 2P" Google Font (retro/8-bit style)

## File Structure

| File | Purpose |
|------|---------|
| `index.php` | Main application template - HTML structure, view logic, form handling, database queries |
| `config.php` | Database connection, league start date, week calculation logic |
| `functions.php` | Business logic - schedule generation, match result logging with point calculation |
| `schedule_data.php` | Hardcoded round-robin matchups for all weeks |
| `style.css` | Pokémon-themed colors, layout, responsive design |
| `.htaccess` | Apache configuration |

## Database Schema

### `players` table
- `id` INT (primary key)
- `name` VARCHAR (trainer name - unique)
- `points` INT (total tournament points)
- `wins` INT (match wins count)
- `losses` INT (match losses count)
- `draws` INT (always 0)
- `games_won` INT (total individual games won)
- `games_lost` INT (total individual games lost)

### `matches` table
- `id` INT (primary key)
- `week` INT (tournament week)
- `player1_name` VARCHAR (first trainer)
- `player2_name` VARCHAR (second trainer)
- `p1_games_won` INT (games won by player 1)
- `p2_games_won` INT (games won by player 2)
- `is_played` BOOLEAN (0=not logged, 1=result recorded)

## Current Season: Season 2

### Trainers (7 players)
1. Wikit_wikit
2. Nxken
3. AlvenBleken
4. Wasmachien1337
5. MattiLeMattiBE
6. P4ulfiction
7. PVG (new in Season 2)

### Scoring System
- **Match Format:** Best-of-3 games (always play all 3)
- **Valid Results:** 3-0, 2-1
- **Points:**
  - 3 points for match win
  - 1 point for 2-1 loss
  - 0 points for 3-0 loss
- **Ranking:** By points (DESC), then game differential (DESC), then name (ASC)

## UI Views

1. **Stand (Leaderboard)** - Current standings with points, W-L-D, games
2. **Schema (Schedule)** - Matches for selected/current week
3. **Resultaat (Log Match)** - Form to record match results
4. **Hall of Fame** - Previous season champions

## Season 1 Results (Hall of Fame)
- **Champion:** Nxken
- **Magikarp (Last Place):** P4ulfiction

## Key Configuration

- `LEAGUE_START_DATE` in config.php controls when the season starts
- Week calculation is automatic based on current date
- Matches can be logged even after their week has passed

## Database Credentials (config.php)
```
Host: localhost
Username: pokemon
Password: $NA!6qri2eQn7eoq
Database: poketournament_
```

## Development Notes

- The schedule in `schedule_data.php` needs to be regenerated when adding/removing trainers
- For 7 trainers, a full round-robin requires 7 weeks (each trainer plays 6 matches)
- Each week has either 3 matches (one trainer has a bye) in a 7-player format
