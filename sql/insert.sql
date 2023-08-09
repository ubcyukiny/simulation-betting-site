
-- Game
INSERT INTO Game (GameID, ScoreHome, ScoreAway, GameDate, HomeTeamID, AwayTeamID)
VALUES (0022200650, 132, 118, TO_DATE('2023-01-15', 'YYYY-MM-DD'), 1610612741, 1610612744);


-- Admin, NOT USED IN DEMO, NO WAY TO CREATE IN DEMO FOR NOW
INSERT INTO Admin (UserName, AccountBalance, Email) VALUES ('Admin1', 1050000, 'admin1@test.com');
INSERT INTO Admin (UserName, AccountBalance, Email) VALUES ('Admin2', 1040000, 'admin2@test.com');
INSERT INTO Admin (UserName, AccountBalance, Email) VALUES ('Admin3', 1030000, 'admin3@test.com');
INSERT INTO Admin (UserName, AccountBalance, Email) VALUES ('Admin4', 1020000, 'admin4@test.com');
INSERT INTO Admin (UserName, AccountBalance, Email) VALUES ('Admin5', 1010000, 'admin5@test.com');

-- Game, NO WAY TO CREATE IN DEMO FOR NOW
INSERT INTO Game VALUES (0022200650, 132, 118, TO_DATE('2023-01-15', 'YYYY-MM-DD'), 1610612741, 1610612744);
INSERT INTO Game VALUES (0022200606, 107, 99, TO_DATE('2023-01-09', 'YYYY-MM-DD'), 1610612738, 1610612741);
INSERT INTO Game VALUES (0022200623, 135, 129, TO_DATE('2023-01-11', 'YYYY-MM-DD'), 1610612763, 1610612759);
INSERT INTO Game VALUES (0022200608, 121, 113, TO_DATE('2023-01-09', 'YYYY-MM-DD'), 1610612763, 1610612759);
INSERT INTO game VALUES(0042200405,94,89,TO_DATE('2023-06-12', 'YYYY-MM-DD'),1610612743,1610612748);
INSERT INTO game VALUES(0042200404,95,108,TO_DATE('2023-06-09', 'YYYY-MM-DD'),1610612748,1610612743);
INSERT INTO game VALUES(0042200403,94,109,TO_DATE('2023-06-07', 'YYYY-MM-DD'),1610612748,1610612743);
INSERT INTO game VALUES(0042200402,108,111,TO_DATE('2023-06-04', 'YYYY-MM-DD'),1610612743,1610612748);
INSERT INTO game VALUES(0042200401,104,93,TO_DATE('2023-06-01', 'YYYY-MM-DD'),1610612743,1610612748);
INSERT INTO game VALUES(0042200307,84,103,TO_DATE('2023-05-29', 'YYYY-MM-DD'),1610612738,1610612748);
INSERT INTO game VALUES(0042200306,103,104,TO_DATE('2023-05-27', 'YYYY-MM-DD'),1610612748,1610612738);
INSERT INTO game VALUES(0042200305,110,97,TO_DATE('2023-05-25', 'YYYY-MM-DD'),1610612738,1610612748);
INSERT INTO game VALUES(0042200304,99,116,TO_DATE('2023-05-23', 'YYYY-MM-DD'),1610612748,1610612738);
INSERT INTO game VALUES(0042200314,111,113,TO_DATE('2023-05-22', 'YYYY-MM-DD'),1610612747,1610612743);
INSERT INTO game VALUES(0042200303,128,102,TO_DATE('2023-05-21', 'YYYY-MM-DD'),1610612748,1610612738);
INSERT INTO game VALUES(0042200313,108,119,TO_DATE('2023-05-20', 'YYYY-MM-DD'),1610612747,1610612743);
INSERT INTO game VALUES(0042200302,105,111,TO_DATE('2023-05-19', 'YYYY-MM-DD'),1610612738,1610612748);
INSERT INTO game VALUES(0042200312,108,103,TO_DATE('2023-05-18', 'YYYY-MM-DD'),1610612743,1610612747);
INSERT INTO game VALUES(0042200301,116,123,TO_DATE('2023-05-17', 'YYYY-MM-DD'),1610612738,1610612748);
INSERT INTO game VALUES(0042200311,132,126,TO_DATE('2023-05-16', 'YYYY-MM-DD'),1610612743,1610612747);
INSERT INTO game VALUES(0042200217,112,88,TO_DATE('2023-05-14', 'YYYY-MM-DD'),1610612738,1610612755);
INSERT INTO game VALUES(0042200206,96,92,TO_DATE('2023-05-12', 'YYYY-MM-DD'),1610612748,1610612752);
INSERT INTO game VALUES(0042200236,122,101,TO_DATE('2023-05-12', 'YYYY-MM-DD'),1610612747,1610612744);
INSERT INTO game VALUES(0042200226,100,125,TO_DATE('2023-05-11', 'YYYY-MM-DD'),1610612756,1610612743);
INSERT INTO game VALUES(0042200216,86,95,TO_DATE('2023-05-11', 'YYYY-MM-DD'),1610612755,1610612738);
INSERT INTO game VALUES(0042200235,121,106,TO_DATE('2023-05-10', 'YYYY-MM-DD'),1610612744,1610612747);
INSERT INTO game VALUES(0042200205,112,103,TO_DATE('2023-05-10', 'YYYY-MM-DD'),1610612752,1610612748);
INSERT INTO game VALUES(0042200225,118,102,TO_DATE('2023-05-09', 'YYYY-MM-DD'),1610612743,1610612756);
INSERT INTO game VALUES(0042200215,103,115,TO_DATE('2023-05-09', 'YYYY-MM-DD'),1610612738,1610612755);
INSERT INTO game VALUES(0042200234,104,101,TO_DATE('2023-05-08', 'YYYY-MM-DD'),1610612747,1610612744);
INSERT INTO game VALUES(0042200204,109,101,TO_DATE('2023-05-08', 'YYYY-MM-DD'),1610612748,1610612752);
INSERT INTO game VALUES(0042200224,129,124,TO_DATE('2023-05-07', 'YYYY-MM-DD'),1610612756,1610612743);
INSERT INTO game VALUES(0042200214,116,115,TO_DATE('2023-05-07', 'YYYY-MM-DD'),1610612755,1610612738);

-- Bet
-- Bet: moneyLine
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (1, 'moneyLine', 'Ken', 0022200650);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (2, 'moneyLine', 'Ken', 0022200606);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (3, 'moneyLine', 'Ken', 0042200405);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (4, 'moneyLine', 'Kelvin', 0022200623);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (5, 'moneyLine', 'Marco', 0022200608);
-- bet: spread, NOT USED IN DEMO, NO WAY TO CREATE IN DEMO FOR NOW
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (6, 'spread', 'Andrew', 0022200650);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (7, 'spread', 'Marmot', 0022200606);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (8, 'spread', 'Andrew', 0042200405);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (9, 'spread', 'Marmot', 0022200623);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (10, 'spread', 'Andrew', 0022200608);
-- bet: overUnder, NOT USED IN DEMO, NO WAY TO CREATE IN DEMO FOR NOW
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (11, 'overUnder', 'Kelvin', 0042200217);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (12, 'overUnder', 'Kelvin', 0042200235);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (13, 'overUnder', 'Kelvin', 0042200214);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (14, 'overUnder', 'Marmot', 0042200205);
INSERT INTO Bet (BetID, BetType, UserName, GameID) VALUES (15, 'overUnder', 'Ken', 0042200236);

-- UserPlacesBet, have 1 example that 1 user placed on every available bets
-- , have examples of users who placed bets total > 500
-- have games where total amount is more than 2000
-- prediction string on spread and overUnder may not make sense, demo uses MoneyLine only
-- Ken placed on every bet
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 1, 100, 'Home', -110);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 2, 150, 'Away', 105);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 3, 200, 'Home', -120);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 4, 100, 'Home', -105);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 5, 150, 'Away', 110);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 6, 200, 'Home', -110);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 7, 100, 'Home', +100);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 8, 150, 'Away', -110);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 9, 200, 'Home', -110;
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 10, 350, 'Home', +100);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 11, 200, 'Over', -110);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 12, 100, 'Over', +100);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 13, 150, 'Under', -110);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 14, 200, 'Under', +100);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Ken', 15, 350, 'Over', -110);
-- Other user placing large bets (total > 500)
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Marco', 5, 600, 'Home', -120);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Marco', 6, 400, 'Away', +120);
-- have games where total amount is more than 2000
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Marmot', 3, 1900, 'Home', +105);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Kelvin', 4, 2900, 'Away', -105);
-- More examples for spread and overUnder
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Andrew', 6, 100, 'Home', +110);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Marmot', 7, 200, 'Away', -110);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Andrew', 8, 300, 'Home', +100);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Marmot', 9, 400, 'Away', -100);
INSERT INTO UserPlacesBet (UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES ('Andrew', 10, 500, 'Over', +100);

-- PotentialPayout, NOT USED IN DEMO
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (100, -110, 191);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (150, 105, 308);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (200, -120, 367);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (100, -105, 195);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (150, 110, 315);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (200, -110, 382);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (100, 100, 200);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (150, -110, 286);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (200, -110, 382);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (350, 100, 700);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (200, -110, 382);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (100, 100, 200);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (150, -110, 286);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (200, 100, 400);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (350, -110, 668);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (600, -120, 1100);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (400, 120, 880);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (1900, 105, 3895);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (2900, -105, 5524);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (100, 110, 210);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (200, -110, 382);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (300, 100, 600);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (400, -100, 800);
INSERT INTO PotentialPayout (BetAmount, InitialOdds, PotentialPayout) VALUES (500, 100, 1000);

-- certifies, NOT USED IN DEMO
INSERT INTO Certifies (UserName, BetID, AdminVig) VALUES ('Admin1', 6, 0.05);
INSERT INTO Certifies (UserName, BetID, AdminVig) VALUES ('Admin2', 7, 0.04);
INSERT INTO Certifies (UserName, BetID, AdminVig) VALUES ('Admin1', 8, 0.05);
INSERT INTO Certifies (UserName, BetID, AdminVig) VALUES ('Admin3', 9, 0.06);
INSERT INTO Certifies (UserName, BetID, AdminVig) VALUES ('Admin2', 10, 0.04);

-- team
INSERT INTO team VALUES('1610612737','Atlanta Hawks','Atlanta');
INSERT INTO team VALUES('1610612738','Boston Celtics','Boston');
INSERT INTO team VALUES('1610612739','Cleveland Cavaliers','Cleveland');
INSERT INTO team VALUES('1610612740','New Orleans Pelicans','New Orleans');
INSERT INTO team VALUES('1610612741','Chicago Bulls','Chicago');
INSERT INTO team VALUES('1610612742','Dallas Mavericks','Dallas');
INSERT INTO team VALUES('1610612743','Denver Nuggets','Denver');
INSERT INTO team VALUES('1610612744','Golden State Warriors','Golden State');
INSERT INTO team VALUES('1610612745','Houston Rockets','Houston');
INSERT INTO team VALUES('1610612746','Los Angeles Clippers','Los Angeles');
INSERT INTO team VALUES('1610612747','Los Angeles Lakers','Los Angeles');
INSERT INTO team VALUES('1610612748','Miami Heat','Miami');
INSERT INTO team VALUES('1610612749','Milwaukee Bucks','Milwaukee');
INSERT INTO team VALUES('1610612750','Minnesota Timberwolves','Minnesota');
INSERT INTO team VALUES('1610612751','Brooklyn Nets','Brooklyn');
INSERT INTO team VALUES('1610612752','New York Knicks','New York');
INSERT INTO team VALUES('1610612753','Orlando Magic','Orlando');
INSERT INTO team VALUES('1610612754','Indiana Pacers','Indiana');
INSERT INTO team VALUES('1610612755','Philadelphia 76ers','Philadelphia');
INSERT INTO team VALUES('1610612756','Phoenix Suns','Phoenix');
INSERT INTO team VALUES('1610612757','Portland Trail Blazers','Portland');
INSERT INTO team VALUES('1610612758','Sacramento Kings','Sacramento');
INSERT INTO team VALUES('1610612759','San Antonio Spurs','San Antonio');
INSERT INTO team VALUES('1610612760','Oklahoma City Thunder','Oklahoma City');
INSERT INTO team VALUES('1610612761','Toronto Raptors','Toronto');
INSERT INTO team VALUES('1610612762','Utah Jazz','Utah');
INSERT INTO team VALUES('1610612763','Memphis Grizzlies','Memphis');
INSERT INTO team VALUES('1610612764','Washington Wizards','Washington');
INSERT INTO team VALUES('1610612765','Detroit Pistons','Detroit');
INSERT INTO team VALUES('1610612766','Charlotte Hornets','Charlotte');

-- TeamAbbreviation, NOT USED IN DEMO, NO WAY TO CREATE IN DEMO FOR NOW
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Atlanta Hawks', 'ATL');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Boston Celtics', 'BOS');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Cleveland Cavaliers', 'CLE');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('New Orleans Pelicans', 'NOP');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Chicago Bulls', 'CHI');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Dallas Mavericks', 'DAL');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Denver Nuggets', 'DEN');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Golden State Warriors', 'GSW');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Houston Rockets', 'HOU');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Los Angeles Clippers', 'LAC');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Los Angeles Lakers', 'LAL');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Miami Heat', 'MIA');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Milwaukee Bucks', 'MIL');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Minnesota Timberwolves', 'MIN');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Brooklyn Nets', 'BKN');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('New York Knicks', 'NYK');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Orlando Magic', 'ORL');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Indiana Pacers', 'IND');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Philadelphia 76ers', 'PHI');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Phoenix Suns', 'PHX');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Portland Trail Blazers', 'POR');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Sacramento Kings', 'SAC');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('San Antonio Spurs', 'SAS');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Oklahoma City Thunder', 'OKC');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Toronto Raptors', 'TOR');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Utah Jazz', 'UTA');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Memphis Grizzlies', 'MEM');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Washington Wizards', 'WAS');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Detroit Pistons', 'DET');
INSERT INTO TeamAbbreviation (FullName, Abbreviation) VALUES ('Charlotte Hornets', 'CHA');

-- Player, NOT USED IN DEMO, NO WAY TO CREATE IN DEMO FOR NOW
INSERT INTO Player (PlayerID, TeamID, InjuryStatus) VALUES (201939, 1610612744, 'Out');
INSERT INTO Player (PlayerID, TeamID, InjuryStatus) VALUES (202710, 1610612748, 'DayToDay');
INSERT INTO Player (PlayerID, TeamID, InjuryStatus) VALUES (1627783, 1610612738, 'Questionable');
INSERT INTO Player (PlayerID, TeamID, InjuryStatus) VALUES (1628369, 1610612761, 'Probable');
INSERT INTO Player (PlayerID, TeamID, InjuryStatus) VALUES (203897, 1610612741, 'Doubtful');

-- TeamPlays, NOT USED IN DEMO, NO WAY TO CREATE IN DEMO FOR NOW
INSERT INTO TeamPlays (GameID, TeamID) VALUES (0022200650, 1610612744);
INSERT INTO TeamPlays (GameID, TeamID) VALUES (0022200606, 1610612741);
INSERT INTO TeamPlays (GameID, TeamID) VALUES (0022200650, 1610612741);
INSERT INTO TeamPlays (GameID, TeamID) VALUES (0022200606, 1610612761);
INSERT INTO TeamPlays (GameID, TeamID) VALUES (0042200403, 1610612748);
INSERT INTO TeamPlays (GameID, TeamID) VALUES (0042200403, 1610612743);

-- PlayerPlays, NOT USED IN DEMO, NO WAY TO CREATE IN DEMO FOR NOW
INSERT INTO PlayerPlays (PlayerID, GameID, Minutes, Points, Assists, Rebounds) VALUES (201939, 0022200650, 28, 11, 3, 5);
INSERT INTO PlayerPlays (PlayerID, GameID, Minutes, Points, Assists, Rebounds) VALUES (202710, 0022200650, 30, 8, 7, 4);
INSERT INTO PlayerPlays (PlayerID, GameID, Minutes, Points, Assists, Rebounds) VALUES (1627783, 0022200650, 26, 9, 1, 6);
INSERT INTO PlayerPlays (PlayerID, GameID, Minutes, Points, Assists, Rebounds) VALUES (1628369, 0022200606, 22, 6, 2, 7);
INSERT INTO PlayerPlays (PlayerID, GameID, Minutes, Points, Assists, Rebounds) VALUES (203897, 0022200606, 38, 19, 3, 5);

-- Spread, NOT USED IN DEMO, NO WAY TO CREATE IN DEMO FOR NOW
INSERT INTO Spread (BetID, GameID, UserName, Status, TotalPool, TotalVig, ScoreDifference, Odds) VALUES (6, 0022200650, 'Andrew', 'Open', 1000, 100, 5.5, -110);
INSERT INTO Spread (BetID, GameID, UserName, Status, TotalPool, TotalVig, ScoreDifference, Odds) VALUES (7, 0022200606, 'Marmot', 'Closed', 2000, 150, 3.5, +100);
INSERT INTO Spread (BetID, GameID, UserName, Status, TotalPool, TotalVig, ScoreDifference, Odds) VALUES (8, 0042200405, 'Andrew', 'Settled', 1500, 200, 7.5, -110);
INSERT INTO Spread (BetID, GameID, UserName, Status, TotalPool, TotalVig, ScoreDifference, Odds) VALUES (9, 0022200623, 'Marmot', 'Canceled', 3500, 250, 9.5, -110);
INSERT INTO Spread (BetID, GameID, UserName, Status, TotalPool, TotalVig, ScoreDifference, Odds) VALUES (10, 0022200608, 'Andrew', 'Open', 5000, 300, 6.5, +100);

-- OverUnder, NOT USED IN DEMO, NO WAY TO CREATE IN DEMO FOR NOW
INSERT INTO OverUnder (BetID, GameID, UserName, Status, TotalPool, TotalVig, TotalScore, Odds) VALUES (11, 0042200217, 'Kelvin', 'Open', 1000, 100, 210, -110);
INSERT INTO OverUnder (BetID, GameID, UserName, Status, TotalPool, TotalVig, TotalScore, Odds) VALUES (12, 0042200235, 'Kelvin', 'Closed', 2500, 200, 215, +100);
INSERT INTO OverUnder (BetID, GameID, UserName, Status, TotalPool, TotalVig, TotalScore, Odds) VALUES (13, 0042200214, 'Kelvin', 'Settled', 1800, 150, 205, -110);
INSERT INTO OverUnder (BetID, GameID, UserName, Status, TotalPool, TotalVig, TotalScore, Odds) VALUES (14, 0042200205, 'Marmot', 'Canceled', 3000, 250, 220, +100);
INSERT INTO OverUnder (BetID, GameID, UserName, Status, TotalPool, TotalVig, TotalScore, Odds) VALUES (15, 0042200236, 'Ken', 'Open', 4000, 300, 200, -110);

-- MoneyLine
INSERT INTO MoneyLine (BetID, GameID, UserName, HomeTeam, AwayTeam, HomeTeamOdds, AwayTeamOdds) VALUES (1, 0022200650, 'Ken', 'Chicago Bulls', 'Golden State Warriors', -110, +110);
INSERT INTO MoneyLine (BetID, GameID, UserName, HomeTeam, AwayTeam, HomeTeamOdds, AwayTeamOdds) VALUES (2, 0022200606, 'Ken', 'Boston Celtics', 'Chicago Bulls', -115, +105);
INSERT INTO MoneyLine (BetID, GameID, UserName, HomeTeam, AwayTeam, HomeTeamOdds, AwayTeamOdds) VALUES (3, 0042200405, 'Ken', 'Denver Nuggets', 'Miami Heat', -120, +100);
INSERT INTO MoneyLine (BetID, GameID, UserName, HomeTeam, AwayTeam, HomeTeamOdds, AwayTeamOdds) VALUES (4, 0022200623, 'Kelvin', 'Memphis Grizzlies', 'San Antonio Spurs', -105, -115);
INSERT INTO MoneyLine (BetID, GameID, UserName, HomeTeam, AwayTeam, HomeTeamOdds, AwayTeamOdds) VALUES (5, 0022200608, 'Marco', 'Memphis Grizzlies', 'San Antonio Spurs', -110, +110);

