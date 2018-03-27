<?php

if (!class_exists("CreateTable")) {

    class CreateTable {

        public function __construct() {
            
        }

       public function on_activate() {

            global $wpdb;

            $create_table_category = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}plugin_quiz_category` (
              `id` int(9) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `name` text NOT NULL
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

            $create_table_question = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}plugin_quiz_question` (
              `id` int(9) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `id_category` int(9) NOT NULL ,
              `pregunta` text NOT NULL,
              `option1` varchar(200) NOT NULL,
              `option2` varchar(200) NOT NULL,
              `option3` varchar(200) NOT NULL,
              `option4` varchar(200) NOT NULL,
              `valid_answer` varchar(200) NOT NULL
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

            $create_table_user = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}plugin_quiz_users` (
              `id` int(9) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `id_jugador` varchar(200) NOT NULL ,
              `id_question` int(9) NOT NULL ,
              `valid_answer` text NOT NULL
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            dbDelta($create_table_category);

            dbDelta($create_table_question);

            dbDelta($create_table_user);
        }

    }

}



