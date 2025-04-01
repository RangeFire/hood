@include('layouts.header')

<style>
    .nav-side-menu {
        display: none;
    }

    .navbarTopRight {
        display: none;
    }
</style>

    <div class="row" style="margin: 50px;">
        <div class="col-xl-6 d-flex">
            <div class="checkoutCenter">
                <div class="checkout">
                    <a class="goBack" href="/products"><i class="fa-solid fa-left"></i> Zurück</a>
                    <div class="row mt-2">
                        <div class="col-xl-4">
                            <img class="productLogo" src="/assets/images/logo/logoIconTransparent.png">
                        </div>
                        <div class="col-xl-6 d-flex align-items-center">
                            <a class="productSlogan">Unverbindlich Testen</a>
                        </div>
                    </div>

                    <form action="/products/setTrial" method="post">@csrf
                    <div class="row mt-4 checkoutElement">
                        <div class="col-xl-6">
                            <a class="head">Testlaufzeit</a><br>
                            <a class="sub mt-1">3 Tage</a>
                        </div>
                        <div class="col-xl-6 checkoutRightSite">
                            <a class="head">0,00€</a><br>
                            <a class="sub mt-1">Inkl. MwSt</a>
                        </div>
                    </div>

                    <div class="row mt-4" style="padding: 0px 10px;">
                        <button type="submit" class="btn button-primary-orange w-100">Testphase starten</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-6 featureImage" style="background-image: none"></div>
    </div>

