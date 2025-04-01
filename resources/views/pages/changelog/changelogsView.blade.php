@include('layouts.header')
@php $copyLinkURL = (new App\Models\Project)->findProject(session('activeProject'))->project_hash.'.'.env('APP_DOMAIN').'/cc/wishes'; @endphp


<div class="page_content">
    <div class="container customContainer">
        <div class="subNavigation">
            @include('layouts.trialRuntimeInfo')
            <div class="subNavigationSection">
                <div class="subNavLeft">
                    <a class="navlink" href="/wishes">Nutzerwünsche</a>
                    <a class="navlink active" href="/changelogs">Ankündigungen</a>
                    <a class="navlink" href="/bugreports">Bugreports</a>
                    {{-- <a class="navlink" href="">Umfragen</a> --}}
                </div>
                <div class="subNavLeft">
                   <a class="link" href="/changelogs/addChangelog" style="background-color: #2A85FF;color:white!important;border:none;">Ankündigung erstellen</a> 
                </div>
            </div>
        </div>


        <div class="col-8">
            @foreach ($changelogs as $changelog)        
            @php $externalViewURL = 'https://'.(new App\Models\Project)->findProject(session('activeProject'))->project_hash.'.'.env('APP_DOMAIN').'/cc' @endphp
            <div class="col-xl-11 changelogPostItem" style="background-image: url(http://storage.mycraftit.com/hood/nP9ssATvQImXkADWLwwK_Cover%20photo-min.png);">
                <a class="changelogStateLabel">{{$changelog->status}}</a>
                <a class="changelogEditButton mr-3" href="/changelog/deleteChangelog/{{$changelog->hash}}"><i class="fa-sharp fa-solid fa-trash"></i></a>
                <a class="changelogEditButton mr-1" href="/changelogs/editChangelog/{{$changelog->hash}}"><i class="fa-sharp fa-solid fa-pencil"></i></a>
                <a class="changelogEditButton mr-1" href="{{$externalViewURL}}/changelog/{{$changelog->hash}}/view" target="_blank"><i class="fa-sharp fa-solid fa-arrow-up-right-from-square"></i></a>
                
                <div class="postItemContent">
                    <div class="row">
                        <div class="col-xl-8 postItemContentItem">
                            <a class="postItemTitle">{{$changelog->title}}</a>
                            <a class="postItemSubTitle">vom {{ ($changelog->created_at)->format('d.m.Y H:i'); }}</a>
                        </div>
                        <div class="col postItemContentItem">
                            <a class="postItemSubheadline">Autor</a>
                            <a class="postItemHeadline">{{(new App\Models\User)->findUser($changelog->creator)->username}}</a>
                        </div> 
                        <div class="col-auto postItemContentItem">
                            <a class="postItemSubheadline">Likes</a>
                            <a class="postItemHeadline"><i class="fa-solid fa-thumbs-up postItemLikes"></i> {{$changelog->votes_up}}</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>    
        
    </div>
</div>
    <div style="height: 48px;"></div>