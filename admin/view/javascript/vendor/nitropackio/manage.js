($ => {
    const WarmupStats = (function() {
        var autoRefresh = false;
        var refreshInterval = null;

        const updateWarmupStats = function(warmup_stats) {
            $('*[data-warmup-button]').hide();

            $('.warmup-status').html(warmup_stats.text_warmup_status);

            if (warmup_stats.status) {
                $('*[data-warmup-button="info"]').show();

                $('*[data-warmup-button]').attr('disabled', false);

                if (warmup_stats.is_warmup_active) {
                    $('*[data-warmup-button="pause"]').show();
                } else {
                    $('*[data-warmup-button="start"]').show();
                }
            }

            $('#modal-warmup-stats .modal-body').empty();

            warmup_stats.details.forEach(function(detail) {
                $('#modal-warmup-stats .modal-body').append(
                    $('#template-modal-warmup-detail').html()
                        .replace(/{key}/, detail.key)
                        .replace(/{value}/, detail.value)
                );
            });
        }

        const loadWarmupStats = function() {
            $.ajax({
                url: $('#warmup-buttons').attr('data-warmup-stats-url'),
                dataType: 'json',
                success: function(data) {
                    if (!data.status) {
                        Notification.danger(data.message);
                    } else {
                        updateWarmupStats(data.warmup_stats);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (handleLoggedOut(jqXHR.responseText)) {
                        Notification.danger($('#manage-form').attr('data-text-logged-out'));
                    } else {
                        console.error(jqXHR, textStatus, errorThrown);
                        Notification.danger(errorThrown);
                    }
                }
            });
        }

        const initInterval = function() {
            refreshInterval = setInterval(function() {
                if (autoRefresh) {
                    loadWarmupStats();
                }
            }, 5000);
        }

        initInterval();

        return {
            setAutoRefreshStatus: function(status) {
                autoRefresh = status;
            },
            update: updateWarmupStats,
            refresh: function() {
                if (refreshInterval) {
                    clearInterval(refreshInterval);
                }

                loadWarmupStats();
                initInterval();
            }
        }
    })();

    const iFramesLoaded = _ => {
        var allLoaded = new Promise((resolve, reject) => {
            var promises = $.map($('iframe'), iframe => new Promise((innerResolve, innerReject) => {
                $(iframe).load(innerResolve);
            }));

            Promise.all(promises).then(resolve);
        });

        return allLoaded;
    }

    const loadIFrames = _ => {
        $('iframe[data-src]').each((index, iframe) => {
            $(iframe).attr('src', $(iframe).attr('data-src'));
        });
    }

    const htmlEntities = str => {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    const nextCustomPage = _ => {
        var next = 0;

        $('[data-custom-page-i]').each(function(index, element) {
            var candidate = parseInt($(element).attr('data-custom-page-i'));

            if (candidate >= next) {
                next = candidate + 1;
            }
        });

        return next;
    }

    const customPageRow = page => {
        let html = $('#template-custom-page').html();

        return html
            .replace(/{i}/g, nextCustomPage())
            .replace(/{name}/g, page.name)
            .replace(/{name_escaped}/g, htmlEntities(page.name))
            .replace(/{route}/g, page.route);
    }

    const Notification = (_ => {
        var status = false;
        var timeout;

        var display = (msg, type) => {
            if (!status) return;

            if ($('#nitropack-notification[data-type=' + type + ']').length) {
                var messageElement = $('#nitropack-notification[data-type=' + type + ']').find("#nitropack-notification-message");

                $(messageElement).html(
                    $(messageElement).html().concat(' ').concat(msg)
                );
            } else {
                clearTimeout(timeout);

                $('#nitropack-notification').remove();

                $('body').append(
                    $('#template-nitropack-notification-'.concat(type)).html()
                        .replace(/{message}/g, msg)
                );

                timeout = setTimeout(_ => {
                    $('#nitropack-notification').remove();
                }, 3000);
            }
        }

        return {
            setStatus: newStatus => {
                status = newStatus;
            },
            success: msg => {
                display(msg, 'success');
            },
            danger: msg => {
                display(msg, 'danger');
            },
            info: msg => {
                display(msg, 'info');
            },
            warning: msg => {
                display(msg, 'warning');
            }
        }
    })();

    const setNitroPackPreset = async function(status) {
        var previous_value = parseInt($('#nitropack-local-preset').val());
        var new_value = parseInt(status);

        $('#nitropack-local-preset').val(new_value);

        return previous_value != status;
    }

    const handleLoggedOut = function(responseText) {
        if (responseText.indexOf('index.php?route=common/login') > 0) {
            // Refresh the page to display the login window
            document.location = document.location;

            return true;
        }

        return false;
    }

    const updateConnectionDot = function() {
        $('*[data-connection]').hide();

        switch ($('#select-status:checked').length) {
            case 0 :
                $('*[data-connection="disabled"]').show();
            break;
            default :
                $('*[data-connection="connected"]').show();
            break;
        }
    }

    const saveForm = (function() {
        var execCount = 0;

        const doSave = function() {
            return $.ajax({
                url: $('#manage-form').attr('action'),
                type: 'POST',
                data: $('#manage-form').find('input[type!="checkbox"],input[type="checkbox"]:checked,select,textarea'),
                dataType: 'json',
                beforeSend: function() {
                    Notification.info($('#manage-form').attr('data-text-loading'));
                },
                success: function(data) {
                    Notification[data.type](data.message);

                    if (data.warmup_details !== null) {
                        $('#warmup-details').html(data.warmup_details);
                    }

                    if (data.warmup_stats !== null) {
                        WarmupStats.update(data.warmup_stats);
                        WarmupStats.setAutoRefreshStatus(data.warmup_stats.status);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (handleLoggedOut(jqXHR.responseText)) {
                        Notification.danger($('#manage-form').attr('data-text-logged-out'));
                    } else {
                        console.error(jqXHR, textStatus, errorThrown);
                        Notification.danger(errorThrown);
                    }
                },
                complete: function() {
                    execCount--;

                    // console.log("DECREMENT", execCount);

                    // Only after the first save, we enable notifications. This is because the first save is always occurring.
                    Notification.setStatus(true);

                    if (execCount == 1) {
                        doSave();
                    }
                }
            });
        }

        const execute = function() {
            if (execCount < 2) {
                execCount++;

                // console.log("INCREMENT", execCount);

                if (execCount == 1) {
                    return doSave();
                }
            }

            return Promise.resolve();
        };

        return execute;
    })();

    const executeInvalidation = function(invalidate = "") {
        return $.ajax({
            url: $('#optimizations').attr('data-url-invalidate-cache').concat(invalidate),
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                Notification.info($('#optimizations').attr('data-text-loading-invalidate-cache'));
            },
            success: function(data) {
                Notification[data.type](data.message);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (handleLoggedOut(jqXHR.responseText)) {
                    Notification.danger($('#manage-form').attr('data-text-logged-out'));
                } else {
                    console.error(jqXHR, textStatus, errorThrown);
                    Notification.danger(errorThrown);
                }
            }
        });
    }

    const executePurge = function(purge = "") {
        return $.ajax({
            url: $('#optimizations').attr('data-url-purge-cache').concat(purge),
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                Notification.info($('#optimizations').attr('data-text-loading-purge-cache'));
            },
            success: function(data) {
                Notification[data.type](data.message);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (handleLoggedOut(jqXHR.responseText)) {
                    Notification.danger($('#manage-form').attr('data-text-logged-out'));
                } else {
                    console.error(jqXHR, textStatus, errorThrown);
                    Notification.danger(errorThrown);
                }
            }
        });
    }

    $(document).on('click', '#disconnect', function(e) {
        if (!confirm($(this).attr('data-are-you-sure'))) {
            e.preventDefault();
        } else {
            $(this).button('loading');
        }
    });

    $(document).on('click', '.delete-custom-page', function(e) {
        if (!confirm($(this).attr('data-are-you-sure'))) {
            e.preventDefault();
        } else {
            let route = $(this).closest('[data-custom-page-i]').find('input[name*="[route]"]').val();

            $(this).closest('tr').remove();
            
            saveForm();
            executePurge('&purge_type=route&purge_value='.concat(route));
        }
    });

    $(document).on('click', '.checkbox-td', function(e) {
        if ($(e.target).is('input')) {
            return;
        }

        var checkbox = $(this).find('input').first();

        $(checkbox).prop('checked', !$(checkbox).prop('checked')).trigger('change');
    });

    $(document).on('change', '#manage-form', function(e) {
        updateConnectionDot();
        saveForm();
    });

    $(document).on('click', '#add-custom-page', function(e) {
        e.preventDefault();

        var modal = $($('#template-modal-custom-page').html()).modal();
    });

    $(document).on('click', '#save-custom-page', function(e) {
        var page = {
            name: $('#input-custom-page-name').val(),
            route: $('#select-custom-page-route').val(),
        };

        $('#custom-pages').append(customPageRow(page));

        saveForm();

        $('.modal').modal('hide');
    });

    $(document).on('click', '#button-configure-warmup', function(e) {
        e.preventDefault();

        var modal = $($('#template-modal-warmup').html()).modal();
    });

    $(document).on('click', '[data-warmup-button="info"]', function(e) {
        e.preventDefault();

        var modal = $($('#template-modal-warmup-stats').html()).modal();

        $(modal).on('shown.bs.modal', function() {
            WarmupStats.refresh();
        });
    });

    $(document).on('shown.bs.modal', '#modal-warmup', function(e) {
        $.ajax({
            url: $(this).attr('data-warmup-form'),
            dataType: 'json',
            beforeSend: function() {
                $('#save-warmup').attr('disabled', true);
            },
            success: function(data) {
                if (data.status) {
                    $('#modal-warmup .modal-body').html(data.html);
                } else {
                    Notification.danger(data.message);

                    $('.modal').modal('hide');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (handleLoggedOut(jqXHR.responseText)) {
                    Notification.danger($('#manage-form').attr('data-text-logged-out'));
                } else {
                    console.error(jqXHR, textStatus, errorThrown);
                    Notification.danger(errorThrown);
                }

                $('.modal').modal('hide');
            },
            complete: function() {
                $('#save-warmup').attr('disabled', false);
            }
        });
    });

    $(document).on('click', '#save-warmup', function(e) {
        $('#warmup-data').empty();

        $('#modal-warmup .modal-body input[type="checkbox"][data-exclude-name]:not(:checked)').each(function(index, element) {
            $('#warmup-data').append(
                '<input type="hidden" name="' + $(element).attr('data-exclude-name') + '" value="' + $(element).attr('value') + '" />'
            );
        });

        $('#modal-warmup .modal-body input[type="checkbox"][data-route-name]:checked').each(function(index, element) {
            $('#warmup-data').append(
                '<input type="hidden" name="' + $(element).attr('data-route-name') + '" value="' + $(element).attr('value') + '" />'
            );
        });

        saveForm();

        $('.modal').modal('hide');
    });

    $(document).on('click', '[data-warmup-button][data-warmup-action]', function(e) {
        e.preventDefault();

        var button = this;
        $(button).attr('disabled', true);

        $.ajax({
            url: $(button).attr('data-warmup-action'),
            dataType: 'json',
            success: function(data) {
                if (!data.status) {
                    Notification.danger(data.message);
                } else {
                    WarmupStats.refresh();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (handleLoggedOut(jqXHR.responseText)) {
                    Notification.danger($('#manage-form').attr('data-text-logged-out'));
                } else {
                    console.error(jqXHR, textStatus, errorThrown);
                    Notification.danger(errorThrown);
                }
            }
        });
    });

    $(document).on('input', '#input-custom-page-name', function(e) {
        $('#save-custom-page').attr('disabled', $(this).val() == "");
    });

    $(document).on('hidden.bs.modal', '.modal-removable', function(e) {
        $(this).remove();
    });

    $(document).on('click', '*', function(e) {
        if ($(e.target).is('.dropdown-toggle')) {
            $('.dropdown-toggle').each((index, element) => {
                if (element == e.target && !$(e.target).closest('.dropdown').hasClass('show')) {
                    $(e.target).closest('.dropdown').addClass('show');
                    $(e.target).closest('.dropdown').find('.dropdown-menu').addClass('show');
                } else {
                    $(element).closest('.dropdown').removeClass('show');
                    $(element).closest('.dropdown').find('.dropdown-menu').removeClass('show');
                }
            });
        } else {
            $('.dropdown').removeClass('show');
            $('.dropdown').find('.dropdown-menu').removeClass('show');
        }
    });

    $(document).on('click', '.dropdown-item', function(e) {
        $(this).closest('.dropdown-menu').find('.dropdown-item').removeClass('active');
        $(this).addClass('active');
    });

    $(document).on('click', '[data-button-clear]', function(e) {
        e.preventDefault();

        if (confirm($(this).attr('data-are-you-sure'))) {
            if ($(this).attr('data-button-clear') == 'invalidate') {
                executeInvalidation($(this).attr('data-button-clear-action'));
            } else if ($(this).attr('data-button-clear') == 'purge') {
                executePurge($(this).attr('data-button-clear-action'));
            }
        }
    });

    $(document).ready(function() {
        NitroPack.QuickSetup.setChangeHandler(async function(value, success, error) {
            var presetDifferent = await setNitroPackPreset(value);

            if (presetDifferent) {
                await saveForm();
            } else {
                // Enable save notifications
                Notification.setStatus(true);
            }

            success(value);
        });

        NitroPack.Optimizations.setInvalidateCacheHandler(async function(success, error) {
            executeInvalidation().then(function() {
                success();
            });
        });

        NitroPack.Optimizations.setPurgeCacheHandler(async function(success, error) {
            executePurge().then(function() {
                success();
            });
        });

        loadIFrames();
        updateConnectionDot();
        WarmupStats.setAutoRefreshStatus($('#warmup-buttons').attr('data-warmup-autostart') == '1');
    });
})(jQuery);
