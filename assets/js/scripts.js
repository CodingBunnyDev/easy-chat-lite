document.addEventListener('DOMContentLoaded', function() {
	var toggleAdvancedSettings = document.getElementById('toggle-advanced-settings');
	var advancedSettings = document.getElementById('advanced-settings-section');

	if (toggleAdvancedSettings) {
		toggleAdvancedSettings.addEventListener('change', function() {
			if (this.checked) {
				advancedSettings.style.display = 'block';
			} else {
				advancedSettings.style.display = 'none';
			}
		});
	}

	// Get the settings form
	var settingsForm = document.getElementById('coding-bunny-settings-form');

	// Check if the form exists
	if (settingsForm) {
		settingsForm.addEventListener('submit', function(event) {
			var phoneNumberInput = document.getElementById('whatsapp-number');
			var phoneNumber = phoneNumberInput.value;

			if (!validatePhoneNumber(phoneNumber)) {
				alert('Please enter a valid phone number.');
				event.preventDefault();
			}
		});
	}

	// Function to validate phone numbers
	function validatePhoneNumber(phoneNumber) {
		var phoneRegex = /^[0-9]+$/;
		return phoneRegex.test(phoneNumber);
	}

});

// Disable interaction for elements with the .non-editable class
document.addEventListener("DOMContentLoaded", function() {
	document.querySelectorAll('.non-editable').forEach(function(element) {
		element.addEventListener('click', function(event) {
			event.preventDefault();
		});
	});
});

// Radius value
document.addEventListener('DOMContentLoaded', function() {
	const radiusRangeInput = document.getElementById('icon-radius-range');
	const radiusValueDisplay = document.getElementById('icon-radius-value');

	radiusRangeInput.addEventListener('input', function() {
		radiusValueDisplay.textContent = `${radiusRangeInput.value}px`;
	});
});

// Position value
document.addEventListener('DOMContentLoaded', function() {
	const horizontalRangeInput = document.getElementById('horizontal-position-range');
	const horizontalValueDisplay = document.getElementById('horizontal-position-value');

	horizontalRangeInput.addEventListener('input', function() {
		horizontalValueDisplay.textContent = `${horizontalRangeInput.value}px`;
	});
});

document.addEventListener('DOMContentLoaded', function() {
	const verticalRangeInput = document.getElementById('vertical-position-range');
	const verticalValueDisplay = document.getElementById('vertical-position-value');

	verticalRangeInput.addEventListener('input', function() {
		verticalValueDisplay.textContent = `${verticalRangeInput.value}px`;
	});
});


// Size value
document.addEventListener('DOMContentLoaded', function() {
	const sizeRangeInput = document.getElementById('icon-size-range');
	const sizeValueDisplay = document.getElementById('icon-size-value');

	sizeRangeInput.addEventListener('input', function() {
		sizeValueDisplay.textContent = `${sizeRangeInput.value}px`;
	});
});

// Shadow value
document.addEventListener('DOMContentLoaded', function() {
	const shadowRangeInput = document.getElementById('icon-shadow-range');
	const shadowValueDisplay = document.getElementById('icon-shadow-value');

	shadowRangeInput.addEventListener('input', function() {
		shadowValueDisplay.textContent = `${shadowRangeInput.value}px`;
	});
});

// Tooltip font size value
document.addEventListener('DOMContentLoaded', function() {
	const tooltipRangeInput = document.getElementById('tooltip-font-size-range');
	const tooltipValueDisplay = document.getElementById('tooltip-font-size-value');

	tooltipRangeInput.addEventListener('input', function() {
		tooltipValueDisplay.textContent = `${tooltipRangeInput.value}px`;
	});
});