
    <link href="{{ asset('css/map/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('css/map/leaflet.draw.css') }}" rel="stylesheet">
    <link href="{{ asset('css/map/dashboard.css') }}" rel="stylesheet">

    <script src="{{ asset('js/mix/leaflet.js') }}"></script>
    <script src="{{ asset('js/mix/moment.js') }}"></script>
    <script src="{{ asset('js/mix/dialog.js') }}"></script>

    <script src="{{ asset('js/views/map/functions.js')}}"></script>
    
    <div>
        <form>
            <div class="form-group" id="selUserHolder">
                <select id='selUser' class="form-control"></select>
            </div>
        </form>
       
        <div id= 'flex-list-container' style='display: flex; height: 80vh;'>
            <div id="map" style="width: 100%;"></div>
        </div>
    </div>


    <script>
        var imageUrl = "{{url('/css/images/')}}";
        
        $( function() {
            var dialog, form,

            dialog = $( "#dialog-form").dialog({
                autoOpen: false,
                height: 400,
                width: 350,
                modal: true,
                dialogClass: 'dialogTitleClass',
                close: function() {
                }
            });
            //allFields = $( [] ).add( name ).add( mac ).add( location ),
            tips = $( ".validateTips" );
            gatewayZones = <?php echo $gatewayZones; ?>;
            currentFloor = "";
            building = <?php echo $building; ?>;
            floors = <?php echo $floors; ?>;
           
            var btIcon = L.icon({
                iconUrl: "{{url('/css/images/bt.png')}}",
                iconSize: ["{{Config::get('constants.icons.bt')}}", "{{Config::get('constants.icons.bt')}}"]
            });

            var set_delay = 15000;
            var dataSet;

            var listSet = false;
            var userList;

            $("#selUser").select2({
                selectionCssClass: 'form-control',
            });
            $('#user-list-marker').on('click', 'li', function() {
                alert(this.getAttribute("data-id"));    
                window.location.href = "residents/".concat(this.getAttribute("data-id"), 'edit');
            })
            $('#selUser').change(function(){
                selectedData = $('#selUser').select2('data')[0];
                getUserLocation($('#selUser').select2('data')[0].beacon_id);
                console.log(selectedData);
            });

            getUserLocation = function(id){
                var redIcon = L.icon({
                    iconUrl: "{{url('/css/images/redmarker.png')}}",
                    iconSize: [50,50],
                    className: 'blinking'
                });
                // console.log(id);
                $.ajax({
                    url: "{{ url('user/get') }}",
                    method: 'get',        
                    data: { 
                        id: id 
                    },   
                    success: function(data){
                        // console.log(data);
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
                    url: "{{ url('user-positions') }}",
                    method: 'get',             
                    success: function(data){
                        var users = data['beacons'];
                        var userCount = data['userCount'];
                        var userRunningCount = data['userRunningCount'];

                        selectData = $.map(data['beacons'], function (obj) {
                            var full_name;
                            if (obj.hasOwnProperty('resident')){
                                full_name = obj.resident.resident_fName.concat(" ", obj.resident.resident_lName)
                            }
                            else if (obj.hasOwnProperty('staff')){
                                full_name = obj.staff.fName.concat(" ", obj.staff.lName)
                            }
                            obj.text = obj.text || full_name; // replace name with the property used for the text
                            obj.id = obj.beacon_id;
                            return obj;
                        });

                        console.log(selectData);
                        $("#selUser").select2({
                            data:selectData
                        });
                      
                        removeAll(drawnLayersArray, 'tempall');
                        for (var i = 0; i < users.length; ++i) {
                            // userList.add({name: data[i].user.name, tag: data[i].tag_mac, id: data[i].id});
                            var reader_mac = users[i].gateway.mac_addr
                            // console.log(userRunningCount[reader_mac]);
                            addTooltip(users[i], drawnLayers, gatewayZones, userCount[reader_mac], userRunningCount[reader_mac], dialog);
                            if (users[i].draw){
                                userRunningCount[reader_mac] = userRunningCount[reader_mac] + 1;
                            }
                        }
                        // console.log(userRunningCount);                
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

        var map = L.map("map", {
            crs: L.CRS.Simple,
            minZoom: -2,
            maxBoundsViscosity: 1.0,
        }); //CRS simple referring to normal coordinate value

        //Map
        var drawnLayers = new Object();
        var drawnLayersArray = []
        var floorIndex = new Object();
        var baseLayer = new Object();
        var bounds = new Object();

        //Add Empty Base Layer to map
        var layercontrol = L.control.layers(baseLayer).addTo(map);

        //Add gatewayzones and floor image to appropriate layer
        for (var i = 0; i < floors.length; i++) {
            alias = floors[i].alias;

            if(floors[i].map === null){
                url = "/storage/greyimage.png"
            }else{
                url = floors[i].map.url;
            }
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
                    // console.log(baseLayer);
                    layercontrol.remove();
                    layercontrol = L.control.layers(baseLayer).addTo(map);
                    $('.leaflet-control-layers input').get(floorIndex[currentFloor]).click();
                }
            }(alias, url));  
        };
  
        drawZones(gatewayZones, drawnLayers, btIcon);

        map.on('baselayerchange', function(e) {
            for (var key in drawnLayers){
                map.removeLayer(drawnLayers[key]);
            }
            map.addLayer(drawnLayers[e.name]);
            currentFloor = e.name;

            map.setMaxBounds(bounds[currentFloor]);
         
        });

    });
    </script>

    <body>
        <div id="dialog-form" title="Residents in Location: ">
      
            <div id="user-list-holder-marker">
                <input type="text" class="fuzzy-search" placeholder="Search" />
            
                <ul id="user-list-marker" class="list" style="display: inline-block">
                    <li id = "first-item-marker"><h3 class="name">Name</h3>
                        <p class="last_seen">Last Seen</p>
                        <p class="tag">Tag</p>
                    </li>
                </ul> 
                <ul class="pagination"></ul>
            </div>
        </div>
    </body>
