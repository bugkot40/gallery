<?php


namespace app\models\gallery;

use yii\db\ActiveRecord;

/**
 * Class GalleryAdvertising
 * @package app\models
 * @property string description
 * @property string name_file
 */
class GalleryAdvertising extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery-advertising';
    }
    public function rules()
    {
        return [
            [['name_file'], 'string', 'max' => 50, 'message' => 'Загружаемый файл имеет слишком длинное название'],
        ];

    }
}