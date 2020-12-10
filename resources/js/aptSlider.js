const { eq } = require("lodash");

// ** FUNZIONI **/
$('.arrow-slider-sx').click(function(){
    prevImage();
 });

 $('.arrow-slider-dx').click(function(){
     nextImage();
 });

//////////// funzione slider pagina appartamento /////
function nextImage(){
    var activeImage =$('.apt-image.active');
    var activeDot = $('.dots__carousel-active');
    activeImage.removeClass('active');
    activeImage.addClass('hidden');
    activeDot.removeClass('dots__carousel-active');
    if (activeImage.hasClass('last') == true) {
        $('.apt-image.first').removeClass('hidden');
        $('.apt-image.first').addClass('active');
        $('.dots__carousel.first').addClass('dots__carousel-active');
    } else {
       // metto la classe attiva al successivo
        activeImage.next().removeClass('hidden');
        activeImage.next().addClass('active');
        activeDot.next().addClass('dots__carousel-active');
    }
}

function prevImage(){
    var activeImage =$('.apt-image.active');
    var activeDot = $('.dots__carousel-active');
    activeImage.removeClass('active');
    activeImage.addClass('hidden');
    activeDot.removeClass('dots__carousel-active');
    if (activeImage.hasClass('first') == true) {
       $('.apt-image.last').removeClass('hidden');
        $('.apt-image.last').addClass('active');
        $('.dots__carousel.last').addClass('dots__carousel-active');
    } else {
        //metto la classe attiva al successivo
        activeImage.prev().removeClass('hidden');
        activeImage.prev().addClass('active');
        activeDot.prev().addClass('dots__carousel-active');
    }
}
$('.dots__carousel').click(function(){
    $('.dots__carousel').removeClass('dots__carousel-active');
    $(this).addClass('dots__carousel-active');
    $('.apt-image.active').addClass('hidden');
  $('.apt-image').eq($(this).index()).removeClass('hidden');
  $('.apt-image').eq($(this).index()).addClass('active');
});