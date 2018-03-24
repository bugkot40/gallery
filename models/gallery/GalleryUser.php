<?php

namespace app\models\gallery;

/**
 * This is the model class for table "user".
 * @property integer $id
 * @property string $name
 * @property string $login
 * @property string $password
 * @property string $status
 * @property string $name_file
 * @property string $base_name_file
 * @property GalleryWork[] $works
 */
class GalleryUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery-user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'string', 'max' => 10, 'message' => 'Некорректный заполнено поле'],
            [['login'], 'string', 'max' => 25, 'message' => 'Логин должен быть короче'],
            [['name'], 'string', 'max' => 50, 'message' => 'Имя пользователя должно быть короче'],
            [['password'], 'string', 'max' => 50, 'message' => 'Слишком длинный пароль'],
            [['base_name_file'], 'string', 'max' => 50, 'message' => 'Загружаемая аватарка имеет слишком длинное название'],
            [['name_file'], 'string', 'max' => 50, 'message' => 'Некорректный заполнено поле'],
            [['login'], 'unique', 'message' => 'Пользователь с таким логином уже есть'],
            [['login', 'password'], 'required', 'message' => 'Заполните поле'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'login' => 'Логин',
            'password' => 'Пароль',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryWorks()
    {
        return $this->hasMany(GalleryWork::className(), ['id_user' => 'id']);
    }
}
