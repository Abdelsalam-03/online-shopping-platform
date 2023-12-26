// variables
const CARTNAME = USERNAME + "Cart";
let currentDisplay = PRODUCTS;
let cartContent = undefined;

// components
let mainContainer = document.querySelector("main.container");
let controlFields = document.querySelectorAll(".controller input");
let minRange = document.querySelector(".controller #from");
let maxRange = document.querySelector(".controller #to");
let categoriesButtons = document.querySelectorAll("aside .controls button");
let cartElement = document.querySelector(".cart");
let openCart = cartElement.querySelector(".open");
let closeCart = cartElement.querySelector(".close");
let viewPreview = document.getElementById("view-preview");
let goToCheckOut = document.getElementById("view-checkout");
let searchBar = document.getElementById("search");
let searchButton = document.querySelector("#search + button");
let totalElements = 0;

/**
 * Components section
 */

// preview order component

function createPreviewComponent() {
    preview = document.createElement("div");
    preview.classList.add("preview");
    document.body.appendChild(preview);
    content = document.createElement("div");
    content.classList.add("content");
    preview.appendChild(content);
    head = document.createElement("div");
    head.classList.add("head");
    lastPrice = document.createElement("p");
    head.appendChild(lastPrice);
    items = document.createElement("table");
    items.classList.add("items");
    thead = document.createElement("thead");
    itemName = document.createElement("td");
    quantity = document.createElement("td");
    price = document.createElement("td");
    itemName.innerHTML = "Name";
    quantity.innerHTML = "Quantity";
    price.innerHTML = "Price";
    thead.appendChild(itemName);
    thead.appendChild(quantity);
    thead.appendChild(price);
    items.appendChild(thead);
    tbody = document.createElement("tbody");
    items.appendChild(tbody);
    operations = document.createElement("div");
    operations.classList.add("operations");
    cancelOrder = document.createElement("button");
    cancelOrder.innerHTML = "Cancel Order";
    cancelOrder.classList.add("cancel");
    resumeOrder = document.createElement("button");
    resumeOrder.innerHTML = "Resume Shopping";
    resumeOrder.classList.add("resume");
    confirmOrder = document.createElement("button");
    confirmOrder.innerHTML = "Confirm Order";
    confirmOrder.classList.add("confirm");
    operations.appendChild(cancelOrder);
    operations.appendChild(resumeOrder);
    operations.appendChild(confirmOrder);

    resumeOrder.addEventListener("click", ()=>{
        document.querySelector(".preview").style.cssText = "display: none";
    });
    
    cancelOrder.addEventListener("click", ()=>{
        document.querySelector(".preview").style.cssText = "display: none";
        emptyCart();
    });

    confirmOrder.addEventListener("click", ()=>{
        document.querySelector(".preview").style.cssText = "display: none";
        doCheckOut();
    });

    content.appendChild(head);
    content.appendChild(items);
    content.appendChild(operations);

}


/**
 * Templates section
 */

// create the product template node

let template = document.createElement("div");
template.classList.add("product");
let data = ["name", "category", "description", "price"];
data.forEach(element => {
    paragraph = document.createElement("p");
    paragraph.classList.add(element);
    paragraph.setAttribute("value", element);
    template.appendChild(paragraph);
});
paragraph = undefined;
data = undefined;

// create the cart product element template node

let cartItem = document.createElement("div");
cartItem.classList.add("item");
itemName = document.createElement("p");
itemName.classList.add("name");
cartItem.appendChild(itemName);
adjustPrice = document.createElement("input");
adjustPrice.setAttribute("type", "number");
adjustPrice.setAttribute("min", "0");
cartItem.appendChild(adjustPrice);
itemPrice = document.createElement("p");
itemPrice.classList.add("price");
cartItem.appendChild(itemPrice);

itemPrice = undefined;
adjustPrice = undefined;
itemName = undefined;


// Start script

if (window.localStorage[CARTNAME]) {
    cartContent = JSON.parse(window.localStorage[CARTNAME]);
} else {
    cartContent = {};
}

Object.keys(cartContent).forEach(key =>{
    totalElements+= cartContent[key]['quantity'];
});

printProducts(PRODUCTS);

fillCartContent();

createPreviewComponent();

// End script

// Start events 
controlFields.forEach(field =>{

    field.addEventListener("change", ()=>{
        printProducts(controlProducts(currentDisplay));
    });

});

categoriesButtons.forEach(button =>{

    button.addEventListener("click", ()=> {
        value = getAttribute(button, "value");
        if (value == "") {
            currentDisplay = PRODUCTS;
        } else {
            currentDisplay = PRODUCTS.filter((product) => {
                return product['category'] === value;
            });
        }
        otuput = controlProducts(currentDisplay);
        printProducts(otuput);
    });

});

viewPreview.addEventListener("click", ()=>{
    if (calcTotalPrice() > 0) {
        fillPreview();
        document.querySelector(".preview").style.cssText = "display: flex";
    } else {
        console.log("no content on the cart");
    }
});

goToCheckOut.addEventListener("click", ()=>{
    doCheckOut();
});

searchButton.addEventListener("click", ()=>{
    if (searchBar.value != "") {
        
        searchVlue =  searchBar.value ;
        regex = new RegExp(searchVlue, "i");
        result = PRODUCTS.filter((product) => {
            return regex.test(product['name']);
        });
        if(result.length > 0){
            printProducts(result);
            currentDisplay = result;
        }
    } else {
        console.log("Empty serch bar");
    }
});

openCart.addEventListener("click", ()=>{
    cartElement.querySelector('.cart-body').style.cssText = "display: flex";
    openCart.classList.add("active");
});

closeCart.addEventListener("click", ()=>{
    cartElement.querySelector('.cart-body').style.cssText = "display: none";
    openCart.classList.toggle("active");
});

// End events 

/**
 * functions
 */

function printProducts(data) {
    let container = document.createElement("section");
    container.classList.add("products");
    data.forEach(element => {
        product = template.cloneNode(true);
        product.querySelectorAll("p").forEach(paragraph => {
            paragraph.innerHTML = element[getAttribute(paragraph, "value")];
        });
        cartButton = document.createElement("button");
        icon = document.createElement("i");
        icon.setAttribute("class", "fas fa-cart-plus");
        cartButton.addEventListener("click", ()=>{
            addProductToCart(element['name'], 1, element['price']);
            totalElements++;
            printCount();
        });
        product.appendChild(cartButton);
        cartButton.innerHTML = "Add To Cart ";
        cartButton.append(icon);
        container.appendChild(product);
    });
    updateData(container);
}

function updateData(container) {
    if (mainContainer.childElementCount == 1) {
        mainContainer.appendChild(container);
    } else {
        mainContainer.replaceChild(container, mainContainer.querySelector("section"))
    }
    addEventsToProducts();
}

function getAttribute(element, attribute) {
    attr = element.attributes.getNamedItem(attribute);
    if (attr) {
        return attr.value;
    } else {
        return "";
    }
}

function controlProducts(data) {
    start = +minRange.value;
    end = +maxRange.value;
    if ((start == end && start == 0) || (start > end && end != 0)) {
        
    } else {
        data = rangeProducts(start, end, data);
    } 
    controlFields.forEach(field => {
        if (field.name == "order" && field.checked && field.value != 0) {
            data = sortProducts(field.value, data);
        }
    });
    return data;
}

function rangeProducts(start, end, data) {
    if (end == 0) {
        return data.filter((product) => {
            return +product["price"] >= start;
        });
    } else if(start == 0){
        return data.filter((product) => {
            return +product["price"] <= end;
        });
    } else {
        return data.filter((product) => {
            return +product["price"] >= start && +product["price"] <= end;
        });
    }   
}

function sortProducts(type, data) {
    length = data.length;
    if (type == 1) {
        for (let index = 0; index < length; index++) {
            minIndex = index;
            for (let pointer = index; pointer < length; pointer++) {
                if (+data[minIndex]["price"] > +data[pointer]["price"]) {
                    minIndex = pointer;
                }
            }
            if (minIndex != index) {
                temp = data[index];
                data[index] = data[minIndex];
                data[minIndex] = temp;
            }
        }
    } else {
        for (let index = 0; index < length; index++) {
            maxIndex = index;
            for (let pointer = index; pointer < length; pointer++) {
                if (+data[maxIndex]["price"] < +data[pointer]["price"]) {
                    maxIndex = pointer;
                }
            }
            if (maxIndex != index) {
                temp = data[index];
                data[index] = data[maxIndex];
                data[maxIndex] = temp;
            }
        }
    }
    return data;
}

function addEventsToProducts() {
    // mainContainer.querySelectorAll(".product").forEach(element => {
    //     element.addEventListener("click", ()=>{
    //         element.querySelector("button").style.cssText = "display:block;";
    //     });
    //     element.addEventListener("dblclick", () =>{
    //         element.querySelector("button").style.cssText = "display:none;";
    //     })
    // });
}

function addProductToCart(name, quantity = 1, price = 0) {
    
    if (window.localStorage[CARTNAME]) {
        cartContent = JSON.parse(window.localStorage[CARTNAME]);
    }
    if (cartContent[name] && +quantity > 0) {
        cartContent[name]['quantity'] = +cartContent[name]['quantity'] + quantity;
    } else{
        cartContent[name] = {};
        cartContent[name]['quantity'] = quantity;
        cartContent[name]['price'] = price;
    }
    window.localStorage[CARTNAME] = JSON.stringify(cartContent);
    fillCartContent();
}

function calledWhenQuantityStateChanged(name, quantity) {
    quantity = Math.floor(quantity);
    if (quantity <= 0) {
        addProductToCart(name, 0, cartContent[name]['price']);
    } else {
        updateProductQuantity(name, quantity);
    }
}

function updateProductQuantity(name, quantity) {
    cartContent[name]['quantity'] = quantity;
    window.localStorage[CARTNAME] = JSON.stringify(cartContent);
}

function fillCartContent() {
    if (cartContent) {
        totalPrice = 0;
        content = cartElement.querySelector(".content");
        content.innerHTML = '';
        Object.keys(cartContent).forEach(key => {
            if (+cartContent[key]['quantity'] > 0) {
                item = cartItem.cloneNode(true);
                item.querySelector(".name").innerHTML = key;
                item.querySelector("input").value = cartContent[key]['quantity'];
                item.querySelector("input").setAttribute("content", key);
                item.querySelector("input").addEventListener("change", (event)=>{
                    if (+event.target.value > 0) {
                        calledWhenQuantityStateChanged(event.target.getAttribute("content"), event.target.value);
                        event.target.parentNode.querySelector(".price").innerHTML = (cartContent[key]['price']);
                    } else {
                        calledWhenQuantityStateChanged(event.target.getAttribute("content"), event.target.value);
                    }
                    cartElement.querySelector(".total").innerHTML = "Total Price : " + calcTotalPrice();
                    printCount();
                });
                item.querySelector(".price").innerHTML = (cartContent[key]['price']);
                content.appendChild(item);
                totalPrice += (+cartContent[key]['price'] * +cartContent[key]['quantity']);
            }
        });
        if (totalPrice == 0) {
            cartContent = {};
        }
        cartElement.querySelector(".total").innerHTML = "Total Price : " + totalPrice;
        printCount();
    } else {
        cartElement.querySelector(".total").innerHTML = "Total Price : 0";
    }
}

function printCount() {
    if (totalElements > 10) {
        cartElement.setAttribute('count', "10+");
        openCart.style.cssText = "animation-name: pulse;";
        setTimeout(() => {
            openCart.style.cssText = "";
        }, 300);
    } else {
        cartElement.setAttribute('count', totalElements);
        openCart.style.cssText = "animation-name: pulse;";
        setTimeout(() => {
            openCart.style.cssText = "";
        }, 300);
    }
}

function emptyCart() {
    totalElements = 0;
    printCount();
    cartContent = {};
    window.localStorage.removeItem(CARTNAME);
    fillCartContent();
}

function calcTotalPrice() {
    total = 0;
    sum = 0;
    Object.keys(cartContent).forEach(key => {
        
        total += (+cartContent[key]['price'] * +cartContent[key]['quantity']);
        sum +=  +cartContent[key]['quantity'];
        
    });
    totalElements = sum;
    return total;
}

function fillPreview(){
    emptyPreview();
    preview = document.querySelector("body > .preview");
    if (cartContent) {
        tbody = preview.querySelector("tbody");
        Object.keys(cartContent).forEach(key => {
            if (cartContent[key]['quantity'] > 0) {
                item = document.createElement("tr");

                itemName = document.createElement("td");
                itemName.innerHTML = key;

                itemQuantity = document.createElement("td");
                itemQuantity.innerHTML = cartContent[key]['quantity'];

                itemPrice = document.createElement("td");
                itemPrice.innerHTML = cartContent[key]['price'];

                item.appendChild(itemName);
                item.appendChild(itemQuantity);
                item.appendChild(itemPrice);

                tbody.appendChild(item);
            }
        });
        preview.querySelector("table").appendChild(tbody);
        
        preview.querySelector(".head > p").innerHTML = "Total Price : " + calcTotalPrice();
    }

}

function emptyPreview(){
    preview = document.querySelector("body > .preview");
    preview.querySelector(".items tbody").innerHTML = "";
}

function doCheckOut() {
    if (calcTotalPrice() > 0) {
        if (document.getElementById("checkform")) {
            document.getElementById("checkform").remove();
        }
        request = document.createElement("form");
        request.style.cssText = "display: none";
        request.setAttribute("method", "post");
        request.setAttribute("action", "checkout.php");
        field = document.createElement('textArea');
        field.setAttribute("name", "checkOutRequest");
        field.value = JSON.stringify(cartContent);
        nameField = document.createElement('input');
        nameField.setAttribute("type", "text");
        nameField.setAttribute("name", "customerName");
        nameField.value = USERNAME;
        request.appendChild(field);
        request.appendChild(nameField);
        document.body.appendChild(request);
        emptyCart();
        request.submit();
    } else {
        console.log("no content on the cart");
    }
}

// End functions
