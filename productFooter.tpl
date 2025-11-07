<script>
	document.addEventListener("DOMContentLoaded", function(event) {

		const metersPerPackage = parseFloat({$product["features"][62]["value"]});
		const inputSelector = '#ppbs_widget input.unit';

		const packageDiv = createPackageDiv();

		var calcDiv = document.getElementById('ppbs_widget');

		function createPackageDiv() {
			var packageDiv = document.createElement('div');
			packageDiv.id = "packages";
			return packageDiv;
		}

		$(document).on('keyup', inputSelector, function (e) {
			ensureCalculatorLoaded();

			var input = document.querySelector(inputSelector);
			var isValidInput = input && input.value && !isNaN(input.value);

			if(isValidInput && !isNaN(metersPerPackage)) {
				var meters = parseFloat(input.value);
				var	packages = Math.ceil(meters / metersPerPackage); 
				var content = packages + ' paquetes (' + meters + ' m2)';
				packageDiv.innerHTML = content;
			}
			else {
				packageDiv.innerHTML = '';
			}
		});

		function ensureCalculatorLoaded() {
			if(!calcDiv) {
				calcDiv = document.getElementById('ppbs_widget');
				calcDiv.appendChild(packageDiv);
			}
		}
	});
</script>