<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#

class UniTwistoForm {
	public static function CreateForm( $twistoPublicKey, $payload, $replyUrl, $uniModulDirUrl ) {
		return <<<HTML
<script>
    document.querySelector('head').innerHTML += '<link rel="stylesheet" href="$uniModulDirUrl/style/css/unitwisto.css" type="text/css"/>';
</script>
<div style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block; border: 0px none; background-color: transparent; z-index: 9999; margin: 0px; padding: 0px; overflow: hidden; visibility: visible;">
    <div class="twisto-layout-wrapper" id="twisto__wrapper" style="display: none">
        <div class="twisto__main--container">
            <img src="$uniModulDirUrl/UniTwistoLogo.png" class="twisto__logo">
            <div id="twisto">
                <h2 class="twisto-title">Okamžitý nákup s platbou později </h2>
                <span id="twisto-try-again-btn">Zkusit znovu</span>
            </div>
            <div class="twisto-checkbox-wrapper-new">
                <label>
                    <input type="checkbox" id="twisto-checkbox-new">
                    <span id="twisto-terms" class="twisto__terms">Souhlasím s
		<span id="twisto-terms-href"
              class="twisto__terms--link">všeobecnými obchodními podmínkami služby Twisto.cz</span> (platba první objednávky do 14 dní od doručení zboží) a se zpracováním osobních údajů pro účely této služby. Podmínkou služby je věk 18+ a převzetí zboží zákazníkem.</span>
                </label>
            </div>
            <span id="twisto-error-alert" class="twisto__error--alert"></span>
            <div id="twisto-modal" class="twisto__modal" style="display: none">
                <div id="twisto-page-overlay" class="modal-close"></div>
                <div id="twisto__popover">
                    <h2>Zboží ihned, platím za 14 dní</h2>
                    <p>Nechce se vám teď vyťukávat číslo karty? Spěcháte a potřebujete nakoupit rychle? Hodilo by se
                        vám zaplatit později? Brnkačka. Celý nákup si můžete okamžitě objednat a zaplatit až 14 dní
                        po doručení zboží. Sami si pak vyberete, jak nákup zaplatíte.</p>
                    <div class="bottom">
                        <a class="more" href="https://www.twisto.cz/" target="_blank">Více o službě Okamžitý nákup s
                            platbou později</a>
                        <span class="modal-btn modal-close button twisto__button">Pokračovat v nákupu</span>
                    </div>
                    <div class="close-cross modal-close"></div>
                </div>
            </div>
            <div class="twisto--bottom">
                <button class="twisto__button" id="twisto-checkout-btn">
                    Potvrzuji objednávku
                </button>
                <a class="twisto__link" href="{$replyUrl}transaction_id=cancel">
                    Další způsoby platby
                </a>
                <span id="twisto--overlay" class="twisto--overlay">
		    <div class="twisto--spinner" id="twisto--spinner">
                <svg width="40" height="40" x="0px" y="0px" viewBox="0 0 20 20">
                    <circle class="outer" cx="10" cy="10" r="9.5" stroke-width="1" stroke="#ffffff" fill="none"/>
                    <circle class="inner" cx="10" cy="10" r="7.5" stroke-width="3" stroke="#5ad16a" fill="none"/>
  			</svg>
            </div>
			<span id="twisto--overlay--message"></span>
		    </span>
            </div>
        </div>
    </div>
    <div class="twisto__modal--overlay" id="twisto-modal-overlay"></div>
</div>

<script type="text/javascript">
    var _twisto_config = {
        public_key: '$twistoPublicKey',
        script: 'https://static.twisto.cz/api/v2/twisto.js'
    };
    (function (e, g, a) {
        function h(a) {
            return function () {
                b._.push([a, arguments])
            }
        }
        var f = ["check"], b = e || {}, c = document.createElement(a);
        a = document.getElementsByTagName(a)[0];
        b._ = [];
        for (var d = 0; d < f.length; d++) b[f[d]] = h(f[d]);
        this[g] = b;
        c.type = "text/javascript";
        c.async = !0;
        c.src = e.script;
        a.parentNode.insertBefore(c, a);
        delete e.script
    }).call(window, _twisto_config, "Twisto", "script");
</script>

<script type="text/javascript">
    TwistoCheckout = {
        paymentModule: document.getElementById('twisto'),
        terms: document.getElementById('twisto-terms'),
        termsHref: document.getElementById('twisto-terms-href'),
        termsCheckbox: document.getElementById('twisto-checkbox-new'),
        overlay: document.getElementById('twisto--overlay'),
        overlayMessage: document.getElementById('twisto--overlay--message'),
        overlaySpinner: document.getElementById('twisto--spinner'),
        errorAlert: document.getElementById('twisto-error-alert'),
        tryAgainBtn: document.getElementById('twisto-try-again-btn'),
        modalTrigger: document.getElementById('twisto-popover-trigger'),
        checkoutBtn: document.getElementById('twisto-checkout-btn'),
        modal: document.getElementById('twisto-modal'),
        twisto__wrapper: document.getElementById('twisto__wrapper'),

        init: function () {
            this.reset();
            this.paymentModule.onclick = function (e) {
                e.preventDefault();
            };
            this.termsHref.onclick = function (e) {
                var win = window.open('https://www.twisto.cz/podminky/', '_blank');
                win.focus();
            };
            this.checkoutBtn.onclick = this.tryAgainBtn.onclick = function (e) {
                if (TwistoCheckout.termsCheckbox.checked) {
                    TwistoCheckout.showOverlay(true, 'Probíhá vyhodnocení objednávky');
                    TwistoCheckout.checkout('$payload');
                } else {
                    TwistoCheckout.errorAlert.innerText = "Pro použití platební metody Twisto je potřeba souhlasit s podmínkami služby.";
                    TwistoCheckout.errorAlert.style.display = 'block';
                }
            };
             
            var closers = document.querySelectorAll('.modal-close');
            for (var i = closers.length - 1; i >= 0; i--) {
                closers[i].onclick = function (e) {
                    TwistoCheckout.modal.style.display = 'none';
                }
            }
            ;
        },
        checkout: function (payload) {
            Twisto.check(payload, function (response) {
                if (response.status == 'accepted') {
                    // platba byla schválena
                    window.location = '$replyUrl' + 'transaction_id=' + response.transaction_id;
                } else {
                    var reason = response.reason !== null ? response.reason : 'Omlouváme se, platba byla systémem Twisto zamítnuta. Zvolte jinou platební metodu.';
                    TwistoCheckout.showOverlay(false, reason);
                }
            }, function () {
                TwistoCheckout.error();
            });
        },
        showOverlay: function (spinner, message) {
            if (spinner) {
                this.overlaySpinner.style.display = 'block';
            } else {
                this.overlaySpinner.style.display = 'none';
            }
            this.overlayMessage.innerText = message;
            this.overlay.style.display = 'block';
        },
        reset: function () {

            this.tryAgainBtn.style.display = 'none';
            this.errorAlert.style.display = 'none';
        },
        error: function (reason) {
           if (typeof(reason) == 'undefined')
                reason = 'Došlo k chybě při odesílání objednávky na platební bránu Twisto. Zkuste to prosím znovu, případně si vyberte jinou platební metodu.';
            this.errorAlert.innerText = reason;
            this.errorAlert.style.display = 'block';
            this.overlay.style.display = 'none';
        }
    };
    TwistoCheckout.init();
</script>
HTML;
	}
}