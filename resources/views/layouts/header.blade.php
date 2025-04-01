@php
    use App\Helpers\Subscription;
    
    $userProjects = (new App\Models\UserProject)->getUserProjects(App\Helpers\Auth::$user->id);
    if(session('activeProject') == 'empty') {
        header("Location: /start");
        exit();
    }

    $userData = (new App\Models\User)->find(App\Helpers\Auth::$user->id);
    $isOwner = \App\Helpers\Auth::user('isOwner');

    $permissions = [
        'manage_users' => App\Helpers\Auth::hasPermission('manage_users'),
        'settings' => App\Helpers\Auth::hasPermission('settings'),
        'support' => App\Helpers\Auth::hasPermission('support'),
        'ingame_integration' => App\Helpers\Auth::hasPermission('ingame_integration'),
        'monitoring' => App\Helpers\Auth::hasPermission('monitoring'),
        'surveys' => App\Helpers\Auth::hasPermission('surveys'),
        'wishes' => App\Helpers\Auth::hasPermission('wishes'),
        'bugreports' => App\Helpers\Auth::hasPermission('bugreports'),
    ];
@endphp

<!DOCTYPE html>
<html>
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="CraftIT© GmbH">
    <title>Hood - Community Management</title>
    <link rel="apple-touch-icon" href="/assets/images/logo/logoIconTransparent.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/logo/logoIconTransparent.png">
    <!-- =========================================================================================================== -->
    <script defer src="https://wehood.app/livechat/d04d3b003aa14d7586873cdb8816da43/v1/scriptSupplier"></script>
    <!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
    <!-- BEGINN: CSS-->
    <script src="https://kit.fontawesome.com/1f26011b9e.js" crossorigin="anonymous"></script>
    {{-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="//static.filestackapi.com/filestack-js/3.x.x/filestack.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    <!-- BEGIN: MAIN-->
    <link rel="stylesheet" href="/assets/css/app.css?v={{ random_int(1, 999) }}">
    <script src="/assets/js/app.js?v={{ random_int(1, 999) }}"></script>

    @include('layouts.notifications')

    <!-- Lucky Orange -->
    <script async defer src="https://tools.luckyorange.com/core/lo.js?site-id=cfd5f4ca"></script>
 
    <!-- Weglot Übersetzung -->
    <script type="text/javascript" src="https://cdn.weglot.com/weglot.min.js"></script>
    <script>
        Weglot.initialize({
            api_key: 'wg_abc78c01ac229587ec83efe0877489342'
        });
    </script>


</head>

<style>
    .table-hover tbody tr:hover {
        color: #212529!important;
    }

    .btn.focus, .btn:focus {
        outline: 0;
        box-shadow: 0 0 0 .2rem
        rgba(0,123,255,.25);
    }

    .dropdown-toggle::after {
        content: none!important;
    }    

    .navbarTopLeft {
        position: absolute;
        top: 20px;
        left: 206px;
        width: auto;
        z-index: 2000;
    }  

    .navbarTopRight {
        position: absolute;
        top: 20px;
        right: 90px;
        width: auto;
        z-index: 2000;
    }

    .dropdownMenuTopNav {
        padding: 16px 24px 16px 24px;
        width: 270px;
        background-color: #22252D;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0px 4px 8px -4px rgb(0 0 0 / 25%), inset 0px -1px 1px rgb(0 0 0 / 49%), inset 0px 2px 1px rgb(255 255 255 / 6%);
        border-radius: 12px;
    }

    .dropdownMenuTopNavProfile {
        padding: 16px 24px 16px 24px;
        width: 199px;
        transform: translate3d(-120px, 0px, 0px);
        background-color: #22252D;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0px 4px 8px -4px rgb(0 0 0 / 25%), inset 0px -1px 1px rgb(0 0 0 / 49%), inset 0px 2px 1px rgb(255 255 255 / 6%);
        border-radius: 12px;
    }

    .customLink {
        display: flex;
        align-items: center;
        width: 100%;
        height: 48px;
        padding: 0 12px;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.6;
        transition: all .2s;
    }

    .customLink:last-child {
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #EFEFEF;
    }

    .customLink a {
        color: #EEF1F5!important;
    }

    .addProjectLink {
        display: flex;
        align-items: center;
        width: 100%;
        height: 48px;
        padding: 0 12px;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.6;
        transition: all .2s;
    }

    .profilMenuItem {
        display: flex;
        align-items: center;
        width: 100%;
        height: 48px;
        padding: 0 12px;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.6;
        transition: all .2s;
    }

    .profilMenuItem a:hover {
        color: #377DFF;
    }

    .profilMenuItem a {
        color: #EEF1F5!important;
        cursor: pointer;
    }

    .fsp-picker__brand-container {
    display: none;
    }
</style>

<script>
	$(document).ready(function() {
        $(".nav-item").click(function () {
            let text = $(this).find('#nav-text').text();
            localStorage.setItem('selectedTab', text);
			$('.sidebar__item').find('#nav-text').each(function() {
                if($(this).text() !== text) {
                    $(this).parent().removeClass('active');
                }
            })
        });
		
		let selectedTab = localStorage.getItem('selectedTab');
		
		if (selectedTab == null) {
			localStorage.setItem('selectedTab', 'Dashboard');
			window.location.href = '<?php '' ?>';
			return false;
		}

        $('.nav-item').find('#nav-text').each(function() {
			if (selectedTab == null && $(this).text() == 'Dashboard') {
				localStorage.setItem('selectedTab', $(this).text());
				$(this).closest("li").addClass('active');
			}

            if($(this).text() === selectedTab) {
				$(this).closest("li").addClass('active');
			}
        });
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    function toggleMobileNav() {
        var x = document.getElementById("nav-side-menu");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
    <body style="background-color: #F7F7F8;overflow-x: hidden;">

        {{-- <div class="navbarTopLeft">
            @include('layouts.trialRuntimeInfo')
        </div>   --}}

        <div class="bnav_mobile_header">
            <div class="bnav_mobile_header_block">
                <ul>
                    <li><a href="#"> <img src="/assets/images/navigation/logo.svg"></a> </li>
                    <li><a href="javascript:void(0);" class="toggle-swich"> <img src="/assets/images/navigation/bar.svg"></a> </li>
                </ul>
            </div>
        </div>


        <div class="bnav_header">
            <div class="bnav_logo_box_mobile">
                <ul>
                    <li><a href="#"> <img src="/assets/images/navigation/logo.svg"></a> </li>
                    <li><a href="javascript:void(0);" class="close-sidebar"> <img src="/assets/images/navigation/close.svg"></a> </li>
                </ul>
            </div>

            <div class="bnav_logo_box bnav_logo_box d-flex justify-content-center">
                <img src="/assets/images/navigation/small-logo.svg" alt="" />
            </div>

            <div class="banv_navbar">
                <ul>
                    <li class="brd">
                        <a href="/dashboard" class="active">
                            <span>
                                <img src="/assets/images/navigation/dash-icon1.svg" alt="" class="banv_dash-icon" />
                                <img src="/assets/images/navigation/dash-icon-active1.svg" alt="" class="banv_dash-icon-active" />
                            </span>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="/tickets">
                            <span><span class="custom_support_badge" id="open_tickets_counter">0</span>
                                <img src="/assets/images/navigation/dash-icon2.svg" alt="" class="banv_dash-icon" />
                                <img src="/assets/images/navigation/dash-icon-active2.svg" alt="" class="banv_dash-icon-active" />
                            </span>
                            Support
                        </a>
                    </li>
                    @if($permissions['monitoring'])
                    <li>
                        <a href="/monitoring">
                            <span>
                                <img src="/assets/images/navigation/dash-icon3.svg" alt="" class="banv_dash-icon" />
                                <img src="/assets/images/navigation/dash-icon-active3.svg" alt="" class="banv_dash-icon-active" />
                            </span>
                            Monitoring
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="/gameserver">
                            <span>
                                <img src="/assets/images/navigation/dash-icon4.svg" alt="" class="banv_dash-icon" />
                                <img src="/assets/images/navigation/dash-icon-active4.svg" alt="" class="banv_dash-icon-active" />
                            </span>
                            Connect
                        </a>
                    </li>
                    <li class="brd">
                        <a href="/bugreports">
                            <span>
                                <img src="/assets/images/navigation/dash-icon5.svg" alt="" class="banv_dash-icon" />
                                <img src="/assets/images/navigation/dash-icon-active5.svg" alt="" class="banv_dash-icon-active" />
                            </span>
                            Core
                        </a>
                    </li>
                    @if($permissions['manage_users'])
                    <li>
                        <a href="/users">
                            <span>
                                <img src="/assets/images/navigation/dash-icon6.svg" alt="" class="banv_dash-icon" />
                                <img src="/assets/images/navigation/dash-icon-active6.svg" alt="" class="banv_dash-icon-active" />
                            </span>
                            Benutzer
                        </a>
                    </li>
                    @endif
                    @if($permissions['settings'])
                    <li>
                        <a href="/settings">
                            <span>
                                <img src="/assets/images/navigation/dash-icon7.svg" alt="" class="banv_dash-icon" />
                                <img src="/assets/images/navigation/dash-icon-active7.svg" alt="" class="banv_dash-icon-active" />
                            </span>
                            Einstellungen
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            <div class="bnav_dropdown">
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-img"><!--<span class="badge">23</span>--> <img src="{{ $userData->avatar ? $userData->avatar : '/assets/images/roboter.png' }}" alt="" /></div>
                        <div class="drop-info">
                            <h4>{{$userData->username}}</h4>
                            <h6></h6>
                        </div>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div class="bnav_info_links">
                            <li><a class="dropdown-item" data-toggle="modal" data-target="#profilSettingsModal"> <img src="/assets/images/navigation/drop-icon1.svg" alt="" /> Profil {{Session::get('activeProject')}}</a></li>
                            <li><a class="dropdown-item" href="/products"> <i style="color:#007bff" class="fa-solid fa-rectangle-pro mr-3"></i> Hood One</a></li>
                        </div>
                        <hr />
                        <div class="bnav_info_links">
                            @foreach ($userProjects as $userProject) 
                                @if($userData->favorite_project == $userProject->project_id)
                                    {{-- <li class="customLink"><i class="fa-solid fa-star activeProjectStar pr-1"></i> <a tabindex="-1" href="/project/change/{{ $userProject->project_id }}">{{ $userProject->project->name }}</a></li> --}}
                                    <li><a class="dropdown-item" href="/project/change/{{ $userProject->project_id }}"> <img src="/assets/images/navigation/drop-icon3.svg" style="margin-right: 7px;" /> {{ $userProject->project->name }}</a></li>
                                @else
                                    {{-- <li class="customLink" onclick="setFavoriteUserProject({{$userProject->project_id}}, {{$userData->id}})"><i class="fa-regular fa-star favoriteProjectStar pr-1"></i> <a tabindex="-1" href="/project/change/{{ $userProject->project_id }}">{{ $userProject->project->name }}</a></li> --}}
                                    <li style="display:flex;"><a class="dropdown-item" style="padding-right: 0px;width:auto;" onclick="setFavoriteUserProject({{$userProject->project_id}}, {{$userData->id}})"><img class="favoriteProjectStarUnfill" src="/assets/images/navigation/drop-icon4.svg"/><a class="dropdown-item" style="padding-left: 0px;" href="/project/change/{{ $userProject->project_id }}">{{ $userProject->project->name }} {{$userProject->project_id}}</a></a></li>
                                @endif
                            @endforeach 
                            <li><a class="dropdown-item active" href="/start" style="color: #2A85FF;"> <img src="/assets/images/navigation/drop-icon5.svg"/> Projekt erstellen</a></li>
                        </div>
                        <hr />
                        <div class="bnav_info_links">
                            <li><a class="dropdown-item" href="/logout"> <img src="/assets/images/navigation/drop-icon6.svg" alt="" /> Log out</a></li>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bnav_bottom_info">
                <img src="/assets/images/navigation/bottom-logo.png" alt="">
                <h4>“Deine Community <br> in deinen Händen.“</h4>
            </div>

        </div>

        <script type="text/javascript">
            /* **** Add Remove Class **** */
            $(".toggle-swich").on("click", function () {
                $(".bnav_header").toggleClass("show-sidebar");
                $("body").toggleClass("show-sidebar");
            });

            $(".close-sidebar").on("click", function () {
                $(".bnav_header").removeClass("show-sidebar");
                $("body").removeClass("show-sidebar");
            });
            /* **** End Add Remove Class **** */
        </script>
        

    </body>
</html>

<!-- Profil Settings -->
<div class="modal fade" id="profilSettingsModal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content custom_modal">
            <div class="modal-header custom_modal_header">
                <p class="h3" id="exampleModalLabel">Nutzerprofil</p>
            </div>
            <div class="modal-body">
                <form action="/user/editProfile" method="post">
                @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Dein Name (wird in Tickets angezeigt)</label>
                            <input name="fullname" type="text" class="form-control" value="{{ $userData->fullname ? $userData->fullname : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label>E-Mail Adresse</label>
                            <input  name="email" type="text" class="form-control" value="{{ $userData->email ? $userData->email : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Benutzername</label>
                            <input  name="username" type="text" class="form-control" value="{{ $userData->username ? $userData->username : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Neues Passwort</label>
                            <input name="password" type="password" value="" class="form-control">
                        </div>
                        <div class="row d-flex align-items-center mt-3">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <img class="brandLogo" src="{{ $userData->avatar ? $userData->avatar : '/assets/images/roboter.png' }}">
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="form-group">
                                    <button type="button" class="btn btn-block btn-primary mt-0" onclick="uploadProfilImage()">Profilbild ändern</button>
                                </div>
                            </div>
                            <input id="profileImageURL" name="profileImageURL" hidden>
                        </div>
                    </div>
            </div>
            <div class="modal-footer custom_modal_footer justify-content-center">
                <button style="width: 46%;" type="button" class="btn button-primary"
                        data-dismiss="modal">Abbrechen
                </button>
                <button style="color: #FCFCFC; width: 46%;" type="submit"
                        class="btn button-primary-orange">Speichern
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Project Modal -->
<div class="modal fade" id="modal-noSubscription" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="width: 650px; margin: 0 auto;">
            <div class="modal-header custom_modal_header">
                <p class="h3" id="exampleModalLabel">Projekt löschen</p>
            </div>
            <div class="modal-body">
                <div class="text-muted">Bist du sicher dass du das Projekt <b></b> löschen willst?</div>
                <div class="text-muted" style="color: #dc3545!important;">Alle Daten werden gelöscht und sind nicht wiederherstellbar</div>
            </div>
            <form action="/project/delete" method="post">
                @csrf
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-block button-primary-red w-50">Jetzt löschen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Set favorite user Project -->
<form action="/user/setFavoriteProject" id="setFavoriteProject_form" method="post">@csrf
        <input name="favoriteProjectId" id="favoriteProjectId" hidden>
        <input name="userId" id="userId" hidden>
</form>

<!-- Audio Notification -->
<audio style="display:none;" id="notificationAudio"><source src="/assets/sounds/notifications/christmas.mp3"></audio>

@if (session('notificationSound'))
    <script>triggerSoundAlert()</script>
@endif
@include('layouts.footer')
