<?php /** @var int $validCredentials */ ?>
<form id="yandexmoney-form-1" class="yandexmoney-form">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 padding-bottom">
                <div class="form-group">
                    <label for="ym_api_shop_id">shopId</label>
                    <input type="text" id="ym_api_shop_id" name="ym_api_shop_id"
                           value="<?php echo get_option('ym_api_shop_id'); ?>" class="form-control"
                           placeholder="<?= __('Заполните поле', 'yandex-money-checkout'); ?>" />
                    <p class="help-block help-block-error"></p>
                </div>
                <div class="form-group">
                    <label for="ym_api_shop_password"><?= __('Секретный ключ', 'yandex-money-checkout') ?></label>
                    <input type="text" id="ym_api_shop_password" name="ym_api_shop_password"
                           value="<?php echo get_option('ym_api_shop_password'); ?>" class="form-control"
                           placeholder="<?= __('Заполните поле', 'yandex-money-checkout'); ?>" />
                    <p class="help-block help-block-error"></p>
                </div>
                <?php if ($validCredentials === 1) : ?>
                <div class="form-group">
                    <p class="help-block help-block-error">
                        <?= __('Проверьте shopId и Секретный ключ — где-то есть ошибка. А лучше скопируйте их прямо из <a href="https://kassa.yandex.ru/my" target="_blank">личного кабинета Яндекс.Кассы</a>', 'yandex-money-checkout'); ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-md-5 col-md-offset-1 help-side">
                <p class="title"><b><?= __('shopId и секретный ключ', 'yandex-money-checkout'); ?></b></p>
                <ul>
                    <li><?= __("<b>shopId</b> можно скопировать в <a target='_blank' href='https://kassa.yandex.ru/my/shop-settings'>личном кабинете Кассы</a>.", 'yandex-money-checkout'); ?></li>
                    <li><?= __('<b>Секретный ключ</b> нужно выпустить и сохранить после подключения Кассы. Если ключ потерялся, в личном кабинете можно его перевыпустить.', 'yandex-money-checkout'); ?></li>
                </ul>
                <p><br></p>
            </div>

        </div>

        <div class="row">
            <div class="col-md-8 col-lg-9">
                <div class="row form-footer">
                    <div class="col-md-12">
                        <button class="btn btn-default btn-back" data-tab="section1"><?= __('Назад', 'yandex-money-checkout'); ?></button>
                        <button class="btn btn-primary btn-forward" data-tab="section2"><?= __('Сохранить и продолжить', 'yandex-money-checkout'); ?></button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</form>