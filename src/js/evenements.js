document.addEventListener("DOMContentLoaded", () => {
  const swiper = new Swiper(".swiper-container", {
    loop: true,
    autoplay: { delay: 4000 },
    pagination: { el: ".swiper-pagination", clickable: true },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    effect: "fade",
    speed: 1200,
    lazy: true,
    preloadImages: false,
    slidesPerView: 1,
    spaceBetween: 0,
    breakpoints: {
      320: { slidesPerView: 1, spaceBetween: 0 },
      768: { slidesPerView: 1, spaceBetween: 0 },
      1024: { slidesPerView: 1, spaceBetween: 0 },
      1920: { slidesPerView: 1, spaceBetween: 0 },
    },
  });

  const swiperTenues = new Swiper(".swiper-container-tenues", {
    loop: true,
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 1.2,
    spaceBetween: 20,
    effect: "slide",
    speed: 800,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },
    navigation: {
      nextEl: ".swiper-button-next-tenues",
      prevEl: ".swiper-button-prev-tenues",
    },
    lazy: true,
    preloadImages: false,
    initialSlide: 1, // Commence au deuxième slide pour équilibrer l'affichage
    breakpoints: {
      320: {
        slidesPerView: 1.2,
        spaceBetween: 10,
      },
      640: {
        slidesPerView: 1.5,
        spaceBetween: 15,
      },
      768: {
        slidesPerView: 2.2,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 3.5, // Augmente pour garantir des cartes partielles
        spaceBetween: 40,
      },
      1280: {
        slidesPerView: 4, // Encore plus pour les écrans très larges
        spaceBetween: 50,
      },
    },
  });

  // Forcer la mise à jour du Swiper après l'initialisation
  setTimeout(() => {
    swiperTenues.update();
  }, 100);
});