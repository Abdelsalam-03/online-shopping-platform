<?php

    if (!isset($pageTitle)) {
        header("Location: ../home.php");
    }


?>
<header>
    <div class="container">
        <nav class="links">
           <a href="home.php" class="<?php echo $pageTitle === "Home"?"active": "" ?>">Home</a>
        <?php if(isset($_SESSION['admin'])): ?>  
            <a href="add.php" class="<?php echo $pageTitle === "Add Product"?"active": "" ?>">Create Product</a>
        <?php endif;?>
        <?php if(isset($_SESSION['manager'])): ?>
            <a href="addadmin.php" class="<?php echo $pageTitle === "Add Admin"?"active": "" ?>">Add Admin</a>
        <?php endif; ?>
        <?php if (! isset($_SESSION["name"])) : ?>
            <a href="login.php" class="<?php echo $pageTitle === "Log In"?"active": "" ?>">Log In</a>
            <a href="signup.php" class="<?php echo $pageTitle === "Sign Up"?"active": "" ?>">Sign Up</a>
        <?php else: ?>
            <a href="logout.php">Log Out</a>
        <?php endif; ?>
        </nav>
    <?php if ($pageTitle == "Home"): ?>
        <div class="searchBar">
            <input type="text" name="" id="search">
            <button class="brand-color-red">Search</button>
        </div>
        <div class="cart" count="0">
            <i class="fas fa-cart-shopping open"></i>
            <section class="cart-body">
                <i class="fas fa-close close"></i>
                <div class="total"></div>
                <div class="lables item">
                    <p class="name">Item</p>
                    <p class="name">Quantity</p>
                    <p class="price">Price</p>
                </div>
                <div class="content"></div>
                <div class="purchace">
                    <button id="view-checkout">Check Out</button>
                    <button id="view-preview">Review</button>
                </div>
            </section>
        </div>
    <?php endif; ?>
    
    </div>
</header>