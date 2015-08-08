<?php
$this->pageTitle=Yii::app()->name . ' - '.UsersModule::t("Registration");
?>

    <h1><?php echo UsersModule::t("Registration"); ?></h1>

    <div class="form">
        <?php $form=$this->beginWidget('UActiveForm', array()); ?>

        <p class="note"><?php echo UsersModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

        <?php echo $re = $form->errorSummary(array($model)); ?>

        <div class="row">
            <?php echo $re1 = $form->labelEx($model,'username'); ?>
            <?php echo $re2 = $form->textField($model,'username'); ?>
            <?php echo $re3 = $form->error($model,'username'); ?>
        </div>

        <div class="row">
            <?php echo $re4 = $form->labelEx($model,'password'); ?>
            <?php echo $re5 = $form->passwordField($model,'password'); ?>
            <?php echo $re6 = $form->error($model,'password'); ?>

        </div>

        <div class="row">
            <?php echo $re7 = $form->labelEx($model,'password_repeat'); ?>
            <?php echo $re8 = $form->passwordField($model,'password_repeat'); ?>
            <?php echo $re9 = $form->error($model,'password_repeat'); ?>
        </div>

        <div class="row">
            <?php echo $re10 = $form->labelEx($model,'email'); ?>
            <?php echo $re11 = $form->textField($model,'email'); ?>
            <?php echo $re12 = $form->error($model,'email'); ?>
        </div>




        <div class="row submit">
            <?php echo CHtml::submitButton(UsersModule::t("Register")); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div><!-- form -->
