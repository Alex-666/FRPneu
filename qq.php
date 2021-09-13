<html><head></head><body><div class="simplecheckout-right-column"><div class="simplecheckout-block" id="simplecheckout_customer">
    <div class="checkout-heading panel-heading"><span>Kupující</span><span class="checkout-heading-button"><a href="javascript:void(0)" data-onclick="openLoginBox">Registrovaný zákazník</a></span></div>
    <div class="simplecheckout-block-content">
                  <fieldset class="form-horizontal">
          <div class="form-group  row-customer_customer_group_id">
    <label class="control-label col-sm-4" for="customer_customer_group_id">Typ zákazníka</label>
    <div class="col-sm-8">
              <select class="form-control" name="customer[customer_group_id]" id="customer_customer_group_id" data-theme="bootstrap" data-onchange="reloadAll">
                      <option value="1">Fyzická osoba</option>
                      <option value="2">Pneuservis</option>
                      <option value="3" selected="selected">Právnická osoba</option>
                  </select>
                      </div>
 </div>

          <div class="form-group required row-customer_firstname">
    <label class="control-label col-sm-4" for="customer_firstname">Jméno</label>
    <div class="col-sm-8">
              <input class="form-control" type="text" name="customer[firstname]" id="customer_firstname" value="" placeholder="" data-reload-payment-form="true">
                    <div class="simplecheckout-rule-group" data-for="customer_firstname">
                      <div style="display:none;" data-for="customer_firstname" data-rule="byLength" class="text-danger simplecheckout-error-text simplecheckout-rule" data-length-min="1" data-length-max="32" data-required="true">Jméno musí být od 1 až 32 znaků!</div>
                  </div>
                </div>
 </div>

          <div class="form-group required row-customer_lastname">
    <label class="control-label col-sm-4" for="customer_lastname">Příjmení</label>
    <div class="col-sm-8">
              <input class="form-control" type="text" name="customer[lastname]" id="customer_lastname" value="" placeholder="" data-reload-payment-form="true">
                    <div class="simplecheckout-rule-group" data-for="customer_lastname">
                      <div style="display:none;" data-for="customer_lastname" data-rule="byLength" class="text-danger simplecheckout-error-text simplecheckout-rule" data-length-min="1" data-length-max="32" data-required="true">Příjmení musí být od 1 až 32 znaků!</div>
                  </div>
                </div>
 </div>

          <div class="form-group required row-customer_telephone">
    <label class="control-label col-sm-4" for="customer_telephone">Telefon</label>
    <div class="col-sm-8">
              <input class="form-control" type="tel" name="customer[telephone]" id="customer_telephone" value="" placeholder="" data-reload-payment-form="true">
                    <div class="simplecheckout-rule-group" data-for="customer_telephone">
                      <div style="display:none;" data-for="customer_telephone" data-rule="byLength" class="text-danger simplecheckout-error-text simplecheckout-rule" data-length-min="3" data-length-max="32" data-required="true">Telefon musí být od 3 do 32 znaků!</div>
                  </div>
                </div>
 </div>

          <div class="form-group required row-customer_email">
    <label class="control-label col-sm-4" for="customer_email">E-mail</label>
    <div class="col-sm-8">
              <input class="form-control" type="email" name="customer[email]" id="customer_email" value="" placeholder="" data-reload-payment-form="true">
                    <div class="simplecheckout-rule-group" data-for="customer_email">
                      <div style="display:none;" data-for="customer_email" data-rule="api" class="text-danger simplecheckout-error-text simplecheckout-rule" data-method="checkEmailForUniqueness" data-filter="customer_register" data-filter-value="0" data-required="true">Vyberte e-mailovou adresu je již zaregistrována!</div>
                      <div style="display:none;" data-for="customer_email" data-rule="regexp" class="text-danger simplecheckout-error-text simplecheckout-rule" data-regexp=".+@.+" data-required="true">Chyba v adrese e-mailu!</div>
                  </div>
                </div>
 </div>

          <div class="form-group  row-customer_ico">
    <label class="control-label col-sm-4" for="customer_ico">IČO</label>
	<span class="Ico"></span>
    <div class="col-sm-8">
              <input class="form-control" type="text" name="customer[ico]" id="customer_ico" value="" placeholder="" data-reload-payment-form="true">
                    <div class="simplecheckout-rule-group" data-for="customer_ico">
                      <div style="display:none;" data-for="customer_ico" data-rule="notEmpty" class="text-danger simplecheckout-error-text simplecheckout-rule" data-not-empty="1">Napište prosím IČO firmy</div>
                  </div>
                </div>
 </div>

          <div class="form-group  row-customer_dic">
    <label class="control-label col-sm-4" for="customer_dic">DIČ</label>
    <div class="col-sm-8">
              <input class="form-control" type="text" name="customer[dic]" id="customer_dic" value="" placeholder="" data-reload-payment-form="true">
                    <div class="simplecheckout-rule-group" data-for="customer_dic">
                      <div style="display:none;" data-for="customer_dic" data-rule="notEmpty" class="text-danger simplecheckout-error-text simplecheckout-rule" data-not-empty="1">Napište prosím DIČ firmy</div>
                  </div>
                </div>
 </div>

          </fieldset>
      </div>
</div><div class="simplecheckout-block" id="simplecheckout_payment_address">
    <div class="simplecheckout-block-content">
          <fieldset class="form-horizontal">
          <div class="form-group required row-payment_address_company">
    <label class="control-label col-sm-4" for="payment_address_company">Firma</label>
    <div class="col-sm-8">
              <input class="form-control" type="text" name="payment_address[company]" id="payment_address_company" value="" placeholder="" data-reload-payment-form="true">
                    <div class="simplecheckout-rule-group" data-for="payment_address_company">
                      <div style="display:none;" data-for="payment_address_company" data-rule="notEmpty" class="text-danger simplecheckout-error-text simplecheckout-rule" data-not-empty="1" data-required="true">Napište prosím název firmy!</div>
                  </div>
                </div>
 </div>

          <div class="form-group required row-payment_address_country_id">
    <label class="control-label col-sm-4" for="payment_address_country_id">Stát</label>
    <div class="col-sm-8">
              <select class="form-control" name="payment_address[country_id]" id="payment_address_country_id" data-theme="bootstrap" data-onchange="reloadAll">
                      <option value=""> --- Prosím vyberte --- </option>
                      <option value="56" selected="selected">Česká republika</option>
                      <option value="81">Německo</option>
                      <option value="189">Slovenská republika</option>
                  </select>
                    <div class="simplecheckout-rule-group" data-for="payment_address_country_id">
                      <div style="display:none;" data-for="payment_address_country_id" data-rule="notEmpty" class="text-danger simplecheckout-error-text simplecheckout-rule" data-not-empty="1" data-required="true">Prosím vyberte stát!</div>
                  </div>
                </div>
 </div>

          <div class="form-group required row-payment_address_zone_id">
    <label class="control-label col-sm-4" for="payment_address_zone_id">Okres</label>
    <div class="col-sm-8">
              <select class="form-control" name="payment_address[zone_id]" id="payment_address_zone_id" data-theme="bootstrap" data-onchange="reloadAll">
                      <option value="" selected="selected"> --- Prosím vyberte --- </option>
                      <option value="890">Jihočeský</option>
                      <option value="891">Jihomoravský</option>
                      <option value="892">Karlovarský</option>
                      <option value="893">Královehradecký</option>
                      <option value="894">Liberecký</option>
                      <option value="895">Moravskoslezský</option>
                      <option value="896">Olomoucký</option>
                      <option value="897">Pardubický</option>
                      <option value="898">Plzeňský</option>
                      <option value="899">Praha</option>
                      <option value="900">Středočeský</option>
                      <option value="889">Ústecký</option>
                      <option value="901">Vysočina</option>
                      <option value="902">Zlínský</option>
                  </select>
                    <div class="simplecheckout-rule-group" data-for="payment_address_zone_id">
                      <div style="display:none;" data-for="payment_address_zone_id" data-rule="notEmpty" class="text-danger simplecheckout-error-text simplecheckout-rule" data-not-empty="1" data-required="true">Prosím, vyberte okres!</div>
                  </div>
                </div>
 </div>

          <div class="form-group required row-payment_address_city">
    <label class="control-label col-sm-4" for="payment_address_city">Město</label>
    <div class="col-sm-8">
              <input class="form-control" type="text" name="payment_address[city]" id="payment_address_city" value="" placeholder="" data-onchange="reloadAll">
                    <div class="simplecheckout-rule-group" data-for="payment_address_city">
                      <div style="display:none;" data-for="payment_address_city" data-rule="byLength" class="text-danger simplecheckout-error-text simplecheckout-rule" data-length-min="2" data-length-max="128" data-required="true">Město by mělo být od 2 do 128 znaků!</div>
                  </div>
                </div>
 </div>

          <div class="form-group required row-payment_address_postcode">
    <label class="control-label col-sm-4" for="payment_address_postcode">PSČ</label>
    <div class="col-sm-8">
              <input class="form-control" type="text" name="payment_address[postcode]" id="payment_address_postcode" value="" placeholder="" data-onchange="reloadAll">
                    <div class="simplecheckout-rule-group" data-for="payment_address_postcode">
                      <div style="display:none;" data-for="payment_address_postcode" data-rule="byLength" class="text-danger simplecheckout-error-text simplecheckout-rule" data-length-min="2" data-length-max="10" data-required="true">PSČ musí být od 2 až 10 znaků!</div>
                  </div>
                </div>
 </div>

          <div class="form-group required row-payment_address_address_1">
    <label class="control-label col-sm-4" for="payment_address_address_1">Ulice a číslo popisné</label>
    <div class="col-sm-8">
              <input class="form-control" type="text" name="payment_address[address_1]" id="payment_address_address_1" value="" placeholder="" data-reload-payment-form="true">
                    <div class="simplecheckout-rule-group" data-for="payment_address_address_1">
                      <div style="display:none;" data-for="payment_address_address_1" data-rule="byLength" class="text-danger simplecheckout-error-text simplecheckout-rule" data-length-min="3" data-length-max="128" data-required="true">Ulice a číslo popisné musí být od 3 do 128 znaků!</div>
                  </div>
                </div>
 </div>

          </fieldset>
              <input type="hidden" name="payment_address[current_address_id]" id="payment_address_current_address_id" value="0">
      </div>
      <div class="simplecheckout-customer-same-address">
      <label><input type="checkbox" name="address_same" value="1" checked="checked" data-onchange="reloadAll">Doručovácí adresa je stejná jako fakturáční adresa</label>
    </div>
  </div><div class="simplecheckout-block" id="simplecheckout_comment">
          <div class="checkout-heading panel-heading">Poznámka</div>
        <div class="simplecheckout-block-content">
      <textarea class="form-control" name="comment" id="comment" placeholder="Poznámka" data-reload-payment-form="true"></textarea>
    </div>
</div></div></body></html>


<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  
  
  <script>
  
  
function getXmlString(xml) {
  if (window.ActiveXObject) { return xml.xml; }
  return new XMLSerializer().serializeToString(xml);
}

  $('#customer_ico').focusout(function(){
  var value = $(this).val();
    
  $.ajax({
  url: "https://www.frpneu.cz/ico-info.php?ico="+value,
  dataType: "xml",
}).done(function(result) {
	
var str = getXmlString(result)

/*FIRMA */
var regexp = /<are:Obchodni_firma>(.*?)<\/are:Obchodni_firma>/g;
var matchAll = str.matchAll(regexp);
matchAll = Array.from(matchAll);
var firstMatch = matchAll[0];

if (typeof(firstMatch) != "undefined"){
$("#payment_address_company").val(firstMatch[1])
}

/* PSK */
var regexp = /<dtt:PSC>(.*?)<\/dtt:PSC>/g;
var matchAll = str.matchAll(regexp);
matchAll = Array.from(matchAll);
var firstMatch = matchAll[0];

if (typeof(firstMatch) != "undefined"){
$("#payment_address_postcode").val(firstMatch[1])
 }
 
/* CITY */
var regexp = /<dtt:Nazev_mestske_casti>(.*?)<\/dtt:Nazev_mestske_casti>/g;
var matchAll = str.matchAll(regexp);
matchAll = Array.from(matchAll);
var firstMatch = matchAll[0];

if (typeof(firstMatch) != "undefined"){
$("#payment_address_city").val(firstMatch[1])
  }
  
/* Street*/
var regexp = /<dtt:Nazev_ulice>(.*?)<\/dtt:Nazev_ulice>/g;
var matchAll = str.matchAll(regexp);
matchAll = Array.from(matchAll);
var firstMatch = matchAll[0];

if (typeof(firstMatch) != "undefined"){
$("#payment_address_address_1").val(firstMatch[1])
}

/* HOUSE */
var regexp = /<dtt:Cislo_domovni>(.*?)<\/dtt:Cislo_domovni>/g;
var matchAll = str.matchAll(regexp);
matchAll = Array.from(matchAll);
var firstMatch = matchAll[0];

if (typeof(firstMatch) != "undefined"){
$("#payment_address_address_1").val($("#payment_address_address_1").val()+', '+firstMatch[1])
  }
  
});
  

  
});  
  </script>