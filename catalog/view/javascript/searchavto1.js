class SearchAvtoForm {
    constructor(form_selector) {
        this.form = $(form_selector);
        let self = this;
        self.fields = {};

        this.form.find('select').each(function () {
            let $this = $(this);
            self.fields[$this.attr('name')] = $this.find('option:first').text();
        });

        $(form_selector).on('change', 'select', function () {
            self.form.addClass('form-disabled');
            self.refresh();
        });

        $(form_selector).on('click', '.reset_button', function () {
            self.reset();
            self.refresh();
            return false;
        });
    }

    refresh() {
        this.values = {};

        for (let field in this.fields) {
            this.values[field] = this.form.find('select[name="' + field + '"] option:selected').text();
        }

        let self = this;

        $.getJSON("https://www.frpneu.cz/search-auto/?" + this.form.serialize() + '&ajax=1', function (data) {
            console.log(data);
            for (var field in self.fields) {

                let options = data[field] || [];
                let options_string = '<option value="">' + self.fields[field] + '</option>';
                for (var key in options) {
                    //Временно убираем лишние сезоны.

                    if (field == "season") {
                        if (options[key] == 'letní' || options[key] == 'zimní' || options[key] == 'celoroční') {
                            options_string += '<option value="' + options[key] + '"' + (options[key] == self.values[field] ? ' selected' : '') + '>' + options[key] + '</option>';
                        }
                    } else {
                        options_string += '<option value="' + options[key] + '"' + (options[key] == self.values[field] ? ' selected' : '') + '>' + options[key] + '</option>';
                    }
                }

                self.form.find('select[name="' + field + '"]').html(options_string);
            }
            self.form.find('span.total_result').html(data.total_text);

            self.form.removeClass('form-disabled');
        });

    }

    reset() {
        this.form.find('select').each(function () {
            $(this).val('');
        });
    }


}

document.addEventListener("DOMContentLoaded", function (event) {
    window.disc_search_form = new SearchAvtoForm('#tab-disc form');
    window.tire_search_form = new SearchAvtoForm('#tab-tire form');
    window.disc_search_form.refresh();
    window.tire_search_form.refresh();
});

