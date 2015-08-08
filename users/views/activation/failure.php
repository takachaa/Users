<?php
?>

    <h1><?php echo $title; ?></h1>

    <div>
        <?php echo $content; ?>

    </div>

    <div class="row">
        <p class="hint">
            <?php echo CHtml::link(UsersModule::t("Login"),Yii::app()->getModule('users')->loginUrl); ?>
        </p>
    </div>