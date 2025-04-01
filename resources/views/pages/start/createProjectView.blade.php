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

    <link rel="stylesheet" href="/assets/css/app.css?v={{ random_int(1, 999) }}">
    <link rel="stylesheet" href="/assets/css/auth.css?v={{ random_int(1, 999) }}">
    <script src="/assets/js/app.js?v={{ random_int(1, 999) }}"></script>
    @include('layouts.notifications')
</head>
<body>
    <a href="/" type="button" class="btn-close" data-bs-dismiss="modal"><i class="fal fa-times fa-fw"></i></a>
    <div class="main-wizard-wrp" style="margin-top: 80px;">
        <div class="container">
            <div class="main-wizard-row">
                <div class="wizard-block">
                    <h1>Was hast du heute vor?</h1>

                    <form action="/project/create" class="kt-form" method="POST">@csrf
                    <div class="form-wizard">
                            <div class="form-wizard-header">
                                <ul class="list-unstyled form-wizard-steps clearfix">
                                    <li class="activated"><span>1</span>Triff eine Entscheidung</li>
                                    <li class="active"><span>2</span> Projektname</li>
                                    <li><span>3</span> Durchstarten</li>
                                </ul>
                            </div>

                            <fieldset class="wizard-fieldset show">
                                <div class="wizard-inner-block">
                                    <div class="verify-block">
                                        <img src="images/verify-ic.svg" alt="">

                                        <h3>Projekt Erstellen</h3>
                                        <p>Gib deinem Projekt einen Namen und legt direkt los.</p>

                                        <div class="opt-box">
                                            <div class="form-group">
                                                <div class="form-floating mb-3" style="width: 340px;">
                                                <input type="text" class="form-control" name="projectName" placeholder="name" required>
                                                <label for="email">Dein Projektname</label>
                                                </div>
                                            </div>
                                        </div>

                                       <div class="btn-group">
                                            <div class="btnbx">
                                                <a href="/start" class="form-wizard-previous-btn btn-main"><i class="far fa-arrow-left fa-fw"></i> Zurück</a>
                                            </div>
                                            <div class="btnbx">
                                                <button type="submit" class="form-wizard-next-btn btn-main nextbtn" style="width: 100%;">Weiter <i class="far fa-arrow-right fa-fw"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <img src="images/shap1.png" alt="" class="shp1">
                                    <img src="images/shap2.png" alt="" class="shp2">
                                </div>
                            </fieldset> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script>
         /* ***** OTP Verification JS ***** */
    var verificationCode = [];
    $(".verification-code input[type=text]").keyup(function (e) {
      
      // Get Input for Hidden Field
      $(".verification-code input[type=text]").each(function (i) {
        verificationCode[i] = $(".verification-code input[type=text]")[i].value; 
        $('#verificationCode').val(Number(verificationCode.join('')));
        //console.log( $('#verificationCode').val() );
      });

      //console.log(event.key, event.which);

      if ($(this).val() > 0) {
        if (event.key == 1 || event.key == 2 || event.key == 3 || event.key == 4 || event.key == 5 || event.key == 6 || event.key == 7 || event.key == 8 || event.key == 9 || event.key == 0) {
          $(this).next().focus();
        }
      }else {
        if(event.key == 'Backspace'){
            $(this).prev().focus();
        }
      }

    }); // keyup

    $('.verification-code input').on("paste",function(event,pastedValue){
      console.log(event)
      $('#txt').val($content)
      console.log($content)
      //console.log(values)
    });
    </script>
</body>
</html>