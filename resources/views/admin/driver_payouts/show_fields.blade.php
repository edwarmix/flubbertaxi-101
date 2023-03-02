<!-- Driver Id Field -->
<div class="form-group">
    {!! Form::label('driver_id', 'Driver Id:') !!}
    <p>{{ $driverPayout->driver_id }}</p>
</div>

<!-- Method Field -->
<div class="form-group">
    {!! Form::label('method', 'Method:') !!}
    <p>{{ $driverPayout->method }}</p>
</div>

<!-- Amount Field -->
<div class="form-group">
    {!! Form::label('amount', 'Amount:') !!}
    <p>{{ $driverPayout->amount }}</p>
</div>

<!-- Date Field -->
<div class="form-group">
    {!! Form::label('date', 'Date:') !!}
    <p>{{ $driverPayout->date }}</p>
</div>
