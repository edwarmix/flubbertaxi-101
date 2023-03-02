@push('page_css')
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
@endpush

<!-- Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image', __('Image Icon') . ':') !!}
    <input type="file" name="image" class="form-control" id="inputImage" style="" accept="image/*"
        onchange="loadFile(event)">
    <div class="col-12 pt-2 text-center">
        <center>
            <img id="imagePreview"
                src="{{ isset($category) && $category->getHasMediaAttribute() ? $category->getFirstMediaUrl('default') : asset('/img/image_default.png') }}"
                alt="Image Preview" onchange="loadFile(event)"
                style="display: {{ isset($category) && $category->getHasMediaAttribute() ? 'inline-block' : 'none' }};max-width: 90%" />
        </center>
    </div>
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'maxlength' => 255, 'maxlength' => 255]) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('base_price', __('Base Price')) !!}
    {!! Form::number('base_price', null, [
        'class' => 'form-control',
        'placeholder' => trans('Base Price'),
        'min' => 0,
        'step' => 0.01,
    ]) !!}
    <div class="form-text text-muted">
        {{ __('Minimum price of the ride') }}
    </div>
</div>
<div class="form-group col-sm-4">
    {!! Form::label('base_distance', __('Base Distance'), ['class' => 'control-label text-right']) !!}
    {!! Form::number('base_distance', null, [
        'class' => 'form-control',
        'placeholder' => trans('Base Distance'),
        'min' => 0,
        'step' => 0.01,
    ]) !!}
    <div class="form-text text-muted">
        {{ __('Distance that is paid by the minimum price without additional pricing factor') }}
    </div>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('additional_distance_pricing', __('Additional Distance Price'), [
        'class' => 'control-label text-right',
    ]) !!}
    {!! Form::number('additional_distance_pricing', null, [
        'class' => 'form-control',
        'placeholder' => trans('price by ') . setting('distance_unit', 'mi'),
        'min' => 0,
        'step' => 0.01,
    ]) !!}
    <div class="form-text text-muted">
        {{ __('Additional price for distance surplus the base distance') }}
    </div>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('app_tax', __('App Tax') . ' (%)', ['class' => 'control-label text-right']) !!}
    {!! Form::number('app_tax', null, [
        'class' => 'form-control',
        'placeholder' => __('Percentage that will go to the app on each ride'),
        'min' => 0,
        'max' => 100,
        'step' => 0.01,
    ]) !!}
    <div class="form-text text-muted">
        {{ __('Percentage that will go to the app on each ride') }}
    </div>
</div>
<!-- Active Field -->
<div class="form-group col-sm-4" style="padding-top: 37px">
    <div class="checkbox icheck">
        <label class="w-100 ml-2 form-check-inline">
            {!! Form::hidden('default', 0) !!}
            {!! Form::checkbox('default', 1, $category->default ?? false) !!}
            <span class="ml-2">{{ __('Default Vehicle Type') }}</span>
        </label>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.vehicle_types.index') }}" class="btn btn-light">{{ __('crud.cancel') }}</a>
</div>

@push('scripts')
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>

    <script type="text/javascript">
        var outputImage = document.getElementById('imagePreview');

        var loadFile = function(event) {

            if (event.target.files.length > 0) {
                outputImage.src = URL.createObjectURL(event.target.files[0]);
                outputImage.onload = function() {
                    URL.revokeObjectURL(outputImage.src) // free memory
                }
                outputImage.style.display = 'block';
            } else {
                @if (isset($category))
                    outputImage.src = "{{ $category->getFirstMediaUrl('default') }}";
                    outputImage.style.display = 'block';
                @endif

            }
        };
    </script>
@endpush
