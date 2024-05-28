<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки базы данных
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'alt43_oiadmin' );

/** Имя пользователя базы данных */
define( 'DB_USER', 'alt43_oiadmin' );

/** Пароль к базе данных */
define( 'DB_PASSWORD', 'NL00Ht%i' );

/** Имя сервера базы данных */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'YcNqkRj>NDW:HjQ)[oMHMK2UGf,mZzK1nBc5Mz&,&7Fu8,h-Tq<~u{[+Rb~=z5VA' );
define( 'SECURE_AUTH_KEY',  's{XcCel/>IY9zJ9wcm=ms!!MgIT2uD%=)IFMy{}xfH3&;hvu-Om&O=Dl<-w{zxc9' );
define( 'LOGGED_IN_KEY',    'g_#5avjFQpfS8c<jn3n1Qvc:qB/rAh@,e,cm{1!,I+;KuK2-T<h%ql?LkP#yw~A&' );
define( 'NONCE_KEY',        'V0`|J;h~rmM>~}3OX^bUU10@<|gdW7+xeGC5HG(_*o=ftG)W[[jv!?3NQ{p/Stg9' );
define( 'AUTH_SALT',        'mOd_=ymfsu7FQaP^Nr^6Bf8aa0!C31f^?.k6Dt-$.G *l[UO4(Z)V)[^2O4k9Yb_' );
define( 'SECURE_AUTH_SALT', '/0,_g M-zXKS~o.-0<sUBwz,DqbALozvDlJhzLR`Xy0pmk)@,ug[s^SEgP~B` L!' );
define( 'LOGGED_IN_SALT',   'dDz]Qz]&p6!t{h}`{*jozhxs]ePeNYV^@,d!Yz#O1WvH$pC^S&qZ,{f%Bx?m4?t[' );
define( 'NONCE_SALT',       '|rE4Ji6$^Ed(G*7?99:<n-18>aZ5d}!&$WC,(PDZ3X1+yTWrqYWF0eIk1Jw)VY~!' );

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
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
