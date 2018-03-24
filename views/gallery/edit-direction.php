<?php

use app\components\gallery\EditFormWidget;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

//debug($editForm);
?>
<div class="container col-sm-5 col-md-4  edit-widget">
    <?= EditFormWidget::widget(['editForm' => $editForm]) ?>
</div>
<div class="container col-sm-7 col-md-8 edit-content">
    <div class="edit-message">
        <p class="edit-title"><?= $editForm->title ?></p>
        <?php //debug($editForm->list) ?>
        <?php if ($editForm->result): ?>
            <p class="edit-result"
               style="background-color: <?= $editForm->result['color'] ?>"><?= $editForm->result['text'] ?></p>
        <?php endif; ?>
    </div>
    <?php if ($editForm->action == 'delete'): ?>
        <div class="delete">
            <?php if ($editForm->list): ?>
                <?php $editDirection = ActiveForm::begin(['options' => [
                    'id' => 'js_directionDelete'
                ]]); ?>
                <?= $editDirection->field($editForm, 'directionId')->dropDownList($editForm->list) ?>
                <?= Html::submitButton('Удалить', ['id' => 'delete_direction', 'class' => 'direction btn btn-danger']) ?>
                <?php ActiveForm::end() ?>
            <?php else : ?>
                <p class="edit-result" style="background: red">Разделы отсутствуют</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($editForm->action == 'add'): ?>
        <div class="add">
            <?php $editDirection = ActiveForm::begin(['options' => [
                'id' => 'js_directionAdd',
                'enctype' => 'multipart/form-data',
            ]]); ?>
            <?= $editDirection->field($editForm, 'directionName') ?>
            <?php $editForm->directionName = strip_tags($editForm->directionName); ?>
            <?= $editDirection->field($editForm, 'file')->fileInput(['class' => 'direction btn btn-success'])->label('Добавить иконку?') ?>
            <?= Html::submitButton('Добавить', ['id' => 'add_direction', 'class' => 'direction btn btn-success ']) ?>
            <?php ActiveForm::end() ?>
        </div>
    <?php endif; ?>

    <?php if ($editForm->action == 'change'): ?>
        <div class="change">
            <?php if ($editForm->list): ?>
                <?php $editDirection = ActiveForm::begin(['options' => [
                    'id' => 'js_directionChange',
                    'enctype' => 'multipart/form-data',
                ]]); ?>
                <?= $editDirection->field($editForm, 'directionId')->dropDownList($editForm->list) ?>
                <?php if (!$editForm->directionName): ?>
                    <?= Html::submitButton('Выбрать', ['id' => 'change_direction', 'class' => 'direction btn btn-primary']) ?>
                <?php endif; ?>
                <?php if ($editForm->directionName): ?>
                    <?= $editDirection->field($editForm, 'directionName')->label('Введите новое направление творчества') ?>
                    <?= Html::img("@web/images/gallery/direction-icon/{$editForm->logo}", ['alt' => 'logo', 'width' => '50px']) ?>
                    <?= $editDirection->field($editForm, 'file')->fileInput(['class' => 'direction btn btn-primary'])->label('Заменить иконку?') ?>
                    <?= Html::submitButton('Изменить', ['id' => 'change_direction', 'class' => 'direction btn btn-primary']) ?>
                <?php endif; ?>
                <?php ActiveForm::end() ?>
            <?php else : ?>
                <p class="edit-result" style="background: red">Разделы отсутствуют</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
