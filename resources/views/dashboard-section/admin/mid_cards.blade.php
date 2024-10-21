<div class="row">
    <div class="col-md-6 col-xl-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" style="overflow-y: scroll; height:235px;">
                <h4 class="card-title">Vet Schedules</h4>
                <div class="shedule-list d-flex align-items-center justify-content-between mb-3">
                    <h3>{{ $data->start." - ".$data->end }}</h3>
                    <small>{{$data->bookings->count()}} Bookings</small>
                </div>

                @foreach($data->bookings as $booking)

                    <div class="event border-bottom py-3">
                        <p class="mb-2 font-weight-medium">{{$booking->event_name}}</p>
                        <div class="d-flex align-items-center">
                            <div class="badge {{ Illuminate\Support\Arr::random(['badge-warning','badge-success','badge-danger','badge-info'],1)[0]}} mr-2">
                                {{  Carbon\Carbon::parse($booking->event_start)->format('d, F H:i') }}
                            </div>
                            {{--                                <small class="text-muted ml-2">London, UK</small>--}}
                            -
                            <div class="badge {{ Illuminate\Support\Arr::random(['badge-warning','badge-success','badge-danger','badge-info'],1)[0]}} ml-2 ">
                                {{  Carbon\Carbon::parse($booking->event_end)->format('d, F H:i') }}
                            </div>
                            <small class="text-muted ml-2">{{ ucwords(strtolower($booking->vet->first_name.' '.$booking->vet->other_names)) }}</small>
                            <div class="image-grouped ml-auto">
                                @if(isset($booking->vet) && isset($booking->vet->profile_image))
                                    @if($booking->vet->vet->profile_image and file_exists('storage/'.$booking->vet->vet->profile_image))
                                        <img src="{{ asset('storage/'.$booking->vet->vet->profile_image )}}"
                                             alt="profile">
                                    @else
                                        <img src="{{ url('assets/images/avatar.png') }}" alt="profile"
                                             class="rounded-circle t-image">
                                    @endif
                                @else
                                    <img src="{{ url('assets/images/avatar.png') }}" alt="profile"
                                         class="rounded-circle t-image">
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div>
                <h5 class=" pl-3 pb-2 pt-2"> {{ number_format($data->vets)}} Vets & Extension Officers</h5>
            </div>
        </div>

    </div>

    @php
        $total_gender_distribution = $data->gender["female"] + $data->gender["male"] + $data->gender["other"]
    @endphp
    <div class="col-sm-12 col-md-6 col-lg-6 grid-margin stretch-card">
        <div class="card" style="overflow-y: scroll; height:280px;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5 d-flex align-items-center">
                        <canvas id="UsersDoughnutChart" class="400x160 mb-4 mb-md-0" height="200"></canvas>
                    </div>
                    <div class="col-md-7">
                        <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Farmers Gender
                            Distribution</h4>
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$data->gender["female"]}}</p>
                                    <small class="text-muted ml-2">Female</small>
                                </div>
                                <p class="mb-0 font-weight-medium">{{ $total_gender_distribution > 0 ? number_format((($data->gender["female"] / $total_gender_distribution) * 100), 2,'.',',') : 0 }}
                                    %</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{ $total_gender_distribution ? (($data->gender["female"] / $total_gender_distribution) * 100) : 0}}%"
                                     aria-valuenow="{{$total_gender_distribution ? (($data->gender["female"] / $total_gender_distribution) * 100) : 0}}"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$data->gender["male"]}}</p>
                                    <small class="text-muted ml-2">Male</small>
                                </div>
                                <p class="mb-0 font-weight-medium">{{ $total_gender_distribution ? number_format((($data->gender["male"] / $total_gender_distribution) * 100), 2,'.',',') : 0 }}
                                    %</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ $total_gender_distribution ? (($data->gender["male"] / $total_gender_distribution) * 100) : 0}}%"
                                     aria-valuenow="{{$total_gender_distribution ? (($data->gender["male"] / $total_gender_distribution) * 100) : 0}}"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="wrapper mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 font-weight-medium">{{$data->gender["other"]}}</p>
                                    <small class="text-muted ml-2">Other</small>
                                </div>
                                <p class="mb-0 font-weight-medium">{{ $total_gender_distribution ? number_format((($data->gender["other"] / $total_gender_distribution) * 100), 2,'.',',') : 0 }}
                                    %</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar"
                                     style="width: {{ $total_gender_distribution ? (($data->gender["other"] / $total_gender_distribution) * 100) : 0}}%"
                                     aria-valuenow="{{ $total_gender_distribution ? (($data->gender["other"] / $total_gender_distribution) * 100) : 0}}"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
