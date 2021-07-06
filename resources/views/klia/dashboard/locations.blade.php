<style>

</style>
<div class="col-sm-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-center">
            <div class="iq-header-title" style="margin-right: 10px">
                <h4 class="card-title">Locations</h4>
            </div>
         </div>
         <div id="overlay" class="iq-card-body">
            <div style='display: flex; height: 80vh; justify-content: center; align-items: center; flex-direction: column'>
                <img src="{{asset('img/icons/loading-small.gif')}}" >
                <h4>Loading...</h4>
            </div>
        </div>
        <div id="location-list-div">
            <div style='display: flex; height: 100vh;'>
                <div class ="scroller" style="margin-bottom: 4em;line-height:3em;overflow:scroll;background-color: rgb(255, 255, 255);display: inline-block;">
                    <div id="location-list-holder">
                        <div style=" position: sticky;top: 0px; height: 4em; background-color: rgb(255, 255, 255)">
                        <input  type="text" class="search form-control round" placeholder="Search" />
                        </div>
                        <ul id="location-list" class="list iq-chat-ui nav flex-column nav-pills" style="display: inline-block">

                            <li id ="first-item" class="d-flex mb-4 align-items-center">
                                <span>
                                    <img class="image rounded img-fluid avatar-40"src={{asset('img/avatars/default-profile-m.jpg')}} alt="N/A"> 
                                </span>
                                <div class="media-support-info ml-3">
                                   <h6 class="location_name">Location Name</h6>
                                   <p class="serial mb-0">Serial</p>
                                </div>
                                {{-- <div class="media-support-amount ml-3">
                                   <h6 class="text-primary">+ 250</h6>
                                   <p class="mb-0">USD</p>
                                </div> --}}
                             </li>
                        </ul> 
                        <ul class="pagination">
                        </ul>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    var options = {
        valueNames: [
            { attr: 'src', name: 'image' },
            'location_name', 
            'serial',
            // 'tag',
        ],
        page: 50,
        pagination: true
    };

   locationList = new List('location-list-holder', options);

    getLocationData = function(){
        // $( "#location-list-div" ).hide();
        // $( "#overlay" ).show();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("home.locationPresence")}}',
            type: "get",
            success:function(response){
                $gateways = response['gateways'];
                console.log($gateways);
                $gateways.forEach((location) => {
                    var item = locationList.get("serial", location['serial']);
                    if (item.length > 0){
                        if (location['icon']){
                            item[0].values({
                                'image': "{{asset('img/icons/greencheck.png')}}",
                            })
                        }else{
                            item[0].values({
                                'image': "{{asset('img/icons/redcross.png')}}",
                            })
                        }
                    }else{
                        if (location['icon'])
                            location['image'] = "{{asset('img/icons/greencheck.png')}}";
                        else
                            location['image'] = "{{asset('img/icons/redcross.png')}}";
                        locationList.add(location);
                    }
                });
                $( "#location-list-div" ).show();
                $( "#overlay" ).hide();
            },
            error:function(error){
                console.log(error)
            }
        })
        .always(function () {
            setTimeout(getLocationData, 60000);
        });
    }

    locationList.clear();
    $( "#location-list-div" ).hide();
    $( "#overlay" ).show();
    getLocationData();

</script>