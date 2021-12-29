jQuery(document).ready(function($) {

    var tipsySettings =  {
        gravity: 'e',
        html: true,
        trigger: 'manual',
        className: function() {
            return 'tipsy-' + $(this).data('id');
        },
        title: function() {
            activeId = $(this).data('id');
            return $(this).attr('original-title');
        }
    }

    $('.regenerateWhatsThis').tipsy({
        fade: true,
        gravity: 'w'
    });

    $('.regenerateError').tipsy({
        fade: true,
        gravity: 'e'
    });

    var data = {
            action: 'regenerate_request'
        },

        errorTpl = '<div class="regenerateErrorWrap"><a class="regenerateError">Failed! Hover here</a></div>',
        $btnApplyBulkAction = $("#doaction"),
        $btnApplyBulkAction2 = $("#doaction2"),
        $topBulkActionDropdown = $(".tablenav.top .bulkactions select[name='action']"),
        $bottomBulkActionDropdown = $(".tablenav.bottom .bulkactions select[name='action2']");


    var requestSuccess = function(data, textStatus, jqXHR) {
        var $button = $(this),
            $parent = $(this).closest('.regenerate-wrap, .buttonWrap'),
            $cell = $(this).closest("td");

        if (data.html) {
            $button.text("Image optimized");

            var type = data.type,
                originalSize = data.original_size,
                $originalSizeColumn = $(this).parent().prev("td.original_size"),
                regenerateedData = '';

            $parent.fadeOut("fast", function() {
                $cell
                    .find(".noSavings, .regenerateErrorWrap")
                    .remove();
                $cell.html(data.html);
                $cell.find('.regenerate-item-details')
                    .tipsy(tipsySettings);
                $originalSizeColumn.html(originalSize);
                $parent.remove();
            });

        } else if (data.error) {

            var $error = $(errorTpl).attr("title", data.error);

            $parent
                .closest("td")
                .find(".regenerateErrorWrap")
                .remove();


            $parent.after($error);
            $error.tipsy({
                fade: true,
                gravity: 'e'
            });

            $button
                .text("Retry request")
                .removeAttr("disabled")
                .css({
                    opacity: 1
                });
        }
    };

    var requestFail = function(jqXHR, textStatus, errorThrown) {
        $(this).removeAttr("disabled");
    };

    var requestComplete = function(jqXHR, textStatus, errorThrown) {
        $(this).removeAttr("disabled");
        $(this)
            .parent()
            .find(".regenerateSpinner")
            .css("display", "none");
    };

    var opts = '<option value="regenerate-bulk-lossy">' + "Regenerate All" + '</option>';

    $topBulkActionDropdown.find("option:last-child").before(opts);
    $bottomBulkActionDropdown.find("option:last-child").before(opts);


    var getBulkImageData = function() {
        var $rows = $("tr[id^='post-']"),
            $row = null,
            postId = 0,
            imageDateItem = {},
            $enjoyedBtn = null,
            btnData = {},
            originalSize = '',
            rv = [];
        $rows.each(function() {
            $row = $(this);
            postId = this.id.replace(/^\D+/g, '');
            if ($row.find("input[type='checkbox'][value='" + postId + "']:checked").length) {
                $enjoyedBtn = $row.find(".regenerate_req");
                if ($enjoyedBtn.length) {
                    btnData = $enjoyedBtn.data();
                    originalSize = $.trim($row.find('td.original_size').text());
                    btnData.originalSize = originalSize;
                    rv.push(btnData);
                }
            }
        });
        return rv;
    };

    var bulkModalOptions = {
        zIndex: 4,
        escapeClose: true,
        clickClose: false,
        closeText: 'close',
        showClose: false
    };

    var renderBulkImageSummary = function(bulkImageData) {
        var setting = regenerate_settings.api_lossy;
        var nImages = bulkImageData.length;
        var header = '<p class="regenerateBulkHeader">regenerate Bulk Image Optimization <span class="close-regenerate-bulk">&times;</span></p>';
        var enjoyedEmAll = '<button class="regenerate_req_bulk">Regenerate All</button>';
        var typeRadios = '<div class="radiosWrap"><p>Choose optimization mode:</p><label><input type="radio" id="regenerate-bulk-type-lossy" value="Lossy" name="regenerate-bulk-type"/>Auto</label></div>';

        var $modal = $('<div id="regenerate-bulk-modal" class="regenerate-modal"></div>')
                .html(header)
                .append(typeRadios)
                .append('<p class="the-following">The following <strong>' + nImages + '</strong> images will be optimized by way2enjoy.com<!-- using the <strong class="bulkSetting">' + setting + '</strong> setting:--></p>')
                .appendTo("body")
                .kmodal(bulkModalOptions)
                .bind($.kmodal.BEFORE_CLOSE, function(event, modal) {

                })
                .bind($.kmodal.OPEN, function(event, modal) {

                })
                .bind($.kmodal.CLOSE, function(event, modal) {
                    $("#regenerate-bulk-modal").remove();
                })
                .css({
                    top: "10px",
                    marginTop: "40px"
                });

        if (setting === "lossy") {
            $("#regenerate-bulk-type-lossy").attr("checked", true);
        } else {
            $("#regenerate-bulk-type-lossless").attr("checked", true);
        }
        $bulkSettingSpan = $(".bulkSetting");
        $("input[name='regenerate-bulk-type']").change(function() {
            var text = this.id === "regenerate-bulk-type-lossy" ? "lossy" : "lossless";
            $bulkSettingSpan.text(text);
        });

        // to prevent close on clicking overlay div
        $(".jquery-modal.blocker").click(function(e) {
            return false;
        });

        // otherwise media submenu shows through modal overlay
        $("#menu-media ul.wp-submenu").css({
            "z-index": 1
        });

        var $table = $('<table id="regenerate-bulk"></table>'),
            $headerRow = $('<tr class="regenerate-bulk-header"><td>File Name</td><td style="width:120px">Original Size</td><td style="width:120px">way2enjoy.com Stats</td></tr>');

        $table.append($headerRow);
        $.each(bulkImageData, function(index, element) {
           $table.append('<tr class="regenerate-item-row" data-regeneratebulkid="' + element.id + '"><td class="regenerate-bulk-filename">' + element.filename + '</td><td class="regenerate-originalsize">' + element.originalSize + '</td><td class="regenerate-regenerateedsize"><span class="regenerateBulkSpinner hidden"></span></td></tr>');


        });

        $modal
            .append($table)
            .append(enjoyedEmAll);

        $(".close-regenerate-bulk").click(function() {
            $.kmodal.close();
        });

        if (!nImages) {
            $(".regenerate_req_bulk")
                .attr("disabled", true)
                .css({
                    opacity: 0.5
                });
        }
    };


    var bulkAction = function(bulkImageData) {

        $bulkTable = $("#regenerate-bulk");
        var jqxhr = null;

        var q = async.queue(function(task, callback) {
            var id = task.id,
                filename = task.filename;

            var $row = $bulkTable.find("tr[data-regeneratebulkid='" + id + "']"),
                $regenerateedSizeColumn = $row.find(".regenerate-regenerateedsize"),
                $spinner = $regenerateedSizeColumn
                .find(".regenerateBulkSpinner")
                .css({
                    display: "inline-block"
                }),
                $savingsPercentColumn = $row.find(".regenerate-savingsPercent"),
                $savingsBytesColumn = $row.find(".regenerate-savings");

            jqxhr = $.ajax({
                url: ajax_object.ajax_url,
                data: {
                    'action': 'regenerate_request',
                    'id': id,
                    'type': $("input[name='regenerate-bulk-type']:checked").val().toLowerCase(),
                    'origin': 'bulk_optimizer'
                },
                type: "post",
                dataType: "json",
                timeout: 360000
            })
                .done(function(data, textStatus, jqXHR) {
                    if (data.success && typeof data.message === 'undefined') {
                        var type = data.type,
                            originalSize = data.original_size,
                            regenerateedSize = data.html,
                            savingsPercent = data.savings_percent,
                            savingsBytes = data.saved_bytes;

                        $regenerateedSizeColumn.html(data.html);

                        $regenerateedSizeColumn
                            .find('.regenerate-item-details')
                            .remove();

                        $savingsPercentColumn.text(savingsPercent);
                        $savingsBytesColumn.text(savingsBytes);

                        var $button = $("button[id='regenerateid-" + id + "']"),
                            $parent = $button.parent(),
                            $cell = $button.closest("td"),
                            $originalSizeColumn = $button.parent().prev("td.original_size");


                        $parent.fadeOut("fast", function() {
                            $cell.find(".noSavings, .regenerateErrorWrap").remove();
                            $cell
                                .empty()
                                .html(data.html);
                            $cell
                                .find('.regenerate-item-details')
                                .tipsy(tipsySettings);
                            $originalSizeColumn.html(originalSize);
                            $parent.remove();
                        });

                    } else if (data.error) {
                        if (data.error === 'This image can not be optimized any further') {
                            $regenerateedSizeColumn.text('No savings found.');
                        } else {

                        }
                    }
                })

            .fail(function() {

            })

            .always(function() {
                $spinner.css({
                    display: "none"
                });
                callback();
            });
        }, regenerate_settings.bulk_async_limit);

        q.drain = function() {
            $(".regenerate_req_bulk")
                .removeAttr("disabled")
                .css({
                    opacity: 1
                })
                .text("Done")
                .unbind("click")
                .click(function() {
                    $.kmodal.close();
                });
        }

        // add some items to the queue (batch-wise)
        q.push(bulkImageData, function(err) {

        });
    };


    $btnApplyBulkAction.add($btnApplyBulkAction2)
        .click(function(e) {
            if ($(this).prev("select").val() === 'regenerate-bulk-lossy') {
                e.preventDefault();
                var bulkImageData = getBulkImageData();
                renderBulkImageSummary(bulkImageData);

                $('.regenerate_req_bulk').click(function(e) {
                    e.preventDefault();
                    $(this)
                        .attr("disabled", true)
                        .css({
                            opacity: 0.5
                        });
                    bulkAction(bulkImageData);
                });
            }
        });




    var activeId = null;
    $('.regenerate-item-details').tipsy(tipsySettings);

    var $activePopup = null;
    $('body').on('click', '.regenerate-item-details', function(e) {
        //$('.tipsy[class="tipsy-' + activeId + '"]').remove();

        var id = $(this).data('id');
        $('.tipsy').remove();
        if (id == activeId) {
            activeId = null;
            $(this).text('Show details');
            return;
        }
        $('.regenerate-item-details').text('Show details');
        $(this).tipsy('show');
        $(this).text('Hide details');
    });

    $('body').on('click', function(e) {
        var $t = $(e.target);
        if (($t.hasClass('tipsy') || $t.closest('.tipsy').length) || $t.hasClass('regenerate-item-details')) {
            return;
        } else {
            activeId = null;
            $('.regenerate-item-details').text('Show details');
            $('.tipsy').remove();
        }
    });

    $('body').on('click', 'small.regenerateReset', function(e) {
        e.preventDefault();
        var $resetButton = $(this);
        var resetData = {
            action: 'regenerate_reset'
        };

        resetData.id = $(this).data("id");
        $row = $('#post-' + resetData.id).find('.compressed_size');

        var $spinner = $('<span class="resetSpinner"></span>');
        $resetButton.after($spinner);

        var jqxhr = $.ajax({
                url: ajax_object.ajax_url,
                data: resetData,
                type: "post",
                dataType: "json",
                timeout: 360000
            })
            .done(function(data, textStatus, jqXHR) {
                if (data.success !== 'undefined') {
                    $row
                        .hide()
                        .html(data.html)
                        .fadeIn()
                        .prev(".original_size.column-original_size")
                        .html(data.original_size);

                    $('.tipsy').remove();
                }
            });
    });

    $('body').on('click', '.regenerate-reset-all', function(e) {
        e.preventDefault();

        var reset = confirm('This will immediately remove all regenerate metadata associated with your images. \n\nAre you sure you want to do this?');
        if (!reset) {
            return;
        }

        var $resetButton = $(this);
        $resetButton
            .text('Resetting images, pleaes wait...')
            .attr('disabled', true);
        var resetData = {
            action: 'regenerate_reset_all'
        };


        var $spinner = $('<span class="resetSpinner"></span>');
        $resetButton.after($spinner);

        var jqxhr = $.ajax({
                url: ajax_object.ajax_url,
                data: resetData,
                type: "post",
                dataType: "json",
                timeout: 360000
            })
            .done(function(data, textStatus, jqXHR) {
                $spinner.remove();
                $resetButton
                    .text('Your images have been reset.')
                    .removeAttr('disabled')
                    .removeClass('enabled');
            });
    });

    // $('.regenerateAdvancedSettings h3').on('click', function () {
    //     var $rows = $('.regenerate-advanced-settings');
    //     var $plusMinus = $('.regenerate-plus-minus');
    //     if ($rows.is(':visible')) {
    //         $rows.hide();
    //         $plusMinus
    //             .removeClass('dashicons-arrow-down')
    //             .addClass('dashicons-arrow-right');
    //     } else {
    //         $rows.show();
    //         $plusMinus
    //             .removeClass('dashicons-arrow-right')
    //             .addClass('dashicons-arrow-down');
    //     }
    // });

    $('body').on("click", ".regenerate_req", function(e) {
        e.preventDefault();
        var $button = $(this),
            $parent = $(this).parent();

        data.id = $(this).data("id");

        $button
            .text(wp_way2_regen_msgs.optimizing_img)
            .attr("disabled", true)
            .css({
                opacity: 0.5
            });


        $parent
            .find(".regenerateSpinner")
            .css("display", "inline");


        var jqxhr = $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            type: "post",
            dataType: "json",
            timeout: 360000,
            context: $button
        })

        .done(requestSuccess)

        .fail(requestFail)

        .always(requestComplete);

    });
});