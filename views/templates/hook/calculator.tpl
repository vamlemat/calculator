{* SIN FORM, SIN INPUT qty. Solo el bloque de la calculadora *}
<div id="calculator"
     class="row calculator"
     {if isset($product.price_amount)}data-package-price="{$product.price_amount}"{/if}
     {if isset($calculator_packaging)}data-package-size="{$calculator_packaging}"{/if}
     {if isset($product.price)}data-unit-price="{$product.price}"{/if}
     {if isset($product.unity)}data-unit="{$product.unity}"{/if}>

  <div class="calculator-container row">
    <div class="col-md-7 col-sm-12">
      <div class="col-sm-12 unit-entry">
        <label>Área</label>
        <input id="calculator-user-input" type="text" class="unit" autocomplete="off" placeholder="0.00" value="">
        <span class="suffix">{$product.unity}</span>
      </div>
      <div id="packaging-count" class="col-sm-12"></div>
    </div>

    <div class="col-sm-12 col-md-5 price-wrapper">
      <div id="calculator-message">
        Introduzca el área que desee comprar
      </div>
    </div>
  </div>
</div>
