import pandas as pd
from nba_api.stats.endpoints import leaguegamefinder, boxscoretraditionalv2
from datetime import datetime

# Get current year
current_year = datetime.now().year

# Get games data from NBA API for the 2022-23 regular season
game_finder = leaguegamefinder.LeagueGameFinder(season_nullable='2022-23')
games = game_finder.get_data_frames()[0]

# Filter games for specific GameID's
selected_game_ids = ['0022200650', '0022200606']
selected_games = games[games['GAME_ID'].isin(selected_game_ids)]

# Initialize an empty list to store player statistics
player_stats_list = []

# Loop through each selected game to get player statistics
for game_id in selected_game_ids:
    # Fetch detailed player statistics for the game
    boxscore = boxscoretraditionalv2.BoxScoreTraditionalV2(game_id=game_id)
    player_stats = boxscore.get_data_frames()[0]
    # Append player statistics to the list
    player_stats_list.append(player_stats)

# Concatenate player statistics for the selected games into a single DataFrame
player_stats_combined = pd.concat(player_stats_list)

# Extract only the required columns
player_stats_combined = player_stats_combined[['PLAYER_ID', 'GAME_ID', 'MIN', 'PTS', 'AST', 'REB']]

# Rename the columns
player_stats_combined = player_stats_combined.rename(columns={
    'PLAYER_ID': 'PlayerID',
    'MIN': 'Minutes',
    'PTS': 'Points',
    'AST': 'Assists',
    'REB': 'Rebounds'
})

# Export to Excel
output_file = 'player_stats_selected_games.xlsx'
player_stats_combined.to_excel(output_file, index=False, engine='openpyxl')
