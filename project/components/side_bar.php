<?php

    if (!isset($pageTitle)) {
        header("Location: ../home.php");
    }

?>

<aside>
    <h3>Categories</h3>
    <ul class="controls">
        <li><button>ALL</button></li>

        <?php foreach ($categories as $category): ?>
            
            <li><button value="<?= $category ?>"><?= strtoupper($category) ?></button></li>
            
        <?php endforeach ; ?>
    </ul>
</aside>