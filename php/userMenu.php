<div class="form-container">
    <?php
    if (isset($_SESSION['userName'])) {
        echo "<div class='welcome-message'>Welcome, " . $_SESSION['userName'] . "</div>";
        echo "<div class='account-balance'>Current Account Balance: " . $_SESSION['accountBalance'] . "</div>";
        echo "<div class='email'>Email: " . $_SESSION['email'] . "</div>";
        echo '<form action="main.php" method="post">';
        echo '<input class="form-button logout-button" type="submit" value="Logout" name="Logout">';
        echo '</form>';
    } else {
        echo "<div class='welcome-message'>Please log in.</div>";
        echo "<a href='main.php' class='login-link'>Back to Login Page</a>";
    }
    ?>
</div>