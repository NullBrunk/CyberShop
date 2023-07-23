function alert_and_scroll(message){

    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
    })

    window.location.href = "#commenttext";
    showcomm();
}

function success(message, alert = "Updated"){
    Swal.fire(
        alert,
        message,
        'success'
    )
}


function salert(message){
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
    })
}