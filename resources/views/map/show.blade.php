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
      <meta charset="utf-8" />

      <title>A Leaflet map!</title>

    </head>

    <body>
    <div style="width:250px;height:400px;line-height:3em;overflow:scroll;padding:5px;display: inline-block;">
        <div id="user-list-holder">
            <input type="text" class="fuzzy-search" placeholder="Search" />
     
        <ul id="user-list" class="list" style="display: inline-block">
            <li id = "first-item"><h3 class="name">Name</h3>
                <p class="tag">Tag</p>
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
            gatewayZones = <?php echo $gatewayZones; ?>;
            center_x = 0;
            center_y = 0;
            radius = 0;
            geoJson = new Object();
            newZone = new Object();
            selectedData = new Object();
            currentFloor = "1st Storey";
            readers = <?php echo $readers; ?>;
            selectData = $.map(readers, function (obj) {
                obj.text = obj.text || obj.mac_addr; // replace name with the property used for the text
                return obj;
            });

            var btIcon = L.icon({
                iconUrl: "{{url('/css/images/bt.png')}}",
                iconSize: [25,25]
            });

            console.log(selectData);


            $("#selReader").select2({
                data:selectData
            });

            selectedData = $('#selReader').select2('data')[0];
            $('#selReader').change(function(){
                location.readOnly = true; 
                selectedData = $('#selReader').select2('data')[0];

              
            });
            

            var set_delay = 5000;
            var dataSet;

            getUserLocation = function(id){
                var redIcon = L.icon({
                    iconUrl: "{{url('/css/images/redmarker.png')}}",
                    iconSize: [50,50],
                    className: 'blinking'
                });
                console.log(id);
                $.ajax({
                    url: "{{ url('user/get') }}",
                    method: 'get',        
                    data: { 
                        id: id 
                    },   
                    success: function(data){
                        console.log(data);
                        removeAll(drawnLayersArray, 'temp');
                        drawUserLocation(data, drawnLayers, gatewayZones, floorIndex, redIcon);
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    error: function(xhr, request, error){
                        console.log(xhr.responseText);
                    },
                })
            }
            getUserData = function(){
                $.ajax({
                    url: "{{ url('user-position') }}",
                    method: 'get',             
                    success: function(data){
                        console.log(data);
                        dataSet = data;
                        
                        var options = {
                            valueNames: [
                            'name', 
                            'tag',
                            { data: ['id'] }
                            ],
                            page: 10,
                            pagination: true
                        };
                        var userList = new List('user-list-holder', options);

                        //userList.remove("name", "Name"); 
                        userList.clear();
                        removeAll(drawnLayersArray, 'tempall');
                        for (var i = 0; i < data.length; ++i) {
                                userList.add({name: data[i].user.name, tag: data[i].tag_mac, id: data[i].id});

                                var redIcon = L.icon({
                                    iconUrl: "{{url('/css/images/redmarker.png')}}",
                                    iconSize: [50,50],
                                    className: 'blinking'
                                    });
                                    addTooltip(data[i], drawnLayers, gatewayZones, redIcon);
                        }
                
                        $('#user-list').on('click', 'li', function() {
                            getUserLocation(this.getAttribute("data-id"));    
                        })
                        
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    error: function(xhr, request, error){
                        console.log(xhr.responseText);
                    },
                })
                .always(function () {
                    setTimeout(getUserData, set_delay);
                });
            }
            getUserData();

            function drawMarkers(data){
            id = data.id;
            e = newZone;
            drawnLayers[currentFloor].addLayer(e.layer);
            if (e.layerType == "rectangle"){
                corner1 = e.layer.toGeoJSON().geometry.coordinates[0][0];
                corner2 = e.layer.toGeoJSON().geometry.coordinates[0][2];
                x = (corner1[0] + corner2[0])/2;
                y = (corner1[1] + corner2[1])/2;

                drawnLayers[currentFloor].addLayer(L.marker({lng: x, lat: y}, {icon: btIcon}));
                var string = "<b>Mac</b>:".concat(selectedData.mac_addr,"<br> <b>Location</b>: ",location.val());
                L.marker({lng: x,lat: y}, {icon: btIcon})
                        .bindTooltip(string)
                        .addTo(drawnLayers[currentFloor]);
                geoJson = e.layer.toGeoJSON().geometry;
            }
            else{
                drawnLayers[currentFloor].addLayer(L.marker(e.layer.getLatLng(), {icon: btIcon}));
                var string = "<b>Mac</b>:".concat(selectedData.mac_addr,"<br> <b>Location</b>: ",location.val());
                L.marker(e.layer.getLatLng(), {icon: btIcon})
                        .bindTooltip(string)
                        .addTo(drawnLayers[currentFloor]);
                geoJson = e.layer.toGeoJSON().geometry;
                geoJson.radius = e.layer.getRadius();
            }
            geoJson.floor = currentFloor;
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
                        drawMarkers(data);
                        selectData = selectData.filter(reader => reader.mac_addr != selectedData.mac_addr);
                        $("#selReader").empty();
                        $("#selReader").select2({
                            data: selectData
                        });
 
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
        var bounds = [[0, 0],[505, 598]]; 
        var bounds2 = [[0, 0],[518, 800]]; 
        var bounds3 = [[0, 0],[1768, 2500]]; 
        var bounds4 = [[0, 0],[2048, 2896]]; 

        map.fitBounds(bounds);
        var image = L.imageOverlay("{{url('/images/floorplan.png')}}", bounds).addTo(map);
        var image2 = L.imageOverlay("{{url('/images/floorplan2.png')}}", bounds2)
        var image3 = L.imageOverlay("{{url('/images/floorplan3.png')}}", bounds3)
        var image4 = L.imageOverlay("{{url('/images/floorplan4.png')}}", bounds4)

        var baseLayers = {
        "1st Storey": image,
        "2nd Storey": image2,
        "3rd Storey": image3,
        "4th Storey": image4
        };

        L.control.layers(baseLayers).addTo(map);

        var drawnItems = new L.FeatureGroup();
        var drawnItems1 = new L.FeatureGroup();
        var drawnItems2 = new L.FeatureGroup();
        var drawnItems3 = new L.FeatureGroup();
        var drawnItems4 = new L.FeatureGroup();

        var drawnLayers = {
        "1st Storey": drawnItems1,
        "2nd Storey": drawnItems2,
        "3rd Storey": drawnItems3,
        "4th Storey": drawnItems4
        };

        drawnLayersArray = [drawnItems1,drawnItems2,drawnItems3,drawnItems4];

        var floorIndex = {
        "1st Storey": 0,
        "2nd Storey": 1,
        "3rd Storey": 2,
        "4th Storey": 3
        };

        map.addLayer(drawnItems);
        map.addLayer(drawnItems1);

        drawControl = new L.Control.Draw({
                draw: {
                    polygon: false,
                    marker: false,
                    polyline: false,
                    circlemarker: false
                },
                edit: {
                    featureGroup: drawnItems1
                }
        });;

        drawControlLayer = function(controlLayer){
            map.removeControl(drawControl);
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

        drawControlLayer(drawnLayers[currentFloor]);
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
            geoJson.floor = currentFloor;
        });

        map.on('draw:edited', function (e) {
            var layers = e.layers;
            console.log("LAYERS");
            console.log(e);
            console.log(layers);
            layers.eachLayer(function (layer) {
                //do stuff, but I can't check which type I'm working with
                // the layer parameter doesn't mention its type
                console.log(layer);
                console.log("Geometry");
                console.log(layer.toGeoJSON().geometry);
            });
        });

        map.on('draw:deleted', function (e) {
            var type = e.layerType,
                layer = e.layer;
            console.log(layer);
            //drawnItems.addLayer(layer);
            dialog.dialog('open');

            console.log('On draw:created', e.target);
            console.log(e.type, e);
            console.log(e.layerType);
            console.log("GeoJSON:".geoJson);
        });
        map.on('baselayerchange', function(e) {
            map.removeLayer(drawnItems1);
            map.removeLayer(drawnItems2);
            map.removeLayer(drawnItems3);
            map.removeLayer(drawnItems4);
            map.addLayer(drawnLayers[e.name]);
            currentFloor = e.name;
            drawControlLayer(drawnLayers[currentFloor]);
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