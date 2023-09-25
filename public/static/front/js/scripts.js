//slider
const slider = document.querySelector('.slider');
const slides = document.querySelectorAll('.slide');
const slideWidth = slides[0].clientWidth;
let currentSlide = 0;

function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    updateSlider();
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    updateSlider();
}

function updateSlider() {
    slider.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
}

setInterval(nextSlide, 5000);

const arrowLeft = document.querySelector('.arrow-left');
const arrowRight = document.querySelector('.arrow-right');

arrowLeft.addEventListener('click', prevSlide);
arrowRight.addEventListener('click', nextSlide);

/*top headers*/
const postList = document.querySelectorAll(".article-full");
postList.forEach(item => {
    if (item.classList.contains('schema-1') || item.classList.contains('schema-2')) {
        const headers = item.querySelector(".article-full__headers");
        item.prepend(headers);
    }
});

/*moving the main photo to the beginning */
$(".article-full__content .article-full__image-img").prependTo(".article-full__image");