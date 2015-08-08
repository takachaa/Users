<?php
$this->pageTitle=Yii::app()->name . ' - '.UsersModule::t("Change Email");
$this->breadcrumbs=array(
    UsersModule::t("Profile") => array('/users/member'),
    UsersModule::t("Change Email"),
);
?>

<h2><?php echo UsersModule::t("Change Email"); ?></h2>
<?php echo $this->renderPartial('menu'); ?>

<div class="form">
    <?php $form=$this->beginWidget('UActiveForm', array()); ?>

    <p class="note"><?php echo UsersModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
    <?php echo CHtml::errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email'); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="row submit">
        <?php echo CHtml::submitButton(UsersModule::t("Save")); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->