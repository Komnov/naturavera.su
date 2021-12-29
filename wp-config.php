<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'naturavera_nv' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'naturavera_nv' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'KiAYjNoH' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Rnb2DuPn=JC[z^6O-<jHG)O^%?=`M@/2N Gn+O+`!YRKkmZ2Kn^APzm+^%2jOz.8' );
define( 'SECURE_AUTH_KEY',  'oq+>Z&C9Z<AS/JC/g2]uHi6U[vTMP[#c 3 WtRK{!Y!8B `ZCV`c&da{8+TAfd&d' );
define( 'LOGGED_IN_KEY',    'wHBu%L4N>,x#?-b7C}]MM20bo$~dZf+|fx92St1>#SM|4uwSNx)#T7sQPKO8aQ?|' );
define( 'NONCE_KEY',        'NE()gW;D9|k,(IWzfGb*Lz?8315c#VpKr1UymTT,Upm%yWc1+~AK}fK7s+]#F*(W' );
define( 'AUTH_SALT',        ' aT7eY<=Mf{xM$mv^Ly^L!GG<+iLc`w* Qjb(P+OpyCjG.4je.Ol,o&_*lv8WIiT' );
define( 'SECURE_AUTH_SALT', 'x?NOTd@W`4Ov;Oz?!{?:Bh-bb~Va!8icOp=FMYz452LIWAK3L>PkS!G&f]98wsvx' );
define( 'LOGGED_IN_SALT',   '5AOh{,_<MJ>P9GQ7`<8lrU}Cq A]f.QNx~G^4<?d6vP7e8C.&#Y,_{bT?qnE]%[k' );
define( 'NONCE_SALT',       ':&-0,>Xag:`20f92!)__Sx7lfNC:7kn}u]U Gb<#fW#ME;j})v]|-gd5y|#-Y<`3' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );