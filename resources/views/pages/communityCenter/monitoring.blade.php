@include('layouts.headerCommunityCenter')

<div class="communityCenterPageContent">
    <div class="headline">
        <div class="headlineLeft">
            @if($maintenance)
                <p class="communityCenterHeadline" style="text-align:left!important;margin-bottom: 5px;color: orange!important;">Wartungsarbeiten</p>
            @else
                <p class="communityCenterHeadline" style="text-align:left!important;margin-bottom: 5px;color: green!important;">Alle Systeme Online</p>
            @endif
        </div>
    </div>

    @if($maintenance && $maintenance->text)
        <div class="maintenance d-flex justify-content-center"> 
            <div class="alert alert-warning" role="alert">
                <a style="font-size: 16px; font-weight: 600;">{{ $maintenance->text }}</a>
            </div>
        </div>
    @endif
    

    <style>
    
    </style>

    <div class="monitoringSection pt-5">
        {{-- <div class="monitoringAlert mb-5">
            <div class="alert alert-warning" role="alert" style="text-align: center;font-weight: 700;border-radius: 9px;">
               Wartungsarbeiten am 16.03.2022 ab 17:30 Uhr. Wir können die Dauer leider nicht einschätzen.
            </div>
        </div> --}}

        @if($services && count($services) > 0)

            <div class="headline mt-5">
                <a class="communityCenterHeadline">Statistik (60 Tage)</a>
            </div>

            <div class="monitoringServices mb-4">

                @foreach($services as $service)
                    @php
                        $currentStatus = null;
                        if(isset($service->status))
                            $lastStatus = $service->status[array_key_last($service->status)];

                        if(isset($lastStatus) && $lastStatus['status'] != 'no_data') {
                            $currentStatus = $lastStatus['status'] == 'statusOnline' ? 'Online' : 'Offline';
                        }else {
                            $currentStatus = 'N/A';
                        }

                        if($currentStatus == 'Online') {
                            $blob = 'statusOnline';
                            $blobClass = 'greenblob';
                        }
                        if($currentStatus == 'Offline') {
                            $blob = 'statusOffline';
                            $blobClass = 'redblob';
                        }
                        if($currentStatus == 'N/A') {
                            $blob = 'statusNoData';
                            $blobClass = 'greyblob';
                        }

                    @endphp
                    <!-- Service Item -->
                    <div class="monitoringService">
                        <div class="serviceTitle">
                            <a>{{ $service->name }}</a>
                        </div>
                        <div class="serviceUptime">
                            {{-- @for ($i = 0; $i < 60; $i++)
                                <a class="serviceStatusDaySign statusOnline">&nbsp;</a>
                            @endfor --}}
                            @foreach ($service->status as $serviceDayStatus)
                                <a href="javascript:void(0)" style="cursor: default"
                                    onclick="return;showServiceDayModal('{{ $service->id }}', '{{ $serviceDayStatus['date'] }}')"
                                    data-toggle="tooltip" data-placement="top" title="{{ $serviceDayStatus['date'] }}"
                                    class="serviceStatusDaySign {{ $serviceDayStatus['status'] }}">&nbsp;</a>
                            @endforeach
                        </div>
                        <div class="serviceStatus">
                            @if($currentStatus)
                                <div class="blob {{ $blobClass }}"></div><a class="{{ $blob }}">{{ $currentStatus ?: '' }}</a>
                            @endif
                        </div>
                    </div>
                    <!-- Service Item END -->
                @endforeach 

                <!-- Service Item -->
                {{-- <div class="monitoringService">
                    <div class="serviceTitle">
                        <a>TeamSpeak Server</a>
                    </div>
                    <div class="serviceUptime">
                        @for ($i = 0; $i < 60; $i++)
                            <a class="serviceStatusDaySign statusNoData">&nbsp;</a>
                        @endfor
                    </div>
                    <div class="serviceStatus">
                        <div class="blob greyblob"></div><a class="statusNoData">N/A</a>
                    </div>
                </div> --}}
                <!-- Service Item END -->
            </div>
        @endif

        <div class="headline mt-5">
            <a class="communityCenterHeadline">Uptime Statistiken</a>
        </div>

        <div class="monitoringStatistics">
                <div class="statistic">
                    <a class="title">Letzte 24 Stunden</a>
                    <a class="subtitle">{{ $statistics['1day'] }}%</a>
                </div>
                <div class="statistic">
                    <a class="title">Letzte 7 Tage</a>
                    <a class="subtitle">{{ $statistics['7days'] }}%</a>
                </div>
                <div class="statistic">
                    <a class="title">Letzte 30 Tage</a>
                    <a class="subtitle">{{ $statistics['30days'] }}%</a>
                </div>
                <div class="statistic">
                    <a class="title">Letzte 60 Tage</a>
                    <a class="subtitle">{{ $statistics['60days'] }}%</a>
                </div>
        </div>
    </div>

</div>


@include('layouts.footerCommunityCenter')
