-- GeneralUser
INSERT INTO GeneralUser ("bobSmith", 1000, "bobSmith@gmail.com")
INSERT INTO GeneralUser ("janeSmith", 2000,"janeSmith@gmail.com")
INSERT INTO GeneralUser ("kevinMalone", 3000, "kevinMalone@gmail.com")
INSERT INTO GeneralUser ("mikeScott", 3500, "bestBossEver@gmail.com")
INSERT INTO GeneralUser ("dwightSchrute", 5000, "iLoveBeets@hotmail.com")
INSERT INTO GeneralUser ("darrylBball", 4000, "Bball4Life@yahoo.com")
INSERT INTO GeneralUser ("jimpBball", 4500, "JimBball@gmail.com")
INSERT INTO GeneralUser ("pamBball", 300, "pamBball@hotmail.com")

-- Admin
INSERT INTO GeneralUser ("admin1", 10000, "admin1@gmail.com")
INSERT INTO GeneralUser ("admin2", 20000, "admin2@gmail.com")
INSERT INTO GeneralUser ("admin3", 10000, "admin3@gmail.com")
INSERT INTO GeneralUser ("admin4", 20000, "admin4@gmail.com")
INSERT INTO GeneralUser ("admin5", 10000, "admin5@gmail.com")
INSERT INTO GeneralUser ("admin6", 20000, "admin6@gmail.com")
INSERT INTO GeneralUser ("admin7", 10000, "admin7@gmail.com")
INSERT INTO GeneralUser ("admin8", 20000, "admin8@gmail.com")

-- Bet
-- BetID
-- begins with '1' = Spread
-- begins with '2' = OverUnder
-- begins with '3' = MoneyLine
INSERT INTO Bet (1001, "Spread", "bobSmith", 0022200650)
INSERT INTO Bet (1002, "Spread", "janeSmith", 0022200606)
INSERT INTO Bet (2001, "OverUnder", "kevinMalone", 0022200623)
INSERT INTO Bet (2002, "OverUnder", "mikeScott", 0022200623)
INSERT INTO Bet (3001, "MoneyLine", "dwightSchrute", 0022200608)
INSERT INTO Bet (3002, "MoneyLine", "darrylBball", 0022200608)

-- UserPlacesBet
-- bobSmith is betting that Chicago Bulls (homeTeam, favorite) will win by >=15 points for game 0022200650
INSERT INTO UserPlacesBet ('bobSmith', 1001, 100, "Chicago Bulls -15")
-- janeSmith is betting that Chicago Bulls (awayTeam, not favorite) will lose by <=10 points for game 0022200606
INSERT INTO UserPlacesBet ('janeSmith', 1002, 200, "Boston Celtics +10")
-- kevinMalone is betting that the total game points will be >250 for game 0022200623
INSERT INTO UserPlacesBet ('kevinMalone', 2001, 400, "Over 250")
-- kevinMalone is betting that the total game points will be <250 for game 0022200623
INSERT INTO UserPlacesBet ('mikeScott', 2002, 100, "Under 250")
-- dwightSchrute is betting that the Memphis grizzlies will win for game 0022200608, needs to bet $250 to win $100
INSERT INTO UserPlacesBet ('dwightSchrute', 3001, 250, "Memphis Grizzlies -250")
-- dwightSchrute is betting that the Memphis grizzlies will win for game 0022200608, needs to bet $100 to win $300
INSERT INTO UserPlacesBet ('darrylBball', 3002, 100, "San Antonio Spurs +300")

-- PotentialPayout


--Certifies
-- AdminVig is in %
INSERT INTO Certifies ("admin1", 1001, 5)
INSERT INTO Certifies ("admin2", 1002, 10)
INSERT INTO Certifies ("admin3", 2001, 5)
INSERT INTO Certifies ("admin4", 2002, 10.5)
INSERT INTO Certifies ("admin5", 3001, 15)
INSERT INTO Certifies ("admin6", 3002, 10)

-- Spread
-- Status is one of either 'Not Started', 'Live' or 'Game Over'
-- TotalVig is in $
INSERT INTO Spread (1001, 0022200650, 'bobSmith', 'Game Over', 100, 5, 14, -15)
INSERT INTO Spread (1002, 0022200606, 'janeSmith', 'Game Over', 200, 10, 8, +10)

-- OverUnder
INSERT INTO OverUnder (2001, 0022200623, 'kevinMalone', 'Game Over', 500, 5, 264, 250)
INSERT INTO OverUnder (2002, 0022200623, 'mikeScott', 'Game Over', 500, 10.5, 264, 250)

-- MoneyLine
INSERT INTO MoneyLine (3001, 0022200608, 'dwightSchrute', 'Game Over', 'Memphis Grizzlies', 'San Antonio Spurs', 110, 130)
INSERT INTO MoneyLine (3002, 0022200608, 'darrylBball', 'Game Over', 'Memphis Grizzlies', 'San Antonio Spurs', 110, 130)

-- Playerplays

-- Game
INSERT INTO Game (GameID, ScoreHome, ScoreAway, GameDate, HomeTeamID, AwayTeamID)
VALUES (0022200650, 132, 118, TO_DATE('2023-01-15', 'YYYY-MM-DD'), 1610612741, 1610612744);

INSERT INTO Game (GameID, ScoreHome, ScoreAway, GameDate, HomeTeamID, AwayTeamID)
VALUES (0022200606, 107, 99, TO_DATE('2023-01-09', 'YYYY-MM-DD'), 1610612738, 1610612741);

INSERT INTO Game (GameID, ScoreHome, ScoreAway, GameDate, HomeTeamID, AwayTeamID)
VALUES (0022200623, 135, 129, TO_DATE('2023-01-11', 'YYYY-MM-DD'), 1610612763, 1610612759);

INSERT INTO Game (GameID, ScoreHome, ScoreAway, GameDate, HomeTeamID, AwayTeamID)
VALUES (0022200608, 121, 113, TO_DATE('2023-01-09', 'YYYY-MM-DD'), 1610612763, 1610612759);

INSERT INTO Game (GameID, ScoreHome, ScoreAway, GameDate, HomeTeamID, AwayTeamID)
VALUES (2022200086, 104, 112, TO_DATE('2023-01-09', 'YYYY-MM-DD'), 1612709914, 1612709904);

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