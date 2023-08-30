async function deleteitem(id) {

    let idproduct = id.split("_")[1];
    // Supprimer un élément du panier

    url = "/cart/delete/" + idproduct;

    let resp = await fetch(url);
    let data = await resp.json();


    if(data.removed) {

        // Supprimer l'élément de la div sans avoir a reloader la page ainsi que son hr
        document.getElementById(id).remove();
        document.getElementById("hrcart_" + idproduct).remove()

    }
    else {
        let elem = document.getElementById(id).querySelector("div div span");
        elem.innerHTML = parseInt(elem.innerHTML) - 1;
    }

    const num = document.getElementById("number");

    if(num.innerHTML == 1){
        number = document.getElementById("number");
        number.innerHTML = ""
    }
    else {
        num.innerHTML = num.innerHTML - 1
    }

}