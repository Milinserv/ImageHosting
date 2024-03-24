<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $create_date
 * @property string|null $loading_time
 */
class Image extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_date', 'loading_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'create_date' => 'Create Date',
            'loading_time' => 'Loading Time',
        ];
    }

    public function getAllImage(): array
    {
        return Image::find()->all();
    }

    public function saveImage($imageName, $loadingTime, $create_at): bool
    {
        $this->name = $imageName;
        $this->loading_time = $loadingTime;
        $this->create_date = $create_at;

        return $this->save(false);
    }

    public function getImage()
    {
        return '/uploads/' . $this->name;
    }
}
