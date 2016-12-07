<?php
/** Enable W3 Total Cache Edge Mode */
define('W3TC_EDGE_MODE', true); // Added by W3 Total Cache

/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

define ('WPLANG', 'en_EN');

/**
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'valenciafoodtourspain-wp-DSFBPGSl');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'pai6IxXQd1Q9');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'NXnLsFyniN18gzVt');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '&,u}D*m*G3ae8[_`X*sg|.vV)vA$hN^pFm;K{y-NhVijV5sd%>xcXmHC?p+XHI)5');
define('SECURE_AUTH_KEY', 'F+vK_/vmP&`0;wX2N_Ugfi`:$p*^6CCq/ AvFK_|__=`/.&V{0zwH0FrD;axj2`o');
define('LOGGED_IN_KEY', '$8SVOF#fNN]mH5pia7K) elKQ p7,m<G5n^$q=7nfSU6M6`@Cf[htb[{yOEN+.y<');
define('NONCE_KEY', 'gY)P]&POCYt43GWVbT?a.F/mjyn9ml>h@RgN,gVF?mccU.-t3kZfa:Re*[M,#}#_');
define('AUTH_SALT', 'CpC1RHd_Vm8T9lQE<?70#D*Cy!lGkE4JFLE/s#;1sQpc,H8x:4qDDbrQ>Ln~QVvg');
define('SECURE_AUTH_SALT', 'S5g4^Z$=WQ%mJg/8YLg$Y}4bh<a/2=(LF;x>5eH4WRpgv~Wh&{M[1D?{;~U@{eD(');
define('LOGGED_IN_SALT', '1?&b9:Jw<Lt>WRP6)1nXVSt?Kpa[&%mfi]d&5WDa=~`#FiHr/NpR}NK^Y62qC*Ff');
define('NONCE_SALT', '#E2QN&[:ALc-x=sm1K1.A]L#U6!8O|,hCsn!z3,LQ#v&T#!4DLx+9.66^;M~/6H2');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
