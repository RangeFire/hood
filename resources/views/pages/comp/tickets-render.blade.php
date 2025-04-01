<table class="table table-hover">
    <thead>
        <tr>
        <th scope="col">Ticket</th>
        <th scope="col">Art</th>
        <th scope="col">Kategorie</th>
        <th scope="col">Status</th>
        <th scope="col">Name</th>
        <th scope="col">Plattform</th>
        <th scope="col">Supporter</th>
        <th scope="col">Zeit</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($custom_render_tickets as $ticket)
            @php
        
                date_default_timezone_set('Europe/Berlin');
                $targetDate = $ticket->created_at;
                $today = date('Y-m-d H:i:s');
                $interval = $targetDate->diff($today);  
        
                if($interval->format('%d') > 7) {
                    $interval = "am " . date_format($targetDate, "d.m.Y");
                } else if ($interval->format('%d') < 1 && $interval->format('%H') < 1 ) {
                    $interval = "vor " . $interval->format('%i Minuten');                 
                } else if ($interval->format('%d') < 1 && $interval->format('%H') < 24 ) {
                    $interval = "vor " . $interval->format('%H Stunden %i Minuten');                 
                } else if ($interval->format('%d') == 1) {
                    $interval = "vor " . $interval->format('%d Tag');                 
                } else if ($interval->format('%d') > 1) {
                    $interval = "vor " . $interval->format('%d Tagen');                 
                }

                if($ticket->closed == "1") {
                    $status = 'Geschlossen';
                }else if($ticket->leadingOperator) {
                    $status = 'In Bearbeitung';
                }else {
                    $status = 'Offen';
                } 
            
            @endphp
                
            <tr onclick="javascript:location.href='/ticket/{{ $ticket->id }}">
                <td style="cursor: pointer;text-decoration: underline;" onclick="javascript:location.href='/ticket/{{ $ticket->id }}'">{{ $ticket->ticket_title != null ? $ticket->ticket_title : '#' . str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $ticket->type ? ticketType($ticket->type) : '-' }}</td>
                <td>{{ $ticket->category ?: '-' }}</td>
                <td>{{ $status }}</td>
                <td>{{ $ticket->ticket_creator }}</td>
                <td><i class="fa-brands fa-discord"></i></td>
                <td>{{ $ticket->leadingOperator ? ($ticket->leadingOperator->fullname ?: $ticket->leadingOperator->username) : 'Kein Supporter' }}</td>
                {{-- <td><img style="width: 22px;border-radius: 100%;" src="{{ $ticket->leadingOperator ? $ticket->leadingOperator->avatar : ''}}" data-toggle="tooltip" data-placement="top" data-title="{{ $ticket->leadingOperator ? $ticket->leadingOperator->username : '-' }}"></td> --}}
                <td>{{ $interval }}</td>
                <td><a href="/ticket/closeTicket/{{ $ticket->id }}" mr-2><i class="fa-solid fa-lock"></i></a><a href="/ticket/deleteTicket/{{ $ticket->id }}" mr-2><i class="fa-solid fa-trash"></i></a></td>
            </tr>
        
        @endforeach
    </tbody>
</table>