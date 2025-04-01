@php
use App\Helpers\Subscription;
@endphp


<!DOCTYPE html>
<html>
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="CraftIT© GmbH">
    <title>{{$project ? $project->name : 'Community Center'}}</title>
    <link rel="apple-touch-icon" href="/assets/images/logo/logoIconTransparent.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/logo/logoIconTransparent.png">

    <!-- BEGINN: FONT CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />    
    {{-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="//static.filestackapi.com/filestack-js/3.x.x/filestack.min.js"></script>

    <!-- BEGIN: MAIN-->
    <link rel="stylesheet" href="/assets/css/app.css?v={{ random_int(1, 999) }}">
    <script src="/assets/js/app.js?v={{ random_int(1, 999) }}"></script>

    @include('layouts.notifications')

</head>

<body>

<style>
html, body {
   margin:0;
   padding:0;
   height:100%;
}

.fsp-picker__brand-container {
  display: none;
}
</style>

@if(isset($project))
  <nav class="navbar navbar-expand-lg communityCenterNav">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"><i style="color: white;font-size:36px;" class="fa-solid fa-bars"></i></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="#">{{ $communityCenterSettings->headline ? $communityCenterSettings->headline : $project->name . " - Community Center"}}</a>
      <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
        <li class="nav-item active">
          <a class="nav-link" href="/cc/">Home</a>
        </li>
        @if(Subscription::hasActiveSubscription('monitoring', $project->id))
        <li class="nav-item active">
          <a class="nav-link" href="/cc/wishes">Wunschliste</a>
        </li>
        @endif
        <li class="nav-item active">
          <a class="nav-link" href="/cc/bugreport">Bugreport</a>
        </li>
        @if(Subscription::hasActiveSubscription('monitoring', $project->id))
        <li class="nav-item active">
          <a class="nav-link" href="/cc/status">Systemstatus</a>
        </li>
        @endif
      </ul>
    </div>
  </nav>
@endif