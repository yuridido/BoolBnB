require("./bootstrap");
//require("./apt");
require("./aptSlider");
require("./alert");
var $ = require("jquery");
const Handlebars = require("handlebars");
const {
    log
} = require("handlebars");
const {
    find
} = require("lodash");



var getMap = (function(){
const apiKey = "31kN4urrGHUYoJ4IOWdAiEzMJJKQpfVk";
let map = tt.map({
    key: apiKey,
    container: "map",
    center: [12.49334, 41.88996],
    style: "tomtom://vector/1/basic-main",
    zoom: 4
});
})();

///////// chiamata ajax per prensere coordianate appartamento e metterle nella mappa////
$.ajax({
    url:'http://127.0.0.1:8000/api/apartments/'+$('#app-id').html(),
    headers: {
        KEY: 'test'
    },
    success: function(response){
        let map = tt.map({
            key: '31kN4urrGHUYoJ4IOWdAiEzMJJKQpfVk',
            container: "map",
            //// prendo i dati e lei passo alla mappa 
            center: [ response[0].longitude,response[0].latitude],
            style: "tomtom://vector/1/basic-main",
            /// zommo la mappa sul punto
            zoom: 16
        });
        var element = document.createElement("div");
        element.id = "marker";
        var coordinates = [response[0].longitude,response[0].latitude];
        const marker = new tt.Marker({ element: element })
            .setLngLat(coordinates)
             //// appaendo il marker alla mappa////
            .addTo(map);

        var popupOffsets = {
            top: [0, 0],
            bottom: [0, -70],
            "bottom-right": [0, -70],
            "bottom-left": [0, -70],
            left: [25, -35],
            right: [-25, -35]
        };


    },error: function(){
        console.log(arguments);
    }
});
