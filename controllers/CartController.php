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

    public function actionOrder()
    {
        $cart = Yii::$app->cart;

        //set Sandbox Environment
        OpenPayU_Configuration::setEnvironment('sandbox');

        //set POS ID and Second MD5 Key (from merchant admin panel)
        OpenPayU_Configuration::setMerchantPosId('359373');
        OpenPayU_Configuration::setSignatureKey('df5d6975e742236414fb1f487e9a19f1');

        //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
        OpenPayU_Configuration::setOauthClientId('359373');
        OpenPayU_Configuration::setOauthClientSecret('73dc8091417d3ea2c42afdf204dc38ea');

        $order = array();

        $order['notifyUrl'] = 'http://localhost:8080/cart/success';
        $order['continueUrl'] = 'http://localhost:8080/cart/success';
        
        $order['customerIp'] = '127.0.0.1';
        $order['merchantPosId'] = OpenPayU_Configuration::getOauthClientId() ? OpenPayU_Configuration::getOauthClientId() : OpenPayU_Configuration::getMerchantPosId();
        $order['description'] = 'Order';
        $order['currencyCode'] = 'PLN';
        $order['extOrderId'] = uniqid('', true);
        
        $order['totalAmount'] = intval($cart->getTotalCost() * 100);
      
        $items = $cart->getItemIds();

        $i=0;
        foreach($items as $key => $val)
        {
            if($cart->getItem($val)->getQuantity() > 0)
            {
                $product = Product::find()
                    ->where(['name' => $val])
                    ->one();
            
                $order['products'][$i]['name'] = $product->name;
                $order['products'][$i]['unitPrice'] = $cart->getItem($val)->getPrice() * 100;
                $order['products'][$i]['quantity'] = $cart->getItem($val)->getQuantity();
                $i++;
            }
            
        }

        $order['buyer']['email'] = 'some_email@gmail.com';
        $order['buyer']['phone'] = '123456789';
        $order['buyer']['firstName'] = 'Paweł';
        $order['buyer']['lastName'] = 'Owsianny';
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