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
        document.getElementById(id).classList.add("bi-heart-fill")
    }
    else {
        document.getElementById(id).classList.add("bi-heart")
    }
}

async function heartclick(url, id, num){
    let resp = await fetch(url);

    let elem = document.getElementById(id)
    
    elem.classList.toggle("bi-heart-fill")
    elem.classList.toggle("bi-heart")


    let number = document.getElementById(num)
    if(elem.classList.contains("bi-heart-fill")){
        number.innerText = parseInt(number.innerText) + 1;
    }
    else {
        number.innerText = parseInt(number.innerText) - 1;
    }
}

function addtocart(id){

    /* Add the product into the cart server side */
    fetch("/cart/add/" + id).then((resp) => {
    

        // If there is a redirection, the user is not logged

        if(!resp.redirected){
            
            resp.json().then((data) => {

                /* Add the product into the cart client side */
                
                const id_cart_elem = data.id
                const url = location.protocol + "//" + window.location.hostname + ":8000/api/products/" + id

                fetch(url).then((response) => {
                    
                    if (response.ok) {
                        response.json().then((data) => {

                            let cart = document.getElementById("cart_to_fill");

                            cart.innerHTML += `
                                <li id="${id_cart_elem}">
                                    <p class="show_cart">

                                        <img src="/storage/product_img/${data.img}"       style="padding-left: 3%; width: 22%; display: block; user-select: none !important;">

                                        <a href="/details/${data.id}" style="display: block;overflow: hidden; width: 57%; margin:auto;">${data.name}</a>
                                        <img src="/assets/img/trash.png" onclick="deleteitem(${id_cart_elem})" class="trash-cart">
                                    </p>
                                </li>
                                <hr id="hr${id_cart_elem}">
                            `     
                        })
                    }
                })

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
}

$(function() {
    $('#commentTextBar').markItUp(mySettings);
})        