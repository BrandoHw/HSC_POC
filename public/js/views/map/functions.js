function addTooltip(data, drawnLayers, gatewayZones, redIcon){
    var mac_addr = data.reader_mac;
    var result = gatewayZones.filter(obj => obj.mac_addr === mac_addr)[0];
   
    if(typeof result !== 'undefined'){
      var floor = result.alias;

      if (floor == null){
          floor = "Floor ".concat( result.number.toString());
      };
      
      userCount = 0;
      drawnLayers[floor].eachLayer(function (layer) {
  
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
      var string = "<b>Name</b>: ".concat(data.user.name,"<br> <b>Tag Mac</b>: ",data.tag_mac
                                         ,"<br> <b>Last Seen</b>: ", data.updated_at);
        var x= Number(result.geoJson.marker.lng);
        var y= Number(result.geoJson.marker.lat);
        console.log( typeof x);
        var marker = L.marker({lng: (x+lngv),lat: (y+latv)}, {icon: redIcon}).bindTooltip(
          string
        );
        marker.id = "tempall"
        marker.mac = result.mac_addr;
        marker.addTo(drawnLayers[floor]);
  }
}

  function drawZones(gatewayZones, drawnLayers, btIcon){
    console.log(gatewayZones);

    for (i = 0; i < gatewayZones.length; i++) {
        readerZone = gatewayZones[i];
        var mac_addr_s = readerZone.mac_addr;
        var location_s = readerZone.gateway.location.location_description;
        var string = "<b>Mac</b>:".concat(mac_addr_s,"<br> <b>Location</b>: ",location_s);
        var floor = readerZone.alias;

        if (floor == null){
            floor = "Floor ".concat( readerZone.number.toString());
        };
      
        if (readerZone.geoJson.type == "Polygon"){
          var corner1 = readerZone.geoJson.coordinates[0][0].reverse().map(Number);
          var corner2 = readerZone.geoJson.coordinates[0][2].reverse().map(Number);

          //Array is passed by reference so undo the reversal
          readerZone.geoJson.coordinates[0][0].reverse();
          readerZone.geoJson.coordinates[0][2].reverse();

          var rectangle = L.rectangle([corner1, corner2]);
          rectangle.id = readerZone.id;
          rectangle.addTo(drawnLayers[floor]);
          var marker = L.marker(readerZone.geoJson.marker, {icon: btIcon}).bindTooltip(
              string
          );
          marker.id = readerZone.id;
          marker.addTo(drawnLayers[floor]);
        }else{
          var center = readerZone.geoJson.coordinates;
          var radius = readerZone.geoJson.radius;
          var circle = L.circle({lng: center[0],lat: center[1]}, {radius: radius});
          circle.id = readerZone.id;
          // circle.setStyle({
          //   color:'red'
          // })
          circle.addTo(drawnLayers[floor]);
          var marker = L.marker(readerZone.geoJson.marker, {icon: btIcon}).bindTooltip(
              string
          );
          marker.id = readerZone.id;
          marker.addTo(drawnLayers[floor]);
        }
    }  

  }

  function drawUserLocation(data, drawnLayers, gatewayZones, floorIndex, redIcon){

    //Look for drawn zone that corresponds to the tag mac
    var mac_addr = data[0].reader_mac;
    var result = gatewayZones.filter(obj => obj.mac_addr === mac_addr)[0];

    //Do not draw if there is no corresponding zone
    if(typeof result !== 'undefined'){
      var floor = result.alias;

      if (floor == null){
          floor = "Floor ".concat( result.number.toString());
      };
      
      //Programmatically go to required floor
      $('.leaflet-control-layers input').get(floorIndex[floor]).click();
      var string = "<br> <b>Tag Mac</b>: ".concat(data[0].tag_mac,
                                          "<br> <b>Last Seen</b>: ", data[0].updated_at );
      var x= Number(result.geoJson.marker.lng);
      var y= Number(result.geoJson.marker.lat);
      var marker = L.marker({lng: x,lat: (y-15)}, {icon: redIcon}).bindPopup(
        string
      );

      // Create an element to hold all your text and markup
      var container = $('<div />');

      // Delegate all event handling for the container itself and its contents to the container
      container.on('click', '.smallPolygonLink', function() {
        alert("test");
      });

      // Insert whatever you want into the container, using whichever approach you prefer
      container.html("<b>Name</b>: ".concat("<a href='#' class='smallPolygonLink'>", data[0].user.name, "</a>"));
      container.append($('<span class="bold">').append(string))

      var link = $('<a href="#" class="speciallink">TestLink</a>').on('click', function() {
        alert("test");
      })[0];
      marker.bindPopup(container[0]);
      marker.id = "temp";
      marker.addTo(drawnLayers[floor]);
      marker.openPopup();
      console.log(drawnLayers[floor]);
    }else{
      alert("No Location Data or Valid Zone for this User");
    }
  }

  function removeAll(drawnLayers, id){
    for (i = 0;i<drawnLayers.length;i++){
      drawnLayers[i].eachLayer(function (layer) {
        if (layer.id === id){
          drawnLayers[i].removeLayer(layer);
        } 
      });
    }
  }



    

  