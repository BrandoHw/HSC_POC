<div class="modal-content">

    <!-- Title -->
    <div class="modal-header">
        <h5 class="modal-title">Add <strong>New</strong> Floor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>

    <!-- Form -->
    {!! Form::open(['url' => '/floors', 'method' => 'post', 'enctype'=>  'multipart/form-data']) !!}
    {{ csrf_field() }}
    @method('post')

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">

                <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('number') ? ' has-danger' : '' }}">
                        <input class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}"
                        name="number" id="number" type="number" placeholder="{{ __('Floor Number') }}"
                        value="" required="true" aria-required="true" min="0" 
                        />
                    </div>
                </div>

                <label class="col-sm-2 col-form-label">{{ __('Alias') }}</label>
                <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('alias') ? ' has-danger' : '' }}">
                        <input class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}"
                        name="alias" id="alias" type="text" placeholder="{{ __('Alias') }}"
                        value="" required="true" aria-required="true" style="color: black"
                        />
                    </div>
                </div>
                <label for="image-input" class="col-sm-6 col-form-label">Floor Plan Image</label>
                <div class="col-sm-7">
                    <input id="image-input" type="file" class="form-control" name="image-input">
                    <img src="#" alt = "" id="img-preview" width="200px" />   <!--for preview purpose -->
                </div>

                <div class="modal-footer">
                    <img src={{url('/css/images/ajax-loader.gif')}} id="loading-indicator" style="display:none" />
                    <button type="submit" id="submit" class="btn btn-primary">Save Floor</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>

            </div>
        </div>
    {{ Form::close() }}
</div>


