<?php

use thyseus\metronic\widgets\Portlet;
use thyseus\metronic\widgets\GridView;
use thyseus\metronic\widgets\ButtonGroup;
use thyseus\auth0\models\ApiUser;
use thyseus\auth0\models\Tenant;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\growl\Growl;
use kartik\alert\AlertBlock;
/* @var $this yii\web\View */

$this->title = 'Service Admin';
$this->params['breadcrumbs'][] = $this->title;
//$this->params['sidebarItems'] = Yii::$app->params['sidebarItems'];
?>

<div class="row">
<?php Pjax::begin(['id' => 'container-pjax', 'timeout' => false]); ?>

    <!-- BEGIN user portlet -->
    <div class="col-md-6">
    <?php Portlet::begin(['id' => 'user-portlet', 'title' => 'Users Permission', 'subtitle' => 'for this tenant...' ]); ?>

    <?= GridView::widget([
        'id' => 'user-gridview',
        'dataProvider' => new ArrayDataProvider(['allModels' => $userQuery->all(), 'pagination' => ['pageSize' => 10,]]),
        'columns' => ApiUser::column()
            ->nickname()
            ->email()
            ->tenants()
            ->all(),
    ]);?>

    <?php Portlet::end(); ?>
    </div>
    <!-- END user portlet -->

    <!-- BEGIN tenant portlet -->
    <div class="col-md-6">
    <?php //Pjax::begin(['options' => ['id' => 'container-pjax']]); ?>
    <?php
    $checkboxButton =Html::a('<i class="fa fa-trash"></i> Delete selected', '#', ['title' => 'Delete Selected Tenant', 'class' => 'selectCheckboxButton', 'value-url' => 'http://localhost:8100/auth0/tenant/delete-checkbox', 'value-id' => 'tenant-gridview',]);
    Portlet::begin(['id' => 'tenant-portlet', 'title' => 'Tenants', 'subtitle' => 'showing  total users...',
        'buttons' => [
            Html::a('<i class="fa fa-plus"></i>', false, ['value' => Url::to(['tenant/create']), 'title' => 'Create Tenant', 'class' => 'showModalButton btn btn-circle green-haze btn-sm']),
            Html::a('<i class="fa fa-download"></i>', false, ['value' => Url::to(['tenant/import']), 'title' => 'Import Tenant', 'class' => 'showModalButton btn btn-circle blue btn-sm']),
            Html::a('<i class="fa fa-cloud-download"></i>', ['tenant/export'], ['title' => 'Export Tenant', 'class' => 'btn btn-circle yellow btn-sm', 'data-pjax' => 0]),
            Html::a('<i class="fa fa-trash"></i>', ['tenant/delete-all'], ['title' => 'Delete All Tenant', 'class' => 'btn btn-circle red btn-sm', 'data-confirm' => 'Are you sure you want to delete all items?', 'data-method' => 'post', 'data-pjax' => 0]),
        ],
    ]); ?>
    <?= GridView::widget([
        'id' => 'tenant-gridview',
        'dataProvider' => new ActiveDataProvider(['query' => $tenantQuery, 'pagination' => ['pageSize' => 10,]]),
        'columns' => Tenant::column()
            ->nameWithLink()
            ->users()
            ->actions()
            ->all(),
    ]);?>

    <?php Portlet::end(); ?>
    <?php //Pjax::end(); ?>
    </div>
    <!-- END tenant portlet -->

<?php Pjax::end(); ?>
</div>
