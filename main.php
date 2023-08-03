<?php
    session_start();
?>
<html>
<head>
    <title>Home Page</title>
</head>
<body>
<h1>NBA Betting</h1>
<p>Let's win some money!</p>
<hr/>
<h1>Sign up here</h1>
<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="newUsername" name="SignUpUsername" placeholder="Enter your username" required>
    <label for="email">Email:</label>
    <input type="email" id="newEmail" name="SignUpEmail" placeholder="Enter your email" required>
    <input type="submit" value="Submit" name="CreateNewUser">
</form>
<hr/>
<h1>Login here</h1>
<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="LoginUsername" placeholder="Enter your username" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="LoginEmail" placeholder="Enter your email" required>
    <input type="submit" value="Submit" , name="Login">
</form>
<hr/>
<h1>Click here to view as admin</h1>
<form action="admin.php" method="post">
    <input type="submit" value="LoginAsAdmin">
</form>


<?php
// global variables
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

// executeBoundSQL, handle error
function executeBoundSQL($cmdstr, $list)
{
    /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
In this case you don't need to create the statement several times. Bound variables cause a statement to only be
parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
See the sample code below for how this function is used */
    global $db_conn, $success;
    $statement = oci_parse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            oci_bind_by_name($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = oci_execute($statement);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
    return $statement;
}

// function connectToDB()
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


// function disconnectDB
function disconnectFromDB()
{
    global $db_conn;
    oci_close($db_conn);
}

function handleCreateNewUserRequest()
{
    global $db_conn;
    if (connectToDB()) {
        $tuple = array(
            ":bind1" => $_POST['SignUpUsername'],
            ":bind2" => $_POST['SignUpEmail']
        );
        $allTuples = array($tuple);
        executeBoundSQL("insert into GeneralUser(UserName, Email) values(:bind1, :bind2)", $allTuples);
        oci_commit($db_conn);
        disconnectFromDB();

    }
}

function handleLogin()
{
    global $db_conn;
    if (connectToDB()) {
        // get userinputUsername and userinputEmail, wrap it in a array
        $tuple = array(
                ":bind1" => $_POST['LoginUsername'],
                ":bind2" => $_POST['LoginEmail']
        );
        $allTuples = array($tuple);
        $statement = executeBoundSQL("select 1 from GeneralUser where UserName = :bind1 and Email = :bind2", $allTuples);
        $row = oci_fetch_assoc($statement);

        if ($row) {
            $userName = $_POST['LoginUsername'];
            $_SESSION['userName'] = $userName;
            $_SESSION['email'] = $_POST['LoginEmail'];
            // get accountBalance
            $stid = oci_parse($db_conn, "select accountBalance from GeneralUser where username ='" . $userName . "'" );
            oci_execute($stid);
            $result = oci_fetch_array($stid, OCI_BOTH);
            $_SESSION['accountBalance'] = $result['ACCOUNTBALANCE']; // accountbalance
            disconnectFromDB();
            header("Location: generalUser.php");
            exit;
        } else {
            echo "Cannot find account with that username/email.";
            disconnectFromDB();
        }
    }
}

// if SignUp is pressed
if (isset($_POST['CreateNewUser'])) {
    handleCreateNewUserRequest();
}

// if login is pressed
if (isset($_POST['Login'])) {
    handleLogin();
}

?>
</body>
</html>
