<?php

    $pageTitle = "Check Out";
    include("components/head.php");

    
    if(isset($_POST['customerName']) && isset($_SESSION['name'])){
        if ($_POST['customerName'] !== $_SESSION['name']) {
            $redirectToHome();
        }
    }

    $inStock = [];
    $outStock = [];
    $totalItems = 0; 
    $totalPrice = 0; 

    if (isset($_POST['checkOutRequest']) || isset($_SESSION['cart'])) {
        
        $cartContent = '';
        $customerName = '';

        if (isset($_POST['checkOutRequest'])) {
            $cartContent = $_POST['checkOutRequest'];
            $customerName = $_POST['customerName'];
        } elseif(isset($_SESSION['cart'])){
            $cartContent = $_SESSION['cart'];
            $customerName = $_SESSION['name'];
        }

        if (!isset($_SESSION['name'])) {
            $_SESSION['cart'] = $cartContent;
            $redirectToLogIn();
        }

        $cartContent = json_decode($cartContent, true);

        $cartContent = array_filter($cartContent, function($product){
            return +$product['quantity'] > 0;
        });


        $cartProductsNames = array_keys($cartContent);
        $currentProducts = $getProducts();
        foreach($cartProductsNames as $name){
            
            foreach($currentProducts as $key => $product){
                
                if ($product['name'] == $name) {
                    if (+$product['quantity'] >= +$cartContent[$name]['quantity']) {
                        $currentProducts[$key]['quantity']-= $cartContent[$name]['quantity'];
                        $price = $currentProducts[$key]['price'] * $cartContent[$name]['quantity'];
                        $totalItems+= $cartContent[$name]['quantity'];
                        $totalPrice+= $price;
                        $inStock[] = [
                            "quantity"  => $cartContent[$name]['quantity'],
                            "name"      => $name,
                            "price"     => number_format($currentProducts[$key]['price'], thousands_separator:","),
                            "total"     => number_format($price, thousands_separator:",")
                        ];
                        break;
                    } else {
                        $outStock[] = $name;
                    }
                    break;
                }
            }
        }
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    } else {
        $redirectToHome();
    }
?>

<?php if($inStock):
     
        
?>
    
<section class="invoice-container">
    <div class="invoice">
        <h2>Invoice</h2>
        <div class="head">
            <p class="name">Customer Name : <?=$_SESSION['name'] ?></p>
            <p class="date">Date : <?php echo date("Y/m/d  h:i") ?></p>
        </div>
        <table>
            <thead>
                <td>Quantity</td>
                <td>Name</td>
                <td>Price</td>
                <td>Total</td>
            </thead>
            <tbody>
                <?php foreach($inStock as $product): ?>
                    <tr>
                        <?php foreach($product as $information): ?>
                            <td><?= $information?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                <tr class="bottom">
                    <td><?= $totalItems ?></td>
                    <td></td>
                    <td></td>
                    <td><?= number_format($totalPrice, thousands_separator: ",") ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>


<?php endif; ?>

<?php if($outStock): ?>

    <?php foreach ($outStock as $item): ?>
        <p class="error"><b><?= $item ?></b> is out of stock</p>
    <?php endforeach;?>

<?php endif; ?>