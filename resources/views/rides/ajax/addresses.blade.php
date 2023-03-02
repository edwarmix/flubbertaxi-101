    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                <b><i class="far fa-flag"></i> {{ __('Boarding location') }}</b>
            </h5>
            <div class="card-text">
                <p>
                    @php($boardingLocationData = json_decode($ride->boarding_location_data, true))
                    @if (in_array($ride->ride_status, ['waiting', 'pending', 'accepted']))
                        <i class="fa fa-clock text-warning" title="{{ __('Waiting') }}"></i>
                    @elseif(in_array($ride->ride_status, ['in_progress', 'completed']))
                        <i class="fa fa-check-circle text-success" title="{{ __('Collected') }}"></i>
                    @else
                        <i class="fa fa-times-circle text-danger" title="{{ __('Cancelled') }}"></i>
                    @endif
                    {{ $boardingLocationData['formatted_address'] . ' - ' . ($boardingLocationData['number'] ?? '-') }}
                </p>
            </div>
        </div>
    </div>
    <div class="card mt-5">
        <div class="card-body">
            <h5 class="card-title">
                <b><i class="fas fa-flag-checkered"></i> {{ __('Destination location') }}</b>
            </h5>
            <div class="card-text">
                <p>
                    @php($destinationLocationData = json_decode($ride->destination_location_data, true))
                    @if (in_array($ride->ride_status, ['waiting', 'pending', 'accepted']))
                        <i class="fa fa-clock text-warning" title="{{ __('Waiting') }}"></i>
                    @elseif(in_array($ride->ride_status, ['in_progress', 'completed']))
                        <i class="fa fa-check-circle text-success" title="{{ __('Collected') }}"></i>
                    @else
                        <i class="fa fa-times-circle text-danger" title="{{ __('Cancelled') }}"></i>
                    @endif
                    {{ $destinationLocationData['formatted_address'] . ' - ' . $destinationLocationData['number'] ?? '-' }}
                </p>
            </div>
        </div>
    </div>
