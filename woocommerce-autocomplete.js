(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const fieldSets = [
            { prefix: 'billing', addressField: 'billing_address_1' },
            { prefix: 'shipping', addressField: 'shipping_address_1' },
        ];

        fieldSets.forEach(setupAutocomplete);
    });

    function setupAutocomplete({ prefix, addressField }) {
        const addressInput = document.getElementById(addressField);
        if (!addressInput || typeof google === 'undefined') return;

        const autocomplete = new google.maps.places.Autocomplete(addressInput, {
            fields: ['address_components', 'geometry', 'formatted_address'],
        });

        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();
            if (!place || !place.address_components) return;

            const components = getAddressData(place.address_components);

            // Build street-only address (street number + route)
            const streetParts = [];
            if (components.street_number) streetParts.push(components.street_number);
            if (components.route) streetParts.push(components.route);
            const streetAddress = streetParts.join(' ');

            // Replace the input with only the street part
            addressInput.value = streetAddress;
            addressInput.dispatchEvent(new Event('change'));

            // Autofill other fields
            setValue(`${prefix}_city`, components.locality || components.sublocality);
            setValue(`${prefix}_postcode`, components.postal_code);
            setValue(`${prefix}_state`, components.administrative_area_level_1);
            setValue(`${prefix}_country`, components.country_code?.toUpperCase());
        });
    }

    function getAddressData(addressComponents) {
        const result = {};
        addressComponents.forEach((component) => {
            const type = component.types[0];
            switch (type) {
                case 'street_number':
                    result.street_number = component.long_name;
                    break;
                case 'route':
                    result.route = component.long_name;
                    break;
                case 'postal_code':
                    result.postal_code = component.long_name;
                    break;
                case 'locality':
                    result.locality = component.long_name;
                    break;
                case 'sublocality_level_1':
                    result.sublocality = component.long_name;
                    break;
                case 'administrative_area_level_1':
                    result.administrative_area_level_1 = component.short_name;
                    break;
                case 'country':
                    result.country_code = component.short_name;
                    result.country = component.long_name;
                    break;
            }
        });
        return result;
    }

    function setValue(id, value) {
        if (!value) return;
        const field = document.getElementById(id);
        if (field) {
            field.value = value;
            field.dispatchEvent(new Event('change'));
        }
    }
})();