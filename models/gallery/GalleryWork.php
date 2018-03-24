<?php

namespace app\models\gallery;

/**
 * This is the model class for table "work".
 * @property integer $id
 * @property integer $id_direction
 * @property integer $id_user
 * @property string $name_file
 * @property string $base_name_file
 * @property string $name_work
 * @property string $description
 * @property boolean $best
 * @property GalleryDirection $idDirection
 * @property GalleryUser $idUser
 * @property integer load_time
 */
class GalleryWork extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery-work';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_direction', 'id_user'], 'integer'],
            [['best'], 'boolean'],
            [['base_name_file','name_file', 'name_work'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 250 ],
            [['name_file'], 'unique', 'message' => 'Такой файл уже есть в базе данных'],
            [['id_direction'], 'exist', 'skipOnError' => true, 'targetClass' => GalleryDirection::className(), 'targetAttribute' => ['id_direction' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => GalleryUser::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_direction' => 'Id GalleryDirection',
            'id_user' => 'Id GalleryUser',
            'base_name_file' => 'Базовое имя файла с раширением',
            'name_file' => 'Имя файла',
            'name_work' => 'Наименование работы',
            'description' => 'Описание',
            'best' => 'Best',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGalleryDirection()
    {
        return $this->hasOne(GalleryDirection::className(), ['id' => 'id_direction']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGalleryUser()
    {
        return $this->hasOne(GalleryUser::className(), ['id' => 'id_user']);
    }
}
