@include('layouts.headerCommunityCenter')

<style>
.communityCenterSubtitle {

}

.resultContent {
    margin-top: 30px;
}

.progressItem {
    margin: 18px 0px;
}

.progressItem .customProgressBar {
    height: 30px;
    border-radius: 5px;
    font-weight: 600;
    font-size: 16px;
    background-color: #232A37 !important;
    margin-top: 5px;
}

.progressItem .progressHeadline {
    font-weight: 600;
    font-size: 16px;
    color:white!important;
}

.resultContent .pieChartContainer {
    width: 40%;
}

</style>

<div class="communityCenterPageContent">
    <div class="headline">
        <div class="headlineLeft">
            <a class="communityCenterHeadline">UMFRAGEN TITEL</a><br>
            <a class="communityCenterSubtitle">Umfrageergebnis</a>
        </div>
    </div>

    <div class="resultContent">
        <div class="row mb-5">
            <div class="col-8">
                <a class="communityCenterSubtitle">Hier steht das der Sehr lange Text der UMfragenHier steht das der Sehr lange Text der UMfragenHier steht das der Sehr lange Text der UMfragenHier steht das der Sehr lange Text der UMfragen</a>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-4">
                <div class="progressItem">
                    <a class="progressHeadline">Antwortmöglichkeit 1</a>
                    <div class="progress customProgressBar">
                        <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                    </div>               
                </div>
                <div class="progressItem">
                    <a class="progressHeadline">Antwortmöglichkeit 2</a>
                    <div class="progress customProgressBar">
                        <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                    </div>               
                </div>
                <div class="progressItem">
                    <a class="progressHeadline">Antwortmöglichkeit 3</a>
                    <div class="progress customProgressBar">
                        <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                    </div>               
                </div>
            </div>
            {{-- <div class="col-4 d-flex align-items-center justify-content-center">
                <div class="pieChartContainer mt-5">
                     <canvas id="pieChart" width="50" height="50"></canvas>
                </div>
            </div> --}}
        </div>
    </div>

</div>


<script>
const ctx = document.getElementById('pieChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Antwort1', 'Antwort2', 'Antwort3'],
        datasets: [{
            data: [12, 19, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'none',
      },
      title: {
        display: false,
        text: 'Umfragenauswertung'
      }
    } 
  }
});
</script>


@include('layouts.footerCommunityCenter')





{{-- <div class="container startContainer">
    <div class="row">
        <p class="startHeadline">Umfrageergebnis</p>
        <a class="page_subtitle" style="text-align: center;padding: 0px 23%; color:white;">{{ $survey->status == "stop" ? 'Die Umfrage ist bereits beendet. Hier ist das Ergebnis.' : 'Wir danken dir für deine Teilnahme.' }}</a>
    </div>

    <div class="container" style="width: 50%;">
        <div class="row mt-4">

            <!-- Survey Item -->
            <div class="col-12 mt-3 surveyAnswereItem">
                <div class="row">
                    <div class="col-1 d-flex justify-content-center">
                            <a class="reactionEmoji">{{ $survey->icon_one ? $survey->icon_one : 'NOTHING' }}</a>
                    </div>
                    <div class="col-10 d-flex justify-content-center align-items-center">
                            <a class="answereText">{{ $survey->answere_one ? $survey->answere_one : 'NOTHING' }} ({{round($answereStatistics['answer_one'])}}%)</a>
                    </div>
                </div>
            </div>
            <!-- Survey Item END -->

            <!-- Survey Item -->
            <div class="col-12 mt-3 surveyAnswereItem">
                <div class="row">
                    <div class="col-1 d-flex justify-content-center">
                            <a class="reactionEmoji">{{ $survey->icon_two ? $survey->icon_two : 'NOTHING' }}</a>
                    </div>
                    <div class="col-10 d-flex justify-content-center align-items-center">
                            <a class="answereText">{{ $survey->answere_two ? $survey->answere_two : 'NOTHING' }} ({{round($answereStatistics['answer_two'])}}%)</a>
                    </div>
                </div>
            </div>
            <!-- Survey Item END -->

            @if ($survey->answere_three) 
            <!-- Survey Item -->
            <div class="col-12 mt-3 surveyAnswereItem">
                <div class="row">
                    <div class="col-1 d-flex justify-content-center">
                            <a class="reactionEmoji">{{ $survey->icon_three ? $survey->icon_three : 'NOTHING' }}</a>
                    </div>
                    <div class="col-10 d-flex justify-content-center align-items-center">
                            <a class="answereText">{{ $survey->answere_three ? $survey->answere_three : 'NOTHING' }} ({{round($answereStatistics['answer_three'])}}%)</a>
                    </div>
                </div>
            </div>
            <!-- Survey Item END -->
            @endif

        </div>
    </div>

</div> --}}


{{-- <script>
function createProject() {
    $('#createProject').modal('show');
}

function joinProject() {
    $('#joinProject').modal('show');
}
</script>

</body>
</html> --}}