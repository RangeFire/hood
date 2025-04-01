@include('layouts.headerCommunityCenter')

<style>
    *{
        color: white;
    }

    img {
    width: 100%;
    height: auto;
    }

    .postBox {
        background-color: #22252D!important;
        border: 1px solid rgba(0, 0, 0, 0.1)!important;
        box-shadow: 0px 4px 8px -4px rgb(0 0 0 / 25%), inset 0px -1px 1px rgb(0 0 0 / 49%), inset 0px 2px 1px rgb(255 255 255 / 6%)!important;
        border-radius: 12px!important;
        padding: 70px;
    }
</style>

<div class="communityCenterPageContent">
    <div class="row d-flex w-100 justify-content-center mt-5">
        <div class="col-xl-7 postBox">
            <div>
                @php
                    echo html_entity_decode($changelogData->content);
                @endphp
            </div>
        </div>
    </div>
    <div class="row d-flex w-100 justify-content-center mt-1">
        <div class="col-xl-7 pl-0">
            <a href="/changelog/{{$changelogData->hash}}/like"><button class="btn button-primary-orange"><i class="fa-solid fa-thumbs-up" style="font-size: 16px;color:white;"></i> {{$changelogData->votes_up}}</button></a> 
        </div>
    </div>
</div>

@include('layouts.footerCommunityCenter')


