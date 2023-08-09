<!DOCTYPE html>
<html>

<head>
    <title>304 Project</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <div style="display: flex;">
        <!-- Left 1/3rd: Admin Search -->
        <div class="container" style="width: 33%;">
            <fieldset>
                <legend>Admin Search</legend>
                <div id="adminSearchContainer">
                    <form id="adminForm" method="GET" action="adminFunctions.php" target="resultFrame">
                        <select id="tableFrom" name="tableFrom" required size="8" onchange="updateAttributes()">
                            <option value="GeneralUser" selected>GeneralUser</option>
                            <option value="Bet">Bet</option>
                            <option value="Team">Team</option>
                            <option value="Player">Player</option>
                            <option value="Game">Game</option>
                            <option value="Spread">Spread</option>
                            <option value="OverUnder">OverUnder</option>
                            <option value="MoneyLine">MoneyLine</option>
                        </select>
                        <select id="attributeOptions" name="attributeOptions[]" required multiple size="8">
                            <!-- Attribute options will be updated dynamically by JavaScript -->
                        </select>
                        <br>
                        <br>
                        <input type="hidden" id="AdminSearchRequest" name="AdminSearchRequest">
                        <input type="submit" value="Display" name="AdminSearch">
                    </form>
                </div>
            </fieldset>
            <fieldset>
                <legend>List of current users</legend>
                <form method="GET" action="adminFunctions.php" target="resultFrame">
                    <p><input type="submit" value="Display" name="DisplayCurrUsersRequest"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Lists of current Bets</legend>
                <form method="GET" action="adminFunctions.php" target="resultFrame">
                    <p><input type="submit" value="Display" name="DisplayCurrBets"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Transaction list of users placing on Bets</legend>
                <form method="GET" action="adminFunctions.php" target="resultFrame">
                    <p><input type="submit" value="Display" name="DisplayUserPlacesBet"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Update user email/accountBalance</legend>
                <form method="GET" action="adminFunctions.php" target="resultFrame">
                    <input type="hidden" id="updateUserRequest" name="updateUserRequest">
                    <label for="usernameToUpdate">Username of user to update:</label>
                    <input type="text" id="usernameToUpdate" name="usernameToUpdate" placeholder="userName">
                    <label for="attributeToChange">Enter attribute to Change:</label>
                    <input type="text" id="attributeToChange" name="attributeToChange" placeholder="Enter Email/AccountBalance">
                    <label for="newValue">Enter new value</label>
                    <input type="text" id="newValue" name="newValue" placeholder="newValue">
                    <input type="submit" value="Update User" name="UpdateUser">
                </form>
            </fieldset>
            <fieldset>
                <legend>Delete users and bets created by that user, any placement of that bet will be deleted as well </legend>
                <form method="GET" action="adminFunctions.php" target="resultFrame">
                    <!--    should on cascade delete-->
                    <label for="username">UserName to Delete:</label>
                    <input type="text" id="usernameToDelete" name="UsernameToDelete">
                    <input type="submit" value="Delete User" name="DeleteUser">
                </form>
            </fieldset>
            <fieldset>
                <legend>Join: Find name and accountBalance of all users who placed on a specific bet</legend>
                <form method="GET" action="adminFunctions.php" target="resultFrame">
                    <label for="betID">BetID:</label>
                    <input type="number" id="betID" name="BetID" required>
                    <p><input type="submit" value="Display" name="DisplayJoin"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Division Operation: Find list of users that placed on every bet</legend>
                <form method="GET" action="adminFunctions.php" target="resultFrame">
                    <p><input type="submit" value="Display" name="DisplayDivision"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Nested aggregation with group by: find the average amount bet on each game, but only in games where the total amount
                    bet is greater than 2000 dollars</legend>
                    <form method="GET" action="adminFunctions.php" target="resultFrame">
                    <p><input type="submit" value="Display" name="DisplayNestedAggregationWithGroupBy"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Aggregation with group by: max betAmount of bet placed grouped by users</legend>
                <form method="GET" action="adminFunctions.php" target="resultFrame">
                    <p><input type="submit" value="Display" name="DisplayAggregationWithGroupBy"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Aggregation with having: find users who have placed bets with a total amount more than 500</legend>
                <form method="GET" action="adminFunctions.php" target="resultFrame">
                    <p><input type="submit" value="Display" name="DisplayAggregationWithHaving"></p>
                </form>
            </fieldset>
        </div>
        <!-- Right 2/3rd: Functions -->
        <div style="width: 66%; padding-left: 20px;">
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