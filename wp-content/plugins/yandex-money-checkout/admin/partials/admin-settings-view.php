<?php
/**
 * @var array $wcTaxes
 * @var WP_Post[] $pages
 * @var string $wcCalcTaxes
 * @var array $ymTaxRatesEnum
 * @var array $ymTaxes
 * @var string $isHoldEnabled
 * @var string $descriptionTemplate
 * @var string $isReceiptEnabled
 * @var bool $testMode
 * @var string $isDebugEnabled
 * @var string $forceClearCart
 * @var bool|null $validCredentials
 * @var string $active_tab
 * @var bool $isNeededShowNps
 */

?>

<!-- Start tabs -->
<h2 class="nav-tab-wrapper">
    <a class="nav-tab <?php echo $active_tab == 'yandex-checkout-settings' ? 'nav-tab-active' : ''; ?>"
       href="?page=yandex_money_api_menu&tab=yandex-checkout-settings">
        <?= __('Настройки модуля Яндекс.Касса для WooCommerce', 'yandex-money-checkout'); ?>
    </a>
    <a class="nav-tab <?php echo $active_tab == 'yandex-checkout-transactions' ? 'nav-tab-active' : ''; ?>"
       href="?page=yandex_money_api_menu&tab=yandex-checkout-transactions" style="display:none;">
        <?= __('Список платежей через модуль Кассы', 'yandex-money-checkout'); ?>
    </a>
</h2>
<div class="wrap">

    <div class="tab-panel" id="yandex-checkout-settings" <?php echo $active_tab != 'yandex-checkout-settings' ? 'style="display: none;' : ''; ?>>

        <div class="container-max">
            <div class="container-fluid">
                <div class="row padding-bottom">
                    <div class="col-md-12">
                        <h2><?= __('Настройки модуля Яндекс.Касса для WooCommerce', 'yandex-money-checkout'); ?></h2>
                    </div>
                 </div>
                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        <p><?= __('Версия модуля', 'yandex-money-checkout') ?> <?= YAMONEY_API_VERSION; ?></p>
                    </div>
                    <div class="col-md-8">
                        <p><?= __("Работая с модулем, вы автоматически соглашаетесь с <a href='https://money.yandex.ru/doc.xml?id=527132' target='_blank'>условиями его использования</a>.", 'yandex-money-checkout'); ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="tab font-ys" role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li id="tab-section1" role="presentation" class="active">
                                    <a href="#section1" role="tab" data-toggle="tab"><?= __('Авторизация', 'yandex-money-checkout'); ?></a>
                                </li>
                                <li id="tab-section2" role="presentation">
                                    <a href="#section2" role="tab" data-toggle="tab"><?= __('Способы оплаты', 'yandex-money-checkout'); ?></a>
                                </li>
                                <li id="tab-section3" role="presentation">
                                    <a href="#section3" role="tab" data-toggle="tab"><?= __('Доп. функции', 'yandex-money-checkout'); ?></a>
                                </li>
                                <li id="tab-section4" role="presentation">
                                    <a href="#section4" role="tab" data-toggle="tab"><?= __('54-ФЗ', 'yandex-money-checkout'); ?></a>
                                </li>
                                <li id="tab-section5" role="presentation">
                                    <a href="#section5" role="tab" data-toggle="tab"><?= __('Настройка уведомлений', 'yandex-money-checkout');?></a>
                                </li>
                                <li id="tab-section6" role="presentation">
                                    <a href="#section6" role="tab" data-toggle="tab"><?= __('Готово', 'yandex-money-checkout'); ?></a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content tabs">
                                <div role="tabpanel" class="tab-pane active" id="section1"></div>
                                <div role="tabpanel" class="tab-pane" id="section2"></div>
                                <div role="tabpanel" class="tab-pane" id="section3"></div>
                                <div role="tabpanel" class="tab-pane" id="section4"></div>
                                <div role="tabpanel" class="tab-pane" id="section5"></div>
                                <div role="tabpanel" class="tab-pane" id="section6"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="tab-panel" style="display:none;"
         id="yandex-checkout-transactions" <?php echo $active_tab != 'yandex-checkout-transactions' ? 'style="display: none;' : ''; ?>>
        <form id="events-filter" method="POST">
            <?php
            TransactionsListTable::render();
            ?>
        </form>
    </div>
</div>
<!-- End tabs -->

<script type="text/javascript">
    function activeTab(tab) {
        jQuery('.nav-tabs a[href="#' + tab + '"]').tab('show');
    }
    function settingsModeChange () {
        let $mode = jQuery('#shop-mode');
        jQuery('.tooltip-text').hide();
        jQuery('#tooltip-' + $mode.val()).show();
        jQuery('.nav-tabs > li').hide();
    }
    /**
     * Show tooltip with text
     * @param elem
     * @param msg
     */
    function showTooltip(elem, msg) {
        jQuery(elem).tooltip('destroy').tooltip({trigger: 'focus', title: msg}).tooltip('show');
    }

    function buttonToggle () {
        jQuery(document).on('click', '.btn-toggle', function() {
            jQuery(this).find('.btn').toggleClass('active');
            if (jQuery(this).find('.btn-primary').size()>0) {
                jQuery(this).find('.btn').toggleClass('btn-primary');
            }
            if (jQuery(this).find('.btn-danger').size()>0) {
                jQuery(this).find('.btn').toggleClass('btn-danger');
            }
            if (jQuery(this).find('.btn-success').size()>0) {
                jQuery(this).find('.btn').toggleClass('btn-success');
            }
            if (jQuery(this).find('.btn-info').size()>0) {
                jQuery(this).find('.btn').toggleClass('btn-info');
            }
            jQuery(this).find('.btn').toggleClass('btn-default');
        });
    }

    /**
     * Get array of form field names
     * @param form
     * @returns {[]}
     */
    function getAllFormInputs(form) {
        let fields = [];
        form.find('input,select,textarea').each(function (index, elm) {
            if (elm.name) {
                fields.push(elm.name.replace(/\[(.+)\]/g,''));
            }
        });
        return jQuery.unique(fields);
    }

    jQuery(document).ready(function () {
        buttonToggle();
        let clip = new ClipboardJS('button.copy-button');
        clip.on('success', function (e) {
            showTooltip(e.trigger, jQuery(e.trigger).data('success'));
        });
        clip.on('error', function (e) {
            showTooltip(e.trigger, jQuery(e.trigger).data('error'));
        });
        jQuery(document).on('click', 'button.copy-button', function(e) { e.preventDefault(); });
        jQuery(document).on('click', 'button.btn-forward', function(e) {
            e.preventDefault();
            let self = jQuery(this).prop('disabled', true);
            let post = self.closest('.yandexmoney-form').serializeArray();
            let fields = getAllFormInputs(self.closest('.yandexmoney-form'));
            post.push({name: 'page_options', value: fields.join(',')});
            jQuery.post('<?= admin_url('admin-ajax.php'); ?>?action=yandex_checkout_save_settings', post, function (res) {
                if (res.status === 'success') {
                    activeTab(self.data('tab'));
                } else {
                    console.log(res);
                    self.prop('disabled', false);
                }
            });
        });
        jQuery(document).on('click', 'button.btn-back', function(e) {
            e.preventDefault();
            activeTab(jQuery(this).data('tab'));
        });
        jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            let target = jQuery(e.target).attr("href");
            jQuery(target).html('<div class="text-center offset-md-1"><div class="loader"></div></div>');
            jQuery.ajax({
                url: '<?= admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'yandex_checkout_get_tab',
                    tab: target.replace('#', '')
                },
                method : 'GET',
                success : function (data) {
                    jQuery(target).html(data);
                    jQuery(target).find('[data-toggle="tooltip"]').tooltip();
                    jQuery(target).find('#ym_api_pay_mode').on('change', function(e){
                        jQuery('.pay-mode-block').hide();
                        jQuery('#pay-mode-' + jQuery(this).val()).show();
                    });
                    jQuery('#ym_api_enable_receipt :radio').on('change', function(e){
                        if(jQuery(this).val() != 1) {
                            if (jQuery('#ym_api_enable_second_receipt :checked').val() == 1) {
                                jQuery('#ym_api_enable_second_receipt input:not(:checked)').closest('.btn').trigger('click');
                            }
                        }
                    });
                },
                error : function(error){ console.log(error) }
            });
        });
        jQuery('.active a[data-toggle="tab"]').trigger('shown.bs.tab');

    });
</script>