@include('layouts.header')
@php
    use App\Helpers\Subscription;
    $timezone = date_default_timezone_get();

    $currentProject = \App\Helpers\Auth::user('activeProject');
    $isOwner = \App\Helpers\Auth::user('isOwner');

    $supportConfig = [
        'discord_init_title' => 'Ticket Support',
        'discord_init_description' => '**Du hast Fragen oder Probleme?**
         Erstelle ein Ticket, in dem du aus einer unserer Kategorien wählst und wir unterstützen dich persönlich bei deinem Anliegen.',
        'discord_ticket_welcome_message' => 'Hallo @user Es wird sich schnellstmöglich ein freier Supporter hier im Ticket bei dir melden.',
        'out_of_time_message' => 'Hallo @user. Du hast ein Ticket außerhalb unserer Supportzeiten eröffnet. Wir werden uns so schnell wie möglich bei dir melden.',
        'time_start' => '',
        'time_end' => '',
        'support_days' => false,
    ];

    if($project->supportConfig) {
        if($project->supportConfig->discord_init_title) {
            $supportConfig['discord_init_title'] = $project->supportConfig->discord_init_title;
        }
        if($project->supportConfig->discord_init_description) {
            $supportConfig['discord_init_description'] = $project->supportConfig->discord_init_description;
        }
        if($project->supportConfig->discord_ticket_welcome_message) {
            $supportConfig['discord_ticket_welcome_message'] = $project->supportConfig->discord_ticket_welcome_message;
        }
        if($project->supportConfig->out_of_time_message) {
            $supportConfig['out_of_time_message'] = $project->supportConfig->out_of_time_message;
        }
        if($project->supportConfig->time_start) {
            $supportConfig['time_start'] = $project->supportConfig->time_start;
        }
        if($project->supportConfig->time_end) {
            $supportConfig['time_end'] = $project->supportConfig->time_end;
        }
        if($project->supportConfig->time_end) {
            $supportConfig['support_days'] = $project->supportConfig->support_days;
        }
    }

@endphp

@if(App::environment('production'))
    @php
        $discord_bot_url = 'https://discord.com/api/oauth2/authorize?client_id=980458246504587324&permissions=8&redirect_uri=https%3A%2F%2Fwehood.app%2FsetProjectGuildID&response_type=code&scope=identify%20bot%20messages.read';
    @endphp
@else
    @php
        $discord_bot_url = 'https://discord.com/api/oauth2/authorize?client_id=994534235308314694&permissions=8&redirect_uri=http%3A%2F%2Flocalhost%3A8888%2FsetProjectGuildID&response_type=code&scope=guilds%20bot%20identify%20email%20guilds.join%20applications.commands';
    @endphp
@endif

<body onload="startTab()">
<div class="page_content">

    <!--- Page headline -->
    <div class="row">
        <div class="col page_title_area">
            <h3 class="page_headline">Projekteinstellungen</h3>
            <h6 class="page_subtitle">Dein Projekt, deine Regeln</h6>
        </div>
        <div class="col-8 text-right">
        </div>
    </div>

    <style>
        .settingTabNav {
            border: none;
            margin: 5px 0px;
            padding: 14px;
            background-color: transparent;
            text-align: left;
            border-radius: 9px;
        }

        .settingTabNav .title {
            font-weight: 400;
            font-size: 18px;
            color: white!important;
        }

        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
            color: #fff;
            background-color: #2A85FF!important;
            border-radius: 7px;
        }

        .settingsubHeadline {
            font-size: 18px;
            color: white;
            font-weight: 700;
            width: 100%;
            margin-bottom: 30px;
        }
    </style>

    
<div class="row mt-5">
    <div class="col-xl-3 mb-5">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link settingTabNav active" data-toggle="pill" data-target="#v-pills-basicSettings" type="button" role="tab"><a class="title"><i class="fa-solid fa-sliders-up"></i> Basis Einstellungen</a></button>
            <button class="nav-link settingTabNav" data-toggle="pill" data-target="#v-pills-support" type="button" role="tab"><a class="title"><i class="fa-solid fa-headset"></i> Supportsystem</a></button>
            <button class="nav-link settingTabNav" data-toggle="pill" data-target="#v-pills-liveChat" type="button" role="tab"><a class="title"><i class="fa-solid fa-comment"></i> LiveChat</a></button>
            <button class="nav-link settingTabNav" data-toggle="pill" data-target="#v-pills-branding" type="button" role="tab"><a class="title"><i class="fa-solid fa-palette"></i> Branding</a></button>
            <button class="nav-link settingTabNav" data-toggle="pill" data-target="#v-pills-communityCenter" type="button" role="tab"><a class="title"><i class="fa-solid fa-browser"></i> Community Center</a></button>
            <button class="nav-link settingTabNav" data-toggle="pill" data-target="#v-pills-alerts" type="button" role="tab"><a class="title"><i class="fa-solid fa-bells"></i> Alertsystem</a></button>
            <button class="nav-link settingTabNav" data-toggle="pill" data-target="#v-pills-danger" type="button" role="tab"><a class="title"><i class="fa-solid fa-triangle-exclamation"></i> Danger Zone</a></button>
        </div>
    </div>
    <div class="col-xl-1"></div>
    <div class="col-xl-8">
        <div class="tab-content" id="v-pills-tabContent">

            <!-- Basic Settings Tab -->
            <div class="tab-pane fade" id="v-pills-basicSettings" role="tabpanel">
                <div class="row" style="align-items: center;">
                    <div class="col-8 mb-4">
                        <p class="settingsHeadline">Basis Einstellungen</p>
                        <a href="" class="settingsSubHead">Hier findest du die Grundeinstellungen.</a>
                    </div>
                    <div class="col-4 mb-4"></div>
                </div>

                <div class="row mt-4">
                    <div class="col-xl-6">
                    <form action="/settings/editProjectName" method="post">@csrf
                        <div class="form-group">
                            <label>Projektname</label>
                            <input type="text" class="form-control" name="projektName" value="{{ $project->name }}">
                            <button type="submit" class="btn btn-block btn-primary mt-3">Speichern</button>
                        </div>
                    </form>
                    </div>
                    <div class="col-xl-6"></div>
                </div>
            </div>

            <!-- Discord Bot Settings Tab -->
            <div class="tab-pane fade show" id="v-pills-support" role="tabpanel">
            <div class="row">
                <div class="ml-3 settingsubHeadline">Discord Bot <a href="https://www.youtube.com/watch?v=GRU-_EFd5EE" target="_blank"><i class="fa-solid fa-circle-question"></i></a></div>

                <div class="col-xl-5">
                    @if(!$discord_channels)
                        <div class="form-group">
                            <button type="button" onclick="window.location.href = '{{ $discord_bot_url }}'" class="btn btn-primary mt-2"><i class="fa-brands fa-discord"></i> Discord Server verbinden</button>
                        </div>
                    @else 
                        <form action="/setProjectInitChannel" id="test" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Channel für Supportnachricht</label>
                                <select name="discord_channel" id="" class="form-control">
                                    @foreach($discord_channels as $channel)
                                        <option value="{{$channel->id}}">{{$channel->name}}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary mt-3" style="width: 100%;">Nachricht senden</button>
                            </div>
                        </form>
                    @endif
                </div>

                @if($discord_channels)
                <div class="col-xl-5">
                    <div class="form-group">
                        <label>Support Init Nachricht</label>
                        <button type="button" class="btn btn-block btn-primary mt-0" data-toggle="modal" data-target="#modal-editTicketChannelMessage">Bearbeiten</button>
                    </div>
                </div>

                <div class="col-xl-5 mt-4">
                    <div class="form-group">
                        <label>Discord Ticket Willkomensnachricht</label>
                        <button type="button" class="btn btn-block btn-primary mt-0" data-toggle="modal" data-target="#modal-editTicketWelcomeMessage">Bearbeiten</button>
                    </div>
                </div>
                @endif
            </div>
                

            <div class="settingsSpacer"></div>
            <div class="row">
                    <div class="ml-3 settingsubHeadline">Supportsystem <a href="https://www.youtube.com/watch?v=558L1MVgLpg" target="_blank"><i class="fa-solid fa-circle-question"></i></a></div>

                    <div class="col-xl-5 mb-2">
                        <div class="form-group">
                            <label>Deine Support Kategorien @if(!App\Helpers\Subscription::hasActiveSubscription('support')) <a class="badge badge-primary premiumBadge" href="products/checkout/support">Hood One</a> @endif</label>
                            {{-- <select name="discord_channel" id="" class="form-control">
                                @if(count($support_categories) <= 0)
                                    <option value="" selected readonly disabled>Keine Kategorie vorhanden</option>
                                @endif
                                @foreach($support_categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select> --}}
                            @if(App\Helpers\Subscription::hasActiveSubscription('support'))
                                <button type="button" class="btn btn-block btn-primary mt-0" data-toggle="modal" data-target="#modal-customSupportCategories">Bearbeiten</button>
                            @else
                                <button type="button" class="btn btn-block btn-primary mt-0" disabled>Bearbeiten</button>
                            @endif
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group">
                            <label>Supportzeiten einstellen @if(!App\Helpers\Subscription::hasActiveSubscription('support')) <a class="badge badge-primary premiumBadge" href="products/checkout/support">Hood One</a> @endif</label>
                            @if(App\Helpers\Subscription::hasActiveSubscription('support'))
                                <button type="button" class="btn btn-block btn-primary mt-0" data-toggle="modal" data-target="#modal-editSupportTime">Bearbeiten</button>
                            @else
                                <button type="button" class="btn btn-block btn-primary mt-0" disabled>Bearbeiten</button>
                            @endif
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="form-group">
                            <label>Text Snippet erstellen @if(!App\Helpers\Subscription::hasActiveSubscription('support')) <a class="badge badge-primary premiumBadge" href="products/checkout/support">Hood One</a> @endif</label>
                            @if(App\Helpers\Subscription::hasActiveSubscription('support'))
                                <button type="button" class="btn btn-block btn-primary mt-0" data-toggle="modal" data-target="#modal-addTextSnippet">Hinzufügen</button>
                            @else
                                <button type="button" class="btn btn-block btn-primary mt-0 disabled" disabled>Hinzufügen</button>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

            <!-- LiveChat Settings Tab -->
            <div class="tab-pane fade" id="v-pills-liveChat" role="tabpanel">
                <div class="row">
                    <div class="ml-3 settingsubHeadline">Javascript Einbindung @if(!App\Helpers\Subscription::hasActiveSubscription('support')) <a class="badge badge-primary premiumBadge" href="products/checkout/support">Hood One</a> @endif</div>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <label>Skript kopieren und in deiner Seite einfügen </label>
                            @if(App\Helpers\Subscription::hasActiveSubscription('support'))
                            <button type="button" class="btn btn-block btn-primary mt-0" onclick="copyLiveChatScriptToClipboard()" >Live-Chat-Skript kopieren</button>
                            @else
                            <button type="button" class="btn btn-block btn-primary mt-0" disabled>Live-Chat-Skript kopieren</button>
                            @endif
                            <input type="text" id="livechat_script" class="form-control mt-2" style="display: none" disabled readonly>
                        </div>
                    </div>
                </div>

                <div class="settingsSpacer"></div>
                <div class="row d-flex align-items-center">
                    <div class="ml-3 settingsubHeadline">Chat Bubble Icon anpassen <small>(64x64px)</smalL> @if(!App\Helpers\Subscription::hasActiveSubscription('support')) <a class="badge badge-primary premiumBadge" href="products/checkout/support">Hood One</a> @endif</div>
                    <div class="col-xl-2">
                        <div class="form-group">
                            <img class="brandLogo" src="{{$livechatSettings->bubble_image != null ? $livechatSettings->bubble_image : '/assets/images/noImage.png'}}">
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="form-group">
                            @if(App\Helpers\Subscription::hasActiveSubscription('support'))
                            <button type="button" class="btn btn-block btn-primary mt-0" onclick="uploadLivechatToggleImage()">Icon wählen</button>
                            @else
                            <button type="button" class="btn btn-block btn-primary mt-0" disabled>Icon wählen</button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="settingsSpacer"></div>
                <form action="/settings/editLivechatTexts" method="post">@csrf
                <div class="row d-flex align-items-center">
                <div class="ml-3 settingsubHeadline">Chat Texte anpassen @if(!App\Helpers\Subscription::hasActiveSubscription('support')) <a class="badge badge-primary premiumBadge" href="products/checkout/support">Hood One</a> @endif</div>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control" name="headline" value="{{ $livechatSettings->chat_headline ? $livechatSettings->chat_headline : 'Hi, wir sind Hood 👋'}}">
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="form-group">
                         @if(App\Helpers\Subscription::hasActiveSubscription('support'))
                            <button type="submit" class="btn btn-block btn-primary mt-0" >Headline speichern</button>
                        @else
                            <button class="btn btn-block btn-primary mt-0" disabled>Headline speichern</button>
                        @endif
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control" name="subtitle" value="{{ $livechatSettings->chat_subtitle ? $livechatSettings->chat_subtitle : 'Cool, dass du da bist'}}">
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="form-group">
                        @if(App\Helpers\Subscription::hasActiveSubscription('support'))
                            <button type="submit" class="btn btn-block btn-primary mt-0" >Subtitle speichern</button>
                        @else
                            <button class="btn btn-block btn-primary mt-0" disabled>Subtitle speichern</button>
                        @endif
                        </div>
                    </div>
                </div>
                </form>

                {{-- <div class="settingsSpacer"></div>
                <form action="/settings/editWhitelabel" method="post">@csrf
                <div class="row d-flex align-items-center">
                    <div class="ml-3 settingsubHeadline">Chat Widgets</div>
                    <div class="col-xl-5">
                            @if($project->show_whitelabel == 'false')
                            <input name="showWhitelabel" value="true" hidden>
                            <button type="submit" class="btn btn-block btn-primary mt-0">Chat starten anzeigen</button>
                            @else
                            <input name="showWhitelabel" value="false" hidden>
                            <button type="submit" class="btn btn-block btn-primary mt-0">Chat starten deaktivieren</button>
                            @endif
                    </div>
                </div>
                </form> --}}
            </div>

            <!-- Branding Settings Tab -->
            <div class="tab-pane fade" id="v-pills-branding" role="tabpanel">

                {{-- @if(!Subscription::hasActiveSubscription('branding'))
                    <div class="row">
                        <div class="col unlockFeatureDiv">
                            <div>
                                <i style="color: gold" class="fa-solid fa-star"></i> <span>Du benötigst <b><a href="https://wehood.io/products/branding" target="_blank">Hood Branding</a></b> damit diese Einstellungen funktionieren.</span>
                            </div>
                            <div class="mt-4">   
                                <a class="unlockBadge" href="/products">Jetzt Freischalten</a>
                            </div>
                        </div>
                    </div>
                @endif --}}
                <div class="row d-flex align-items-center">
                    <div class="ml-3 settingsubHeadline">Logo anpassen <a href="https://youtu.be/kPnPtzKyUHE" target="_blank"><i class="fa-solid fa-circle-question"></i></a>@if(!App\Helpers\Subscription::hasActiveSubscription('support')) <a class="badge badge-primary premiumBadge" href="products/checkout/branding">Hood Branding</a> @endif</div>
                    <div class="col-xl-2">
                        <div class="form-group">
                            <img class="brandLogo" src="{{$project->logo != null ? $project->logo : '/assets/images/noImage.png'}}">
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="form-group">
                        @if(App\Helpers\Subscription::hasActiveSubscription('branding'))
                            <button type="button" class="btn btn-block btn-primary mt-0" onclick="uploadLogo()">Logo wählen</button>
                        @else
                            <button type="button" class="btn btn-block btn-primary mt-0" disabled>Logo wählen</button>
                        @endif 
                        </div>
                    </div>
                </div>

                <div class="settingsSpacer"></div>
                <form action="/settings/editDomain" method="post">@csrf
                <div class="row d-flex align-items-center">
                    <div class="ml-3 settingsubHeadline">Custom Subdomain <a href="https://youtu.be/kPnPtzKyUHE" target="_blank"><i class="fa-solid fa-circle-question"></i></a>@if(!App\Helpers\Subscription::hasActiveSubscription('support')) <a class="badge badge-primary premiumBadge" href="products/checkout/branding">Hood Branding</a> @endif</div>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control" name="customDomain" value="{{$project ? $project->project_hash : ''}}">
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="form-group">
                        @if(App\Helpers\Subscription::hasActiveSubscription('branding'))
                            <button type="submit" class="btn btn-block btn-primary mt-0">Subdomain speichern</button>
                        @else
                            <button class="btn btn-block btn-primary mt-0" disabled>Subdomain speichern</button>
                        @endif
                        </div>
                    </div>
                </div>
                </form>

                <div class="settingsSpacer"></div>
                    <form action="/settings/editWhitelabel" method="post">@csrf
                    <div class="row d-flex align-items-center">
                        <div class="ml-3 settingsubHeadline">Whitelabel <a href="https://youtu.be/kPnPtzKyUHE" target="_blank"><i class="fa-solid fa-circle-question"></i></a>@if(!App\Helpers\Subscription::hasActiveSubscription('support')) <a class="badge badge-primary premiumBadge" href="products/checkout/branding">Hood Branding</a> @endif</div>
                        <div class="col-xl-5">
                        @if(App\Helpers\Subscription::hasActiveSubscription('branding'))
                                @if($project->show_whitelabel == 'false')
                                <input name="showWhitelabel" value="true" hidden>
                                <button type="submit" class="btn btn-block btn-primary mt-0">Whitelabel aktivieren</button>
                                @else
                                <input name="showWhitelabel" value="false" hidden>
                                <button type="submit" class="btn btn-block btn-primary mt-0">Whitelabel deaktivieren</button>
                                @endif
                        @else
                                @if($project->show_whitelabel == 'false')
                                <input name="showWhitelabel" value="true" hidden>
                                <button class="btn btn-block btn-primary mt-0" disabled>Whitelabel aktivieren</button>
                                @else
                                <input name="showWhitelabel" value="false" hidden>
                                <button class="btn btn-block btn-primary mt-0" disabled>Whitelabel deaktivieren</button>
                                @endif
                        @endif
                        </div>
                    </div>
                    </form>
            </div>

            <!-- Community Center Tab -->
            <div class="tab-pane fade" id="v-pills-communityCenter" role="tabpanel">
                <div class="row" style="align-items: center;">
                    <div class="col-8 mb-4">
                        <p class="settingsHeadline">Community Center</p>
                        <a href="" class="settingsSubHead">Manage deinen externen Hood Auftrit.</a>
                    </div>
                    <div class="col-4 mb-4"></div>
                </div>

                <div class="row mt-4">
                    <div class="col-xl-6">
                    <form action="/settings/editCommunityCenterHeadline" method="post">@csrf
                        <div class="form-group">
                            <label>Titel / Headline</label>
                            <input type="text" class="form-control" name="communityCenter_headline" value="{{ $communityCenterSettings ? $communityCenterSettings->headline : $project->name . " Community Center"}}">
                            <button type="submit" class="btn btn-block btn-primary mt-3">Speichern</button>
                        </div>
                    </form>
                    </div>
                    <div class="col-xl-6"></div>
                </div>
            </div>

            <!-- Alerts Tab -->
            <div class="tab-pane fade" id="v-pills-alerts" role="tabpanel">
                <div class="row" style="align-items: center;">
                    <div class="col-8 mb-4">
                        <p class="settingsHeadline">Alerts verwalten</p>
                        <a href="" class="settingsSubHead">Lass dich benachrichtigen.</a>
                    </div>
                    <div class="col-4 mb-4 d-flex align-items-center">
                        <button type="submit" data-toggle="modal" data-target="#modal-addAlert" class="btn btn-block btn-primary">Alert erstellen</button>
                    </div>
                </div>

                <div class="row mt-4">
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
                                <td class="paragraphSmallSlim">{{ $i+1 }}</td>
                                <td>{{ $alert->event }}</td>
                                <td>{{ $alert->action }}</td>
                                <td style="overflow-x: hidden;max-width: 200px;">{{ $alert->action_reference }}</td>
                                <td>
                                    <div class="d-flex">
                                        <i class="fas fa-trash" style="cursor: pointer;" onclick="deleteAlert('{{ $alert->id }}')"></i>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                </div>
            </div>

            <!-- Danger Settings Tab -->
            <div class="tab-pane fade" id="v-pills-danger" role="tabpanel">
                <div class="row" style="align-items: center;">
                    <div class="col-8 mb-4">
                        <p class="settingsHeadline">Danger Zone</p>
                        <a href="" class="settingsSubHead">Alle Änderungen hier sind unwiderruflich.</a>
                    </div>
                    <div class="col-4 mb-4"></div>
                </div>
                <button class="btn button-primary-red" data-toggle="modal" data-target="#modal-deleteProject">Projekt löschen</button>
            </div>
        </div>
    </div>
</div>

</div>




<!-- =================== -->
<!-- BEGINN MODAL AND JS -->
<!-- =================== -->
@if(App\Helpers\Subscription::hasActiveSubscription('support'))
<div class="modal fade" id="modal-editSupportTime" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="width: 650px; margin: 0 auto;">
            <form action="/settings/editSupportTime" method="post">
                @csrf
                <div class="modal-header custom_modal_header">
                    <p class="h3" id="exampleModalLabel">Supportzeiten einstellen</p>
                </div>
                <div class="modal-body">

                    <input type="text" name="support_days" id="hiddenform_support_days" hidden>

                    <div class="support-time-days">

                        <div class="day-card" data-dayid="1"><a>Montag</a></div>
                        <div class="day-card" data-dayid="2"><a>Dienstag</a></div>
                        <div class="day-card" data-dayid="3"><a>Mittwoch</a></div>
                        <div class="day-card" data-dayid="4"><a>Donnerstag</a></div>
                        <div class="day-card" data-dayid="5"><a>Freitag</a></div>
                        <div class="day-card" data-dayid="6"><a>Samstag</a></div>
                        <div class="day-card" data-dayid="7"><a>Sonntag</a></div>

                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="exampleInputEmail1">Von</label>
                                <input name="time_start" type="time" class="form-control" value="{{ $supportConfig['time_start'] }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="exampleInputEmail1">Bis</label>
                                <input name="time_end" type="time" class="form-control" value="{{ $supportConfig['time_end'] }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12 offset-0">
                            <div class="form-group mt-2">
                                <label for="exampleInputEmail1" class="mt-3">Nachricht für Ticket ausßerhalb der Supportzeiten</label>
                                <textarea name="out_of_time_message" id="" cols="30" rows="10" class="form-control" style="widht: 100%;" required>{{ $supportConfig['out_of_time_message'] }}</textarea>
                                <p href="" class="settingsSubHead mt-3"><span>Um den Discord-Namen (Discord-Ping) im Text anzugeben, {{ '@user' }} verwenden</span></p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-block button-primary-orange w-50">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Alert Add -->
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
                                                    <option value="new_support_chat">Neuer Chat/Ticket eröffnet</option>
                                                    <option value="new_bugreport">Neuer Bugreport erstellt</option>
                                                    <option value="new_user_wish">Neuer Wunsch geäußert</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Aktion</label>
                                                <select onchange="onChangeEvent(this)" name="action" type="text" class="form-control" autocomplete="off" required>
                                                    <option value="" selected disabled readonly>Bitte auswählen</option>
                                                    <option value="send_mail">E-Mail senden</option>
                                                    <option value="mobile_push">Mobile Pushnachricht senden</option>
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
                                } else if($(element).val() == 'mobile_push') {
                                    label = 'SimplePush Geräte ID';
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

    const days = JSON.parse(@json($supportConfig['support_days'])); 

    $(() => {
        setCardDays();
    });

    $('.day-card').on('click', function(){
        $(this).toggleClass('active');
        setFormDays();
    });

    function setCardDays(){
        for(let day of days) {
            if(day.active === false) continue;
            $(`.day-card[data-dayid=${day.day}]`).addClass('active');
        }
        setFormDays();
    }

    function setFormDays() {
        $('#hiddenform_support_days').val(JSON.stringify(prepareSupportDays()));
    }

    function prepareSupportDays() {
        let days = [];
        $('.day-card').each(function(){
            let day = $(this).data('dayid');
            let active = false;
            if($(this).hasClass('active')) {
                active = true;
            }
            days.push({
                day,
                active
            });
        });
        return days;
    }

</script>

<div class="modal fade" id="modal-editTicketWelcomeMessage" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="width: 650px; margin: 0 auto;">
            <form action="/settings/ticketWelcomeMessage" method="post">
                @csrf
                <div class="modal-header custom_modal_header">
                <p class="h3" id="exampleModalLabel">Willkomensnachricht bearbeiten</p>
                </div>
                <div class="modal-body">
                    <div class="form-group mt-2">
                        <label for="exampleInputEmail1" class="mt-3">Discord Willkomensnachricht</label>
                        <textarea name="message" id="" cols="30" rows="10" class="form-control" style="widht: 100%;">{{ $supportConfig['discord_ticket_welcome_message'] }}</textarea>
                        <p href="" class="settingsSubHead mt-3"><span>Um den Discord-Namen (Discord-Ping) im Text anzugeben, {{ '@user' }} verwenden</span></p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-block button-primary-orange w-50">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-deleteProject" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="width: 650px; margin: 0 auto;">
            <div class="modal-header custom_modal_header">
                <p class="h3" id="exampleModalLabel">Projekt löschen</p>
            </div>
            <div class="modal-body">
                <div class="text-muted">Bist du sicher dass du das Projekt <b>{{ $currentProject->name }}</b> löschen willst?</div>
                <div class="text-muted mt-1" style="color: #dc3545!important;">Alle Daten werden gelöscht und sind <b>nicht</b> wiederherstellbar</div>
                <div class="row">
                    <div class="col-6 mt-3"> 
                        <div class="form-group">
                            <label>Zum bestätigen "deleteProjekt"</label>
                            <input class="form-control" type="text" oninput="checkDeleteProject()" id="confirmDeleteProjekt">
                        </div>
                    </div>
                </div>
            </div>
            <form action="/project/delete" method="post">
                @csrf
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-block button-primary-red w-50" id="showDeleteProject" style="display:none;">Jetzt löschen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-editTicketChannelMessage" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="width: 650px; margin: 0 auto;">
            <form action="/settings/ticketChannelMessage" method="post">
                @csrf
                <div class="modal-header custom_modal_header">
                    <p class="h3" id="exampleModalLabel">Nachricht für Supportchannel bearbeiten</p>
                </div>
                <div class="modal-body">
                    <div class="form-group mt-2">
                        <label for="exampleInputEmail1">Titel</label>
                        <input name="title" type="text" class="form-control" value="{{ $supportConfig['discord_init_title'] }}" maxlength="128">

                        <label for="exampleInputEmail1" class="mt-3">Beschreibung</label>
                        <textarea name="description" id="" cols="30" rows="10" class="form-control" style="widht: 100%;">{{ $supportConfig['discord_init_description'] }}</textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-block button-primary-orange w-50">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(App\Helpers\Subscription::hasActiveSubscription('support'))
<div class="modal fade" id="modal-customSupportCategories" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="/settings/setCustomCategories" method="post">
            @csrf
            <input id="form_setCustomCategories_helper" name="submit_data" type="text" hidden>

            <div class="modal-content" style="width: 650px; margin: 0 auto;">
                <div class="modal-header custom_modal_header">
                    <p class="h3" id="exampleModalLabel">Support Kategorien</p>
                </div>
                <div class="modal-body">
                    <div class="row mt-4">
                        <div class="col-10" style="position:relative;">

                            <div id="ticket_categories_render_conatiner"></div>

                            <!-- Userfriendly Note -->
                            <div class="fillToSaveNote">
                                <img src="/assets/images/svg/fillToSave.svg">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <div type="button" onclick="submitCategories()" class="btn btn-block button-primary-orange w-50">Speichern</div>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@if(App\Helpers\Subscription::hasActiveSubscription('support'))
<div class="modal fade" id="modal-addTextSnippet" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="width: 650px; margin: 0 auto;">
            <form action="/settings/addTextSnippet" method="post">@csrf
                <div class="modal-header custom_modal_header">
                    <p class="h3" id="exampleModalLabel">Erstelle ein Textsnippet</p>
                </div>
                <div class="modal-body">
                    <div class="form-group mt-2">
                        <label for="exampleInputEmail1">Snippet Tag (ohne #)</label>
                        <input name="snippetIdentifier" type="text" class="form-control" placeholder="#supportEnd" maxlength="50" pattern="[a-zA-Z0-9]+" required>

                        <label for="exampleInputEmail1" class="mt-3">Snippet Nachricht</label>
                        <textarea name="snippetMessage" id="" cols="10" rows="5" class="form-control" style="widht: 100%;" placeholder="Vielen Dank für das nette Gespräch. Wenn du weitere Fragen hast, melde dich gerne erneut bei uns im Support." required></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-block button-primary-orange w-50">Erstellen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    function copyLiveChatScriptToClipboard() {
        const appURL = '{{ env('APP_URL') }}';
        const projectLivechatToken = '{{ $project->livechat_token }}';
        var finalText = `<script defer src=\"${appURL}/livechat/${projectLivechatToken}/v1/scriptSupplier\"><\/script>`;
        copyLinkToClipboard(finalText, 'Das Livechat-Skript wurde erfolgreich erstellt und kopiert.');
        $('#livechat_script').val(finalText).show();    
    }
    function copyLinkToClipboard(text, success_text) {
        navigator.clipboard.writeText(text);
        new Notification('success', success_text, '5000')
    }
</script>

<script>

    const existingCategories = JSON.parse('@php echo json_encode($support_categories) @endphp');

    const renderHtml = `
        <div class="supportCategoryItem">
            <div class="form-group col">
                <label>Kategorie Name</label>
                <input onChange="onChangeCategory(this)" type="text" class="form-control" name="name" placeholder="Allgemein">
            </div>
            <div class="form-group col">
                <label>Wo anzeigen</label>
                <select name="usage_location" id="" class="form-control">
                        <option value="" disabled>Wähle einen Ort</option>
                        <option selcted value="all">Überall</option>
                        {{-- <option value="discord">Discord</option> --}}
                </select>
            </div>
        </div>
    `;

    function deleteTextSnippet(id) {
        var deleteForm = document.getElementById("deleteTextSnippet"+id);
        console.log(id);
        console.log(deleteForm);
        deleteForm.submit();
    }

    function editTextSnippet(id) {

    }

    function onChangeCategory(element) {
        if($(element).val().trim() != '') {
            createNewCategory();
        }else {
            removeThisCategory(element);
        }
    }

    function createNewCategory() {

        if($('#ticket_categories_render_conatiner').children().last().find('input[name="name"]').val().trim() == '') return;

        $('#ticket_categories_render_conatiner').append(renderHtml);
    }

    function renderCategories(categories) {

        categories.forEach(category => {
            let customRenderHTML = `
                <div class="supportCategoryItem">
                    <div class="form-group col">
                        <label>Kategorie Name</label>
                        <input onChange="onChangeCategory(this)" type="text" class="form-control" name="name" placeholder="Allgmein" value="${category.name}">
                    </div>
                    <div class="form-group col">
                        <label>Wo anzeigen</label>
                        <select name="usage_location" id="" class="form-control">
                                <option value="" disabled>Wähle einen Ort</option>
                                <option selcted value="all">Überall</option>
                                {{-- <option value="discord">Discord</option> --}}
                        </select>
                    </div>
                </div>
            `;
            $('#ticket_categories_render_conatiner').append(customRenderHTML);
        });
    }

    function createFirstCategory() {
        $('#ticket_categories_render_conatiner').append(renderHtml);
    }

    function removeThisCategory(element) {
        if($('#ticket_categories_render_conatiner').children().length == 1) return;
        $(element).parent().parent().remove();
    }

    function getCategoriesFormData() {
        var categories = [];
        $('#ticket_categories_render_conatiner').children().each(function(index, element) {

            if($(element).find('input[name="name"]').val().trim() == '') return;

            var category = {};
            category.name = $(element).find('input[name="name"]').val();
            category.description = $(element).find('input[name="description"]').val();
            category.location = $(element).find('select[name="usage_location"]').val();
            categories.push(category);
        });

        return categories;
    }   
    
    function submitCategories() {
        $('#form_setCustomCategories_helper').val(JSON.stringify(getCategoriesFormData()));
        $('#modal-customSupportCategories').find('form').submit();
    }

    function deleteAlert($alertElementId) {
        document.getElementById('elementId').value = $alertElementId;
        document.getElementById("deleteAlertElement").submit();
    }

    function startTab() {
        $('#v-pills-basicSettings').tab('show')
    }

    $(() => {
        renderCategories(existingCategories);
        createFirstCategory();
    });

</script>



<!-- ============= -->
<!-- Image Uploads -->
<script>
    function uploadLogo() {
        const client = filestack.init("AhY2QLM5BQViijG7RYx4iz");
        const options = {
             fromSources: ["local_file_system","url"],
             maxSize: 1100000,
             lang: 'de',
             accept: ["image/*"],
             storeTo: {
                location: 's3',
                path: '/hood/'
            },
            transformations: {
                crop: true,
                circle: true,
                rotate: true
            },
            onUploadDone: (res) => saveLogo(res),
        };
        client.picker(options).open();
    }

    function saveLogo(response) {
        let logoUrl = "http://storage.mycraftit.com/" + response.filesUploaded[0].key;
        document.getElementById('logoURL').value = logoUrl;
        document.getElementById("editLogo").submit();
    }

    function uploadLivechatToggleImage() {
        const clientChatBubble = filestack.init("AhY2QLM5BQViijG7RYx4iz");
        const clientChatBubbleOptions = {
             fromSources: ["local_file_system","url"],
             maxSize: 1100000,
             lang: 'de',
             accept: ["image/*"],
             storeTo: {
                location: 's3',
                path: '/hood/'
            },
            transformations: {
                crop: true,
                circle: true,
                rotate: true
            },
            onUploadDone: (res) => saveChatBubbleImage(res),
        };
        clientChatBubble.picker(clientChatBubbleOptions).open();
    }

    function saveChatBubbleImage(response) {
        let imageURL = "http://storage.mycraftit.com/" + response.filesUploaded[0].key;
        document.getElementById('liveChatBubbleImageURL').value = imageURL;
        document.getElementById("editLiveChatBubbleImage").submit();
    }

    function checkDeleteProject() {

        let confirmDeleteButton = document.getElementById('showDeleteProject');
        let checkConfirmInput = document.getElementById('confirmDeleteProjekt').value;
        console.log(checkConfirmInput);
        if(checkConfirmInput == 'deleteProjekt') {
            confirmDeleteButton.style.display = "block";
        }
    }
</script>


<form action="/settings/deleteAlertElement" id="deleteAlertElement" method="post">@csrf
    <input id="elementId" name="elementId" hidden>
</form>

<form action="/settings/editLogo" id="editLogo" method="post">@csrf
    <input id="logoURL" name="logoURL" hidden>
</form>

<form action="/settings/editLiveChatBubbleImage" id="editLiveChatBubbleImage" method="post">@csrf
    <input id="liveChatBubbleImageURL" name="liveChatBubbleImageURL" hidden>
</form>




<style>
.customAddButton {
    color: white;
    height: 48px;
    font-size: 23px;
}

.customAddButton:hover {
    color: white;
    cursor: pointer;
}

.supportCategoryItem {
    display: flex;
    flex-direction: row;
    padding: 15px 0px 0px 0px;
}

.supportCategoryItemText {
    color: white;
    font-size: 22px;
}

.fillToSaveNote {
    position: absolute;
    top: -86px;
    right: -135px;
}
</style>
</body>