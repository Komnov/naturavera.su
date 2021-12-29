<?php

/* @var int $testMode */
/* @var int $payMode */
/* @var int $isHoldEnabled */
/* @var int $isSbBOLEnabled */
?>
<form id="yandexmoney-form-2" class="yandexmoney-form">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 padding-bottom">
                <div class="form-group">
                    <label for="ym_api_pay_mode"><?= __('Сценарий оплаты', 'yandex-money-checkout'); ?></label>
                    <select id="ym_api_pay_mode" name="ym_api_pay_mode" class="form-control">
                        <option value="0" <?= ($payMode == 0) ? 'selected="selected"' : ''; ?>><?= __('Выбор оплаты на стороне магазина', 'yandex-money-checkout'); ?></option>
                        <option value="1" <?= $payMode == 1 ? 'selected="selected"' : ''; ?>><?= __('Выбор оплаты на стороне сервиса Яндекс.Касса', 'yandex-money-checkout'); ?></option>
                    </select>
                    <p class="help-block help-block-error"></p>
                </div>

            </div>
            <div class="col-md-5 col-md-offset-1 help-side">
                <p class="title"><b><?= __('Способы оплаты', 'yandex-money-checkout'); ?></b></p>
                <p id="pay-mode-1" class="pay-mode-block" style="<?= ($payMode == 1) ? '' : 'display:none;'; ?>">
                    <?= __('Покупатель выбирает способ оплаты и вводит платёжные данные на странице Кассы.', 'yandex-money-checkout'); ?><br><br>
                    <a target="_blank" href="https://kassa.yandex.ru/help/payments/accept-methods.html"><?= __('Подробнее о способах оплаты &gt;', 'yandex-money-checkout'); ?></a>
                </p>
                <p id="pay-mode-0" class="pay-mode-block" style="<?= ($payMode == 0) ? '' : 'display:none;'; ?>">
                    <?= __('Выберите способы, которые подключены в Яндекс.Кассе.', 'yandex-money-checkout'); ?><br>
                    <?= __('После этого они появятся в платёжной форме на сайте.', 'yandex-money-checkout'); ?><br><br>
                    <a target="_blank" href="https://kassa.yandex.ru/help/payments/accept-methods.html"><?= __('Подробнее о способах оплаты &gt;', 'yandex-money-checkout'); ?></a>
                </p>
            </div>
        </div>

        <div id="ym_api_epl_installments_row" class="row" style="<?=($testMode) ? 'display:none;' : '' ?>">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="checkbox">
                        <input type="hidden" name="ym_api_epl_installments" value="0">
                        <input type="checkbox" id="ym_api_epl_installments" name="ym_api_epl_installments" value="1" <?= get_option('ym_api_epl_installments') == '1' ? ' checked="checked" ' : '' ?>>
                        <label class="control-label" for="ym_api_epl_installments">
                            <?= __('Добавить метод «Заплатить по частям» на страницу оформления заказа', 'yandex-money-checkout'); ?>
                        </label>
                        <p class="help-block help-block-error"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row padding-bottom">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="checkbox">
                        <input type="hidden" name="ym_api_add_installments_block" value="0">
                        <input type="checkbox" id="ym_api_add_installments_block" name="ym_api_add_installments_block" value="1" <?= get_option('ym_api_add_installments_block') == '1' ? ' checked="checked" ' : '' ?>>
                        <label class="control-label" for="ym_api_add_installments_block">
                            <?= __('Добавить блок «Заплатить по частям» в карточки товаров', 'yandex-money-checkout'); ?>
                        </label>
                        <p class="help-block help-block-error"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 form-group">
                <div class="btn-group btn-toggle" data-toggle="buttons">
                    <label class="btn<?=(!$isHoldEnabled)?' btn-primary active':' btn-default';?>">
                        <input type="radio" name="ym_api_enable_hold" value="0"<?=(!$isHoldEnabled)?' checked':'';?>> <?= __('Выкл', 'yandex-money-checkout'); ?>
                    </label>
                    <label class="btn<?=($isHoldEnabled)?' btn-primary active':' btn-default';?>">
                        <input type="radio" name="ym_api_enable_hold" value="1"<?=($isHoldEnabled)?' checked':'';?>> <?= __('Вкл', 'yandex-money-checkout'); ?>
                    </label>
                </div>
                <?= __('Отложенные платежи', 'yandex-money-checkout'); ?>
            </div>
        </div>
        <div class="row padding-bottom">
            <div class="col-md-6">
                <p><small class="text-muted"><?= __('Если опция включена, платежи с карт проходят в 2 этапа: у клиента сумма замораживается, и вам вручную нужно подтвердить её списание – через панель администратора', 'yandex-money-checkout'); ?></small></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 form-group">
                <div class="btn-group btn-toggle" data-toggle="buttons">
                    <label class="btn<?=(!$isSbBOLEnabled)?' btn-primary active':' btn-default';?>" data-toggle="collapse" data-target="#sbbol-collapsible">
                        <input type="radio" name="ym_enable_sbbol" value="0"<?=(!$isSbBOLEnabled)?' checked':'';?>> <?= __('Выкл', 'yandex-money-checkout'); ?>
                    </label>
                    <label class="btn<?=($isSbBOLEnabled)?' btn-primary active':' btn-default';?>" data-toggle="collapse" data-target="#sbbol-collapsible">
                        <input type="radio" name="ym_enable_sbbol" value="1"<?=($isSbBOLEnabled)?' checked':'';?>> <?= __('Вкл', 'yandex-money-checkout'); ?>
                    </label>
                </div>
                <?= __('Сбербанк Бизнес онлайн', 'yandex-money-checkout'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><small class="text-muted">
                        <?= __('Если опция включена, вы можете принимать онлайн-платежи от юрлиц через Сбербанк Бизнес Онлайн.', 'yandex-money-checkout'); ?>
                        <?= __("Подробнее — на <a target='_blank' href='https://kassa.yandex.ru/help/payments/b2b-payments.html'>сайте Кассы</a>.", 'yandex-money-checkout'); ?>
                    </small></p>
            </div>
        </div>

        <div id="sbbol-collapsible" class="row collapse<?=($isSbBOLEnabled)?' in':'';?>" aria-expanded="<?=($isSbBOLEnabled)?'true':'false';?>">

            <div class="col-md-7">

                <?php $ymSbbolTaxRatesEnum = get_option('ym_sbbol_tax_rates_enum'); ?>
                <div class="row">
                    <div class="col-md-5">
                        <label><?= __("Шаблон для назначения платежа", 'yandex-money-checkout') ?></label>
                    </div>
                    <div class="col-md-7">
                        <textarea type="text" id="ym_sbbol_purpose" name="ym_sbbol_purpose" class="form-control"
                                  placeholder="<?= __('Заполните поле', 'yandex-money-checkout'); ?>"><?= $sbbolTemplate ?></textarea>

                        <p><small class="text-muted"><?= __("Это назначение платежа будет в платёжном поручении.", 'yandex-money-checkout') ?></small></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label><?= __("Ставка по умолчанию", 'yandex-money-checkout') ?></label>
                    </div>
                    <div class="col-md-7">
                        <select id="ym_default_tax_rate" name="ym_sbbol_default_tax_rate">
                            <?php foreach ($ymSbbolTaxRatesEnum as $taxId => $taxName) : ?>
                                <option value="<?php echo $taxId ?>" <?php echo $taxId == get_option('ym_sbbol_default_tax_rate') ? 'selected=\'selected\'' : ''; ?>><?php echo $taxName ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p><small class="text-muted"><?= __("Эта ставка передаётся в Сбербанк Бизнес Онлайн, если в карточке товара не указана другая ставка.", 'yandex-money-checkout') ?></small></p>
                    </div>
                </div>
                <?php if ($wcCalcTaxes == 'yes' && $wcTaxes) : ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label><?= __("Сопоставьте ставки НДС в вашем магазине со ставками для Сбербанка Бизнес Онлайн", 'yandex-money-checkout'); ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <p><?= __("Ставка НДС в вашем магазине", 'yandex-money-checkout'); ?></p>
                        </div>
                        <div class="col-sm-7">
                            <p><?= __("Ставка НДС для Сбербанк Бизнес Онлайн", 'yandex-money-checkout'); ?></p>
                        </div>
                    </div>
                    <?php $ymTaxes = get_option('ym_sbbol_tax_rate'); ?>
                    <?php foreach ($wcTaxes as $wcTax) : ?>
                    <div class="row">
                        <div class="col-sm-5"><?= round($wcTax->tax_rate) ?>%</div>
                        <div class="col-sm-7">
                            <?php $selected = isset($ymTaxes[$wcTax->tax_rate_id]) ? $ymTaxes[$wcTax->tax_rate_id] : null; ?>
                            <select id="ym_sbbol_tax_rate[<?= $wcTax->tax_rate_id ?>]" name="ym_sbbol_tax_rate[<?= $wcTax->tax_rate_id ?>]">
                                <?php foreach ($ymSbbolTaxRatesEnum as $taxId => $taxName) : ?>
                                    <option value="<?php echo $taxId ?>" <?= $selected == $taxId ? 'selected' : ''; ?> >
                                        <?= $taxName ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="col-md-5">
                <div class="info-block">
                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                    <?= __('При оплате через Сбербанк Бизнес Онлайн есть ограничение: в одном чеке могут быть только товары с одинаковой ставкой НДС. Если клиент захочет оплатить за один раз товары с разными ставками — мы покажем ему сообщение, что так сделать не получится.', 'yandex-money-checkout'); ?>
                </div>
            </div>
        </div>


        <div class="row form-footer">
            <div class="col-md-12">
                <button class="btn btn-default btn-back" data-tab="section1"><?= __('Назад', 'yandex-money-checkout'); ?></button>
                <button class="btn btn-primary btn-forward" data-tab="section3"><?= __('Сохранить и продолжить', 'yandex-money-checkout'); ?></button>
            </div>
        </div>
    </div>

</form>
