<script>
    // Function to update attributes dropdown based on the selected table
    function updateAttributes() {
        var selectedTable = document.getElementById('tableFrom').value;
        console.log('Selected Table:', selectedTable); // Move this line here
        var attributeOptions = document.getElementById('attributeOptions');
        // Save the selected table in local storage
        localStorage.setItem('selectedTable', selectedTable);

        attributeOptions.innerHTML = '';

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Configure the AJAX request
        xhr.open('GET', 'get_attributes.php?table=' + encodeURIComponent(selectedTable), true);

        // Define the callback function when the request completes
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        // Parse the response and add attributes to the dropdown
                        var attributes = JSON.parse(xhr.responseText);
                        for (var i = 0; i < attributes.length; i++) {
                            var option = document.createElement('option');
                            option.value = attributes[i];
                            option.text = attributes[i];
                            attributeOptions.appendChild(option);
                        }
                    } catch (error) {
                        console.log('Failed to parse JSON response:', error);
                    }
                } else {
                    console.log('Failed to fetch attributes.');
                }
            }
        };

        // Send the AJAX request
        xhr.send();
    }

    // Bind the updateAttributes function to the change event of the table dropdown
    document.addEventListener('DOMContentLoaded', function() {
        var tableFrom = document.getElementById('tableFrom');
        tableFrom.addEventListener('change', updateAttributes);

        // Retrieve the selected table from local storage and set it as the selected value
        var savedTable = localStorage.getItem('selectedTable');
        if (savedTable) {
            tableFrom.value = savedTable;
        }

        // Call the updateAttributes function to populate the attributeOptions
        updateAttributes();
    });
</script>
<?php
include 'functions.php';
function adminSearch()
{
    if (connectToDB()) {
        $selectedTable = $_POST['tableFrom'];
        $attributes = $_POST['attributeOptions'];
        $selectedAttributes = "";
        foreach ($attributes as $attribute) {
            $selectedAttributes = $selectedAttributes . ", " . $attribute;
        }
        $selectedAttributes = ltrim($selectedAttributes, ",");
        printTable(executePlainSQL("SELECT " . $selectedAttributes . " FROM " . $selectedTable));
        disconnectFromDB();
    }
}
if (isset($_POST['AdminSearch'])) {
    adminSearch();
}

function displayUsers()
{
    if (connectToDB()) {
        printToConsole("I tried");
        printTable(executePlainSQL("SELECT * FROM GeneralUser"), ["Username", "Account Balance", "Email"]);
        disconnectFromDB();
    }
}

function handleUpdateUserRequest()
{
    global $global_db_conn;
    $userName = $_POST['usernameToUpdate'];
    $attributeToChange = $_POST['attributeToChange'];
    $newValue = $_POST['newValue'];
    if (connectToDB()) {
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
            echo "Attribute does not exists, please try with email or accountBalance";
        }
    }
    oci_commit($global_db_conn);
}

function handleDeleteUserRequest()
{
    global $global_db_conn;
    if (connectToDB()) {
        $tuple = array(
            ":bind1" => $_POST['UsernameToDelete']
        );
        $allTuples = array($tuple);
        executeBoundSQL("delete from GeneralUser where UserName=:bind1", $allTuples);
        oci_commit($global_db_conn);
        disconnectFromDB();
    }
}

function handleJoinRequest()
{
    if (connectToDB()) {
        $tuple = array(
            ":bind1" => $_GET['BetID']
        );
        $allTuples = array($tuple);
        $cols = ["Username", "Account Balance"];
        printTable(executeBoundSQL("SELECT g.username, g.accountbalance FROM generaluser g, userplacesbet usp WHERE g.username = usp.username AND usp.BetID = :bind1", $allTuples), $cols);
        disconnectFromDB();
    }
}

function displayUserPlacesBet()
{
    if (connectToDB()) {
        $cols = ["Username", "Bet ID", "Bet Amount", "Prediction", "Odds"];
        printTable(executePlainSQL("SELECT * FROM UserPlacesBet"), $cols);
        disconnectFromDB();
    }
}


function displayDivision()
{
    if (connectToDB()) {
        $result = executePlainSQL("select * from GeneralUser g where not exists (select b.betID from Bet b where not exists (select usp.betID from UserPlacesBet usp where usp.betID = b.betID and usp.userName = g.userName))");
        printTable($result, ["Username", "Account Balance", "Email"]);
        disconnectFromDB();
    }
}

function displayBets()
{
    if (connectToDB()) {
        $cols = ["Bet ID", "Game ID", "Bet Type", "Created By"];
        printTable(executePlainSQL("SELECT * FROM Bet"), $cols);
        disconnectFromDB();
    }
}

function betExists($betID)
{
    if (connectToDB()) {
        if (oci_fetch_array(executePlainSQL('select 1 from Bet where betID =' . $betID), OCI_BOTH) !== false) {
            return true;
        } else {
            echo "Bet with betID:" . $betID . " not found";
            return false;
        }
    } else {
        return false;
    }
}

function displayNestedAggregation()
{
    if (connectToDB()) {
        executePlainSQL("
        create view temp(gameID, betTotal) as
            select b.gameID, sum(usp.betAmount) as betTotal
            from bet b, userPlacesBet usp
            where b.betID = usp.betID
            group by b.gameID
            having sum(usp.betAmount) >= 2000
	        ");
        printTable(executePlainSQL("
        select temp.gameID, avg(usp.betAmount) as betAvg
        from userPlacesBet usp, bet b, temp
        where usp.betID = b.betID and b.gameID = temp.gameID
        group by temp.GameID
        "), ["Game ID", "Average"]);
        executePlainSQL("drop view temp");
        disconnectFromDB();
    }
}

function DisplayAggregationWithGroupBy()
{
    if (connectToDB()) {
        printTable(executePlainSQL("
            select userName, max(betAmount) as maxBet
            from userPlacesBet
            group by userName
        "), ["Username", "Max Bet"]);
        disconnectFromDB();
    }
}

function displayAggregationWithHaving()
{
    if (connectToDB()) {
        printTable(executePlainSQL("
            select userName, sum(betAmount) as betTotal
            from userPlacesBet
            group by userName
            having sum(betAmount) > 500
        "), ["Username", "BetTotal"]);
        disconnectFromDB();
    }
}

if (isset($_GET['DisplayCurrUsersRequest'])) {
    displayUsers();
}

if (isset($_POST['UpdateUser']) && array_key_exists('updateUserRequest', $_POST)) {
    handleUpdateUserRequest();
}

if (isset($_GET['DisplayUserPlacesBet'])) {
    displayUserPlacesBet();
}

if (isset($_GET['DisplayDivision'])) {
    displayDivision();
}

if (isset($_GET['DisplayCurrBets'])) {
    displayBets();
}

if (isset($_POST['DeleteUser'])) {
    handleDeleteUserRequest();
}

if (isset($_GET['DisplayJoin']) && betExists($_GET['BetID'])) {
    handleJoinRequest();
}

if (isset($_GET['DisplayAggregationWithGroupBy'])) {
    displayAggregationWithGroupBy();
}

if (isset($_GET['DisplayNestedAggregationWithGroupBy'])) {
    displayNestedAggregation();
}

if (isset($_GET['DisplayAggregationWithHaving'])) {
    displayAggregationWithHaving();
}
?>