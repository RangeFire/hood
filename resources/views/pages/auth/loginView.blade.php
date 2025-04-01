<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hood - Community Management</title>
    <link rel="apple-touch-icon" href="/assets/images/logo/logoIconTransparent.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/logo/logoIconTransparent.png">

	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/1f26011b9e.js" crossorigin="anonymous"></script>

    <!-- Lucky Orange -->
    <script async defer src="https://tools.luckyorange.com/core/lo.js?site-id=cfd5f4ca"></script>

    <!-- BEGIN: MAIN-->
    <link rel="stylesheet" href="/assets/css/app.css?v={{ random_int(1, 999) }}">
    <link rel="stylesheet" href="/assets/css/auth.css?v={{ random_int(1, 999) }}">
    <script src="/assets/js/app.js?v={{ random_int(1, 999) }}"></script>
    @include('layouts.notifications')
</head>
<body style="background-color: #23252C!important;">
    
    <form action="/login" class="kt-form" method="POST">@csrf
    <section class="register-wrp">
        <div class="left-bx d-none d-md-flex">
            <div class="register-content">
                <img src="/assets/images/other/logo@2x.png" alt="">
                <h1>Steigere dein Spielerwachstum</h1>
                <p>Manage deine Community an einem zentralen Ort<br> und spare dir verschiedene Discord Bots und Tools.</p>
            </div>

            <div class="register-img">
                <img src="/assets/images/other/reigter-img@2x.png" alt="">
            </div>
        </div>
        <div class="right-bx">
            <div class="reigter-form">
                <h2>Anmelden</h2>
                <p style="margin-bottom: 32px;">Willkommen zurück in der Hood 👋🏼</p>
                {{-- <p>Willkommen zurück 👋🏼</p> --}}
                <div style="display: flex;justify-content: space-between;">
                    <div style="width: 48%;">
                        <a href="/discord/auth/login" class="oauth-button"><img style="width: 24px;" src="/assets/images/icons/discord.png"> &nbsp;Discord</a>
                    </div>
                    <div style="width: 48%;">
                        <a href="/google/auth/login" class="oauth-button"><img style="width: 22px;" src="/assets/images/icons/google.png"> &nbsp;Google</a>
                    </div>
                </div>
                <div style="height: 25px; width: 100%;margin: 21px 0px;background-image: url(/assets/images/background/oauth-space.png);background-size: contain;background-repeat: repeat;"> </div>
                <form>
                    <div class="form-group">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="username" placeholder="placeholder" required>
                          <label for="email2">Dein Nutzername</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-floating mb-3">
                          <input type="password" class="form-control" name="password" placeholder="placeholder" required>
                          <label for="email3">Dein Passwort</label>
                        </div>
                    </div>
                    <h6 style="float: right;margin-top: 0px;margin-bottom: 28px;"><img src="/assets/images/other/chain-ic.svg" alt=""> <a href="/passwords/reset">Passwort vergessen?</a></h6>
                    <button type="submit" class="btn-main">Jetzt anmelden</button>
                    <h6>Noch kein Konto? <a href="/register">&nbsp Registrieren</a></h6>
                </form>
            </div>
        </div>
    </section>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>