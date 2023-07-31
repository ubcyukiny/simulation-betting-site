<html> 
  <head>
    <title>Home Page</title> 
  </head>
  <body> 
    <h1>NBA Betting</h1> 
    <p>Let's win some money!</p>
    <hr/>
    <h1>Sign up here</h1>
    <form action="generalUser.php" method="post">
        <!--    should add the account to database
                should check if user is registered already
                createUser logic
        -->
        <label for="username">Username:</label>
        <input type="text" id="newUsername" name="username" placeholder="Enter your username">

        <label for="email">Email:</label>
        <input type="email" id="newEmail" name="email" placeholder="Enter your email">

        <input type="submit" value="Submit">
    </form>
    <hr/>
    <h1>Login here</h1>
    <form action="generalUser.php" method="post">
        <!--   popup if user not found -->
        <!--   add login logic -->
        <!--   should login with correct accountBalance, email-->
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your username">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email">

        <input type="submit" value="Submit">
    </form>
    <hr/>
    <h1>Click here to view as admin</h1>
    <form action="admin.php" method="post">
        <input type="submit" value="LoginAsAdmin">
    </form>
  </body>
</html>
