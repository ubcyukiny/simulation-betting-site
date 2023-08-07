
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
    include 'privateSettings.php';
    //$global_db_conn = oci_connect("ora_black", "password", "dbhost.students.cs.ubc.ca:1522/stu");
    $global_db_conn = oci_connect($glo_user, $glo_pass, "dbhost.students.cs.ubc.ca:1522/stu");

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

function fieldFormatter($fieldName) {
    $array = array(
        "USERNAME" => "Username",
        "BET ID" => "Bet ID",
        "GAME ID" => "Game ID",
        "PLAYER ID" => "Player ID",
        "TEAM ID" => "Team ID",
        "FULLNAME" => "Full Name",
        "ADMINVIG" => "Admin Vig",
        "ABBREVIATION" => "Abbr",
        "MINUTES" => "Minutes",
        "POINTS" => "Points",
        "ASSISTS" => "Assists",
        "REBOUNDS" => "Rebounds",
        "SCOREHOME" => "Score Home",
        "SCOREAWAY" => "Score Away",
        "GAMEDATE" => "Game Date",
        "HOMETEAMID" => "Home Team ID",
        "AWAYTEAMID" => "Away Team ID",
        "STATUS" => "Status",
        "TOTALPOOL" => "Total Pool",
        "TOTALVIG" => "Total Vig",
        "SCOREDIFFERENCE" => "Score Difference",
        "ODDS" => "Odds",
        "TOTALSCORE" => "Total Score",
        "HOMETEAM" => "Home Team",
        "AWAYTEAM" => "Away Team",
        "HOMETEAMODDS" => "Home Team Odds",
        "AWAYTEAMODDS" => "Away Team Odds",
        "CITY" => "City",
        "TEAMID" => "Team ID",
    );
    if ($array[$fieldName] != null) {
        return $array[$fieldName];
    } else {
        return $fieldName;
    }
}

function printTable($result, $columnMapping = null)
{
    echo "<table>";
    echo "<br>Printing Table";
    
    // Print the table header based on column mapping
    if ($columnMapping) {
        echo "<tr>";
        foreach ($columnMapping as $displayName) {
            echo "<th>" . htmlentities($displayName, ENT_QUOTES) . "</th>";
        }
        echo "</tr>";
        echo "<tr><td colspan='" . count($columnMapping) . "' style='border-bottom: 1px solid black;'></td></tr>";
    } else {
        // Print the default header titles
        $defaultHeaders = oci_num_fields($result);
        echo "<tr>";
        for ($i = 1; $i <= $defaultHeaders; $i++) {
            echo "<th> " . fieldFormatter(oci_field_name($result, $i)) . "</th>";
        }
        echo "</tr>";
        echo "<tr><td colspan='" . $defaultHeaders . "' style='border-bottom: 1px solid black;'></td></tr>";
    }

    while ($row = oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo "<tr>";
        // Print each column value
        foreach ($row as $column => $value) {
            echo "<td>" . ($value !== null ? htmlentities($value, ENT_QUOTES) : "") . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
}
