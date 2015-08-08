<?php
$this->pageTitle=Yii::app()->name . ' - '.UsersModule::t("Change Password");
$this->breadcrumbs=array(
    UsersModule::t("Profile") => array('/users/member'),
    UsersModule::t("Change Password"),
);
?>

<h2><?php echo UsersModule::t("Change password"); ?></h2>
<?php echo $this->renderPartial('menu'); ?>

<div class="form">
    <?php $form=$this->beginWidget('UActiveForm', array()); ?>

    <p class="note"><?php echo UsersModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
    <?php echo CHtml::errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password_repeat'); ?>
        <?php echo $form->passwordField($model,'password_repeat'); ?>
        <?php echo $form->error($model,'password_repeat'); ?>
    </div>

    <div class="row submit">
        <?php echo CHtml::submitButton(UsersModule::t("Save")); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->