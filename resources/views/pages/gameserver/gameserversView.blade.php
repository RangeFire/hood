@include('layouts.header')
@php 
    use App\Http\Controllers\GameserverController;
    use App\Services\GameserverService;
@endphp

<style>

</style>
<!-- No Gameservers Found -->
<div class="page_content">
            <div class="container">
                <div class="error-bx d-flex justify-content-center">
                    <h6><img src="images/alert-ic.svg" alt="">Dies ist eine frühe Alpha Testversion. Es kann noch vermehrt zu Fehlern kommen.</h6>
                </div>
            </div>

        <!--- Page headline -->
        <div class="row">
            <div class="col-xl-6 page_title_area">
                <h3 class="h2">GameConnect</h3>
                <h6 class="page_subtitle copy_link">Verbinde deine Gameserver mit Hood</h6>
            </div>
            <div class="col-xl-6 text-right d-flex justify-content-end align-items-center">
                <a class="btn button-primary-orange" href="/gameserver/addGameserver">
                    Hinzufügen
                </a>
            </div>
        </div>
        
    <div class="row">
        @foreach ($gameserverData as $gameserver)
        @php
            $gameserverData = GameserverController::getGameserver($gameserver);
            $gameserverData = json_encode($gameserverData, true);
            $data = json_decode($gameserverData, true);
            if($data[0] == null) { exit(); }
            $serverIP = $data[0]["connectionData"]["ip"].":".$data[0]["connectionData"]["port"];
            $GameserverLiveData = GameserverService::getGameserverLiveData($data);
        @endphp
            <div class="col-xl-4 mr-1 mt-4 gameConnect_serverCard" onclick="location.href='/gameserver/{{$gameserver}}/details';">
                <div class="gameConnect_Card customHover" style="background-image: url(/assets/images/gradientBackground.png);">
                    <div style="display: grid;">
                        <a class="gameConnect_labelBig" style="font-size: 22px;">{{$data[0]["serverData"]["name"]}}</a>
                        <a class="gameConnect_labelSmall mt-2">{{$data[0]["connectionData"]["gameType"]}}</a>
                        <a class="gameConnect_label mt-3">Live Spieler</a>
                        <a class="gameConnect_labelBig mt-2" style="font-size: 26px;">{{$GameserverLiveData[$serverIP]["gq_numplayers"]}}</a>
                    </div>
                    <div class="">
                        <img style="max-width: 120px;" src="/assets/images/gameconnect/gameLogos/{{$data[0]["connectionData"]["gameType"]}}.png">
                    </div>
                </div>
           </div>
        @endforeach 
    </div>
</div>
