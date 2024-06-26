-- drop table with foreign keys and references first, total 15 tables
DROP TABLE UserPlacesBet;
DROP TABLE PotentialPayout;
DROP TABLE Spread;
DROP TABLE OverUnder;
DROP TABLE MoneyLine;
DROP TABLE PlayerPlays;
DROP TABLE TeamPlays;
DROP TABLE Certifies;

DROP TABLE Bet;
DROP TABLE TeamAbbreviation;
DROP TABLE Player;
DROP TABLE Game;
DROP TABLE Team;
DROP TABLE GeneralUser;
DROP TABLE Admin;


CREATE TABLE GeneralUser
(
    UserName       VARCHAR(20) PRIMARY KEY,
    AccountBalance INT DEFAULT 10000   NOT NULL,
    Email          VARCHAR(40) UNIQUE NOT NULL
);


CREATE TABLE Admin
(
    UserName       VARCHAR(20) PRIMARY KEY,
    AccountBalance INT DEFAULT 1000000 NOT NULL,
    Email          VARCHAR(40) UNIQUE  NOT NULL
);

CREATE TABLE Team
(
    TeamID   INT PRIMARY KEY,
    FullName VARCHAR(50),
    City     VARCHAR(30)
);

CREATE TABLE Player
(
    PlayerID     INT PRIMARY KEY,
    TeamID       INT NOT NULL,
    InjuryStatus VARCHAR(50),
    FOREIGN KEY (TeamID) REFERENCES Team (TeamID)
);

CREATE TABLE Game
(
    GameID     INT PRIMARY KEY,
    ScoreHome  INT,
    ScoreAway  INT,
    GameDate   DATE,
    HomeTeamID INT,
    AwayTeamID INT
);

--  made changes here, now store userName who created this bet, for on cascade delete, store gameID as FK
CREATE TABLE Bet
(
    BetID    INT PRIMARY KEY,
    BetType  VARCHAR(20) NOT NULL,
    UserName VARCHAR(20) NOT NULL,
    GameID INT NOT NULL,
    FOREIGN KEY (GameID) REFERENCES Game (GameID),
    FOREIGN KEY (UserName) REFERENCES GeneralUser (UserName) ON DELETE CASCADE
);


-- when the user place a bet, and that bet is deleted, the transaction will be deleted as well
-- User can only place a bet ONCE
CREATE TABLE UserPlacesBet
(
    UserName       VARCHAR(20),
    BetID          INT,
    BetAmount      INT,
    Prediction     VARCHAR(100),
    CalculatedOdds FLOAT,
    PRIMARY KEY (Username, BetID),
    FOREIGN KEY (Username) REFERENCES GeneralUser (UserName) ON DELETE CASCADE,
    FOREIGN KEY (BetID) REFERENCES Bet (BetID) ON DELETE CASCADE
);

-- changed Odds to int, potentialPayout to INT
CREATE TABLE PotentialPayout
(
    BetAmount       INT NOT NULL,
    InitialOdds     INT NOT NULL,
    PotentialPayout INT NOT NULL
);

--Float is in %
CREATE TABLE Certifies
(
    UserName VARCHAR(20),
    BetID    INT,
    AdminVig FLOAT NOT NULL, 
    PRIMARY KEY (UserName, BetID),
    FOREIGN KEY (UserName) REFERENCES Admin (UserName),
    FOREIGN KEY (BetID) REFERENCES Bet (BetID)
);

CREATE TABLE TeamAbbreviation
(
    FullName     VARCHAR(30),
    Abbreviation VARCHAR(3)
);

CREATE TABLE TeamPlays
(
    GameID INT,
    TeamID INT,
    PRIMARY KEY (GameID, TeamID),
    FOREIGN KEY (GameID) REFERENCES Game (GameID),
    FOREIGN KEY (TeamID) REFERENCES Team (TeamID)
);

CREATE TABLE PlayerPlays
(
    PlayerID INT,
    GameID   INT,
    Minutes  INT,
    Points   INT,
    Assists  INT,
    Rebounds INT,
    PRIMARY KEY (PlayerID, GameID),
    FOREIGN KEY (PlayerID) REFERENCES Player (PlayerID),
    FOREIGN KEY (GameID) REFERENCES Game (GameID)
);

--  made changes here, betId references Bet
CREATE TABLE Spread
(
    BetID           INT PRIMARY KEY,
    GameID          INT         NOT NULL,
    UserName        VARCHAR(20) NOT NULL,
    Status          VARCHAR(40) DEFAULT 'Open',
    TotalPool       INT,
    TotalVig        INT,
    ScoreDifference FLOAT,
    Odds            FLOAT,
    FOREIGN KEY (BetID) REFERENCES Bet (BetID),
    FOREIGN KEY (GameID) REFERENCES Game (GameID),
    FOREIGN KEY (UserName) REFERENCES GeneralUser (UserName)
);

--  made changes here, betId references Bet
CREATE TABLE OverUnder
(
    BetID      INT PRIMARY KEY,
    GameID     INT         NOT NULL,
    UserName   VARCHAR(20) NOT NULL,
    Status     VARCHAR(40) DEFAULT 'Open',
    TotalPool  INT,
    TotalVig   INT,
    TotalScore INT,
    Odds       FLOAT,
    FOREIGN KEY (BetID) REFERENCES Bet (BetID),
    FOREIGN KEY (GameID) REFERENCES Game (GameID),
    FOREIGN KEY (UserName) REFERENCES GeneralUser (UserName)
);

--  made changes here, betId references Bet
CREATE TABLE MoneyLine
(
    BetID        INT PRIMARY KEY,
    GameID       INT         NOT NULL,
    UserName     VARCHAR(20) NOT NULL,
    Status       VARCHAR(40) DEFAULT 'Open',
    HomeTeam     VARCHAR(40) NOT NULL,
    AwayTeam     VARCHAR(40) NOT NULL,
    HomeTeamOdds INT         NOT NULL,
    AwayTeamOdds INT         NOT NULL,
    FOREIGN KEY (BetID) REFERENCES Bet (BetID),
    FOREIGN KEY (GameID) REFERENCES Game (GameID),
    FOREIGN KEY (UserName) REFERENCES GeneralUser (UserName) ON DELETE CASCADE
);

