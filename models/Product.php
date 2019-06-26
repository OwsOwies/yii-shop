<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property string $name
 * @property string $category
 * @property int $count
 * @property string $description
 * @property string $price
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'category', 'description'], 'string'],
            [['count'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'category' => 'Category',
            'count' => 'Count',
            'description' => 'Description',
            'price' => 'Price',
        ];
    }
}
