(function ($) {
    'use strict';

    $("#save-settings").click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var form = $("#ym-settings"),
            shopIdInput = form.find('#ym_api_shop_id'),
            shopPassInput = form.find('#ym_api_shop_password'),
            shopIdError = form.find('#shop_id_error'),
            shopPassError = form.find('#shop_pass_error'),
            shopIdValue = shopIdInput.val(),
            shopPassValue = shopPassInput.val(),
            isShopIdValid = (/^\d+$/gi).test(shopIdValue),
            isShopPassValid = (/^test_.*|live_.*$/gi).test(shopPassValue);
        if (isShopIdValid && isShopPassValid) {
            form.submit();
        } else {
            if (!isShopIdValid) {
                shopIdInput.addClass('has-error');
                shopIdError.show();
            } else {
                shopIdError.hide();
                shopIdInput.removeClass('has-error');
            }

            if (!isShopPassValid) {
                shopPassInput.addClass('has-error');
                shopPassError.show();
            } else {
                shopPassError.hide();
                shopPassInput.removeClass('has-error');
            }
        }

        return false;
    });

    function getRadioValue(elements) {
        for (var i = 0; i < elements.length; ++i) {
            if (elements[i].checked) {
                return elements[i].value;
            }
        }
        return elements.length ? elements[0].value : null;
    }

    function triggerPaymentMode(value) {
        if (value == '0') {
            $('.selectPayShop').slideUp();
            $('.selectPayKassa').slideDown();
        } else {
            $('.selectPayShop').slideDown();
            $('.selectPayKassa').slideUp();
        }
    }

    var paymentMode = $('input[name=ym_api_pay_mode]');
    paymentMode.change(function () {
        triggerPaymentMode(this.value);
    });
    triggerPaymentMode(getRadioValue(paymentMode));

    $(document).ready(function () {
        function yandex_money_nps_close() {
            $.post($('.yandex_money_nps_close').data('link'), {action: 'vote_nps'})
                .done(function () {
                    $('.yandex_money_nps_block').slideUp();
                });
        }

        function yandex_money_nps_goto() {
            window.open('https://yandex.ru/poll/5f1ioMjEgV4Ha3DixySw3f');
            yandex_money_nps_close();
        }

        $('.yandex_money_nps_link').on('click', yandex_money_nps_goto);
        $('.yandex_money_nps_close').on('click', yandex_money_nps_close);
    });

})(jQuery);