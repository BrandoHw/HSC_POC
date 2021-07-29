<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>

<div class="row align-items-center container-fluid">
        <button id="generate" type="button" class="btn btn-primary" style="height: 100%">Generate</button>
        <a id="generate-tooltip" href="#" data-toggle="tooltip" data-placement="right" 
        title="Only one API Token may exist per user.
         Generating a new token will invalidate the old token.
         The API token cannot be retrieve again after generation." style="cursor: pointer; left-padding:0">
            <i class="ri-information-fill"></i>
        </a>
    <!-- Target -->
    <div class="col-sm-6" style="padding: 0px">
        <input id="token-display" class="form-control" value="">
    </div>
    <!-- Trigger -->
    <button type="button" class="copy-btn btn btn-light" data-clipboard-target="#token-display">
        <i class="ri-clipboard-line"alt="Copy to clipboard"></i>
    </button>
</div>
<script>
new ClipboardJS('.copy-btn');

$('#generate').on('click', function(){
    $.ajax({
            url: '{{ route("api.generate") }}',
            type: "POST",
            success:function(response){
                $('#token-display').val(response['token']);
            },
            error:function(error){
                console.log(error);
            }
        });
});
</script>