function addTooltip(data, drawnLayers, gatewayZones, redIcon){
    var mac_addr = data.reader_mac;
    var result = gatewayZones.filter(obj => obj.mac_addr === mac_addr)[0];
   
    if(typeof result !== 'undefined'){
      var floor = result.geoJson.floor;
      if (typeof floor == 'undefined'){
            floor = "1st Storey";     
      } 


      userCount = 0;
      drawnLayers[floor].eachLayer(function (layer) {
        console.log("LAYERS");
        console.log(drawnLayers[floor]);
        if (layer.mac === result.mac_addr){
          console.log(layer);
          userCount++;
          console.log(userCount);
        } // it's the marker
      });

      latArray = [-15, -15, -15, -30, -30, -30, -45, -45, -45];
      lngArray = [0, 15, -15];

      latv = latArray[userCount];
      lngv = lngArray[userCount%3];
      var string = "<b>Name</b>: ".concat(data.user.name,"<br> <b>Tag Mac</b>: ",data.tag_mac);
      if (result.geoJson.type == "Polygon"){
        var corner1 = result.geoJson.coordinates[0][0].map(Number);
        var corner2 = result.geoJson.coordinates[0][2].map(Number);
        var y = (corner1[0] + corner2[0])/2;
        var x = (corner1[1] + corner2[1])/2;
        var marker = L.marker({lng: (x+lngv),lat: (y+latv)}, {icon: redIcon}).bindTooltip(
          string
        );
        marker.id = "tempall"
        marker.mac = result.mac_addr;
        marker.addTo(drawnLayers[floor]);
      }else{
        var center = result.geoJson.coordinates.map(Number);
        var radius = result.geoJson.radius
        var marker = L.marker({lng: (center[0]+lngv), lat: (center[1]+latv)}, {icon: redIcon}).bindTooltip(
          string
        );
        marker.id = "tempall";
        marker.mac = result.mac_addr;
        marker.addTo(drawnLayers[floor]);
      }  
  }
}

  function drawZones(gatewayZones, drawnLayers, btIcon){
    console.log(gatewayZones);

    for (i = 0; i < gatewayZones.length; i++) {
        readerZone = gatewayZones[i];
        var mac_addr_s = readerZone.mac_addr;
        var location_s = readerZone.location;
        var string = "<b>Mac</b>:".concat(mac_addr_s,"<br> <b>Location</b>: ",location_s);
        var floor = readerZone.geoJson.floor;
        if (typeof floor == 'undefined'){
            floor = "1st Storey";     
        } 
        if (readerZone.geoJson.type == "Polygon"){
          var corner1 = readerZone.geoJson.coordinates[0][0].reverse().map(Number);
          var corner2 = readerZone.geoJson.coordinates[0][2].reverse().map(Number);
          var y = (corner1[0] + corner2[0])/2;
          var x = (corner1[1] + corner2[1])/2;
          var rectangle = L.rectangle([corner1, corner2]);
          rectangle.id = readerZone.id;
          rectangle.addTo(drawnLayers[floor]);
          var marker = L.marker({lng: x,lat: y}, {icon: btIcon}).bindTooltip(
              string
          );
          marker.id = readerZone.id;
          marker.addTo(drawnLayers[floor]);
        }else{
          var center = readerZone.geoJson.coordinates;
          var radius = readerZone.geoJson.radius;
          var circle = L.circle({lng: center[0],lat: center[1]}, {radius: radius});
          circle.id = readerZone.id;
          circle.addTo(drawnLayers[floor]);
          var marker = L.marker({lng: center[0],lat: center[1]}, {icon: btIcon}).bindTooltip(
              string
          );
          marker.id = readerZone.id;
          marker.addTo(drawnLayers[floor]);
        }
    }  
  }

  function saveAfterEdit(){

  }

  function drawUserLocation(data, drawnLayers, gatewayZones, floorIndex, redIcon){

    //Look for drawn zone
    console.log(data);
    var mac_addr = data[0].reader_mac;
    var result = gatewayZones.filter(obj => obj.mac_addr === mac_addr)[0];

  
    if(typeof result !== 'undefined'){
      var floor = result.geoJson.floor;
      if (typeof floor == 'undefined'){
            floor = "1st Storey";     
      } 
      //Programmatically go to required floor
      $('.leaflet-control-layers input').get(floorIndex[floor]).click();
      var string = "<b>Name</b>: ".concat(data[0].user.name,"<br> <b>Tag Mac</b>: ",data[0].tag_mac);
      if (result.geoJson.type == "Polygon"){
        var corner1 = result.geoJson.coordinates[0][0].map(Number);
        var corner2 = result.geoJson.coordinates[0][2].map(Number);
        var y = (corner1[0] + corner2[0])/2;
        var x = (corner1[1] + corner2[1])/2;
        var marker = L.marker({lng: x,lat: (y-15)}, {icon: redIcon}).bindPopup(
          string
        );
        marker.id = "temp";
        marker.addTo(drawnLayers[floor]);
        marker.openPopup();
      }else{
        var center = result.geoJson.coordinates;
        var marker = L.marker({lng: center[0], lat: (center[1]-15)}, {icon: redIcon}).bindPopup(
          string
        );
        marker.id = "temp";
        marker.addTo(drawnLayers[floor]);
        marker.openPopup();
      }

      console.log(drawnLayers[floor]);
    }else{
      alert("No Location Data or Valid Zone for this User");
    }
  }

  function removeAll(drawnLayers, id){
    console.log("LAYERSS");
    for (i = 0;i<drawnLayers.length;i++){
      drawnLayers[i].eachLayer(function (layer) {
        console.log("LAYERSS");
        console.log(layer);
        if (layer.id === id){
          console.log(layer);
          console.log(drawnLayers[i].hasLayer(layer));
          drawnLayers[i].removeLayer(layer);
          console.log(drawnLayers[i]);
        } // it's the marker
      });
    }
  }

    

  