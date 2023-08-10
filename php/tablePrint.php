<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<?php
include 'utilities.php';

function printTable($result, $columnMapping = null)
{

    echo '<table class="result-table">';
    // Print the table header based on column mapping
    if ($columnMapping) {
        echo "<tr>";
        foreach ($columnMapping as $displayName) {
            echo "<th>" . htmlentities($displayName, ENT_QUOTES) . "</th>";
        }
        echo "</tr>";
        echo "<tr><td colspan='" . count($columnMapping) . "' style='border-bottom: 1px solid black;'></td></tr>";
    } else {
        // Print the default header titles
        $defaultHeaders = oci_num_fields($result);
        echo "<tr>";
        for ($i = 1; $i <= $defaultHeaders; $i++) {
            echo "<th> " . fieldFormatter(oci_field_name($result, $i)) . "</th>";
        }
        echo "</tr>";
        echo "<tr><td colspan='" . $defaultHeaders . "' style='border-bottom: 1px solid black;'></td></tr>";
    }

    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo "<tr>";
        // Print each column value
        foreach ($row as $column => $value) {
            echo "<td>" . ($value !== null ? htmlentities($value, ENT_QUOTES) : "") . "</td>";
        }
        echo "</tr>";
    }
    // For Bugfixing - from stackoverflow post https://stackoverflow.com/questions/4323411/how-can-i-write-to-the-console-in-php



    echo "</table>";
}

$action = $_GET['print'];
if (connectToDB()) {
    switch ($action) {
        case 'adminSearch':
            adminSearch();
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
        case 'displayMoneyline':
            displayMoneyLineBets();
            break;
        case 'PlaceBet':
            handlePlaceBetRequest();
            break;
        case 'DisplayCurrUsersRequest':
            displayUsers();
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
        case 'DisplayJoin':
            if (betExists($_GET['BetID'])) {
                displayJoin();
            }
            break;
        case 'DisplayAggregationWithGroupBy':
            displayAggregationWithGroupBy();
            break;
        case 'DisplayNestedAggregationWithGroupBy':
            displayNestedAggregation();
            break;
        case 'DisplayAggregationWithHaving':
            displayAggregationWithHaving();
            break;
    }
    disconnectFromDB();
}

function adminSearch()
{
    $selectedTable = $_GET['tableFrom'];
    $attributes = $_GET['attributeOptions'];

    $selectedAttributes = combinePost($attributes);
    printTable(executePlainSQL("SELECT " . $selectedAttributes . " FROM " . $selectedTable));
}

function combinePost($attributes)
{
    $selectedAttributes = "";
    foreach ($attributes as $attribute) {
        $selectedAttributes .= ", " . $attribute;
    }
    $selectedAttributes = ltrim($selectedAttributes, ",");
    return $selectedAttributes;
}

function displayUsers()
{
    $colNames = ["Username", "Account Balance", "Email"];
    printTable(executePlainSQL("SELECT * FROM GeneralUser"), $colNames);
}

function displayJoin()
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
    $filter = combinePost($_GET['filterMoney']);
    $command = "SELECT " . $filter . ", Status FROM MoneyLine";
    debug_to_console($command);
    printTable(executePlainSQL($command));
}
?>

</html>