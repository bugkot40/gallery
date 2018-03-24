<?php

use app\components\gallery\EditFormWidget;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<?php //debug($editForm) ?>
<div class="container col-sm-5 col-md-4 edit-widget">
    <?= EditFormWidget::widget(['editForm' => $editForm]) ?>
</div>
<div class="container col-sm-7 col-md-8 edit-content">
    <div class="edit-message">
        <p class="edit-title"><?= $editForm->title ?></p>
        <?php if ($editForm->result): ?>
            <p class="edit-result"
               style="background-color: <?= $editForm->result['color'] ?>"><?= $editForm->result['text'] ?></p>
        <?php endif; ?>
    </div>
    <?php if ($editForm->action == 'delete'): ?>
        <div class="delete">
            <?php if ($editForm->list): ?>
                <?php $editUser = ActiveForm::begin(['options' => ['id' => 'js_userDelete']]); ?>
                <?= $editUser->field($editForm, 'userId')->dropDownList($editForm->list) ?>
                <?= Html::submitButton('Удалить', ['id' => 'delete_user', 'class' => 'user btn btn-danger']) ?>
                <?php ActiveForm::end() ?>
            <?php else: ?>
                <p class="edit-result" style="background: red">На сайте нет ни одного зарегистрированного друга</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($editForm->action == 'add'): ?>
        <div class="add">
            <?php $editUser = ActiveForm::begin(['options' => [
                'enctype' => 'multipart/form-data',
                'id' => 'js_userAdd'
            ]]); ?>
            <?= $editUser->field($editForm, 'userName') ?>
            <?= $editUser->field($editForm, 'userLogin') ?>
            <?= $editUser->field($editForm, 'userPassword')->input('password', ['value' => '']) ?>
            <?= $editUser->field($editForm, 'file')->fileInput(['class' => 'user btn btn-success'])->label('Добавить аватарку?') ?>
            <?= Html::submitButton('Добавить', ['id' => 'add_user', 'class' => 'user btn btn-success']) ?>
            <?php ActiveForm::end() ?>
        </div>
    <?php endif; ?>

    <?php if ($editForm->action == 'change'): ?>
        <div class="change">
            <?php if ($editForm->list): ?>
                <br/>
                <?php $editUser = ActiveForm::begin(['options' => [
                    'enctype' => 'multipart/form-data',
                    'id' => 'js_userChange',
                ]]); ?>
                <?= $editUser->field($editForm, 'userId')->dropDownList($editForm->list) ?>
                <?php if ($editForm->userName && $editForm->userLogin /*&& $editForm->userPassword*/): ?>
                    <?= $editUser->field($editForm, 'userName')->label('Введите новое имя') ?>
                    <?= $editUser->field($editForm, 'userLogin')->label('Введите новый логин') ?>
                    <?= $editUser->field($editForm, 'userPassword')->label('Подтвердите пароль или введите новый')->input('password') ?>
                    <?= Html::img("@web/images/gallery/{$editForm->userLogin}/{$editForm->avatar}", ['alt' => 'avatar', 'width' => '50px']) ?>
                    <?= $editUser->field($editForm, 'file')->fileInput(['class' => 'user btn btn-primary'])->label('Заменить аватарку?') ?>
                    <?= Html::submitButton('Изменить', ['id' => 'change_user', 'class' => 'user btn btn-primary']) ?>
                <?php else: ?>
                    <?= Html::submitButton('Выбрать', ['id' => 'change_user', 'class' => 'user btn btn-primary']) ?>
                <?php endif; ?>
                <?php ActiveForm::end() ?>
            <?php else: ?>
                <p class="edit-result" style="background: red">На сайте нет ни одного зарегистрированного друга</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
