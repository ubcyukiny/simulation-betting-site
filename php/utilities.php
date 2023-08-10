<?php
$global_success = True; //keep track of errors so it redirects the page only if there are no errors
$global_db_conn = NULL; // edit the login credentials in connectToDB()

session_start();


$action = $_GET['util'];
if (connectToDB()) {
    switch ($action) {
        case 'deleteUser':
            handleDeleteUserRequest();
            displayUsers();
            break;
        case 'updateUser':
            handleUpdateUserRequest();
            displayUsers();
            break;
    }
    disconnectFromDB();
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

function handleUpdateUserRequest()
{
    global $global_db_conn;
    $userName = $_GET['selectedUser'];
    $attributeToChange = $_GET['attributeToChange'];
    $newValue = $_GET['newValue'];
    if (strcasecmp($attributeToChange, 'email') == 0) {
        if (filter_var($newValue, FILTER_VALIDATE_EMAIL)) {
            executePlainSQL("UPDATE GeneralUser SET email='" . $newValue . "' WHERE username='" . $userName . "'");
        } else {
            echo "new email is not a valid email format";
        }
    } elseif (strcasecmp($attributeToChange, 'accountBalance') == 0) {
        executePlainSQL("UPDATE GeneralUser SET accountBalance=" . $newValue . " WHERE username='" . $userName . "'");
        echo "Update success!";
    } else {
        echo "Attribute does not exist, please try with email or accountBalance";
    }
    oci_commit($global_db_conn);
}

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
            unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
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

function fieldFormatter($fieldName)
{
    $array = array(
        "USERNAME" => "Username",
        "BETID" => "Bet ID",
        "GAMEID" => "Game ID",
        "PLAYERID" => "Player ID",
        "TEAMID" => "Team ID",
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
        "ACCOUNTBALANCE" => "Account Balance",
        "EMAIL" => "Email",
        "BETTYPE" => "Type",
    );
    if ($array[$fieldName] != null) {
        return $array[$fieldName];
    } else {
        return $fieldName;
    }
}

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

function handleDeleteUserRequest()
{
    global $global_db_conn;
    $tuple = array(
        ":bind1" => $_GET['selectedUser']
    );
    $allTuples = array($tuple);
    executeBoundSQL("DELETE FROM GeneralUser WHERE UserName=:bind1", $allTuples);
    oci_commit($global_db_conn);
}

function betExists($betID)
{
    if (oci_fetch_array(executePlainSQL('SELECT 1 FROM Bet WHERE betID =' . $betID), OCI_BOTH) !== false) {
        return true;
    } else {
        echo "Bet with betID:" . $betID . " not found";
        return false;
    }
}

function gameExists($gameID)
{
    return (oci_fetch_array(executePlainSQL('SELECT 1 FROM Game WHERE gameID =' . $gameID), OCI_BOTH) !== false);
}

function handleCreateMoneyLineBetRequest()
{
    global $global_db_conn;
    if (connectToDB() && gameExists($_POST['GameID'])) {
        // insert to bet table and moneyline table
        // For Bet table
        // need to verify if gameID exists before adding it to bet table
        $tuple2 = array(
            ":bbind1" => $_POST['BetID'],
            ":bbind2" => $_POST['GameID'],
            ":bbind3" => 'MoneyLine',
            ":bbind4" => $_SESSION['userName']
        );
        $allTuples2 = array($tuple2);
        executeBoundSQL("INSERT INTO Bet(BetID, GameID, BetType, UserName) VALUES(:bbind1, :bbind2, :bbind3, :bbind4)", $allTuples2);

        $_gameID = $_POST['GameID'];
        $_homeTeam = oci_fetch_array(executePlainSQL(
            "
            SELECT t.FullName 
            FROM Team t, Game g
            WHERE t.teamID = g.homeTeamID AND g.gameID =" . $_gameID
        ))[0];
        $_awayTeam = oci_fetch_array(executePlainSQL(
            "
            SELECT t.FullName 
            FROM Team t, Game g
            WHERE t.teamID = g.AwayTeamID AND g.gameID =" . $_gameID
        ))[0];

        // For MoneyLine table
        $tuple = array(
            ":bind1" => $_POST['BetID'],
            ":bind2" => $_POST['GameID'],
            ":bind3" => $_SESSION['userName'],
            ":bind4" => $_homeTeam,
            ":bind5" => $_awayTeam,
            ":bind6" => $_POST['HomeTeamOdds'],
            ":bind7" => $_POST['AwayTeamOdds'],
        );
        $allTuples = array($tuple);
        executeBoundSQL("INSERT INTO MoneyLine(BetID, GameID, UserName, HomeTeam, AwayTeam, HomeTeamOdds, AwayTeamOdds)
        VALUES(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)", $allTuples);
        oci_commit($global_db_conn);
    } else {
        echo "Bet creation failed, you are not logged in or GameID not found";
    }
}

function handlePlaceBetRequest()
{
    global $global_db_conn;

    // check user has enough balance, update session and generalUser table if yes
    if ($_SESSION['accountBalance'] >= $_POST['BetAmount']) {
        $_SESSION['accountBalance'] -= $_POST['BetAmount'];
        executePlainSQL("UPDATE generalUser SET accountBalance =" . $_SESSION['accountBalance'] . " WHERE username ='" .  $_SESSION['userName'] . "'");
    } else {
        echo "Not enough balance";

        return false;
    }

    // find odds based on prediction and betID
    if ($_POST['Prediction'] == 'Home') {
        echo "Home selected";
        $_odds = oci_fetch_array(executePlainSQL("SELECT HomeTeamOdds FROM moneyline WHERE betID =" . $_POST['BetID']))[0];
    } else {
        echo "Away selected";
        $_odds = oci_fetch_array(executePlainSQL("SELECT AwayTeamOdds FROM moneyline WHERE betID =" . $_POST['BetID']))[0];
    }

    $tuple = array(
        ":bind1" => $_SESSION['userName'],
        ":bind2" => $_POST['BetID'],
        ":bind3" => $_POST['BetAmount'],
        ":bind4" => $_POST['Prediction'],
        ":bind5" => $_odds
    );
    $allTuples = array($tuple);
    executeBoundSQL("INSERT INTO UserPlacesBet(UserName, BetID, BetAmount, Prediction, CalculatedOdds) VALUES(:bind1, :bind2, :bind3, :bind4, :bind5)", $allTuples);
    oci_commit($global_db_conn);
}
