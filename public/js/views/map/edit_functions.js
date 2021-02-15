function setupList(data, data_unassigned, drawnLayers, gatewayZones, floorIndex, icon){
    var options = {
        valueNames: [
        'serial',
        'location', 
        'mac',
        'online',
        { data: ['id', 'assigned'] }
        ],
        page: 10,
        pagination: true
    };
    var readerList = new List('reader-list-holder', options);
    //readerList.remove("name", "Name"); 
    readerList.clear();
    for (var i = 0; i < data.length; ++i) {
        readerList.add({serial: data[i].serial, location: data[i].location, mac: data[i].mac_addr, id: data[i].id, assigned: data[i].assigned});
    }

    for (var i = 0; i < data_unassigned.length; ++i) {
        readerList.add({serial: data_unassigned[i].serial, location: "Unassigned", 
                        mac: data_unassigned[i].mac_addr, id: data_unassigned[i].id, assigned: data_unassigned[i].assigned});
    }

    $('#reader-list').on('click', 'li', function() {
        //getUserLocation(this.getAttribute("data-id"));    
        if(this.getAttribute("data-assigned") == 1){
            drawGatewayLocation(this.getAttribute("data-id"),  drawnLayers, gatewayZones, floorIndex, icon)
        //Find zone and create popup
        }else{
            alert("Not assigned to a location");
        }
    })
    
}


function drawGatewayLocation(id, drawnLayers, gatewayZones, floorIndex, icon){

    //Look for drawn zone that corresponds to the same id
    var result = gatewayZones.filter(obj => obj.id == id)[0];

    //Do not draw if there is no corresponding zone
    if(typeof result !== 'undefined'){
        removeAllReaders(drawnLayers, 'temp')
        var floor = result.alias;

        if (floor == null){
        floor = "Floor ".concat( result.number.toString());
        };

        //Programmatically go to required floor
        $('.leaflet-control-layers input').get(floorIndex[floor]).click();
        var string = "<b>Location</b>: ".concat(result.location,"<br> <b>Mac</b>: ",result.mac_addr);
        var marker = L.marker(result.geoJson.marker, {icon: icon}).bindPopup(
        string
        );
        marker.id = "temp";
        marker.addTo(drawnLayers[floor]);
        marker.openPopup();
    }else{
      alert("No Location Data or Valid Zone for this User");
    }
  }

  function removeAllReaders(drawnLayers, id){
    for (var key in drawnLayers){
      drawnLayers[key].eachLayer(function (layer) {
        if (layer.id === id){
          drawnLayers[key].removeLayer(layer);
        } 
      });
    }
  }