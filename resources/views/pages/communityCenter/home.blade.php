@include('layouts.headerCommunityCenter')

<div class="communityCenterPageContent">
   <div class="startContainer">
      <a class="page_headline mb-2" style="color: #EEF1F5;">Dies ist der {{$project ? $project->name : ''}} Newsfeed.</a><br>
      <a class="page_subtitle mt-2" style="color: #B0B7C3;">Hier werden demnächst folgende Informationen gelistet.</a><br><br>
      <a class="page_subtitle mt-2" style="color: #B0B7C3;">• Von euch erstellte Changelogs</a><br>
      <a class="page_subtitle mt-2" style="color: #B0B7C3;">• Von euch erstellte Ankündigungen</a><br>
      <a class="page_subtitle mt-2" style="color: #B0B7C3;">• Von euch erstellte Wartungsankündigungen</a><br>
      <a class="page_subtitle mt-2" style="color: #B0B7C3;">• Von euch erstellte Umfragen</a><br>
   </div>
</div>

@include('layouts.footerCommunityCenter')
