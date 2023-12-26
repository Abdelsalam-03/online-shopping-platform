<?php
    
    /**
     * redirect function
     * the base function to redirect the user 
     * params : $path => path of destination
     * returns a function that redirects the user to a specific file
     */

    function redirect($path){
        return function() use($path){
            header("location: " . $path);
            exit();
        };
    }

    /**
     * redirectToHome function
     * moves the user to the home page
     */

    $redirectToHome = redirect("home.php");

    /**
     * redirectToLogIn function
     * moves the user to the log in page
     */

    $redirectToLogIn = redirect("login.php");

    /**
     * redirectToSignUp function
     * moves the user to the sign up page
     */

    $redirectToSignUp = redirect("signup.php");

    /**
     * redirectToCheckOut function
     * moves the user to the check out page 
     */
    
    $redirectToCheckOut = redirect("checkout.php");
    
    /**
     * redirectToAddProduct function
     * moves the user to the add product page
     */

    $redirectToAddProduct = redirect("add.php");



    /**
     * read function
     * the base function to read from a file 
     * params : $fileName
     * returns a function that reads from the specific file
     */

    function read(string $fileName, array $keys = null){
        return function() use($fileName, $keys){
            if (! file_exists($fileName)) {
                return false;
            }
            $file = fopen($fileName, "r");
            $output = [];
            $line = fgetcsv($file);
            if ($keys) {
                while($line){
                    $line = array_combine($keys, $line);
                    $output[] = $line;
                    $line = fgetcsv($file);
                }
            } else {
                while($line){
                    $output[] = $line;
                    $line = fgetcsv($file);
                }
            }
            fclose($file);
            return $output;
        };
    }

    /**
     * $getUsers function
     * returns all the users as a multidimensional array, each user is an array
     * returns false if it users file not found or empty
     */

    $getUsers = read("files/users.csv");
    
    /**
     * $getProducts function
     * returns all the products as a multidimensional array, each product is an array
     * returns false if it products file not found or empty
     */

    $productsData = ["name", "category", "description", "price", "quantity"];

    $getProducts = read("files/products.csv", $productsData);

    /**
     * insert function
     * the base function to insert data on a file
     * params : $fileName
     * returns a function that writes on the specific file
     */

    function insert(string $fileName){
        return function(array $data) use($fileName){
            if (! file_exists($fileName)) {
                return false;
            }
            $file = fopen($fileName, "a");
            fputcsv($file, $data);
            fclose($file);
            return true;
        };
    }

    /**
     * $addUser function
     * function to insert user in the users file
     * params : $data => array of the user's data
     * return false if the users file not found
     * returns true if the prossess completed successfully
     */

    $addUser = insert("files/users.csv");
    
     /**
     * $addproduct function
     * function to insert product in the products file
     * params : $data => array of the product's data
     * return false if the products file not found
     * returns true if the prossess completed successfully
     */

    $addProduct = insert("files/products.csv");

?>