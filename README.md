# Users Extension

## Yii PHP Framework extension for registration and login.

This extension is inspired by the yii-user module and It provides a registration and login form that requires users to enter email and password. if you need an email and password to login form, you can use it.

## Features
This extension has following features.

	•	Login from Email
	•	Logout 
	•	Registration
	•	Activation accounts (send activate key to user email)
	•	Recovery password (send recovery key to user email)
	•	User profile page
	•	Use two tables. One is for registration. The other is for Login.
	•	Change Username. 
	•	Change Password.
	•	Change Email (send recovery key to user email)

## Requirements
Yii 1.1 or above.  
NOTICE: It does not meet Version 2 of Yii.

## Installation
Please install according to the following procedure.

	•	Extract the release file under protected/modules of your project
	•	Change your config main:

```
return array(
    #...
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.modules.users.models.*',
        'application.modules.users.components.*',
    ),
    #...
    'modules'=>array(
        #...
        'users'=>array(),
        #...
    ),
    #...
    'components'=>array(
    #...
        'db'=>array(
        #...
            'tablePrefix' => 'tbl_',
        #...
        ),
        #...
    #...
    ),
    #...
);
```

## SQL Command For Creating tables 
You can use these SQL to create tables for using this extension.
if you need modification, Please use these SQL on base depends on your project.
```
CREATE TABLE `tbl_pre_user_registration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_key` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL,
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `lastvisit` datetime DEFAULT NULL,
  `recovery_key` varchar(255) NOT NULL,
  `recovery_time` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```
