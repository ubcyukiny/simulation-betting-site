<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<?php
include 'functions.php';

function adminSearch()
{
    if (connectToDB()) {
        $selectedTable = $_GET['tableFrom'];
        $attributes = $_GET['attributeOptions'];
        $selectedAttributes = "";
        foreach ($attributes as $attribute) {
            $selectedAttributes = $selectedAttributes . ", " . $attribute;
        }
        $selectedAttributes = ltrim($selectedAttributes, ",");
        printTable(executePlainSQL("SELECT " . $selectedAttributes . " FROM " . $selectedTable));
        disconnectFromDB();
    }
}
if (isset($_GET['AdminSearch'])) {
    adminSearch();
}

function displayUsers()
{
    if (connectToDB()) {
        printTable(executePlainSQL("SELECT * FROM GeneralUser"), ["Username", "Account Balance", "Email"]);
        disconnectFromDB();
    }
}

function handleUpdateUserRequest()
{
    global $global_db_conn;
    $userName = $_GET['usernameToUpdate'];
    $attributeToChange = $_GET['attributeToChange'];
    $newValue = $_GET['newValue'];
    if (connectToDB()) {
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
            echo "Attribute does not exists, please try with email or accountBalance";
        }
    }
    oci_commit($global_db_conn);
}

function handleDeleteUserRequest()
{
    global $global_db_conn;
    if (connectToDB()) {
        $tuple = array(
            ":bind1" => $_GET['UsernameToDelete']
        );
        $allTuples = array($tuple);
        executeBoundSQL("delete from GeneralUser where UserName=:bind1", $allTuples);
        oci_commit($global_db_conn);
        disconnectFromDB();
    }
}

function handleJoinRequest()
{
    if (connectToDB()) {
        $tuple = array(
            ":bind1" => $_GET['BetID']
        );
        $allTuples = array($tuple);
        $cols = ["Username", "Account Balance"];
        printTable(executeBoundSQL("SELECT g.username, g.accountbalance FROM generaluser g, userplacesbet usp WHERE g.username = usp.username AND usp.BetID = :bind1", $allTuples), $cols);
        disconnectFromDB();
    }
}

function displayUserPlacesBet()
{
    if (connectToDB()) {
        $cols = ["Username", "Bet ID", "Bet Amount", "Prediction", "Odds"];
        printTable(executePlainSQL("SELECT * FROM UserPlacesBet"), $cols);
        disconnectFromDB();
    }
}


function displayDivision()
{
    if (connectToDB()) {
        $result = executePlainSQL("select * from GeneralUser g where not exists (select b.betID from Bet b where not exists (select usp.betID from UserPlacesBet usp where usp.betID = b.betID and usp.userName = g.userName))");
        printTable($result, ["Username", "Account Balance", "Email"]);
        disconnectFromDB();
    }
}

function displayBets()
{
    if (connectToDB()) {
        $cols = ["Bet ID", "Game ID", "Bet Type", "Created By"];
        printTable(executePlainSQL("SELECT * FROM Bet"), $cols);
        disconnectFromDB();
    }
}

function betExists($betID)
{
    if (connectToDB()) {
        if (oci_fetch_array(executePlainSQL('select 1 from Bet where betID =' . $betID), OCI_BOTH) !== false) {
            return true;
        } else {
            echo "Bet with betID:" . $betID . " not found";
            return false;
        }
    } else {
        return false;
    }
}

function displayNestedAggregation()
{
    if (connectToDB()) {
        executePlainSQL("
        create view temp(gameID, betTotal) as
            select b.gameID, sum(usp.betAmount) as betTotal
            from bet b, userPlacesBet usp
            where b.betID = usp.betID
            group by b.gameID
            having sum(usp.betAmount) >= 2000
	        ");
        printTable(executePlainSQL("
        select temp.gameID, avg(usp.betAmount) as betAvg
        from userPlacesBet usp, bet b, temp
        where usp.betID = b.betID and b.gameID = temp.gameID
        group by temp.GameID
        "), ["Game ID", "Average"]);
        executePlainSQL("drop view temp");
        disconnectFromDB();
    }
}

function DisplayAggregationWithGroupBy()
{
    if (connectToDB()) {
        printTable(executePlainSQL("
            select userName, max(betAmount) as maxBet
            from userPlacesBet
            group by userName
        "), ["Username", "Max Bet"]);
        disconnectFromDB();
    }
}

function displayAggregationWithHaving()
{
    if (connectToDB()) {
        printTable(executePlainSQL("
            select userName, sum(betAmount) as betTotal
            from userPlacesBet
            group by userName
            having sum(betAmount) > 500
        "), ["Username", "BetTotal"]);
        disconnectFromDB();
    }
}

if (isset($_GET['DisplayCurrUsersRequest'])) {
    displayUsers();
}

if (isset($_GET['UpdateUser']) && array_key_exists('updateUserRequest', $_GET)) {
    handleUpdateUserRequest();
}

if (isset($_GET['DisplayUserPlacesBet'])) {
    displayUserPlacesBet();
}

if (isset($_GET['DisplayDivision'])) {
    displayDivision();
}

if (isset($_GET['DisplayCurrBets'])) {
    displayBets();
}

if (isset($_GET['DeleteUser'])) {
    handleDeleteUserRequest();
}

if (isset($_GET['DisplayJoin']) && betExists($_GET['BetID'])) {
    handleJoinRequest();
}

if (isset($_GET['DisplayAggregationWithGroupBy'])) {
    displayAggregationWithGroupBy();
}

if (isset($_GET['DisplayNestedAggregationWithGroupBy'])) {
    displayNestedAggregation();
}

if (isset($_GET['DisplayAggregationWithHaving'])) {
    displayAggregationWithHaving();
}
?>