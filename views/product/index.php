<?php
    use yii\helpers\Html;
    use yii\widgets\LinkPager;
    use yii\helpers\Url;
?>
<h1>Produkty</h1>
<div style="display: flex; flex-direction: row; height: 100%; justify-content: flex-start; flex-wrap: wrap;">
  <?php foreach ($products as $product): ?>
    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 20px;">
        <?= Html::tag('img', NULL, ['src' => "https://picsum.photos/150?random={$product->name}"]) ?>
        <span><?= Html::encode("{$product->name}") ?></span>
        <span><?= Html::encode("{$product->category}") ?></span>
        <div>
          <?= Html::a('Szczegóły', ['search-function', 'name' => $product->name]) ?>
        </div>
    </div>
  <?php endforeach; ?>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>