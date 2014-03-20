<?php
/**
 * Configurazioni di base di WordPress.
 *
 * Questo file contiene le seguenti configurazioni: Impostazioni MySQL,
 * Prefisso tabella, Chiavi Segrete, Lingua di WordPress e ABSPATH.
 + E' possibile trovare maggiori informazioni visitando la pagina del Codex
 + {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}.
 + E' possibile ottenere le impostazioni MySQL dal proprio web host.
 *
 * Questo file viene utilizato dallo script wp-config.php durante le fasi di
 * installazione. Non è necessario utilizzare il sito web, è possibile semplicemente
 + copiare questo file in "wp-config.php" e inserire i vari parametri.
 *
 * @package WordPress
 */

require_once 'globals.php'; 
 
// ** Impostazioni MySQL - E' possibile ottenere le impostazioni MySQL dal proprio web host ** //
/** Il nome del database per WordPress */
define('DB_NAME', '...');

/** Nome utente database MySQL */
define('DB_USER', '...');

/** Password database MySQL */
define('DB_PASSWORD', '...');

/** hostname MySQL */
define('DB_HOST', '...');

/** Database Charset da utilizzare nella creazione delle tabelle del database. */
define('DB_CHARSET', 'utf8');

/** Il tipo di Database Collation. Da non modificare in caso di dubbi. */
define('DB_COLLATE', '');

/**#@+
 * Chiavi univoche di identificazione.
 *
 * Modificarle con differenti frasi univoche!
 * E' possibile generarle utilizzando il link {@link https://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 * E' possibile cambiarele in qualsiasi momento per invalidare tutto i cookie esistenti. Ciò obbligherà gli utenti a effettuare nuovamente il login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'put your unique phrase here');
define('SECURE_AUTH_KEY', 'put your unique phrase here');
define('LOGGED_IN_KEY', 'put your unique phrase here');
define('NONCE_KEY', 'put your unique phrase here');
/**#@-*/

/**
 * Prefisso tabella database WordPress.
 *
 * E' possibile avere installazioni multiple su un singolo database impostando
 + per ciascuna installazione un prefizzo univoco.
 * Solo numeri. lettere e carattere di sottolineatura!
 */
$table_prefix  = 'wp_';

/**
 * Lingua di localizzazione di WordPress, valore predefinito: Inglese.
 *
 * Modificare questa voce per localizare WordPress. Occorre sia instllato un
 + file MO corrispondente alla lingua scelta posto all'interno della directory
 * wp-content/languages. Ad esempio, installare de.mo in wp-content/languages
 + e impostare WPLANG a 'de' per abilitare la lingua Tedesca
 * language support.
 */
define ('WPLANG', 'it_IT');

/* Niente altro, configurazione terminata! Buon blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
