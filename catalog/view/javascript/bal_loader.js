function declension(a) {
    switch (a) {
        case 1:
            return text_d1_items;
            break;
        case 2:
        case 3:
        case 4:
            return text_d2_items;
            break;
        default:
            return text_d3_items;
    }
}

function button_autopager(callback) {
	
	if (!$("#search-auto-filter").length) return;
	
    var e_length, e_length_s, appendTodiv, e_total, e_total_p;
    var pagenum = 1;

    var pagelink = window.location.href;
    var page = pagelink.match(/page=(\d+)/);
    if (page) pagenum = page[1];

    appendTodiv = '.appenddivproduct';

    var e_length = $(appendTodiv + '> div[data-scroll=element]').length;
    if ($('div').is(".pokazat_vse")) {
    }    else {
        if ($('a').is('.next-pagination')) {
            e_total = $(".divpagination div:last").text().replace(/[^\d]/gi, '.').split('.').filter(function (number) {
                return number > 0;
            });
            e_length_s = e_total[1] - e_total[0] + 1;
            e_total_p = e_total[2] - (pagenum - 1) * e_length_s;
            if ((e_total_p - e_length) < e_length_s) e_length_s = e_total_p - e_length;
            var next_url = $("a.next-pagination").attr('href');
            $(".divpagination").before('<div class="row pokazat_vse" style="margin-bottom: 10px;"><div class="col-sm-12"><span class="box-next"><a href="' + next_url + '" rel="next">' + text_show_next.replace(/{show}/gi, e_length_s).replace(/{items}/gi, declension(e_length_s)) + '</a><span class="results">' + text_show_all.replace(/{show}/gi, e_length).replace(/{items}/gi, declension(e_length)).replace(/{shows}/gi, e_total_p) + '</span></span></div></div>');
        } else {
            $(".pagination").show();
        }
    }
    $.autopager({
        autoLoad: false,
        link: 'a[rel=next]',
        content: 'div[data-scroll=element]',
        pagination: '.pagination li',
        appendTo: appendTodiv,
        start: function (current, next) {
            $('div[data-scroll=element]').removeClass("new-element");
            $(".divpagination").after('<div id="autopager-loading"><img alt="' + text_loading + '" src="image/loading.gif" /><div>' + text_loading + '</div></div>');
        },
        load: function (current, next) {
            if (next.url == undefined) {
                $('span.box-next').html(text_no_more_show);
                $('span.box-next').fadeOut(2000, function () {
                    $(this).remove();
                })
            } else {
                $('a[rel=next]').attr('href', next.url)
            }
            if (next.pagination !== undefined) {
                $('.pagination').html(next.pagination);
            }
            $('#autopager-loading').remove();
            $('span.box-next').parent().parent().remove();
            if (callback && typeof (callback) === "function") {
                callback();
            }
            button_autopager();
        }
    });

    $('.box-next').click(function () {
        $.autopager('load');
        return false;
    });
    e_length = $(appendTodiv + '> div[data-scroll=element]').length;

}

$(document).ready(function () {
    button_autopager();
    var hash = window.location.href;

    function hashchange() {
        var hash = location.hash;
        button_autopager();
    }

    window.addEventListener('hashchange', hashchange);
});