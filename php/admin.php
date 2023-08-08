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
                    <form id="adminForm" method="POST" action="admin.php">
                        <select id="tableFrom" name="tableFrom" required size="8">
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
                            <option value="" disabled selected>Select a table</option>
                            <!-- Attributes will be dynamically populated based on table selection -->
                        </select>
                        <br>
                        <br>
                        <input type="hidden" id="AdminSearchRequest" name="AdminSearchRequest">
                        <input type="submit" value="Display" name="AdminSearch">
                    </form>

                </div>
            </fieldset>
            <fieldset>
                <legend>Lists of current Bets</legend>
                <form action="admin.php" method="GET">
                    <p><input type="submit" value="Display" name="DisplayCurrBets"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Transaction list of users placing on Bets</legend>
                <form action="admin.php" method="GET">
                    <p><input type="submit" value="Display" name="DisplayUserPlacesBet"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Update user email/accountBalance</legend>
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
            </fieldset>
            <fieldset>
                <legend>Delete users and bets created by that user, any placement of that bet will be deleted as well </legend>
                <form action="admin.php" method="post">
                    <!--    should on cascade delete-->
                    <label for="username">UserName to Delete:</label>
                    <input type="text" id="usernameToDelete" name="UsernameToDelete">
                    <input type="submit" value="Delete User" name="DeleteUser">
                </form>
            </fieldset>
            <fieldset>
                <legend>Join: Find name and accountBalance of all users who placed on a specific bet</legend>
                <form action="admin.php" method="GET">
                    <label for="betID">BetID:</label>
                    <input type="number" id="betID" name="BetID" required>
                    <p><input type="submit" value="Display" name="DisplayJoin"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Division Operation: Find list of users that placed on every bet</legend>
                <form action="admin.php" method="GET">
                    <p><input type="submit" value="Display" name="DisplayDivision"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Nested aggregation with group by: find the average amount bet on each game, but only in games where the total amount
                    bet is greater than 2000 dollars</legend>
                <form action="admin.php" method="GET">
                    <p><input type="submit" value="Display" name="DisplayNestedAggregationWithGroupBy"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Aggregation with group by: max betAmount of bet placed grouped by users</legend>
                <form action="admin.php" method="GET">
                    <p><input type="submit" value="Display" name="DisplayAggregationWithGroupBy"></p>
                </form>
            </fieldset>
            <fieldset>
                <legend>Aggregation with having: find users who have placed bets with a total amount more than 500</legend>
                <form action="admin.php" method="GET">
                    <p><input type="submit" value="Display" name="DisplayAggregationWithHaving"></p>
                </form>
            </fieldset>
        </div>
        <!-- Right 2/3rd: Functions -->
        <div style="width: 66%; padding-left: 20px;">
            <?php include 'adminFunctions.php'; ?>
        </div>
    </div>
</body>

</html>