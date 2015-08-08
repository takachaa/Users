<?php
$this->pageTitle=Yii::app()->name . ' - '.UsersModule::t("Login");
$form = $this->beginWidget('UActiveForm', array());
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<h1><?php echo UsersModule::t("Login");  ?></h1>
<div class="form">

<p class="note"><?php echo UsersModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

<div class="row">
    <?php echo $form->errorSummary(array($model));?>
    <div>

        <div class="row">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email'); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'password'); ?>
            <?php echo $form->passwordField($model,'password'); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
        <div class="row">
            <?php echo CHtml::submitButton(UsersModule::t("Login")); ?>
        </div>

        <div class="row">
            <p class="hint">
                <?php echo CHtml::link(UsersModule::t("Register"),Yii::app()->getModule('users')->registrationUrl); ?> |
                <?php echo CHtml::link(UsersModule::t("Lost Password?"),Yii::app()->getModule('users')->recoveryUrl); ?>
            </p>
        </div>

        <div class="row rememberMe">
            <?php echo $form->checkBox($model,'rememberMe'); ?>
            <?php echo $form->label($model,'rememberMe'); ?>
            <?php echo $form->error($model,'rememberMe'); ?>
        </div>

        <?php
        $this->endWidget();
        ?>
</div>
