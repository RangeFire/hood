@include('layouts.header')

<div class="page_content">

    <?php //var_dump($_SESSION['permissions']) ?>

    <!--- Page headline -->
    <div class="row">
        <div class="col-xl-4 page_title_area">
            <h3 class="page_headline">Benutzer</h3>
            <h6 class="page_subtitle">Alle Benutzer auf einen Blick</h6>
        </div>
        <div class="col-8 text-right">
                {{-- <button class="btn button-primary-orange mr-3" data-toggle="modal" data-target="#createRole">Rolle anlegen</button> --}}
            <button class="btn button-primary-orange" data-toggle="modal" data-target="#inviteUser">Einladungen</button>
            <button class="btn button-primary-orange ml-3" data-toggle="modal" data-target="#modal-createRole">Rolle anlegen</button>
        </div>
    </div>


    <div class="row" style="margin-top: 74px;">
        <div class="col-12">

            <div class="top-search-bar card p-0">
                <div class="card-body p-0 m-0">

                    <div class="search-elements">
                        <img class="search-img" src="/assets/images/svg/search.svg" alt="">
                        <input id="inputsearch" type="text" name="search" class="form-control search" placeholder="Suchen..." autocomplete="off" value="" required>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card customHoodCard" style="padding: 32px; margin-top: 48px">
                    <table id="theTableSecond" class="table dataTable">
                        <thead>
                        <tr>
                            <th>Nutzername</th>
                            <th>Name</th>
                            <th>Rolle</th>
                            <th>Beigetreten</th>
                            <th>Aktionen</th>
                        </tr>
                        </thead>
                        <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->user->username }}</td>
                                        <td>{{ $user->user->fullname }}</td>
                                        <td style="text-decoration: underline;cursor: pointer" onclick="selectRole('{{ $user->user->id }}')">{{ $user->currentRole ? $user->currentRole->title : 'Keine Rolle' }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            {{-- <a onclick="editEmployeeModal('{{$user->id}}')"><i class="fas fa-pen"></i></a> --}}
                                            <a class="miniIcon" onclick="deleteEmployeeModal('{{$user->user->id}}', '{{session('activeProject')}}')"><i class='fas fa-trash-alt'></i></a>
                                        </td>
                                    </tr>  
                                @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
        <div class="col-xl-4">
               <div class="card customHoodCard" style="padding: 20px 32px; margin-top: 48px">

                    @foreach ($roles as $role)
                        <div class="row mb-2 rolesCard">
                            <div class="col">
                                <a class="roleTitle">{{ $role->title }}</a><br>
                                <a class="roleUser">1 Benutzer</a>
                            </div>
                            <div class="col d-flex align-items-center justify-content-end">
                                <a onclick="editRoleModal('{{$role->id}}')" class="mr-1"><i class="fa-solid fa-pen"></i></a>
                                <a onclick="deleteRoleModal('{{$role->id}}')" class="ml-1"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </div>
                    @endforeach

               </div>
        </div>
    </div>
</div>



<!-- Modal - delete a employee -->
<div class="modal fade" id="modal-deleteEmployee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content custom_modal">
            <div class="modal-header custom_modal_header">
                <div class="row">
                    <div class="col-xl-6 col-6">
                        <img src="/assets/images/alert-triangle.png" class="w-100 h-auto">
                    </div>
                </div>
            </div>
            <form action="" id="modal-deleteEmployee_form" method="post">
            @csrf
                <div class="modal-body">
                    <h4 class="h2" id="exampleModalLabel" style="margin-bottom: 16px;">Benutzer entfernen</h4>
                    <div class="page_subtitle">
                        Bist du dir sicher, dass du diesen Nutzer entfernen möchtest?
                    </div>
                </div>
                <div class="modal-footer custom_modal_footer justify-content-center">
                    <button style="width: 46%;" type="button" class="btn button-primary"
                            data-dismiss="modal">Abbrechen
                    </button>
                    <button style="width: 46%;" type="submit"
                        class="btn button-primary-red">Entfernen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- invite an Employee -->
<div class="modal fade" id="inviteUser" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
            <div class="row">
              <div class="col d-flex justify-content-center ">
                    <img style="width: 84px;" src="/assets/images/invitationCodes.png">
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12 d-flex justify-content-center">
                  <a class="headline">Einladungscodes</a>
              </div>
              <div class="col-12 d-flex justify-content-center mt-2">
                  <a class="subtitle">Erstelle und verwalte deine Einladungen</a>
              </div>
            </div>

            <div class="row d-flex justify-content-center mt-4">
                <div class="col-12 d-flex justify-content-center">
                    <a class="invitationCode" id="inviteCode">{{ $inviteCode ? $inviteCode->code : '-' }}</a>
                </div>         
            </div>
            <div class="row d-flex justify-content-center mt-4">
                <div class="col-4 d-flex justify-content-center">
                    <button class="customButton" style="width: 150px;" onclick="generateInviteCode()">Generieren</button>
                </div>     
                <div class="col-4 d-flex justify-content-center">
                    <button class="customButton" style="width: 150px;" onclick="copyCodeToClipboard()">Kopieren</button>
                </div>       
            </div>
      </div>
    </div>
  </div>
</div>

<!-- edit an Employee -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content custom_modal">
            <div class="modal-header custom_modal_header">
                <p class="h3" id="exampleModalLabel">Mitarbeiter bearbeiten</p>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm" action="/editEmployee" method="post">
                @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Vor- & Nachname</label>
                            <input id="editEmployee_fullname" name="fullname" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input id="editEmployee_email" name="email" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Benutzerrname</label>
                            <input id="editEmployee_username" name="username" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Neues Passwort</label>
                            <input id="editEmployee_password" name="password" type="password" class="form-control">
                        </div>
                        {{-- <div class="form-group">
                            <label>Benutzerrechte</label>
                            <select id="editEmployee_userRights" name="userRights" class="form-control" required>
                                <option value="" selected disabled>Bitte auswählen</option>
                                <option value="1">1st Level Support</option>
                                <option value="2">2nd Level Support</option>
                                <option value="3">3rd Level Support / Entwicklung</option>
                                <option value="4" disabled>Administrator</option>
                            </select>
                        </div> --}}
                        <div id="customProfileImageEdit"></div>

                        <script defer>
                            let customProfileImageEdit = new CustomUpload($('#customProfileImageEdit'), {
                                displayText: 'Profilbild zum hochladen hier ablegen',
                                mode: 'crop-inline-display',
                                formRequired: false,
                            });
                        </script>

                    </div>
            </div>
            <div class="modal-footer custom_modal_footer justify-content-center">
                <button style="width: 46%;" type="button" class="btn button-primary"
                        data-dismiss="modal">Abbrechen
                </button>
                <button style="color: #FCFCFC; width: 46%;" type="submit" name="editEmployeeSubmit"
                        class="btn button-primary-orange">Speichern
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-selectRole" tabindex="-1" role="dialog" aria-hidden="true">
   
    <div class="modal-dialog modal-dialog-centered" role="document">
     <div class="modal-content">
       <div class="modal-body">
             <div class="row mt-3">
               <div class="col-12 d-flex justify-content-center">
                   <a class="headline"><span id="selectRole_user_name"></span> eine Rolle zuweisen</a>
               </div>
             </div>
 
             <form action="SETBYSCRIPT" id="selectRole_form" class="kt-form" method="post">@csrf
                
                <div class="row mt-4">
                    <div class="col-10 offset-1">
                        <div class="form-group">
                            <label>Rolle auswählen</label>
                            <select class="form-control" name="roleID" id="" required>
                                <option value="" selected disabled readonly><--- Bitte auswählen ---></option>
                                
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->title }}</option>
                                @endforeach
                                
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-8 offset-2 d-flex justify-content-center mt-4 mb-2">
                   <button class="btn btn-block button-primary-orange" type="submit">Speichern</button>
                </div>
             </form>
       </div>
     </div>
   </div>
    
 </div>

<!-- create an User role -->
<div class="modal fade" id="modal-createRole" tabindex="-1" role="dialog" aria-hidden="true">
   
   <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
            <div class="row mt-3">
              <div class="col-12 d-flex justify-content-center">
                  <a class="headline">Rolle erstellen</a>
              </div>
            </div>

            <form action="/user/role/add" class="kt-form" method="post">@csrf
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Name der Rolle</label>
                            <input name="rolename" type="text" class="form-control" autocomplete="off" placeholder="" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-6">
                        <input class="mb-2" type="checkbox" name="manage_users"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf Nutzer/Rollen anlegen, bearbeiten und löschen">Teamverwaltung</a><br>
                        <input class="mb-2" type="checkbox" name="support"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf alle Funktionen des Supportsystem nutzen">Supportsystem</a><br>
                        <input class="mb-2" type="checkbox" name="monitoring"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf alle Funktionen des Monitorings nutzen">Monitoring</a><br>
                        <input class="mb-2" type="checkbox" name="surveys"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf alle Funktionen des Umfragensystems nutzen">Umfragen</a><br>

                    </div>
                    <div class="col-6">
                        <input class="mb-2" type="checkbox" name="settings"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Verwaltung der Projekteinstellungen">Einstellungen</a><br>
                        {{-- <input class="mb-2" type="checkbox" name="ingame_integration"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf die Spieler aus der Datenbank ansehen">Ingameintegration</a><br> --}}
                        <input class="mb-2" type="checkbox" name="wishes"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf alle Funktionen des Wünschesystems nutzen">Wünsche</a><br>
                        <input class="mb-2" type="checkbox" name="bugreports"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf Bugreports verwalten">Bugreports</a><br>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center mt-4 mb-2">
                  <button class="customButton" type="submit">Erstellen</button>
              </div>
            </form>
      </div>
    </div>
  </div>
   
</div>

<!-- edit an User role -->
<div class="modal fade" id="modal-editRole" tabindex="-1" role="dialog" aria-hidden="true">
   
    <div class="modal-dialog modal-dialog-centered" role="document">
     <div class="modal-content">
       <div class="modal-body">
             <div class="row mt-3">
               <div class="col-12 d-flex justify-content-center">
                   <a class="headline">Rolle bearbeiten</a>
               </div>
             </div>
 
             <form id="editRole_form" action="/user/role/edit/" class="kt-form" method="post">@csrf
                 <div class="row mt-4">
                     <div class="col-6">
                         <div class="form-group">
                             <label>Name der Rolle</label>
                             <input id="editRole_name" name="rolename" type="text" class="form-control" autocomplete="off" placeholder="" required>
                         </div>
                     </div>
                 </div>
 
                <div class="row mt-2">
                    <div class="col-6">
                        <input id="editRole_check_manage_users" class="mb-2" type="checkbox" name="manage_users"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf Nutzer/Rollen anlegen, bearbeiten und löschen">Teamverwaltung</a><br>
                        <input id="editRole_check_support" class="mb-2" type="checkbox" name="support"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf alle Funktionen des Supportsystem nutzen">Supportsystem</a><br>
                        <input id="editRole_check_monitoring" class="mb-2" type="checkbox" name="monitoring"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf alle Funktionen des Monitorings nutzen">Monitoring</a><br>
                        <input id="editRole_check_surveys" class="mb-2" type="checkbox" name="surveys"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf alle Funktionen des Umfragensystems nutzen">Umfragen</a><br>
                    </div>
                    <div class="col-6">
                        <input id="editRole_check_settings" class="mb-2" type="checkbox" name="settings"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Verwaltung der Projekteinstellungen">Einstellungen</a><br>
                        {{-- <input id="editRole_check_ingame_integration" class="mb-2" type="checkbox" name="ingame_integration"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf die Spieler aus der Datenbank ansehen">Ingameintegration</a><br> --}}
                        <input id="editRole_check_wishes" class="mb-2" type="checkbox" name="wishes"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf alle Funktionen des Wünschesystems nutzen">Wünsche</a><br>
                        <input id="editRole_check_bugreports" class="mb-2" type="checkbox" name="bugreports"><a class="ml-2 checkboxText" data-toggle="tooltip" data-placement="top" data-title="Darf Bugreports verwalten">Bugreports</a><br>
                    </div>
                </div>
                 <div class="col-12 d-flex justify-content-center mt-4 mb-2">
                   <button class="customButton" type="submit">Speichern</button>
               </div>
             </form>
       </div>
     </div>
   </div>
    
 </div>

<!-- Modal - delete a role -->
<div class="modal fade" id="modal-deleteRole" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content custom_modal">
            <div class="modal-header custom_modal_header">
                <div class="row">
                    <div class="col-xl-6 col-6">
                        <img src="/assets/images/alert-triangle.png" class="w-100 h-auto">
                    </div>
                </div>
            </div>
            <form action="" id="modal-deleteRole_form" method="post">
            @csrf
                <div class="modal-body">
                    <h4 class="h2" id="exampleModalLabel" style="margin-bottom: 16px;">Rolle löschen</h4>
                    <div class="page_subtitle">
                        Sind Sie sich sicher, dass Sie diese Rolle löschen wollen?
                    </div>
                </div>
                <div class="modal-footer custom_modal_footer justify-content-center">
                    <button style="width: 46%;" type="button" class="btn button-primary"
                            data-dismiss="modal">Abbrechen
                    </button>
                    <button style="width: 46%;" type="submit"
                        class="btn button-primary-red">Löschen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    let users = @json($users);

    function selectRole(userID) {

        let user = users.find(user => user.user.id == userID);

        if(!user) return console.error('user not found');

        $('#selectRole_form').attr('action', '/user/role/assign/' + user.user.id);
        $('#selectRole_user_name').html(user.user.username);
        $('#modal-selectRole').modal('show');

    }

$(document).ready(function(){
   var copyText = $('#inviteCode').text();

   $('#inviteCode').html(copyText.toString().split('').join('-'));
});

function generateInviteCode() {
    axios.get('/invite/generate/'+{{ session('activeProject') }}).then(resp => {
        let id = resp.data;
        
        $('#inviteCode').html(id.toString().split('').join('-'));
    });
}

function copyCodeToClipboard() {
   var copyText = $('#inviteCode').text();
   var finalText = copyText.split('-').join('');

   navigator.clipboard.writeText(finalText);

    navigator.clipboard.writeText(finalText);
    new Notification('success', 'Der Einladungscode wurde kopiert.', '5000')
}


</script>
<style>
    input[type='checkbox'] {
        margin-top: 3px;
    }

    #appViewMoveFileTextChange{
        font-family: Montserrat;
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 150%;
        /* or 21px */
        text-align: center;

        /* LightMode/Body */

        color: #565677;
    }

    .appViewHint {
        padding: 16px 56px 16px 0px;
        background-color: #f6f6f6;
        border-radius: 8px;
    }

    .image-drop {
        width: 100%;
        height: 150px;
        background: #cff;
        border-radius: 8px;

        border: 2px dashed #1784D5;
        background: rgba(92, 168, 238, 0.12);
        cursor: pointer;

        user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
    }

    .image-drop-text {
        text-align: center;
        height: 100%;
        padding: 30px;
        background: rgba(92, 168, 238, 0.12);

        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-drop-text h3 {
        font-family: 'Montserrat';
        font-style: normal;
        font-weight: 500;
        font-size: 1.0rem;
        line-height: 150%;

        color: #1784D5;
    }

    .appViewNameLabel {
        font-family: Montserrat;
        font-style: normal;
        font-weight: 800;
        font-size: 14px;
        line-height: 150%;
        color: #565677;
    }

    .anhang_header {
        font-family: Montserrat;
        font-style: normal;
        font-weight: 800;
        font-size: 34px;
        line-height: 120%;
        color: #14142A;
    }

    .invitationCode {
        font-size: 30px;
        color: #EEF1F5!important;
    }

    .modal-body label {
        color: #B0B7C3!important;
    }

    .modal-body input {
        background-color: #2F3746!important;
        padding: 12px;
        border-radius: 12px;
        color: white!important;
        font-size: 24px;
        text-align: center;
        border: none;
    }

    .modal-body .checkboxText {
        color: #B0B7C3!important;
    }

    .rolesCard {
        background-color: #22252D;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0px 4px 8px -4px rgb(0 0 0 / 25%), inset 0px -1px 1px rgb(0 0 0 / 49%), inset 0px 2px 1px rgb(255 255 255 / 6%);
        border-radius: 12px;
        padding: 12px 12px;
    }
    .roleTitle {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 7px;
        color: white!important;
    }
    .roleUser {
        font-weight: 400;
        color: #B0B7C3;
        font-size: 14px;
        transition: all 0.3s ease;
        color: white!important;
    }
    i {
        color: white!important;
        cursor: pointer;
    }
</style>   

<script>
    let roles = @json($roles);

    function editRoleModal(id) {

        let role = roles.find(role => role.id == id);

        $('#editRole_form').attr('action', `/user/role/edit/${id}`);

        $('#editRole_name').val(role.title);

        $('#editRole_check_manage_users').attr('checked', role.manage_users ? true : false);
        $('#editRole_check_settings').attr('checked', role.settings  ? true : false);
        $('#editRole_check_support').attr('checked', role.support  ? true : false);
        $('#editRole_check_monitoring').attr('checked', role.monitoring  ? true : false);
        $('#editRole_check_surveys').attr('checked', role.surveys  ? true : false);
        $('#editRole_check_wishes').attr('checked', role.wishes  ? true : false);
        $('#editRole_check_ingame_integration').attr('checked', role.ingame_integration  ? true : false);
        $('#editRole_check_bugreports').attr('checked', role.bugreports ? true : false);

        $('#modal-editRole').modal('show');
    }
    function deleteRoleModal(id) {
        $('#modal-deleteRole_form').attr('action', `user/role/delete/${id}`);
        $('#modal-deleteRole').modal('show')
    }

    function deleteEmployeeModal(user_id, project_id) {
        $('#modal-deleteEmployee_form').attr('action', `/user/delete/${user_id}/${project_id}`);
        $('#modal-deleteEmployee').modal('show')
    }
</script>

<!-- Edit Employee Modal Ajax -->
<script type="text/javascript">
    let employees = JSON.parse('<?= json_encode($users) ?>');

    function editEmployeeModal(id) {
        // Filter EmployeeData by id
        let employee = employees.filter(employee => employee.id == id)[0];

        let roles =  {'firstLvlSupp': '1', 'secondLvlSupp': '2', 'thirdLvlSupp': '3', 'Jesus': '4'};

        // Set Modal Input Values
        $('#editEmployee_fullname').val(employee.fullname);
        $('#editEmployee_email').val(employee.email);
        $('#editEmployee_username').val(employee.username);
        $('#editEmployee_userRights').val(roles[employee.userRole]);

        customProfileImageEdit.changeInlineImage(employee.avatar)

        $("#editEmployeeForm").attr('action', '/user/edit/' + employee.id);
        if(employee.userProfileImage && employee.userProfileImage != '')
            $("#eE_appViewImagePreview").prop('src', employee.userProfileImage);
        else
            $("#eE_appViewImagePreview").prop('src', '');

        // Open Modal
        $('#editEmployeeModal').modal('show');
    }

</script>