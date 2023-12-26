<?php

    $pageTitle = "Home";
    include("components/head.php");


    $isAdmin = isset($_SESSION['admin']);

    $products = $getProducts();

    
    $categories = [];
    
    foreach($products as $product){
        $categories[] = $product["category"];
    }
    
    $categories = array_unique($categories);
?>
<script>
    const USERNAME = "<?= isset($_SESSION['name'])? $_SESSION['name']: "default" ?>" ;
    const PRODUCTS = <?php echo json_encode($products); ?>;
</script>
<div class="back-ground-container">
    <section class="container controller">
        <form action="" method="post">
            <label for="from">Price Range From</label>
            <input type="number" name="from" id="from" use="range">
            <label for="to">To</label>
            <input type="number" name="to" id="to" use="range">
            <label for="asc">Ascending</label>
            <input type="radio" name="order" id="asc" value="1">
            <label for="dsc">Descending</label>
            <input type="radio" name="order" id="dsc" value="2">
            <input type="radio" name="order" value="0" checked style="display: none;">
        </form>
    </section>
</div>
<main class="container">

    <?php 
    
        include("components/side_bar.php");
        
    ?>

</main>
























<?php 

    include("components/footer.php");

?>