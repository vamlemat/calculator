// /modules/calculator/views/js/front.js
(function ($) {
  'use strict';

  const STORAGE_PREFIX = 'calcState:';
  function getPid() {
    const $form = $('#add-to-cart-or-refresh');
    const v = $form.find('input[name="id_product"]').val();
    return v ? String(v) : 'unknown';
  }
  function loadState(pid) {
    try { const raw = sessionStorage.getItem(STORAGE_PREFIX + pid); return raw ? JSON.parse(raw) : null; }
    catch (_) { return null; }
  }
  function saveState(pid, state) {
    try { sessionStorage.setItem(STORAGE_PREFIX + pid, JSON.stringify(state)); }
    catch (_) {}
  }

  function initCalculators() {
    $('.calculator').each(function () {
      const $wrap = $(this);
      if ($wrap.attr('data-calc-bound') === '1') return;
      $wrap.attr('data-calc-bound', '1');
      new PackagingCalculator($wrap); // eslint-disable-line no-new
    });
  }

  $(document).ready(initCalculators);

  // Observa NUEVOS .calculator sin reinicializar los ya atados
  (function watchForNewCalculators() {
    try {
      let rafId = null;
      const observer = new MutationObserver(() => {
        if (rafId) return;
        rafId = requestAnimationFrame(() => { rafId = null; initCalculators(); });
      });
      observer.observe(document.body, { childList: true, subtree: true });
    } catch (_) {}
  })();

  class PackagingCalculator {
    constructor($calculator) {
      this.$calculator = $calculator;
      this.pid = getPid();
      this.debounceTimer = null;

      this.cacheDom();
      this.bind();
      this.renderInit();
      this.restoreIfAny();
    }

    cacheDom() {
      this.packageSize  = parseFloat(this.$calculator.data('package-size'));
      this.packagePrice = parseFloat(this.$calculator.data('package-price'));
      this.unit         = this.$calculator.data('unit');
      this.unitPrice    = this.$calculator.data('unit-price');

      this.$packagingCount = this.$calculator.find('#packaging-count');
      this.$message        = this.$calculator.find('#calculator-message');
      this.$areaInput      = this.$calculator.find('#calculator-user-input');

      // Form nativo (prioriza el visible por si algún módulo duplica IDs)
        this.$form = $('form#add-to-cart-or-refresh:visible').first();
        if (!this.$form.length) {
          this.$form = $('form[action*="carrito"], form[action*="cart"]').filter(':visible').first();
        }
        // Input de cantidad (el visible primero)
        this.$qtyInput = this.$form.find('#quantity_wanted, input[name="qty"]').filter(':visible').first();

    }

    bind() {
      this.$areaInput.off('input.calculator keyup.calculator change.calculator');
      this.$areaInput.on('input.calculator keyup.calculator change.calculator', () => {
        // Debounce 120 ms para evitar ráfagas
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => this.onUserType(), 120);
      });

      this.$calculator.off('mouseenter.calculator focusin.calculator')
                      .on('mouseenter.calculator focusin.calculator', () => this.cacheDom());
    }

    renderInit() {
      this.disableError();
      if (!this.$areaInput.val()) {
        this.setMessage('Introduzca el área que desee comprar', '');
      }
    }

    restoreIfAny() {
      const st = loadState(this.pid);
      if (!st) return;
      if (typeof st.area === 'string') this.$areaInput.val(st.area);
      if (st.countHtml) this.$packagingCount.html(st.countHtml);
      if (st.msgHtml)   this.$message.html(st.msgHtml);
      if (Number.isFinite(st.packages)) {
        // 👇 Solo fijamos el valor; NO disparamos eventos para no provocar re-render del tema
        this.$qtyInput.val(st.packages);
      }
    }

    onUserType() {
      this.disableError();

      if (this.isEmptyOrZeroInput()) {
        this.enableError();
        this.setMessage('Introduzca un número mayor que cero.', 'error');
        return;
      }
      if (!this.canCalculate()) {
        this.enableError();
        this.setMessage('Por favor, introduzca un número válido', '');
        return;
      }
      this.update();
    }

    update() {
      // Re-cacheo suave por si cambió el form nativo
      this.$form = $('form#add-to-cart-or-refresh:visible').first();
        if (!this.$form.length) {
          this.$form = $('form[action*="carrito"], form[action*="cart"]').filter(':visible').first();
        }
        this.$qtyInput = this.$form.find('#quantity_wanted, input[name="qty"]').filter(':visible').first();


      const packages = this.totalPackages;
      const totalTxt = this.totalPrice(packages).toFixed(2);

      // 👇 Fijamos la cantidad, SIN .trigger('input/change') para evitar parpadeo
      this.$qtyInput.val(packages);

      const countHtml = this.renderPackagingCount(packages);
      const msgHtml   = this.renderPriceMessage(totalTxt);
      this.$packagingCount.html(countHtml);
      this.setMessage(msgHtml, '');

      saveState(this.pid, {
        area: String(this.$areaInput.val() || ''),
        packages,
        countHtml,
        msgHtml
      });
    }

    setMessage(msg, cls) { this.$message.attr('class', cls || '').html(msg); }
    enableError()        { this.$calculator.addClass('error'); }
    disableError()       { this.$calculator.removeClass('error'); }

    isValidInput() {
      const v = this.$areaInput.val();
      if (v === null || v === undefined || v === '') return false;
      const n = parseFloat(String(v).replace(',', '.'));
      return Number.isFinite(n) && n > 0;
    }
    isEmptyOrZeroInput() {
      const v = this.$areaInput.val();
      if (v === null || v === undefined || v === '') return true;
      const n = parseFloat(String(v).replace(',', '.'));
      return !Number.isFinite(n) || n === 0;
    }
    canCalculate() {
      return this.isValidInput() && Number.isFinite(this.packageSize) && this.packageSize > 0;
    }
    get totalPackages() {
      const meters  = parseFloat(String(this.$areaInput.val()).replace(',', '.')) || 0;
      const pkgSize = Number.isFinite(this.packageSize) && this.packageSize > 0 ? this.packageSize : 1;
      return Math.ceil(meters / pkgSize);
    }
    get totalMeters() {
      return this.totalPackages * (Number.isFinite(this.packageSize) ? this.packageSize : 0);
    }
    totalPrice(packages) {
      return (Number.isFinite(this.packagePrice) ? this.packagePrice : 0) * packages;
    }
    renderPackagingCount(packages) {
      return `${packages} paquete(s) = ${this.totalMeters.toFixed(2)}${this.unit}`;
    }
    renderPriceMessage(totalPrice) {
      return `
        <div id="calculator-area-price">${(Number(this.packagePrice)||0).toFixed(2)}€ / Paquete</div>
        <div id="calculator-price">(${totalPrice}€ / total)</div>
      `;
    }
  }
})(jQuery);
