@include('layouts.header')
@php
    $gameserverData = json_encode($gameserverData, true);
    $data = json_decode($gameserverData, true);
    $gameserverWeekData = json_encode($gameserverWeekData, true);
    $serverIP = $data[0]["connectionData"]["ip"].":".$data[0]["connectionData"]["port"];
@endphp
<!-- No Gameservers Found -->
<div class="page_content">

    <div class="gameconnect_headline">
        <div>
            <a class="servername">{{$data[0]["serverData"]["name"]}}</a>
        </div>
        <div class="mt-2">
            <a class="gameconnect_headlineItem"><i class="fa-solid fa-copy"></i> {{$GameserverLiveData[$serverIP]["gq_address"].":".$GameserverLiveData[$serverIP]["gq_port_client"]}}</a>
            <a class="gameconnect_headlineItem"><i class="fa-solid fa-people-robbery"></i> {{$GameserverLiveData[$serverIP]["gq_numplayers"]}} / {{$GameserverLiveData[$serverIP]["gq_maxplayers"]}} Spieler</a>
        </div>
    </div>

    <div class="gameConnect_content">
        <div class="gameConnect_left mt-4">
            <div class="row">
                <div class="col-xl-5 mr-4 mt-2 p-0">
                    <div class="gameConnect_Card" style="background-image: url(/assets/images/gradientBackground.png);">
                        <div style="display: grid;">
                            <a class="gameConnect_label">Live Spieler <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-placement="top" title="Live Stand alle 30 Sekunden"></i></a>
                            <a class="gameConnect_headline mt-2">{{$GameserverLiveData[$serverIP]["gq_numplayers"]}}</a>
                            {{-- <a class="gameConnect_statisticUp mt-2"><span><i class="fa-solid fa-up"></i> N/A%</span> diese Woche</a> --}}
                        </div>
                        <div class="">
                            <img style="max-width: 120px;" src="/assets/images/gameconnect/gameLogos/{{$data[0]["connectionData"]["gameType"]}}.png">
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 mr-4 mt-2 p-0">
                    <div class="gameConnect_Card">
                        <div style="display: grid;">
                            <a class="gameConnect_label">Spieler Tag</a>
                            <a class="gameConnect_headline mt-2" id="avaragePlayerDay">N/A Ø</a>
                            <a class="gameConnect_statisticUp mt-2"> </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 mr-4 mt-2 p-0">
                    <div class="gameConnect_Card">
                        <div style="display: grid;">
                            <a class="gameConnect_label">Spieler Woche</a>
                            <a class="gameConnect_headline mt-2" id="avaragePlayerWek">N/A Ø</a>
                            <a class="gameConnect_statisticUp mt-2"></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3 mr-4">
                <div class="col-12 p-0">
                    <div class="gameConnect_Card" style="display: block;">
                        <div style="display: grid;">
                            <a class="gameConnect_label">Spieler Tagesverlauf</a>
                            <a class="gameConnect_labelBig mt-2">{{date("d.m.Y")}}</a>
                            <div id="chartDay"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3 mr-4">
                <div class="col-12 p-0">
                    <div class="gameConnect_Card" style="display: block;">
                        <div style="display: grid;">
                            <a class="gameConnect_label">7 Tage Spieler Wachstum</a>
                            <a class="gameConnect_labelBig mt-2">{{date("d.m.Y", strtotime("-6 days")) . " - " . date("d.m.Y", strtotime("0 days"))}}</a>
                            <div id="chartWeek"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="gameConnect_right mt-4">
            <div class="row">
                <div class="col-xl-12 mt-2">
                    <div class="gameConnect_sidebar">
                        <div style="display: grid;">
                            <a class="gameConnect_label mt-3">Derzeitiger Status</a>
                            <a class="gameConnect_headline mt-2" style="font-size:32px;">Alles in Ordnung</a>
                            <a class="gameConnect_statisticUp mt-3"></a>
                        </div>

                        <a class="gameConnect_labelBig mt-3">Aktivitätslog 24 Stunden</a>

                        <div class="row mt-4 mb-3">
                            <div class="col-2">
                                <img style="max-width: 60px;" src="/assets/images/gameConnect/okay.png">
                            </div>
                            <div class="col-9">
                                <a class="gameConnect_labelWhite mt-3">Alles Sauber</a><br>
                                <a class="gameConnect_labelSmall mt-3">Hood konnte keine Probleme mit deinem Server feststellen.</a><br>
                                <small class="gameConnect_small">vor 4 Stunden</small>
                            </div>
                        </div>
                        <div class="row mt-4 mb-3">
                            <div class="col-2">
                                <img style="max-width: 60px;" src="/assets/images/gameConnect/okay.png">
                            </div>
                            <div class="col-9">
                                <a class="gameConnect_labelWhite mt-3">Alles Sauber</a><br>
                                <a class="gameConnect_labelSmall mt-3">Hood konnte keine Probleme mit deinem Server feststellen.</a><br>
                                <small class="gameConnect_small">vor 6 Stunden</small>
                            </div>
                        </div>
                        <div class="row mt-4 mb-3">
                            <div class="col-2">
                                <img style="max-width: 60px;" src="/assets/images/gameConnect/error.png">
                            </div>
                            <div class="col-9">
                                <a class="gameConnect_labelWhite mt-3">Lange Ladezeiten</a><br>
                                <a class="gameConnect_labelSmall mt-3">Hood hat ein Problem mit den Ladezeiten deines Servers festgestellt..</a><br>
                                <small class="gameConnect_small">vor 12 Stunden</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

</div>
<script>
const gameserverDayStatistic = JSON.parse(<?php echo $gameserverDayData ?>); 
/* Convert php arrays to usable apexChart Array Formats */
let playerFull = 0;
var playerValues = gameserverDayStatistic[0].map(function(obj) {
    if(obj.player != "Error") {
        playerFull += obj.player;
    }
    return obj.player;
});
var timeValues = gameserverDayStatistic[0].map(function(obj) {
    return obj.date + " Uhr";
});
document.getElementById("avaragePlayerDay").innerHTML = Math.round(playerFull/24) + ' Ø';

var optionsDay = {
    series: [{
    name: 'Spieler',
    data: playerValues
}],
    chart: {
    height: 350,
    type: 'bar',
    toolbar: {
    show: false,
    },
},
plotOptions: {
    bar: {
    borderRadius: 10,
    dataLabels: {
        position: 'top', // top, center, bottom
    },
    }
},
dataLabels: {
    enabled: true,
    formatter: function (val) {
    return val + "";
    },
    offsetY: -20,
    style: {
    fontSize: '12px',
    colors: ["#EFEFEF"]
    }
},

xaxis: {
    //categories: ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00" , "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"],
    categories: timeValues,
    position: 'bottom',
    axisBorder: {
    show: false
    },
    axisTicks: {
    show: false
    },
    crosshairs: {
    fill: {
        type: 'gradient',
        gradient: {
        colorFrom: '#D8E3F0',
        colorTo: '#BED1E6',
        stops: [0, 100],
        opacityFrom: 0.4,
        opacityTo: 0.5,
        }
    }
    },
    tooltip: {
    enabled: true,
    }
},
yaxis: {
    axisBorder: {
    show: false
    },
    axisTicks: {
    show: false,
    },
    labels: {
    show: false,
    formatter: function (val) {
        return val + "";
    }
    }
}
};

const gameserverWeekStatistic = JSON.parse(<?php echo $gameserverWeekData ?>); 
let reversedArray = gameserverWeekStatistic.slice().reverse();
var avaragePlayerWeek = 0;
var timeValuesWeek = reversedArray.map(function(objs) {
    avaragePlayerWeek += objs.avaragePlayer;
    return objs.date;
});

var playerValuesWeek = reversedArray.map(function(objs) {
    return objs.playerHigh;
});

document.getElementById("avaragePlayerWek").innerHTML = Math.round(avaragePlayerWeek/7) + ' Ø';

var optionsWeek = {
  chart: {
    type: 'line',
    height: '200px',
    toolbar: {
    show: false,
    },
  },
  tooltip: {
      enabled: true,
  },
  legend: {
      show: false,
  },
  stroke: {
      curve: 'smooth',
  },
  grid: {
    show: false,
  },
  yaxis: {
      show: false,
  },
  series: [{
    name: 'Player High',
    data: playerValuesWeek,
  }],
  xaxis: {
    categories: timeValuesWeek,
  }
}

var chartDay = new ApexCharts(document.querySelector("#chartDay"), optionsDay);
var chartWeek = new ApexCharts(document.querySelector("#chartWeek"), optionsWeek);

chartDay.render();
chartWeek.render();
</script>