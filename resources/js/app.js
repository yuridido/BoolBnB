require("./bootstrap");
require("./add");
require("./alert");

var $ = require("jquery");
const Handlebars = require("handlebars");
const { Alert } = require("bootstrap");
const { log } = require("handlebars");

$(document).ready(function() {
    $(".nav__user-box").click(function() {
        $(".nav__user__menu").toggleClass("active");
    });

    $("#search").keydown(function() {
        if (event.which == 13 || event.keyCode == 13) {
            if ($("#search").val() != "") {
                getCoordinates($("#search").val(), $("#range-value").html());
            }
        }
    });
    /// la barra di ricerca nav nav sparisce al click ricerca
    $(".nav__search-button").click(function() {
        $("#hidenav").hide();
        hidenav();
    });

    //////////////richiamo funzioni autocomplete
    $("#search").keyup(function() {
        $("#auto-complete").empty();
        autoComplete($("#search").val());
    });
    //funzione per selezionare suggerimento e restuirlo nella search
    $(document).on("click", ".complete-results", function() {
        var value = $(this).text();
        $("#search").val(value);
    });

    // per chiudere l'autocomplete al click fuori
    $(document).click(function() {
        $("#auto-complete").removeClass("complete-on");
    });
});
/////////////////////////////////////
// animation
function hidenav() {
    $("nav__search-icon-big").addClass("active-flex");
    $(".nav__search-city").addClass("active-flex");
    $(".nav__search-date-start").addClass("active-flex");
    $(".nav__search-date-end").addClass("active-flex");
    $(".nav__search").addClass("nav__search-large");
    $(".nav__search-button").addClass("nav__search-button-large");
    $(".nav__search-icon-big").addClass("active-flex");
    $("#start-search").addClass("hidden");
}
$(window).bind("mousewheel", function(event) {
    $("#hidenav").show();
    $("nav__search-icon-big").removeClass("active-flex");
    $(".nav__search-city").removeClass("active-flex");
    $(".nav__search-date-start").removeClass("active-flex");
    $(".nav__search-date-end").removeClass("active-flex");
    $(".nav__search").removeClass("nav__search-large");
    $(".nav__search-button").removeClass("nav__search-button-large");
    $(".nav__search-icon-big").removeClass("active-flex");
    $("#start-search").removeClass("hidden");
});

// slider per impostare il range della ricerca fra 20km e 120 km///
var slider = (function() {
    var slider = document.getElementById("myRanges");
    var output = document.getElementById("range-value");

    output.innerHTML = slider.value;

    slider.oninput = function() {
        output.innerHTML = this.value;
    };
    /// slider da 20 a cento con sfondo custom
    function rangeslider() {
        $("#range-hidden").val($("#range-value").html());
        var range = (slider.value - 20) * 1.25;
        var color =
            "linear-gradient(90deg, rgb(230, 30, 77)" +
            range +
            "%, rgb(214,214,214)" +
            range +
            "%)";
        slider.style.background = color;
    }

    slider.addEventListener("mousemove", function() {
        rangeslider();
    });
    slider.addEventListener("touchmove", function() {
        rangeslider();
    });
    ///////////////////////////////////////////////////
})();
// chiamta che prende ip dell'utente e capisce la regione per ricerca nei paraggi
var getIp = (function() {
    $.ajax({
        mehtod: "GET",
        url: "https://api.ipdata.co",
        data: {
            "api-key":
                "b9bcf03b37c7c5b52f5297af16c2acf07e72d596a1cb8257ed1add0c"
        },
        success: function(risposta) {
            $("#ip-home-search").val(risposta.region);
        },
        error: function() {
            
        }
    });
})();

// chiamata api per controllare messaggi non letti
var unreadMessages = (function() {
    var id = $("#nav_user-id").val();
    $.ajax({
        url: "http://127.0.0.1:8000/api/unread",
        method: "GET",
        headers: {
            KEY: "test"
        },
        data: {
            id: id
        },
        success: function(risposta) {
            if (risposta.length > 0) {
                // messaggio per count 1
                if (risposta[0].unread == 1) {
                    $(".msg-msg").empty();
                    $(".msg-msg").append(
                        risposta[0].unread + " nuovo messaggio"
                    );
                    $(".msg-msg").append(`<i class="dot fas fa-circle"></i>`);
                    // messaggio per count > 1
                } else {
                    $(".msg-msg").empty();
                    $(".msg-msg").append(
                        risposta[0].unread + " nuovi messaggi"
                    );
                    $(".msg-msg").append(`<i class="dot fas fa-circle"></i>`);
                }
            } else {
                $(".msg-msg").empty();
                $(".msg-msg").append("Messaggi");
            }
        },
        error: function() {
            consol.log(arguments);
            alert("errore");
        }
    });
})();

// funzione per i suggerimenti nella search
function autoComplete(query) {
    if (query.length < 3 || query == "") {
        $("#auto-complete").removeClass("complete-on");
    }
    if (query != "" && isNaN(query) && query.length > 3) {
        tt.services
            .fuzzySearch({
                key: "31kN4urrGHUYoJ4IOWdAiEzMJJKQpfVk",
                query: query
            })
            .go()
            .then(function(response) {

                /// creaimo array vuoto in cui pushamo i rusultati della chiamata api a tomtom
                var address = [];
                var results = "";

                for (let i = 0; i < 4; i++) {
                    if (response.results[i]) {
                        // nel ciclo pusho i risulti in un array e controllo che non ci siano ripetizioni
                        var streetName =
                            response.results[i].address["streetName"];
                        var city = response.results[i].address["municipality"];
                        var countryCode =
                            response.results[i].address["countryCode"];

                        /// se l'indirizzo o la citta non e ripetuto lo pushamo nell'array di cui sopra 
                        if (
                            streetName != undefined &&
                            !address.includes(streetName) &&
                            city != undefined &&
                            !address.includes(city) &&
                            countryCode == "IT"
                        ) {
                            address.push(streetName + " " + city);
                        } else if (
                            streetName == undefined &&
                            city != undefined &&
                            !address.includes(city) &&
                            countryCode == "IT"
                        ) {
                            address.push(city);
                        }
                    }
                }
                //// appaendiamo l'array senza doppioni nell'autocomplete
                for (let i = 0; i < address.length; i++) {
                    results +=
                        '<div style="padding:1rem .5rem" class="complete-results">' +
                        address[i] +
                        "</div>";
                }
                //// se l'array non e vuoto facciamo apparire il menu autocoplete 
                if (address.length != 0) {
                    document.getElementById(
                        "auto-complete"
                    ).innerHTML = results;
                    $("#auto-complete").addClass("complete-on");
                } else {
                    $("#auto-complete").removeClass("complete-on");
                }
            });
    }
}

// VALIDAZIONE FORM incapulamento variabili da usare nelle funzione di validazione
var checkForm = (function() {
    return {
        letterNumber: /^[0-9a-zA-Z ]+$/,
        letter: /^[a-zA-Z' ]+$/,
        number: /^[0-9 ]+$/,
        allChar: /^[a-zA-Z0-9'!?@#àèòìù\$%\^\&*\)\( +=.,_-]+$/,
        dateR: /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/,
        emailR: /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    };
})();

// validazione input della pagina create e edit apartment
$("#title").keyup(function() {
    checkInput($(this), checkForm.allChar, 10, 300, "il titolo");
});
$("#address").keyup(function() {
    checkInput($(this), checkForm.allChar, 3, 300, "l'indirizzo");
});
$("#city").keyup(function() {
    checkInput($(this), checkForm.allChar, 1, 30, "la città");
});
$("#postal-code").keyup(function() {
    checkInput($(this), checkForm.allChar, 1, 20, "il cap");
});
$("#country").keyup(function() {
    checkInput($(this), checkForm.letter, 1, 30, "la nazione");
});
$("#description").keyup(function() {
    checkInput($(this), checkForm.allChar, 20, 2000, "la descrizione");
});
$("#daily-price").keyup(function() {
    checkInput($(this), checkForm.number, 1, 2000, "il prezzo");
});
$("#sm").keyup(function() {
    checkInput($(this), checkForm.number, 1, 2000, "i metri quadrati");
});
$("#rooms").keyup(function() {
    checkInput($(this), checkForm.number, 1, 2000, "le camere");
});
$("#beds").keyup(function() {
    checkInput($(this), checkForm.number, 1, 2000, "i letti");
});
$("#bathrooms").keyup(function() {
    checkInput($(this), checkForm.number, 1, 2000, "i bagni");
});

// al click del submit controlla se i campi soddisfano le condizioni e impedisce il submit del create e del edit apartment
$("#crea").click(function(e) {
    if (
        (checkInput($("#title"), checkForm.allChar, 10, 300, "il titolo") &&
            checkInput(
                $("#address"),
                checkForm.allChar,
                3,
                300,
                "l'indirizzo"
            ) &&
            checkInput($("#city"), checkForm.allChar, 1, 30, "la città") &&
            checkInput($("#postal-code"), checkForm.allChar, 1, 20, "il cap") &&
            checkInput($("#country"), checkForm.letter, 1, 30, "la nazione") &&
            checkInput(
                $("#description"),
                checkForm.allChar,
                20,
                2000,
                "la descrizione"
            ) &&
            checkInput(
                $("#daily-price"),
                checkForm.number,
                1,
                2000,
                "il prezzo prezzo"
            ) &&
            checkInput(
                $("#sm"),
                checkForm.number,
                1,
                2000,
                "i metri quadrati"
            ) &&
            checkInput($("#rooms"), checkForm.number, 1, 2000, "le camere") &&
            checkInput($("#beds"), checkForm.number, 1, 2000, "i letti") &&
            checkInput(
                $("#bathrooms"),
                checkForm.number,
                1,
                2000,
                "i bagni"
            )) ||
        checkInput($("#title"), checkForm.allChar, 10, 300, "il titolo") ||
        checkInput($("#address"), checkForm.allChar, 3, 300, "l'indirizzo") ||
        checkInput($("#city"), checkForm.allChar, 1, 30, "la città") ||
        checkInput($("#postal-code"), checkForm.allChar, 1, 20, "il cap") ||
        checkInput($("#country"), checkForm.letter, 1, 30, "la nazione") ||
        checkInput(
            $("#description"),
            checkForm.allChar,
            20,
            2000,
            "la descrizione"
        ) ||
        checkInput($("#daily-price"), checkForm.number, 1, 2000, "il prezzo") ||
        checkInput($("#sm"), checkForm.number, 1, 2000, "i metri quadrati") ||
        checkInput($("#rooms"), checkForm.number, 1, 2000, "le camere") ||
        checkInput($("#beds"), checkForm.number, 1, 2000, "i letti") ||
        checkInput($("#bathrooms"), checkForm.number, 1, 2000, "i bagni")
    ) {
        e.preventDefault();
    }
});

// validazione input della pagina register
$("#firstnameR").keyup(function() {
    checkInput($(this), checkForm.letter, 2, 50, "il nome");
});
$("#lastnameR").keyup(function() {
    checkInput($(this), checkForm.letter, 2, 50, "il cognome");
});
$("#emailR").keyup(function() {
    checkInput($(this), checkForm.emailR, 2, 255, "la mail");
});
$("#passwordR").keyup(function() {
    checkInput($(this), checkForm.allChar, 8, 255, "la password");
});
$("#password-confirmR").keyup(function() {
    if ($("#password-confirmR").val() != $("#passwordR").val()) {
        $(this).addClass("error");
        $(this)
            .next(".message-E")
            .addClass("message-on");
        $(this)
            .next(".message-E")
            .text("Le password non sono uguali");
    } else {
        $(this).removeClass("error");
        $(this)
            .next(".message-E")
            .removeClass("message-on");
    }
});
$("#dateR").focusout(function() {
    if ($("#dateR").val() == "") {
        $(this).addClass("error");
        $(this)
            .next(".message-E")
            .addClass("message-on");
        $(this)
            .next(".message-E")
            .text("Non hai inserito la data");
    } else {
        $(this).removeClass("error");
        $(this)
            .next(".message-E")
            .removeClass("message-on");
    }
});

// Al click del form register controlla se tutte le condizione sono soddisfatte
$("#registerR").click(function(e) {
    if (
        (checkInput($("#firstnameR"), checkForm.letter, 2, 50, "il nome") &&
            checkInput(
                $("#lastnameR"),
                checkForm.letter,
                2,
                50,
                "il cognome"
            ) &&
            checkInput($("#emailR"), checkForm.emailR, 2, 255, "la mail") &&
            checkInput(
                $("#passwordR"),
                checkForm.allChar,
                8,
                255,
                "la password"
            ) &&
            $("#password-confirmR").val() != $("#passwordR").val() &&
            $("#password-confirmR").val() == "" &&
            $("#dateR").val() == "") ||
        checkInput($("#firstnameR"), checkForm.letter, 2, 50, "il nome") ||
        checkInput($("#lastnameR"), checkForm.letter, 2, 50, "il cognome") ||
        checkInput($("#emailR"), checkForm.emailR, 2, 255, "la mail") ||
        checkInput($("#passwordR"), checkForm.allChar, 8, 255, "la password") ||
        $("#password-confirmR").val() != $("#passwordR").val() ||
        $("#password-confirmR").val() == "" ||
        $("#dateR").val() == ""
    ) {
        e.preventDefault();
    }
});
// fine pagina register

// validazione pagina login
$("#emailL").keyup(function() {
    checkInput($(this), checkForm.emailR, 2, 255, "la mail");
});
$("#passwordL").keyup(function() {
    checkInput($(this), checkForm.allChar, 8, 255, "la password");
});

$("#registerL").click(function(e) {
    if (
        (checkInput($("#emailL"), checkForm.emailR, 2, 255, "la mail") &&
            checkInput(
                $("#passwordL"),
                checkForm.allChar,
                8,
                255,
                "la password"
            )) ||
        checkInput($("#emailL"), checkForm.emailR, 2, 255, "la mail") ||
        checkInput($("#passwordL"), checkForm.allChar, 8, 255, "la password")
    ) {
        e.preventDefault();
    }
});
// fine validazione pagina login

// validazione invio messaggio pagina apartment
$("#firstnameM").keyup(function() {
    checkInput($(this), checkForm.letter, 2, 50, "il nome");
});
$("#lastnameM").keyup(function() {
    checkInput($(this), checkForm.letter, 2, 50, "il cognome");
});
$("#emailM").keyup(function() {
    checkInput($(this), checkForm.emailR, 2, 255, "la mail");
});

$("#messageM").keyup(function() {
    checkInput($(this), checkForm.allChar, 2, 2000, "il messsaggio");
});

$("#send-message").click(function(e) {
    if (
        (checkInput($("#firstnameM"), checkForm.letter, 2, 50, "il nome") &&
            checkInput(
                $("#lastnameM"),
                checkForm.letter,
                2,
                50,
                "il cognome"
            ) &&
            checkInput($("#emailM"), checkForm.emailR, 2, 255, "la mail") &&
            checkInput(
                $("#messageM"),
                checkForm.allChar,
                2,
                2000,
                "il messaggio"
            )) ||
        checkInput($("#firstnameM"), checkForm.letter, 2, 50, "il nome") ||
        checkInput($("#lastnameM"), checkForm.letter, 2, 50, "il cognome") ||
        checkInput($("#emailM"), checkForm.emailR, 2, 255, "la mail") ||
        checkInput($("#messageM"), checkForm.allChar, 2, 2000, "il messsaggio")
    ) {
        e.preventDefault();
    }
});
// fine validazione messaggio

// funzione per controllare lato client il form
function checkInput(selector, kind, min, max, field) {
    if (
        selector.val() == "" ||
        !matchKind(selector, kind) ||
        selector.val().length < min ||
        selector.val().length > max
    ) {
        selector.addClass("error");
        selector.next(".message-E").addClass("message-on");
        if (selector.val() == "") {
            selector.next(".message-E").text("Non hai inserito " + field);
        } else if (!matchKind(selector, kind)) {
            selector
                .next(".message-E")
                .text("Hai inserito un formato non valido");
        } else if (selector.val().length < min) {
            selector.next(".message-E").text("Il campo è troppo breve");
        } else if (selector.val().length > max) {
            selector.next(".message-E").text("Il campo è troppo lungo");
        }
        return true;
    } else {
        selector.removeClass("error");
        selector.next(".message-E").removeClass("message-on");
    }
}

// funzione per controllare se l'input soddisfa la condizione di tipo
function matchKind(selector, kind) {
    if (selector.val().match(kind)) {
        return true;
    }
    return false;
}
// chiamta che prende ip dell'utente e capisce la regione per ricerca nei paraggi
var getIp = (function() {
    $.ajax({
        mehtod: "GET",
        url: "https://api.ipdata.co/",
        data: {
            "api-key":
                "92f9e5e9b27bdc813e5552b9f01845c320c980dcaebb48b880455854"
        },
        success: function(risposta) {
            console.log(risposta);
            $("#ip-home-search").val(risposta.region);
        },
        error: function() {}
    });
})();


///////////////////////////////////////////////////////////////////
///////////////////animazioni menu mobile /////////////////////////
///////////////////////////////////////////////////////////////////

//// funzione per animare il menu in mobile farlo apparire e scomparire////
$(".hamburger-menu").click(function() {
    $(".hamburger-menu-bars-top").toggleClass(
        "hamburger-menu-bars-top-animated"
    );
    $(".hamburger-menu-bars-bottom").toggleClass(
        "hamburger-menu-bars-bottom-animated"
    );
    $(".hamburger-menu-bars").toggleClass("hamburger-menu-bars-animated");
    $(".hamburger-menu").toggleClass("hamburger-menu-animated");
    $(".mobile-menu").toggleClass("hidden");
});
$("#menu-bottom").click(function() {
    $(".hamburger-menu-bars-top").toggleClass(
        "hamburger-menu-bars-top-animated"
    );
    $(".hamburger-menu-bars-bottom").toggleClass(
        "hamburger-menu-bars-bottom-animated"
    );
    $(".hamburger-menu-bars").toggleClass("hamburger-menu-bars-animated");
    $(".hamburger-menu").toggleClass("hamburger-menu-animated");
    $(".mobile-menu").toggleClass("hidden");
});

/// animazione mobile menu quando il menu mobile tocca fondo pagina scopare per far vedere il footer senno riappare ///
$(window).scroll(function() {
    if (jQuery(window).width() <= 600) {
        if (
            $(window).scrollTop() + $(window).height() ==
            $(document).height()
        ) {
            $(".footer__menu-mobile").slideUp(100);
        } else {
            $(".footer__menu-mobile").slideDown(100);
        }
    }
});
