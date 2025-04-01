@include('layouts.header')

<style>
    .nav-side-menu {
        display: none;
    }

    .navbarTopRight {
        display: none;
    }
</style>

    <script>
        window.onload = function() {
            changeRuntime(3);
        };

        function changeRuntime(runtimeMonths) {
            let prices = @json(\App\Helpers\AppHelper::$prices);
            
            let runtime = runtimeMonths;

            if(runtime == 1) {
                document.getElementById("runtimeSpan2").classList.remove("active");
                document.getElementById("runtimeSpan1").classList.add("active");
            } else {
                document.getElementById("runtimeSpan1").classList.remove("active");
                document.getElementById("runtimeSpan2").classList.add("active");
            }

            if(runtime == 1) {
                $('#priceSpan').html(prices.supportMonth);
                $('#runtimeDue').html(Date.today().add(1).months().toString("dd.MM.yyyy"));

                $('#productType').val("supportMonth");
                $('#productName').val("Hood Support - 1 Monat");
                $('#selectRuntime').val(1);
            } else if (runtime == 3) {
                $('#priceSpan').html((prices.supportThreeMonths*3)-1);
                $('#runtimeDue').html(Date.today().add(3).months().toString("dd.MM.yyyy"));
                $('#selectRuntime').val(3);
            } else {
                priceSpan.textContent = 'N/A';
            }
        }
    </script>

    <div class="row" style="margin: 50px;">
        <div class="col-xl-6 d-flex">
            <div class="checkoutCenter">
                <div class="checkout">
                    <a class="goBack" href="/products"><i class="fa-solid fa-left"></i> Zurück</a>
                    <div class="row mt-4">
                        <div class="col-xl-4 mr-3">
                            <img class="productLogo" src="/assets/images/productLogos/Support.png">
                        </div>
                        <div class="col-xl-6">
                            <a class="productSlogan">Booste deine <br>Nutzerbindung</a>
                        </div>
                    </div>

                    <form action="/payment/create" method="post">@csrf
                    <div class="row mt-5 checkoutElement">
                        <div class="col-xl-5">
                            <a class="head">Laufzeit</a><br>
                            <a class="sub mt-1">bis zum <span id="runtimeDue">N/A</span></a>
                        </div>
                        <div class="col-xl-7 checkoutRightSite">
                            <div class="chooseRuntime">
                                <div class="runtimeContainer">
                                    <span class="runtimeSpan" id="runtimeSpan1" onclick="changeRuntime(1)">1 Monat</span>
                                    <span class="runtimeSpan active" id="runtimeSpan2" onclick="changeRuntime(3)">3 Monate</span>
                                </div>
                                {{-- <select style="border: none;background: #31343F!important;" class="form-control" name="selectRuntime" id="selectRuntime" onchange="changeRuntime()">
                                    <option value="1">1 Monat</option>
                                    <option value="3" selected>3 Monate</option>
                                </select> --}}
                                <input type="text" value="3" id="selectRuntime" name="selectRuntime" hidden/> <!-- used to config price in service -->
                                <input type="text" value="supportThreeMonths" id="productType" name="productType" hidden/> <!-- used to config price in service -->
                                <input type="text" value="Hood Support - 3 Monate" id="productName" name="productName" hidden/> <!-- shown in the payment window and mollie -->
                                <input type="text" value="support" name="subscriptionName" hidden/> <!-- Identifier to activate right subscription in db-->
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 checkoutElement">
                        <div class="col-xl-6">
                            <a class="head">Total</a><br>
                            <a class="sub mt-1">Einmalzahlung</a>
                        </div>
                        <div class="col-xl-6 checkoutRightSite">
                            <a class="head" id="priceSpan">00,00€</a><br>
                            <a class="sub mt-1">Inkl. MwSt</a>
                        </div>
                    </div>

                    <div class="row mt-4 checkoutElement">
                        <div class="col-xl-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Ich stimme den CraftIT© GmbH <a href="https://mycraftit.com/legal/agb">AGB</a> zu.
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Ich stimme der Ausführung des Vertrages vor Ablauf der Widerrufsfrist ausdrücklich zu. 
                                Ich habe zur Kenntnis genommen, dass mein Widerrufsrecht 
                                mit Beginn der Ausführung des Vertrags erlischt.
                            </label>
                        </div>
                        </div>
                    </div>


                    <div class="row mt-4 f-flex justify-content-center" style="padding: 0px 10px;">
                        <button type="submit" class="btn button-primary-orange w-100 mb-4">Jetzt bezahlen</button>
                        <img style="max-width: 380px;" src="/assets/images/background/paymentMethods.png">
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-6 featureImage" style="background-image: url(/assets/images/background/monitoringCheckoutFeatures.png)"></div>
    </div>

