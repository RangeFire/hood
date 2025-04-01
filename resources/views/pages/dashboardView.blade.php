@include('layouts.header')
<div class="page_content">
    <!--- Page headline -->
    <div class="row">
        <div class="col-6 page_title_area">
            <h3 class="h2">Dashboard</h3>
            <h6 class="page_subtitle">Zahlen sagen mehr als Worte</h6>
        </div>
    </div>


    <!-- Empty Space -->
    <div style="height: 48px;"></div>

    <!-- Page statistics -->
    <div class="row mb-4">
        <!-- Statistic #1 -->
        <div class="col-xl-3">
            <div class="card card-inline statisticCard">
                <div class="card-content">
                    <div class="inline-image">
                        <i class="fa-solid fa-ticket-simple customIcon"></i>
                    </div>
                    <div class="inline-text">
                        <a class="paragraphSmallSlim">Offene Tickets</a><br>
                        <a class="h4">{{ (new App\Http\Controllers\TicketController)->countOpenTicketsInternal() >= 0 ? (new App\Http\Controllers\TicketController)->countOpenTicketsInternal() : 'Keine Tickets' }}</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistic #2 -->
        <div class="col-xl-3">
            <div class="card card-inline statisticCard">
                <div class="card-content">
                    <div class="inline-image">
                        <i class="fa-brands fa-discord customIcon"></i>
                    </div>
                    <div class="inline-text">
                        <a class="paragraphSmallSlim">Discord Nutzer</a><br>
                        <a class="h4">{{ $discordUsers ?: '0' }}</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Custom Statistic #4 -->
        <div class="col-xl-3">
            <div class="card card-inline statisticCard">
                <div class="card-content">
                    <div class="inline-image">
                        <i class="fa-solid fa-people-group customIcon"></i>
                    </div>
                    <div class="inline-text">
                        <a class="paragraphSmallSlim" data-toggle="tooltip" data-placement="top" data-title="folgt mit Update">Spieler gesamt</a><br>
                        <a class="h4">N/A</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Custom Statistic #3 -->
        <div class="col-xl-3">
            <div class="card card-inline statisticCard">
                <div class="card-content">
                    <div class="inline-image">
                        <i class="fa-solid fa-sack-dollar customIcon"></i>
                    </div>
                    <div class="inline-text">
                        <a class="paragraphSmallSlim" data-toggle="tooltip" data-placement="top" data-title="folgt mit Update">Shop Umsatz</a><br>
                        <a class="h4">N/A</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-auto">
            <div class="card card-inline statisticCard">
                    <div style="padding:28px 28px 28px 28px;">
                        <a class="h4">Hood in 3 Minuten erlärt</a>
                        <div class="player mt-4">
                            <iframe style="border-radius: 12px;" width="auto" height="auto" src="https://www.youtube.com/embed/GRU-_EFd5EE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                   </div>
            </div>
        </div>
    </div>    

    <div class="d-flex justify-content-center" style="margin-top: 64px;">
        <img src="/assets/images/sectionwip.png" style="width: 32%; height: auto;">
    </div>

    <div style="height: 32px;"></div>

</div>