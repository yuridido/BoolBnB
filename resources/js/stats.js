require("./bootstrap");
require("./aptSlider");
var $ = require("jquery");
var myfunction = (function () {
    var labels = [];
    var dataSet = [];
    var dataMessage = [];
    var labelMessage = [];
  $.ajax({
     url:'http://127.0.0.1:8000/api/stats',
     method: 'GET',
     headers: {
         KEY:'test'
     },
     data:{
         id:$('#stats-check').val(),
     },
     success:function(response){
         console.log(response);
          for (var i = 0; i <response.views.length; i++){
              labels.push(response.views[i].date);
              dataSet.push(response.views[i].daily_views);
          }
          for (var i = 0; i <response.messages.length; i++){
            labelMessage.push(response.messages[i].date_messages);
            dataMessage.push(response.messages[i].daily_messages);
        }
          console.log(labels,dataSet);
          compileChartViews(labels,dataSet,$('#chart-views'),'Visualizzazioni');
          compileChartViews(labelMessage,dataMessage,$('#chart-messages'),'Messaggi');
     },
     error:function(){
         console.log('connessione non riuscita');
     }
  });
  })();
  function compileChartViews(label,dataset,chart,title){
  var views = chart;
  var statChart = new Chart(views,{
       type: "line",
       data:{
           labels:label,
           datasets:[{
               label:'Annuali',
               data:dataset,
               borderColor:'rgb(252,100,45)',
               backgroundColor:'rgba(0,166,153,.4)'
           }],
       },
       animation: {
        animateScale: true
    },
    scales:{
        yAxes: [
            {
              ticks: {
                precision: 0,
              },
            },
          ],
    },
       options:{
           title:{
               text:title,
               display:true,
            },
            
      }
  });
}