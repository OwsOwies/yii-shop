<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;;
use app\models\Product;

$this->title = "Cart";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        $cart = Yii::$app->cart;
        $itemIds = $cart->getItemIds();
        ?>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Name</th>
                    <th>Category</th> 
                    <th>Count</th>
                    <th>Price per unit</th>
                    <th>Total Price</th>
                    <th>Remove</th>
                </tr>
            </thead>
        <?php
        foreach($itemIds as $key => $val)
        {
            if($cart->getItem($val)->getQuantity() > 0)
            {
                $product = Product::find()
                    ->where(['name' => $val])
                    ->one();
            }
            echo "<tr>
                    <td>$product->name</td>
                    <td>$product->category</td>
                    <td>".$cart->getItem($val)->getQuantity()."</td>
                    <td>$product->price EUR</td>
                    <td>".$cart->getItem($val)->getCost()." EUR</td>
                    <td>
                        <button class=\"removeFromCart btn btn-danger\" data-key=\"$product->name\">Remove</button>
                    </td>
            </tr>";
        }
        ?>
    </table>

    <h2> Total: <?php echo $cart->getTotalCost(); echo " EUR";?> </h2>

    <?php 
        if ($cart->getTotalCount() > 0)
        {
            echo '<button class="PayWithPayU" type="submit" style="float: right; border: 0px; height: 50px; width: 290px; background: url(\'http://static.payu.com/pl/standard/partners/buttons/payu_account_button_long_03.png\'); cursor: pointer;"></button>';
        }
    ?>
    
</div>

<?php

$script = <<< JS

$('.PayWithPayU').on("click", function(){
    $.ajax({
        type: "get",
        url: "/cart/pay",
        
        success: function(result) {
            console.log(result);
        }, 
        error: function(result) {
            console.log('error');
        } 
        
    });

});

$('.removeFromCart').on("click", function(){

    $.ajax({
        type: "get",
        url: "/product/remove-from-cart",
        data: { name: $(this).attr('data-key') }, 
    });

});

JS;

$this->registerJs($script);
?>