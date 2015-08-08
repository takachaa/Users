<?php
$this->pageTitle=Yii::app()->name . ' - '.UsersModule::t("Restore");
?>

<h1><?php echo UsersModule::t("Restore"); ?></h1>

    <div class="form">
        <?php echo CHtml::beginForm(); ?>

        <?php echo CHtml::errorSummary($form); ?>

        <div class="row">
            <?php echo CHtml::activeLabel($form,'email'); ?>
            <?php echo CHtml::activeTextField($form,'email') ?>
            <p class="hint"><?php echo UsersModule::t("Please enter your email address."); ?></p>
        </div>

        <div class="row submit">
            <?php echo CHtml::submitButton(UsersModule::t("Restore")); ?>
        </div>

        <?php echo CHtml::endForm(); ?>
    </div><!-- form -->

