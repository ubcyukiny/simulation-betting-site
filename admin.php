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
    <label for="usernameToUpdate">UserName to make Changes:</label>
    <input type="text" id="usernameToUpdate" name="usernameToUpdate" placeholder="userName">
    <label for="attributeToChange">Enter attribute to Change:</label>
    <input type="text" id="attributeToChange" name="attributeToChange" placeholder="Enter Email/AccountBalance">
    <label for="newValue">Enter new value</label>
    <input type="text" id="newValue" name="newValue" placeholder="newValue">
    <input type="submit" value="Submit" name="UpdateUser">
</form>
<hr/>
<h1>Delete users here, on cascade, show user created bet also deleted(TODO)</h1>
<form action="admin.php" method="post">
    <!--    should on cascade delete-->
    <label for="username">UserName to Delete:</label>
    <input type="text" id="username" name="username" placeholder="userName here">
    <input type="submit" value="Submit">
</form>
<hr/>
<h1>Division Operation: Find list of users that placed on every bet</h1>
<form action="admin.php" method="GET">
    <p><input type="submit" value="Display" name="DisplayDivision"></p>
</form>
<hr/>
<?php
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()


function executePlainSQL($cmdstr)
{ //takes a plain (no bound variables) SQL command and executes it
    global $db_conn, $success;
    $statement = oci_parse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = oci_execute($statement);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}

function connectToDB()
{
    global $db_conn;
    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = oci_connect("ora_yukiny", "a13215942", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
        return true;
    } else {
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB()
{
    global $db_conn;
    oci_close($db_conn);
}

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
    global $db_conn;
    $userName = $_POST['usernameToUpdate'];
    $attributeToChange = $_POST['attributeToChange'];
    $newValue = $_POST['newValue'];
    if (connectToDB()) {
        if (strcasecmp($attributeToChange, 'email') == 0) {
            if (filter_var($newValue, FILTER_VALIDATE_EMAIL)) {
                executePlainSQL("UPDATE GeneralUser SET email='" . $newValue . "' WHERE username='" . $userName . "'");
                echo "Update success!";
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
    oci_commit($db_conn);
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
    echo "<tr><th>BetID</th><th>BetType</th></tr>";
    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row['BETID'] . "</td><td>" . $row['BETTYPE'] . "</td></tr>";
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

?>

</body>
</html>

