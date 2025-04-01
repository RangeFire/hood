@include('layouts.header');
@php

    function get_alert_event($event) {
        if($event == 'monitoring_down') return 'Monitoring Ausfall';
        if($event == 'monitoring_online_again') return 'Monitoring wieder Online';
        return false;
    }

    function get_alert_action($action) {
        if($action == 'send_mail') return 'E-Mail versenden';
        if($action == 'send_sms') return 'SMS Nachricht';
        if($action == 'discord_webhook') return 'Discord-Nachricht (Webhook)';
        return false;
    }

    $copyLinkURL = (new App\Models\Project)->findProject(session('activeProject'))->project_hash.'.'.env('APP_DOMAIN').'/cc/wishes';

@endphp
<body onload="initAddMonitoringFormInputs()">
    <div class="page_content">
        <!--- Page headline -->
        <div class="row">
            <div class="col-xl-6 page_title_area">
                <h3 class="h2">Monitoring</h3>
                {{-- <h6 class="page_subtitle">Behalte all deine Systeme immer im Blick</h6> --}}
                <h6 class="page_subtitle copy_link" onclick="copyLinkToClipboard()"><i class="fa-solid fa-copy"></i>&nbsp;{{$copyLinkURL}}</h6>
            </div>
            <div class="col-xl-6 text-right d-flex justify-content-end align-items-center">
                {{-- <button class="btn mr-2 button-primary-orange" data-toggle="modal" data-target="#modal-alerts">
                    <i class="fa-solid fa-brake-warning"></i>
                </button> --}}
                <button class="btn mr-2 button-primary-orange" data-toggle="modal" data-target="#modal-maintenance">
                    Wartungsmodus
                </button>
                <button class="btn button-primary-orange" data-toggle="modal" data-target="#addMonitoringService">
                    Hinzufügen
                </button>
            </div>
        </div>

        <!-- Empty Space -->
        <div style="height: 48px;"></div>
        @if($maintenance)
            <form action="maintenance/stopMaintenanceMode" method="post">@csrf
                <div class="maintenance d-flex justify-content-start mb-3"> 
                    <div class="alert alert-warning" role="alert" style="border-radius: 9px;">
                        <a style="font-size: 15px; font-weight: 600;">Der Wartungsmodus ist aktiv</a>
                        <button class="btn mr-2 button-primary-orange ml-4" style="font-size: 12px;font-weight: 600;padding: 10px 19px;" type="submit">Deaktivieren</button>
                    </div>
                </div>
            </form>
        @endif

    <div class="row mb-4 d-flex justify-content-center">
            <!-- Statistic #1 -->
            <div class="col-xl-3">
                <div class="card card-inline statisticCard">
                    <div class="card-content">
                        <div class="inline-image">
                            <i class="fa-solid fa-earth-europe customIcon"></i>
                        </div>
                        <div class="inline-text">
                            <a class="paragraphSmallSlim">Globale Uptime</a><br>
                            <a class="h4">{{ $globalUptime ?: '0' }}%</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Statistic #2 -->
            {{-- <div class="col-xl-3">
                <div class="card card-inline statisticCard">
                    <div class="card-content">
                        <div class="inline-image">
                            <i class="fa-solid fa-person-drowning customIcon"></i>
                        </div>
                        <div class="inline-text">
                            <a class="paragraphSmallSlim">Ausfälle gesamt</a><br>
                            <a class="h4">{{ $globalOffline ?? 0 }}</a>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!-- Custom Statistic #4 -->
            <div class="col-xl-3">
                <div class="card card-inline statisticCard">
                    <div class="card-content">
                        <div class="inline-image">
                            <i class="fa-brands fa-searchengin customIcon"></i>
                        </div>
                        <div class="inline-text">
                            <a class="paragraphSmallSlim">Heutige Prüfungen</a><br>
                            <a class="h4">{{ $todayChecks }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Custom Statistic #3 -->
            <div class="col-xl-3">
                <div class="card card-inline statisticCard">
                    <div class="card-content">
                        <div class="inline-image">
                            <i class="fa-solid fa-person-running customIcon"></i>
                        </div>
                        <div class="inline-text">
                            <a class="paragraphSmallSlim">Aktive Services</a><br>
                            <a class="h4">{{ count($services) }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>    

        <script>
            let monitoring_charts = [];
            let service = null;
        </script>

        <div class="row mt-5">

            @foreach($services as $i => $service)

                <!-- Monitoring Service Item -->
                <div class="col-4">
                    <div class="monitoringItem mt-3">
                        <div class="monitoringHead">
                            <div class="left">
                                <div class="image">
                                    <img src="/assets/images/logo/logoIconTransparent.png">
                                </div>
                                <div class="content">
                                    <a class="title">{{ $service->name }}</a><br>
                                    <a class="subtitle">{{ $service->url ?: $service->ip }}{{ $service->port ? ':'.$service->port : '' }}</a>
                                </div>
                            </div>
                            <div class="right">
                                <div class="dropdown menu">
                                    <div class="btn-group dropright">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" onclick="showServiceDayModal('{{ $service->id }}', '{{ date('d.m.Y') }}')">Tagesübersicht</a>
                                        <a class="dropdown-item" href="#" onclick="editServiceModal('{{$service->id}}')">Bearbeiten</a>
                                        <a class="dropdown-item" href="#" onclick="deleteServiceModal('{{$service->id}}')">Entfernen</a>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="monitoringBody mt-4">
                            @if($service->onlineSince != 'Nicht erfasst')
                                <div class="statusDiv">
                                    <a class="statusTitle">Derzeitiger Status</a><br>
                                    <a class="status">Online seit dem {{ $service->onlineSince }}</a>
                                </div> 
                            @endif
                            <div class="detailDiv mt-3">
                                <div class="left">
                                    <a class="detailTitle">Uptime</a><br>
                                    <a class="detail">⌀ {{ $service->uptimePercentage['uptime'] }}%</a>
                                </div>
                                <div class="right">
                                    <a class="detailTitle">Antwortzeit</a><br>
                                    <a class="detail">⌀ {{ $service->averageResponseTime }} ms</a>
                                </div>
                            </div> 
                            <div class="chartDiv mt-4">
                                <canvas id="monitoringChart-{{ $service->id }}" width="400" height="200"></canvas>
                            </div> 
                        </div>
                    </div>
                </div>
                <!-- Monitoring Service Item END -->

                <script>
                    // Monitoring Chart
                    service = @json($service);

                    var myChart = new Chart($(`#monitoringChart-${service.id}`), {
                        type: 'line',
                        data: {
                            labels: Object.keys(service.last7DaysUptime),
                            datasets: [{
                                label: '7 Tage Uptime',
                                data: Object.values(service.last7DaysUptime),
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        format: {
                                            style: 'percent'
                                        }
                                    }
                                }
                            },

                            onClick: (evt) => {

                                let find = monitoring_charts.find(chart => chart.service_id == {{ $service->id }});

                                const { chart, service_id } = find;

                                const points = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);

                                if (points.length) {
                                    const firstPoint = points[0];
                                    var label = chart.data.labels[firstPoint.index];
                                    showServiceDayModal(service_id, `${label}2022`);
                                }
                            }
                        },
                    });

                    monitoring_charts.push({
                        service_id: service.id,
                        chart: myChart
                    });

                </script>
            
            @endforeach
        </div>

    <!-- Monitoring Day Detail -->
    <div class="modal fade" id="showMonitoringServiceDay" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <a class="headline">Tagesdetails</a>
                        </div>
                        <div class="col-12 d-flex justify-content-center mt-2">
                            <a class="subtitle" id="showMonitoringServiceDayDetailsSubtitle"></a>
                        </div>
                    </div>

                    <div class="row mt-3 mb-3">
                        <div class="monitoring-statusbar">
                            <div class="monitoring-statusbar-wrapper" id="monitoring-statusbar-wrapper">
                                <span class="monitoring-statusbar-item monitoring-statusbar-item-success"></span>
                                <span class="monitoring-statusbar-item monitoring-statusbar-item-danger"></span>
                                <span class="monitoring-statusbar-item monitoring-statusbar-item-warning"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 pt-4">
                        <div class="col-4 text-center">
                            <h5 class="text-muted">Online</h5>
                            <span id="online" class="text-white"></span>
                        </div>
                        <div class="col-4 text-center">
                            <h5 class="text-muted">Offline</h5>
                            <span id="offline" class="text-white"></span>
                        </div>
                        <div class="col-4 text-center">
                            <h5 class="text-muted">Keine Daten</h5>
                            <span id="empty" class="text-white"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service erstellen -->
    <div class="modal fade" id="addMonitoringService" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <a class="headline">Service erstellen</a>
                        </div>
                        <div class="col-12 d-flex justify-content-center mt-2">
                            <a class="subtitle">Erstelle einen neuen Monitoring Service</a>
                        </div>
                    </div>

                    <div class="row mt-5" id="modal_emoji_root_element">
                        <div class="col-12">
                            <form method="post" action="/monitoring/add">@csrf
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Bezeichnung</label>
                                            <input name="serviceTitle" type="text" class="form-control"
                                                autocomplete="off" placeholder="Web Shop" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Kategorie</label>
                                            <select class="form-control" onchange="chooseAddMonitoringType()" name="serviceType" id="addM_select" required>
                                                <option value="webseite">Webseite</option>
                                                <option value="gameserver">Gameserver</option>
                                                <option value="teamspeak">Teamspeak 3 Server</option>
                                                <option value="server">Server</option>
                                                <option value="service">Dienst auf Server</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4" id="AddM_url">
                                        <div class="form-group">
                                            <label>URL</label>
                                            <input name="serviceURL" type="text" class="form-control" autocomplete="off"
                                                placeholder="google.com/apps">
                                        </div>
                                    </div>
                                    <div class="col-4" id="AddM_ip">
                                        <div class="form-group">
                                            <label>IP-Adresse</label>
                                            <input name="serviceIP" type="text" class="form-control" autocomplete="off"
                                                placeholder="127.0.0.0">
                                        </div>
                                    </div>
                                    <div class="col-4" id="AddM_port">
                                        <div class="form-group">
                                            <label>Port</label>
                                            <input name="servicePort" type="text" class="form-control"
                                                autocomplete="off" placeholder="3306">
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center mt-4">
                        <div class="col d-flex justify-content-center">
                            <button class="customButton" style="" onclick="">Überwachung starten</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Service bearbeiten -->
    <div class="modal fade" id="editMonitoringService" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="edit_form" method="post" action="SETBYSCIPRT">@csrf
                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-center">
                                <a class="headline">Service bearbeiten</a>
                            </div>
                            <div class="col-12 d-flex justify-content-center mt-2">
                                <a class="subtitle">Bearbeite deinen Monitoring Service</a>
                            </div>
                        </div>

                        <div class="row mt-5" id="modal_emoji_root_element">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Bezeichnung</label>
                                            <input id="edit_bezeichnung" name="serviceTitle" type="text"
                                                class="form-control" autocomplete="off" placeholder="Web Shop"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Kategorie</label>
                                            <select id="edit_kategorie" onchange="changeEditMonitoringFormInputs()" class="form-control" name="serviceType" required>
                                                <option value="webseite">Webseite</option>
                                                <option value="gameserver">Gameserver</option>
                                                <option value="teamspeak">Teamspeak 3 Server</option>
                                                <option value="server">Server</option>
                                                <option value="service">Dienst auf Server</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4" id="EditM_url">
                                        <div class="form-group">
                                            <label>URL</label>
                                            <input id="edit_url" name="serviceURL" type="text" class="form-control"
                                                autocomplete="off" placeholder="google.com/apps"> 
                                        </div>
                                    </div>
                                    <div class="col-4" id="EditM_ip">
                                        <div class="form-group">
                                            <label>IP-Adresse</label>
                                            <input id="edit_ip" name="serviceIP" type="text" class="form-control"
                                                autocomplete="off" placeholder="127.0.0.0"> 
                                        </div>
                                    </div>
                                    <div class="col-4" id="EditM_port">
                                        <div class="form-group">
                                            <label>Port</label>
                                            <input id="edit_port" name="servicePort" type="text" class="form-control"
                                                autocomplete="off" placeholder="3306"> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-center mt-4">
                            <div class="col d-flex justify-content-center">
                                <button type="submit" class="customButton" style="" onclick="">Speichern</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - delete Service -->
    <div class="modal fade" id="modal-deleteService" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content custom_modal">
                <div class="modal-header custom_modal_header">
                    <div class="row">
                        <div class="col-xl-6 col-6">
                            <img src="/assets/images/alert-triangle.png" class="w-100 h-auto">
                        </div>
                    </div>
                </div>
                <form action="" id="modal-deleteService_form" method="post">
                    @csrf
                    <div class="modal-body">
                        <h4 class="h2" id="exampleModalLabel" style="margin-bottom: 16px;">Monitoring Service
                            entfernen</h4>
                        <div class="page_subtitle">
                            Bist du dir sicher, dass du diesen Service entfernen möchtest?
                        </div>
                    </div>
                    <div class="modal-footer custom_modal_footer justify-content-center">
                        <button style="width: 46%;" type="button" class="btn button-primary"
                                data-dismiss="modal">Abbrechen
                        </button>
                        <button style="width: 46%;" type="submit"
                                class="btn button-primary-red">Entfernen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal - Monitoring Alerts -->
    <div class="modal fade" id="modal-alerts" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            
            <div class="modal-content custom_modal p-4">

                <div class="modal-header custom_modal_header align-items-center">
                    <div class="col-6 d-flex align-items-center justify-content-left">
                        <h1 class="h3">Monitoring-Alerts</h1>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn button-primary-orange" onclick="callbackHandleSwitchModal(() => $('#modal-addAlert').modal('show') );">Alert erstellen</button>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Auslöser</th>
                            <th scope="col">Aktion</th>
                            <th scope="col">Empfänger</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                
                        @foreach($alerts as $i => $alert)
                        
                            <tr>
                                <th class="paragraphSmallSlim">{{ $i+1 }}</th>
                                <td>{{ get_alert_event($alert->event) }}</td>
                                <td>{{ get_alert_action($alert->action) }}</td>
                                <td style="overflow-x: hidden;max-width: 200px;">{{ $alert->action_reference }}</td>
                                <td>
                                    <div class="d-flex">
                                        <i class="fas fa-edit mr-2" style="cursor: pointer;" onclick="editAlertModal('{{ $alert->id }}')"></i>
                                        <i class="fas fa-trash" style="cursor: pointer;" onclick="deleteAlertModal('{{ $alert->id }}')"></i>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>

     <!-- Maintenance mode -->
    <div class="modal fade" id="modal-maintenance" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <a class="headline">Wartungsmodus</a>
                        </div>
                        <div class="col-12 d-flex justify-content-center mt-2">
                            <a class="subtitle">Informiere deine Nutzer über anstehende Wartungsarbeiten</a>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-12 pl-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>So sieht deine Wartung im Web aus:</label>
                                    <div class="alert alert-warning" style="width:100%!important;" role="alert" id="messagePreview">
                                    @if($maintenance)
                                        {{ $maintenance->text ?: '' }}
                                    @else
                                        Vorschau deiner Wartungsnachricht.
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <form method="post" action="/maintenance/changeMessage">@csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Beschreibe deine Arbeiten</label>
                                                <textarea id="maintenanceMessage" class="form-control" oninput="showMaintenancemMessagePreview()" placeholder="Wir führen am 10.08 von 20:00 bis 23:00 Uhr Wartungsarbeiten an unseren Systemen durch. Dabei kann es zeitweise zu Ausfällen kommen." name="maintenanceMessage" rows="3">{{$maintenance ? ($maintenance->text ?: '') : ''}}</textarea>
                                            </div>
                                        </div>
                                    </div>                   
                                </div>
                                @if($discord_channels)
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlSelect1">Im Discord veröffentlichen?</label>
                                                        <select class="form-control" name="discordMaintenanceAlert">
                                                            <option value="null" selected>Nein</option>

                                                            @foreach($discord_channels as $channel)
                                                                <option value="{{$channel->id}}">{{$channel->name}}</option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                   
                                    </div>
                                @endif
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center mt-4">
                        <div class="col d-flex justify-content-center">
                            <button class="customButton" style="" onclick="">Wartungsnachricht senden</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Alert Management -->
    <div class="modal fade" id="modal-addAlert" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <a class="headline">Alert erstellen</a>
                        </div>
                    </div>

                    <form method="post" action="/monitoring/addAlert">@csrf
                        <div class="row mt-5">
                            <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Event</label>
                                                <select name="event" type="text" class="form-control" autocomplete="off" required>
                                                    <option value="" selected disabled readonly>Bitte auswählen</option>
                                                    <option value="monitoring_down">Monitoring ausgefallen</option>
                                                    <option value="monitoring_online_again">Monitoring wieder online</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Aktion</label>
                                                <select onchange="onChangeEvent(this)" name="action" type="text" class="form-control" autocomplete="off" required>
                                                    <option value="" selected disabled readonly>Bitte auswählen</option>
                                                    <option value="send_mail">E-Mail senden</option>
                                                    <option value="send_sms">SMS senden</option>
                                                    <option value="discord_webhook">Discord-Nachricht senden (Webhook)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="add_alert_reference" class="col-6 offset-3 d-none" style="">
                                            <div class="form-group">
                                                <label id="add_alert_reference_label">SET-BY-SCRIPT</label>
                                                <input id="add_alert_reference_input" name="reference" type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <script>
                            function onChangeEvent(element) {

                                let label;

                                if($(element).val() == 'send_mail') {
                                    label = 'Empfänger-Email-Adresse';
                                    $('#add_alert_reference_input').attr('type', 'email');
                                } else if($(element).val() == 'send_sms') {
                                    label = 'Empfänger Telefonnummer';
                                    $('#add_alert_reference_input').attr('type', 'text');
                                } else if($(element).val() == 'discord_webhook') {
                                    label = 'Discord-Webhook-Link';
                                    $('#add_alert_reference_input').attr('type', 'text');
                                }

                                $('#add_alert_reference_label').text(label);
                                $('#add_alert_reference').removeClass('d-none');
                            }
                        </script>

                        <div class="row d-flex justify-content-center mt-4">
                            <div class="col d-flex justify-content-center">
                                <button type="submit" class="customButton">Alert erstellen</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const alerts = @json($alerts);

        function editAlertModal(alert_id) {

            let alert = alerts.find(alert => alert.id == alert_id);


            $('#edit_alert_form').attr('action', `/monitoring/editAlert/${alert_id}`);
            $('#edit_alert_event').val(alert.event);
            $('#edit_alert_action').val(alert.action);

            $('#edit_alert_action').trigger('change');
            
            callbackHandleSwitchModal(() => {
                $('#edit_alert_reference_input').val(alert.action_reference);
                $('#modal-editAlert').modal('show');
            });

        }

        function deleteAlertModal(alert_id) {
            $('#modal-deleteAlert_form').attr('action', `/monitoring/deleteAlert/${alert_id}`);
            callbackHandleSwitchModal(() => {
                $('#modal-deleteAlert').modal('show')
            });
        }

        function initAddMonitoringFormInputs() {
            document.getElementById("AddM_ip").style.display = "none";
            document.getElementById("AddM_port").style.display = "none";
        }

        function chooseAddMonitoringType() {
            const selectType = document.getElementById("addM_select").value;

            switch (selectType) {
                case "webseite": 
                    document.getElementById("AddM_url").style.display = "block";
                    document.getElementById("AddM_ip").style.display = "none";
                    document.getElementById("AddM_port").style.display = "none";
                break;
                case "gameserver": 
                    document.getElementById("AddM_ip").style.display = "block";
                    document.getElementById("AddM_port").style.display = "block";  
                    document.getElementById("AddM_url").style.display = "none";               
                break;
                case "teamspeak": 
                    document.getElementById("AddM_ip").style.display = "block";
                    document.getElementById("AddM_port").style.display = "none";  
                    document.getElementById("AddM_url").style.display = "none"; 
                break;
                case "server": 
                    document.getElementById("AddM_ip").style.display = "block";
                    document.getElementById("AddM_port").style.display = "none";  
                    document.getElementById("AddM_url").style.display = "none"; 
                break;
                case "service": 
                    document.getElementById("AddM_ip").style.display = "block";
                    document.getElementById("AddM_port").style.display = "block";
                    document.getElementById("AddM_url").style.display = "none";
                break;
                default: 
                    document.getElementById("AddM_url").style.display = "block";
                    document.getElementById("AddM_ip").style.display = "none";
                    document.getElementById("AddM_port").style.display = "none";
                break;
            }
        }

        function chooseEDITMonitoringType(selectType) {
            console.log(selectType);

            switch (selectType) {
                case "webseite": 
                    document.getElementById("EditM_url").style.display = "block";
                    document.getElementById("EditM_ip").style.display = "none";
                    document.getElementById("EditM_port").style.display = "none";
                break;
                case "gameserver": 
                    document.getElementById("EditM_ip").style.display = "block";
                    document.getElementById("EditM_port").style.display = "block";  
                    document.getElementById("EditM_url").style.display = "none";               
                break;
                case "teamspeak": 
                    document.getElementById("EditM_ip").style.display = "block";
                    document.getElementById("EditM_port").style.display = "none";  
                    document.getElementById("EditM_url").style.display = "none"; 
                break;
                case "server": 
                    document.getElementById("EditM_ip").style.display = "block";
                    document.getElementById("EditM_port").style.display = "none";  
                    document.getElementById("EditM_url").style.display = "none"; 
                break;
                case "service": 
                    document.getElementById("EditM_ip").style.display = "block";
                    document.getElementById("EditM_port").style.display = "block";
                    document.getElementById("EditM_url").style.display = "none";
                break;
                default: 
                    document.getElementById("EditM_url").style.display = "block";
                    document.getElementById("EditM_ip").style.display = "none";
                    document.getElementById("EditM_port").style.display = "none";
                break;
            }
        }

        function changeEditMonitoringFormInputs() {
            const selectType = document.getElementById("edit_kategorie").value;
            chooseEDITMonitoringType(selectType);
        }

        function showMaintenancemMessagePreview() {
            const messageBlock = document.getElementById("maintenanceMessage").value;
            const messagePreview = document.getElementById("messagePreview").innerHTML = messageBlock;
        }
    </script>

    <!-- Alert bearbeiten -->
    <div class="modal fade" id="modal-editAlert" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <a class="headline">Alert bearbeiten</a>
                        </div>
                    </div>

                    <form id="edit_alert_form" method="post" action="SETBYSCRIPT">@csrf
                        <div class="row mt-5">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Event</label>
                                            <select id="edit_alert_event" name="event" type="text" class="form-control" autocomplete="off" required>
                                                <option value="" selected disabled readonly>Bitte auswählen</option>
                                                <option value="monitoring_down">Monitoring ausgefallen</option>
                                                <option value="monitoring_online_again">Monitoring wieder online</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Aktion</label>
                                            <select id="edit_alert_action" onchange="onChangeEditEvent(this)" name="action" type="text" class="form-control" autocomplete="off" required>
                                                <option value="" selected disabled readonly>Bitte auswählen</option>
                                                <option value="send_mail">E-Mail senden</option>
                                                <option value="send_sms">SMS senden</option>
                                                <option value="discord_webhook">Discord-Nachricht senden (Webhook)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="edit_alert_reference" class="col-6 offset-3 d-none" style="">
                                        <div class="form-group">
                                            <label id="edit_alert_reference_label">SET-BY-SCRIPT</label>
                                            <input id="edit_alert_reference_input" name="reference" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function onChangeEditEvent(element) {

                                let label;

                                if($(element).val() == 'send_mail') {
                                    label = 'Empfänger-Email-Adresse';
                                    $('#edit_alert_reference_input').attr('type', 'email');
                                } else if($(element).val() == 'send_sms') {
                                    label = 'Empfänger Telefonnummer';
                                    $('#edit_alert_reference_input').attr('type', 'text');
                                } else if($(element).val() == 'discord_webhook') {
                                    label = 'Discord-Webhook-Link';
                                    $('#edit_alert_reference_input').attr('type', 'text');
                                }

                                $('#edit_alert_reference_label').text(label);
                                $('#edit_alert_reference').removeClass('d-none');
                            }
                        </script>

                        <div class="row d-flex justify-content-center mt-4">
                            <div class="col d-flex justify-content-center">
                                <button type="submit" class="customButton">Alert erstellen</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - delete a employee -->
    <div class="modal fade" id="modal-deleteAlert" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content custom_modal">
                <div class="modal-header custom_modal_header">
                    <div class="row">
                        <div class="col-xl-6 col-6">
                            <img src="/assets/images/alert-triangle.png" class="w-100 h-auto">
                        </div>
                    </div>
                </div>
                <form action="" id="modal-deleteAlert_form" method="post">
                    @csrf
                    <div class="modal-body">
                        <h4 class="h2" id="exampleModalLabel" style="margin-bottom: 16px;">Alert entfernen</h4>
                        <div class="page_subtitle">
                            Bist du dir sicher, dass du diesen Alert entfernen möchtest?
                        </div>
                    </div>
                    <div class="modal-footer custom_modal_footer justify-content-center">
                        <button style="width: 46%;" type="button" class="btn button-primary"
                                data-dismiss="modal">Abbrechen
                        </button>
                        <button style="width: 46%;" type="submit"
                                class="btn button-primary-red">Entfernen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    <script>
        let monitoringServices = @json($services);

        function copyLinkToClipboard() {
            var finalText = '{{$copyLinkURL}}';

            navigator.clipboard.writeText(finalText);
            new Notification('success', 'Der Link wurde kopiert.', '5000')
        }

        function showServiceDayModal(service_id, date) {
            $('#monitoring-statusbar-wrapper').empty();

            $('#showMonitoringServiceDayDetailsSubtitle').text('Details vom Monitoring Service am ' + date);

            let monitoringService = monitoringServices.find(service => service.id == service_id);
            let currentDateData = monitoringService.status.find(dateData => dateData.date == date);
            let statusCounts = currentDateData.status_counts;

            if (currentDateData.status === 'no_data') {
                $('#monitoring-statusbar-wrapper').append('<span class="monitoring-statusbar-item monitoring-statusbar-item-empty" data-toggle="tooltip" data-placement="top" title="Keine Daten vorhanden"></span>')
            } else {
                let courses = currentDateData.courses;
                if (
                    typeof currentDateData.courses === 'object' &&
                    !Array.isArray(currentDateData.courses) &&
                    currentDateData.courses !== null
                ) {
                    courses = Object.keys(currentDateData.courses).map(key => currentDateData.courses[key]);
                }

                let courseItems = [];
                let lastItem = null;

                courses.forEach((value, key) => {

                    if (lastItem?.state !== value) {
                        if(lastItem) {
                            courseItems.push(lastItem)

                            lastItem = null;
                        }
                    } else {
                        lastItem.end = key;
                    }

                    if(key === 0 || !lastItem) {
                        lastItem = {
                            'start': key,
                            'end': key,
                            'state': value
                        }
                        return;
                    }
                })

                courseItems.push(lastItem);

                courseItems.forEach(item => {
                    let percentageRange = (((item.end - item.start) +1) / 24) * 100;
                    let start = ('0' + item.start).slice(-2);
                    let end = ('0' + parseInt(item.end+1)).slice(-2);
                    let statusTitle = '';
                    let connector = 'bis';

                    switch (item.state) {
                        case 'success':
                            statusTitle = 'Online von';
                            break;
                        case 'warning':
                            statusTitle = 'Ausfälle zwischen';
                            connector = 'und';
                            break;
                        case 'danger':
                            statusTitle = 'Offline von';
                            break;
                        case 'empty':
                            statusTitle = 'Keine Daten zwischen';
                            connector = 'und';
                            break;
                    }
                    statusTitle += ` ${start}:00 ${connector} ${end}:00 Uhr`;

                    $('#monitoring-statusbar-wrapper').append('<span style="width: ' + percentageRange + '%" class="mx-1 monitoring-statusbar-item monitoring-statusbar-item-' + item.state + '" data-toggle="tooltip" data-placement="top" title="' + statusTitle + '"></span>')
                })
            }

            callbackHandleSwitchModal(() => {
                $('#showMonitoringServiceDay').modal('show');
                $('[data-toggle="tooltip"]').tooltip();
            })

        }

        function deleteServiceModal(service_id, project_id) {
            $('#modal-deleteService_form').attr('action', `/monitoring/delete/${service_id}`);
            $('#modal-deleteService').modal('show')
        }

        function editServiceModal(id) {
            $('#editMonitoringService').modal('show');

            let monitoringService = monitoringServices.find(service => service.id == id);
            chooseEDITMonitoringType(monitoringService.type);

            $('#edit_form').attr('action', `/monitoring/edit/${monitoringService.id}`);

            $('#edit_bezeichnung').val(monitoringService.name);
            $('#edit_kategorie').val(monitoringService.type);
            $('#edit_url').val(monitoringService.url);
            $('#edit_ip').val(monitoringService.ip);
            $('#edit_port').val(monitoringService.port);

        }

        
    </script>
</div>
</body>

