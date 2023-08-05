<?php
$global_success = True; //keep track of errors so it redirects the page only if there are no errors
$global_db_conn = NULL; // edit the login credentials in connectToDB()

function get_db_conn()
{
    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.

    return oci_connect("ora_black", "password", "dbhost.students.cs.ubc.ca:1522/stu");
}