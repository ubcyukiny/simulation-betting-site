import pandas as pd
from nba_api.stats.endpoints import leaguegamefinder
from datetime import datetime

# Get current year
current_year = datetime.now().year

# Get games data from NBA API for the current season
game_finder = leaguegamefinder.LeagueGameFinder(season_nullable=current_year)
games = game_finder.get_data_frames()[0]

# Convert data to DataFrame
games_df = pd.DataFrame(games)

# Export to Excel
output_file = 'nba_games_this_season.xlsx'
games_df.to_excel(output_file, index=False, engine='openpyxl')