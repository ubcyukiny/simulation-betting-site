<html>
<head>
    <title>Home Page</title>
</head>
<body>
<h1>NBA Betting</h1>
<p>Let's win some money!</p>
<hr/>
<h1>Sign up here (Working?)</h1>
<form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="newUsername" name="SignUpUsername" placeholder="Enter your username">
    <label for="email">Email:</label>
    <input type="email" id="newEmail" name="SignUpEmail" placeholder="Enter your email">
    <input type="submit" value="Submit" name="CreateNewUser">
</form>
<hr/>
<h1>Login here (TODO)</h1>
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
<hr/>
<h1>Click here to view as admin</h1>
<form action="admin.php" method="post">
    <input type="submit" value="LoginAsAdmin">
</form>


<?php
// global variables
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()

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
        } else {
            echo "SignUp Success!";
        }
    }
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

// function handleCreatNewUserRequest -> call executeBoundSql
function handleCreateNewUserRequest()
{
    global $db_conn;
    if (connectToDB()) {
        $tuple = array (
                ":bind1" => $_POST['SignUpUsername'],
                ":bind2" => $_POST['SignUpEmail']
        );
        $alltuples = array ($tuple);
        executeBoundSQL("insert into GeneralUser(UserName, Email) values(:bind1, :bind2)", $alltuples);
        oci_commit($db_conn);
        disconnectFromDB();
    }
}

// if SignUp is pressed
if (isset($_POST['CreateNewUser'])) {
    handleCreateNewUserRequest();

}

?>
</body>
</html>
