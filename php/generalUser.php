<?php
include 'utilities.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>General User</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="left-column">
            <?php include 'userMenu.php'; ?>
            <div class="form-container">
                <form method="POST" action="tablePrint.php" target="resultFrame">
                    <h1 class="section-title">Display games (for createBet)</h1>
                    <p><input class="form-button" type="submit" value="Display Games" name="DisplayGames"></p>
                </form>
            </div>
            <div class="form-container">

                <form method="POST" action="generalUser.php">
                    <p><input class="form-button" type="submit" value="Filter Games" name="FilterGames"></p>
                    <label for="TeamNameFilter">Enter Team Name:</label>
                    <p><input type="text" id="TeamNameFilter" name="TeamNameFilter" required></p>
                </form>

            </div>
            <div class="form-container">
                <h1 class="section-title">Display current moneyline bets:</h1>
                <form method="POST" action="tablePrint.php" target="resultFrame">
                    <label for="filterMoney">Show:</label><br>
                    <select id="filterMoney" name="filterMoney[]" size="7" multiple>
                        <option value="BETID">Bet ID</option>
                        <option value="GAMEID">Game ID</option>
                        <option value="USERNAME" selected>Username</option>
                        <option value="HOMETEAM" selected>Home Team</option>
                        <option value="AWAYTEAM" selected>Away Team</option>
                        <option value="HOMETEAMODDS" selected>Home Team Odds</option>
                        <option value="AWAYTEAMODDS" selected>Away Team Odds</option>
                    </select><br>
                    <p><input class="form-button" type="submit" value="Display MoneyLine bets" name="DisplayAvailableBets"></p>
                </form>
            </div>
            <div class="form-container">
                <h1 class="section-title">Place your bet here:</h1>
                <h1 class="section-subtitle">You cannot place the same bet twice</h1>
                <form method="POST" action="tablePrint.php" target="resultFrame">
                    <label for="betId">Bet ID:</label>
                    <input type="number" id="betId" name="BetID" required><br>

                    <label for="betAmount">Bet Amount:</label>
                    <input type="number" id="betAmount" name="BetAmount" required><br>

                    <label for="prediction">Prediction: (Home/Away)</label>
                    <select id="prediction" name="Prediction" required>
                        <option value="Home">Home</option>
                        <option value="Away">Away</option>
                    </select><br>

                    <p><input class="form-button" type="submit" value="Place bet" name="PlaceBet"></p>
                </form>
            </div>
            <div class="form-container">

                <h1 class="section-title">Create your bet here:</h1>
                <h1 class="section-subtitle">Form for MoneyLine Bet</h1>
                <form method="POST" action="tablePrint.php" target="resultFrame">
                    <label for="betId">Bet ID:</label>
                    <input type="number" id="betId" name="BetID" required><br>

                    <label for="gameId">Game ID:</label>
                    <input type="number" id="gameId" name="GameID" required><br>

                    <label for="homeTeamOdds">Home Team Odds:</label>
                    <input type="number" id="homeTeamOdds" name="HomeTeamOdds" required><br>

                    <label for="awayTeamOdds">Away Team Odds:</label>
                    <input type="number" id="awayTeamOdds" name="AwayTeamOdds" required><br>

                    <input class="form-button" type="submit" value="Submit Bet" name="CreateNewMoneyLineBet">
                </form>
            </div>
        </div>
        <!-- Right 2/3rd: Functions -->
        <div class="right-column">
            <iframe id="resultFrame" name="resultFrame" style="border: none; width: 100%; height: 100%;"></iframe>
        </div>
    </div>
</body>

</html>