<?php 

    $pageTitle = "Add Product";
    include("components/head.php");
    if (! isset($_SESSION['name']) || ! isset($_SESSION['admin'])) {
        $redirectToLogIn();
    }


?>

<section>
    <section class="form">
        <form action="" method="post">
            <h2>Create Product</h2>
            <div class="item">
                <label for="name">Name</label>
                <input type="text" name="name" id="name">
            </div>
            <div class="item">
                <label for="category">Category</label>
                <input type="text" name="category" id="category">
            </div>
            <div class="item">
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10"></textarea>
            </div>
            <div class="item">
                <label for="price">Price</label>
                <input type="text" name="price" id="price">
            </div>
            <div class="item">
                <label for="quantity">Quantity</label>
                <input type="text" name="quantity" id="quantity">
            </div>
            <input type="submit" value="Add" name="added">
        </form>
    </section>
</section>

<?php

    if (isset($_POST['added'])) {
        
        ["category" => $category, "name" => $name, "description" => $description, "price" => $price, "quantity" => $quantity] = $_POST;
        
        if (!empty($name) && !empty($category) && !empty($description) && !empty($price) && !empty($quantity)) {
            $product = [$name, $category, $description, $price, $quantity];
            $addProduct($product);
            echo "<p class='success'>Successfully Added One Product</p>";
        }

    }

?>