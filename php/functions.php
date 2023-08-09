<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<?php
include 'utilities.php';

$action = key(array_filter($_GET, function ($value, $key) {
    return isset($_GET[$key]);
}, ARRAY_FILTER_USE_BOTH));
if (connectToDB()) {
    switch ($action) {
        case 'AdminSearch':
            adminSearch();
            break;
        case 'Logout':
            session_unset();
            session_destroy();
            header("Location: main.php");
            break;
        case 'DisplayGames':
            displayGames();
            break;
        case 'FilterGames':
            displayGames($_GET['TeamNameFilter']);
            break;
        case 'CreateNewMoneyLineBet':
            handleCreateMoneyLineBetRequest();
            break;
        case 'DisplayAvailableBets':
            displayMoneyLineBets();
            break;
        case 'PlaceBet':
            handlePlaceBetRequest();
            break;
        case 'DisplayCurrUsersRequest':
            displayUsers();
            break;
        case 'UpdateUser':
            if (array_key_exists('updateUserRequest', $_GET)) {
                handleUpdateUserRequest();
            }
            break;
        case 'DisplayUserPlacesBet':
            displayUserPlacesBet();
            break;
        case 'DisplayDivision':
            displayDivision();
            break;
        case 'DisplayCurrBets':
            displayBets();
            break;
        case 'DeleteUser':
            handleDeleteUserRequest();
            break;
        case 'DisplayJoin':
            if (betExists($_GET['BetID'])) {
                handleJoinRequest();
            }
            break;
        case 'DisplayAggregationWithGroupBy':
            displayAggregationWithGroupBy();
            break;
        case 'DisplayNestedAggregationWithGroupBy':
            displayNestedAggregation();
            break;
    }
    disconnectFromDB();
}

function adminSearch()
{
    $selectedTable = $_GET['tableFrom'];
    $attributes = $_GET['attributeOptions'];
    $selectedAttributes = "";
    foreach ($attributes as $attribute) {
        $selectedAttributes .= ", " . $attribute;
    }
    $selectedAttributes = ltrim($selectedAttributes, ",");
    printTable(executePlainSQL("SELECT " . $selectedAttributes . " FROM " . $selectedTable));
}

function displayUsers()
{
    $colNames = ["Username", "Account Balance", "Email"];
    printTable(executePlainSQL("SELECT * FROM GeneralUser"), $colNames);
}

function handleUpdateUserRequest()
{
    global $global_db_conn;
    $userName = $_GET['usernameToUpdate'];
    $attributeToChange = $_GET['attributeToChange'];
    $newValue = $_GET['newValue'];
    if (strcasecmp($attributeToChange, 'email') == 0) {
        if (filter_var($newValue, FILTER_VALIDATE_EMAIL)) {
            executePlainSQL("UPDATE GeneralUser SET email='" . $newValue . "' WHERE username='" . $userName . "'");
        } else {
            echo "new email is not a valid email format";
        }
    } elseif (strcasecmp($attributeToChange, 'accountBalance') == 0) {
        executePlainSQL("UPDATE GeneralUser SET accountBalance=" . $newValue . " WHERE username='" . $userName . "'");
        echo "Update success!";
    } else {
        echo "Attribute does not exist, please try with email or accountBalance";
    }
    oci_commit($global_db_conn);
}

function handleDeleteUserRequest()
{
    global $global_db_conn;
    $tuple = array(
        ":bind1" => $_GET['UsernameToDelete']
    );
    $allTuples = array($tuple);
    executeBoundSQL("DELETE FROM GeneralUser WHERE UserName=:bind1", $allTuples);
    oci_commit($global_db_conn);
}

function handleJoinRequest()
{
    $tuple = array(
        ":bind1" => $_GET['BetID']
    );
    $allTuples = array($tuple);
    $cols = ["Username", "Account Balance"];
    printTable(executeBoundSQL("SELECT g.username, g.accountbalance FROM generaluser g, userplacesbet usp WHERE g.username = usp.username AND usp.BetID = :bind1", $allTuples), $cols);
}

function displayUserPlacesBet()
{
    $cols = ["Username", "Bet ID", "Bet Amount", "Prediction", "Odds"];
    printTable(executePlainSQL("SELECT * FROM UserPlacesBet"), $cols);
}

function displayDivision()
{
    $result = executePlainSQL("SELECT * FROM GeneralUser g WHERE NOT EXISTS (SELECT b.betID FROM Bet b WHERE NOT EXISTS (SELECT usp.betID FROM UserPlacesBet usp WHERE usp.betID = b.betID AND usp.userName = g.userName))");
    $colNames = ["Username", "Account Balance", "Email"];
    printTable($result, $colNames);
}

function displayBets()
{
    $colNames = ["Bet ID", "Game ID", "Bet Type", "Created By"];
    printTable(executePlainSQL("SELECT * FROM Bet"), $colNames);
}

function betExists($betID)
{
    if (oci_fetch_array(executePlainSQL('SELECT 1 FROM Bet WHERE betID =' . $betID), OCI_BOTH) !== false) {
        return true;
    } else {
        echo "Bet with betID:" . $betID . " not found";
        return false;
    }
}

function displayNestedAggregation()
{

    executePlainSQL("
        CREATE VIEW temp(gameID, betTotal) AS
            SELECT b.gameID, SUM(usp.betAmount) AS betTotal
            FROM bet b, userPlacesBet usp
            WHERE b.betID = usp.betID
            GROUP BY b.gameID
            HAVING SUM(usp.betAmount) >= 2000
	        ");
    $colNames = ["Game ID", "Average"];
    printTable(executePlainSQL("
        SELECT temp.gameID, AVG(usp.betAmount) AS betAvg
        FROM userPlacesBet usp, bet b, temp
        WHERE usp.betID = b.betID AND b.gameID = temp.gameID
        GROUP BY temp.GameID
        "), $colNames);
    executePlainSQL("DROP VIEW temp");
}

function displayAggregationWithGroupBy()
{

    $colNames = ["Username", "Max Bet"];
    printTable(executePlainSQL("
            SELECT userName, MAX(betAmount) AS maxBet
            FROM userPlacesBet
            GROUP BY userName
        "), $colNames);
}

function displayAggregationWithHaving()
{

    $colNames = ["Username", "BetTotal"];
    printTable(executePlainSQL("
            SELECT userName, SUM(betAmount) AS betTotal
            FROM userPlacesBet
            GROUP BY userName
            HAVING SUM(betAmount) > 500
        "), $colNames);
}

function displayGames($args = null)
{

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
}

function displayMoneyLineBets()
{

    $filter = implode(", ", $_GET['filterMoney']);
    $command = "SELECT " . $filter . ", Status FROM MoneyLine";
    printTable(executePlainSQL($command));
}

function gameExists($gameID)
{
    return (oci_fetch_array(executePlainSQL('SELECT 1 FROM Game WHERE gameID =' . $gameID), OCI_BOTH) !== false);
}

function handleCreateMoneyLineBetRequest()
{
    global $global_db_conn;
    if (connectToDB() && gameExists($_GET['GameID'])) {
        // insert to bet table and moneyline table
        // For Bet table
        // need to verify if gameID exists before adding it to bet table
        $tuple2 = array(
            ":bbind1" => $_GET['BetID'],
            ":bbind2" => $_GET['GameID'],
            ":bbind3" => 'MoneyLine',
            ":bbind4" => $_SESSION['userName']
        );
        $allTuples2 = array($tuple2);
        executeBoundSQL("INSERT INTO Bet(BetID, GameID, BetType, UserName) VALUES(:bbind1, :bbind2, :bbind3, :bbind4)", $allTuples2);

        $_gameID = $_GET['GameID'];
        $_homeTeam = oci_fetch_array(executePlainSQL(
            "
            SELECT t.FullName 
            FROM Team t, Game g
            WHERE t.teamID = g.homeTeamID AND g.gameID =" . $_gameID
        ))[0];
        $_awayTeam = oci_fetch_array(executePlainSQL(
            "
            SELECT t.FullName 
            FROM Team t, Game g
            WHERE t.teamID = g.AwayTeamID AND g.gameID =" . $_gameID
        ))[0];

        // For MoneyLine table
        $tuple = array(
            ":bind1" => $_GET['BetID'],
            ":bind2" => $_GET['GameID'],
            ":bind3" => $_SESSION['userName'],
            ":bind4" => $_homeTeam,
            ":bind5" => $_awayTeam,
            ":bind6" => $_GET['HomeTeamOdds'],
            ":bind7" => $_GET['AwayTeamOdds'],
        );
        $allTuples = array($tuple);
        executeBoundSQL("INSERT INTO MoneyLine(BetID, GameID, UserName, HomeTeam, AwayTeam, HomeTeamOdds, AwayTeamOdds)
        VALUES(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)", $allTuples);
        oci_commit($global_db_conn);
    } else {
        echo "Bet creation failed, you are not logged in or GameID not found";
    }
}

function handlePlaceBetRequest()
{
    global $global_db_conn;

    // check user has enough balance, update session and generalUser table if yes
    if ($_SESSION['accountBalance'] >= $_GET['BetAmount']) {
        $_SESSION['accountBalance'] -= $_GET['BetAmount'];
        executePlainSQL("UPDATE generalUser SET accountBalance =" . $_SESSION['accountBalance'] . " WHERE username ='" .  $_SESSION['userName'] . "'");
    } else {
        echo "Not enough balance";

        return false;
    }

    // find odds based on prediction and betID
    if ($_GET['Prediction'] == 'Home') {
        echo "Home selected";
        $_odds = oci_fetch_array(executePlainSQL("SELECT HomeTeamOdds FROM moneyline WHERE betID =" . $_GET['BetID']))[0];
    } else {
        echo "Away selected";
        $_odds = oci_fetch_array(executePlainSQL("SELECT AwayTeamOdds FROM moneyline WHERE betID =" . $_GET['BetID']))[0];
    }

    $tuple = array(
        ":bind1" => $_SESSION['userName'],
        ":bind2" => $_GET['BetID'],
        ":bind3" => $_GET['BetAmount'],
        ":bind4" => $_GET['Prediction'],
        ":bind5" => $_odds
    );
    $allTuples = array($tuple);
    executeBoundSQL("INSERT INTO UserPlacesBet(UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES(:bind1, :bind2, :bind3, :bind4, :bind5)", $allTuples);
    oci_commit($global_db_conn);
}
?>