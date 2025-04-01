@include('layouts.header')
@php
    $timezone = date_default_timezone_get();

    function ticketType(string $type) {

        if($type == 'discord') return 'Discord';
        if($type == 'livechat') return 'Web-Livechat';

    }

@endphp

<div class="lightgrey-header-big"></div>
<div class="page_content">

    <!--- Page headline -->
    <div class="row">
        <div class="col page_title_area">
            <h3 class="page_headline">Support Tickets</h3>
            <h6 class="page_subtitle">Web und Discord Support in einem</h6>
        </div>
        <div class="col-7 text-right">
        </div>
    </div>

    <div class="row" style="margin-top: 74px;">
        <div class="col-12">
            <div class="top-search-bar card mb-3 p-0" style="width: 800px">
                <div class="card-body p-0 m-0">
                    <div class="search-elements d-flex align-items-center">

                        <!-- Tab links -->
                        <div class="tab d-flex w-100" style="justify-content: space-evenly;">
                            <button class="tablinks" onclick="openTab(event, 'unassigned')" id="defaultTab">
                                Offene Tickets
                                @if(count($open_tickets) > 0)
                                    <span class="badge badge-danger">{{ count($open_tickets) }}</span></button>
                                @endif
                            <button class="tablinks" onclick="openTab(event, 'selfassigned')">
                                Mir zugewiesen
                                @if(count($assigned_tickets) > 0)
                                    <span class="badge badge-danger">{{ count($assigned_tickets) }}</span></button>
                                @endif
                            </button>
                            <button class="tablinks" onclick="openTab(event, 'all')">
                                Alle Tickets
                                @if(count($all_tickets) > 0)
                                    <span class="badge badge-primary">{{ count($all_tickets) }}</span>
                                @endif
                            </button>
                            <button class="tablinks" onclick="openTab(event, 'archived')">
                                Archiviert
                                @if(count($closed_tickets) > 0)
                                    <span class="badge badge-primary">{{ count($closed_tickets) }}</span>
                                @endif
                            </button>
                        </div>

                    </div>
                        <div class="search-spacer"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row ml-1">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-12">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="tickets-container">

                        <!-- Tab content -->
                        <div class="row">

                            <div id="unassigned" class="tabcontent">
                            @if (!$open_tickets)
                                @include('pages.comp.errorEmpty')
                            @else
                            @php
                                $custom_render_tickets = $open_tickets;
                             @endphp
                                @include('pages.comp.tickets-render')
                            @endif
                            </div>
    
                            <div id="selfassigned" class="tabcontent">
                            @if (!$assigned_tickets)
                                @include('pages.comp.errorEmpty')
                            @else
                            @php
                                $custom_render_tickets = $assigned_tickets;
                             @endphp
                                @include('pages.comp.tickets-render')
                            @endif
                            </div>
    
                            <div id="all" class="tabcontent">
                            @if (!$all_tickets)
                                @include('pages.comp.errorEmpty')
                            @else
                            @php
                                $custom_render_tickets = $all_tickets;
                             @endphp
                                @include('pages.comp.tickets-render')
                            @endif
                            </div>
    
                            <div id="archived" class="tabcontent">
                            @if (!$closed_tickets)
                                @include('pages.comp.errorEmpty')
                            @else
                            @php
                                $custom_render_tickets = $closed_tickets;
                             @endphp
                                @include('pages.comp.tickets-render')
                            @endif
                            </div>

                        </div>

                    </div>
                    <div style="height: 50px;"></div>
                </div>
            </div>
        </div>

        <style>
            
        </style>

        <style>
            .badge-danger {
                background-color: #FF5630;
            }

            .ticketDiv {
                padding: 40px;
                background-color: #FFFF;
                border-radius: 12px;
                cursor: pointer;
            }

            .tickets-container{
                width: 100%;
                min-height: 50vh;
                max-height: 75vh;
            }

            /* width */
            ::-webkit-scrollbar {
                width: 3px;
                border-radius: 20px;
            }

            /* Track */
            ::-webkit-scrollbar-track {
                background: transparent !important;;
            }

            /* Handle */
            ::-webkit-scrollbar-thumb {
                background: #bec5cb;
            }

            /* Handle on hover */
            ::-webkit-scrollbar-thumb:hover {
                background: #555;
            }

            .tab {
                 overflow: hidden;
                 margin: 0px 20px 0px 30px
            }

            /* Style the buttons that are used to open the tab content */
            .tab button {
                background-color: inherit;
                float: left;
                border: none;
                outline: none;
                cursor: pointer;
                padding: 14px 16px;
                color: white;
            }

            /* Change background color of buttons on hover */
            .tab button:hover {
                 
            }

            /* Create an active/current tablink class */
            .tab button.active {
                  border-bottom: 3px solid #377DFF;
            }

            /* Style the tab content */
            .tabcontent {
                width: 100%;
                display: none;
                /* margin-left: 300px; */
                padding: 0px 86px 0px 0px;
            }
        </style>
        <script>
                document.getElementById("defaultTab").click();

                function openTab(evt, cityName) {
                // Declare all variables
                var i, tabcontent, tablinks;

                // Get all elements with class="tabcontent" and hide them
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Get all elements with class="tablinks" and remove the class "active"
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }

                // Show the current tab, and add an "active" class to the button that opened the tab
                document.getElementById(cityName).style.display = "block";
                evt.currentTarget.className += " active";
                }
        </script>
    </div>
</div>