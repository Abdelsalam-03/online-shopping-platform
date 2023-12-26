<?php 

    $pageTitle = "Add Admin";
    include("components/head.php");
    if (! isset($_SESSION['name']) || ! isset($_SESSION['manager'])) {
        $redirectToHome();
    }
?>

<section class="auth-form form">
    <form action="addadmin.php" method="post">
        <h2>Add Admin</h2>
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
        <input type="submit" value="Add" name="signedUp">

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
                    $data = [$userName, $password, "admin"];
                    $addUser($data);
                    echo "<p class='success'>Successfully Added 1 Admin</p>";
                }
            }
        } else {
            echo "<p class='warning'>Please Fill All the Fields</p>";
        }
    }

?>
    </form>
</section>