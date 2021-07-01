<div class="col-sm-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-body">
            <div style='display: flex; height: 100vh;'>

                <div class ="scroller" style="line-height:3em;overflow:scroll;padding:5px;background-color: rgb(255, 255, 255);display: inline-block;">
                    <div id="location-list-holder">
                        <input type="text" class="search form-control round" placeholder="Search" />

                    <ul id="location-list" class="list iq-chat-ui nav flex-column nav-pills" style="display: inline-block">

                        <li id = "first-item">
                            <img class="rounded img-fluid avatar-40"src={{asset('img/avatars/default-profile-m.jpg')}} alt="N/A"> 
                            <h3 class="name">Name</h3>
                            <h5 class="location">Location</h5>
                            <p class="tag">Tag</p>
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
            'name', 
            'location',
            'tag',
        ],
        page: 10,
        pagination: true
    };

   locationList = new List('location-list-holder', options);

   for (let i = 0; i < 15; i++){
        locationList.add({
                        name: "rawr",
                        location : "gweyhr",
                        tag: i,
                    });
    }
</script>