<?php
    use yii\helpers\Html;
    use yii\widgets\LinkPager;
    use yii\helpers\Url;
?>
<h1><?= Html::encode("{$product->name}") ?></h1>
<div>
    <?= Html::tag('img', NULL, ['src' => "https://picsum.photos/400?random={$product->name}"]) ?>
    <table class="table" style="margin-top: 10px;">
            <tbody class="thead-light">
                <tr>
                    <th>Name</th>
                    <td><?= Html::encode("{$product->name}") ?></td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td><?= Html::encode("{$product->category}") ?></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><?= Html::encode("{$product->description}") ?></td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td><?= Html::encode("{$product->price} PLN") ?></td>
                </tr>
                <tr>
                    <th>In stock</th>
                    <td><?= Html::encode("{$product->count}") ?></td>
                </tr>
            </tbody>
    </table>
    <div style="width: 200px;">
        <?= Html::input('number', 'qty', 0, ['step' => 1, 'min' => 0, 'max' => $product->count, 'class' => "qty form-control {$product->name}", 'data-key' => $product->name]) ?>
        <?= Html::button('Add to cart', ['class' => "addCartClick btn btn-primary {$product->name}"]); ?>
    </div>
</div>

<?php

$script = <<< JS

$('.addCartClick').on("click", function(){
    var prodName = $(this).attr("class").split(" ")[3];
    console.log('added to cart', prodName);

    var data = {
      name: prodName,
      qty: 0,
    };

    $('.qty.' + prodName).each(function(){
      data.qty = $(this).val();
    })

    $.ajax({
        type: "post",
        url: "/product/add-to-cart",
        data: { data: data },

        success: function() {
            console.log('success');
        }, 
        error: function() {
            console.log('error');
        } 
    });

});

JS;

$this->registerJs($script);
?>