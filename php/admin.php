<html>

<head>
    <title>304 Project</title>
    <script>

        // Function to update attributes dropdown based on the selected table
        function updateAttributes() {
            var selectedTable = document.getElementById('tableFrom').value;
            var attributeOptions = document.getElementById('attributeOptions');

            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configure the AJAX request
            xhr.open('GET', 'get_attributes.php?table=' + encodeURIComponent(selectedTable), true);

            // Debug: Log the URL being requested
            console.log('Request URL:', xhr.url);

            // Define the callback function when the request completes
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    // Debug: Log the response status and text
                    console.log('Response Status:', xhr.status);
                    console.log('Response Text:', xhr.responseText);

                    if (xhr.status === 200) {
                        // Clear existing options
                        attributeOptions.innerHTML = '';

                        // Parse the response and add attributes to the dropdown
                        var attributes = JSON.parse(xhr.responseText);
                        for (var i = 0; i < attributes.length; i++) {
                            var option = document.createElement('option');
                            option.value = attributes[i];
                            option.text = attributes[i];
                            attributeOptions.appendChild(option);
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
            document.getElementById('tableFrom').addEventListener('change', updateAttributes);
        });
    </script>
</head>

<body>
    <h1>This is the admin page</h1>
    <h1>Lists of current users</h1>
    <form method="GET" action="admin.php"> <!--refresh page when submitted-->
        <input type="hidden" id="DisplayCurrUsersRequest" name="DisplayCurrUsersRequest">
        <input type="submit" value="Display" name="DisplayCurrUsers">
    </form>
    <hr />
    <h1>Admin Search</h1>
    <form id="adminForm" method="POST" action="admin.php"> <!--refresh page when submitted-->
        <select id="tableFrom" name="tableFrom" required size="8">
            <option value="GeneralUser">GeneralUser</option>
            <option value="Bet">Bet</option>
            <option value="Team">Team</option>
            <option value="Player">Player</option>
            <option value="Game">Game</option>
            <option value="Spread">Spread</option>
            <option value="OverUnder">OverUnder</option>
            <option value="MoneyLine">MoneyLine</option>
        </select>
        <select id="attributeOptions" name="attributeOptions" required multiple size="8">
            <option value="" disabled selected>Select a table</option>
            <!-- Attributes will be dynamically populated based on table selection -->
        </select>
        <br>
        <input type="hidden" id="AdminSearchRequest" name="AdminSearchRequest">
        <input type="submit" value="Display" name="AdminSearch">
    </form>
    <?php

    ?>
    <hr />
    <h1>Lists of current Bets</h1>
    <form action="admin.php" method="GET">
        <p><input type="submit" value="Display" name="DisplayCurrBets"></p>
    </form>
    <hr />
    <h1>Transaction list of users placing on Bets</h1>
    <form action="admin.php" method="GET">
        <p><input type="submit" value="Display" name="DisplayUserPlacesBet"></p>
    </form>
    <hr />
    <h1>Update user email/accountBalance</h1>
    <form action="admin.php" method="post">
        <input type="hidden" id="updateUserRequest" name="updateUserRequest">
        <label for="usernameToUpdate">Username of user to update:</label>
        <input type="text" id="usernameToUpdate" name="usernameToUpdate" placeholder="userName">
        <label for="attributeToChange">Enter attribute to Change:</label>
        <input type="text" id="attributeToChange" name="attributeToChange" placeholder="Enter Email/AccountBalance">
        <label for="newValue">Enter new value</label>
        <input type="text" id="newValue" name="newValue" placeholder="newValue">
        <input type="submit" value="Update User" name="UpdateUser">
    </form>
    <hr />
    <h1>Delete users and bets created by that user, any placement of that bet will be deleted as well </h1>
    <form action="admin.php" method="post">
        <!--    should on cascade delete-->
        <label for="username">UserName to Delete:</label>
        <input type="text" id="usernameToDelete" name="UsernameToDelete">
        <input type="submit" value="Delete User" name="DeleteUser">
    </form>
    <hr />
    <h1>Join: Find name and accountBalance of all users who placed on a specific bet</h1>
    <form action="admin.php" method="GET">
        <label for="betID">BetID:</label>
        <input type="number" id="betID" name="BetID" required>
        <p><input type="submit" value="Display" name="DisplayJoin"></p>
    </form>
    <hr />
    <h1>Division Operation: Find list of users that placed on every bet</h1>
    <form action="admin.php" method="GET">
        <p><input type="submit" value="Display" name="DisplayDivision"></p>
    </form>
    <hr />
    <h1>Nested aggregation with group by: find the average amount bet on each game, but only in games where the total amount
        bet is greater than 2000 dollars</h1>
    <form action="admin.php" method="GET">
        <p><input type="submit" value="Display" name="DisplayNestedAggregationWithGroupBy"></p>
    </form>
    <hr />
    <h1>Aggregation with group by: max betAmount of bet placed grouped by users</h1>
    <form action="admin.php" method="GET">
        <p><input type="submit" value="Display" name="DisplayAggregationWithGroupBy"></p>
    </form>
    <hr />
    <h1>Aggregation with having: find users who have placed bets with a total amount more than 500</h1>
    <form action="admin.php" method="GET">
        <p><input type="submit" value="Display" name="DisplayAggregationWithHaving"></p>
    </form>
    <hr />

    <?php
    include 'functions.php';

    function displayUsers()
    {
        if (connectToDB()) {
            printToConsole("I tried");
            printTable(executePlainSQL("SELECT * FROM GeneralUser"), ["Username", "Account Balance", "Email"]);
            disconnectFromDB();
        }
    }

    function adminSearch()
    {
        if (connectToDB()) {
            $selectedTable = $_POST['tableFrom'];
            printToConsole($selectedTable);
            $selectedAttributes = "";
            foreach ($_POST['attributeOptions'] as $attribute) {
                $selectedAttributes = $selectedAttributes . ", " . $attribute;
            }
            $selectedAttributes = ltrim($selectedAttributes, ", ");
            printToConsole($selectedAttributes);
            printTable(executePlainSQL("SELECT " . $selectedAttributes . " FROM " . $selectedTable));
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

    if (isset($_POST['AdminSearch'])) {
        adminSearch();
    }
    ?>
</body>

</html>