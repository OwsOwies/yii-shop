<?php

namespace app\controllers;

use Yii;
use OpenPayU_Order;
use OpenPayU_Configuration;
use OpenPayU_Util;
use OpenPayU_Exception;
use OpenPayU_Exception_Request;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Product;

class CartController extends Controller
{

    public function actionSuccess()
    {
        return $this->render('success');
    }

    public function actionError()
    {
        return $this->render('error');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPay()
    {
        $cart = Yii::$app->cart;

        //set Sandbox Environment
        OpenPayU_Configuration::setEnvironment('secure');

        //set POS ID and Second MD5 Key (from merchant admin panel)
        OpenPayU_Configuration::setMerchantPosId('359296');
        OpenPayU_Configuration::setSignatureKey('ec333338d846ac23bd96d2ac2c0d2224');

        //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
        OpenPayU_Configuration::setOauthClientId('359296');
        OpenPayU_Configuration::setOauthClientSecret('f4d60aaa5001c6d3caf816f32686f222');

        $order = array();

        $order['notifyUrl'] = 'http://localhost:8080/cart/success';
        $order['continueUrl'] = 'http://localhost:8080/cart/success';
        
        $order['customerIp'] = '127.0.0.1';
        $order['merchantPosId'] = OpenPayU_Configuration::getOauthClientId() ? OpenPayU_Configuration::getOauthClientId() : OpenPayU_Configuration::getMerchantPosId();
        $order['description'] = 'New order';
        $order['currencyCode'] = 'PLN';
        $order['extOrderId'] = uniqid('', true);
        
        $order['totalAmount'] = intval($cart->getTotalCost())*100;
      
        $items = $cart->getItemIds();

        $i=0;
        foreach($items as $key => $val)
        {
            if($cart->getItem($val)->getQuantity() > 0)
            {
                $product = Product::find()
                ->where(['name' => $val->name])
                ->one();
                echo $product;
            
                $order['products'][$i]['name'] = $product->name;
                $order['products'][$i]['unitPrice'] = $cart->getItem($val)->getPrice()*100;
                $order['products'][$i]['quantity'] = $cart->getItem($val)->getQuantity();
                $i++;
            }
            
        }

        $order['buyer']['email'] = 'test_buyer_email@payu.com';
        $order['buyer']['phone'] = '123123123';
        $order['buyer']['firstName'] = 'Jan';
        $order['buyer']['lastName'] = 'Kowalski';
        $order['buyer']['language'] = 'pl';
        
        $response = OpenPayU_Order::create($order);
        $status_desc = OpenPayU_Util::statusDesc($response->getStatus());
        if ($response->getStatus() == 'SUCCESS') {
            $cart->clear();
            return $this->redirect($response->getResponse()->redirectUri);
        } else {
            return $this->redirect(['/cart/error']);
        } 
    }
}

?>