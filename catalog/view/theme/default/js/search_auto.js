window.SearchAuto = {
    xhr: null,
    top: 0,
    popup: {
        select: null,
        small: false
    },
};

function checkAjax() {
    if(SearchAuto.xhr && SearchAuto.xhr.readystate != 4) {
        SearchAuto.xhr.abort();
    }
}

function getAjaxData($parent, type) {
    SearchAuto.xhr = $.ajax({
        type: 'POST',
        url: location.protocol + '//' + location.host + '/index.php?route=extension/module/search_auto/' + type,
        dataType: 'json',
        data: $parent.find("select[disabled!='disabled']"),
        beforeSend: function() {
            $parent.find('.ajaxload').css('visibility', 'visible');
        },
        complete: function() {
            $parent.find('.ajaxload').css('visibility', 'hidden');
        },
        success: function(data) {
            if (data.error) {
                alert(data.error);
            } else if (data[type]) {
                $parent.find('#input-' + type).append($(data[type])).attr('disabled', false);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            if(thrownError != 'abort') {
                alert('ERROR: \n\n' + thrownError);
            }
        }
    });
}

function translit(str) {
    var dictionary = {
        "а":"a", "б":"b", "в":"v", "г":"g",
        "д":"d", "е":"e", "ё":"yo", "ж":"zh",
        "з":"z", "и":"i", "й":"j", "к":"k",
        "л":"l", "м":"m", "н":"n", "о":"o",
        "п":"p", "р":"r", "с":"s", "т":"t",
        "у":"u", "ф":"f", "х":"h", "ц":"c",
        "ч":"ch", "ш":"sh", "щ":"shch", "ъ":"",
        "ы":"y", "ь":"", "э":"eh", "ю":"yu",
        "я":"ya"
    };

    return str.replace(/([а-яё])/gi, function(matchResult) {
        return dictionary[matchResult];
    });
}

function clear(data) {
    var str = translit(data.toLowerCase());
    return str.replace(/[^a-z0-9-\.]/gi, '-').replace(/-{2,}/g, '-').replace(/^-+|-+$/g, '');
}

function resizeAutoPopup() {
    if (SearchAuto.popup.select == null) return;

    var top = SearchAuto.popup.select.offset().top + SearchAuto.popup.select.innerHeight() + 17;
    var left = SearchAuto.popup.select.closest('form').find('select').first().offset().left;
    var width = SearchAuto.popup.select.closest('form').innerWidth() - 30;

    if (SearchAuto.popup.small) {
        width = 200;
    }

    var corner_left = SearchAuto.popup.select.offset().left - SearchAuto.popup.select.closest('form').offset().left - 2;
    var corner_width = SearchAuto.popup.select.innerWidth();

    $('.auto-popup-corner').css({left: corner_left, width: corner_width});
    $('.auto-popup').css({top, left, width});
}

$(document).ready(function() {
    $.get(location.protocol + '//' + location.host + '/index.php?route=extension/module/search_auto/vendorsimages', function(images) {
        $(images).each(function(index, src) {
            $("<img/>").attr('src', src);
        });
    });

    var $popup = $('<div/>').addClass('auto-popup');
    var $corner = $('<div/>').addClass('auto-popup-corner');
    $popup.append($corner);
    var $itemsWrap = $('<div/>').addClass('auto-popup-items-wrap');
    var $items = $('<div/>').addClass('auto-popup-items');
    $itemsWrap.append($items);
    $popup.append($itemsWrap);
    var $backdrop = $('<div/>').addClass('auto-popup-backdrop');

    $("body").append($popup);
    $("body").append($backdrop);

    $(".search-auto select[name='vendor']").bind('change', function() {
        checkAjax();

        var $parent = $(this).closest('#tab-auto');

        $parent.find('.button').attr('disabled', true).addClass('disabled');
        $parent.find('#input-model, #input-year, #input-mod').empty().attr('disabled', true);

        if($(this).val() != '-') {
            getAjaxData($parent, 'model');
        }
    });

    $(".search-auto select[name='model']").bind('change', function() {
        checkAjax();

        var $parent = $(this).closest('#tab-auto');

        $parent.find('.button').attr('disabled', true).addClass('disabled');
        $parent.find('#input-year, #input-mod').empty().attr('disabled', true);

        if($(this).val() != '-') {
            getAjaxData($parent, 'year');
        } else {
            $parent.find('#input-year, #input-mod').empty().attr('disabled', true);
            $parent.find('.button').addClass('disabled');
        }
    });

    $(".search-auto select[name='year']").bind('change', function() {
        checkAjax();

        var $parent = $(this).closest('#tab-auto');

        $parent.find('.button').attr('disabled', true).addClass('disabled');
        $parent.find('#input-mod').empty().attr('disabled', true);

        if($(this).val() != '') {
            getAjaxData($parent, 'mod');
        } else {
            $parent.find('#input-mod').empty().attr('disabled', true);
            $parent.find('.button').attr('disabled', true).addClass('disabled');
        }
    });

    $(".search-auto select[name='mod']").bind('change', function() {
        var $parent = $(this).closest('#tab-auto');

        if($(this).val() != '') {
            $parent.find('.button').attr('disabled', false).removeClass('disabled');
        } else {
            $parent.find('.button').attr('disabled', true).addClass('disabled');
        }
    });

    $(".search-auto select.open-auto-popup").bind('mousedown', function(e) {
        e.preventDefault();
        
        var $this = $(this);

        if ($this.hasClass('load-auto-popup')) return;

        if ($this.hasClass('has-open-auto-popup')) {
            $('.has-open-auto-popup').removeClass('has-open-auto-popup');
            $('.auto-popup').hide();
            return;
        }

        $('.has-open-auto-popup').removeClass('has-open-auto-popup');

        var type = $this.data('auto-popup');
        var $options = $this.find('option');

        if ($options.length == 1 && $options.first().val() == '') return;

        $this.addClass('load-auto-popup');

        $items.html('');

        $options.each(function(index, option) {
            var val = $(option).val();

            if (!val) return;

            switch (type) {
                case 'vendor':
                    var vendor = val.trim();
                    var image = 'image/vendors/' + vendor.replace(' ', '_').toUpperCase() + '.png';

                    $items.append(
                        $('<div/>').addClass('auto-popup-item').attr('data-auto-popup-item', type).attr('data-auto-popup-value', vendor).append(
                            $('<img/>').addClass('auto-popup-item--image').attr('src', image).attr('width', 25).attr('height', 25)
                        ).append(
                            $('<div/>').addClass('auto-popup-item--title').html(vendor)
                        )
                    );
                break;

                case 'model':
                    var model = val;
                    var vendor = $('#input-vendor').val().trim();
                    var image = 'image/vendors/' + vendor.replace(' ', '_').toUpperCase() + '.png';

                    $items.append(
                        $('<div/>').addClass('auto-popup-item').attr('data-auto-popup-item', type).attr('data-auto-popup-value', model).append(
                            $('<img/>').addClass('auto-popup-item--image').attr('src', image).attr('width', 25).attr('height', 25)
                        ).append(
                            $('<div/>').addClass('auto-popup-item--title').html(model)
                        )
                    );
                break;

                default:
                    var title = val;

                    $items.append(
                        $('<div/>').addClass('auto-popup-item').attr('data-auto-popup-item', type).attr('data-auto-popup-value', title).append(
                            $('<div/>').addClass('auto-popup-item--title auto-popup-item--title-year').html(title)
                        )
                    );
                break;
            }

        }).promise().done(function() {
            $this.removeClass('load-auto-popup');
            $this.addClass('has-open-auto-popup');

            if ($this.closest('.search-auto').innerWidth() < 800) {
                $('.auto-popup').addClass('mid');
            }

            if ($this.closest('.search-auto').innerWidth() < 400) {
                $('.auto-popup').addClass('small');
            }

            SearchAuto.popup.small = $this.closest('.search-auto').innerWidth() < 320;
            SearchAuto.popup.select = $this;
            resizeAutoPopup();

            $('.auto-popup').show();
            $('.auto-popup-backdrop').show();

            $('.auto-popup-items-wrap').scrollTop(0);

            SearchAuto.top = $(document).scrollTop();
        });

    });

    $(document).on('click', '.auto-popup-backdrop', function() {
        $('.auto-popup').hide();
        $('.auto-popup-backdrop').hide();
    });

    $(document).on('click', '[data-auto-popup-item]', function() {
        var $this = $(this);

        var type = $this.data('auto-popup-item');
        var value = $this.data('auto-popup-value');
        var $input = $('#input-' + type);

        $input.find('option[value="' + value + '"]').attr('selected', 'true').text(value);
        $input.val(value);

        setTimeout(function() {
            $input.trigger('change');
        }, 100);

        $('.has-open-auto-popup').removeClass('has-open-auto-popup');
        $('.auto-popup').hide();
        $('.auto-popup-backdrop').hide();

        $(document).scrollTop(SearchAuto.top);
    });

    if ($('.search-auto').length) {
        $(window).resize(function() {
            resizeAutoPopup();
        });
    }

});
