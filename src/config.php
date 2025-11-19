<?php
// ===== DATABASE CONFIGURATION =====
define('DB_HOST', 'db');
define('DB_NAME', 'novaccess');
define('DB_USER', 'exampleuser');
define('DB_PASSWORD', 'examplepass');

// ===== SITE CONFIGURATION =====
define('SITE_NAME', 'Novaccess');
define('SITE_TAGLINE', 'Votre hub de liens centralisé');
define('SITE_DESCRIPTION', 'Novaccess - Votre hub de liens centralisé');
define('SITE_LANG', 'fr');
define('SITE_ICON', '');

// ===== DISPLAY CONFIGURATION =====
define('ITEMS_PER_PAGE', 50);
define('DEFAULT_SORT', 'name');
define('DEFAULT_ORDER', 'ASC');

// ===== SEARCH CONFIGURATION =====
define('SEARCH_PLACEHOLDER', 'Rechercher un lien par nom...');
define('ENABLE_SEARCH', true);

// ===== FEATURES CONFIGURATION =====
define('ENABLE_DRAG_DROP', true);
define('ENABLE_FAVICON', true);
define('FAVICON_SERVICE', 'google'); // 'google', 'duckduckgo', or 'local'

// ===== UI LABELS =====
define('LABEL_AUTH_REQUIRED', 'Auth requise');
define('LABEL_OPEN_LINK', 'Ouvrir');
define('LABEL_NO_LINKS', 'Aucun lien disponible');
define('LABEL_LINKS_AVAILABLE', 'lien(s) disponible(s)');
define('LABEL_SEARCH_RESULTS', 'sur');

// ===== DEVELOPER MODE =====
define('DEV_MODE', false);
define('SHOW_ERRORS', DEV_MODE);

if (SHOW_ERRORS) {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
} else {
  error_reporting(0);
  ini_set('display_errors', 0);
}
?>