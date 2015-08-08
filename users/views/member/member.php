<?php
$this->pageTitle=Yii::app()->name . ' - '.UsersModule::t("Profile");

$this->breadcrumbs=array(
    UsersModule::t("Profile") => array('/users/member'),
);


?><h2><?php echo UsersModule::t('Your Profile'); ?></h2>
<?php echo $this->renderPartial('menu'); ?>

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
    </div>
    <br>
<?php endif; ?>
<table class="dataGrid">
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('username')); ?>
        </th>
        <td><?php echo CHtml::encode($model->username); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('email')); ?>
        </th>
        <td><?php echo CHtml::encode($model->email); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('createtime')); ?>
        </th>
        <td><?php
            echo $model->create_time;

            ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('lastvisit')); ?>
        </th>
        <td><?php
            echo $model->lastvisit
            ?>
        </td>
    </tr>

</table>
