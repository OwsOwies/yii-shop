<?php
    use yii\helpers\Html;
    use yii\widgets\LinkPager;
    use yii\helpers\Url;
?>
<h1><?= Html::encode("{$product->name}") ?></h1>
<div>
    <?= Html::encode("{$product->category}") ?>
    <?= Html::tag('img', NULL, ['src' => "https://picsum.photos/400?random={$product->name}"]) ?>
    <p>
        <?= Html::encode("{$product->category}") ?>
    </p>
</div>