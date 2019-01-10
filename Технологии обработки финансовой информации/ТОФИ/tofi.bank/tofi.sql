/*
SQLyog Ultimate v12.14 (64 bit)
MySQL - 5.6.28-log : Database - tofi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tofi` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `tofi`;

/*Table structure for table `wf_admin_structure` */

DROP TABLE IF EXISTS `wf_admin_structure`;

CREATE TABLE `wf_admin_structure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `parent` varchar(255) DEFAULT '',
  `perms` int(11) DEFAULT NULL,
  `hidden` tinyint(1) DEFAULT NULL,
  `uri` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `wf_admin_structure` */

insert  into `wf_admin_structure`(`id`,`title`,`template`,`parent`,`perms`,`hidden`,`uri`) values 
(1,'Заявки на кредит','ApplicationsPage','',10,0,'/index/'),
(2,'Профиль','AdminProfilePage','',100,1,'/profile/'),
(3,'Структура','StructurePage','',100,0,'/structure/'),
(4,'Админка','AdminStructurePage','StructurePage',255,0,'/admin-structure/'),
(5,'Шаблоны структуры','StructureTemplatesPage','StructurePage',255,0,'/structure-templates/'),
(6,'Файлы','CkeditorPage','',100,1,'/files/'),
(7,'Настройки','SettingsPage','',100,0,'/settings/'),
(8,'Локализация','LocalizerManagementPage','StructurePage',100,0,'/localizer/'),
(9,'Пользователи','UserPage','',100,0,'/users/'),
(14,'Тесты','TestsPage','CandidatesPage',100,0,'/tests/'),
(22,'Кандидаты','CandidatesPage','',100,0,'/candidates/'),
(23,'Письмо кандидатам','CandidatesMailPage','CandidatesPage',100,0,'/candidates-mail/'),
(25,'Кредиты','CreditsPage','',10,0,'/credits/');

/*Table structure for table `wf_applications` */

DROP TABLE IF EXISTS `wf_applications`;

CREATE TABLE `wf_applications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `percent` float DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `term_months` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `cdate` datetime DEFAULT NULL,
  `udate` datetime DEFAULT NULL,
  `fio` varchar(255) DEFAULT NULL,
  `request_printed` varchar(255) DEFAULT NULL,
  `income_statement` varchar(255) DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `military_id` varchar(255) DEFAULT NULL,
  `insurance_certificate` varchar(255) DEFAULT NULL,
  `third_party_documents` varchar(255) DEFAULT NULL,
  `passport_info` text,
  `address_info` text,
  `phone` varchar(255) DEFAULT NULL,
  `comments` text,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `wf_applications` */

insert  into `wf_applications`(`id`,`title`,`percent`,`amount`,`term_months`,`status`,`cdate`,`udate`,`fio`,`request_printed`,`income_statement`,`passport`,`military_id`,`insurance_certificate`,`third_party_documents`,`passport_info`,`address_info`,`phone`,`comments`,`user_id`) values 
(1,'Кредит на 5 месяцев под 700%',700,45,5,6,NULL,'2017-11-02 21:38:11','Иванов Иван Иванович','/content/Files/4.jpg','/content/Files/4.jpg','/content/Files/4.jpg','/content/Files/4.jpg','/content/Files/4.jpg','/content/Files/4.jpg','234234 выдан Первомайским РУВД города Крыжополя','Крыжополь, ул. Булочковая, д. 1','+375-23-234234234234','',33),
(2,'Кредит на 10 месяцев под 30%',30,1000,10,6,'2017-11-04 18:35:10','2017-11-04 18:56:00','Иванов Петр Петрович','/content/Files/4.jpg','/content/Files/4.jpg','/content/Files/4.jpg','','/content/Files/4.jpg','','242343234 выдан Октябрьским РУВД г.Минска 10.10.1982','г.Минск, ул. Толстого, д.10','+375-29-1563463','',33),
(3,'Кредит на 6 месяцев под 30%',30,1000,6,6,'2017-11-04 22:11:40','2017-11-04 22:12:51','Петренко Иван Васильевич','/content/Files/4.jpg','/content/Files/4.jpg','/content/Files/4.jpg','','/content/Files/4.jpg','','выдан паспорт','дом 3','+375-29-1467533','',39);

/*Table structure for table `wf_credits` */

DROP TABLE IF EXISTS `wf_credits`;

CREATE TABLE `wf_credits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `fio` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fine` float NOT NULL DEFAULT '0',
  `paid` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `wf_credits` */

insert  into `wf_credits`(`id`,`title`,`amount`,`date_start`,`date_end`,`application_id`,`fio`,`user_id`,`fine`,`paid`) values 
(4,'Кредит на 5 месяцев под 700%',131.25,'2017-11-02','2018-04-02',1,'Иванов Иван Иванович',33,0,1),
(5,'Кредит на 10 месяцев под 30%',250,'2017-11-04','2018-09-04',2,'Иванов Петр Петрович',38,0,0),
(6,'Кредит на 6 месяцев под 30%',1087.52,'2017-11-04','2018-05-04',3,'Петренко Иван Васильевич',38,0,0);

/*Table structure for table `wf_credits_fact` */

DROP TABLE IF EXISTS `wf_credits_fact`;

CREATE TABLE `wf_credits_fact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `date` date DEFAULT NULL,
  `credit_id` int(11) DEFAULT NULL,
  `is_fine` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `wf_credits_fact` */

insert  into `wf_credits_fact`(`id`,`title`,`amount`,`date`,`credit_id`,`is_fine`) values 
(6,'Оплата пени 6 рублей 2017-11-04',6,'2017-11-04',4,1);

/*Table structure for table `wf_credits_plan` */

DROP TABLE IF EXISTS `wf_credits_plan`;

CREATE TABLE `wf_credits_plan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `date` date DEFAULT NULL,
  `credit_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `wf_credits_plan` */

insert  into `wf_credits_plan`(`id`,`title`,`amount`,`date`,`credit_id`) values 
(6,'Оплата 131.25 рублей 2017-12-02',26.25,'2017-12-02',4),
(7,'Оплата 131.25 рублей 2018-01-02',26.25,'2018-01-02',4),
(8,'Оплата 131.25 рублей 2018-02-02',26.25,'2018-02-02',4),
(9,'Оплата 131.25 рублей 2018-03-02',26.25,'2018-03-02',4),
(10,'Оплата 131.25 рублей 2018-04-02',26.25,'2018-04-02',4),
(11,'Оплата 25 рублей 2017-12-04',25,'2017-12-04',5),
(12,'Оплата 25 рублей 2018-01-04',25,'2018-01-04',5),
(13,'Оплата 25 рублей 2018-02-04',25,'2018-02-04',5),
(14,'Оплата 25 рублей 2018-03-04',25,'2018-03-04',5),
(15,'Оплата 25 рублей 2018-04-04',25,'2018-04-04',5),
(16,'Оплата 25 рублей 2018-05-04',25,'2018-05-04',5),
(17,'Оплата 25 рублей 2018-06-04',25,'2018-06-04',5),
(18,'Оплата 25 рублей 2018-07-04',25,'2018-07-04',5),
(19,'Оплата 25 рублей 2018-08-04',25,'2018-08-04',5),
(20,'Оплата 25 рублей 2018-09-04',25,'2018-09-04',5),
(21,'Оплата 191.67 рублей 2017-12-04',191.67,'2017-12-04',6),
(22,'Оплата 187.5 рублей 2018-01-04',187.5,'2018-01-04',6),
(23,'Оплата 183.34 рублей 2018-02-04',183.34,'2018-02-04',6),
(24,'Оплата 179.17 рублей 2018-03-04',179.17,'2018-03-04',6),
(25,'Оплата 175 рублей 2018-04-04',175,'2018-04-04',6),
(26,'Оплата 170.84 рублей 2018-05-04',170.84,'2018-05-04',6);

/*Table structure for table `wf_loc_lang` */

DROP TABLE IF EXISTS `wf_loc_lang`;

CREATE TABLE `wf_loc_lang` (
  `id_lang` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  `uri` varchar(60) DEFAULT NULL,
  `locale` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id_lang`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `wf_loc_lang` */

insert  into `wf_loc_lang`(`id_lang`,`name`,`uri`,`locale`) values 
(1,'Русский','','ru_RU.UTF8');

/*Table structure for table `wf_loc_strings` */

DROP TABLE IF EXISTS `wf_loc_strings`;

CREATE TABLE `wf_loc_strings` (
  `id_string` int(11) NOT NULL AUTO_INCREMENT,
  `id_lang` int(11) NOT NULL,
  `auto_created` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` varchar(150) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id_string`)
) ENGINE=InnoDB AUTO_INCREMENT=691 DEFAULT CHARSET=utf8;

/*Data for the table `wf_loc_strings` */

insert  into `wf_loc_strings`(`id_string`,`id_lang`,`auto_created`,`name`,`value`,`description`) values 
(1,1,0,'nav_title_loc_strings','Локализация',''),
(2,1,0,'loc_strings_add','Добавление локализации',''),
(3,1,0,'loc_strings_edit','Редактирование локализации',''),
(4,1,0,'v_info_loc_strings_add','Локализация успешно добавлена.',''),
(5,1,0,'v_info_loc_strings_update','Локализация успешно обновлена.',''),
(6,1,0,'nav_title_structure_content','Поля',''),
(7,1,0,'structure_content_add','Добавление поля',''),
(8,1,0,'structure_content_edit','Редактирование поля',''),
(9,1,0,'v_info_structure_content_add','Поле успешно добавлено.',''),
(10,1,0,'v_info_structure_content_update','Поле успешно обновлено.',''),
(11,1,0,'type','Тип',''),
(12,1,0,'required','Обязательное',''),
(13,1,0,'nav_title_products_images','Изображения',''),
(14,1,0,'products_images_add','Добавление изображения',''),
(15,1,0,'products_images_edit','Редактирование изображения',''),
(16,1,0,'v_info_products_images_add','Изображение успешно добавлено.',''),
(17,1,0,'v_info_products_images_update','Изображение успешно обновлено.',''),
(18,1,0,'v_tree_up','Перемещение вверх',''),
(19,1,0,'v_tree_up_inact','Перемещение вверх недоступно',''),
(20,1,0,'v_tree_down','Перемещение вниз',''),
(21,1,0,'v_tree_down_inact','Перемещение вниз недоступно',''),
(22,1,0,'v_tree_delete','Удаление',''),
(23,1,0,'v_tree_delete_inact','Удаление недоступно',''),
(24,1,0,'v_tree_add','Добавление',''),
(25,1,0,'v_tree_add_inact','Добавление недоступно',''),
(26,1,0,'v_tree_edit','Редактирование',''),
(27,1,0,'v_tree_edit_inact','Редактирование недоступны',''),
(28,1,0,'nav_title_articles_images','Изображения',''),
(29,1,0,'articles_images_add','Добавление изображения',''),
(30,1,0,'articles_images_edit','Редактирование изображения',''),
(31,1,0,'v_info_articles_images_add','Изображение успешно добавлено.',''),
(32,1,0,'v_info_articles_images_update','Изображение успешно обновлено.',''),
(33,1,0,'nav_title_me_members','Пользователи',''),
(34,1,0,'me_members_add','Добавление пользователя',''),
(35,1,0,'me_members_edit','Редактирование пользователя',''),
(36,1,0,'v_info_me_members_add','Пользователь успешно добавлен.',''),
(37,1,0,'v_info_me_members_update','Пользователь успешно обновлён.',''),
(38,1,0,'login_no_such_user','Такой пользователь отсутствует.',''),
(39,1,0,'v_info_admin_update','Данные успешно обновлены.',''),
(40,1,0,'error_pass_confirm','Пароль и его подтверждение различаются.',''),
(41,1,0,'guest','Неавторизованный пользователь',''),
(42,1,0,'user','Зарегистрированный пользователь',''),
(44,1,0,'content_manager','Младший менеджер',''),
(45,1,0,'admin','Администратор',''),
(46,1,0,'supervisor','Администратор',''),
(47,1,0,'admin_area','Разделы администрирования',''),
(48,1,0,'structure_area','Разделы структуры сайта',''),
(49,1,0,'login','Логин',''),
(50,1,0,'password','Пароль',''),
(51,1,0,'email','Email',''),
(52,1,0,'a_password','Пароль',''),
(53,1,0,'confirm_password','Подтверждение пароля',''),
(54,1,0,'id_type','Тип',''),
(55,1,0,'new_login','Логин',''),
(56,1,0,'new_email','Email',''),
(57,1,0,'new_password','Пароль',''),
(58,1,0,'new_confirm_password','Подтверждение пароля',''),
(59,1,0,'user_permissions','Допуски пользователя',''),
(60,1,0,'id_level','Уровень доступа',''),
(61,1,0,'login_form_name','Логин',''),
(62,1,0,'login_form_password','Пароль',''),
(63,1,0,'login_form_store','Запомнить',''),
(64,1,0,'logout_question','Вы уверены?',''),
(65,1,0,'txt_registration_request','Запрос на регистрацию',''),
(66,1,0,'txt_forgot_password','Восcтановление пароля',''),
(67,1,0,'txt_error_user_exist','Пользователь с таким адресом отсутствует',''),
(68,1,0,'nav_title_structure_content_values','Редактирование данных страницы',''),
(69,1,0,'v_info_structure_content_values_update','Данные успешно обновлены.',''),
(77,1,0,'filter_by_id_structure','Фильтр по категории',''),
(78,1,0,'id_structure','Категория',''),
(79,1,0,'txt_name','Вас зовут',''),
(80,1,0,'txt_email','Ваш е-mail',''),
(81,1,0,'txt_message','Ваш вопрос',''),
(82,1,0,'txt_captcha','Антиспам',''),
(83,1,0,'txt_send','отправить',''),
(84,1,0,'txt_error_email_send','Произошла ошибка и Ваше сообщение не может быть отправлено. Попробуйте позже.',''),
(85,1,0,'txt_contact_success','Ваше сообщение успешно отправлено!',''),
(86,1,0,'nav_title_faq_types','Типы вопросов',''),
(87,1,0,'faq_types_add','Добавление типа',''),
(88,1,0,'faq_types_edit','Редактирование типа',''),
(89,1,0,'v_info_faq_types_add','Тип успешно добавлен.',''),
(90,1,0,'v_info_faq_types_update','Тип успешно обновлен.',''),
(91,1,0,'nav_title_products','Продукция',''),
(92,1,0,'tab_title_products','Продукция',''),
(93,1,0,'tab_title_products_images','Изображения',''),
(98,1,0,'filter_by_id_category','Фильтр по категории',''),
(99,1,0,'id_category','Категория',''),
(105,1,0,'nav_title_structure','Страницы',''),
(106,1,0,'tab_title_structure','Страница',''),
(107,1,0,'tab_title_structure_data','Данные',''),
(108,1,0,'structure_add','Добавление страницы',''),
(109,1,0,'structure_edit','Редактирование страницы',''),
(110,1,0,'v_info_structure_add','Страница успешно добавлена.',''),
(111,1,0,'v_info_structure_update','Страница успешно обновлена.',''),
(112,1,0,'system','Системное имя',''),
(113,1,0,'id_template','Шаблон',''),
(114,1,0,'show_in_menu','Отображать в меню',''),
(115,1,0,'can_edit','Давать доступ к системным настройкам',''),
(116,1,0,'can_add','Разрешить добавление подразделов',''),
(117,1,0,'unique_field_system_error','Такое значение системного имени уже существует в данном разделе структуры.',''),
(118,1,0,'nav_title_structure_templates','Шаблоны',''),
(119,1,0,'tab_title_structure_templates','Шаблон',''),
(120,1,0,'tab_title_structure_templates_data_fields','Поля данных',''),
(121,1,0,'tab_title_structure_templates_data_lists','Списки данных',''),
(122,1,0,'structure_templates_add','Добавление шаблона',''),
(123,1,0,'structure_templates_edit','Редактирование шаблона',''),
(124,1,0,'v_info_structure_templates_add','Шаблон успешно добавлена.',''),
(125,1,0,'v_info_structure_templates_update','Шаблон успешно обновлена.',''),
(126,1,0,'action','Динамический',''),
(127,1,0,'validator_isrequired','Заполните поле \"%1$s\".',''),
(128,1,0,'validator_enumeration','Поле \"%1$s\" заполнено неправильно.',''),
(129,1,0,'validator_isemail','Введите корректный Email в поле \"%1$s\".',''),
(130,1,0,'validator_isurl','Введите корректный URI в поле \"%1$s\".',''),
(131,1,0,'validator_issystem','Введите корректное системное имя в поле \"%1$s\".',''),
(132,1,0,'validator_isdate','Введите корректную дату в поле \"%1$s\".',''),
(133,1,0,'validator_isnumber','Введите число в поле \"%1$s\".',''),
(134,1,0,'validator_maxlength','Введите не больше %2$d символа(ов) в поле \"%1$s\".',''),
(135,1,0,'validator_minlength','Введите по меньшей мере %2$d символа(ов) в поле \"%1$s\".',''),
(136,1,0,'validator_rangelength','Введите по меньшей мере %2$d символа(ов), но не больше %3$d символа(ов), в поле \"%1$s\".',''),
(137,1,0,'validator_maxvalue','В поле \"%1$s\" введите значение меньшее либо равное %2$s.',''),
(138,1,0,'validator_minvalue','Введите значение большее либо равное чем %2$s в поле \"%1$s\".',''),
(139,1,0,'validator_rangevalue','Введите значение большее либо равное чем %2$s, но меньшее либо равное %2$s, в поле \"%1$s\".',''),
(140,1,0,'validator_equalto','Значение поля \"%1$s\" должно быть равно значению поля \"%2$s\".',''),
(141,1,0,'validator_accept','Выберите файл корректного типа в поле \"%1$s\", разрешённые типы \"%2$s\".',''),
(142,1,0,'administrative_suite','Aдминистративная часть',''),
(143,1,0,'exit_suite','Выход',''),
(144,1,0,'admin_area_id_lang','Язык',''),
(145,1,0,'sub_menu_title','Меню раздела',''),
(146,1,0,'fields_marked_with','Поля обозначенные ',''),
(147,1,0,'are_obligatory','обязательны',''),
(148,1,0,'total','всего',''),
(149,1,0,'database_error','Ошибка базы данных',''),
(150,1,0,'data_updated','Данные успешно обновлены.',''),
(151,1,0,'no_records','К сожалению, нету записей для отображения.',''),
(152,1,0,'no_selected_items','Не выбрано ни одной записи.',''),
(153,1,0,'conf_sure_leave','Вы действительно хотите покинуть текущую страницу?',''),
(154,1,0,'conf_sure_delete','Вы действительно хотите УДАЛИТЬ выбранные данные?',''),
(155,1,0,'conf_sure_show','Вы действительно хотите ОПУБЛИКОВАТЬ выбранные данные?',''),
(156,1,0,'filter_by_title','Фильтр по названию',''),
(157,1,0,'filter_by_name','Фильтр по названию',''),
(158,1,0,'filter_by_value','Фильтр по значению',''),
(159,1,0,'filter_by_login','Фильтр по логину',''),
(160,1,0,'filter_by_email','Фильтр по email',''),
(161,1,0,'filter_by_id_level','Фильтр по уровню доступа',''),
(162,1,0,'filter_by_date','Фильтр по дате',''),
(163,1,0,'filter_by_status','Фильтр по статусу',''),
(164,1,0,'unique_field_title_error','Название должно быть уникально.',''),
(165,1,0,'a_greater_then_b_error','%3$s:значение %2$s должно быть больше или равно значению %1$s.',''),
(166,1,0,'from','с',''),
(167,1,0,'to','по',''),
(168,1,0,'yes','Да',''),
(169,1,0,'no','Нет',''),
(170,1,0,'all','Все',''),
(171,1,0,'pages','Страницы',''),
(172,1,0,'sort_id','Порядок',''),
(173,1,0,'button_login','Войти',''),
(174,1,0,'button_clear','Очистить',''),
(175,1,0,'button_search','Поиск',''),
(176,1,0,'button_add','Добавить',''),
(177,1,0,'button_save','Сохранить',''),
(178,1,0,'button_cancel','Отмена',''),
(179,1,0,'button_delete','Удалить',''),
(180,1,0,'button_show','Опубликовать',''),
(181,1,0,'button_preview','Предпросмотр',''),
(182,1,0,'button_send','Отправить',''),
(183,1,0,'status','Статус',''),
(184,1,0,'hided','Скрыто',''),
(185,1,0,'published','Опубликовано',''),
(186,1,0,'active','Опубликовать',''),
(187,1,0,'inactive','Неактивен',''),
(188,1,0,'sequence','Порядок',''),
(189,1,0,'title','Название',''),
(190,1,0,'name','Название',''),
(191,1,0,'anonce','Анонс',''),
(192,1,0,'description','Текст',''),
(193,1,0,'id_lang','Язык',''),
(194,1,0,'short_text','Краткое описание',''),
(195,1,0,'text','Текст',''),
(196,1,0,'date','Дата',''),
(197,1,0,'price','Цена',''),
(198,1,0,'image','Изображение',''),
(199,1,0,'flash','Flash ролик',''),
(200,1,0,'delete','Удалить',''),
(201,1,0,'featured','Анонсировать',''),
(202,1,0,'link','Ссылка',''),
(203,1,0,'uri','URI',''),
(204,1,0,'subedit','Данные',''),
(205,1,0,'nav_title_subedit_list','Список данных',''),
(206,1,0,'subedit_title','Название',''),
(207,1,0,'subedit_file','Файл',''),
(208,1,0,'subedit_image','Изображение',''),
(209,1,0,'subedit_list_add','Добавление данных',''),
(210,1,0,'v_subedit_add','Данные удачно добавлены',''),
(211,1,0,'v_subedit_edit','Данные удачно изменёны.',''),
(212,1,0,'subedit_list_edit','Редактирование данных',''),
(213,1,0,'nav_title_faq','Вопросы',''),
(214,1,0,'faq_add','Добавление вопроса',''),
(215,1,0,'faq_edit','Редактирование вопроса',''),
(216,1,0,'v_info_faq_add','Вопрос успешно добавлен.',''),
(217,1,0,'v_info_faq_update','Вопрос успешно обновлен.',''),
(218,1,0,'id_faq_type','Тип вопроса',''),
(219,1,0,'answer','Ответ',''),
(220,1,0,'nav_title_settings','Настройки',''),
(221,1,0,'settings_add','Добавление настройки',''),
(222,1,0,'settings_edit','Редактирование настройки',''),
(223,1,0,'v_info_settings_add','Настройка успешно добавлена.',''),
(224,1,0,'v_info_settings_update','Настройка успешно обновлена.',''),
(225,1,0,'hidden','Скрыто',''),
(226,1,0,'validator','Валидация',''),
(227,1,0,'comment','Комментарий',''),
(228,1,0,'value','Значение',''),
(229,1,0,'nav_title_products_categories','Категория',''),
(230,1,0,'products_categories_add','Добавление категории',''),
(231,1,0,'products_categories_edit','Редактирование категории',''),
(232,1,0,'v_info_products_categories_add','Категория успешно добавлена.',''),
(233,1,0,'v_info_products_categories_update','Категория успешно обновлена.',''),
(234,1,0,'nav_title_structure_attachables','Списки',''),
(235,1,0,'structure_attachables_add','Добавление списка',''),
(236,1,0,'structure_attachables_edit','Редактирование списка',''),
(237,1,0,'v_info_structure_attachables_add','Список успешно добавлен.',''),
(238,1,0,'v_info_structure_attachables_update','Список успешно обновлен.',''),
(239,1,0,'nav_title_structure_images','Изображения',''),
(240,1,0,'structure_images_add','Добавление изображения',''),
(241,1,0,'structure_images_edit','Редактирование изображения',''),
(242,1,0,'v_info_structure_images_add','Изображение успешно добавлено.',''),
(243,1,0,'v_info_structure_images_update','Изображение успешно обновлено.',''),
(244,1,0,'nav_title_admin_structure','Разделы',''),
(245,1,0,'admin_structure_add','Добавление раздела',''),
(246,1,0,'admin_structure_edit','Редактирование раздела',''),
(247,1,0,'v_info_admin_structure_add','Раздел успешно добавлена.',''),
(248,1,0,'v_info_admin_structure_update','Раздел успешно обновлена.',''),
(249,1,0,'template','Шаблон',''),
(250,1,0,'parent','Родитель',''),
(251,1,0,'perms','Доступ',''),
(252,1,0,'scv_meta_title','Meta Title',''),
(253,1,0,'scv_meta_description','Meta Description',''),
(254,1,0,'scv_meta_keywords','Meta Keywords',''),
(255,1,0,'scv_promo_image','Промо-изображение',''),
(256,1,0,'scv_promo_text','Текст-обращение',''),
(257,1,0,'scv_content','Сообщение о ошибке',''),
(258,1,0,'scv_redirect_link','Ссылка на страницу',''),
(259,1,0,'scv_items_per_page','Записей на странице',''),
(260,1,0,'scv_contact_information','Контактная информация',''),
(261,1,0,'scv_map_image','Карта проезда',''),
(262,1,0,'scv_additional_information','Дополнительная информация',''),
(263,1,0,'scv_email_from','Email адрес отправителя',''),
(264,1,0,'scv_email_to','Email адрес получателя',''),
(265,1,0,'scv_email_subject','Тема письма',''),
(266,1,0,'scv_email_message','Шаблон письма',''),
(267,1,0,'scv_image','Изображение',''),
(268,1,0,'scv_anonce','Описание',''),
(269,1,1,'arhitector','arhitector','arhitector'),
(270,1,1,'index_welcome','Добро пожаловать','Добро пожаловать'),
(271,1,1,'index_on_company_site','на сайт компании','на сайт компании'),
(272,1,0,'scv_index_image','Изображение',''),
(273,1,0,'scv_index_anonce','Короткое описание',''),
(274,1,1,'404_page_not_found','Страница не найдена','Страница не найдена'),
(275,1,1,'anonce_advantage','Преимущества','anonce_advantage'),
(276,1,1,'tab_title_products_files','Файлы','tab_title_products_files'),
(277,1,1,'tab_title_products_links','Ссылки','tab_title_products_links'),
(278,1,1,'file','Файл','file'),
(279,1,1,'nav_title_products_files','Файлы','nav_title_products_files'),
(280,1,1,'nav_title_products_links','Ссылки','nav_title_products_links'),
(281,1,1,'products_links_add','Добавление ссылки','products_links_add'),
(282,1,1,'v_info_products_links_add','Ссылка добавлена','v_info_products_links_add'),
(283,1,1,'products_links_edit','Редактирование ссылки','products_links_edit'),
(284,1,1,'v_info_products_links_update','Ссылка обновлена','v_info_products_links_update'),
(285,1,1,'products_files_add','Добавление файла','products_files_add'),
(286,1,1,'v_info_products_files_add','Файл добавлен','v_info_products_files_add'),
(287,1,1,'products_files_edit','Редактирование файла','products_files_edit'),
(288,1,1,'v_info_products_files_update','Файл обновлен','v_info_products_files_update'),
(289,1,1,'more_about_company','Подробнее о компании','Подробнее о компании'),
(290,1,0,'scv_contents','Информация',''),
(291,1,1,'tab_title_articles','Статья','tab_title_articles'),
(292,1,1,'tab_title_articles_images','Изображения','tab_title_articles_images'),
(293,1,1,'nav_title_articles','Статьи','nav_title_articles'),
(294,1,1,'articles_add','Добавление статьи','articles_add'),
(295,1,1,'v_info_articles_add','Статья успешно добавлена.','v_info_articles_add'),
(296,1,1,'articles_edit','Редактирование статьи','articles_edit'),
(297,1,1,'v_info_articles_update','Статья успешно обновлена.','v_info_articles_update'),
(298,1,1,'nav_title_structure_files','Файлы','nav_title_structure_files'),
(299,1,1,'structure_files_edit','Редактировать файл','structure_files_edit'),
(300,1,1,'v_info_structure_files_update','Файл обновлен','v_info_structure_files_update'),
(301,1,1,'structure_files_add','Добавить файл','structure_files_add'),
(302,1,1,'v_info_structure_files_add','Файл добавлен','v_info_structure_files_add'),
(303,1,1,'tab_title_articles_files','Файлы','tab_title_articles_files'),
(304,1,1,'nav_title_articles_files','Файлы','nav_title_articles_files'),
(305,1,1,'articles_files_add','Добавление файла','articles_files_add'),
(306,1,1,'v_info_articles_files_add','Файл добавлен','v_info_articles_files_add'),
(307,1,1,'articles_files_edit','Редактирование файла','articles_files_edit'),
(308,1,1,'v_info_articles_files_update','Файл обновлен','v_info_articles_files_update'),
(309,1,1,'show_date','Показывать дату','show_date'),
(310,1,0,'scv_banner1','Баннер 1',''),
(311,1,0,'scv_banner1_link','Ссылка баннера 1',''),
(312,1,0,'scv_banner2','Баннер 2',''),
(313,1,0,'scv_banner2_link','Ссылка баннера 2',''),
(314,1,1,'change_color_gamma','Изменить цветовую гамму','Изменить цветовую гамму'),
(315,1,1,'footer_ttl_contacts','Контакы:','Контакы:'),
(316,1,1,'footer_ttl_moscow','Москва','Москва'),
(317,1,1,'footer_ttl_minsk','Минск','Минск'),
(318,1,1,'nav_title_homepage_annonces','Анонсы','nav_title_homepage_annonces'),
(319,1,1,'homepage_annonces_add','Добавление анонса','homepage_annonces_add'),
(320,1,1,'v_info_homepage_annonces_add','Анонс добавлен','v_info_homepage_annonces_add'),
(321,1,1,'homepage_annonces_edit','Редактирование анонса','homepage_annonces_edit'),
(322,1,1,'v_info_homepage_annonces_update','Анонс обновлен','v_info_homepage_annonces_update'),
(323,1,0,'scv_contents_moscow_office','Информация: Офис в Москве',''),
(324,1,0,'scv_contents_minsk_office','Информация: Офис в Минске',''),
(325,1,0,'scv_coordinates_minsk','Координаты на карте: Минск',''),
(326,1,0,'scv_coordinates_moscow','Координаты на карте: Москва',''),
(327,1,0,'scv_approximation_minsk','Приближение карты: Минск',''),
(328,1,0,'scv_approximation_moscow','Приближение карты: Москва',''),
(329,1,0,'scv_coordinates_center_minsk','Координаты: Центр Минска',''),
(330,1,0,'scv_coordinates_center_moskow','Координаты: Центр Москвы',''),
(331,1,1,'ttl_contact_office_moscow','Офис в Москве','Офис в Москве'),
(332,1,1,'ttl_contact_office_minsk','Головной офис в Минске','Головной офис в Минске'),
(333,1,0,'scv_subheader','Подзаголовок',''),
(334,1,1,'nav_title_projects','Проекты','nav_title_projects'),
(335,1,1,'projects_add','Добавление проекта','projects_add'),
(336,1,1,'v_info_projects_add','Проект добавлен','v_info_projects_add'),
(337,1,1,'projects_edit','Редактирование проекта','projects_edit'),
(338,1,1,'v_info_projects_update','Проект обновлен','v_info_projects_update'),
(339,1,1,'tab_title_projects','Проекты','tab_title_projects'),
(340,1,1,'tab_title_projects_images','Изображения','tab_title_projects_images'),
(341,1,1,'nav_title_projects_images','Изображения','nav_title_projects_images'),
(342,1,1,'projects_images_add','Добавление изображения проекта','projects_images_add'),
(343,1,1,'v_info_projects_images_add','Изображение проекта добавлено','v_info_projects_images_add'),
(344,1,1,'projects_images_edit','Редактирование изображения проекта','projects_images_edit'),
(345,1,1,'v_info_projects_images_update','Изображение проекта обновлено','v_info_projects_images_update'),
(346,1,0,'scv_annonce','Анонс',''),
(347,1,1,'read_more','Читать полностью','Читать полностью'),
(348,1,1,'prev_photo','Предыдущее фото','Предыдущее фото'),
(349,1,1,'next_photo','Следующее фото','Следующее фото'),
(350,1,1,'photo','фото','фото'),
(351,1,1,'choosen','Избранное','Избранное'),
(352,1,1,'favourite','Избранное','favourite'),
(353,1,1,'filter_by_array','filter_by_Array','filter_by_Array'),
(354,1,1,'array','',''),
(355,1,1,'mandatory','Обязательное','mandatory'),
(356,1,1,'type_string','Строка','type_string'),
(357,1,1,'type_html','HTML','type_html'),
(358,1,1,'type_text','Текст','type_text'),
(359,1,1,'type_date','Дата','type_date'),
(360,1,1,'type_bool','Булево','type_bool'),
(361,1,1,'type_file','Файл','type_file'),
(362,1,1,'type_number','Число','type_number'),
(363,1,1,'type_email','Email','type_email'),
(364,1,1,'type_url','URL','type_url'),
(365,1,1,'type_system','Системная метка','type_system'),
(387,1,1,'nav_title_wf_structure','Структура','nav_title_wf_structure'),
(388,1,1,'wf_structure_edit','Редактирование страницы','wf_structure_edit'),
(389,1,1,'nav_title_wf_structure_content_values','Редактирование данных','nav_title_wf_structure_content_values'),
(390,1,1,'wf_structure_templates_edit','Редактирование шаблона','wf_structure_templates_edit'),
(391,1,1,'nav_title_wf_structure_content','Поля','nav_title_wf_structure_content'),
(392,1,1,'nav_title_wf_structure_attachables','Списки','nav_title_wf_structure_attachables'),
(393,1,1,'wf_structure_content_add','Добавление поля ','wf_structure_content_add'),
(394,1,1,'wf_structure_content_edit','Редактирование поля','wf_structure_content_edit'),
(395,1,1,'v_info_wf_structure_content_add','Поле добавлено','v_info_wf_structure_content_add'),
(396,1,1,'v_info_wf_structure_content_update','Поле сохранено','v_info_wf_structure_content_update'),
(397,1,1,'wf_structure_add','Добавление страницы','wf_structure_add'),
(398,1,1,'v_info_wf_structure_add','Страница добавлена','v_info_wf_structure_add'),
(399,1,1,'v_info_wf_structure_update','Страница сохранена','v_info_wf_structure_update'),
(400,1,1,'wf_settings_edit','Редактирование настройки','wf_settings_edit'),
(401,1,1,'v_info_wf_settings_update','Настройка обновлена','v_info_wf_settings_update'),
(402,1,1,'nav_title_reviews','Отзывы','nav_title_reviews'),
(403,1,1,'author','Автор','author'),
(404,1,1,'wf_reviews_edit','Редактирование отзыва','wf_reviews_edit'),
(405,1,1,'v_info_wf_reviews_update','Отзыв обновлен','v_info_wf_reviews_update'),
(406,1,1,'wf_projects_edit','Редактирование проекта','wf_projects_edit'),
(407,1,1,'nav_title_wf_projects_images','Картинки проекта','nav_title_wf_projects_images'),
(408,1,1,'v_info_wf_structure_templates_update','Шаблон обновлен','v_info_wf_structure_templates_update'),
(409,1,1,'wf_settings_add','Добавление настройки','wf_settings_add'),
(410,1,1,'v_info_wf_settings_add','Настройка добавлена','v_info_wf_settings_add'),
(411,1,1,'v_info_wf_structure_content_values_update','Данные обновлены','v_info_wf_structure_content_values_update'),
(412,1,1,'wf_projects_add','Добавить проект','wf_projects_add'),
(413,1,1,'v_info_wf_projects_add','Проект добавлен','v_info_wf_projects_add'),
(414,1,1,'v_info_wf_projects_update','Проект обновлен','v_info_wf_projects_update'),
(415,1,1,'wf_projects_images_add','Добавить изображение','wf_projects_images_add'),
(416,1,1,'v_info_wf_projects_images_add','Изображение добавлено','v_info_wf_projects_images_add'),
(417,1,1,'wf_projects_images_edit','Редактировать изображение','wf_projects_images_edit'),
(418,1,1,'v_info_wf_projects_images_update','Изображение обновлено','v_info_wf_projects_images_update'),
(419,1,1,'wf_me_members_edit','wf_me_members_edit','wf_me_members_edit'),
(420,1,1,'nav_title_wf_homepage_annonces','nav_title_wf_homepage_annonces','nav_title_wf_homepage_annonces'),
(421,1,0,'scv_annonce_header','Заголовок приветствия',''),
(422,1,0,'scv_annonce_text','Текст приветствия',''),
(423,1,0,'scv_annonce_button','Название кнопки приветствия',''),
(424,1,1,'wf_me_members_add','Добавить пользователя','wf_me_members_add'),
(425,1,1,'error_email_exist','Такой email уже есть в базе','error_email_exist'),
(426,1,1,'v_info_wf_me_members_add','Пользователь добавлен','v_info_wf_me_members_add'),
(427,1,1,'v_info_wf_me_members_update','Пользователь обновлен','v_info_wf_me_members_update'),
(428,1,1,'developer','Программист','developer'),
(429,1,1,'wf_structure_templates_add','Добавить шаблон','wf_structure_templates_add'),
(430,1,1,'v_info_wf_structure_templates_add','Шаблон добавлен','v_info_wf_structure_templates_add'),
(431,1,1,'login_access_denied','В доступе отказано','login_access_denied'),
(432,1,1,'cdate','Дата создания','cdate'),
(462,1,1,'theme_title','Тема','theme_title'),
(463,1,1,'difficulty','Сложность','difficulty'),
(464,1,1,'nav_title_tests','Тесты','nav_title_tests'),
(465,1,1,'status_title','Статус','status_title'),
(466,1,1,'wf_sites_add','Добавить сайт','wf_sites_add'),
(468,1,1,'v_info_wf_tasks_add','Задача добавлена','v_info_wf_tasks_add'),
(469,1,1,'comments','Комментарии','comments'),
(470,1,1,'contacts','Контакты','contacts'),
(472,1,1,'id','id','id'),
(473,1,1,'owner_id','Менеджер','owner_id'),
(474,1,1,'manager','Менеджер','manager'),
(475,1,1,'filter_by_owner_id','Фильтр по менеджеру','filter_by_owner_id'),
(476,1,1,'nav_title_tags','nav_title_tags','nav_title_tags'),
(477,1,1,'wf_tags_add','wf_tags_add','wf_tags_add'),
(478,1,1,'v_info_wf_tags_add','v_info_wf_tags_add','v_info_wf_tags_add'),
(479,1,1,'wf_tags_edit','wf_tags_edit','wf_tags_edit'),
(480,1,1,'v_info_wf_tags_update','v_info_wf_tags_update','v_info_wf_tags_update'),
(481,1,1,'tags','tags','tags'),
(482,1,1,'theme','theme','theme'),
(483,1,1,'answer1','answer1','answer1'),
(484,1,1,'answer2','answer2','answer2'),
(485,1,1,'answer3','answer3','answer3'),
(486,1,1,'answer4','answer4','answer4'),
(487,1,1,'answer5','answer5','answer5'),
(488,1,1,'answer6','answer6','answer6'),
(489,1,1,'correct_answer','Правильный ответ','correct_answer'),
(490,1,1,'wf_tests_edit','Редактирование теста','wf_tests_edit'),
(491,1,1,'v_info_wf_tests_update','Тест обновлен','v_info_wf_tests_update'),
(492,1,1,'wf_tests_add','Добавление теста','wf_tests_add'),
(493,1,1,'v_info_wf_tests_add','Тест добавлен','v_info_wf_tests_add'),
(509,1,1,'nav_title_candidates','Кандидаты','nav_title_candidates'),
(510,1,1,'age_address','Город/Возраст','age_address'),
(511,1,1,'ip','IP','ip'),
(512,1,1,'session','Сессия','session'),
(513,1,1,'tries','Попыток','tries'),
(514,1,1,'answer_count','Баллы теста','answer_count'),
(515,1,1,'wf_candidates_edit','Редактирование кандидата','wf_candidates_edit'),
(516,1,1,'filter_by_full_content','Поиск по всей карточке','filter_by_full_content'),
(517,1,1,'v_info_wf_candidates_update','Кандидат обновлен','v_info_wf_candidates_update'),
(518,1,1,'button_reject','Отклонить!','button_reject'),
(519,1,1,'candidate_name','Имя кандидата','candidate_name'),
(520,1,1,'v_info_mail_sent','Письмо отправлено','v_info_mail_sent'),
(521,1,1,'owner_title','Менеджер','owner_title'),
(524,1,1,'button_ready','Готово!','button_ready'),
(525,1,1,'button_processed','Обработано!','button_processed'),
(527,1,1,'skype','Skype','skype'),
(532,1,1,'remind_date','Дата напоминания','remind_date'),
(533,1,1,'remind_reason','Напомнить о','remind_reason'),
(534,1,1,'date_clear_button','Х','date_clear_button'),
(536,1,1,'v_info_wf_order_list_update','Данные обновлены','v_info_wf_order_list_update'),
(537,1,1,'do_remind','Напоминание','do_remind'),
(538,1,1,'filter_by_remind','Напоминание','filter_by_remind'),
(539,1,1,'label','Метка','label'),
(541,1,1,'filter_by_has_reminder','С установленной датой напоминания','filter_by_has_reminder'),
(542,1,1,'first_message','Первое обращение (сообщение)','first_message'),
(543,1,1,'nav_title_reminders','Напоминания','nav_title_reminders'),
(544,1,1,'wf_reminders_add','Добавить напоминание','wf_reminders_add'),
(545,1,1,'v_info_wf_reminders_add','Напоминание добавлено','v_info_wf_reminders_add'),
(546,1,1,'wf_reminders_edit','Редактировать напоминание','wf_reminders_edit'),
(547,1,1,'period_days','Период (дни для периодических, месяцы для календарных)','period_days'),
(548,1,1,'v_info_wf_reminders_update','Напоминание обновлено','v_info_wf_reminders_update'),
(549,1,1,'period_correction','Корректировка периода (дни)','period_correction'),
(550,1,1,'nav_title_reminders_groups','Группы напоминаний','nav_title_reminders_groups'),
(551,1,1,'wf_reminders_groups_add','Добавление группы','wf_reminders_groups_add'),
(552,1,1,'v_info_wf_reminders_groups_add','Группа добавлена','v_info_wf_reminders_groups_add'),
(553,1,1,'wf_reminders_groups_edit','Редактирование группы','wf_reminders_groups_edit'),
(554,1,1,'v_info_wf_reminders_groups_update','Группа обновлена','v_info_wf_reminders_groups_update'),
(555,1,1,'group_id','Группа','group_id'),
(556,1,1,'group_title','Группа','group_title'),
(557,1,1,'filter_by_group_id','Фильтр по группе','filter_by_group_id'),
(563,1,1,'remind_period','Период напоминания (дни)','remind_period'),
(580,1,1,'wf_candidates_add','Добавить кандидата','wf_candidates_add'),
(581,1,1,'v_info_wf_candidates_add','Кандидат добавлен','v_info_wf_candidates_add'),
(636,1,1,'button_activate','Активировать','button_activate'),
(637,1,1,'button_deactivate','Деактивировать','button_deactivate'),
(644,1,1,'phone','Телефон','phone'),
(645,1,1,'country_id','Страна','country_id'),
(646,1,1,'remind','Включить напоминания','remind'),
(647,1,1,'remind_start_date','Дата начала напоминаний','remind_start_date'),
(648,1,1,'registration_form_name','Логин','registration_form_name'),
(649,1,1,'registration_form_password','Пароль','registration_form_password'),
(651,1,1,'registration_form_store','registration_form_store','registration_form_store'),
(652,1,1,'nav_title_applications','Заявки на кредит','nav_title_applications'),
(653,1,1,'wf_applications_add','Добавление заявки на кредит','wf_applications_add'),
(654,1,1,'percent','Процент по кредиту','percent'),
(655,1,1,'amount','Сумма','amount'),
(656,1,1,'term_months','Срок (месяцы)','term_months'),
(657,1,1,'v_info_wf_applications_add','Заявка добавлена','v_info_wf_applications_add'),
(658,1,1,'wf_applications_edit','Редактирование заявки','wf_applications_edit'),
(659,1,1,'v_info_wf_applications_update','Заявка обновлена','v_info_wf_applications_update'),
(660,1,1,'udate','Дата редактирования','udate'),
(661,1,1,'fio','Ф.И.О.','fio'),
(662,1,1,'request_printed','Заявление в электронном виде','request_printed'),
(663,1,1,'income_statement','Справка о доходах','income_statement'),
(664,1,1,'passport','Паспорт (3 главные страницы)','passport'),
(665,1,1,'military_id','Военный билет','military_id'),
(666,1,1,'insurance_certificate','Страховое свидетельство','insurance_certificate'),
(667,1,1,'3rd_party_documents','Документы на залог/поручительство третьих лиц','3rd_party_documents'),
(668,1,1,'passport_info','Паспортные данные (номер, когда и кем выдан)','passport_info'),
(669,1,1,'address_info','Адрес','address_info'),
(670,1,1,'third_party_documents','Документы третьих лиц','third_party_documents'),
(671,1,1,'1','1','1'),
(672,1,1,'2','2','2'),
(673,1,1,'3','3','3'),
(674,1,1,'4','4','4'),
(675,1,1,'5','5','5'),
(676,1,1,'6','6','6'),
(677,1,1,'7','7','7'),
(678,1,1,'date_start','Дата начала','date_start'),
(679,1,1,'date_end','Дата конца','date_end'),
(680,1,1,'nav_title_credits','Кредиты','nav_title_credits'),
(681,1,1,'wf_credits_edit','Редактирование кредита','wf_credits_edit'),
(682,1,1,'nav_title_wf_credits_plan','Выплаты.План','nav_title_wf_credits_plan'),
(683,1,1,'wf_credits_plan_edit','Выплаты.План','wf_credits_plan_edit'),
(684,1,1,'nav_title_wf_credits_fact','Выплаты.Факт','nav_title_wf_credits_fact'),
(685,1,1,'wf_credits_fact_add','Добавление выплаты','wf_credits_fact_add'),
(686,1,1,'v_info_wf_credits_fact_add','Выплата добавлена','v_info_wf_credits_fact_add'),
(687,1,1,'wf_credits_fact_edit','Редактирование выплаты','wf_credits_fact_edit'),
(688,1,1,'v_info_wf_credits_fact_update','Выплата сохранена','v_info_wf_credits_fact_update'),
(689,1,1,'fine','Пеня','fine'),
(690,1,1,'paid','Оплачен','paid');

/*Table structure for table `wf_me_members` */

DROP TABLE IF EXISTS `wf_me_members`;

CREATE TABLE `wf_me_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_level` int(11) NOT NULL,
  `password` varchar(64) NOT NULL,
  `restored_password` varchar(64) DEFAULT NULL,
  `login` varchar(64) NOT NULL,
  `title` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `uri` text,
  `status` int(11) NOT NULL DEFAULT '0',
  `contacts` text,
  PRIMARY KEY (`id`,`id_level`,`status`),
  UNIQUE KEY `id` (`id`,`status`),
  UNIQUE KEY `id_2` (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

/*Data for the table `wf_me_members` */

insert  into `wf_me_members`(`id`,`id_level`,`password`,`restored_password`,`login`,`title`,`email`,`uri`,`status`,`contacts`) values 
(1,255,'54b53072540eeeb8f8e9343e71f28176','','system','','system@example.com',NULL,1,NULL),
(28,160,'5faf358b5b2fc48b0596eb3c38a1b677','','manager','Менеджер','manager@example.com',NULL,1,''),
(33,10,'ee11cbb19052e40b07aac0ca060c23ee','','user','user','user@example.com',NULL,1,NULL),
(34,11,'6c741089a44c080eca30c000f4dd9ef3','','client_services','Специалист по работе с клиентами','client_services@example.com',NULL,1,''),
(35,12,'e91e6348157868de9dd8b25c81aebfb9','','security','Сотрудник службы безопасности','security@example.com',NULL,1,''),
(36,13,'c48b49c804b923a737207acc1e227a96','','credit_committee','Член кредитного комитета','credit_committee@example.com',NULL,1,''),
(37,14,'e9289f50cf28123e252e03e89210a6f6','','department_manager','Начальник кредитного отдела','department_manager@example.com',NULL,1,''),
(38,15,'4b583376b2767b923c3e1da60d10de59','','operator','Операционист','operator@example.com',NULL,1,''),
(39,10,'7e58d63b60197ceb55a1c487989a3720','','user2','user2','user2@example.com',NULL,1,NULL);

/*Table structure for table `wf_me_members_pages` */

DROP TABLE IF EXISTS `wf_me_members_pages`;

CREATE TABLE `wf_me_members_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_member` int(11) NOT NULL,
  `allowed_page` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `wf_me_members_pages` */

/*Table structure for table `wf_settings` */

DROP TABLE IF EXISTS `wf_settings`;

CREATE TABLE `wf_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `system` varchar(250) DEFAULT NULL,
  `validator` varchar(250) DEFAULT NULL,
  `value` text,
  `comment` varchar(250) DEFAULT NULL,
  `hidden` int(1) DEFAULT NULL,
  `mandatory` int(1) NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT 'string',
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `wf_settings` */

insert  into `wf_settings`(`id`,`title`,`system`,`validator`,`value`,`comment`,`hidden`,`mandatory`,`type`) values 
(1,'CMS: Максимальный уровень вложенности','structure_max_level','isNumber::true,minValue::1,maxValue::9999','20','',1,0,'number'),
(2,'CMS: Добавление в корень сайта','structure_tree_root_add','isNumber::true,minValue::0,maxValue::1','1','',1,0,'bool'),
(3,'CMS: Версия','structure_cms_version','minLength::6,maxLength::7','static','static/dynamic',1,0,'string'),
(4,'Название сайта','site_title','minLength::1,maxLength::256','Tofi.Bank','',0,0,'string'),
(8,'Подвал: Годы копирайта','copy_years','minLength::1,maxLength::256','2011','',0,0,'number'),
(9,'Email администратора','administrator_email','minLength::1,maxLength::256','from@example.com','',0,0,'string'),
(12,'Email-ы куда отправляются письма','to_email','minLength::1,maxLength::256','sergey.ger@gmail.com;to1@example.com',NULL,0,0,'string'),
(16,'Процент кредита (в год)','percent_year',NULL,'30','',0,1,'number'),
(17,'Дебаг: текущая дата','debug_current_date',NULL,'2018-01-12','',0,0,'date'),
(18,'Размер пени % в год','fine',NULL,'600','',0,0,'number');

/*Table structure for table `wf_structure` */

DROP TABLE IF EXISTS `wf_structure`;

CREATE TABLE `wf_structure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `system` varchar(255) NOT NULL,
  `uri` varchar(255) DEFAULT NULL,
  `id_template` int(11) DEFAULT NULL,
  `perms` int(11) DEFAULT '0',
  `show_in_menu` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `can_edit` tinyint(1) DEFAULT NULL,
  `can_add` tinyint(1) DEFAULT NULL,
  `id_lang` int(11) DEFAULT '1',
  `sort_id` int(11) DEFAULT '0',
  `cdate` datetime DEFAULT NULL,
  `udate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `wf_structure` */

insert  into `wf_structure`(`id`,`id_parent`,`title`,`system`,`uri`,`id_template`,`perms`,`show_in_menu`,`status`,`can_edit`,`can_add`,`id_lang`,`sort_id`,`cdate`,`udate`) values 
(1,0,'Выгодный банк','index','/index/',25,0,1,1,0,0,1,1,'2010-09-20 09:47:26','2017-10-28 20:30:53'),
(27,0,'Ошибка 404','404','/404/',11,0,0,1,0,0,1,2,'2010-09-20 09:47:26','2010-09-20 09:47:26');

/*Table structure for table `wf_structure_attachables` */

DROP TABLE IF EXISTS `wf_structure_attachables`;

CREATE TABLE `wf_structure_attachables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_template` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `system` varchar(255) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `sort_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_template` (`id_template`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `wf_structure_attachables` */

insert  into `wf_structure_attachables`(`id`,`id_template`,`title`,`system`,`uri`,`sort_id`) values 
(2,5,'Изображения','StructureImages','izobrazheniya-2',1),
(3,8,'Типы вопросов','FAQTypes','tipyi-voprosov',1),
(4,8,'Вопросы','FAQ','voprosyi',2);

/*Table structure for table `wf_structure_content` */

DROP TABLE IF EXISTS `wf_structure_content`;

CREATE TABLE `wf_structure_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_template` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `system` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `validator` varchar(255) NOT NULL,
  `required` int(11) DEFAULT NULL,
  `sort_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_template` (`id_template`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

/*Data for the table `wf_structure_content` */

insert  into `wf_structure_content`(`id`,`id_template`,`title`,`system`,`type`,`validator`,`required`,`sort_id`) values 
(1,1,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(2,1,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(3,1,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(7,2,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(8,2,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(9,2,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(10,2,'Информация','contents','html','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:5:\"20000\";}',0,4),
(11,3,'Ссылка на страницу','redirect_link','text','a:2:{s:9:\"minLength\";s:1:\"1\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(16,5,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(17,5,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(18,5,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(19,5,'Контактная информация','contact_information','html','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:5:\"20000\";}',0,4),
(20,5,'Карта проезда','map_image','file','a:2:{s:10:\"isRequired\";b:1;s:6:\"option\";s:6:\"Images\";}',0,5),
(22,5,'Email адрес отправителя','email_from','text','a:2:{s:10:\"isRequired\";b:1;s:7:\"isEmail\";b:1;}',0,6),
(23,5,'Email адрес получателя','email_to','text','a:2:{s:10:\"isRequired\";b:1;s:7:\"isEmail\";b:1;}',0,7),
(24,5,'Тема письма','email_subject','text','a:3:{s:10:\"isRequired\";b:1;s:9:\"minLength\";s:1:\"5\";s:9:\"maxLength\";s:4:\"2000\";}',0,8),
(25,5,'Шаблон письма','email_message','html','a:3:{s:10:\"isRequired\";b:1;s:9:\"minLength\";s:1:\"5\";s:9:\"maxLength\";s:5:\"20000\";}',0,9),
(29,7,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(30,7,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(31,7,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(32,7,'Изображение на главной','index_image','file','a:2:{s:10:\"isRequired\";b:1;s:6:\"option\";s:6:\"Images\";}',1,4),
(33,7,'Описание','anonce','html','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:5:\"20000\";}',0,6),
(34,7,'Записей на странице','items_per_page','text','a:3:{s:10:\"isRequired\";b:1;s:8:\"isNumber\";b:1;s:8:\"minValue\";s:1:\"1\";}',0,7),
(35,8,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(36,8,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(37,8,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(38,9,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(39,9,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(40,9,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(41,9,'Записей на странице','items_per_page','text','a:3:{s:10:\"isRequired\";b:1;s:8:\"isNumber\";b:1;s:8:\"minValue\";s:1:\"1\";}',0,4),
(42,10,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(43,10,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(44,10,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(45,11,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(46,11,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(47,11,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(48,11,'Сообщение о ошибке','content','html','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:5:\"20000\";}',0,4),
(49,7,'Описание на главной','index_anonce','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:3:\"512\";}',1,5),
(58,13,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(59,13,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(60,13,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(61,13,'Короткое описание','index_anonce','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:3:\"512\";}',0,5),
(62,13,'Изображение','index_image','file','a:2:{s:10:\"isRequired\";b:1;s:6:\"option\";s:6:\"Images\";}',1,4),
(63,13,'Описание','contents','html','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:5:\"20000\";}',0,6),
(64,14,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(65,14,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(66,14,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(67,14,'Описание','contents','html','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:5:\"20000\";}',0,4),
(68,14,'Записей на странице','items_per_page','text','a:3:{s:10:\"isRequired\";b:1;s:8:\"isNumber\";b:1;s:8:\"minValue\";s:1:\"1\";}',1,5),
(70,15,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(71,15,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(72,15,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(73,15,'Записей на странице','items_per_page','text','a:3:{s:10:\"isRequired\";b:1;s:8:\"isNumber\";b:1;s:8:\"minValue\";s:1:\"1\";}',0,4),
(78,16,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(79,16,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(80,16,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(89,17,'Meta Title','meta_title','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,1),
(90,17,'Meta Description','meta_description','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,2),
(91,17,'Meta Keywords','meta_keywords','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,3),
(92,17,'Информация','contents','html','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:5:\"20000\";}',0,4),
(95,16,'Информация','contents','html','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:5:\"20000\";}',0,4),
(96,1,'Заголовок приветствия','annonce_header','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',1,4),
(97,1,'Текст приветствия','annonce_text','textarea','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,5),
(98,1,'Название кнопки приветствия','annonce_button','text','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:4:\"2000\";}',0,6),
(99,19,'Информация','contents','html','a:2:{s:9:\"minLength\";s:1:\"4\";s:9:\"maxLength\";s:5:\"20000\";}',0,1);

/*Table structure for table `wf_structure_content_values` */

DROP TABLE IF EXISTS `wf_structure_content_values`;

CREATE TABLE `wf_structure_content_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_content` int(11) DEFAULT NULL,
  `id_structure` int(11) DEFAULT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_content` (`id_content`),
  KEY `id_structure` (`id_structure`)
) ENGINE=InnoDB AUTO_INCREMENT=345 DEFAULT CHARSET=utf8;

/*Data for the table `wf_structure_content_values` */

insert  into `wf_structure_content_values`(`id`,`id_content`,`id_structure`,`value`) values 
(35,48,27,'<p class=\"error\">\r\n	Если Вы вводили адрес вручную в адресную строку браузера, проверьте, все ли Вы написали правильно. Если Вы перешли по ссылке с другого сайта, попробуйте перейти на <a href=\"/\">главную страницу сайта</a>. А если Вы встретили неработающую ссылку на странице нашего сайта, пожалуйста, сообщите нам об этом.</p>'),
(57,1,1,''),
(58,2,1,''),
(59,3,1,''),
(102,45,27,'Страница не найдена'),
(103,46,27,'Уникальные технологии'),
(104,47,27,'Уникальные технологии'),
(246,7,1,''),
(247,8,1,''),
(248,9,1,''),
(249,10,1,'<p>\r\n	Главная страница</p>'),
(342,96,1,''),
(343,97,1,''),
(344,98,1,'');

/*Table structure for table `wf_structure_files` */

DROP TABLE IF EXISTS `wf_structure_files`;

CREATE TABLE `wf_structure_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_attachable` int(11) DEFAULT NULL,
  `id_structure` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `sort_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `wf_structure_files` */

/*Table structure for table `wf_structure_images` */

DROP TABLE IF EXISTS `wf_structure_images`;

CREATE TABLE `wf_structure_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_attachable` int(11) DEFAULT NULL,
  `id_structure` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `wf_structure_images` */

/*Table structure for table `wf_structure_templates` */

DROP TABLE IF EXISTS `wf_structure_templates`;

CREATE TABLE `wf_structure_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `system` varchar(255) NOT NULL,
  `action` tinyint(1) DEFAULT NULL,
  `hidden` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `wf_structure_templates` */

insert  into `wf_structure_templates`(`id`,`title`,`system`,`action`,`hidden`) values 
(1,'Главная','IndexPage',0,0),
(3,'Переход на страницу','RedirectPage',0,0),
(11,'404 Ошибка','Error404Page',0,1),
(18,'Тест','TestPage',1,0),
(25,'Выгодный банк','RegistrationPage',1,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
