<html>
<head>
    <title>304 Project</title>
</head>
<body>
<h1>This is the admin page</h1>
<h1>Lists of current users</h1>
<form method="GET" action="admin.php"> <!--refresh page when submitted-->
    <input type="hidden" id="DisplayCurrUsersRequest" name="DisplayCurrUsersRequest">
    <input type="submit" value="Display" name="DisplayCurrUsers">
</form>
<hr/>
<h1>Lists of current Bets</h1>
<form action="admin.php" method="GET">
    <p><input type="submit" value="Display" name="DisplayCurrBets"></p>
</form>
<hr/>
<h1>Transaction list of users placing on Bets</h1>
<form action="admin.php" method="GET">
    <p><input type="submit" value="Display" name="DisplayUserPlacesBet"></p>
</form>
<hr/>
<h1>Update user email/accountBalance</h1>
<form action="admin.php" method="post">
    <input type="hidden" id="updateUserRequest" name="updateUserRequest">
    <label for="usernameToUpdate">Username of user to update:</label>
    <input type="text" id="usernameToUpdate" name="usernameToUpdate" placeholder="userName">
    <label for="attributeToChange">Enter attribute to Change:</label>
    <input type="text" id="attributeToChange" name="attributeToChange" placeholder="Enter Email/AccountBalance">
    <label for="newValue">Enter new value</label>
    <input type="text" id="newValue" name="newValue" placeholder="newValue">
    <input type="submit" value="Update User" name="UpdateUser">
</form>
<hr/>
<h1>Delete users and bets created by that user, any placement of that bet will be deleted as well </h1>
<form action="admin.php" method="post">
    <!--    should on cascade delete-->
    <label for="username">UserName to Delete:</label>
    <input type="text" id="usernameToDelete" name="UsernameToDelete">
    <input type="submit" value="Delete User" name="DeleteUser">
</form>
<hr/>
<h1>Join: Find name and accountBalance of all users who placed on a specific bet</h1>
<form action="admin.php" method="GET">
    <label for="betID">BetID:</label>
    <input type="number" id="betID" name="BetID" required>
    <p><input type="submit" value="Display" name="DisplayJoin"></p>
</form>
<hr/>
<h1>Division Operation: Find list of users that placed on every bet</h1>
<form action="admin.php" method="GET">
    <p><input type="submit" value="Display" name="DisplayDivision"></p>
</form>
<hr/>

<?php

include 'functions.php';

function printUsers($result)
{
    echo "<br>Retrieved data from table generalUsers:<br>";
    echo "<table>";
    echo "<tr><th>UserName</th><th>AccountBalance</th><th>Email</th></tr>";
    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row['USERNAME'] . "</td><td>" . $row['ACCOUNTBALANCE'] . "</td><td>" . $row['EMAIL'] . "</td></tr>";
    }
    echo "</table>";
}

function displayUsers()
{
    if (connectToDB()) {
        printUsers(executePlainSQL("SELECT * FROM GeneralUser"));
        disconnectFromDB();
    }
}


function handleUpdateUserRequest()
{
    global $global_db_conn;
    $userName = $_POST['usernameToUpdate'];
    $attributeToChange = $_POST['attributeToChange'];
    $newValue = $_POST['newValue'];
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
            ":bind1" => $_POST['UsernameToDelete']
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
        printUserJoinUserPlacesBet(executeBoundSQL("SELECT g.username, g.accountbalance FROM generaluser g, userplacesbet usp WHERE g.username = usp.username AND usp.BetID = :bind1", $allTuples));
        disconnectFromDB();
    }
}

function printUserJoinUserPlacesBet($result)
{
    echo "<br>Name and accountBalance of all users who placed on betID = " . $_GET['BetID'] . "<br>";
    echo "<table>";
    echo "<tr><th>UserName</th><th>AccountBalance</th></tr>";
    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row['USERNAME'] . "</td><td>" . $row['ACCOUNTBALANCE'] . "</td></tr>";
    }
    echo "</table>";
}


function printUserPlacesBet($result)
{
    echo "<br>Retrieved data from table UserPlacesBet:<br>";
    echo "<table>";
    echo "<tr><th>UserName</th><th>BetID</th><th>BetAmount</th><th>Prediction</th><th>CalculatedOdds</th></tr>";
    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row['USERNAME'] . "</td><td>" . $row['BETID'] . "</td><td>" . $row['BETAMOUNT'] . "</td>
        <td>" . $row['PREDICTION'] . "</td><td>" . $row['CALCULATEDODDS'] . "</td></tr>";
    }
    echo "</table>";
}

function printBets($result)
{
    echo "<br>Retrieved data from table Bet:<br>";
    echo "<table>";
    echo "<tr><th>BetID</th><th>BetType</th><th>UserName (Created By)</th></tr>";
    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row['BETID'] . "</td><td>" . $row['BETTYPE'] . "</td><td>" . $row['USERNAME'] . "</td></tr>";
    }
    echo "</table>";
}

function displayUserPlacesBet()
{
    if (connectToDB()) {
        printUserPlacesBet(executePlainSQL("SELECT * FROM UserPlacesBet"));
        disconnectFromDB();
    }
}


function displayDivision()
{
    if (connectToDB()) {
        $result = executePlainSQL("select * from GeneralUser g where not exists (select b.betID from Bet b where not exists (select usp.betID from UserPlacesBet usp where usp.betID = b.betID and usp.userName = g.userName))");
        printUsers($result);
        disconnectFromDB();
    }
}

function displayBets()
{
    if (connectToDB()) {
        printBets(executePlainSQL("SELECT * FROM Bet"));
        disconnectFromDB();
    }
}

function betExists($betID) {
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

if (isset($_GET['DisplayCurrUsersRequest'])) {
    displayUsers();
}

if (isset($_POST['UpdateUser']) && array_key_exists('updateUserRequest', $_POST)) {
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

if (isset($_POST['DeleteUser'])) {
    handleDeleteUserRequest();
}

if (isset($_GET['DisplayJoin']) && betExists($_GET['BetID'])) {
    handleJoinRequest();
}



?>

</body>
</html>

