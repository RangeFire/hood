@include('layouts.header')
@php
    $timezone = date_default_timezone_get();
@endphp

<div class="page_content">

    <!--- Page headline -->
    <div class="row mt-4">
        <div class="col-6 page_title_area">
            <h3 class="h2">Umfragen</h3>
            <h6 class="page_subtitle">Hol dir die Meinung deiner Community</h6>
        </div>
        <div class="col-6 text-right">
            <button class="btn button-primary-orange" data-toggle="modal" data-target="#addSurvey">
                Hinzufügen
            </button>
        </div>
    </div>

    <div class="row" style="margin-top: 90px;">
        <div class="col-12">


            <div class="row">

                <script>
                    let surveyID;
                </script>

                @foreach ($surveys as $survey)

                    @php

                        $percent_one = $survey->formattedAnswers['one_percent'];
                        $percent_two = $survey->formattedAnswers['two_percent'];
                        $percent_three = $survey->formattedAnswers['three_percent'];

                    @endphp

                            <!-- Survey Card -->
                    <div class="col-4 mr-4 card surveyCard mb-4">
                        <div class="row">
                            <div class="col">
                                <p class="settingsHeadline">{{ $survey->title }}</p>
                                <a href="" class="settingsSubHead noWrap" data-toggle="tooltip" data-placement="top"
                                   title="{{ $survey->content }}">{{ $survey->content }}</a>
                            </div>
                        </div>
                        <div class="survey-container mt-4">
                            <div class="survey-column">
                                {{-- <div class="circular-progress">
                                    <div class="value-container">0%</div>
                                </div>    --}}
                                <div id="circular-progress-left-{{ $survey->id }}"></div>
                                <div class="chartContent centerChooseText mt-3">
                                    <p class="surveyChooseEmoji">{{ $survey->icon_one ? $survey->icon_one : 'NOTHING' }}</p>
                                    <a class="surveyChooseText">{{ $survey->answere_one ? $survey->answere_one : 'NOTHING' }}</a>
                                </div>
                            </div>
                            <div class="survey-column">
                                {{-- <div class="circular-progress">
                                    <div class="value-container">0%</div>
                                </div>    --}}
                                <div id="circular-progress-middle-{{ $survey->id }}"></div>
                                <div class="chartContent centerChooseText mt-3">
                                    <p class="surveyChooseEmoji">{{ $survey->icon_two ? $survey->icon_two : 'NOTHING' }}</p>
                                    <a class="surveyChooseText">{{ $survey->answere_two ? $survey->answere_two : 'NOTHING' }}</a>
                                </div>
                            </div>
                            <div class="survey-column">
                                @if ($survey->answere_three)
                                    {{-- <div class="circular-progress">
                                        <div class="value-container">0%</div>
                                    </div>    --}}
                                    <div id="circular-progress-right-{{ $survey->id }}"></div>
                                    <div class="chartContent centerChooseText mt-3">
                                        <p class="surveyChooseEmoji">{{ $survey->icon_three ? $survey->icon_three : 'NOTHING' }}</p>
                                        <a class="surveyChooseText">{{ $survey->answere_three ? $survey->answere_three : 'NOTHING' }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-4 d-flex justify-content-center manageSurvey">
                            {{-- <i onclick="javascript:window.open('/survey/set/active')" class="fa-solid fa-circle-play"></i> --}}
                            @if ($survey->status == "active")
                                <i onclick="location.href=('/survey/stop/{{ $survey->id }}')"
                                   class="fa-solid fa-circle-stop"></i>
                            @endif
                            <i onclick="javascript:window.open('https://extern.wehood.app/{{ (new App\Models\Project)->findProject(session('activeProject'))->project_hash }}/survey/{{ $survey->id }}', '_blank')"
                               class="fa-solid fa-eye"></i>
                            <i onclick="copyLinkToClipboard({{ $survey->id }})" id="copySurveyLink"
                               class="fa-solid fa-copy"></i>
                        </div>
                        <div class="row d-flex justify-content-center mt-2">
                            <a href=""
                               class="surveyUserText">Teilnehmer: {{ (new App\Models\SurveyAnswere)->countSurveyAnswer($survey->id) }}</a>
                        </div>
                    </div>
                    <style>
                        .xrow {
                            display: flex;
                            flex-direction: row;
                            align-items: center;
                            justify-content: space-evenly;
                        }

                        .xcol-4 {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                        }
                    </style>
                    <!-- Survey Card END -->

                    <script>
                        surveyID = "{{ $survey->id }}";
                        new CircularProgress($(`#circular-progress-left-${surveyID}`), {{ $percent_one }})
                        new CircularProgress($(`#circular-progress-middle-${surveyID}`), {{ $percent_two }})
                        new CircularProgress($(`#circular-progress-right-${surveyID}`), {{ $percent_three }})
                    </script>

                @endforeach

                <!-- Survey Card Add -->
                <div class="col-4 card surveyCardAdd" data-toggle="modal" data-target="#addSurvey">
                    <div class="center">
                        <i class="fa-solid fa-circle-plus addIcon"></i>
                    </div>
                </div>
                <!-- Survey Card ADD END -->

            </div>


        </div>
        <div>


        </div>

        <!-- Umfrage erstellen -->
        <div class="modal fade" id="addSurvey" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col d-flex justify-content-center ">
                                <img style="width: 84px;" src="/assets/images/statistics.png">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-center">
                                <a class="headline">Umfrage erstellen</a>
                            </div>
                            <div class="col-12 d-flex justify-content-center mt-2">
                                <a class="subtitle">Setzte auf die Meinung deiner Community</a>
                            </div>
                        </div>

                        <form method="post" action="/survey/add">@csrf
                            <div class="row mt-5" id="modal_emoji_root_element">
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <input name="surveyTitle" type="text" class="form-control"
                                                       autocomplete="off" placeholder="GameOS nutzen" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col">
                                            <div class="form-group">
                                                <textarea class="form-control" style="margin-top:5px;"
                                                          placeholder="Sollen wir GameOS nutzen?" name="surveyContent"
                                                          rows="3" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-7">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="form-group">
                                                <input name="answere1" type="text" class="form-control"
                                                       autocomplete="off" placeholder="Antwortmöglichkeit 1" required>
                                            </div>
                                        </div>
                                        <div class="changeEmojiNote">
                                            <img src="/assets/images/svg/changeEmoji.svg">
                                        </div>
                                        <div class="col-3" style="padding-left: 0px;">
                                            <div class="form-group">
                                                <button type="button" class="emojiButton" id="emoji-1">😍</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-9">
                                            <div class="form-group">
                                                <input name="answere2" type="text" class="form-control"
                                                       autocomplete="off" placeholder="Antwortmöglichkeit 2" required>
                                            </div>
                                        </div>
                                        <div class="col-3" style="padding-left: 0px;">
                                            <div class="form-group">
                                                <button type="button" class="emojiButton" id="emoji-2">😐</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-9">
                                            <div class="form-group">
                                                <input name="answere3" type="text" class="form-control"
                                                       autocomplete="off" placeholder="Antwortmöglichkeit 3">
                                            </div>
                                        </div>
                                        <div class="col-3" style="padding-left: 0px;">
                                            <div class="form-group" style="padding-top:3px;">
                                                <button type="button" class="emojiButton" id="emoji-3">😡</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="text" name="custom_emojis" id="custom-emojis" hidden>

                                <script>

                                    const customEmojis = ['😍', '😐', '😡'];

                                    $(() => {
                                        const emoji_button_one = $('#emoji-1');
                                        const emoji_button_two = $('#emoji-2');
                                        const emoji_button_three = $('#emoji-3');

                                        const emoji_one = new EmojiButton({rootElement: modal_emoji_root_element});
                                        const emoji_two = new EmojiButton({rootElement: modal_emoji_root_element});
                                        const emoji_three = new EmojiButton({rootElement: modal_emoji_root_element});

                                        emoji_one.on('emoji', emoji => {
                                            $(emoji_button_one).html(emoji.emoji);
                                            customEmojis[0] = emoji.emoji;
                                            setCustomEmojiToPostData();
                                        });

                                        emoji_two.on('emoji', emoji => {
                                            $(emoji_button_two).html(emoji.emoji);
                                            customEmojis[1] = emoji.emoji;
                                            setCustomEmojiToPostData();
                                        });

                                        emoji_three.on('emoji', emoji => {
                                            $(emoji_button_three).html(emoji.emoji);
                                            customEmojis[2] = emoji.emoji;
                                            setCustomEmojiToPostData();
                                        });

                                        emoji_button_one.on('click', (evt) => {
                                            emoji_one.pickerVisible ? emoji_one.hidePicker() : emoji_one.showPicker(emoji_button_one);
                                        });

                                        emoji_button_two.on('click', (evt) => {
                                            emoji_two.pickerVisible ? emoji_two.hidePicker() : emoji_two.showPicker(emoji_button_two);
                                        });

                                        emoji_button_three.on('click', (evt) => {
                                            emoji_three.pickerVisible ? emoji_three.hidePicker() : emoji_three.showPicker(emoji_button_three);
                                        });

                                        setCustomEmojiToPostData();

                                    });

                                    function setCustomEmojiToPostData() {
                                        $('#custom-emojis').val(JSON.stringify(customEmojis));
                                    }

                                    function copyLinkToClipboard(surveyID) {

                                        var finalText = 'https://extern.wehood.app/{{ (new App\Models\Project)->findProject(session('activeProject'))->project_hash }}/survey/' + surveyID;

                                        navigator.clipboard.writeText(finalText);
                                        new Notification('success', 'Der Link wurde kopiert.', '5000')
                                    }

                                    // window.addEventListener('DOMContentLoaded', () => {
                                    //     const picker = new EmojiButton();
                                    //     picker.on('emoji', emoji => {
                                    //         document.querySelector('input').value += emoji.emoji;
                                    //     });
                                    //     button.addEventListener('click', () => {
                                    //         picker.pickerVisible ? picker.hidePicker() : picker.showPicker(button);
                                    //     });
                                    // });
                                </script>

                                <style>
                                    .emoji-picker__wrapper {
                                        position: absolute;
                                        right: -350px;
                                    }
                                </style>

                                {{-- <!-- Discord Vorschau -->
                                <div class="col-7">
                                    <div class="discordPreview">
                                        <div class="head">
                                            <img style="max-width: 32px;" src="data:image/png;base64,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">
                                            <a class="botName ml-2">GameOS <span class="botTag">BOT</span> <span style="font-size: 12px;" class="messageDate ml-2"> 22.06.2022</span></a>
                                        </div>
                                        <div class="body mt-3">
                                            <a class="headline">Title der Umfrage</a><br>
                                            <a class="content">Der Beschriebungs Inhalt der Umfrage soll dann hier mit rein damit die direkt eine Vorschau haben vwie Ihr Zeug aussieht.</a>
                                        </div>
                                        <div class="discordPreviewReactions mt-3">
                                            <a class="reactionItem">&nbsp 🥵 &nbsp 1 &nbsp</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Discord Vorschau END --> --}}
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input name="stopAt" type="datetime-local" class="form-control"
                                               autocomplete="off" placeholder="Umfrage automatisch stoppen?">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center mt-4">
                                <div class="col d-flex justify-content-center">
                                    <button class="customButton" style="" onclick="">Erstellen und veröffentlichen
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>