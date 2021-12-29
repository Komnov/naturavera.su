<script src="https://kassa.yandex.ru/checkout-ui/v2.js"></script>
<script>
    const checkout = new window.YandexCheckout({
        confirmation_token: 'confirmation-token',
        return_url: 'https://merchant.site',
    });

    checkout.render('ym-widget-checkout-ui');
</script>

<div id="ym-widget-checkout-ui"></div>