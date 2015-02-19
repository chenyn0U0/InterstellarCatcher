<!DOCTYPE html>  
<html lang="en">  
<head>
	<meta charset="UTF-8">
	<title>Interstellar Catcher</title>

	<link rel="stylesheet" href="stylesheets/main.css"/>

	<script src="js/d3/d3.min.js" charset="utf-8"></script>
	<script src="js/jquery.js"></script>
	<script src="js/raphael.js"></script>

	<?php
	$cip = $_SERVER["REMOTE_ADDR"];
	if($cip=="::1") $cip="82.68.58.152";
	$iptolocation = "'http://api.hostip.info/country.php?ip=".$cip."'";
	$lalala= "
			<script>
			var nowLocation;
			$.get( ".$iptolocation.",function( data ){nowLocation=data;console.log(nowLocation);});
			</script>
			";
	echo $lalala;
	?>

</head>

<body>




<script>
var paper = Raphael(0, 0, 2200, 1100);
var opicitychange=0.1;
var bgStar = new Array();
for(var i=0;i<200;i++){
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
		<img src="img/skyseer.png"/><img src="img/rocket.png" style="height:150px"/>
	</div>
</div>




<script>
var lowPrice=80;


var data=[56,70,120,36,10,100,29,130,29,329,19,73,59,10,49,60,30,48];
d3.select("#starslayer").selectAll("img")
	.data(data)
    .enter()
    .append("div")
    .attr("class","starContainer")
    .attr("style",function(d){
		var style="";
		style+="top:"+getHeight(0.1,0.6)+"px;"+"left:"+getWidth(0.1,0.9)+"px;";
		return style;
	 })
    .append("img")
    .attr("src",function(d){
    	var x=Math.random()*6;
    	if(x<=1)return"img/planet1.png";
    	if(x<=2)return"img/planet2.png";
    	if(x<=3)return"img/planet3.png";
    	if(x<=4)return"img/planet4.png";
    	if(x<=5)return"img/planet5.png";
    	else return"img/planet6.png";

    })
    .attr("class",function(d){
    	if(d>80)return"stars1";
    	if(d>60)return"stars2";
    	if(d>40)return"stars3";
    	if(d>30)return"stars4";
    	if(d>20)return"stars5";
    	else return"stars6";
    });

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



</script>





</body>
</html>