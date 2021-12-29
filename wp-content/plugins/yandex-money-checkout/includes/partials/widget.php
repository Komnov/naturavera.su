<?php if (!empty($data['error'])):?>
 <?=$data['error']?>
<?php else: ?>
<script src="https://kassa.yandex.ru/checkout-ui/v2.js"></script>
<script>
    const checkout = new window.YandexCheckout({
        confirmation_token: '<?= $data['token']; ?>',
        return_url: '<?= $data['return_url']; ?>',
        embedded_3ds: true,
        newDesign: true,
        error_callback: function (error) {
            if (error.error === 'token_expired') {
                document.location.redirect('<?= $data['payment_url']; ?>');
            }
            console.log(error);
        }
    });
</script>

<div id="ym-widget-checkout-ui"></div>
<script>
    document.addEventListener("DOMContentLoaded", function (event) {
        checkout.render('ym-widget-checkout-ui');
    });
</script>
<?php endif;?>