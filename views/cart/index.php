<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;;
use app\models\Product;

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
                    <td>".$cart->getItem($val)->getQuantity()."</td>
                    <td>$product->price PLN</td>
                    <td>".$cart->getItem($val)->getCost()." PLN</td>
                    <td>
                        <button class=\"removeFromCart btn btn-danger\" data-key=\"$product->name\">Remove</button>
                    </td>
            </tr>";
        }
        ?>
    </table>

    <h2> Total: <?php echo $cart->getTotalCost(); echo " PLN";?> </h2>

    <?php 
        if ($cart->getTotalCount() > 0)
        {
            echo '<button class="payButton" type="submit" style="float: right; border: 0px; height: 50px; width: 290px; background: url(\'http://static.payu.com/pl/standard/partners/buttons/payu_account_button_long_03.png\'); cursor: pointer;"></button>';
        }
    ?>
    
</div>

<?php

$script = <<< JS

$('.payButton').on("click", function(){
    $.ajax({
        type: "get",
        url: "/cart/order",
        success: function() {
            console.log('success');
        }, 
        error: function() {
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