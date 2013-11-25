<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    // function Timer(){this.t={};this.tick=function(a,b){this.t[a]=[(new Date).getTime(),b]};this.tick("start")}var loadTimer=new Timer;window.jstiming={Timer:Timer,load:loadTimer};if(window.external&&window.external.pageT)window.jstiming.pt=window.external.pageT;if(window.jstiming)window.jstiming.report=function(g,d){var c="";if(window.jstiming.pt){c+="&srt="+window.jstiming.pt;delete window.jstiming.pt}if(window.external&&window.external.tran)c+="&tran="+window.external.tran;var a=g.t,h=a.start;delete a.start;var i=[],e=[];for(var b in a){if(b.indexOf("_")==0)continue;var f=a[b][1];if(f)a[f][0]&&e.push(b+"."+(a[b][0]-a[f][0]));else h&&i.push(b+"."+(a[b][0]-h[0]))}if(d)for(var j in d)c+="&"+j+"="+d[j];(new Image).src=["http://csi.gstatic.com/csi?v=3","&s=gviz&action=",g.name,e.length?"&it="+e.join(",")+c:c,"&rt=",i.join(",")].join("")};
    // google.load('visualization', '1', {'packages':['annotatedtimeline']});
    // google.load('visualization', '1', {packages:['gauge']});
    // google.setOnLoadCallback(drawChart);
    // var csi_timer = new window.jstiming.Timer();
    // csi_timer.name = 'docs_gauge';

    // function drawChart() {
    //   csi_timer.tick('load');

    //   var data_line = new google.visualization.DataTable();
    //   data_line.addColumn('date', 'x');
    //   data_line.addColumn('number', 'y');

    //   data_line.addRows(1);

    //   data_line.setValue(0, 0, new Date());
    //   data_line.setValue(0, 1, 0);

    //   var data_gauge = new google.visualization.DataTable();
    //   data_gauge.addColumn('string', 'Label');
    //   data_gauge.addColumn('number', 'Value');
    //   data_gauge.addRows(1);
    //   data_gauge.setValue(0, 0, 'VALORES');
    //   data_gauge.setValue(0, 1, 0);

    //   csi_timer.tick('data');

    //   var formatter = new google.visualization.DateFormat({formatType: 'long'});
    //   formatter.format(data_line,0);


    //   var chart_line = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_div_line'));  
    //   var chart_gauge = new google.visualization.Gauge(document.getElementById('chart_div_gauge'));
    //   csi_timer.tick('new');

    //   var options_line = {
    //     title: 'esta como quiere',
    //     dateFormat: 'HH:mm:ss',
    //     displayRangeSelector:false,
    //     displayZoomButtons: false,
    //     allowRedraw: true,
    //     fill: 5,
    //     displayAnnotations: false,
    //     allValuesSuffix: '[m]',
    //     displayAnnotationsFilter: true
    //   }

    //   var options_gauge = {width: 400, height: 120, redFrom: 5, redTo: 7,
    //     yellowFrom:3, yellowTo: 5, minorTicks: 5, greenFrom: 0, greenTo: 3, max:7};
      
    //   chart_line.draw(data_line, options_line);
    //   chart_gauge.draw(data_gauge, options_gauge);
    //   csi_timer.tick('draw');
    //   window.jstiming.report(csi_timer);

      
    //   setInterval(function() {
    //     var json = $.ajax({
    //        url: 'json_status.php', // make this url point to the data file
    //        dataType: 'json',
    //        async: false
    //       }).responseText;

    //       var obj = jQuery.parseJSON(json);
    //       // console.log(json);
    //       // data_gauge.setValue(0, 1, obj.value);
           

    //       var dataJson = eval(json);
    //       for (var i in dataJson){
    //          data_line.setValue(parseInt(i),0,new Date());
    //          data_line.setValue(parseInt(i),1,dataJson[i].value);
    //          data_gauge.setValue(0, 1, dataJson[i].value);
    //          if (dataJson[i].value > 5){
    //             $("#status").text('critico').css("color","red").show();
    //          }else{
    //             $("#status").hide();
    //          }
    //       } 
    //       // console.log(obj.value);
    //       // data.setValue(0, 1, obj.value);
    //        chart_line.draw(data_line, options_line);
    //        chart_gauge.draw(data_gauge, options_gauge);
    //     }, 2000);    
    // }

</script>
