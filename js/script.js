// Smooth Scroll Functionality
class SmoothScroll {
    constructor() {
        this.init();
    }

    init() {
        // Плавная прокрутка для всех якорных ссылок
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();

                const targetId = anchor.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    this.scrollToElement(targetElement);
                }
            });
        });
    }

    scrollToElement(element) {
        const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
        const offsetPosition = elementPosition - 80; // Учитываем высоту header

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
}

// Reviews Carousel
class ReviewsCarousel {
    constructor() {
        this.reviewCards = document.querySelectorAll('.review-card');
        this.dots = document.querySelectorAll('.reviews__dot');
        this.prevBtn = document.querySelector('.reviews__nav-btn.prev');
        this.nextBtn = document.querySelector('.reviews__nav-btn.next');
        this.currentReview = 0;
        this.totalReviews = this.reviewCards.length;

        this.init();
    }

    init() {
        this.prevBtn.addEventListener('click', () => this.prevReview());
        this.nextBtn.addEventListener('click', () => this.nextReview());

        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToReview(index));
        });

        // Auto rotate every 8 seconds
        this.startAutoRotate();
    }

    showReview(index) {
        // Remove active classes
        this.reviewCards.forEach(card => {
            card.classList.remove('active', 'prev', 'next');
        });
        this.dots.forEach(dot => dot.classList.remove('active'));

        // Set new active review
        this.reviewCards[index].classList.add('active');
        this.dots[index].classList.add('active');

        // Set previous and next states for 3D effect
        const prevIndex = index > 0 ? index - 1 : this.totalReviews - 1;
        const nextIndex = index < this.totalReviews - 1 ? index + 1 : 0;

        this.reviewCards[prevIndex].classList.add('prev');
        this.reviewCards[nextIndex].classList.add('next');

        this.currentReview = index;
    }

    nextReview() {
        let nextIndex = this.currentReview + 1;
        if (nextIndex >= this.totalReviews) nextIndex = 0;
        this.showReview(nextIndex);
    }

    prevReview() {
        let prevIndex = this.currentReview - 1;
        if (prevIndex < 0) prevIndex = this.totalReviews - 1;
        this.showReview(prevIndex);
    }

    goToReview(index) {
        this.showReview(index);
    }

    startAutoRotate() {
        setInterval(() => {
            this.nextReview();
        }, 10000);
    }
}

// Mobile Menu functionality
function initMobileMenu() {
    const hamburger = document.querySelector('.nav__hamburger');
    const navMenu = document.querySelector('.nav__menu');
    const body = document.body;

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function () {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            body.classList.toggle('menu-open');
        });

        // Закрываем меню при клике на ссылку + плавная прокрутка
        navMenu.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();

                // Закрываем мобильное меню
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                body.classList.remove('menu-open');

                // Плавная прокрутка к цели
                const targetId = link.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    const elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                    const offsetPosition = elementPosition - 80;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
}

// Simple and Reliable Hero Slider
class HeroSlider {
    constructor() {
        this.slides = document.querySelectorAll('.slide');
        this.indicators = document.querySelectorAll('.indicator');
        this.prevBtn = document.querySelector('.control-btn.prev');
        this.nextBtn = document.querySelector('.control-btn.next');
        this.currentSlide = 0;
        this.totalSlides = this.slides.length;
        this.autoSlideInterval = null;
        this.isAnimating = false;

        this.init();
    }

    init() {
        // Показываем первый слайд
        this.showSlide(0);

        // Вешаем обработчики событий
        this.prevBtn.addEventListener('click', () => this.prevSlide());
        this.nextBtn.addEventListener('click', () => this.nextSlide());

        // Клики по индикаторам
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.goToSlide(index));
        });

        this.startAutoSlide();

        // Пауза при наведении
        const slider = document.querySelector('.hero__slider');
        slider.addEventListener('mouseenter', () => this.stopAutoSlide());
        slider.addEventListener('mouseleave', () => this.startAutoSlide());
    }

    showSlide(index) {
        if (this.isAnimating) return;

        this.isAnimating = true;

        // Скрываем все слайды
        this.slides.forEach(slide => {
            slide.classList.remove('active');
        });

        // Убираем активные индикаторы
        this.indicators.forEach(indicator => {
            indicator.classList.remove('active');
        });

        // Показываем текущий слайд
        this.slides[index].classList.add('active');
        this.indicators[index].classList.add('active');

        this.currentSlide = index;

        // Сбрасываем флаг анимации после перехода
        setTimeout(() => {
            this.isAnimating = false;
        }, 800);
    }

    nextSlide() {
        let nextIndex = this.currentSlide + 1;
        if (nextIndex >= this.totalSlides) {
            nextIndex = 0;
        }
        this.showSlide(nextIndex);
    }

    prevSlide() {
        let prevIndex = this.currentSlide - 1;
        if (prevIndex < 0) {
            prevIndex = this.totalSlides - 1;
        }
        this.showSlide(prevIndex);
    }

    goToSlide(index) {
        if (index >= 0 && index < this.totalSlides) {
            this.showSlide(index);
        }
    }

    startAutoSlide() {
        this.stopAutoSlide();
        this.autoSlideInterval = setInterval(() => {
            this.nextSlide();
        }, 10000);
    }

    stopAutoSlide() {
        if (this.autoSlideInterval) {
            clearInterval(this.autoSlideInterval);
            this.autoSlideInterval = null;
        }
    }
}

// Highlight active menu item based on scroll position
function initActiveMenu() {
    const sections = document.querySelectorAll('section[id]');
    const menuLinks = document.querySelectorAll('.nav__menu a[href^="#"]');

    function updateActiveMenu() {
        let current = '';
        const scrollY = window.pageYOffset;

        sections.forEach(section => {
            const sectionHeight = section.offsetHeight;
            const sectionTop = section.offsetTop - 100;
            const sectionId = section.getAttribute('id');

            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                current = sectionId;
            }
        });

        menuLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', updateActiveMenu);
    updateActiveMenu();
}

// Инициализация когда страница загружена
document.addEventListener('DOMContentLoaded', function () {
    new HeroSlider();
    new SmoothScroll();
    new ReviewsCarousel(); // Добавляем эту строку
    initMobileMenu();
    initActiveMenu();

    // Header scroll effect
    const header = document.querySelector('.header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
            header.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.boxShadow = 'none';
        }
    });
});