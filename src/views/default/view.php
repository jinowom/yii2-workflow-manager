<?php
/**
 * @var yii\web\View $this
 * @var jinowom\workflow\manager\models\Workflow $model
 */

use jinowom\workflow\manager\models\Transition;
use yii\helpers\Html;
use jinowom\workflow\view\WorkflowViewWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\jui\Sortable;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use backend\widgets\Breadcrumbs;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('workflow', 'Workflow'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="x-nav">
    <div>
    <span class="layui-breadcrumb">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </span>
    </div>
    <div style="position: absolute; right: 17px;top: 0px;">
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>

</div>
<div class="layui-card-body">
    <div class="view wom-plan-view">
<section id="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-body">

                    <h3>
                        当前流程名是：<?php echo $model->name_cn; ?>
                        
                        <div class="pull-right">
                            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('workflow', '返回工作列表'), ['default/index'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('workflow', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                            <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('workflow', 'Delete'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data-confirm' => Yii::t('workflow', 'Are you sure?'),
                                'data-method' => 'post',
                            ]) ?>
                        </div>
                    </h3>

                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            $sortables = [];
                            foreach ($model->statuses as $status) {
                                $actions = [];
                                $actions[] = '<span class="glyphicon glyphicon-move sortable-handle" style="cursor: move"></span>';
                                if ($model->initial_status_id != $status->id) {
                                    $actions[] = Html::a('<span class="glyphicon glyphicon-star"></span>', ['initial', 'id' => $model->id, 'status_id' => $status->id], ['title' => Yii::t('workflow', 'Set Initial')]);
                                }
                                $actions[] = Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['status/update', 'id' => $status->id, 'workflow_id' => $status->workflow_id], ['title' => Yii::t('workflow', 'Update')]);
                                $actions[] = Html::a('<span class="glyphicon glyphicon-trash"></span>', ['status/delete', 'id' => $status->id, 'workflow_id' => $status->workflow_id], [
                                    'title' => Yii::t('workflow', 'Delete'),
                                    'data-confirm' => Yii::t('workflow', 'Are you sure?'),
                                    'data-method' => 'post',
                                ]);
                                $transitions = $status->startTransitions ? '<br><small><span class="glyphicon glyphicon-chevron-right"></span>&nbsp; &nbsp;' . implode(', ', ArrayHelper::map($status->startTransitions, 'end_status_id', 'endName')) . '</small>' : '';
                                $metadatas = $status->metadatas ? '<br><small><span class="glyphicon glyphicon-tags"></span>&nbsp; &nbsp;' . Json::encode(ArrayHelper::map($status->metadatas, 'key', 'value')) . '</small>' : '';
                                $sortables[] = [
                                    'content' => '<div class="pull-right">' . implode(' ', $actions) . '</div>' . $status->name . $transitions . $metadatas,
                                    'options' => [
                                        'id' => 'Status_' . $status->id,
                                        'class' => 'list-group-item',
                                    ],
                                ];
                            }
                            echo DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'name_cn',
                                        'value' => Html::tag('span', $model->name_cn, ['class' => 'label label-default', 'style' => 'color:#fff;background:' . $model->getColor()]),
                                        'format' => 'raw',
                                    ],
                                    [
                                        'attribute' => 'color',
                                        'label' => Yii::t('app', 'Color'),
                                        'format' => 'raw',
                                    ],
                                    [
                                        'attribute' => 'initial_status_id_cn',
                                        'value' => $model->initial_status_id_cn,
                                    ],
                                    [
                                        'attribute' => 'initial_status_id',
                                        'value' => $model->initial_status_id,
                                    ],
                                    [
                                        'label' => Yii::t('workflow', 'Status') . '<br>' . Html::a(Yii::t('workflow', 'Create Status'), ['status/create', 'workflow_id' => $model->id], ['class' => 'btn btn-success btn-xs']),
                                        'value' => Sortable::widget([
                                            'items' => $sortables,
                                            'options' => [
                                                'class' => 'list-group',
                                                'style' => 'margin-bottom:0;',
                                            ],
                                            'clientOptions' => [
                                                'axis' => 'y',
                                                'update' => new JsExpression("function(event, ui){
                                    $.ajax({
                                        type: 'POST',
                                        url: '" . Url::to(['sort', 'id' => $model->id]) . "',
                                        data: $(event.target).sortable('serialize') + '&_csrf=" . Yii::$app->request->getCsrfToken() . "',
                                        success: function() {
                                            location.reload();
                                        }
                                    });
                                }"),
                                            ],
                                        ]),
                                        'format' => 'raw',
                                    ],
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            if ($model->statuses) {
                                echo WorkflowViewWidget::widget([
                                    'workflow' => Yii::$app->workflowSource->getWorkflow($model->id),
                                    'containerId' => 'workflowView'
                                ]);
                                echo '<div id="workflowView" style="height: 400px;"></div>';
                            }
                            ?>
                        </div>
                    </div>

                    <?php if ($model->statuses) { ?>
                        <?= Html::beginForm(); ?>
                        <h2><?= Yii::t('workflow', 'Transitions') ?></h2>
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th colspan="2" rowspan="2"></th>
                                <th class="text-center"
                                    colspan="<?= count($model->statuses) ?>"><?= Yii::t('workflow', 'End Status') ?></th>
                            </tr>
                            <tr>
                                <?php foreach ($model->statuses as $endStatus) { ?>
                                    <th class="text-center">
                                        <?= $endStatus->name ?>
                                    </th>
                                <?php } ?>
                            </tr>
                            <?php foreach ($model->statuses as $k => $startStatus) { ?>
                                <tr>
                                    <?php if (!$k) { ?>
                                        <th class="text-center"
                                            rowspan="<?= count($model->statuses) ?>"><?= Yii::t('workflow', 'Start Status') ?></th>
                                    <?php } ?>
                                    <th class="text-right"><?= $startStatus->name ?></th>
                                    <?php foreach ($model->statuses as $endStatus) { ?>
                                        <td class="text-center">
                                            <?php
                                            $options = ['uncheck' => 0];
                                            if ($startStatus->id == $endStatus->id) {
                                                unset($options['uncheck']);
                                                $options['disabled'] = true;
                                            }
                                            $transition = Transition::findOne(['workflow_id' => $model->id, 'start_status_id' => $startStatus->id, 'end_status_id' => $endStatus->id]);
                                            echo Html::checkbox('Status[' . $startStatus->id . '][' . $endStatus->id . ']', $transition ? true : false, $options);
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </table>
                        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('workflow', '返回工作列表'), ['default/index'], ['class' => 'btn btn-primary']) ?>
                        <?= Html::submitButton(Yii::t('workflow', 'Save'), ['class' => 'btn btn-success']) ?>
                        <?= Html::endForm(); ?>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</section>
    </div>
</div>