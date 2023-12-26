<?php 

    $pageTitle = "Log In";
    $landingPage = true;
    include("components/head.php");

?>

<section class="auth-form form">
    <form action="login.php" method="post">
        <h2>Log In</h2>
        <div class="item">
            <label for="log-u-n">User Name</label>
            <input type="text" name="name" id="log-u-n">
        </div>
        <div class="item">
            <label for="log-p">Password</label>
            <input type="password" name="password" id="log-p">
        </div>
        <input type="submit" value="Log In" name="loggedIn">
        <p>Didn't have an account? <a href="signup.php">Sign Up.</p></a>

<?php
    if (isset($_SESSION['name'])) {
        $redirectToHome();
    }
    if (isset($_POST["loggedIn"])) {
        ["name" => $userName, "password" => $password] = $_POST;
        if (! empty($userName) && ! empty($password)) {
            $users = $getUsers();

            $userExist = false;
            $correctPassword = false;
            $isAdmin = false;
            foreach($users as $user){
                if ($user[0] == $userName) {
                    $userExist = true;
                    $correctPassword = password_verify($password, $user[1]);
                    if (isset($user[2])) {
                        $isAdmin = true;
                        if ($user[2] === "manager") {
                            $isManager = true;
                        }
                    }
                    break;
                }
            }
            if ($userExist && $correctPassword) {
                
                if ($isAdmin) {
                    $_SESSION["admin"] = true;
                }
                if ($isManager) {
                    $_SESSION["manager"] = true;
                }
                $_SESSION["name"] = $userName;
                if (isset($_SESSION['cart'])) {
                    $redirectToCheckOut();
                } else{
                    $redirectToHome();
                }
            } elseif($userExist) {
                echo "<p class='warning'>Wrong Password</p>";
            } else {
                echo "<p class='warning'>user Name not exist</p>";
            }
        } else {
            echo "<p class='warning'>Please Fill All the Fields</p>";
        }
    }

?>

    </form>
</section>