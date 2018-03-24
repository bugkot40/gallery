<?php

use app\components\gallery\EditFormWidget;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

//debug($editForm);

?>
<div class="container  col-sm-5 col-md-4  edit-widget">
    <?= EditFormWidget::widget(['editForm' => $editForm]) ?>
</div>
<div class="container   col-sm-7 col-md-8 edit-content">
    <p class="edit-title"><?= $editForm->title ?></p>
    <?php if ($editForm->action == 'add'): ?>
        <div class="add">
            <?php if ($editForm->file): ?>
                <?php if ($editForm->result == 'cloning'): ?>
                    <p class="edit-result" style="background: red">Такой файл уже есть на сервере</p>
                <?php endif; ?>
                <?php if ($editForm->result != 'cloning'): ?>
                    <?php if ($editForm->result == 'blockBest'): ?>
                        <p class='edit-result' style='background: red'> К сожалению лучшая работа для демонстрации в
                            галлерее друзей может быть только одна</p>
                    <?php else: ?>
                        <div id="load-img"><?= Html::img("@web/images/gallery/$editForm->userLogin/{$editForm->file->name}", ['alt' => $editForm->file->name, 'width' => '100px']); ?></div>
                        <div id="edit-result">
                            <?= $editForm->result ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php $editWork = ActiveForm::begin(['options' => [
                'enctype' => 'multipart/form-data',
                'id' => 'js_workAdd'
            ]]) ?>
            <div id="add-work">
                <?php if ($editForm->userStatus == 'author' && $editForm->editUser == 'friend'): ?>
                    <?php if ($editForm->list['user']): ?>
                        <?php if ($editForm->list['direction']): ?>
                            <?= $editWork->field($editForm, 'userId')->dropDownList($editForm->list['user']) ?>
                            <?= $editWork->field($editForm, 'directionId')->dropDownList($editForm->list['direction']) ?>
                            <?= $editWork->field($editForm, 'workName') ?>
                            <?= $editWork->field($editForm, 'description') ?>
                            <?= $editWork->field($editForm, 'best')->dropDownList([1 => 'Да', 0 => 'Нет']) ?>
                            <?= $editWork->field($editForm, 'file')->fileInput(['class' => 'work btn btn-success'])->label('Добавить?') ?>
                            <?= Html::submitButton('Добавить', ['id' => 'add_work_friend', 'class' => 'work btn btn-success']) ?>
                        <?php else: ?>
                            <p class="edit-result" style="background: red">Разделы отсутствуют, добавлять работы
                                некуда </p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="edit-result" style="background: red">Друзей нет, работу добавить некому </p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($editForm->userStatus == 'friend' || ($editForm->userStatus == 'author' && $editForm->editUser == 'self')): ?>
                    <?php if ($editForm->list['direction']): ?>
                        <br/>
                        <?= $editWork->field($editForm, 'directionId')->dropDownList($editForm->list['direction']) ?>
                        <?= $editWork->field($editForm, 'workName') ?>
                        <?= $editWork->field($editForm, 'description') ?>
                        <?= $editWork->field($editForm, 'best')->dropDownList([1 => 'Да', 0 => 'Нет']) ?>
                        <?= $editWork->field($editForm, 'file')->fileInput(['class' => 'work btn btn-success'])->label('Добавить?') ?>
                        <?= Html::submitButton('Добавить', ['id' => 'add_work_self', 'class' => 'work btn btn-success']) ?>
                    <?php else: ?>
                        <p class="edit-result" style="background: red">Разделы отсутствуют, добавлять работы некуда </p>
                    <?php endif; ?>
                <?php endif; ?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($editForm->action == 'delete'): ?>
        <div class="delete">
            <?= $editForm->result ?>
            <?php $editWork = ActiveForm::begin(['options' => ['js_workDelete']]) ?>
            <?php if ($editForm->userStatus == 'author' && $editForm->editUser == 'friend'): ?>
                <?php if ($editForm->list['user']): ?>
                    <?php if ($editForm->list['direction']): ?>
                        <br/>
                        <?php if (!$editForm->list['work']): ?>
                            <?= $editWork->field($editForm, 'userId')->dropDownList($editForm->list['user']) ?>
                            <?= $editWork->field($editForm, 'directionId')->dropDownList($editForm->list['direction']) ?>
                            <?= Html::submitButton('Выбрать', ['id' => 'select_work_friend', 'class' => 'work btn btn-danger']) ?>
                            <?php if ($editForm->userId && $editForm->directionId && !$editForm->list['work']): ?>
                                <p class="edit-result" style="background: red">Работ нет</p>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($editForm->list['work']): ?>
                            <?= $editWork->field($editForm, 'workId')->dropDownList($editForm->list['work']) ?>
                            <?= Html::submitButton('Удалить', ['id' => 'delete_work_friend', 'class' => 'work btn btn-danger']) ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="edit-result" style="background: red">Разделы отсутствуют, работ нет</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="edit-result" style="background: red">На сайте нет зарегистрированных друзей, работ нет</p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($editForm->userStatus == 'friend' || ($editForm->userStatus == 'author' && $editForm->editUser == 'self')): ?>
                <?php if ($editForm->list['direction']): ?>
                    <br/>
                    <?php if (!$editForm->list['work']): ?>
                        <?= $editWork->field($editForm, 'directionId')->dropDownList($editForm->list['direction']) ?>
                        <?= Html::submitButton('Выбрать', ['id' => 'select_work_self', 'class' => 'work btn btn-danger']) ?>
                        <?php if ($editForm->directionId && !$editForm->list['work']): ?>
                            <p class="edit-result" style="background: red">Работ нет</p>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($editForm->list['work']): ?>
                        <?= $editWork->field($editForm, 'workId')->dropDownList($editForm->list['work']) ?>
                        <?= Html::submitButton('Удалить', ['id' => 'delete_work_self', 'class' => 'work btn btn-danger']) ?>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="edit-result" style="background: red">Разделы отсутствуют, работ нет</p>
                <?php endif; ?>
            <?php endif; ?>
            <?php ActiveForm::end() ?>
        </div>
    <?php endif; ?>

    <?php if ($editForm->action == 'change'): ?>
        <div class="change">
            <?= $editForm->result ?>
            <?php $editWork = ActiveForm::begin(['options' => ['js_workChange']]) ?>
            <?php if ($editForm->userStatus == 'author' && $editForm->editUser == 'friend'): ?>
                <?php if ($editForm->list['user']): ?>
                    <?php if ($editForm->list['direction']): ?>
                        <br/>
                        <?= $editWork->field($editForm, 'userId')->dropDownList($editForm->list['user'])->label('Выберите друга, работу которого необходимо редактировать') ?>
                        <?= $editWork->field($editForm, 'directionId')->dropDownList($editForm->list['direction'])->label('Выберите раздел, где находиться редактируемая работа ') ?>
                        <?php if (!$editForm->directionId): ?>
                            <?= Html::submitButton('Выбрать', ['id' => 'select_work_friend', 'class' => 'work btn btn-primary']) ?>
                        <?php endif; ?>
                        <?php if ($editForm->userId && $editForm->directionId && !$editForm->list['work']): ?>
                            <p class="edit-result" style="background: red">Работ нет</p>
                            <?= Html::submitButton('Выбрать', ['id' => 'select_work_friend', 'class' => 'work btn btn-primary']) ?>
                        <?php endif; ?>
                        <?php if ($editForm->list['work']): ?>
                            <?= $editWork->field($editForm, 'workId')->dropDownList($editForm->list['work'])->label('Выберите работу для редактирования') ?>
                            <?php if (!$editForm->workId): ?>
                                <?= Html::submitButton('Выбрать', ['id' => 'select_work_friend', 'class' => 'work btn btn-primary']) ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($editForm->workId): ?>
                            <?= $editWork->field($editForm, 'workName')->label('Изменить наименование работы ?') ?>
                            <?= $editWork->field($editForm, 'best')->dropDownList([1 => 'Да', 0 => 'Нет'])->label('Статус работы лучшая ?') ?>
                            <?= $editWork->field($editForm, 'description')->label('Изменить описание к работе ?') ?>
                            <?= Html::submitButton('Изменить', ['id' => 'change_work_friend', 'class' => 'work btn btn-primary']) ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="edit-result" style="background: red">Разделы отсутствуют, работ нет</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="edit-result" style="background: red">На сайте нет зарегистрированных друзей, работ нет</p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($editForm->userStatus == 'friend' || ($editForm->userStatus == 'author' && $editForm->editUser == 'self')): ?>
                <?php if ($editForm->list['direction']): ?>
                    <br/>
                    <?= $editWork->field($editForm, 'directionId')->dropDownList($editForm->list['direction'])->label('Выберите раздел в котором находится ваша работа') ?>
                    <?php if (!$editForm->directionId): ?>
                        <?= Html::submitButton('Выбрать', ['id' => 'select_work_self', 'class' => 'work btn btn-primary']) ?>
                    <?php endif; ?>
                    <?php if ($editForm->directionId && !$editForm->list['work']): ?>
                        <p class="edit-result" style="background: red">Работ нет</p>
                        <?= Html::submitButton('Выбрать', ['id' => 'select_work_self', 'class' => 'work btn btn-primary']) ?>
                    <?php endif; ?>
                    <?php if ($editForm->list['work']): ?>
                        <?= $editWork->field($editForm, 'workId')->dropDownList($editForm->list['work'])->label('Выберите свою работу для редактирования') ?>
                        <?php if (!$editForm->workId): ?>
                            <?= Html::submitButton('Выбрать', ['id' => 'change_work_self', 'class' => 'work btn btn-primary']) ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($editForm->workId): ?>
                        <?= $editWork->field($editForm, 'workName')->label('Измените наименование работы') ?>
                        <?= $editWork->field($editForm, 'best')->dropDownList([1 => 'Да', 0 => 'Нет']) ?>
                        <?= $editWork->field($editForm, 'description')->label('Измените описание к работе') ?>
                        <?= Html::submitButton('Изменить', ['id' => 'change_work_self', 'class' => 'work btn btn-primary']) ?>
                    <?php endif; ?>

                <?php else: ?>
                    <p class="edit-result" style="background: red">Разделы отсутствуют, работ нет</p>
                <?php endif; ?>
            <?php endif; ?>
            <?php ActiveForm::end() ?>
        </div>
    <?php endif; ?>
</div>



