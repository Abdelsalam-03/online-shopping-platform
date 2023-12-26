<?php 

    $pageTitle = "Sign Up";
    $landingPage = true;
    include("components/head.php");
    if (isset($_SESSION['name'])) {
        $redirectToHome();
    }
?>

<section class="auth-form form">
    <form action="signup.php" method="post">
        <h2>Sign Up</h2>
        <div class="item">
            <label for="sign-u-n">User Name</label>
            <input type="text" name="name" id="sign-u-n" required="required">
        </div>
        <div class="item">
            <label for="sign-p">Password</label>
            <input type="password" name="password" id="sign-p" required="required">
        </div>
        <div class="item">
            <label for="sign-p-c">Confirm Password</label>
            <input type="password" name="passwordConfirm" id="sign-p-c" required="required">
        </div>
        <input type="submit" value="Sign Up" name="signedUp">
        <p>Already have an account? <a href="login.php">Log In.</p></a>

<?php

    if (isset($_POST["signedUp"])) {
        ["name" => $userName, "password" => $password, "passwordConfirm" => $passwordConfirm] = $_POST;
        if (! empty($userName) && ! empty($password) && ! empty($passwordConfirm)) {
            if ($password === $passwordConfirm) {
                $users = $getUsers();

                $userExist = false;
                foreach($users as $user){
                    if ($user[0] == $userName) {
                        $userExist = true;
                        break;
                    }
                }
                if ($userExist) {
                    echo "<p class='warning'>Sorry this user name is exist!</p>";
                } else {
                    $password = password_hash($password, PASSWORD_BCRYPT);
                    $data = [$userName, $password];
                    $addUser($data);
                    $_SESSION['name'] = $userName;
                    if (isset($_SESSION['cart'])) {
                        $redirectToCheckOut();
                    }
                    $redirectToHome();
                }
            }
        } else {
            echo "<p class='warning'>Please Fill All the Fields</p>";
        }
    }

?>
    </form>
</section>