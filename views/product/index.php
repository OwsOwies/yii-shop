<?php
    use yii\helpers\Html;
    use yii\widgets\LinkPager;
?>
<h1>Produkty</h1>
<table>
  <tr>
    <th>Name</th>
    <th>Category</th> 
    <th>Count</th>
    <th>Description</th>
  </tr>
  <?php foreach ($products as $product): ?>
    <tr>
        <td><?= Html::encode("{$product->name}") ?></td>
        <td><?= Html::encode("{$product->category}") ?></td> 
        <td><?= Html::encode("{$product->count}") ?></td>
        <td><?= Html::encode("{$product->description}") ?></td>
    </tr>
  <?php endforeach; ?>
</table>
<?= LinkPager::widget(['pagination' => $pagination]) ?>