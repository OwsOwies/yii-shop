<?php

namespace app\controllers;

use yii\web\Controller;

use yii\data\Pagination;

use app\models\Product;

use yii\helpers\Url;

class ProductController extends Controller
{
    public function actionIndex() {
        $query = Product::find();
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $products = $query
            ->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'products' => $products,
            'pagination' => $pagination,
        ]);
    }

    public function actionSearchFunction($name) {
        return $this->redirect(['single-product/index', 'name' => $name]);
    }
}