<div>
    <label for="{{ $name }}_{{ $key }}">{{ $label }}</label>
    <div class="input-group">
        <select id="{{ $name }}_{{ $key }}" class="custom-select location-select_{{ $name }}_{{ $key }}" name="{{ $name }}" aria-label="{{ $label }}">
            <option value="">--Select location--</option>
        </select>
        <div class="input-group-append">
            <button id="location-picker_toggle-button_{{ $name }}_{{ $key }}" class="btn btn-outline-secondary" type="button" data-type="select">Add Location</button>
        </div>
    </div>
    @if ($errors->has($name))
        <span class="help-block text-danger"> 
            <strong>{{ $errors->first($name)  }}</strong>
        </span>
    @endif
</div>


@push('custom-scripts')
    <script>

        const value_{{ $name }}_{{ $key }} = '{{ $value }}';
        const locations_{{ $name }}_{{ $key }} = JSON.parse('{!! json_encode($locations) !!}');
        const selectedLocation_{{ $key }} = '{{$selectedValue}}'

        if ( $('#location-picker_toggle-button_{{ $name }}_{{ $key }}').attr('data-type') === 'select') {
            setupSelect('select', '{{ $name }}_{{ $key }}',selectedLocation_{{ $key }});
        }

        $('#location-picker_toggle-button_{{ $name }}_{{ $key }}').on('click', function() {

            let type = this.getAttribute('data-type');

            if (type === 'select') {

                type = 'search';
                this.innerHTML = 'Select Location';

                setupSelect(type, '{{ $name }}_{{ $key }}', selectedLocation_{{ $key }});

            } else if (type === 'search') {

                type = 'select';
                this.innerHTML = 'Add Location';
                setupSelect(type, '{{ $name }}_{{ $key }}');

            }

            this.setAttribute('data-type', type);

        });

        function setupSelect(type, which, selectedValue) {

            if ($(`.location-select_${which}`).hasClass("select2-hidden-accessible")) {
                $(`.location-select_${which}`)
                    .select2('destroy');
            }

            if (type === 'select') {

                $(`.location-select_${which}`)
                    .select2({ theme: 'bootstrap4' });

                clearSelect(which);

                locations_{{ $name }}_{{ $key }}
                    .forEach((loc) => {
                      var theOption = new Option(loc.name, loc.id);

                        $(`.location-select_${which}`)
                            .append(theOption)
                            .trigger('change');
                    });


              if(selectedValue){
                $(`.location-select_${which}`).val(selectedValue).trigger('change');
              }else{
                $(`.location-select_${which}`)
                .val(value_{{ $name }}_{{ $key }})
                .trigger('change');
              }

            } else if (type === 'search') {

                clearSelect(which);

                $(`.location-select_${which}`)
                    .select2({ 
                        theme: 'bootstrap4',
                        ajax: {
                            url: '/cooperative/logistics/locations/search',
                            data: (params) => ({
                                query: params.term,
                            }),
                            processResults: (data) => ({ 
                                results: data.locations 
                            }),
                        }
                    });

            }
            
        }

        function clearSelect(which) {
            $(`.location-select_${which}`)
                .find('option')
                .remove()
                .end()
                .append(`<option value="">--Select location--</option>`)
                .val('')
                .trigger('change');
        }

    </script>
@endpush
