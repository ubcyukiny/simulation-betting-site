<html>
<head>
    <title>304 Project</title>
</head>
<body>
<h1>This is the admin page</h1>
<h1>Lists of current users(Working)</h1>
<form method="GET" action="admin.php"> <!--refresh page when submitted-->
    <input type="hidden" id="DisplayCurrUsersRequest" name="DisplayCurrUsersRequest">
    <input type="submit" value="Display" name="DisplayCurrUsers">
</form>
<hr/>
<h1>Lists of current Bets(TODO)</h1>
<p><input type="submit" value="Display" name="DisplayCurrBets"></p>
<hr/>
<h1>Lists of users placing on Bets(TODO)</h1>
<p><input type="submit" value="Display" name="DisplayUserPlaceBets"></p>
<hr/>
<h1>Update user email/accountBalance(Working)</h1>
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
{ //prints results from a select statement
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


if (isset($_GET['DisplayCurrUsersRequest'])) {
    displayUsers();
}

if (isset($_POST['UpdateUser']) && array_key_exists('updateUserRequest', $_POST)) {
    handleUpdateUserRequest();
}

?>

</body>
</html>

