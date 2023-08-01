-- drop table with foreign keys and references first
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


CREATE TABLE GeneralUser (
	UserName VARCHAR(20) PRIMARY KEY,
	AccountBalance INT DEFAULT 1000 NOT NULL, 
	Email VARCHAR(40) UNIQUE NOT NULL
);


CREATE TABLE Admin (
	UserName VARCHAR(20) PRIMARY KEY,
	AccountBalance INT DEFAULT 1000000 NOT NULL, 
	Email VARCHAR(40) UNIQUE NOT NULL
);

CREATE TABLE Bet (
	BetID INT PRIMARY KEY,
	BetType VARCHAR(20) NOT NULL
);


CREATE TABLE UserPlacesBet (
	UserName VARCHAR(20),
	BetID INT,
	BetAmount INT,
	Prediction VARCHAR(100),
	CalculatedOdds FLOAT,
	PRIMARY KEY (Username, BetID),
	FOREIGN KEY (Username) REFERENCES GeneralUser (UserName),
	FOREIGN KEY (BetID) REFERENCES Bet (BetID)
);

CREATE TABLE PotentialPayout (
	BetAmount INT NOT NULL,
	InitialOdds FLOAT NOT NULL,
	PotentialPayout FLOAT NOT NULL
);

CREATE TABLE Certifies (
	UserName VARCHAR(20),
	BetID INT,
	AdminVig FLOAT NOT NULL,
	PRIMARY KEY (UserName, BetID),
	FOREIGN KEY (UserName) REFERENCES Admin (UserName),
	FOREIGN KEY (BetID) REFERENCES Bet (BetID)
);

CREATE TABLE Team (
	TeamID INT PRIMARY KEY,
	FullName VARCHAR(50),
	City VARCHAR(30)
);

CREATE TABLE TeamAbbreviation(
	FullName VARCHAR(30),
	Abbreviation VARCHAR(3)
);

CREATE TABLE Player (
	PlayerID INT PRIMARY KEY,
	TeamID INT NOT NULL,
	InjuryStatus VARCHAR(50),
	FOREIGN KEY (TeamID) REFERENCES Team (TeamID)
);


CREATE TABLE Game (
	GameID INT PRIMARY KEY,
	ScoreHome INT,
	ScoreAway INT,
	GameDate DATE,
	HomeTeamID INT,
	AwayTeamID INT
);

CREATE TABLE TeamPlays (
	GameID INT,
	TeamID INT,
	PRIMARY KEY (GameID, TeamID),
	FOREIGN KEY (GameID) REFERENCES Game (GameID),
	FOREIGN KEY (TeamID) REFERENCES Team (TeamID)
);

CREATE TABLE PlayerPlays (
	PlayerID INT,
	GameID INT,
	Minutes INT,
	Points INT,
	Assists INT,
	Rebounds INT,
	PRIMARY KEY (PlayerID, GameID),
	FOREIGN KEY (PlayerID) REFERENCES Player (PlayerID),
	FOREIGN KEY (GameID) REFERENCES Game (GameID)
);


CREATE TABLE Spread (
	BetID INT PRIMARY KEY,
	GameID INT NOT NULL,
	UserName VARCHAR(20) NOT NULL,
	Status VARCHAR(40),
	TotalPool INT,
	TotalVig INT,
	ScoreDifference FLOAT,
	Odds FLOAT,
	FOREIGN KEY (GameID) REFERENCES Game (GameID),
	FOREIGN KEY (UserName) REFERENCES GeneralUser(UserName)
);

CREATE TABLE OverUnder (
	BetID INT PRIMARY KEY,
	GameID INT NOT NULL,
	UserName VARCHAR(20) NOT NULL,
	Status VARCHAR(40),
	TotalPool INT,
	TotalVig INT,
	TotalScore INT,
	Odds FLOAT,
	FOREIGN KEY (GameID) REFERENCES Game (GameID),
	FOREIGN KEY (UserName) REFERENCES GeneralUser(UserName)
);

CREATE TABLE MoneyLine (
	BetID INT PRIMARY KEY,
	GameID INT NOT NULL,
	UserName VARCHAR(20) NOT NULL,
	Status VARCHAR(40),
	TotalPool INT,
	TotalVig INT,
	Odds FLOAT,
	FOREIGN KEY (GameID) REFERENCES Game (GameID),
	FOREIGN KEY (UserName) REFERENCES GeneralUser(UserName)
);

