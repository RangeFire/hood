@php
use App\Helpers\Subscription;
@endphp

@if(Subscription::hasFreeTrial())
<div class="trialSection">
    <a class="runtime">Deine Testphase läuft noch {{Subscription::hasFreeTrial() > 1 ? Subscription::hasFreeTrial().' Tage' : Subscription::hasFreeTrial().' Tag'}}</a>
    <a class="link" href="/products">Jetzt freischalten</a>
</div>
@endif