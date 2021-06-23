@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/map/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('css/map/leaflet.draw.css') }}" rel="stylesheet">
    <link href="{{ asset('css/map/map.css') }}" rel="stylesheet">

    <script src="{{ asset('js/mix/leaflet.js') }}"></script>
    <script src="{{ asset('js/mix/moment.js') }}"></script>
    <script src="{{ asset('js/mix/dialog.js') }}"></script>
    <script src="{{ asset('js/mix/stepper.js') }}"></script>

    <script src="{{ asset('js/views/map/functions.js')}}"></script>
    <script src="{{ asset('js/views/map/edit_functions.js')}}"></script>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div style='display: flex; height: 80vh;'>
                        <div class ="scroller" style="width:25%; line-height:3em;overflow:scroll;padding:5px;background-color: rgb(255, 255, 255);display: inline-block;">
                            <div id="reader-list-holder">
                                <input type="text" class="search form-control round" placeholder="Search" />
                                <ul id="reader-list" class="list iq-chat-ui nav flex-column nav-pills" style="display: inline-block">
                                    <li id = "first-item"><h3 class="serial">Serial</h3>
                                        <h3 class="location">Location</h3>
                                        <p class="mac">Mac</p>
                                        <p class="online">Online</p>
                                    </li>
                                </ul> 
                                <ul class="pagination"></ul>
                            </div>
                        </div>

                        <div id="map" style="width: 75%; display: inline-block"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Create Zone Modal -->
    <div class="modal fade" id="createZoneModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            @include('map.create')
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('js/views/map/jquery_functions.js')}}"></script>
    <script>
   
        var drawnLayers = new Object();
        var drawnLayersArray = []
        var floorIndex = new Object();
        var baseLayer = new Object();
        var bounds = new Object();

        var btIcon = L.icon({
                iconUrl: "{{url('/css/images/bt.png')}}",
                iconSize: ["{{Config::get('constants.icons.bt')}}", "{{Config::get('constants.icons.bt')}}"]
            });

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
                if (obj.serial === null){
                    obj.serial = "N/A";
                }
                obj.text = obj.text || obj.serial.concat(" - ", obj.mac_addr); // replace name with the property used for the text
                obj.id = obj.gateway_id;
                return obj;
            });
            console.log("Select Data")
            console.log(selectData);
            var btIcon = L.icon({
                iconUrl: "{{url('/css/images/bt.png')}}",
                iconSize: ["{{Config::get('constants.icons.bt')}}", "{{Config::get('constants.icons.bt')}}"]
            });

            // for (var i = 0; i < selectData.length; i++){
            //     if (selectData[i].alias == null){
            //         selectData[i].alias =  //Floor ".concat(selectData[i].number.toString())
            //     }
            // }
            

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
                        setupList(data['gatewayZones'], data['readers']);

                        selectData = $.map(data['readers'], function (obj) {
                            if (obj.serial === null){
                                obj.serial = "N/A";
                            }
                            obj.text = obj.text || obj.serial.concat(" - ", obj.mac_addr); // replace name with the property used for the text
                            obj.id = obj.gateway_id;
                            return obj;
                        });
                        $("#selReader").select2({
                            data:selectData.filter(gateway => gateway.alias == currentFloor || gateway.alias == null)
                        });

                        notyf.success("Successful Deletion");  
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    error: function(xhr, request, error){
                        console.log(xhr.responseText);
                        notyf.error("Failed To Delete. Please Refresh Page And Try Again");  
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
                        notyf.success("Sucessful Update");  
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    error: function(xhr, request, error){
                        console.log(xhr.responseText);
                        notyf.error("Failed To Update. Please Refresh Page And Try Again");  
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

        var map = L.map("map", {
            crs: L.CRS.Simple,
            minZoom: -3,
            maxBoundsViscosity: 1.0,
        }); //CRS simple referring to normal coordinate value

       
        //Map
        var drawControl;
        L.EditToolbar.Delete.include({
            removeAllLayers: false
        });
        drawControlLayer = function(controlLayer){
            drawControl = new L.Control.Draw({
                draw: {
                    marker: false,
                    polyline: false,
                    circlemarker: false,
                    circle: {
                        showRadius: false
                    },
                    rectangle: {
                        showArea: false
                    },
                    polygon: {
                        allowIntersection: false
                    },
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
                    if (this.height === 0 || this.width === 0){
                        bounds[alias] =  [[0,0], [2339, 3309]];
                    }else{
                        bounds[alias] =  [[0,0], [this.height, this.width]];
                    }
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
        $('#reader-list').on('click', 'li', function() {
            //getUserLocation(this.getAttribute("data-id"));    
            if(this.getAttribute("data-assigned") == 1){
                drawGatewayLocation(this.getAttribute("data-id"),  drawnLayers, gatewayZones, floorIndex, btIcon)
            //Find zone and create popup
            }else{
                alert("Gateway has not been assigned");
            }
        })
        setupList(gatewayZones, readers);

        //Filter reader data to only display current floor
        $("#selReader").select2({
            data:selectData.filter(gateway => gateway.alias == currentFloor || gateway.alias == null)
        });
        selectedData = $('#selReader').select2('data')[0];

 
        drawZones(gatewayZones, drawnLayers, btIcon);


        map.on('draw:created', function (e) {
            var type = e.layerType,
                layer = e.layer;
            console.log(layer);
            //drawnItems.addLayer(layer);
            //dialog.dialog('open');
            showZoneModal();

            console.log('On draw:created', e.target);
            console.log(e.type, e);
            console.log(e.layerType);
        
            var btIcon = L.icon({
            iconUrl: "{{url('/css/images/bt.png')}}",
            iconSize: ["{{Config::get('constants.icons.bt')}}", "{{Config::get('constants.icons.bt')}}"],
        });

            newZone = e;
            geoJson = e.layer.toGeoJSON().geometry;
            console.log(geoJson);
            
            if (e.layerType == "polygon"){
                var center = function (arr)
                {
                    var minX, maxX, minY, maxY;
                    for (var i = 0; i < arr.length; i++)
                    {
                        minX = (arr[i][0] < minX || minX == null) ? arr[i][0] : minX;
                        maxX = (arr[i][0] > maxX || maxX == null) ? arr[i][0] : maxX;
                        minY = (arr[i][1] < minY || minY == null) ? arr[i][1] : minY;
                        maxY = (arr[i][1] > maxY || maxY == null) ? arr[i][1] : maxY;
                    }
                    return [(minX + maxX) / 2, (minY + maxY) / 2];
                }
                polygon_coord = JSON.parse(JSON.stringify(geoJson));
                polygon_coord = polygon_coord.coordinates[0];
                center_coord = center(polygon_coord)
                geoJson.marker = {lng: center_coord[0], lat: center_coord[1]}
            }
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
                console.log(result);
                //gatewayZone.id = layer.id; 
         
                if (layer instanceof L.Rectangle) {
                    result[0].geoJson.coordinates = layer.toGeoJSON().geometry.coordinates;
                }
                if (layer instanceof L.Circle) {
                    result[0].geoJson.coordinates = layer.toGeoJSON().geometry.coordinates;
                    result[0].geoJson.radius = layer.getRadius();
                }
                if (layer instanceof L.Polygon) {
                    result[0].geoJson.coordinates = layer.toGeoJSON().geometry.coordinates;
                }
                if (layer instanceof L.Marker) {
                    result[0].geoJson.marker = {lat: layer.getLatLng().lat.toString(), lng: layer.getLatLng().lng.toString() };
                }
                console.log("EDIT");
                console.log(layer.toGeoJSON().geometry.coordinates);
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
            

            //Filter Gateway Selector for Floor
            $("#selReader").empty();
            console.log(selectData);
            $("#selReader").select2({
                data:selectData.filter(gateway => gateway.alias == currentFloor || gateway.alias == null)
            });

            selectedData = $('#selReader').select2('data')[0];
            //Redraw Location Table for current floor
            locationTable.draw();

            console.log(bounds);
            map.setMaxBounds(bounds[currentFloor]);
        
    
        });

    });
    </script>
@endsection