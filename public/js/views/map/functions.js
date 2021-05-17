/*
data = Beacon info/position
drawnLayers = The Map Layers which hold the markers
gatewayZones = The information of each blue gatewayzone
max_count = The total number of people in a given zone
current_count = The number of people that have been drawn so far for a given zone
dialog = Dialog holder to contain the list that appears when multiple markers have converged into one big marker
*/
function addTooltip(data, drawnLayers, gatewayZones, max_count, current_count, dialog){
    var mac_addr = data.gateway.mac_addr;
    var full_name;
    if (data.hasOwnProperty('resident')){
      full_name = data.resident.resident_fName.concat(" ", data.resident.resident_lName)
    }
    else if (data.hasOwnProperty('staff')){
      full_name = data.staff.fName.concat(" ", data.staff.lName)
    }
    var tag_mac = data.beacon_mac;
    var last_seen = data.updated_at;
    var result = gatewayZones.filter(obj => obj.mac_addr === mac_addr)[0];
    var isBigMarker = false;
    var isMarkerDrawn = true;
    if(typeof result !== 'undefined'){
      var floor = result.alias;

      if (floor == null){
          floor = "Floor ".concat( result.number.toString());
      };
      
      if (current_count == max_count){
        userCount = 0
        isBigMarker = true;
      }
      else if (current_count > max_count){
        userCount = 0
        isMarkerDrawn = false;
      }else{
        userCount = current_count
      }
      latArray = [-15, -15, -15, -30, -30, -30, -45, -45, -45];
      lngArray = [0, 15, -15];

      latv = latArray[userCount];
      lngv = lngArray[userCount%3];

      var Icon = L.icon({
        iconUrl: imageUrl + "/redmarker.png", //IconSizeFinder
        iconSize: [20,15],
        className: 'blinking'
        });

      var string = "<b>Name</b>: ".concat(full_name, "<br> <b>Tag Mac</b>: ", tag_mac
                                         ,"<br> <b>Last Seen</b>: ", last_seen);

      if (isBigMarker){
        string = max_count.toString().concat(" People in this location");
        Icon = L.icon({
          iconUrl: imageUrl + "/orangemarker.png", //IconSizeFinder
          iconSize: [18,18],
          className: 'blinking'
        });
        latv = -30;
      }
      var x= Number(result.geoJson.marker.lng);
      var y= Number(result.geoJson.marker.lat);
      var marker = L.marker({lng: (x+lngv),lat: (y+latv)}, {icon: Icon}).bindTooltip(
        string
      );
      marker.id = "tempall"
      marker.mac = result.mac_addr;

      if (isBigMarker){
        marker.on({
          click: function(){
            fillMarkerList(mac_addr, max_count, dialog)
          }
        })
     }
     if (isMarkerDrawn){
      marker.addTo(drawnLayers[floor]);
    }
  }
}

function fillMarkerList(mac_addr, max_count, dialog){
  //May need to refresh CSRF Token as this page will be idle for a long time
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({
    url: "/user/group",
    method: 'get',             
    data: { mac_addr: mac_addr},
    success: function(data){
        // console.log("success");
        // console.log(data);
        users = data;
        var options = {
          valueNames: [
          'name', 
          'tag',
          'last_seen',
          { data: ['id'] }
          ],
          page: 10,
          pagination: true
      };

        userListMarker = new List('user-list-holder-marker', options);
     
        //userList.remove("name", "Name"); 
        userListMarker.clear();

        for (var i = 0; i < users.length; ++i) {
          var full_name;
          if (users[i].hasOwnProperty('resident')){
              full_name = users[i].resident.resident_fName.concat(" ", users[i].resident.resident_lName)
          }
          else if (users[i].hasOwnProperty('staff')){
              full_name = users[i].staff.fName.concat(" ", users[i].staff.lName)
          }
          userListMarker.add({name: full_name, 
            tag: users[i].beacon_mac,
            last_seen: users[i].updated_at,
            id: users[i].beacon_id});
        }
        $( "#dialog-form" ).dialog({ title: max_count.toString().concat(" Residents in ", users[0].gateway.location.location_description) });
        dialog.dialog('open');
    },
    error: function(xhr, request, error){
        console.log('error');
        console.log(xhr.responseText);
    },
  });
  
}

function drawZones(gatewayZones, drawnLayers, btIcon){
  console.log(gatewayZones);

  for (i = 0; i < gatewayZones.length; i++) {
      gatewayZone = gatewayZones[i];
      var mac_addr_s = gatewayZone.mac_addr;
      var location_s = gatewayZone.gateway.location.location_description;
      var string = "<b>Mac</b>:".concat(mac_addr_s,"<br> <b>Location</b>: ",location_s);
      var floor = gatewayZone.alias;

      if (floor == null){
          floor = "Floor ".concat( gatewayZone.number.toString());
      };
    
      if (gatewayZone.geoJson.type == "Polygon"){
        // var corner1 = gatewayZone.geoJson.coordinates[0][0].reverse().map(Number);
        // var corner2 = gatewayZone.geoJson.coordinates[0][2].reverse().map(Number);

        // //Array is passed by reference so undo the reversal
        // gatewayZone.geoJson.coordinates[0][0].reverse();
        // gatewayZone.geoJson.coordinates[0][2].reverse();

        // var rectangle = L.rectangle([corner1, corner2]);
        // rectangle.id = gatewayZone.id;
        // rectangle.addTo(drawnLayers[floor]);
        polygon_coord = gatewayZone.geoJson.coordinates[0];
        polygon_coord.forEach( function (item, index){
            item.reverse();
        })
        polygon_s = L.polygon(polygon_coord);
        polygon_s.id = gatewayZone.id;
        polygon_s.addTo(drawnLayers[floor]);
        var marker = L.marker(gatewayZone.geoJson.marker, {icon: btIcon}).bindTooltip(
            string
        );
        var marker = L.marker(gatewayZone.geoJson.marker, {icon: btIcon}).bindTooltip(
            string
        );
        marker.id = gatewayZone.id;
        marker.addTo(drawnLayers[floor]);
        polygon_coord.forEach( function (item, index){
          item.reverse();
        })
      }else{
        var center = gatewayZone.geoJson.coordinates;
        var radius = gatewayZone.geoJson.radius;
        var circle = L.circle({lng: center[0],lat: center[1]}, {radius: radius});
        circle.id = gatewayZone.id;
        // circle.setStyle({
        //   color:'red'
        // })
        circle.addTo(drawnLayers[floor]);
        var marker = L.marker(gatewayZone.geoJson.marker, {icon: btIcon}).bindTooltip(
            string
        );
        marker.id = gatewayZone.id;
        marker.addTo(drawnLayers[floor]);
      }
  }  

  }

function drawUserLocation(data, drawnLayers, gatewayZones, floorIndex, redIcon){

  var redIcon = L.icon({
    iconUrl: imageUrl + "/redmarker.png",
    iconSize: [20,20], //IconSizeFinder
    className: 'blinking'
  });

  //Look for drawn zone that corresponds to the tag mac
  data = data[0];
  console.log(data);
  console.log(data.hasOwnProperty('resident'));
  var mac_addr = data.gateway.mac_addr;
  var full_name;
  if (!(data.resident === null)){
    full_name = data.resident.resident_fName.concat(" ", data.resident.resident_lName)
  }
  else if (!(data.staff === null)){
    full_name = data.staff.fName.concat(" ", data.staff.lName)
  }
  var tag_mac = data.beacon_mac;
  var last_seen = data.updated_at;
  var result = gatewayZones.filter(obj => obj.mac_addr === mac_addr)[0];

  //Do not draw if there is no corresponding zone
  if(typeof result !== 'undefined'){
    var floor = result.alias;

    if (floor == null){
        floor = "Floor ".concat( result.number.toString());
    };
    
    //Programmatically go to required floor
    $('.leaflet-control-layers input').get(floorIndex[floor]).click();
    var string = "<br> <b>Tag Mac</b>: ".concat(tag_mac,
                                        "<br> <b>Last Seen</b>: ", last_seen);
    var x= Number(result.geoJson.marker.lng);
    var y= Number(result.geoJson.marker.lat);
    var marker = L.marker({lng: x,lat: (y+25)}, {icon: redIcon}).bindPopup(
      string
    );

    // Create an element to hold all your text and markup
    var container = $('<div />');

    // Delegate all event handling for the container itself and its contents to the container
    container.on('click', '.smallPolygonLink', function() {
      alert("test");
    });

    // Insert whatever you want into the container, using whichever approach you prefer
    container.html("<b>Name</b>: ".concat("<a href='#' class='smallPolygonLink'>", full_name, "</a>"));
    container.append($('<span class="bold">').append(string))

    var link = $('<a href="#" class="speciallink">TestLink</a>').on('click', function() {
      // alert("test");
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



    

  