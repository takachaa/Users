<ul class="actions">
    <li><?php echo CHtml::link(UsersModule::t('Change UserName'),array('changeusername')); ?></li>
    <li><?php echo CHtml::link(UsersModule::t('Change E-mail'),array('changeemail')); ?></li>
    <li><?php echo CHtml::link(UsersModule::t('Change Password'),array('changepassword')); ?></li>
    <li><?php echo CHtml::link(UsersModule::t('Logout'),array('/users/logout')); ?></li>
</ul>