<style>
.ym-container { position: relative; width: 100%; min-height: 350px; }
.ym-widget { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; }
.woocommerce-button {border-radius: 0;background-color: #eeeeee;border-color: #eeeeee;color: #333333;cursor: pointer;padding: 0.6180469716em 1.41575em;text-decoration: none;font-weight: 600;text-shadow: none;display: inline-block;-webkit-appearance: none;}
</style>
<div class="ym-container"><iframe src="<?= $widgetUrl?>" class="ym-widget" width="100%" height="100%"></iframe></div>
<script>
document.addEventListener('DOMContentLoaded', function(event) {
    let frm = document.querySelector('.ym-container .ym-widget');
    frm.onload = function() {
        let loc = Object.assign({}, frm.contentWindow.location);
        if (loc.href && loc.href.indexOf('order-received') !== -1) {
            jQuery.get('<?= $checkPaymentUrl?>', function(res) {
                if (res.result === 'success') {
                    if (res.status === 'succeeded' || res.status === 'waiting_for_capture') {
                        window.location.href = loc.href.replace('&iframe', '');
                    } else {
                        jQuery('.ym-container').html(
                            '<p><?=$orderNotPaid?></p>' +
                            '<p><a href="' + res.redirectUrl + '" target="_top" class="woocommerce-button button pay"><?=$tryAgain?></a></p>'
                        );
                    }
                }
            }, 'json');

        }
        setTimeout(function() {
            frm.parentElement.style.height = frm.contentWindow.document.documentElement.scrollHeight + 25 + 'px';
        }, 1000);
    };
});
</script>