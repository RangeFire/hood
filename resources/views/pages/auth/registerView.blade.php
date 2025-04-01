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

    <link rel="stylesheet" href="/assets/css/app.css?v={{ random_int(1, 999) }}">
    <link rel="stylesheet" href="/assets/css/auth.css?v={{ random_int(1, 999) }}">

    <!-- Lucky Orange -->
    <script async defer src="https://tools.luckyorange.com/core/lo.js?site-id=cfd5f4ca"></script>
</head>
<body>
    
    <form action="/register" class="kt-form" method="POST">@csrf
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
                <h2>Registrieren</h2>
                <p>Erstelle dir dein persönliches Konto</p>
                   <div style="display: flex;justify-content: space-between;">
                    <div style="width: 48%;">
                        <a href="/discord/auth/login" class="oauth-button"><img style="width: 24px;" src="/assets/images/icons/discord.png"> &nbsp;Discord</a>
                    </div>
                    <div style="width: 48%;">
                        <a href="/google/auth/login" class="oauth-button"><img style="width: 22px;" src="/assets/images/icons/google.png"> &nbsp;Google</a>
                    </div>
                </div>
                <div style="height: 25px; width: 100%;margin: 21px 0px;background-image: url(/assets/images/background/oauth-space.png);background-size: contain;background-repeat: repeat;"> </div>

                    <div class="form-group">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="username" placeholder="name@example.com" required>
                          <label for="email">Dein Benutzername</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="email" placeholder="name@example.com" required>
                          <label for="email2">Deine E-Mail Adresse</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-floating mb-3">
                          <input type="password" class="form-control" name="password" placeholder="placeholder" required>
                          <label for="email3">Dein Passwort</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="customcheck">
                            <input class="styled-checkbox" id="styled-checkbox-1" type="checkbox" value="value1" required>
                            <label for="styled-checkbox-1"><span>Ich akzeptiere die <a href="https://mycraftit.com/legal/privacy">Datenschutzbestimmungen</a>.</span></label>
                        </div>
                    </div>

                    <button type="submit" class="btn-main">Registrierung abschließen</button>
                    <h6>Bereits registriert? <img src="/assets/images/other/chain-ic.svg" alt=""> <a href="/">Anmelden</a></h6>
            </div>
        </div>
    </section>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>