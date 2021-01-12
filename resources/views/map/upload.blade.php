<html>
  <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
  </head>
  <body>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
  </body>
</html>

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>

<script>
   const notyf = new Notyf();
    const notification = notyf.success('Address updated. Click here to continue');
</script>
@endif

{!! Form::open(['url' => '/file-upload', 'method' => 'post', 'enctype'=>  'multipart/form-data']) !!}
        {{ csrf_field() }}
          @method('post')

          <div class="card ">
            <div class="card-header card-header-primary">
              <h4 class="card-title">Upload File</h4>
              <p class="card-category"></p>
            </div>
            <div class="card-body ">
              <div class="row">
                <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                <div class="col-sm-7">
                  <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="name" type="text" placeholder="{{ __('Name') }}" value="" required="true" aria-required="true" />
                  </div>
                </div>
              </div>

              <div class="row">
                <label for="image" class="col-sm-2 col-form-label">Category Image</label>
                <div class="col-sm-7">
                  <input id="image" type="file" class="form-control" name="image">
                </div>
                <img src="#" alt = "" id="img-tag" width="200px" />   <!--for preview purpose -->
              </div>

            </div>
            <div class="card-footer ml-auto mr-auto">
              <button type="submit" class="btn btn-primary">{{ __('Add Category') }}</button>
            </div>
          </div>

          
          
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img-tag').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image").change(function(){
        readURL(this);
    });
</script>