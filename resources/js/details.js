// Afficher le menu ou le masquer lorsque l'on clique sur les 3 points
function menu(id){
    const menu = document.getElementById(id);
    menu.classList.toggle("none")
}


// Afficher le foromulaire pour poster un commentaire
// quand on clique sur "Click here to post a comment"
function showcomm(){
    const form = document.getElementById("formcomm");
    const chevron = document.getElementById("chevron");
    const span = document.getElementById("commcontent");

    form.classList.toggle("none");

    // On passe d'un chevron vers la droite a un chevron vers le bas
    // et inversement 

    chevron.classList.toggle("bx-chevron-right");
    chevron.classList.toggle("bx-chevron-down");

    if(span.innerText==="Click here to close this menu"){
        span.innerText = "Click here to post a comment "
    }
    else {
        span.innerText = "Click here to close this menu"
    }
}

async function haveiliked(url, id) {
    let resp = await fetch(url);
    let data = await resp.json()

    if(data.value == true){
        document.getElementById("empty" + id).classList.add("filled")
        document.getElementById("empty" + id).classList.remove("empty")

        document.getElementById("filled" + id).classList.add("empty")
        document.getElementById("filled" + id).classList.remove("filled")

    }
    
}

async function heartclick(url, id){
    await fetch(url);

    let empty = document.getElementById("empty" + id)
    let filled = document.getElementById("filled" + id)

    empty.classList.toggle("empty")
    empty.classList.toggle("filled")

    filled.classList.toggle("empty")
    filled.classList.toggle("filled")


    let number = document.getElementById("num" + id)
    if(empty.classList.contains("filled")){
        number.innerText = parseInt(number.innerText) + 1;
        return true;
    }
    else {
        number.innerText = parseInt(number.innerText) - 1;
        return false;
    }
}

function addtocart(id){

    /* Add the product into the cart server side */
    fetch("/cart/add/" + id).then((resp) => {
    

        // If there is a redirection, the user is not logged

        if(!resp.redirected){
            
            resp.json().then((data) => {

                if(data.add === true) {
                    let elem = document.getElementById("cart_" + data.id).querySelector("div div span");
                    elem.innerHTML = parseInt(elem.innerHTML) + 1;
                }
                else {   
                    /* Add the product into the cart client side */
                    
                    const id_cart_elem = data.id
                    const url = location.protocol + "//" + window.location.hostname + ":8000/api/products/" + id

                    fetch(url).then((response) => {
                        
                        if (response.ok) {
                            response.json().then((data) => {

                                let cart = document.getElementById("cart_to_fill");

                                console.log(cart);
                                cart.innerHTML += `
                                    <li id="cart_${id_cart_elem}">
                                        <div class="show_cart">
                                            <img src="/storage/product_img/${data.img}" style="padding-left: 3%; width: 22%; display: block; user-select: none !important;">

                                            <div class="d-flex flex-column cartelem" style="width: 57%; overflow: hidden;">
                                                <a href="/details/${id_cart_elem}" style="width: 94%; padding: 6px 0px 0px 20px;  overflow: hidden;">${data.name}</a>
                                                <div>
                                                    <i class="bi bi-x"></i><span>1</span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row m-auto">
                                                <button style="border-top-left-radius: 7px;border-bottom-left-radius: 7px;" class="cartbutton button-pink" onclick='deleteitem("cart_${id_cart_elem}")'>-</button>
                                                <button style="border-top-right-radius: 7px;border-bottom-right-radius: 7px;" class="cartbutton button-blue" onclick='addtocart("${id_cart_elem}")'>+</button>
                                            </div>
                                        </div>
                                    </li>
                                    <hr id="hrcart_${id_cart_elem}">

                                `     
                            })
                        }
                    })
                }
                // Update the span on top of the cart icon 
                
                const num = document.getElementById("number");
                
                if(num.innerHTML === ""){
                    num.innerHTML = 1
                }
                else {
                    num.innerHTML = parseInt(num.innerHTML) + 1
                }
                
            })
        }

    })
    
    return true;
}

$(function() {
    $('#commentTextBar').markItUp(mySettings);
})        



  