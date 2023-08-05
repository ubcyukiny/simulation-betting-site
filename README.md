# project_a2o8f_d0o7w_o9j3b

[Go to demo](https://www.students.cs.ubc.ca/~yukiny/main.php)

## Demo Checklist:

### Done:
- **Insert Operation:** User can sign up new accounts, admin can view a list of users including newly signed up ones
- **Update Operation:** Admin can update user email/accountBalance
- **Division:** Find users who placed on every bet
- **Delete Operation:** On cascade delete, admin can delete users; bets created or placed by that user should be deleted as well, any placement on deleted-user created bet will be removed as well
- **Join:** Find name and accountBalance of all users who placed on a specific bet


### TODO:
- **Selection:** User must have the ability to choose which table and which attributes to select. At least 2 tables and 2 attributes to select. Selection conditions:
    - Games before a certain date or total score > userInputValue
    - Bet with odds > some value, or bets with a userInputGameID (gameID)<br><br>
- **Projection:** Perform projection on the moneylineBet table, selecting 3-4 attributes from (userName, BetID, betAmount, Prediction, CalculatedOdds).<br><br>
- **Aggregation with GROUP BY:** Calculate the maximum bet amount placed, grouped by users for display in the admin page. No user input is needed for this operation.<br><br>
- **Aggregation with HAVING:** Identify bets placed by more than one user, find the maximum bet amount placed, and group the results by user for display in the admin page. No user input is needed for this operation.<br><br>
- **Nested Aggregation with GROUP BY:** Find the average amount bet on each game, but only consider games where the total amount bet is greater than $2000.<br><br>
- **SQL Scripts:** Combine createTable and insert SQL scripts into a single runnable script. Add missing tables and data (at least 10 entries for each table), ensuring that the queries have non-trivial answers for aggregations and divisions.<br><br>
- **Final Report:** Produce a PDF report including:
    - Description of the final project.
    - Differences in the final schema and explanations for these changes.
    - A list of all SQL queries used in the project.
    - Screenshots for required queries.

