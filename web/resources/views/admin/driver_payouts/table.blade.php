@section('css')
    @include('layouts.datatables_css')
@endsection

<div class="table-responsive">
    {!! $driverPayoutDataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered']) !!}
</div>

@push('scripts')
    @include('layouts.datatables_js')
    {!! $driverPayoutDataTable->scripts() !!}
@endpush
