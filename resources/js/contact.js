function menu(id){
    document.getElementById(id).classList.toggle("none")
}

function confirm_delete(url){
Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#293e61',
        cancelButtonColor: '#af2024',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {

        if (result.isConfirmed) {
            window.location.href = url;
        }
    })
}

function sendmsg(){
    Swal.fire({
        title: 'Enter the mail of the user',
        input: 'text',
        inputAttributes: {
        autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Contact',
    
    }).then((result) => {
        if (result.isConfirmed) {
            return window.location.href = "/chatbox/" + result.value;
        }
    })
}