<?php

namespace app\controllers;

use Yii;

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

    public function actionAddToCart()
    {
        $data = $_POST['data'];
        $cart = Yii::$app->cart;

        $product = Product::find()
        ->where(['name' => $data['name']])
        ->one();

        if(!$cart->getItem($product->name)){
            $cart->add($product, $data['qty']);
        }
        else {
            $cart->change($product->name, $data['qty']);
        }
        echo 'success';
        echo $product->name;
    }

    public function actionRemoveFromCart($name) {
        $cart = Yii::$app->cart;
        $cart->remove($name);
        return $this->redirect(['/cart/index']);
    }

    public function actionSearchFunction($name) {
        return $this->redirect(['single-product/index', 'name' => $name]);
    }
}