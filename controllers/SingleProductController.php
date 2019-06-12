<?php

namespace app\controllers;

use yii\web\Controller;

use app\models\Product;

class SingleProductController extends Controller
{
    public function actionIndex($name) {
        $product = Product::find()
            ->where(['name' => $name])
            ->one();

        return $this->render('index', [
            'product' => $product,
        ]);
    }
}