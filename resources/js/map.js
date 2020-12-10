require("./bootstrap");
//require("./apt");
var $ = require("jquery");
const Handlebars = require("handlebars");
const { log } = require("handlebars");
const { find } = require("lodash");


/// api key tom tom 
const apiKey = "31kN4urrGHUYoJ4IOWdAiEzMJJKQpfVk";

// funzione che carica mappa di tom tom all'avvio////
var map = (function(){
let map = tt.map({
    key: apiKey,
    container: "map",
    center: [12.49334, 41.88996],
    style: "tomtom://vector/1/basic-main",
    zoom: 4
});
})();

$(document).ready(function() {
    /// ricerca automatica all'avvio della pagina con i dati presi dal form 
    var instantSearch = (function() {
        if ($("#address-inst").html() != "") {
            getCoordinates(
                $("#address-inst").html(),
                $("#range-form").html(),
                false
            );
            getServices();
        }
    })();
    
    /// funzione ricerca al click del tasto cerca
    $(".nav__search-icon-big").click(function() {
        $(".search__resoults__apartment-cards").empty();
        $('#address-inst').text($("#search").val());
        if ($("#search").val() != "") {
            getCoordinates($("#search").val(), $("#range-value").html(), false);
        }
    });
    /// funzione ricerca con tasto invio del tasto cerca
    $("#search").keydown(function() {
        if (event.which == 13 || event.keyCode == 13) {
            if ($("#search").val() != "") {
                getCoordinates(
                    $("#search").val(),
                    $("#range-value").html(),
                    false
                );
            }
        }
    });
});
//// prendi coordinate dell'input dalla api di tom tom////////////////
function getCoordinates(input, range, services) {
    var zoom = 10;
    if (input != "") {
        tt.services
            .fuzzySearch({
                key: apiKey,
                query: input
            })
            .go()
            .then(function(response) {
                var longitude = response.results[0].position["lng"];
                var latitude = response.results[0].position["lat"];
                city = response.results[0].address["municipality"];
                streetName = response.results[0].address["streetName"];
                // condizione per selezionare lo zoom in caso di città o indirizzo specifico
                if (streetName != undefined && city) {
                    zoom = 16;
                }
                map = tt.map({
                    key: apiKey,
                    style: "tomtom://vector/1/basic-main",
                    container: "map",
                    center: response.results[0].position,
                    zoom: zoom
                });
                
                if (services == false) {
                    getCards(latitude, longitude, range, 1);
                    getCards(latitude, longitude, range, 0);
                } else {
                    getCardsFilter(latitude, longitude, range, 1, services);
                    getCardsFilter(latitude, longitude, range, 0, services);
                }
            });
    }
}
/////////////////////////////////////////////////////////////////////
/* chiamata all nostro db che richiama funzione handlebars per appendere servizi 
e per appendere id appartamneto per poi richimare la nostra api per foto e servizi */
function getServices() {
    $.ajax({
        url: "http://127.0.0.1:8000/api/services/all",
        method: "GET",
        headers: {
            KEY: "test"
        },
        success: function(response) {
            for (var i = 0; i < response.length; i++) {
                var service = `<button data-servicetype="${response[i].id}" class="services-all">${response[i].service}</button>`;
                $(".services").append(service);
            }
        },
        error: function() {

        }
    });
}

//chiamata api interna per gli appartamenti senza filtro servizi
function getCards(lat, lng, maxDist, sponsor) {
    $.ajax({
        url: "http://127.0.0.1:8000/api/apartments",
        method: "GET",
        headers: {
            KEY: "test"
        },
        data: {
            lat: lat,
            lng: lng,
            maxDist: maxDist,
            sponsored: sponsor
        },
        success: function(risposta) {
            if (risposta.length > 0) {
                var results = 'I tuoi risultati per';
                $('.container__search-left__top__text-heading').text(results);
                compileHandlebars(risposta, sponsor);
            } else {
                var noResults = 'Non ci sono risultati per'
                $('.container__search-left__top__text-heading').text(noResults);
            }
        },
        error: function() {}
    });
}
//chiamata api interna per gli appartamenti con filtro servizi
function getCardsFilter(lat, lng, maxDist, sponsor, services) {
    $.ajax({
        url: "http://127.0.0.1:8000/api/apartments",
        method: "GET",
        headers: {
            KEY: "test"
        },
        data: {
            lat: lat,
            lng: lng,
            maxDist: maxDist,
            services: Array.from(services),
            sponsored: sponsor,
            // rooms:$('.filter__chars__numbers.room-numbers').html(),
            // beds:$('.filter__chars__numbers.beds').html(),
            // bathrooms:$('.filter__chars__numbers.toilets').html(),
        },
        success: function(risposta) {
            
            if (risposta.length > 0) {
                compileHandlebars(risposta, sponsor);
            } else {
                var noResults = 'Non ci sono risultati per'
                $('.container__search-left__top__text-heading').text(noResults);
            }
        },
        error: function() {}
    });
}
///////////////////////////////////////////
/// funzione per inserire le card della ricerca nel dom e creare i marker associati nella mappa//////
function compileHandlebars(risp, sponsor) {
    //// se la richiesta e per gli sponsorizzati il container cambia ////
    if (sponsor == 1) {
        var containerCards = $("#sponsor");
        /// senno rimane lo stesso ////
    } else {
        var containerCards = $("#no-sponsor");
    }
    //// handlebars per comilare i container nel dom 
    var source = $("#handlebars_cards").html();
    var templateCards = Handlebars.compile(source);
    
    
    for (let i = 0; i < risp.length; i++) {
        if(sponsor == 1) {
            city = risp[i].city + ' -sponsorizzato-';
        } else {
            city = risp[i].city;
        }
        var context = {
            city: city,
            title: troncaStringa(risp[i].title),
            id: `<input class="aps_id" type="hidden" name="apartment_id" value=${risp[i].id}>`,
            sponsor: sponsor,
            dataId: risp[i].id,
            price: risp[i].daily_price,
            mq: risp[i].sm,
            rooms: risp[i].rooms,
            beds: risp[i].beds,
            bathrooms: risp[i].bathrooms

        };

        var coordinates = [risp[i].longitude, risp[i].latitude];
        var address = risp[i].address;
        var city = risp[i].city;
        var price = risp[i].daily_price;
        // creo il custom marker
        var element = document.createElement("div");
        element.id = "marker";
        const marker = new tt.Marker({ element: element })
            .setLngLat(coordinates)
            .setPopup(new tt.Popup({ offset: 35 }).setHTML(address))
            .addTo(map);

        var popupOffsets = {
            top: [0, 0],
            bottom: [0, -70],
            "bottom-right": [0, -70],
            "bottom-left": [0, -70],
            left: [25, -35],
            right: [-25, -35]
        };

        // popup sui marker
        var popup = new tt.Popup({
            offset: popupOffsets
        }).setHTML(
            address +
                " " +
                city +
                " " +
                "<br>" +
                "<strong>" +
                price +
                "</strong>" +
                " € a notte"
        );

        // assegno il popup
        marker.setPopup(popup);

        var htmlContext = templateCards(context);
        /// appendiamo le cards nel dom
        containerCards.append(htmlContext);

        /// riuchiamiamo la ricerca der iserire le immagini nel carosello delle cards
        getImages(risp[i].id, sponsor);

        
       
        // cliccando su un elemento della lista a sx lo trova in mappa /////
        $('.search__resoults__apartment-cards-content__text').on(
            "click",
            (function(marker) {
                
                return function() {
                    map.easeTo({
                        /// cambiamo centro mappa e zoomiamo sull'elemneto selezionato 
                        center: marker.getLngLat(),
                        zoom: 16
                    });
                    // serve a passare da un marker all'altro nella selezione di sx
                    closeAllPopups();
                    // marker.togglePopup();
                };
            })(marker)
        );
        var el = $(".search__resoults__apartment-cards-content");
        // assosiamo la card al pin ///////////
        var details = buildLocation(el, address);
        // cliccando sul marker aggiunge la classe selected alla card dell'appartamento corrispondente
        marker._element.addEventListener("click", function() {
            var posizione = $(this).index() - 1;
            details.removeClass("selected");
            details.eq(posizione).addClass("selected");
        });
        compileServices(risp[i].id);
        

    }
}
//funzione che associa l'address con la card apartment nel DOM
function buildLocation(el, text) {
    const details = el;
    details.innerHTML = text;
    // details["position"] = xy;
    return details;
}


//compila i servizi degli appartamenti
function compileServices(id) {
    $.ajax({
        url: "http://127.0.0.1:8000/api/services",
        method: "GET",
        headers: {
            KEY: "test"
        },
        data: {
            id: id,
        },
        success: function(risposta) {
            if (risposta.length > 0) {
                $('[serv-id="'+ id +'"]').empty();
                for(i = 0; i < risposta.length; i++) {
                    var icon = '<i class="' + risposta[i].icon + '"></i>'
                    $('[serv-id="'+ id +'"]').append(icon);

                }
            }
        },
        error: function() {}
    });
    
    
}


/// appendere le immagini allo slider
function getImages(id, sponsor) {
    $.ajax({
        url: "http://127.0.0.1:8000/api/images",
        method: "GET",
        data: {
            id: id
        },
        headers: {
            KEY: "test"
        },
        success: function(response) {
            var clss = "";
            for (var i = 0; i < response.length; i++) {
                var img = (clss = "hidden");
                /// associamo classi per far funzionare il carosello ////
                if (i == 0) {
                    clss = "first active";
                } else if (i == response.length - 1) {
                    clss = "hidden last";
                } else {
                    clss = "hidden";
                }
                appendImages(response[i], clss, sponsor);
            }
            /// se l'immagine e una sola nascondiamo i controller slider /////
            if(response.length == 1) {
                $('[data-id="'+ id +'"]').find('.arrow-slider-dx').hide();
                $('[data-id="'+ id +'"]').find('.arrow-slider-sx').hide();
            }
        },
        error: function() {}
    });
}

//// funzione che viene richiamata e trova il container a cui associare l'immagine /////
function appendImages(risp, clss, sponsor) {
    var container = $(".sponsor-" + sponsor);
    container.each(function() {
        var appId = $(this)
            .find(".aps_id")
            .val();
      
        if (appId == risp.apartment_id) {
            img = `<img class="search__resoults__apartment-cards-content-slider-img apt-image ${clss}" 
           src="${risp.path}">`;
            $(this)
                .find(".search__resoults__apartment-cards-content-slider")
                .append(img);
        }
    });
    
}
// funzione per troncare una stringa
function troncaStringa(stringa) {
    var shortText = "";
    if(stringa.length > 28) {
        for (var i = 28; i > 0; i--) {
            if (stringa[i] == " ") {
                shortText = $.trim(stringa).substring(0, i) + "...";
                i = 0;
            }
        } 
    } else {
        shortText = stringa;
        
    }

    return shortText;
}

/// funzione che filtra ricerca per servizi
var serviceCheck = (function() {
    // inzializziamo una array di servizi vuoto
    var selectedService = [];

    $(document).on("click", ".services-all", function() {
        var serviceType = $(this)
            .data("servicetype")
            .toString();
        $(this).toggleClass("service-selected");
        // pushamo il servizio se selezionato 
        if (
            selectedService.length < $(".services-all").length &&
            !selectedService.includes(serviceType)
        ) {
            selectedService.push(serviceType);
        }
        /// se viene deselezionato il servizio lo leviamo dall'array usando il metodo filter ////
        if (!$(this).hasClass("service-selected")) {
            selectedService = selectedService.filter(function(item) {
                return item != serviceType;
            });
        }
    });
    /////// fa prtire la ricerca con i servizi selezionati passandogli l'array di cui sopra ///
    $("#cerca-filtri").click(function() {
        $('.container__search-left__top__filters').hide();
        $("#no-sponsor").empty();
        $("#sponsor").empty();
        getCoordinates($('#address-inst').html(), $("#range-value").html(), selectedService);
    });
})();


// //funzione per selezionare suggerimento e restuirlo nella search
$(document).on('click', '.complete-results', function () {
    var value = $(this).text();
    $("#search").val(value);
    getCoordinates($("#search").val());
    $("#auto-complete").removeClass("complete-on");
});




// per chiudere l'autocomplete al click fuori
$(document).click(function() {
    $("#auto-complete").removeClass("complete-on");
});


//slider /// carsosello della card apartment //////
$(document).on("click", ".arrow-slider-sx", function() {
    var activeImage = $(this).siblings(".apt-image.active");
    activeImage.removeClass("active");
    activeImage.addClass("hidden");
    if (activeImage.hasClass("last") == true) {
        activeImage.siblings(".apt-image.first").removeClass("hidden");
        activeImage.siblings(".apt-image.first").addClass("active");
    } else {
        //metto la classe attiva al successivo
        activeImage.next().removeClass("hidden");
        activeImage.next().addClass("active");
    }
});
$(document).on("click", ".arrow-slider-dx", function() {
    var activeImage = $(this).siblings(".apt-image.active");

    activeImage.removeClass("active");
    activeImage.addClass("hidden");

    if (activeImage.hasClass("first") == true) {
        activeImage.siblings(".apt-image.last").removeClass("hidden");
        activeImage.siblings(".apt-image.last").addClass("active");
    } else {
        //metto la classe attiva al successivo
        activeImage.prev().removeClass("hidden");
        activeImage.prev().addClass("active");
    }
});
/// animaziane menu filtri //////////////////////////////////
$('.filter-toggle').click(function(){
  $('.container__search-left__top__filters').slideDown();
  $(this).find('.filter-toggle-tetx').text('Chiudi');
  $(this).toggleClass('chiudi');
   document.querySelector('.chevron-filter').style.transform = 'rotate(-180deg)';
  if(!$(this).hasClass('chiudi')){
    document.querySelector('.chevron-filter').style.transform = 'rotate(360deg)';
    $('.container__search-left__top__filters').slideUp();
   }
});
$('.plus').click(function(){
    var value = parseInt($(this).siblings(".filter__chars__numbers").text());
    value ++;
    if(value > 10){
        $(this).siblings(".filter__chars__numbers").text(2);
    }else{
        $(this).siblings(".filter__chars__numbers").text(value);
    }
    
});
$('.minus').click(function(){
    var value = parseInt($(this).siblings(".filter__chars__numbers").text());
    value --;
    if(value < 1){
        $(this).siblings(".filter__chars__numbers").text(10);
    }else{
        $(this).siblings(".filter__chars__numbers").text(value);
    }
    
});