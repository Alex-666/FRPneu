window.SearchAuto = {
    xhr: null
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

$(document).ready(function() {
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

});
