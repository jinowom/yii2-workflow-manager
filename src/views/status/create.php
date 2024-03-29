<?php
/**
 * @var yii\web\View $this
 * @var jinowom\workflow\manager\models\form\StatusForm $model
 */

use yii\helpers\Html;

$this->title = Yii::t('workflow', 'Create Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('workflow', 'Workflow'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => $model->status->workflow->id, 'url' => ['default/view', 'id' => $model->status->workflow->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-status-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
