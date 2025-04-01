@include('layouts.header')

<style>
    .addGameserverDescriptionText {
        font-weight: 500;
        font-size: 15px;
        line-height: 150%;
        color: #7F8596!important;
        padding-right: 70px;
        margin-top: 30px;
    }

    .gameserverCard {
        background-color: #22252D;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0px 4px 8px -4px rgb(0 0 0 / 25%), inset 0px -1px 1px rgb(0 0 0 / 49%), inset 0px 2px 1px rgb(255 255 255 / 6%);
        border-radius: 12px;
        padding: 32px;
    }
</style>

<!-- No Gameservers Found -->
<div class="page_content">

    <div class="pageHeadline">
        <div class="leftSite">
            <a class="goBack" href="/gameserver"><i class="fa-solid fa-left"></i> Zurück</a>
            <a class="pageTitle">Gameserver anlegen</a>
        </div>
        <div class="rightSite">
        </div>
    </div>

    <form action="/gameserver/createGameserver" method="POST">@csrf
    <div class="row mt-5">
        <div class="col-xl-6">
            <div class="addGameserverDescriptionText">
                <b style="color: #EFEFEF !important;">Gameserver Type</b><br>
                <a>Wir unterstützen nur bestimmte Spiele. Danach benötigen wir je nach Spiel 1-2 Daten von dir damit wir deinen Server Tracken können.</a>
            </div>

            <div class="addGameserverDescriptionText">
                <a>Wir können dir Statistiken zu deinem Server liefern. Dazu gehören Spielerwachstum, Serverstatus und die Erreichbarkeit. Wir arbeiten auch an Ingame Management Möglichkeiten. </a>
            </div>

            <div class="addGameserverDescriptionText">
                <a>Um deinen Gameserver anlegen zu können, benötigt Hood einige Daten von dir. Bitte fülle diese vollständig aus. Solltest du dein Spiel einen Query Port benötigen trage diesen bitte auch ein.</a>
            </div>
            <div class="addGameserverDescriptionText">
                <b style="color: red;">*Alpha - Das Tracking dieses Servertyps kann noch zu Fehlern führen.</b>
            </div>
        </div>
        <div class="col-xl-6 pr-5 pl-5 gameserverCard">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Wähle dein Spiel</label>
                        <select name="gameCode" class="form-control" id="gameCode" onchange="checkGameCode()">
                                <option value="FiveM">GTA5 - FiveM (Alpha)</option>
                                <option value="Minecraft">Minecraft</option>
                                <option value="AltV">GTA5 - AltV</option>
                                <option value="Arma3">Arma3 (Alpha)</option>
                                <option value="Rust">Rust (Alpha)</option>
                                <option value="Ark">Ark (Alpha)</option>
                                <option value="spaceengineers">Spaceengineers (Alpha)</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Server Name</label>
                        <input name="serverName" type="text" class="form-control" value="" >
                    </div>
                </div>
                <div class="col-6" id="ip">
                    <div class="form-group">
                        <label>Server IP !!Bitte keine Domains, nur IPv4 Addressen!!</label>
                        <input name="serverIP" type="text" class="form-control" placeholder="16.23.293.2" value="" >
                    </div>
                </div>
                <div class="col-6" id="port">
                    <div class="form-group">
                        <label>Server Port</label>
                        <input name="serverPort" type="text" class="form-control" value="">
                    </div>
                </div>
                <div class="col-6" id="master_id" style="display:none;">
                    <div class="form-group">
                        <label>AltV Master ID <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-placement="top" title="Deine MasterID hat dieses Format: 9a696cc5b7b486f57f5e3b3ba7ee4b62 "></i></label>
                        <input name="master_id" type="text" class="form-control" value="">
                    </div>
                </div>
                <div class="col-6" id=queryPort>
                    <div class="form-group">
                        <label>Query Port (Optional)</label>
                        <input name="serverQueryPort" type="text" class="form-control" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-5">
        <div class="col-xl-6">
            <div class="addGameserverDescriptionText">
                <b style="color: #EFEFEF !important;">Server Steuerung <b>(Bald verfügbar)</b></b><br>
                <a>Die Steuerungsfunktion ermöglicht es dir, deinen Server dierekt in Hood GameConnect Starten und Stoppen zu können.</a>
            </div>
        </div>
        <div class="col-xl-6 pr-5 pl-5 gameserverCard">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Wie lautet dein RCON Passwort?</label>
                        <input name="serverRcon" type="text" class="form-control" value="" >
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-6">
            <div class="addGameserverDescriptionText">
                <b style="color: #EFEFEF !important;">Server Monitoring <b>(Bald verfügbar)</b></b><br>
                <a>Sollen wir deinen Server Überwachen? Wir benachrichtigen dich im Falle eines Aufalls oder anderen Störungen die wir feststellen. 
                Die Benachrichtigungen kannst du wie gewohnt in den "Settings->Alertsystem" konfigurieren und dich so per E-Mail, Handy Push und Discord benachrichtigen lassen.</a>
            </div>

            <div class="addGameserverDescriptionText">
                <a></a>
            </div>
        </div>
        <div class="col-xl-6 pr-5 pl-5 gameserverCard">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Sollen wir die Uptime überwachen?</label>
                        <select name="discord_channel" id="" class="form-control" disabled>
                                <option value="">Nein, ich benötige keine Überwachung</option>
                                <option value="">Ja, ich benötige eine Überwachung</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="settingsSpacer mt-5"></div>
        <div class="d-flex justify-content-center mb-3">
          <button style="color: #FCFCFC; width: 25%;" type="submit" class="btn button-primary-orange">Gameserver anlegen</button>
        </div>
        <div class="d-flex justify-content-center">
            <small><a href="#">Benötigst du Unterstützung?</a></small>
        </div>
    </div>
    </form>


    <script>
    function checkGameCode() {
        var gameTypeSelect = document.getElementById("gameCode");
        var ip = document.getElementById("ip");
        var port = document.getElementById("port");
        var queryPort = document.getElementById("queryPort");
        var master_id = document.getElementById("master_id");

        var gameType = gameTypeSelect.options[gameTypeSelect.selectedIndex].value;
        if(gameType === "AltV") {
            ip.style.display = "none";
            port.style.display = "none";
            queryPort.style.display = "none";
            master_id.style.display = "block";
        } else {
            ip.style.display = "block";
            port.style.display = "block";
            queryPort.style.display = "block";
            master_id.style.display = "none";
        }
    }
    </script>