<?php
session_start();
if (isset($_SESSION['userName'])) {
    echo "Welcome, " . $_SESSION['userName'];
    echo "<br>";
    echo "CurrentAccountBalance: " . $_SESSION['accountBalance'];
    echo "<br>";
    echo "Email: " . $_SESSION['email'];
    echo '<form action="" method="post">';
    echo '    <input type="submit" value="Logout" name="Logout">';
    echo '</form>';
} else {
    echo "Please log in.";
    // display link to main.php
    echo '<a href="main.php">Back to Login Page</a>';
    echo "<br>";
}
?>

<html>
<head>
    <title>General User</title>
</head>
<body>
<hr/>
<h1>My Bets:</h1>
<p><input type="submit" value="Display" name="DisplayMyBets"></p>
<hr/>
<h1>MoneyLine Bets currently available:</h1>
<p><input type="submit" value="Display" name="DisplayAvailableBets"></p>
<hr/>
<h1>Create your bet here:</h1>
<h1>Form for MoneyLine Bet</h1>
<?php
    if (isset($_POST['Logout'])) {
        session_unset();
        session_destroy();
        header("Location: main.php");
    }
?>

</body>
</html>
