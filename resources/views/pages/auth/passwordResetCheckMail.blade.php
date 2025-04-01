<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hood - Community Management</title>
    <link rel="apple-touch-icon" href="/assets/images/logo/logoIconTransparent.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/logo/logoIconTransparent.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/1f26011b9e.js" crossorigin="anonymous"></script>

    <!-- Lucky Orange -->
    <script async defer src="https://tools.luckyorange.com/core/lo.js?site-id=cfd5f4ca"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- BEGIN: MAIN-->
    <link rel="stylesheet" href="/assets/css/app.css?v={{ random_int(1, 999) }}">
    <link rel="stylesheet" href="/assets/css/auth.css?v={{ random_int(1, 999) }}">
    <script src="/assets/js/app.js?v={{ random_int(1, 999) }}"></script>

    @include('layouts.notifications')
</head>
<body>
    
    <form action="/passwords/reset/link" class="kt-form" method="POST">@csrf
    <section class="register-wrp">
        <div class="left-bx d-none d-md-flex">
            <div class="register-content">
                <img src="/assets/images/other/logo@2x.png" alt="">

                <h1>Next Level Community<br> Management</h1>
                <p>Manage deine Community an einem zentralen Ort<br> und spare dir tausende Discord Bots und Tools.</p>
            </div>

            <div class="register-img">
                <img src="/assets/images/other/reigter-img@2x.png" alt="">
            </div>
        </div>
        <div class="right-bx">
            <div class="reigter-form">
                <h2>E-Mails prüfen</h2>
                <p>Du solltest eine E-Mail mit Anweisungen erhalten haben um dein Passwort zurücksetzen zu können.</p>


                <a href="/" class="btn-main">zurück zum Login</a>

            </div>
        </div>
    </section>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>