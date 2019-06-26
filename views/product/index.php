<?php
    use yii\helpers\Html;
    use yii\widgets\LinkPager;
    use yii\helpers\Url;
?>
<h1>Products</h1>
<div style="display: flex; flex-direction: row; height: 100%; justify-content: flex-start; flex-wrap: wrap;">
  <?php foreach ($products as $product): ?>
    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 20px; border: 1px solid grey; padding: 5px; width: 220px;">
        <?= Html::tag('img', NULL, ['src' => "https://picsum.photos/150?random={$product->name}"]) ?>
        <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center; width: 100%; margin-top: 10px; margin-bottom: 10px;">
          <div style="display: flex; flex-direction: column; margin-right: 20px; margin-left: 10px;">
            <span><?= Html::encode("{$product->name}") ?></span>
            <span><?= Html::encode("{$product->category}") ?></span>
          </div>
          <div>
            <?= Html::a('Details', ['search-function', 'name' => $product->name]) ?>
          </div>
        </div>
        <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center; width: 100%; margin-top: 10px; margin-bottom: 10px;">
          <div>
            <div><?= Html::encode("Price: {$product->price} EUR") ?></div>
            <div><?= Html::encode("In stock: {$product->count}") ?></div>
            <?= Html::input('number', 'qty', 0, ['step' => 1, 'min' => 0, 'max' => $product->count, 'class' => "qty form-control {$product->name}", 'data-key' => $product->name]) ?>
          </div>
          <?= Html::button('Add to cart', ['class' => "addCartClick btn btn-primary {$product->name}"]); ?>
        </div>
    </div>
  <?php endforeach; ?>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>

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