document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: { delay: 4000 },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        effect: 'fade',
        speed: 1200,
        lazy: true,
        preloadImages: false
    });

    const swiperTenues = new Swiper('.swiper-container-tenues', {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        loop: true,
        slidesPerView: 'auto',
        coverflowEffect: { rotate: 0, stretch: 60, depth: 200, modifier: 2.5, slideShadows: false },
        autoplay: { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true },
        navigation: { nextEl: '.swiper-button-next-tenues', prevEl: '.swiper-button-prev-tenues' },
        lazy: true,
        preloadImages: false,
        breakpoints: {
            640: { slidesPerView: 1.2 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });
});