<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
			.title-map{
				font: bold;
			}
			h1,h2,h3{
				padding: 0;
				margin: 0;
			}
    </style>
    <script type="text/javascript"
						src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDdVPYVZ3pFPvuSsulSrVKZyEIskNIjS-o&sensor=false">
    </script>
		<script type="text/javascript"
						src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js">
    </script>
    <script type="text/javascript">
			
			// To add the marker to the map, call setMap();
			var map;
			var markersArray = [];
			var markers = [];
			function initialize() {
				var haightAshbury = new google.maps.LatLng(-6.197912,106.843313);
				var mapOptions = {
					zoom: 12,
					center: haightAshbury,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				map =  new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

				google.maps.event.addListener(map, 'click', function(event) {
					addMarker(event.latLng);
				});
			}
			var mymarkers={};
			var compare={};
			(function poll(){
				$.ajax({ url: "<?php echo Yii::app()->getBaseUrl(true) ?>/index.php?r=service/getcourier", success: function(data){	
						var infowindow = new google.maps.InfoWindow();
						var marker, i;
						for(user_id in data) {	
							if(mymarkers[user_id]) {
								if(compare[user_id].long == data[user_id].long){
									console.log('sama');
								}else{
									console.log('tidaksama');
									compare[user_id]=data[user_id];
									marker = mymarkers[user_id];
									marker.setPosition(new google.maps.LatLng(data[user_id].lat,data[user_id].long));
									marker.setMap(map);
									console.log('change position '+user_id+' | '+data[user_id].name+' '+data[user_id].lat+', '+data[user_id].long);
								}
							} else {	
								i = data[user_id].name;
								//								var infowindow = new google.maps.InfoWindow(), marker, i;
								marker = new google.maps.Marker({
									position: new google.maps.LatLng(data[user_id].lat,data[user_id].long),
									map: map,
									icon: '<?php echo Yii::app()->getBaseUrl(true) ?>/img/motorcycle.png',
									title:data[user_id].name
								});
								
								var contentString = 
									'<h3><a href="<?php echo Yii::app()->getBaseUrl(true) ?>/index.php?r=user/view&id='+user_id+'" target="_blank">'+data[user_id].name+'</a></h3>'+data[user_id].lat+','+data[user_id].long;
								google.maps.event.addListener(marker, 'click', (function(marker, i, contentString) {
									return function() {
										infowindow.setContent(contentString);
										infowindow.open(map, marker);
										infowindow.setOptions({width:'230px',height:'100px'});
										console.log(i);
									}
								})(marker, i, contentString));
																
								marker.setMap(map);
								mymarkers[user_id]=marker;
								compare[user_id]=data[user_id];
								console.log(data);
							}
						}
							
					}, dataType: "json", complete: poll, timeout: 30000 });
			})();
    </script>
		<script type="text/javascript">
			//			var auto_insert = setInterval(
			//							function(){
			//								$('#insert_location').load('<?php echo Yii::app()->getBaseUrl() ?>/index.php?r=service/insertlocation').fadeIn('slow');
			//							},10000);
		</script>
  </head>
  <body onload="initialize()">
		<div id="insert_location"> </div>
    <div id="map_canvas" style="width:100%; height:100%"></div>
  </body>
</html>