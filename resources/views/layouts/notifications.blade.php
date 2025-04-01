@if (session('error'))
    @php
        echo '<script>let notificationMessage = "'.session('error').'"</script>';
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if(!notificationMessage) return;
            new Notification('error', notificationMessage, '5000')
        });
    </script>
@endif

@if (session('success'))
    @php
        echo '<script>let notificationMessage = "'.session('success').'"</script>';
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if(!notificationMessage) return;
            new Notification('success', notificationMessage, '5000')
        });
    </script>
@endif