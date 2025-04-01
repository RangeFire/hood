<!-- Audio Notification -->
@if((new App\Models\Project)->findProject(session('activeProject'))->soundNotification == true)
    <script>
        function triggerSoundAlert() {
            document.getElementById("notificationAudio").play();
            var audio = document.getElementById("notificationAudio");
            audio.volume = 0.2;
        }
    </script>
@endif
<script>
    function uploadProfilImage() {
        const clientChatBubble = filestack.init("AhY2QLM5BQViijG7RYx4iz");
        const clientChatBubbleOptions = {
             fromSources: ["local_file_system"],
             maxSize: 1100000,
             lang: 'de',
             accept: ["image/*"],
             storeTo: {
                location: 's3',
                path: '/hood/'
            },
            transformations: {
                crop: true,
                circle: true,
                rotate: true
            },
            onUploadDone: (res) => saveChatBubbleImage(res),
        };
        clientChatBubble.picker(clientChatBubbleOptions).open();
    }

    function saveChatBubbleImage(response) {
        let imageURL = "http://storage.mycraftit.com/" + response.filesUploaded[0].key;
        document.getElementById('profileImageURL').value = imageURL;
        document.getElementById("editProfileImage").submit();
    }

    function setFavoriteUserProject(project_id, user_id) {
        document.getElementById('favoriteProjectId').value = project_id;
        document.getElementById('userId').value = user_id;
        document.getElementById("setFavoriteProject_form").submit();
    }

    function asyncCountTickets() {
        $.get('/tickets/countOpen', function(data) {
            if(data == 0) {
                return $('#open_tickets_counter').html(data);
            } else {
                $('#open_tickets_counter').html(data);
            }
        });
    }

    function callbackHandleSwitchModal(callback) {

        let activeModal = $('.modal.show');

        if(activeModal.length > 0) {
            activeModal.modal('hide')/*.on('hidden.bs.modal', function (e) { */
                setTimeout(() => {
                    callback();
                }, 1000);
            //})
        }else {
            callback();
        }

    }

    $(() => {

        asyncCountTickets();
        setInterval(() => {
            asyncCountTickets();
        }, 5000);
        
        /* enables all bootstrap tooltips */
        $('[data-toggle="tooltip"]').tooltip();

        var dataTableInitConfig = {
            pageLength: 10,
            ordering: false,
            "bLengthChange" : false,
            bInfo : false,
            "language": {
                "paginate": {
                    "previous": "zurück",
                    "next": "weiter",
                },
                "search": "Suche:",
                "infoEmpty": "Keine Einträge gefunden",
                "zeroRecords": "Keine Einträge gefunden",
            }
        }

        var table = $('.dataTable').DataTable(dataTableInitConfig);


        /* overwrites config for ordering enable */
        dataTableInitConfig.ordering = true,
        dataTableInitConfig.columnDefs = [
            { orderable: false, targets: [1, 2, 3] }
        ],

        dataTableInitConfig.order = [[0], [4], [5, 'desc']],
        $('.dataTable-order-customers').DataTable().destroy();
        $('.dataTable-order-customers').DataTable(dataTableInitConfig); 

        $('#inputsearch').on('keyup', function () {
            filterSearch();
        });

        $('#select-search-filter').on('change', function () {
            filterSearch();
        });

        const filterSearch = () => {
            var search = $('#inputsearch').val();
            var filter = $('#select-search-filter').val();
            var filterColumn = $('.dataTable').data('filter-column');

            if(filter == 'Alle' || filter == 'Alles') filter = '';

            table.search(search).columns(filterColumn).search(filter).draw();
            
        }

        try {
            filterSearch();
        } catch (error) {}


    });
</script>

