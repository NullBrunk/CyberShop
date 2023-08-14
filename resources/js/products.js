var swiper = new Swiper(".slide-container", {
    slidesPerView: 1,
    spaceBetween: 20,
    sliderPerGroup: 1,
    loop: true,
    centerSlide: "true",
    fade: "true",
    grabCursor: "true",

    pagination: {
        el: ".swiper-pagination",
        clickable: true,
        dynamicBullets: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },

    breakpoints: {
        0: {
            slidesPerView: 1,
        },
    },
    
});


function delete_image(url, elem, csrf){
    Swal.fire({
        title: 'Are you sure ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#293e61',
        cancelButtonColor: '#af2024',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        // On redirige vers la page permettant de supprimer le commentaire
        if (result.isConfirmed) {
            fetch(url, {
                method: "DELETE",
                headers: {
                    "X-CSRF-Token": csrf,
                },
            }).then((data) => {

                status_code = data.status;

                if(status_code === 403) {
                    salert("Forbidden");
                }
                else if(status_code === 401) {
                    salert("Cannot delete the main image !");
                }
                else {
                    window.location.reload();
                }
            });
        }
    })
    }