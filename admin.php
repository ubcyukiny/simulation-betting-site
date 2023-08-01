<html>
<head>
    <title>304 Project</title>
</head>
<body>
<h1>This is the admin page</h1>
<h1>Lists of current users(Working?)</h1>
<form method="GET" action="admin.php"> <!--refresh page when submitted-->
    <input type="hidden" id="DisplayCurrUsersRequest" name="DisplayCurrUsersRequest">
    <input type="submit" value="Display" name="DisplayCurrUsers">
</form>
<hr/>
<h1>Lists of current Bets(TODO)</h1>
<p><input type="submit" value="Display" name="DisplayCurrBets"></p>
<hr/>
<form action="generalUser.php" method="post">
    <!--   popup if user not found -->
    <!--   add login logic -->
    <!--   should log in with correct accountBalance, email-->
    <label for="username">Username:</label>
    <input type="text" id="username" name="LoginUsername" placeholder="Enter your username">

    <label for="email">Email:</label>
    <input type="email" id="email" name="LoginEmail" placeholder="Enter your email">

    <input type="submit" value="Submit">
</form>
<h1>Lists of users placing on Bets(TODO)</h1>
<p><input type="submit" value="Display" name="DisplayUserPlaceBets"></p>
<hr/>
<h1>Delete users here(TODO)</h1>

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

function printResult($result)
{ //prints results from a select statement
    echo "<br>Retrieved data from table demoTable:<br>";
    echo "<table>";
    echo "<tr><th>UserName</th><th>AccountBalance</th><th>Email</th></tr>";
    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
    }
    echo "</table>";
}

function displayUsers()
{
    if (connectToDB()) {
        printResult(executePlainSQL("SELECT * FROM GeneralUser"));
        disconnectFromDB();
    }
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

if (isset($_GET['DisplayCurrUsersRequest'])) {
    displayUsers();
}

?>

</body>
</html>

