
<html>
<head>
    <title>304 Project</title>
</head>
<body>
<h1>This is the admin page</h1>
<h1>Lists of current users</h1>
<p><input type="submit" value="Display" name="DisplayCurrUsers"></p>
<hr/>
<h1>Lists of current Bets</h1>
<p><input type="submit" value="Display" name="DisplayCurrBets"></p>
<hr/>
<h1>Lists of users placing on Bets</h1>
<p><input type="submit" value="Display" name="DisplayUserPlaceBets"></p>
<hr/>
<h1>Delete users here</h1>

<form action="adminView.html" method="post">
    <!--    should on cascade delete-->
    <label for="username">UserName to Delete:</label>
    <input type="text" id="username" name="username" placeholder="userName here">
    <input type="submit" value="Submit">
</form>

</body>
</html>

