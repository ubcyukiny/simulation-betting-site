import pandas as pd
from nba_api.stats.endpoints import leaguegamefinder
from datetime import datetime

# Get current year
current_year = datetime.now().year

# Get games data from NBA API for the 2022-23 regular season
game_finder = leaguegamefinder.LeagueGameFinder(season_nullable='2022-23')
games = game_finder.get_data_frames()[0]

# Separate games data for home and away teams
home_games = games[~games['MATCHUP'].str.contains('@')]
away_games = games[games['MATCHUP'].str.contains('@')]

# Merge home and away games on 'GAME_ID'
games_combined = pd.merge(home_games, away_games, on='GAME_ID', suffixes=('_Home', '_Away'))

# Extract only the essential columns
games_combined = games_combined[['GAME_ID', 'PTS_Home', 'PTS_Away', 'GAME_DATE_Home', 'TEAM_ID_Home', 'TEAM_ID_Away']]

# Rename the columns
games_combined = games_combined.rename(columns={
    'GAME_ID': 'GameID',
    'PTS_Home': 'ScoreHome',
    'PTS_Away': 'ScoreAway',
    'GAME_DATE_Home': 'Date',
    'TEAM_ID_Home': 'HomeTeamID',
    'TEAM_ID_Away': 'AwayTeamID'
})

# Export to Excel
output_file = 'nba_games_this_season_combined.xlsx'
games_combined.to_excel(output_file, index=False, engine='openpyxl')
