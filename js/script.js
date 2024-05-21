var slideIndicado = 1;
var temporizadorSlide;

mostrarSlide(slideIndicado);

function passarSlide(n) {
    clearTimeout(temporizadorSlide);
    mostrarSlide(slideIndicado += n);
}

function slideAtual(n) {
    clearTimeout(temporizadorSlide);
    mostrarSlide(slideIndicado = n);
}

function mostrarSlide(n) {
    var i;
    var slides = document.getElementsByClassName("slide"); /* .length conta o número de elementos com a classe tal */
    var dots = document.getElementsByClassName("bt-manual");
    if (n > slides.length) {
        slideIndicado = 1; /* vai para o primeiro slide se passar do 3 */
    }
    if (n < 1) {
        slideIndicado = slides.length; /* vai pro 3 se passar do primeiro */
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndicado - 1].style.display = "flex";
    dots[slideIndicado - 1].className += " active";

    temporizadorSlide = setTimeout(function () {
        passarSlide(1);
    }, 8000);
}
