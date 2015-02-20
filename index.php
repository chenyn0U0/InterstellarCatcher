<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Interstellar Catcher</title>

    <link rel="stylesheet" href="stylesheets/main.css"/>

    <script src="js/d3/d3.min.js" charset="utf-8"></script>
    <script src="js/jquery.js"></script>
    <script src="js/raphael.js"></script>

    <?php
    // $cip = $_SERVER["REMOTE_ADDR"];
    // if($cip=="::1") $cip="82.68.58.152";
    // $iptolocation = "'http://api.hostip.info/country.php?ip=".$cip."'";
    // $lalala= "
    //         <script>
    //         $(document).ready(function(){
    //         $.get( ".$iptolocation.",function( data ){});
    //         });
    //         </script>

    //         ";
    // echo $lalala;
    ?>

</head>

<body>
<div id="flashdiv"></div>



<script>
var paper = Raphael(0, 0, 2200, 1100);
var opicitychange=0.1;
var bgStar = new Array();
for(var i=0;i<300;i++){
 bgStar[i]=[Math.random()*2200,Math.random()*1100,Math.random()*3,Math.random(),opicitychange];
}
drawStar(paper,bgStar);

function drawStar(papaer,bgStar){
    papaer.clear();
    for(var i=0;i<bgStar.length;i++){
        var circle = paper.circle(bgStar[i][0], bgStar[i][1], bgStar[i][2]);
        circle.attr("fill", "#fff");
        circle.attr("opacity", bgStar[i][3]);
        if(bgStar[i][3]>=1){
            bgStar[i][4]=-opicitychange;
        }
        if(bgStar[i][3]<0){
            bgStar[i][4]=opicitychange;
        }
        bgStar[i][3]+=bgStar[i][4];

    }
}
setInterval("drawStar(paper,bgStar)", 100);
</script>


<div id="layer">
    <div id="starslayer">
    </div>
    <div id="earthlayer">
        <p style="color:white"></p>
        <img id="skyseer" title="See" src="img/skyseer.png"/>
        <img id="rocket" title="Back to your place" src="img/rocket.png"/>
        <p id="nowplanet">Edinburgh</p>
        <a id="moon" title="Today" href="javascript:void(0);"><img style="width:80px" src="img/moon.png" alt=""></a>
        <a id="sun" title="Next day" href="javascript:void(0);"><img style="width:90px" src="img/sun.png" alt=""></a>
        <p id="nowdate"></p>
        <p id="nowplanetvalue" style="display:none">EDI-sky</p>
    </div>
</div>




<script>

    var showdataamount=50;

    var alldata;
    var stardata=new Array();//{1.number in alldata(QuoteId); 2.price; 3.destination; 4.direct or not}




$.get( "exampledata.txt", function( data ) {

    function sortdata(){
        alldata=JSON.parse(data);

        alldata.Quotes.sort(function(x, y){ return x.MinPrice-y.MinPrice;});
        
        for(var j=0;j<showdataamount;j++){

            for(var i=0;i<alldata.Places.length;i++){
                if(alldata.Places[i].PlaceId==alldata.Quotes[j].OutboundLeg.DestinationId) {
                    stardata[stardata.length]=[alldata.Quotes[j].QuoteId,alldata.Quotes[j].MinPrice,alldata.Places[i].Name];
                }

            }
        }
        console.log(alldata);
        return(stardata);
     }


var data=sortdata();


// var data=[56,70,120,36,10,100,29,130,29,329,19,73,59,10,49,60,30,48];
gettoday();
dataupdate();
$("#skyseer").click(function(){dataupdate();});

$("#rocket").click(function(){$("#nowplanet").text("Edinburgh");dataupdate();});

function dataupdate(){
    rocketfly(500,1);
    flash();
    rocketback();
}

function bindstars(){
    var v1=35;
    var v2=45;
    var v3=60;
    var v4=70;
    var v5=100;


    $("#starslayer").text("");
    var stardiv= d3.select("#starslayer").selectAll("img")
        .data(data)
        .enter()
        .append("div")
        .attr("class","starContainer")
        .attr("style",function(d){
            var style="";
            style+="top:"+getHeight(0.1,0.6)+"px;"+"left:"+getWidth(0.1,0.9)+"px;";
            // if(d[1]>v3) style+="z-index:"+ (Math.random()*200);
            // else if(d[1]>v2) style+="z-index:"+ (Math.random()*200+50);
            // else if(d[1]>v1) style+="z-index:"+ (Math.random()*200+100);
            // else style+="z-index:"+ (Math.random()*200+200);
            return style;
         });


    var starpic= stardiv.append("img")
        .attr("src",function(d){
            var x=Math.random()*6;
            if(x<=1)return"img/planet1.png";
            if(x<=2)return"img/planet2.png";
            if(x<=3)return"img/planet3.png";
            if(x<=4)return"img/planet4.png";
            if(x<=5)return"img/planet5.png";
            else return"img/planet6.png";
        })
        .attr("title",function(d){
            var string="£"+d[1]+" - "+d[2];
            return string;
        })
        .attr("class",function(d){
            if(d[1]>v5)return"stars1";
            else if(d[1]>v4)return"stars2";
            else if(d[1]>v3)return"stars3";
            else if(d[1]>v2)return"stars4";
            else if(d[1]>v1)return"stars5";
            else return"stars6";
        })
        .on("mouseover", mouseOverPic)
        .on("mouseout", mouseOutPic)
        .on("click",starjump)
        ;

    var startext= stardiv.append("div")
        .attr("class","startextdiv")
        .append("p")
        .attr("title",function(d){
        var string="£"+d[1]+" - "+d[2];
        return string;
        })
        .text(function(d){
            if(d[1]>v2)return "";
            else return d[2];
        })
        .attr("id",function(d,i){
            return "name"+i;
        })
        .attr("class","starname")
        .on("click",starjump);


function mouseOverPic(d,i){
        // d3.select(this).classed("z-index","200");
        //     $(".starname").hide();
        //     $("#name" + i).show("slow");
}
function mouseOutPic(d,i){
     // d3.select(this).classed("z-index","0");
     //        $(".starname").stop().hide("fast");
}

function starjump(d){
    $("#nowplanet").text(d[2]);
    console.log(d[2]);
    dataupdate();
}


}

function addstyle(style,characteristic,value){
    if(style=="") style+=characteristic+":"+value;
    else style+=","+characteristic+":"+value;
};

function getWidth(min,max){//min>=0;max<=1
    return ($(window).width()*min)+(Math.random()*$(window).width()*(max-min));
}

function getHeight(min,max){//min>=0;max<=1
    return ($(window).height()*min)+(Math.random()*$(window).height()*(max-min));
}

function flash(){
 $("#flashdiv").show().animate({opacity:'0.9'},"fast",function(){bindstars();}).animate({opacity:'0'},"slow",function(){$("#flashdiv").hide();});
}

function rocketfly(x,y){
    // $("#rocket").rotate(45).animate({top:x,left:y}，"fast");
    $("#rocket").animate({top:y,opacity:'0'},"slow");
    // $("#rocket").css('top',y,"slow");

}

function rocketback(){
    $("#rocket").stop().css('top','inherit').animate({opacity:'1'},"slow");
}


	// $.ajax({
	//     url: 'http://partners.api.skyscanner.net/apiservices/browsequotes/v1.0/GB/GBP/en-GB/'+$("#nowplanetvalue").text()+'/anywhere/'+$("#nowdate").text()+'?apiKey=ilw05191304919538696117687255292&callback=chen',
	//     type: 'GET',
	//     // jsonpCallback: 'callback',
	//     crossDomain: true, // enable this
	//     dataType: 'jsonp',
	//     success: function(data) { 
	//     console.log(data) },
	//     error: function() {}
	//     // beforeSend: setHeader
	// });

//*********************************

    // $.ajax({
    //     url: 'http://partners.api.skyscanner.net/apiservices/autosuggest/v1.0/GB/GBP/en-GB?query='+$("#nowplanet").text()+'&apiKey=ilw05191304919538696117687255292&callback=chen',
    //     type: 'GET',
    //     jsonpCallback: 'callback',
    //     crossDomain: true, // enable this
    //     dataType: 'json',
    //     success: function(data) { 
    //     console.log(data) },
    //     error: function() {}
    //     // beforeSend: setHeader
    // });
//
//*********************

        // console.log(alldata);
        // for(var i=0;i<alldata.Quotes[i].length;i++){
        //     for(var j=0;j<stardata.length;j++){
        //         alldata.Quotes[i].MinPrice
        //     }
        // }
   


console.log(alldata);

$("#moon").click(function(){
    gettoday();
    dataupdate();
});

$("#sun").click(function(){
    turntonextday();
    dataupdate();
});

function turntonextday(){
    var date=new Date($("#nowdate").text());
    date=new Date(date.getTime()+(24*60*60*1000));
    var time=date.toLocaleDateString();
    time=time.replace(/\//g,'-');
    $("#nowdate").text(time);
    console.log(time);
    return time;
}

function gettoday(){
    var date=new Date(); 
    var time=date.toLocaleDateString();
    time=time.replace(/\//g,'-');
    $("#nowdate").text(time);
    console.log(time);
    return time;
}
});
</script>



</body>
</html>
<!-- 
<script type="text/javascript">
var thedata;
$.ajax({
        url: "http://partners.api.skyscanner.net/apiservices/browsequotes/v1.0/GB/GBP/en-GB/Edinburgh/anywhere/"+$("#nowdate").text()+"?apiKey=ilw05191304919538696117687255292&callback=chen",
        type: 'GET',
        contentType:'application/json',
        beforeSend: function(request) {
                    request.setRequestHeader("X-Forwarded-For:192.30.252.154");
                    },
        success: function (data) {
			console.log(data);
            alert("success");
        },
        error:function(a,b,c){
        	console.log(a,b,c);
        }
    });

</script> -->