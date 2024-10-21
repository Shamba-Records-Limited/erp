<div class="row">
    <div class="col-md-6 col-xl-6 grid-margin stretch-card">
        <div class="card" style="overflow-y: scroll; height:270px;">
            <div class="card-body">
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
        </div>
    </div>


    <div class="col-sm-6 col-md-6 col-lg-6 grid-margin stretch-card">
            <div class="row flex-grow">
                <div class="col-md-6 col-xl-12 grid-margin grid-margin-md-0 grid-margin-xl stretch-card">
                    <div class="card card-revenue">
                        <div class="card-body d-flex align-items-center">
                            <div class="d-flex flex-grow">
                                <div class="mr-auto">
                                    <p class="highlight-text mb-0 text-white"> {{$data->collections->count()}}</p>
                                    <p class="text-white"> Collected this month </p>
                                    {{--                                        <div class="badge badge-pill"> 18% </div>--}}
                                </div>
                                <div class="ml-auto align-self-end">
                                    <div id="revenue-chart" sparkType="bar" sparkBarColor="#e6ecf5"
                                         barWidth="2"> {{$data->collection_quantity}} </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-12 stretch-card">
                    <div class="card card-revenue-table" style="overflow-y: scroll; height:110px;">
                        <div class="card-body">

                            @php
                                $total_price = 0;
                            @endphp
                            @foreach($data->collections as $c)

                                @php
                                    $price = $c->quantity * $c->product->buying_price;
                                    $total_price += $price
                                @endphp
{{--                                <div class="revenue-item d-flex">--}}
{{--                                    <div class="revenue-desc">--}}
{{--                                        <h6>{{$c->product->name}}</h6>--}}
{{--                                        <p class="font-weight-light">{{ $c->quantity }} {{$c->product->unit->name}} </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="revenue-amount">--}}
{{--                                        <p class="text-primary"> {{$user->cooperative->currency}} {{ number_format(($price),2,'.',',') }} </p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            @endforeach
                            <div class="revenue-amount text-right py-3">
                                <h2 class="text-primary"> {{$user->cooperative->currency}} {{ number_format(($total_price),2,'.',',') }} </h2>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </div>
</div>
