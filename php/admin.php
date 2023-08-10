<?php
include 'utilities.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>304 Project</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <div class="container">
        <!-- Left 1/3rd: Admin Search -->
        <div class="left-column">
            <?php include 'userMenu.php'; ?>
            <div class="form-container">
                <form id="adminForm" method="GET" action="tablePrint.php" target="resultFrame">
                    <select class="tableselect" id="tableFrom" name="tableFrom" required size="15" onchange="updateAttributes()">
                        <!-- Attribute options will be updated dynamically by JavaScript -->
                    </select>
                    <select class="tableselect" id="attributeOptions" name="attributeOptions[]" size="8" required multiple>
                        <!-- Attribute options will be updated by the tableAttributes js -->
                    </select>
                    <br>
                    <br>
                    <button class="form-button" type="submit" name="print" value='adminSearch'>Display</button>
                </form>
            </div>
            <!-- Generated form items -->
            <?php
            $formItems = [
                ["List of current users", "DisplayCurrUsersRequest"],
                ["Lists of current Bets", "DisplayCurrBets"],
                ["Transaction list of users placing on Bets", "DisplayUserPlacesBet"],
                ["Division Operation: Find list of users that placed on every bet", "DisplayDivision"],
                ["Nested aggregation with group by: find the average amount bet on each game, but only in games where the total amount bet is greater than 2000 dollars", "DisplayNestedAggregationWithGroupBy"],
                ["Aggregation with group by: max betAmount of bet placed grouped by users", "DisplayAggregationWithGroupBy"],
                ["Aggregation with having: find users who have placed bets with a total amount more than 500", "DisplayAggregationWithHaving"],
            ];
            ?>
            <div class="form-container">
                <div class="form-header">Modify Users</div><br>
                <form method="GET" action="tablePrint.php" target="resultFrame">
                    <label for="Select Username">UserName:</label><br>
                    <input type="text" id="selectedUser" name="selectedUser"><br>
                    <br>
                    <select id='email/balance' name='attributeToChange'>
                        <option value='email'>Email</option>
                        <option value='accountBalance'>Balance</option>
                    </select>
                    <input type="text" id='input' name='newValue'>
                    <button class="form-button" type="submit" name="util" value="updateUser">Update User</button><br>
                    <button class="form-button" type="submit" name="util" value="deleteUser">Delete User</button>
                </form>
            </div>
            <?php foreach ($formItems as $item) : ?>
                <div class="form-container">
                    <div class="form-header"><?php echo $item[0]; ?></div>
                    <form method="GET" action="tablePrint.php" target="resultFrame">
                        <input type="hidden" action="<?php echo $item[1]; ?>">
                        <button class="form-button" type="submit" name='print' value=<?php echo $item[1]; ?>>Display</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <div class="form-container">
                <form method="GET" action="tablePrint.php" target="resultFrame">
                    <input type="number" name="BetID">
                    <button class="form-button" type="submit" name='print' value='DisplayJoin'>Join</button>
                </form>
            </div>
        </div>

        <!-- Right 2/3rd: tablePrint -->
        <div class="right-column">
            <iframe id="resultFrame" name="resultFrame" style="border: none; width: 100%; height: 100%;"></iframe>
        </div>
    </div>
</body>

<script>
    var tableAttributes = {
        'GeneralUser': ['UserName', 'AccountBalance', 'Email'],
        'Admin': ['UserName', 'AccountBalance', 'Email'],
        'Bet': ['BetID', 'BetType', 'UserName', 'GameID'],
        'UserPlacesBet': ['UserName', 'BetID', 'BetAmount', 'Prediction', 'CalculatedOdds'],
        'PotentialPayout': ['BetAmount', 'InitialOdds', 'PotentialPayout'],
        'Certifies': ['UserName', 'BetID', 'AdminVig'],
        'Team': ['TeamID', 'FullName', 'City'],
        'TeamAbbreviation': ['FullName', 'Abbreviation'],
        'Player': ['PlayerID', 'TeamID', 'InjuryStatus'],
        'Game': ['GameID', 'ScoreHome', 'ScoreAway', 'GameDate', 'HomeTeamID', 'AwayTeamID'],
        'TeamPlays': ['GameID', 'TeamID'],
        'PlayerPlays': ['PlayerID', 'GameID', 'Minutes', 'Points', 'Assists', 'Rebounds'],
        'Spread': ['BetID', 'GameID', 'UserName', 'Status', 'TotalPool', 'TotalVig', 'ScoreDifference', 'Odds'],
        'OverUnder': ['BetID', 'GameID', 'UserName', 'Status', 'TotalPool', 'TotalVig', 'TotalScore', 'Odds'],
        'MoneyLine': ['BetID', 'GameID', 'UserName', 'Status', 'HomeTeam', 'AwayTeam', 'HomeTeamOdds', 'AwayTeamOdds']
    };

    // Function to populate the tableFrom options
    function populateTableFromOptions() {
        var tableFromSelect = document.getElementById("tableFrom");

        tableFromSelect.innerHTML = "";
        for (var table in tableAttributes) {
            var option = document.createElement("option");
            option.value = table;
            option.text = table;
            tableFromSelect.appendChild(option);
        }
    }

    // Call the function to populate tableFrom options on page load
    populateTableFromOptions();

    function updateAttributes() {
        var selectedTable = document.getElementById("tableFrom").value;
        var attributeOptions = document.getElementById("attributeOptions");

        attributeOptions.innerHTML = "";
        for (var i = 0; i < tableAttributes[selectedTable].length; i++) {
            var attribute = tableAttributes[selectedTable][i];
            var option = document.createElement("option");
            option.value = attribute;
            option.text = attribute;
            attributeOptions.appendChild(option);
        }
    }
</script>

</html>