<script>
    class PackageCalculator {

        constructor(packageSize, packagePrice, unit){
            this.disableDefaultControls();
            this.packageSize = packageSize;
            this.packagePrice = packagePrice;
            this.unit = unit;
            this.reset();
        }

        disableDefaultControls() {
            document.getElementsByClassName('product-add-to-cart')[0].classList.add('d-none');
        }

        initialize() {
            this.packagingCountDiv = document.getElementById('packaging-count');
            this.calculatorDiv = document.getElementById('calculator');
            // this.calculatorInformation = document.getElementById('calculator-information');
            this.messageDiv = document.getElementById('calculator-message');
            this.areaInput = document.getElementById('calculator-user-input'); 
            this.quantityInput = document.getElementById('quantity_wanted');
            this.addToCartButton = document.getElementById('add-to-cart-button');
            this.packagingCountDiv.innerHTML = '';
        }

        isValidInput() {
            return this.areaInput
                && this.areaInput.value
                && !isNaN(this.areaInput.value)
                && this.areaInput.value > 0;
        }

        isEmptyOrZeroInput() {
            return !this.areaInput.value 
                || this.areaInput.value == 0
        }

        canCalculate() {
            return this.isValidInput() && !isNaN(this.packageSize);
        }

        reset() {
            this.initialize();
            this.setMessage("Introduzca el área que desee comprar", '');
            this.quantityInput.value = 0;
            this.areaInput.value = '';
            this.disableError();
            this.addToCartButton.disabled = true;
        }

        update() {
            this.initialize();
            let packages = this.totalPackages;
            let msg = this.renderPriceMessage(
                this.totalPrice(packages).toFixed(2),
                this.areaPrice.toFixed(2),
                this.unit);

            this.quantityInput.value = packages;
            this.packagingCountDiv.innerHTML = this.renderPackagingCount(packages, this.totalMeters.toFixed(2), this.unit);
            this.setMessage(msg, '');
        }

        setMessage(msg, cssClass) {
            this.messageDiv.innerHTML = msg;
            this.messageDiv.className = cssClass;
        }

        enableError() {
            this.calculatorDiv.classList.add('error');
            this.addToCartButton.disabled = true;
        }

        disableError() {
            this.calculatorDiv.classList.remove('error');
            this.addToCartButton.disabled = false;
        }

        renderPackagingCount(packages, totalMeters, unit) {
            return `${ packages } paquete(s) = ${ totalMeters }${ unit }`;
        }

        renderPriceMessage(totalPrice, areaPrice, unit) {
            return `
            <div id="calculator-price">${ totalPrice }€</div>
            <div id="calculator-area-price">(${ areaPrice }€/${ unit })</div>`;
        }

        get areaPrice() {
            return this.packagePrice / this.packageSize;
        }

        get totalMeters() {
            return this.totalPackages * this.packageSize;
        }

        totalPrice(packages) {
            return packages * this.packagePrice;
        }
        get totalPackages() {
            let meters = parseFloat(this.areaInput.value);
            return Math.ceil(meters / this.packageSize);
        }
    }

    function initializeCalc() {
        const calculator = new PackageCalculator({$calculator_packaging}, {$product["price_amount"]}, '{$calculator_unit}');
        $(document).on('keyup', calculator.areaInput, function (e) {
            calculator.disableError();
            if(calculator.isEmptyOrZeroInput()) {
                calculator.enableError();
                calculator.setMessage("Introduzca un número mayor que cero.", 'error');
                return;
            }
            if(!calculator.canCalculate()) { 
                calculator.setMessage("Por favor, introduzca un número válido (0.00)", '');
                calculator.enableError();
            }
            else {
                calculator.update();
            }
        });
    }
    document.addEventListener("DOMContentLoaded", function(event) {
        //initializeCalc();
        //initialize_calculators();
    });
</script>
