<?php
namespace app\models\gallery;

/**
 * This is the model class for table "direction".
 * @property integer $id
 * @property string $name
 * @property string $name_file
 * @property string $base_name_file
 * @property GalleryWork[] $works
 */
class GalleryDirection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery-direction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name',], 'string', 'max' => 50, 'message' => 'Название раздела слишком длинное'],
            [['name_file'], 'string', 'max' => 50, 'message' => 'Загружаемый файл имеет слишком длинное название'],
            [['base_name_file'], 'string', 'max' => 50, 'message' => 'Загружаемая иконка имеет слишком длинное название'],
            [['name'], 'unique', 'message' => 'Такой раздел творческого направления уже есть'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryWorks()
    {
        return $this->hasMany(GalleryWork::className(), ['id_direction' => 'id']);
    }
}
