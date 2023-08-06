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
<hr/>
<h1>Display current moneyline bets:</h1>
<form action="generalUser.php" method="get">
    <p><input type="submit" value="Display MoneyLine bets" name="DisplayAvailableBets"></p>
</form>
<hr/>
<h1>Place your bet here:</h1>
(normally it would check if userBalance is enough for betAmount, then update user's accountBalance, ignore for demo)<br>
(also normally it would also get CalculatedOdds based on your prediction and Bet, and insert to
PotentialPayout table, but
ignore for demo)<br>
(Also we prob want a dropdown for user to select Home/Away for prediction)<br>
<form action="generalUser.php" method="post">
    <label for="betId">Bet ID:</label>
    <input type="number" id="betId" name="BetID" required><br>

    <label for="gameId">BetAmount</label>
    <input type="number" id="betAmount" name="BetAmount" required><br>

    <label for="prediction">Prediction: (Home/Away)</label>
    <input type="text" id="prediction" name="Prediction" maxlength="100" required><br>

    <label for="calculatedOdds">Away Team:</label>
    <input type="number" id="calculatedOdds" name="CalculatedOdds" required><br>
    <p><input type="submit" value="Place bet" name="PlaceBet"></p>
</form>
<hr/>
<h1>Create your bet here:</h1>
<h1>Form for MoneyLine Bet</h1>
Ideally, user only provides gameId and php get homeTeam and awayTeam from Game and Team table, for demo, require user to
input
<form action="generalUser.php" method="post">
    <label for="betId">Bet ID:</label>
    <input type="number" id="betId" name="BetID" required><br>

    <label for="gameId">Game ID:</label>
    <input type="number" id="gameId" name="GameID" required><br>

    <label for="homeTeam">Home Team:</label>
    <input type="text" id="homeTeam" name="HomeTeam" maxlength="40" required><br>

    <label for="awayTeam">Away Team:</label>
    <input type="text" id="awayTeam" name="AwayTeam" maxlength="40" required><br>

    <label for="homeTeamOdds">Home Team Odds:</label>
    <input type="number" id="homeTeamOdds" name="HomeTeamOdds" required><br>

    <label for="awayTeamOdds">Away Team Odds:</label>
    <input type="number" id="awayTeamOdds" name="AwayTeamOdds" required><br>

    <input type="submit" value="Submit Bet" name="CreateNewMoneyLineBet">
</form>
<hr/>

<?php

function displayGames()
{
    if (connectToDB()) {
        $command = "SELECT G.GameID, THome.FullName AS HomeTeam, G.ScoreHome, G.ScoreAway, TAway.FullName AS AwayTeam, G.GameDate
        FROM Game G
        INNER JOIN Team THome ON G.HomeTeamID = THome.TeamID
        INNER JOIN Team TAway ON G.AwayTeamID = TAway.TeamID";
        $colNames = ["Game ID", "Home", "", "", "Away", "Date"];
        printTable(executePlainSQL($command), $colNames);
        disconnectFromDB();
    }
}

function displayMoneyLineBets()
{
    if (connectToDB()) {
        $command = "SELECT * FROM MoneyLine";
        $cols = ["Bet ID", "Game ID", "Created BY", "Status", "Home Team", "Away Team", "Odds-Home", "Odds-Away"];
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
            ":bbind2" => 'MoneyLine',
            ":bbind3" => $_SESSION['userName']
        );
        $allTuples2 = array($tuple2);
        executeBoundSQL("insert into Bet(BetID, BetType, UserName) values(:bbind1, :bbind2, :bbind3)", $allTuples2);

        // For MoneyLine table
        $tuple = array(
            ":bind1" => $_POST['BetID'],
            ":bind2" => $_POST['GameID'],
            ":bind3" => $_SESSION['userName'], // username from signin, BetStatus default 'open'
            ":bind4" => $_POST['HomeTeam'],
            ":bind5" => $_POST['AwayTeam'],
            ":bind6" => $_POST['HomeTeamOdds'],
            ":bind7" => $_POST['AwayTeamOdds'],
        );
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
        $tuple = array(
            ":bind1" => $_SESSION['userName'],
            ":bind2" => $_POST['BetID'],
            ":bind3" => $_POST['BetAmount'],
            ":bind4" => $_POST['Prediction'],
            ":bind5" => $_POST['CalculatedOdds']
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

// if submitBet is pressed
if (isset($_POST['CreateNewMoneyLineBet'])) {
    handleCreateMoneyLineBetRequest();
}

// if displayBet is pressed
if (isset($_GET['DisplayAvailableBets'])) {
    displayMoneyLineBets();
}

if (isset($_POST['PlaceBet'])) {
    handlePlaceBetRequest();
}
?>


</body>
</html>
