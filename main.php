<?php
    include 'functions.php';
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
<form action="signup.php" method="post">
    <input type="submit" value="Click Here">
    // <label for="username">Username:</label>
    // <input type="text" id="newUsername" name="SignUpUsername" placeholder="Enter your username" required>
    // <label for="email">Email:</label>
    // <input type="email" id="newEmail" name="SignUpEmail" placeholder="Enter your email" required>
    // <input type="submit" value="Submit" name="CreateNewUser">
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

function handleCreateNewUserRequest()
{
    global $global_db_conn;
    if (connectToDB()) {
        $tuple = array(
            ":bind1" => $_POST['SignUpUsername'],
            ":bind2" => $_POST['SignUpEmail']
        );
        $allTuples = array($tuple);
        executeBoundSQL("insert into GeneralUser(UserName, Email) values(:bind1, :bind2)", $allTuples);
        oci_commit($global_db_conn);
        disconnectFromDB();

    }
}

function handleLogin()
{
    global $global_db_conn;
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
            $stid = oci_parse($global_db_conn, "select accountBalance from GeneralUser where username ='" . $userName . "'" );
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
