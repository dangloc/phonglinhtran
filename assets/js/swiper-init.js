document.addEventListener('DOMContentLoaded', function() {
    // Initialize content swiper
    const contentSwiper = new Swiper('.swiper-story-content', {
        effect: 'fade',
        initialSlide: 1,
        fadeEffect: {
            crossFade: true
        },
        allowTouchMove: false,
        speed: 1000,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });

    // Initialize thumb swiper
    const thumbSwiper = new Swiper('.swiper-story-thumb', {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 2.5,
        initialSlide: 1,
        coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 95,
            modifier: 2.5,
            slideShadows: true,
        },
        speed: 1000,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });

    // Link the two swipers
    contentSwiper.controller.control = thumbSwiper;
    thumbSwiper.controller.control = contentSwiper;

    // Hàm đổi background
    function updateBannerBg() {
        // Lấy slide đang active
        const activeSlide = document.querySelector('.swiper-story-thumb .swiper-slide-active');
        if (activeSlide) {
            const url = activeSlide.getAttribute('data-url');
            if (url) {
                document.querySelector('.section-hero-banner .bg').style.backgroundImage = `url('${url}')`;
            }
        }
    }

    // Gọi khi mới load
    updateBannerBg();

    // Gọi khi slide thay đổi
    thumbSwiper.on('slideChange', updateBannerBg);
    // Gọi khi Swiper khởi tạo lại (resize, v.v.)
    thumbSwiper.on('afterInit', updateBannerBg);
}); 

document.addEventListener('DOMContentLoaded', function() {
    // Swiper cho Truyện mới
    new Swiper('.swiper-latest', {
        slidesPerView: 5,
        spaceBetween: 24,
        navigation: {
            nextEl: '.swiper-latest ~ .swiper-button-next, .swiper-latest .swiper-button-next',
            prevEl: '.swiper-latest ~ .swiper-button-prev, .swiper-latest .swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 2 },
            576: { slidesPerView: 3 },
            992: { slidesPerView: 5 }
        }
    });

    // Swiper cho Truyện hot
    new Swiper('.swiper-popular', {
        slidesPerView: 5,
        spaceBetween: 24,
        navigation: {
            nextEl: '.swiper-popular ~ .swiper-button-next, .swiper-popular .swiper-button-next',
            prevEl: '.swiper-popular ~ .swiper-button-prev, .swiper-popular .swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 2 },
            576: { slidesPerView: 3 },
            992: { slidesPerView: 5 }
        }
    });

    // Swiper cho Thể loại 1
    new Swiper('.swiper-tax1', {
        slidesPerView: 5,
        spaceBetween: 24,
        navigation: {
            nextEl: '.swiper-tax1 ~ .swiper-button-next, .swiper-tax1 .swiper-button-next',
            prevEl: '.swiper-tax1 ~ .swiper-button-prev, .swiper-tax1 .swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 2 },
            576: { slidesPerView: 3 },
            992: { slidesPerView: 5 }
        }
    });

    // Swiper cho Thể loại 2
    new Swiper('.swiper-tax2', {
        slidesPerView: 5,
        spaceBetween: 24,
        navigation: {
            nextEl: '.swiper-tax2 ~ .swiper-button-next, .swiper-tax2 .swiper-button-next',
            prevEl: '.swiper-tax2 ~ .swiper-button-prev, .swiper-tax2 .swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 2 },
            576: { slidesPerView: 3 },
            992: { slidesPerView: 5 }
        }
    });
}); 