@php
use App\Helpers\Subscription;
@endphp

  <footer class="flex-shrink-0 py-4 text-white-50 communityCenterFooter">
    <div class="container text-center">
    @if ($project->show_whitelabel == "true" || !Subscription::hasActiveSubscription('branding', $project->id))
      <a href="https://wehood.io"><small style="color:white;">Wir nutzen Hood - Du etwa nicht?</small></a>
    @endif
      <div class="footerLinks">
        <small><a class="footerLink" href="https://mycraftit.com/legal/impress" target="_blank">Impressum</a></small>
        <small><a class="footerLink" href="https://mycraftit.com/legal/privacy" target="_blank">Datenschutz</a></small>
      </div>
    </div>
  </footer>
</body>
</html>