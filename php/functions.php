
<!-- Function used from PHP tutorial oracle_test.php -->
<?php
$global_success = True; //keep track of errors so it redirects the page only if there are no errors
$global_db_conn = NULL; // edit the login credentials in connectToDB()


function executePlainSQL($cmdstr)
{ //takes a plain (no bound variables) SQL command and executes it
    global $global_db_conn, $global_success;
    $statement = oci_parse($global_db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($global_db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $global_success = False;
    }

    $r = oci_execute($statement);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        $global_success = False;
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
    global $global_db_conn, $global_success;
    $statement = oci_parse($global_db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($global_db_conn);
        echo htmlentities($e['message']);
        $global_success = False;
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
            $global_success = False;
        }
    }
    return $statement;
}

function connectToDB()
{
    global $global_db_conn;
    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $global_db_conn = oci_connect("ora_yukiny", "a13215942", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($global_db_conn) {
        return true;
    } else {
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB()
{
    global $global_db_conn;
    oci_close($global_db_conn);
}