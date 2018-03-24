<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\gallery\EditFormWidget;

//debug($editForm->identification);

?>
<!--<p id="authorization"><?php // <?= Yii::$app->session->getFlash('identificationUser')?></p>-->
<div class="col-lg-12 edit-widget">
    <?= EditFormWidget::widget(['editForm' => $editForm]) ?>
</div>
<div class="container">
    <div id="authorization" class="col-lg-6 col-md-9 col-sm-10 col-xs-12">
        <?php $session = Yii::$app->session->get('user') ?>
        <?php if (!$session): ?>
            <?php if (!$editForm->identification): ?>
                <?php $authorization = ActiveForm::begin([
                    'method' => 'post',
                    'action' => ['gallery/edit', 'remove' => null], //'remove' => null - служебный код
                    'options' => [
                        'id' => 'js_authorization',
                    ],
                ]) ?>
                <?= $authorization->field($editForm, 'userLogin') ?>
                <?= $authorization->field($editForm, 'userPassword')->input('password') ?>
                <?= Html::submitButton('Войти', ['id' => 'authorization']) ?>
                <?php ActiveForm::end() ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>








