<!DOCTYPE html>
<html>
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="CraftIT© GmbH">
    <title>Hood - Willkommen</title>
    <link rel="apple-touch-icon" href="/assets/images/logo/logoIconTransparent.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/logo/logoIconTransparent.png">

    <!-- BEGIN: MAIN-->
    <link rel="stylesheet" href="/assets/css/app.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"></header>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>
<body>

<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

body {
    background-color: #161C27;
}
</style>

<div class="container startContainer">
    <div class="row">
        <a class="startHeadline">Was hast du heute vor?</a>
    </div>

        <div class="row d-flex justify-content-center mt-5">
        <div class="col-4">
          <div class="startChooseBox" onclick="createProject()">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                            <img style="max-width: 200px;" src="/assets/images/background/createProject.png">
                    </div>
                    <div class="col mt-2">
                            <a class="headline">Projekt erstellen</a><br>
                            <a class="subtitle">Dein Projekt, deine Regeln.</a>
                    </div>
                </div>
          </div>
        </div>

        <div class="col-4">
          <div class="startChooseBox" onclick="joinProject()">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                            <img style="max-width: 200px;" src="/assets/images/background/joinProject.png">
                    </div>
                    <div class="col mt-2">
                            <a class="headline">Projekt beitreten</a><br>
                            <a class="subtitle">Einem bestehenden beitreten.</a>
                    </div>
                </div>
          </div>
        </div>
    </div>
</div>


<!-- Create Project -->
<div class="modal custom_modal fade" id="createProject" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="background: #22252D;">
      <div class="modal-body">
            <div class="row">
              <div class="col d-flex justify-content-center ">
                    <img style="width: 84px;" src="/assets/images/createProject.png">
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12 d-flex justify-content-center">
                  <a class="headline">Projekt erstellen</a>
              </div>
              <div class="col-12 d-flex justify-content-center mt-2">
                  <a class="subtitle">Gibt deinem Projekt einen Namen</a>
              </div>
            </div>

            <form action="/project/create" class="kt-form" method="POST">@csrf
            <div class="row">
              <div class="col-12 d-flex justify-content-center mt-4">
                  <input class="customTextInput" type="text" name="projectName" required>
              </div>
              <div class="col-12 d-flex justify-content-center mt-4 mb-2">
                  <button class="customButton" type="submit">Jetzt loslegen</button>
              </div>
            </div>
            </form>
      </div>
    </div>
  </div>
</div>

<!-- Join Project -->
<div class="modal fade" id="joinProject" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="background: #22252D;">
      <div class="modal-body">
            <div class="row">
              <div class="col d-flex justify-content-center ">
                    <img style="width: 84px;" src="/assets/images/codeLock.png">
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12 d-flex justify-content-center">
                  <a class="headline">Einladungscode</a>
              </div>
              <div class="col-12 d-flex justify-content-center mt-2">
                  <a class="subtitle">Gibt deinen Einladungscode ein</a>
              </div>
            </div>

            <form action="/project/join" class="kt-form" method="POST">@csrf
            <div class="row">
              <div class="col-12 d-flex justify-content-center mt-4">
                  <input class="customNumberInput" type="number" name="invitationCode" maxlength = "4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
              </div>
              <div class="col-12 d-flex justify-content-center mt-4 mb-2">
                  <button class="customButton" type="submit">Projekt Beitreten</button>
              </div>
            </div>
            </form>
      </div>
    </div>
  </div>
</div>

<script>
function createProject() {
    $('#createProject').modal('show');
}

function joinProject() {
    $('#joinProject').modal('show');
}
</script>

</body>
</html>