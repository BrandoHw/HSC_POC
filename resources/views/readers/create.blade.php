<div class="modal-content">

    <!-- Title -->
    <div class="modal-header">
        <h5 class="modal-title">Create <strong>New</strong> Gateway</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>

    <!-- Form -->
    {!! Form::open(['url' => '/file-upload', 'method' => 'post', 'enctype'=>  'multipart/form-data']) !!}
    {{ csrf_field() }}
    @method('post')

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <div class="col-sm-7" style="margin-top: 20px; margin-bottom: 15px">
                    <label>
                        Floor
                        <span style="color: red; display:block; float:right;"> *</span>
                    </label>
                    <div>
                        <select id='selectFloor' class="form-control" name='selectFloor' style='height: 200px; width: 295px;'></select>
                    </div>   
                </div>

                <label class="col-sm-2 col-form-label">{{ __('Alias') }}</label>
                <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('alias') ? ' has-danger' : '' }}">
                        <input class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}"
                        name="alias" id="alias" type="text" placeholder="{{ __('Name') }}"
                        value="" required="true" aria-required="true"
                        />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="building">Cancel</button>
                    <button type="submit" id="submit" class="btn btn-primary">Upload File</button>
                </div>

            </div>
    {{ Form::close() }}
  
</div>

