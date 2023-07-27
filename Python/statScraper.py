import pandas as pd
from nba_api.stats.static import teams

team_info = teams.get_teams()
team_df = pd.DataFrame(team_info)

output_file = 'nba_teams_info.xlsx'
team_df.to_excel(output_file, index=False, engine='openpyxl')