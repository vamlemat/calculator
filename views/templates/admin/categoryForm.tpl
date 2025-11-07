<div class="form-wrapper">
  <div class="form-group">
    <label class="control-label col-lg-3">
      Calculadora activada
    </label>

    <div class="col-lg-9">
      <span class="switch prestashop-switch fixed-width-lg">
        <input
          type="radio"
          name="calculator_active"
          id="calculator_active_on"
          value="1"
          {if $calculator_active == 1} checked="checked"{/if}
        />
        <label for="calculator_active_on">Sí</label>
        <input
          type="radio"
          name="calculator_active"
          id="calculator_active_off"
          value="0"
          {if $calculator_active == 0} checked="checked"{/if}
        />
        <label for="calculator_active_off">No</label>
        <a class="slide-button btn"></a>
      </span>

      <p class="help-block">
        Esto es para introducir si esta activo o no
      </p>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-lg-3">
      Unidades de medidas de la calculadora
    </label>

    <div class="col-lg-3">
      <input
        type="text"
        name="calculator_unit"
        id="calculator_unit"
        value="{$calculator_unit}"
        class=""
      />

      <p class="help-block">
        Solo se aplica a esta categoria
      </p>
    </div>
  </div>
</div>
