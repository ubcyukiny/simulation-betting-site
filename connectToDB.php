<?php
$global_success = True; //keep track of errors so it redirects the page only if there are no errors
$global_db_conn = NULL; // edit the login credentials in connectToDB()

function connectToDB()
{
    global $global_db_conn;
    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $global_db_conn = oci_connect("ora_black", "password", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($global_db_conn) {
        return true;
    } else {
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}