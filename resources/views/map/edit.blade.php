@extends('layouts.app')

@section('content')
<html>
    <head>
        <link href="{{ asset('css/map/leaflet.css') }}" rel="stylesheet">
        <link href="{{ asset('css/map/leaflet.draw.css') }}" rel="stylesheet">
        <link href="{{ asset('css/map/map.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/views/map/functions.js')}}"></script>
        
        <script src="{{ asset('js/views/map/edit_functions.js')}}"></script>
      <meta charset="utf-8" />

      <title>A Leaflet map!</title>

    </head>

    <body>

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Maps</strong> Management</h3>
        </div>
    </div>
    
    <div style="width:250px;height:400px;line-height:3em;overflow:scroll;padding:5px;background-color: #eee;display: inline-block;">
        <div id="reader-list-holder">
            <input type="text" class="search" placeholder="Search" />
        
            <ul id="reader-list" class="list" style="display: inline-block">
                <li id = "first-item"><h3 class="serial">Serial</h3>
                    <h3 class="location">Location</h3>
                    <p class="mac">Mac</p>
                    <p class="online">Online</p>
                </li>
            </ul> 
            <ul class="pagination"></ul>
        </div>
    </div>
        <div id="map" style="width: 600px; height: 400px; display: inline-block"></div>
    </body>


    <script>
        $( function() {
            var dialog, form,

            location = $( "#location" ),
            //allFields = $( [] ).add( name ).add( mac ).add( location ),
            tips = $( ".validateTips" );
            center_x = 0;
            center_y = 0;
            radius = 0;
            geoJson = new Object();
            newZone = new Object();
            selectedData = new Object();
            currentFloor = "";

            /*
            Gateway Zones - All drawn gateway zones for a given building
            Readers - All readers that have not been assigned a gateway zone
            Building - The Current Building
            Floors - The floors for the given building
            */
            gatewayZones = <?php echo $gatewayZones; ?>;
            readers = <?php echo $readers; ?>;
            building = <?php echo $building; ?>;
            floors = <?php echo $floors; ?>;
            console.log(floors);
            selectData = $.map(readers, function (obj) {
                obj.text = obj.text || obj.mac_addr; // replace name with the property used for the text
                return obj;
            });

            var btIcon = L.icon({
                iconUrl: "{{url('/css/images/bt.png')}}",
                iconSize: [25,25]
            });

            for (var i = 0; i < selectData.length; i++){
                if (selectData[i].alias == null){
                    selectData[i].alias =  "Floor ".concat(selectData[i].number.toString())
                }
            }

            $('#selReader').change(function(){
                selectedData = $('#selReader').select2('data')[0];
                console.log(selectedData);
            });
            
            var set_delay = 5000;
            var dataSet;

            deleteGatewayZones = function (idArray){
                $.ajax({
                    url: "{{ route('zone.destroy') }}",
                    method: 'post',        
                    data: { 
                        id: idArray
                    },   
                    success: function(data){
                        console.log(data);
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    error: function(xhr, request, error){
                        console.log(xhr.responseText);
                    },
                })
            }
            
            editGatewayZones = function (Gateways){
                console.log(gatewayZones);
                $.ajax({
                    url: "{{ route('zone.update') }}",
                    method: 'post',        
                    data: { 
                        gateways: Gateways
                    },   
                    success: function(data){
                        console.log(data);
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    error: function(xhr, request, error){
                        console.log(xhr.responseText);
                    },
                })
            }

        function drawMarkers(data){
            id = data.id;
            e = newZone;
            e.layer.id = id;
            console.log(e.layer);
            drawnLayers[currentFloor].addLayer(e.layer);
            if (e.layerType == "rectangle"){
                corner1 = e.layer.toGeoJSON().geometry.coordinates[0][0];
                corner2 = e.layer.toGeoJSON().geometry.coordinates[0][2];
                x = (corner1[0] + corner2[0])/2;
                y = (corner1[1] + corner2[1])/2;

                //drawnLayers[currentFloor].addLayer(L.marker(data.geoJson.marker, {icon: btIcon}));
                var string = "<b>Mac</b>:".concat(selectedData.mac_addr,"<br> <b>Location</b>: ",location.val());
                var marker = L.marker({lng: x,lat: y}, {icon: btIcon}).bindTooltip(
                    string
                );
                marker.id = id;
                marker.addTo(drawnLayers[currentFloor]);
                geoJson = e.layer.toGeoJSON().geometry;
            }
            else{
                //drawnLayers[currentFloor].addLayer(L.marker(data.geoJson.marker, {icon: btIcon}));
                var string = "<b>Mac</b>:".concat(selectedData.mac_addr,"<br> <b>Location</b>: ",location.val());
                var marker = L.marker(e.layer.getLatLng(), {icon: btIcon})
                        .bindTooltip(string)
                marker.id = id;
                marker.addTo(drawnLayers[currentFloor]);
                geoJson = e.layer.toGeoJSON().geometry;
                geoJson.radius = e.layer.getRadius();
            }
        
        }

        function addReaderZone() {
            var valid = true;
            //allFields.removeClass( "ui-state-error" );

            if ( valid ) {
            
                console.log(location.val());
                console.log(selectedData.mac_addr);
                console.log(geoJson);
                $.ajax({
                        url: "{{ url('zones') }}",
                        method: 'post',             
                        data: {location: location.val(),
                            mac_addr: selectedData.mac_addr,
                            geoJson: geoJson
                        },
                        success: function(data){
                            console.log("success");
                            console.log(data);
                            gatewayZones.push(data)[0];
                            console.log(gatewayZones);
                            drawMarkers(data);
                            selectData = selectData.filter(reader => reader.mac_addr != selectedData.mac_addr);
                            $("#selReader").empty();
                            $("#selReader").select2({
                                data: selectData.filter(gateway => gateway.alias == currentFloor)
                            });
                             selectedData = $('#selReader').select2('data')[0];
 
                        },
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        error: function(xhr, request, error){
                            console.log('error');
                            console.log(xhr.responseText);
                        },
                });
                dialog.dialog( "close" );
            }
            return valid;
        };

        dialog = $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 400,
        width: 350,
        modal: true,
        buttons: {
            "Create Gateway": addReaderZone,
            Cancel: function() {
            dialog.dialog( "close" );
            }
        },
        close: function() {
            //form[ 0 ].reset();
            //allFields.removeClass( "ui-state-error" );
        }
        });
    
        form = dialog.find( "form" ).on( "submit", function( event ) {
        event.preventDefault();
        //addReaderZone();
        });
    
        $( "#create-user" ).button().on( "click", function() {
        dialog.dialog( "open" );
        });


        var map = L.map("map", {
            crs: L.CRS.Simple,
            minZoom: -4,
        }); //CRS simple referring to normal coordinate value

       
        //Map
        var drawnLayers = new Object();
        var drawnLayersArray = []
        var floorIndex = new Object();
        var baseLayer = new Object();
        var bounds = new Object();
        var drawControl;
        drawControlLayer = function(controlLayer){
            drawControl = new L.Control.Draw({
                draw: {
                    polygon: false,
                    marker: false,
                    polyline: false,
                    circlemarker: false
                },
                edit: {
                    featureGroup: controlLayer
                }
            });
             map.addControl(drawControl);
        }

        //Add Empty Base Layer
        var layercontrol = L.control.layers(baseLayer).addTo(map);

        //Add gatewayzones and floor image to appropriate layer
        for (var i = 0; i < floors.length; i++) {
            alias = floors[i].alias;
            url = floors[i].map.url;
            if (alias == null){
                alias = "Floor ".concat(floors[i].number.toString());
            };
            drawnLayers[alias] = new L.featureGroup();
            drawnLayersArray.push(drawnLayers[alias]);
            floorIndex[alias] = i;
            var img = new Image();
            img.src = url;
            baseLayer[alias] =  L.imageOverlay("storage/greyimage.png", [[0,0], [36, 35]]);;
            img.onload = (function (alias, url) {
                return function() {
                    bounds[alias] =  [[0,0], [this.height, this.width]];
                    baseLayer[alias] = L.imageOverlay(url, bounds[alias]);
                 
                    // for (var key in drawnLayers){
                    //     map.removeLayer(drawnLayers[key]);
                    // }
                    // map.addLayer(drawnLayers[alias]);
                    currentFloor = alias;
                    map.fitBounds(bounds[alias]);
                    console.log(baseLayer);
                    layercontrol.remove();
                    layercontrol = L.control.layers(baseLayer).addTo(map);
                    // var layercontrol = L.control.layers(baseLayer).addTo(map);
                    $('.leaflet-control-layers input').get(floorIndex[currentFloor]).click();
                }
            }(alias, url));  
        };
  
        //Setup list of gateways
        setupList(gatewayZones, readers, drawnLayers, gatewayZones, floorIndex, btIcon);

        //Filter reader data to only display current floor
        $("#selReader").select2({
            data:selectData.filter(gateway => gateway.alias == currentFloor)
        });
        selectedData = $('#selReader').select2('data')[0];

 
        drawZones(gatewayZones, drawnLayers, btIcon);


        map.on('draw:created', function (e) {
            var type = e.layerType,
                layer = e.layer;
            console.log(layer);
            //drawnItems.addLayer(layer);
            dialog.dialog('open');

            console.log('On draw:created', e.target);
            console.log(e.type, e);
            console.log(e.layerType);
        
            var btIcon = L.icon({
            iconUrl: "{{url('/css/images/bt.png')}}",
            iconSize: [25,25],
        });

            newZone = e;
            geoJson = e.layer.toGeoJSON().geometry;

            if (e.layerType == "rectangle"){
                corner1 = e.layer.toGeoJSON().geometry.coordinates[0][0];
                corner2 = e.layer.toGeoJSON().geometry.coordinates[0][2];
                x = (corner1[0] + corner2[0])/2;
                y = (corner1[1] + corner2[1])/2;
                geoJson.marker = {lng: x, lat: y};
            }

            if (e.layerType == "circle"){
                geoJson.radius = e.layer.getRadius();
                console.log(geoJson.radius);
                geoJson.marker = {lng: e.layer.getLatLng().lng, lat: e.layer.getLatLng().lat};
            }
        });

        map.on('draw:edited', function (e) {
            //console.log(e);
            var layers = e.layers;
            result2 = new Array();
            layers.eachLayer(function (layer) {
                var result = gatewayZones.filter(obj => {return obj.id === layer.id});
           
                //gatewayZone.id = layer.id; 
         
                if (layer instanceof L.Rectangle) {
                    result[0].geoJson.coordinates = layer.toGeoJSON().geometry.coordinates;
                }
                if (layer instanceof L.Circle) {
                    result[0].geoJson.coordinates = layer.toGeoJSON().geometry.coordinates;
                    result[0].geoJson.radius = layer.getRadius();
                }

                if (layer instanceof L.Marker) {
                    result[0].geoJson.marker = {lat: layer.getLatLng().lat.toString(), lng: layer.getLatLng().lng.toString() };
                }
                //console.log(layer);
                result2.push(result[0])
            });
            editGatewayZones(result2);
        });

        map.on('draw:deleted', function (e) {
            var type = e.layerType,
                layers = e.layers;
            console.log(layers);
            // //drawnItems.addLayer(layer);
            idArray = []
            layers.eachLayer(function (layer) {
                console.log(layer);
                if (!idArray.includes(layer.id))
                    idArray.push(layer.id);
            });
            console.log(idArray);
            deleteGatewayZones(idArray);
        });
        map.on('baselayerchange', function(e) {
            for (var key in drawnLayers){
                map.removeLayer(drawnLayers[key]);
            }
            map.addLayer(drawnLayers[e.name]);
            currentFloor = e.name;

            if (drawControl != null){
                map.removeControl(drawControl);
            }
            drawControlLayer(drawnLayers[currentFloor]);
            

            $("#selReader").empty();
            $("#selReader").select2({
                data:selectData.filter(gateway => gateway.alias == currentFloor)
            });

            selectedData = $('#selReader').select2('data')[0];

            // layercontrol.remove();
            // layercontrol = L.control.layers(baseLayer).addTo(map);
            // console.log(baseLayer);
        });

    });
    </script>


    <body>
        <div id="dialog-form" title="Create new Gateway">
      
            <fieldset>
        
            <label for="selReader">Mac Address</label>
            <select id='selReader' style='height: 200px; width: 295px;'></select>
            <label for="location">Location</label>
            <input type="text" name="location" id="location"  class="text ui-widget-content ui-corner-all" >
        
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type="submit" tabindex="-1" style="position:absolute; top:-90px">
            </fieldset>
        </form>
        </div>
    </body>

@endsection