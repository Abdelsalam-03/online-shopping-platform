<?php

    include('utilities/configuration.php');

    if (!isset($pageTitle)) {
        header("Location: ../home.php");
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Store<?= " - $pageTitle" ?>
    </title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.min.css">
</head>
<body>
    <?php 
    
        include("utilities/functions.php");
        
        if (! isset($landingPage)) {
            include("components/nav_bar.php");
        }

    ?>
