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