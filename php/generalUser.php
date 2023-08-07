<?php
include 'functions.php';
session_start();
if (isset($_SESSION['userName'])) {
    echo "Welcome, " . $_SESSION['userName'];
    echo "<br>";
    echo "CurrentAccountBalance: " . $_SESSION['accountBalance'];
    echo "<br>";
    echo "Email: " . $_SESSION['email'];
    echo '<form action="" method="post">';
    echo '    <input type="submit" value="Logout" name="Logout">';
    echo '</form>';
} else {
    echo "Please log in.";
    // display link to main.php
    echo '<a href="main.php">Back to Login Page</a>';
    echo "<br>";
}
?>

<html>
<head>
    <title>General User</title>
</head>
<body>
<hr/>
<form method="GET" action="generalUser.php"> <!--refresh page when submitted-->
    <h1>Display games (for createBet)</h1>
    <p><input type="submit" value="Display Games" name="DisplayGames"></p>
</form>
<form method="GET" action="generalUser.php"> <!--refresh page when submitted-->
    <p><input type="submit" value="Filter Games" name="FilterGames"></p>
    <label for="TeamNameFilter">Enter Team Name:</label>
    <p><input type="text" id="TeamNameFilter" name = "TeamNameFilter" required></p>
</form>
<hr/>
<h1>Display current moneyline bets:</h1>
<form action="generalUser.php" method="post">
    <Label for="">Show:</label><br>
    <select id="filterMoney" name = "filterMoney[]" size="7" multiple ><br>
        <option value="BETID">Bet ID</option>
        <option value="GAMEID">Game ID</option>
        <option value="USERNAME" selected>Username</option>
        <option value="HOMETEAM" selected>Home Team</option>
        <option value="AWAYTEAM" selected>Away Team</option>
        <option value="HOMETEAMODDS" selected>Home Team Odds</ption>
        <option value="AWAYTEAMODDS" selected>Away Team Odds</option>
    </select><br>
    <p><input type="submit" value="Display MoneyLine bets" name="DisplayAvailableBets"></p>
</form>
<hr/>
<h1>Place your bet here:</h1>
<h1>You cannot place the same bet twice</h1>
<form action="generalUser.php" method="post">
    <label for="betId">Bet ID:</label>
    <input type="number" id="betId" name="BetID" required><br>

    <label for="gameId">BetAmount</label>
    <input type="number" id="betAmount" name="BetAmount" required><br>

    <label for="prediction">Prediction: (Home/Away)</label>
    <select id="prediction" name="Prediction" required><br>
        <option value="Home">Home</option>
        <option value="Away">Away</option>
    </select><br>
    <p><input type="submit" value="Place bet" name="PlaceBet"></p>
</form>
<hr/>
<h1>Create your bet here:</h1>
<h1>Form for MoneyLine Bet</h1>
<form action="generalUser.php" method="post">
    <label for="betId">Bet ID:</label>
    <input type="number" id="betId" name="BetID" required><br>

    <label for="gameId">Game ID:</label>
    <input type="number" id="gameId" name="GameID" required><br>

    <label for="homeTeamOdds">Home Team Odds:</label>
    <input type="number" id="homeTeamOdds" name="HomeTeamOdds" required><br>

    <label for="awayTeamOdds">Away Team Odds:</label>
    <input type="number" id="awayTeamOdds" name="AwayTeamOdds" required><br>

    <input type="submit" value="Submit Bet" name="CreateNewMoneyLineBet">
</form>
<hr/>

<?php

function displayGames($args = null)
{
    if (connectToDB()) {
        if ($args == null) {
            $command = "SELECT G.GameID, THome.FullName AS HomeTeam, G.ScoreHome, G.ScoreAway, TAway.FullName AS AwayTeam, G.GameDate
            FROM Game G
            INNER JOIN Team THome ON G.HomeTeamID = THome.TeamID
            INNER JOIN Team TAway ON G.AwayTeamID = TAway.TeamID";
            $colNames = ["Game ID", "Home", "", "", "Away", "Date"];
            $result = executePlainSQL($command);
        } else {
            $tuple = array(
                ":bind1" => $args,
            );
            $allTuples = array($tuple);
            $command = "SELECT G.GameID, THome.FullName AS HomeTeam, G.ScoreHome, G.ScoreAway, TAway.FullName AS AwayTeam, G.GameDate
            FROM Game G
            INNER JOIN Team THome ON G.HomeTeamID = THome.TeamID
            INNER JOIN Team TAway ON G.AwayTeamID = TAway.TeamID
            WHERE THome.FullName IN (:bind1) OR TAway.FullName IN (:bind1)";
            $colNames = ["Game ID", "Home", "", "", "Away", "Date"];
            $result = executeBoundSQL($command, $allTuples);
        }
        
        printTable($result, $colNames);
        disconnectFromDB();
    }
}


function displayMoneyLineBets()
{
    if (connectToDB()) {
        $filter = "";
        foreach ( $_POST['filterMoney'] as $term ) { 
            $filter .= $term . ", "; 
          } 
        echo $filter;
        $command = "SELECT ". $filter ."Status FROM MoneyLine";
        printTable(executePlainSQL($command), $cols);
        disconnectFromDB();
    }
}


function gameExists($gameID) {
    if (connectToDB()) {
        return (oci_fetch_array(executePlainSQL('select 1 from Game where gameID =' . $gameID), OCI_BOTH) !== false);
    } else {
        return false;
    }
}

function handleCreateMoneyLineBetRequest()
{
    global $global_db_conn;
    if (connectToDB() && gameExists($_POST['GameID'])) {
        // insert to bet table and moneyline table
        // For Bet table
        // need to verify if gameID exists before adding it to bet table
        $tuple2 = array(
            ":bbind1" => $_POST['BetID'],
            ":bbind2" => $_POST['GameID'],
            ":bbind3" => 'MoneyLine',
            ":bbind4" => $_SESSION['userName']
        );
        $allTuples2 = array($tuple2);
        executeBoundSQL("insert into Bet(BetID, GameID, BetType, UserName) values(:bbind1, :bbind2, :bbind3, :bbind4)", $allTuples2);

        $_gameID = $_POST['GameID'];
        $_homeTeam = oci_fetch_array(executePlainSQL("
        select t.FullName 
        from Team t, Game g
        where t.teamID = g.homeTeamID and g.gameID =" . $_gameID
        ))[0];
        // fetch away team
        $_awayTeam = oci_fetch_array(executePlainSQL("
        select t.FullName 
        from Team t, Game g
        where t.teamID = g.AwayTeamID and g.gameID =" . $_gameID
        ))[0];

        // For MoneyLine table
        // set tuple array
        $tuple = array(
            ":bind1" => $_POST['BetID'],
            ":bind2" => $_POST['GameID'],
            ":bind3" => $_SESSION['userName'], // username from signin, BetStatus default 'open'
            ":bind4" => $_homeTeam,
            ":bind5" => $_awayTeam,
            ":bind6" => $_POST['HomeTeamOdds'],
            ":bind7" => $_POST['AwayTeamOdds'],
        );
        // set allTuples array
        $allTuples = array($tuple);
        executeBoundSQL("insert into MoneyLine(BetID,GameID,UserName,HomeTeam,AwayTeam,HomeTeamOdds,AwayTeamOdds)
        values(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)", $allTuples);
        oci_commit($global_db_conn);
        disconnectfromDB();
    } else {
        echo "Bet creation failed, you are not logged in or GameID not found";
    }
}

function handlePlaceBetRequest()
{
    global $global_db_conn;
    if (connectToDB()) {
        // check user has enough balance, update session and generalUser table if yes
        if ($_SESSION['accountBalance'] >= $_POST['BetAmount']) {
            $_SESSION['accountBalance'] -= $_POST['BetAmount'];
            executePlainSQL("update generalUser set accountBalance =" . $_SESSION['accountBalance'] . " where username ='" .  $_SESSION['userName'] . "'");
        } else {
            echo "Not enough balance";
            disconnectFromDB();
            return false;
        }

        // find odds based on prediction and betID
        if ($_POST['Prediction'] == 'Home') {
            echo "Home selected";
            $_odds = oci_fetch_array(executePlainSQL("select HomeTeamOdds from moneyline where betID =" . $_POST['BetID']))[0];
        } else {
            echo "Away selected";
            $_odds = oci_fetch_array(executePlainSQL("select AwayTeamOdds from moneyline where betID =" . $_POST['BetID']))[0];
        }

        $tuple = array(
            ":bind1" => $_SESSION['userName'],
            ":bind2" => $_POST['BetID'],
            ":bind3" => $_POST['BetAmount'],
            ":bind4" => $_POST['Prediction'],
            ":bind5" => $_odds
        );
        $allTuples = array($tuple);
        executeBoundSQL("insert into UserPlacesBet(UserName, BetID, BetAmount, Prediction, CalculatedOdds) values(:bind1, :bind2, :bind3, :bind4, :bind5)", $allTuples);
        oci_commit($global_db_conn);
        disconnectFromDB();
    }
}

// if logout is pressed
if (isset($_POST['Logout'])) {
    session_unset();
    session_destroy();
    header("Location: main.php");
}

// if displayGame is pressed
if (isset($_GET['DisplayGames'])) {
    displayGames();
}

if (isset($_GET['FilterGames'])) {
    displayGames($_GET ['TeamNameFilter']);
}

// if submitBet is pressed
if (isset($_POST['CreateNewMoneyLineBet'])) {
    handleCreateMoneyLineBetRequest();
}

// if displayBet is pressed
if (isset($_POST['DisplayAvailableBets'])) {
    displayMoneyLineBets();
}

if (isset($_POST['PlaceBet'])) {
    handlePlaceBetRequest();
}
?>


</body>
</html>
