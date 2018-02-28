<?php
/**
 * إعدادات الووردبريس الأساسية
 *
 * عملية إنشاء الملف wp-config.php تستخدم هذا الملف أثناء التنصيب. لا يجب عليك
 * استخدام الموقع، يمكنك نسخ هذا الملف إلى "wp-config.php" وبعدها ملئ القيم المطلوبة.
 *
 * هذا الملف يحتوي على هذه الإعدادات:
 *
 * * إعدادات قاعدة البيانات
 * * مفاتيح الأمان
 * * بادئة جداول قاعدة البيانات
 * * المسار المطلق لمجلد الووردبريس
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** إعدادات قاعدة البيانات - يمكنك الحصول على هذه المعلومات من مستضيفك ** //

/** اسم قاعدة البيانات لووردبريس */
define('DB_NAME', 'aos');

/** اسم مستخدم قاعدة البيانات */
define('DB_USER', 'root');

/** كلمة مرور قاعدة البيانات */
define('DB_PASSWORD', 'root');

/** عنوان خادم قاعدة البيانات */
define('DB_HOST', 'localhost');

/** ترميز قاعدة البيانات */
define('DB_CHARSET', 'utf8mb4');

/** نوع تجميع قاعدة البيانات. لا تغير هذا إن كنت غير متأكد */
define('DB_COLLATE', '');

/**#@+
 * مفاتيح الأمان.
 *
 * استخدم الرابط التالي لتوليد المفاتيح {@link https://api.wordpress.org/secret-key/1.1/salt/}
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '^okb[*6{OL2nFok[5aA:>o JI2|w$8/DQ6!}aRZd_~g=:)g;A%Z1_q6&8/[v C9t');
define('SECURE_AUTH_KEY',  'Ka+pOtMB<nwMA@@DGqBe745[@(Xfj_,8UGy?}CE_cAs^qF0BHyb*)Ts/fnCmZew9');
define('LOGGED_IN_KEY',    'f_M!jZ4$EU@7cr05dMqB T$i-9_O@A[o01q29fv|k}:%F|3{jTdw,`zHW>4Lt2B0');
define('NONCE_KEY',        'jt@ym_~yBta7E[0$|o7(ji+A8q=fX!:eYWQe+AX v+b8X&{:I*|@y-m=unM,0Cez');
define('AUTH_SALT',        'ypdP2sGii*QE0@AzN3^s+sS(e|Y;gUAvzEU>%%m~Ys`x)jzD#`Fkp$}r}qUTk2tT');
define('SECURE_AUTH_SALT', 'K5-jkt;F7G;RVpn>&-T~}Lu-4QA-}[ddvV-RSmVs#.fwtG%Y$v<z.:_^<TRj6E?H');
define('LOGGED_IN_SALT',   'Y*n{RJNrk4&p$nb$d#/ZoUN|pw+FITWGi:PETCg=f H-|HoHM&D`Fc(1sn8JMA?l');
define('NONCE_SALT',       '!.i^t5N(s1~h-2$Qoq+s?AFTgA(v{qK=5C;GeQ^8m^^gV^TOfv*gZ&D>rdFw&Zd7');

/**#@-*/

/**
 * بادئة الجداول في قاعدة البيانات.
 *
 * تستطيع تركيب أكثر من موقع على نفس قاعدة البيانات إذا أعطيت لكل موقع بادئة جداول مختلفة
 * يرجى استخدام حروف، أرقام وخطوط سفلية فقط!
 */
$table_prefix  = 'wp_';

/**
 * للمطورين: نظام تشخيص الأخطاء
 *
 * قم بتغييرالقيمة، إن أردت تمكين عرض الملاحظات والأخطاء أثناء التطوير.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* هذا هو المطلوب، توقف عن التعديل! نتمنى لك التوفيق. */

/** المسار المطلق لمجلد ووردبريس. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** إعداد متغيرات الووردبريس وتضمين الملفات. */
require_once(ABSPATH . 'wp-settings.php');