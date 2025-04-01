@include('layouts.header')
<style>
*{
    margin:0;
    padding:0;
    text-decoration:none;
    list-style:none;
}
</style>

<div class="page_content">
    <section class="pricing-wrp">
            <div class="container">
                <div class="error-bx d-flex justify-content-center">
                    <h6><img src="images/alert-ic.svg" alt="">Angebot: Hood ONE für nur 6.99€ pro Monat.</h6>
                </div>
            </div>

            <div class="main-package-box">
                <h1>Entfessle die wahre <br>Macht von Hood</h1>

                <div class="row">
                    <div class="col-md-3 col-sm-12">
                    @if(App\Helpers\Subscription::hasActiveSubscription('one'))
                        <div class="getting-started">
                    @else
                        <div class="getting-started pay-productOpen">
                    @endif
                            <div class="get-lg">
                                <img src="/assets/images/productLogos/HoodFree.svg" style="max-width: 120px;">
                                {{-- <label>Aktiv</label> --}}
                            </div>
                            <h2>Getting <br>Started</h2>
                            <h6>Dauerhaft kostenlos *</h6>

                            <ul>
                                <li>GameConnect: 1 Server</li>
                                {{-- <li>GameConnect: 1 Monat Statistiken</li> --}}
                                <li>Discord Support Bot</li>
                                <li>Bugreport System</li>
                            </ul>

                            <a href="#" class="btn-main">Aktiviert</a>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12">
                    @if(App\Helpers\Subscription::hasActiveSubscription('one'))
                        <div class="price-package-wrp pay-productOpen" style="height: 100%;position:releative;">
                    @else
                        <div class="price-package-wrp" style="height: 100%;position:releative;">
                    @endif
                            <div class="package-head">
                                <img src="/assets/images/productLogos/HoodOne.png" style="max-width: 120px;">

                                <div class="pay-option">
                                    <img src="/assets/images/background/paymentMethods.png" alt="">
                                </div>
                            </div>

                            <div class="package-namebx">
                                <h2>Hol das beste aus <br> deinem Server</h2>
                                <h5><span style="text-decoration: line-through;">9.99€</span> <strong>6.99€</strong> nur für begrenzte Zeit</h5>
                            </div>

                            <div class="package-include">
                                <ul>
                                    <li>GameConnect: Unlimited Server</li>
                                    {{-- <li>GameConnect: 6 Monate Statiken</li> --}}
                                    <li>GameConnect: Server Starten/Stoppen</li>
                                    <li>Live Chat System</li>
                                    <li>Discord Support Bot</li>
                                    <li>Support Text Snippets</li>
                                    <li>Supportzeiten</li>
                                    <li>Webhook Discord Antworten</li>
                                    <li>Bugreport System</li>
                                    <li>Monitoring System</li>
                                    <li>Nutzerwünsche System</li>
                                    <li>Changelog System</li>
                                    <li>Kein Branding</li>
                                    <li>Customize Livechat</li>
                                    <li>Customize Communitycenter</li>
                                    <li>Customize Logo</li>
                                    <li>Customize Subdomain</li>
                                </ul>
                            </div>
                            @if(App\Helpers\Subscription::hasActiveSubscription('one'))
                                <a href="/products/checkout/one" class="btn-main" style="bottom: 44px; position: absolute;">Jetzt Verlängern</a>
                            @else
                                <a href="/products/checkout/one" class="btn-main" style="bottom: 44px; position: absolute;">Jetzt Upgraden</a>
                            @endif
                        </div>
                    </div>
                </div>

                <img src="images/price-shp1.svg" alt="" class="priceshp1">
                <img src="images/price-shp2.svg" alt="" class="priceshp2">
            </div>

            <div class="some-note">
                <p>Du hast <strong>keine Vertragslaufzeit</strong> und das Produkt <strong>verlängert</strong> sich <strong>nicht automatisch</strong></p>
            </div>
    </section>




    {{-- <div class="row pl-2 pr-2">
        <!-- Hood One -->
        <div class="col-12 pl-0 pr-0">
            <div class="productOneItemContent d-flex align-items-center">
                    <div class="col-6 d-flex justify-content-end">
                        <div>
                            <a style="font-weight: 600;font-size: 17px;color: #2A85FF;">Günstiger als ein Fortnite Skin 🤫</a><br>
                            <a style="font-weight: 700;font-size: 32px;color: #EFEFEF;">Gönn dir alle<br>Hood Funktionen</a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div>
                            <a style="font-weight: 400;font-size: 16px;color: #7F8596;">Hood ONE kombiniert alle <br>Funktionen in einem Produkt.</a><br>
                            <a class="btn button-primary-orange mt-2" href="/products/checkout/one">9,99€ / Monat</a><br>

                            <div style="margin-left: 60px; margin-top: 8px;">
                            @if(App\Helpers\Subscription::hasActiveSubscription('one'))
                            <a class="infoTag mt-2">{{ App\Helpers\Subscription::hasActiveSubscription('one') ? 'Läuft bis '. date('d.m.Y', strtotime(App\Helpers\Subscription::showRuntime("one"))) :  'Nicht Aktiv'}}</a>
                            @else
                            <a class="infoTag mt-2" href="/products/checkout/one"target="_blank">Jetzt Informieren</a>
                            @endif
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <div class="row pl-2 pr-2">
        <!-- Hood Monitoring -->
        <div class="col-12 productItem">
            <div class="productItemContent">
                <div class="row d-flex align-items-center" style="height:100%">
                    <div class="col-xl-2 d-flex justify-content-start pl-0 pr-1">
                        <img class="productLogo" src="/assets/images/productLogos/MonitoringWhite.png">
                    </div>
                    <div class="col-xl-6 pl-0 pr-1 mobileFriendlyProductFeatures">
                        <div class="row">
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Uptimepage</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Multi Alertsystem</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Tagesberichte</a>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Wartungsmodus</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Statistiken</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Keine Begrenzung</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col mr-4 d-flex align-items-center justify-content-end paymentButtonArea">
                        <div>
                            <a class="btn button-primary-orange" href="/products/checkout/monitoring">ab 3,99€ / Monat</a>
                            
                            <div class="d-flex justify-content-center mt-2">
                                @if(App\Helpers\Subscription::hasActiveSubscription('monitoring'))
                                <a class="infoTag">{{ App\Helpers\Subscription::hasActiveSubscription('monitoring') ? 'Läuft bis '. date('d.m.Y', strtotime(App\Helpers\Subscription::showRuntime("monitoring"))) :  'Nicht Aktiv'}}</a>
                                @else
                                <a class="infoTag" href="https://wehood.io/products/monitoring">Jetzt Informieren</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hood Support+ -->
        <div class="col-12 productItem">
            <div class="productItemContent">
                <div class="row d-flex align-items-center" style="height:100%">
                    <div class="col-xl-2 d-flex justify-content-start pl-0 pr-1">
                        <img class="productLogo" src="/assets/images/productLogos/SupportWhite.png">
                    </div>
                    <div class="col-6 pl-0 pr-1 ">
                        <div class="row">
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Live Chat</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Text Snippets</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Ticket System</a>
                                </div>
                            </div>
                        </div>
                         <div class="row mt-2">
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Statistiken</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Multi Agent Ticket</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Discord Webhook</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col mr-4 d-flex align-items-center justify-content-end paymentButtonArea">
                        <div>
                            <a class="btn button-primary-orange" href="/products/checkout/support">ab 3,99€ / Monat</a>
                            <div class="d-flex justify-content-center mt-2">
                                @if(App\Helpers\Subscription::hasActiveSubscription('support'))
                                <a class="infoTag">{{ App\Helpers\Subscription::hasActiveSubscription('support') ? 'Läuft bis '. date('d.m.Y', strtotime(App\Helpers\Subscription::showRuntime("support"))) :  'Nicht Aktiv'}}</a>
                                @else
                                <a class="infoTag" href="https://wehood.io/products/support"target="_blank">Jetzt Informieren</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hood Branding -->
        <div class="col-12 productItem">
            <div class="productItemContent">
                <div class="row d-flex align-items-center" style="height:100%">
                    <div class="col-xl-2 d-flex justify-content-start pl-0 pr-1">
                        <img class="productLogo" src="/assets/images/productLogos/BrandingWhite.png">
                    </div>
                    <div class="col-6 pl-0 pr-1 ">
                        <div class="row">
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Eigenes Logo</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Eigene Subdomain</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="featureItem d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <a>Kein Branding</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col mr-4 d-flex align-items-center justify-content-end paymentButtonArea">
                        <div>
                            <a class="btn button-primary-orange" href="/products/checkout/branding">ab 6,99€ / Monat</a>
                            
                            <div class="d-flex justify-content-center mt-2">
                                @if(App\Helpers\Subscription::hasActiveSubscription('branding'))
                                <a class="infoTag">{{ App\Helpers\Subscription::hasActiveSubscription('branding') ? 'Läuft bis '. date('d.m.Y', strtotime(App\Helpers\Subscription::showRuntime("branding"))) :  'Nicht Aktiv'}}</a>
                                @else
                                <a class="infoTag" href="https://wehood.io/products/branding"target="_blank">Jetzt Informieren</a>
                                @endif                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div> --}}


