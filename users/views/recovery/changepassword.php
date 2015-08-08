<?php
$this->pageTitle=Yii::app()->name . ' - '.UsersModule::t("Change Password");
?>

<h1><?php echo UsersModule::t("Change Password"); ?></h1>


<div class="form">
    <?php echo CHtml::beginForm(); ?>

    <p class="note"><?php echo UsersModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
    <?php echo CHtml::errorSummary($form); ?>

    <div class="row">
        <?php echo CHtml::activeLabelEx($form,'password'); ?>
        <?php echo CHtml::activePasswordField($form,'password'); ?>
    </div>


    <div class="row">
        <?php echo CHtml::activeLabelEx($form,'password_repeat'); ?>
        <?php echo CHtml::activePasswordField($form,'password_repeat'); ?>
    </div>


    <div class="row submit">
        <?php echo CHtml::submitButton(UsersModule::t("Save")); ?>
    </div>

    <?php echo CHtml::endForm(); ?>
</div><!-- form -->