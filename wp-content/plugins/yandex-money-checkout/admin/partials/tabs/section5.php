<?php


?>
<form id="yandexmoney-form-5" class="yandexmoney-form">
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-6 padding-bottom">
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-block">
                            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                            <p>
                                <?= __("Пропишите URL для уведомлений в <a target='_blank' href='https://kassa.yandex.ru/my/shop-settings'>настройках личного кабинета Яндекс.Кассы</a>.", 'yandex-money-checkout'); ?><br>
                                <?= __('Он позволит изменять статус заказа после оплаты в вашем магазине.', 'yandex-money-checkout'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row padding-bottom">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" id="notify_url" name="notify_url" class="form-control" readonly="readonly"
                                   value="<?=site_url('/?yandex_money=callback', 'https');?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default copy-button" data-toggle="tooltip" data-placement="top"
                                        data-clipboard-text="<?=site_url('/?yandex_money=callback', 'https');?>"
                                        data-success="<?=__('Скопировано!', 'yandex-money-checkout');?>" data-error="<?=__('Попробуйте Ctr+C!', 'yandex-money-checkout');?>">
                                    <span class="glyphicon glyphicon-copy"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-md-offset-1 help-side">

            </div>
        </div>

        <div class="row form-footer">
            <div class="col-md-12">
                <button class="btn btn-default btn-back" data-tab="section4"><?= __('Назад', 'yandex-money-checkout'); ?></button>
                <button class="btn btn-primary btn-forward" data-tab="section6"><?= __('Сохранить и продолжить', 'yandex-money-checkout'); ?></button>
            </div>
        </div>
    </div>
</form>