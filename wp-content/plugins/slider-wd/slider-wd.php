<?php

/**
 * Plugin Name: Slider WD
 * Plugin URI: https://web-dorado.com/products/wordpress-slider-plugin.html
 * Description: This is a responsive plugin, which allows adding sliders to your posts/pages and to custom location. It uses large number of transition effects and supports various types of layers.
 * Version: 1.1.0
 * Author: WebDorado
 * Author URI: https://web-dorado.com/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

define('WD_S_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('WD_S_URL', plugins_url(plugin_basename(dirname(__FILE__))));

function wds_use_home_url() {
  $home_url = str_replace("http://", "", home_url());
  $home_url = str_replace("https://", "", $home_url);
  $pos = strpos($home_url, "/");
  if ($pos) {
    $home_url = substr($home_url, 0, $pos);
  }
  $site_url = str_replace("http://", "", WD_S_URL);
  $site_url = str_replace("https://", "", $site_url);
  $pos = strpos($site_url, "/");
  if ($pos) {
    $site_url = substr($site_url, 0, $pos);
  }
  return $site_url != $home_url;
}

if (wds_use_home_url()) {
  define('WD_S_FRONT_URL', home_url("wp-content/plugins/" . plugin_basename(dirname(__FILE__))));
}
else {
  define('WD_S_FRONT_URL', WD_S_URL);
}

$upload_dir = wp_upload_dir();
$WD_S_UPLOAD_DIR = str_replace(ABSPATH, '', $upload_dir['basedir']) . '/slider-wd';

// Plugin menu.
function wds_options_panel() {
  add_menu_page('Slider WD', 'Slider WD', 'manage_options', 'sliders_wds', 'wd_sliders', WD_S_URL . '/images/wd_slider.png');

  $sliders_page = add_submenu_page('sliders_wds', 'Sliders', 'Sliders', 'manage_options', 'sliders_wds', 'wd_sliders');
  add_action('admin_print_styles-' . $sliders_page, 'wds_styles');
  add_action('admin_print_scripts-' . $sliders_page, 'wds_scripts');

  add_submenu_page('sliders_wds', 'Licensing', 'Licensing', 'manage_options', 'licensing_wds', 'wds_licensing');
  add_submenu_page('sliders_wds', 'Featured Plugins', 'Featured Plugins', 'manage_options', 'featured_plugins_wds', 'wds_featured');
  add_submenu_page('sliders_wds', 'Featured Themes', 'Featured Themes', 'manage_options', 'featured_themes_wds', 'wds_featured_themes'); 

  add_submenu_page('sliders_wds', 'Demo Sliders', 'Demo Sliders', 'manage_options', 'demo_sliders_wds', 'wds_demo_sliders'); 

  $uninstall_page = add_submenu_page('sliders_wds', 'Uninstall', 'Uninstall', 'manage_options', 'uninstall_wds', 'wd_sliders');
  add_action('admin_print_styles-' . $uninstall_page, 'wds_styles');
  add_action('admin_print_scripts-' . $uninstall_page, 'wds_scripts');
}
add_action('admin_menu', 'wds_options_panel');

function wd_sliders() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_S_DIR . '/framework/WDW_S_Library.php');
  $page = WDW_S_Library::get('page');
  if (($page != '') && (($page == 'sliders_wds') || ($page == 'uninstall_wds') || ($page == 'WDSShortcode'))) {
    require_once(WD_S_DIR . '/admin/controllers/WDSController' . (($page == 'WDSShortcode') ? $page : ucfirst(strtolower($page))) . '.php');
    $controller_class = 'WDSController' . ucfirst(strtolower($page));
    $controller = new $controller_class();
    $controller->execute();
  }
}

function wds_licensing() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  wp_register_style('wds_licensing', WD_S_URL . '/licensing/style.css', array(), get_option("wds_version"));
  wp_print_styles('wds_licensing');
  require_once(WD_S_DIR . '/licensing/licensing.php');
}

function wds_featured() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_S_DIR . '/featured/featured.php');
  wp_register_style('wds_featured', WD_S_URL . '/featured/style.css', array(), get_option("wds_version"));
  wp_print_styles('wds_featured');
  spider_featured('slider_wd');
}

function wds_featured_themes() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_S_DIR . '/featured/featured_themes.php');
  wp_register_style('wds_featured_themes', WD_S_URL . '/featured/themes_style.css', array(), get_option("wds_version"));
  wp_print_styles('wds_featured_themes');
  spider_featured_themes();
}

function wds_demo_sliders() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_S_DIR . '/demo_sliders/demo_sliders.php');
  wp_register_style('wds_demo_sliders', WD_S_URL . '/demo_sliders/style.css', array(), get_option("wds_version"));
  wp_print_styles('wds_demo_sliders');
  spider_demo_sliders();
}

function wds_frontend() {
  require_once(WD_S_DIR . '/framework/WDW_S_Library.php');
  $page = WDW_S_Library::get('action');
  if (($page != '') && ($page == 'WDSShare')) {
    require_once(WD_S_DIR . '/frontend/controllers/WDSController' . ucfirst($page) . '.php');
    $controller_class = 'WDSController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  }
}

add_action('wp_ajax_WDSPreview', 'wds_preview');
function wds_ajax() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_S_DIR . '/framework/WDW_S_Library.php');
  $page = WDW_S_Library::get('action');
  if ($page != '' && (($page == 'WDSShortcode'))) {
    require_once(WD_S_DIR . '/admin/controllers/WDSController' . ucfirst($page) . '.php');
    $controller_class = 'WDSController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  }
}

function wds_shortcode($params) {
  $params = shortcode_atts(array('id' => 0), $params);
  ob_start();
  wds_front_end($params['id']);
  return str_replace(array("\r\n", "\n", "\r"), '', ob_get_clean());
  // return ob_get_clean();
}
add_shortcode('wds', 'wds_shortcode');

function wd_slider($id) {
  echo wds_front_end($id);
}

$wds = 0;
function wds_front_end($id, $from_shortcode = 1) {
  require_once(WD_S_DIR . '/frontend/controllers/WDSControllerSlider.php');
  $controller = new WDSControllerSlider();
  global $wds;
  $controller->execute($id, $from_shortcode, $wds);
  $wds++;
  return;
}

function wds_preview() {
  require_once(WD_S_DIR . '/framework/WDW_S_Library.php');
  $page = WDW_S_Library::get('action');
  if ($page != '' && $page == 'WDSPreview') {
    $version = get_option("wds_version");
    wp_print_scripts('jquery');
    wp_register_script('wds_jquery_mobile', WD_S_URL . '/js/jquery.mobile.js', array(), $version);
    wp_print_scripts('wds_jquery_mobile');
    wp_register_style('wds_frontend', WD_S_URL . '/css/wds_frontend.css', array(), $version);
    wp_print_styles('wds_frontend');
    wp_register_style('wds_effects', WD_S_URL . '/css/wds_effects.css', array(), $version);
    wp_print_styles('wds_effects');
    wp_register_style('wds_font-awesome', WD_S_URL . '/css/font-awesome-4.0.1/font-awesome.css', array(), '4.0.1');
    wp_print_styles('wds_font-awesome');
    wp_register_script('wds_frontend', WD_S_URL . '/js/wds_frontend.js', array(), $version);
    wp_print_scripts('wds_frontend');
    $google_fonts = array('ABeeZee' => 'ABeeZee', 'Abel' => 'Abel', 'Abril+Fatface' => 'Abril Fatface', 'Aclonica' => 'Aclonica', 'Acme' => 'Acme', 'Actor' => 'Actor', 'Adamina' => 'Adamina', 'Advent+Pro' => 'Advent Pro', 'Aguafina+Script' => 'Aguafina Script', 'Akronim' => 'Akronim', 'Aladin' => 'Aladin', 'Aldrich' => 'Aldrich', 'Alegreya' => 'Alegreya', 'Alegreya+SC' => 'Alegreya SC', 'Alex+Brush' => 'Alex Brush', 'Alfa+Slab+One' => 'Alfa Slab One', 'Alice' => 'Alice', 'Alike' => 'Alike', 'Alike+Angular' => 'Alike Angular', 'Allan' => 'Allan', 'Allerta' => 'Allerta', 'Allura' => 'Allura', 'Almendra' => 'Almendra', 'Almendra+display' => 'Almendra Display', 'Almendra+sc' => 'Almendra SC', 'Amarante' => 'Amarante', 'Amaranth' => 'Amaranth', 'Amatic+sc' => 'Amatic SC', 'Amethysta' => 'Amethysta', 'Anaheim' => 'Anaheim', 'Andada' => 'Andada', 'Andika' => 'Andika', 'Angkor' => 'Angkor', 'Annie+Use+Your+Telescope' => 'Annie Use Your Telescope', 'Anonymous+Pro' => 'Anonymous Pro', 'Antic' => 'Antic', 'Antic+Didone' => 'Antic Didone', 'Antic+Slab' => 'Antic Slab', 'Anton' => 'Anton', 'Arapey' => 'Arapey', 'Arbutus' => 'Arbutus', 'Arbutus+slab' => 'Arbutus Slab', 'Architects+daughter' => 'Architects Daughter', 'Archivo+black' => 'Archivo Black', 'Archivo+narrow' => 'Archivo Narrow', 'Arimo' => 'Arimo', 'Arizonia' => 'Arizonia', 'Armata' => 'Armata', 'Artifika' => 'Artifika', 'Arvo' => 'Arvo', 'Asap' => 'Asap', 'Asset' => 'Asset', 'Astloch' => 'Astloch', 'Asul' => 'Asul', 'Atomic+age' => 'Atomic Age', 'Aubrey' => 'Aubrey', 'Audiowide' => 'Audiowide', 'Autour+one' => 'Autour One', 'Average' => 'Average', 'Average+Sans' => 'Average Sans', 'Averia+Gruesa+Libre' => 'Averia Gruesa Libre', 'Averia+Libre' => 'Averia Libre', 'Averia+Sans+Libre' => 'Averia Sans Libre', 'Averia+Serif+Libre' => 'Averia Serif Libre', 'Bad+Script' => 'Bad Script', 'Balthazar' => 'Balthazar', 'Bangers' => 'Bangers', 'Basic' => 'Basic', 'Battambang' => 'Battambang', 'Baumans' => 'Baumans', 'Bayon' => 'Bayon', 'Belgrano' => 'Belgrano', 'BenchNine' => 'BenchNine', 'Bentham' => 'Bentham', 'Berkshire+Swash' => 'Berkshire Swash', 'Bevan' => 'Bevan', 'Bigelow+Rules' => 'Bigelow Rules', 'Bigshot+One' => 'Bigshot One', 'Bilbo' => 'Bilbo', 'Bilbo+Swash+Caps' => 'Bilbo Swash Caps', 'Bitter' => 'Bitter', 'Black+Ops+One' => 'Black Ops One', 'Bokor' => 'Bokor', 'Bonbon' => 'Bonbon', 'Boogaloo' => 'Boogaloo', 'Bowlby+One' => 'Bowlby One', 'bowlby+One+SC' => 'Bowlby One SC', 'Brawler' => 'Brawler', 'Bree+Serif' => 'Bree Serif', 'Bubblegum+Sans' => 'Bubblegum Sans', 'Bubbler+One' => 'Bubbler One', 'Buda' => 'Buda', 'Buenard' => 'Buenard', 'Butcherman' => 'Butcherman', 'Butterfly+Kids' => 'Butterfly Kids', 'Cabin' => 'Cabin', 'Cabin+Condensed' => 'Cabin Condensed', 'Cabin+Sketch' => 'Cabin Sketch', 'Caesar+Dressing' => 'Caesar Dressing', 'Cagliostro' => 'Cagliostro', 'Calligraffitti' => 'Calligraffitti', 'Cambo' => 'Cambo', 'Candal' => 'Candal', 'Cantarell' => 'Cantarell', 'Cantata+One' => 'Cantata One', 'Cantora+One' => 'Cantora One', 'Capriola' => 'Capriola', 'Cardo' => 'Cardo', 'Carme' => 'Carme', 'Carrois+Gothic' => 'Carrois Gothic', 'Carrois+Gothic+SC' => 'Carrois Gothic SC', 'Carter+One' => 'Carter One', 'Caudex' => 'Caudex', 'Cedarville+cursive' => 'Cedarville Cursive', 'Ceviche+One' => 'Ceviche One', 'Changa+One' => 'Changa One', 'Chango' => 'Chango', 'Chau+philomene+One' => 'Chau Philomene One', 'Chela+One' => 'Chela One', 'Chelsea+Market' => 'Chelsea Market', 'Chenla' => 'Chenla', 'Cherry+Cream+Soda' => 'Cherry Cream Soda', 'Chewy' => 'Chewy', 'Chicle' => 'Chicle', 'Chivo' => 'Chivo', 'Cinzel' => 'Cinzel', 'Cinzel+Decorative' => 'Cinzel Decorative', 'Clicker+Script' => 'Clicker Script', 'Coda' => 'Coda', 'Coda+Caption' => 'Coda Caption', 'Codystar' => 'Codystar', 'Combo' => 'Combo', 'Comfortaa' => 'Comfortaa', 'Coming+soon' => 'Coming Soon', 'Concert+One' => 'Concert One', 'Condiment' => 'Condiment', 'Content' => 'Content', 'Contrail+One' => 'Contrail One', 'Convergence' => 'Convergence', 'Cookie' => 'Cookie', 'Copse' => 'Copse', 'Corben' => 'Corben', 'Courgette' => 'Courgette', 'Cousine' => 'Cousine', 'Coustard' => 'Coustard', 'Covered+By+Your+Grace' => 'Covered By Your Grace', 'Crafty+Girls' => 'Crafty Girls', 'Creepster' => 'Creepster', 'Crete+Round' => 'Crete Round', 'Crimson+Text' => 'Crimson Text', 'Croissant+One' => 'Croissant One', 'Crushed' => 'Crushed', 'Cuprum' => 'Cuprum', 'Cutive' => 'Cutive', 'Cutive+Mono' => 'Cutive Mono', 'Damion' => 'Damion', 'Dancing+Script' => 'Dancing Script', 'Dangrek' => 'Dangrek', 'Dawning+of+a+New+Day' => 'Dawning of a New Day', 'Days+One' => 'Days One', 'Delius' => 'Delius', 'Delius+Swash+Caps' => 'Delius Swash Caps', 'Delius+Unicase' => 'Delius Unicase', 'Della+Respira' => 'Della Respira', 'Denk+One' => 'Denk One', 'Devonshire' => 'Devonshire', 'Didact+Gothic' => 'Didact Gothic', 'Diplomata' => 'Diplomata', 'Diplomata+SC' => 'Diplomata SC', 'Domine' => 'Domine', 'Donegal+One' => 'Donegal One', 'Doppio+One' => 'Doppio One', 'Dorsa' => 'Dorsa', 'Dosis' => 'Dosis', 'Dr+Sugiyama' => 'Dr Sugiyama', 'Droid+Sans' => 'Droid Sans', 'Droid+Sans+Mono' => 'Droid Sans Mono', 'Droid+Serif' => 'Droid Serif', 'Duru+Sans' => 'Duru Sans', 'Dynalight' => 'Dynalight', 'Eb+Garamond' => 'EB Garamond', 'Eagle+Lake' => 'Eagle Lake', 'Eater' => 'Eater', 'Economica' => 'Economica', 'Electrolize' => 'Electrolize', 'Elsie' => 'Elsie', 'Elsie+Swash+Caps' => 'Elsie Swash Caps', 'Emblema+One' => 'Emblema One', 'Emilys+Candy' => 'Emilys Candy', 'Engagement' => 'Engagement', 'Englebert' => 'Englebert', 'Enriqueta' => 'Enriqueta', 'Erica+One' => 'Erica One', 'Esteban' => 'Esteban', 'Euphoria+Script' => 'Euphoria Script', 'Ewert' => 'Ewert', 'Exo' => 'Exo', 'Expletus+Sans' => 'Expletus Sans', 'Fanwood+Text' => 'Fanwood Text', 'Fascinate' => 'Fascinate', 'Fascinate+Inline' => 'Fascinate Inline', 'Faster+One' => 'Faster One', 'Fasthand' => 'Fasthand', 'Federant' => 'Federant', 'Federo' => 'Federo', 'Felipa' => 'Felipa', 'Fenix' => 'Fenix', 'Finger+Paint' => 'Finger Paint', 'Fjalla+One' => 'Fjalla One', 'Fjord+One' => 'Fjord One', 'Flamenco' => 'Flamenco', 'Flavors' => 'Flavors', 'Fondamento' => 'Fondamento', 'Fontdiner+swanky' => 'Fontdiner Swanky', 'Forum' => 'Forum', 'Francois+One' => 'Francois One', 'Freckle+Face' => 'Freckle Face', 'Fredericka+the+Great' => 'Fredericka the Great', 'Fredoka+One' => 'Fredoka One', 'Freehand' => 'Freehand', 'Fresca' => 'Fresca', 'Frijole' => 'Frijole', 'Fruktur' => 'Fruktur', 'Fugaz+One' => 'Fugaz One', 'GFS+Didot' => 'GFS Didot', 'GFS+Neohellenic' => 'GFS Neohellenic', 'Gabriela' => 'Gabriela', 'Gafata' => 'Gafata', 'Galdeano' => 'Galdeano', 'Galindo' => 'Galindo', 'Gentium+Basic' => 'Gentium Basic', 'Gentium+Book+Basic' => 'Gentium Book Basic', 'Geo' => 'Geo', 'Geostar' => 'Geostar', 'Geostar+Fill' => 'Geostar Fill', 'Germania+One' => 'Germania One', 'Gilda+Display' => 'Gilda Display', 'Give+You+Glory' => 'Give You Glory', 'Glass+Antiqua' => 'Glass Antiqua', 'Glegoo' => 'Glegoo', 'Gloria+Hallelujah' => 'Gloria Hallelujah', 'Goblin+One' => 'Goblin One', 'Gochi+Hand' => 'Gochi Hand', 'Gorditas' => 'Gorditas', 'Goudy+Bookletter+1911' => 'Goudy Bookletter 1911', 'Graduate' => 'Graduate', 'Grand+Hotel' => 'Grand Hotel', 'Gravitas+One' => 'Gravitas One', 'Great+Vibes' => 'Great Vibes', 'Griffy' => 'Griffy', 'Gruppo' => 'Gruppo', 'Gudea' => 'Gudea', 'Habibi' => 'Habibi', 'Hammersmith+One' => 'Hammersmith One', 'Hanalei' => 'Hanalei', 'Hanalei+Fill' => 'Hanalei Fill', 'Handlee' => 'Handlee', 'Hanuman' => 'Hanuman', 'Happy+Monkey' => 'Happy Monkey', 'Headland+One' => 'Headland One', 'Henny+Penny' => 'Henny Penny', 'Herr+Von+Muellerhoff' => 'Herr Von Muellerhoff', 'Holtwood+One +SC' => 'Holtwood One SC', 'Homemade+Apple' => 'Homemade Apple', 'Homenaje' => 'Homenaje', 'IM+Fell+DW+Pica' => 'IM Fell DW Pica', 'IM+Fell+DW+Pica+SC' => 'IM Fell DW Pica SC', 'IM+Fell+Double+Pica' => 'IM Fell Double Pica', 'IM+Fell+Double+Pica+S' => 'IM Fell Double Pica S', 'IM+Fell+English' => 'IM Fell English', 'IM+Fell+English+SC' => 'IM Fell English SC', 'IM+Fell+French+Canon' => 'IM Fell French Canon', 'IM+Fell+French+Canon+SC' => 'IM Fell French Canon SC', 'IM+Fell+Great+Primer' => 'IM Fell Great Primer', 'IM+Fell+Great+Primer+SC' => 'IM Fell Great Primer SC', 'Iceberg' => 'Iceberg', 'Iceland' => 'Iceland', 'Imprima' => 'Imprima', 'Inconsolata' => 'Inconsolata', 'Inder' => 'Inder', 'Indie+Flower' => 'Indie Flower', 'Inika' => 'Inika', 'Irish+Grover' => 'Irish Grover', 'Istok+Web' => 'Istok Web', 'Italiana' => 'Italiana', 'Italianno' => 'Italianno', 'Jacques+Francois' => 'Jacques Francois', 'Jacques+Francois+Shadow' => 'Jacques Francois Shadow', 'Jim+Nightshade' => 'Jim Nightshade', 'Jockey+One' => 'Jockey One', 'Jolly+Lodger' => 'Jolly Lodger', 'Josefin+Sans' => 'Josefin Sans', 'Josefin+Slab' => 'Josefin Slab', 'Joti+One' => 'Joti One', 'Judson' => 'Judson', 'Julee' => 'Julee', 'Julius+Sans+One' => 'Julius Sans One', 'Junge' => 'Junge', 'Jura' => 'Jura', 'Just+Another+Hand' => 'Just Another Hand', 'Just+Me+Again+Down+Here' => 'Just Me Again Down Here', 'Kameron' => 'Kameron', 'Karla' => 'Karla', 'Kaushan+Script' => 'Kaushan Script', 'Kavoon' => 'Kavoon', 'Keania+One' => 'Keania One', 'kelly+Slab' => 'Kelly Slab', 'Kenia' => 'Kenia', 'Khmer' => 'Khmer', 'Kite+One' => 'Kite One', 'Knewave' => 'Knewave', 'Kotta+One' => 'Kotta One', 'Koulen' => 'Koulen', 'Kranky' => 'Kranky', 'Kreon' => 'Kreon', 'Kristi' => 'Kristi', 'Krona+One' => 'Krona One', 'La+Belle+Aurore' => 'La Belle Aurore', 'Lancelot' => 'Lancelot', 'Lato' => 'Lato', 'League+Script' => 'League Script', 'Leckerli+One' => 'Leckerli One', 'Ledger' => 'Ledger', 'Lekton' => 'Lekton', 'Lemon' => 'Lemon', 'Libre+Baskerville' => 'Libre Baskerville', 'Life+Savers' => 'Life Savers', 'Lilita+One' => 'Lilita One', 'Limelight' => 'Limelight', 'Linden+Hill' => 'Linden Hill', 'Lobster' => 'Lobster', 'Lobster+Two' => 'Lobster Two', 'Londrina+Outline' => 'Londrina Outline', 'Londrina+Shadow' => 'Londrina Shadow', 'Londrina+Sketch' => 'Londrina Sketch', 'Londrina+Solid' => 'Londrina Solid', 'Lora' => 'Lora', 'Love+Ya+Like+A+Sister' => 'Love Ya Like A Sister', 'Loved+by+the+King' => 'Loved by the King', 'Lovers+Quarrel' => 'Lovers Quarrel', 'Luckiest+Guy' => 'Luckiest Guy', 'Lusitana' => 'Lusitana', 'Lustria' => 'Lustria', 'Macondo' => 'Macondo', 'Macondo+Swash+Caps' => 'Macondo Swash Caps', 'Magra' => 'Magra', 'Maiden+Orange' => 'Maiden Orange', 'Mako' => 'Mako', 'Marcellus' => 'Marcellus', 'Marcellus+SC' => 'Marcellus SC', 'Marck+Script' => 'Marck Script', 'Margarine' => 'Margarine', 'Marko+One' => 'Marko One', 'Marmelad' => 'Marmelad', 'Marvel' => 'Marvel', 'Mate' => 'Mate', 'Mate+SC' => 'Mate SC', 'Maven+Pro' => 'Maven Pro', 'McLaren' => 'McLaren', 'Meddon' => 'Meddon', 'MedievalSharp' => 'MedievalSharp', 'Medula+One' => 'Medula One', 'Megrim' => 'Megrim', 'Meie+Script' => 'Meie Script', 'Merienda' => 'Merienda', 'Merienda+One' => 'Merienda One', 'Merriweather' => 'Merriweather', 'Merriweather+Sans' => 'Merriweather Sans', 'Metal' => 'Metal', 'Metal+mania' => 'Metal Mania', 'Metamorphous' => 'Metamorphous', 'Metrophobic' => 'Metrophobic', 'Michroma' => 'Michroma', 'Milonga' => 'Milonga', 'Miltonian' => 'Miltonian', 'Miltonian+Tattoo' => 'Miltonian Tattoo', 'Miniver' => 'Miniver', 'Miss+Fajardose' => 'Miss Fajardose', 'Modern+Antiqua' => 'Modern Antiqua', 'Molengo' => 'Molengo', 'Molle' => 'Molle', 'Monda' => 'Monda', 'Monofett' => 'Monofett', 'Monoton' => 'Monoton', 'Monsieur+La+Doulaise' => 'Monsieur La Doulaise', 'Montaga' => 'Montaga', 'Montez' => 'Montez', 'Montserrat' => 'Montserrat', 'Montserrat+Alternates' => 'Montserrat Alternates', 'Montserrat+Subrayada' => 'Montserrat Subrayada', 'Moul' => 'Moul', 'Moulpali' => 'Moulpali', 'Mountains+of+Christmas' => 'Mountains of Christmas', 'Mouse+Memoirs' => 'Mouse Memoirs', 'Mr+Bedfort' => 'Mr Bedfort', 'Mr+Dafoe' => 'Mr Dafoe', 'Mr+De+Haviland' => 'Mr De Haviland', 'Mrs+Saint+Delafield' => 'Mrs Saint Delafield', 'Mrs+Sheppards' => 'Mrs Sheppards', 'Muli' => 'Muli', 'Mystery+Quest' => 'Mystery Quest', 'Neucha' => 'Neucha', 'Neuton' => 'Neuton', 'New+Rocker' => 'New Rocker', 'News+Cycle' => 'News Cycle', 'Niconne' => 'Niconne', 'Nixie+One' => 'Nixie One', 'Nobile' => 'Nobile', 'Nokora' => 'Nokora', 'Norican' => 'Norican', 'Nosifer' => 'Nosifer', 'Nothing+You+Could+Do' => 'Nothing You Could Do', 'Noticia+Text' => 'Noticia Text', 'Nova+Cut' => 'Nova Cut', 'Nova+Flat' => 'Nova Flat', 'Nova+Mono' => 'Nova Mono', 'Nova+Oval' => 'Nova Oval', 'Nova+Round' => 'Nova Round', 'Nova+Script' => 'Nova Script', 'Nova+Slim' => 'Nova Slim', 'Nova+Square' => 'Nova Square', 'Numans' => 'Numans', 'Nunito' => 'Nunito', 'Odor+Mean+Chey' => 'Odor Mean Chey', 'Offside' => 'Offside', 'Old+standard+tt' => 'Old Standard TT', 'Oldenburg' => 'Oldenburg', 'Oleo+Script' => 'Oleo Script', 'Oleo+Script+Swash+Caps' => 'Oleo Script Swash Caps', 'Open+Sans' => 'Open Sans', 'Open+Sans+Condensed' => 'Open Sans Condensed', 'Oranienbaum' => 'Oranienbaum', 'Orbitron' => 'Orbitron',  'Oregano' => 'Oregano', 'Orienta' => 'Orienta', 'Original+Surfer' => 'Original Surfer', 'Oswald' => 'Oswald', 'Over+the+Rainbow' => 'Over the Rainbow', 'Overlock' => 'Overlock', 'Overlock+SC' => 'Overlock SC', 'Ovo' => 'Ovo', 'Oxygen' => 'Oxygen', 'Oxygen+Mono' => 'Oxygen Mono', 'PT+Mono' => 'PT Mono', 'PT+Sans' => 'PT Sans', 'PT+Sans+Caption' => 'PT Sans Caption', 'PT+Sans+Narrow' => 'PT Sans Narrow', 'PT+Serif' => 'PT Serif', 'PT+Serif+Caption' => 'PT Serif Caption', 'Pacifico' => 'Pacifico', 'Paprika' => 'Paprika', 'Parisienne' => 'Parisienne', 'Passero+One' => 'Passero One', 'Passion+One' => 'Passion One', 'Patrick+Hand' => 'Patrick Hand', 'Patrick+Hand+SC' => 'Patrick Hand SC', 'Patua+One' => 'Patua One', 'Paytone+One' => 'Paytone One', 'Peralta' => 'Peralta', 'Permanent+Marker' => 'Permanent Marker', 'Petit+Formal+Script' => 'Petit Formal Script', 'Petrona' => 'Petrona', 'Philosopher' => 'Philosopher', 'Piedra' => 'Piedra', 'Pinyon+Script' => 'Pinyon Script', 'Pirata+One' => 'Pirata One', 'Plaster' => 'Plaster', 'Play' => 'Play', 'Playball' => 'Playball', 'Playfair+Display' => 'Playfair Display', 'Playfair+Display+SC' => 'Playfair Display SC', 'Podkova' => 'Podkova', 'Poiret+One' => 'Poiret One', 'Poller+One' => 'Poller One', 'Poly' => 'Poly', 'Pompiere' => 'Pompiere', 'Pontano+Sans' => 'Pontano Sans', 'Port+Lligat+Sans' => 'Port Lligat Sans', 'Port+Lligat+Slab' => 'Port Lligat Slab', 'Prata' => 'Prata', 'Preahvihear' => 'Preahvihear', 'Press+start+2P' => 'Press Start 2P', 'Princess+Sofia' => 'Princess Sofia', 'Prociono' => 'Prociono', 'Prosto+One' => 'Prosto One', 'Puritan' => 'Puritan', 'Purple+Purse' => 'Purple Purse', 'Quando' => 'Quando', 'Quantico' => 'Quantico', 'Quattrocento' => 'Quattrocento', 'Quattrocento+Sans' => 'Quattrocento Sans', 'Questrial' => 'Questrial', 'Quicksand' => 'Quicksand', 'Quintessential' => 'Quintessential', 'Qwigley' => 'Qwigley', 'Racing+sans+One' => 'Racing Sans One', 'Radley' => 'Radley', 'Raleway' => 'Raleway', 'Raleway+Dots' => 'Raleway Dots', 'Rambla' => 'Rambla', 'Rammetto+One' => 'Rammetto One', 'Ranchers' => 'Ranchers', 'Rancho' => 'Rancho', 'Rationale' => 'Rationale', 'Redressed' => 'Redressed', 'Reenie+Beanie' => 'Reenie Beanie', 'Revalia' => 'Revalia', 'Ribeye' => 'Ribeye', 'Ribeye+Marrow' => 'Ribeye Marrow', 'Righteous' => 'Righteous', 'Risque' => 'Risque', 'Roboto' => 'Roboto', 'Roboto+Condensed' => 'Roboto Condensed', 'Rochester' => 'Rochester', 'Rock+Salt' => 'Rock Salt', 'Rokkitt' => 'Rokkitt', 'Romanesco' => 'Romanesco', 'Ropa+Sans' => 'Ropa Sans', 'Rosario' => 'Rosario', 'Rosarivo' => 'Rosarivo', 'Rouge+Script' => 'Rouge Script', 'Ruda' => 'Ruda', 'Rufina' => 'Rufina', 'Ruge+Boogie' => 'Ruge Boogie', 'Ruluko' => 'Ruluko', 'Rum+Raisin' => 'Rum Raisin', 'Ruslan+Display' => 'Ruslan Display', 'Russo+One' => 'Russo One', 'Ruthie' => 'Ruthie', 'Rye' => 'Rye', 'Sacramento' => 'Sacramento', 'Sail' => 'Sail', 'Salsa' => 'Salsa', 'Sanchez' => 'Sanchez', 'Sancreek' => 'Sancreek', 'Sansita+One' => 'Sansita One', 'Sarina' => 'Sarina', 'Satisfy' => 'Satisfy', 'Scada' => 'Scada', 'Schoolbell' => 'Schoolbell', 'Seaweed+Script' => 'Seaweed Script', 'Sevillana' => 'Sevillana', 'Seymour+One' => 'Seymour One', 'Shadows+Into+Light' => 'Shadows Into Light', 'Shadows+Into+Light+Two' => 'Shadows Into Light Two', 'Shanti' => 'Shanti', 'Share' => 'Share', 'Share+Tech' => 'Share Tech', 'Share+Tech+Mono' => 'Share Tech Mono', 'Shojumaru' => 'Shojumaru',  'Short+Stack' => 'Short Stack', 'Siemreap' => 'Siemreap', 'Sigmar+One' => 'Sigmar One', 'Signika' => 'Signika', 'Signika+Negative' => 'Signika Negative', 'Simonetta' => 'Simonetta', 'Sintony' => 'Sintony', 'Sirin+Stencil' => 'Sirin Stencil', 'Six+Caps' => 'Six Caps', 'Skranji' => 'Skranji', 'Slackey' => 'Slackey', 'Smokum' => 'Smokum', 'Smythe' => 'Smythe', 'Sniglet' => 'Sniglet', 'Snippet' => 'Snippet', 'Snowburst+One' => 'Snowburst One', 'Sofadi+One' => 'Sofadi One', 'Sofia' => 'Sofia', 'Sonsie+One' => 'Sonsie One', 'Sorts+Mill+Goudy' => 'Sorts Mill Goudy', 'Source+Code+Pro' => 'Source Code Pro', 'Source+Sans+Pro' => 'Source Sans Pro', 'Special+Elite' => 'Special Elite', 'Spicy+Rice' => 'Spicy Rice', 'Spinnaker' => 'Spinnaker', 'Spirax' => 'Spirax', 'Squada+One' => 'Squada One', 'Stalemate' => 'Stalemate', 'Stalinist+One' => 'Stalinist One', 'Stardos+Stencil' => 'Stardos Stencil', 'Stint+Ultra+Condensed' => 'Stint Ultra Condensed', 'Stint+Ultra+Expanded' => 'Stint Ultra Expanded', 'Stoke' => 'Stoke', 'Strait' => 'Strait', 'Sue+Ellen+Francisco' => 'Sue Ellen Francisco', 'Sunshiney' => 'Sunshiney', 'Supermercado+One' => 'Supermercado One', 'Suwannaphum' => 'Suwannaphum', 'Swanky+and+Moo+Moo' => 'Swanky and Moo Moo',  'Syncopate' => 'Syncopate', 'Tangerine' => 'Tangerine', 'Taprom' => 'Taprom', 'Tauri' => 'Tauri', 'Telex' => 'Telex', 'Tenor+Sans' => 'Tenor Sans', 'Text+Me+One' => 'Text Me One', 'The+Girl+Next+Door' => 'The Girl Next Door', 'Tienne' => 'Tienne', 'Tinos' => 'Tinos', 'Titan+One' => 'Titan One', 'Titillium+Web' => 'Titillium Web', 'Trade+Winds' => 'Trade Winds', 'Trocchi' => 'Trocchi', 'Trochut' => 'Trochut', 'Trykker' => 'Trykker', 'Tulpen+One' => 'Tulpen One', 'Ubuntu' => 'Ubuntu', 'Ubuntu+Condensed' => 'Ubuntu Condensed', 'Ubuntu+Mono' => 'Ubuntu Mono', 'Ultra' => 'Ultra', 'Uncial+Antiqua' => 'Uncial Antiqua', 'Underdog' => 'Underdog', 'Unica+One' => 'Unica One', 'UnifrakturCook' => 'UnifrakturCook', 'UnifrakturMaguntia' => 'UnifrakturMaguntia', 'Unkempt' => 'Unkempt', 'Unna' => 'Unna', 'VT323' => 'VT323', 'Vampiro+One' => 'Vampiro One', 'Varela' => 'Varela', 'Varela+Round' => 'Varela Round', 'Vast+Shadow' => 'Vast Shadow', 'Vibur' => 'Vibur', 'Vidaloka' => 'Vidaloka', 'Viga' => 'Viga', 'Voces' => 'Voces', 'Volkhov' => 'Volkhov', 'Vollkorn' => 'Vollkorn',  'Voltaire' => 'Voltaire', 'Waiting+for+the+sunrise' => 'Waiting for the Sunrise', 'Wallpoet' => 'Wallpoet', 'Walter+Turncoat' => 'Walter Turncoat', 'Warnes' => 'Warnes', 'Wellfleet' => 'Wellfleet', 'Wendy+One' => 'Wendy One', 'Wire+One' => 'Wire One', 'Yanone+Kaffeesatz' => 'Yanone Kaffeesatz', 'Yellowtail' => 'Yellowtail', 'Yeseva+One' => 'Yeseva One', 'Yesteryear' => 'Yesteryear', 'Zeyada' => 'Zeyada');
    $query = implode("|", str_replace(' ', '+', $google_fonts));
    ?>
    <link id="wds_googlefonts" media="all" type="text/css" href="https://fonts.googleapis.com/css?family=<?php echo $query; ?>" rel="stylesheet">
    <?php
    $id =  WDW_S_Library::get('slider_id');
    ?>
    <div class="wds_preview_cont1">
      <div class="wds_preview_cont2">
        <?php
    wds_front_end($id, 0);
        ?>
      </div>
    </div>
    <?php
  }
  die();
}

function wds_media_button($context) {
  global $pagenow;
  if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {
    $context .= '
      <a onclick="tb_click.call(this); wds_thickDims(); return false;" href="' . add_query_arg(array('action' => 'WDSShortcode', 'TB_iframe' => '1'), admin_url('admin-ajax.php')) . '" class="wds_thickbox button" style="padding-left: 0.4em;" title="Select slider">
        <span class="wp-media-buttons-icon wds_media_button_icon" style="vertical-align: text-bottom; background: url(' . WD_S_URL . '/images/wd_slider.png) no-repeat scroll left top rgba(0, 0, 0, 0);"></span>
        Add Slider WD
      </a>';
  }
  return $context;
}
add_filter('media_buttons_context', 'wds_media_button');

// Add the Slider button to editor.
add_action('wp_ajax_WDSShortcode', 'wds_ajax');

function wds_admin_ajax() {
  ?>
  <script>
    var wds_thickDims, wds_tbWidth, wds_tbHeight;
    wds_tbWidth = 400;
    wds_tbHeight = 200;
    wds_thickDims = function() {
      var tbWindow = jQuery('#TB_window'), H = jQuery(window).height(), W = jQuery(window).width(), w, h;
      w = (wds_tbWidth && wds_tbWidth < W - 90) ? wds_tbWidth : W - 40;
      h = (wds_tbHeight && wds_tbHeight < H - 60) ? wds_tbHeight : H - 40;
      if (tbWindow.size()) {
        tbWindow.width(w).height(h);
        jQuery('#TB_iframeContent').width(w).height(h - 27);
        tbWindow.css({'margin-left': '-' + parseInt((w / 2),10) + 'px'});
        if (typeof document.body.style.maxWidth != 'undefined') {
          tbWindow.css({'top':(H-h)/2,'margin-top':'0'});
        }
      }
    };
  </script>
  <?php
}
add_action('admin_head', 'wds_admin_ajax');

// Add images to Slider.
add_action('wp_ajax_wds_UploadHandler', 'wds_UploadHandler');
add_action('wp_ajax_addImage', 'wds_filemanager_ajax');

// Upload.
function wds_UploadHandler() {
  require_once(WD_S_DIR . '/filemanager/UploadHandler.php');
}

function wds_filemanager_ajax() { 
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  global $wpdb;
  require_once(WD_S_DIR . '/framework/WDW_S_Library.php');
  $page = WDW_S_Library::get('action');
  if (($page != '') && (($page == 'addImage') || ($page == 'addMusic'))) {
    require_once(WD_S_DIR . '/filemanager/controller.php');
    $controller_class = 'FilemanagerController';
    $controller = new $controller_class();
    $controller->execute();
  }
}
// Slider Widget.
if (class_exists('WP_Widget')) {
  require_once(WD_S_DIR . '/admin/controllers/WDSControllerWidgetSlideshow.php');
  add_action('widgets_init', create_function('', 'return register_widget("WDSControllerWidgetSlideshow");'));
}

// Activate plugin.
function wds_activate() {
  global $wpdb;
  wds_install();
  if (!$wpdb->get_var("SELECT * FROM " . $wpdb->prefix . "wdsslider")) {
    $wpdb->insert(
      $wpdb->prefix . 'wdsslider', 
      array(
        'id' => 1,
        'name' => 'Default slider',
        'published' => 1,
        'full_width' => 0,
        'width' => 800,
        'height' => 300,
        'bg_fit' => 'cover',
        'align' => 'center',
        'effect' => 'none',
        'time_intervval' => 5,
        'autoplay' => 1,
        'shuffle' => 0,
        'music' => 0,
        'music_url' => '',
        'preload_images' => 1,
        'background_color' => '000000',
        'background_transparent' => 100,
        'glb_border_width' => 0,
        'glb_border_style' => 'none',
        'glb_border_color' => 'FFFFFF',
        'glb_border_radius' => '',
        'glb_margin' => 0,
        'glb_box_shadow' => '',
        'image_right_click' => 0,
        'layer_out_next' => 1,
        'prev_next_butt' => 1,
        'play_paus_butt' => 0,
        'navigation' => 'hover',
        'rl_butt_style' => 'fa-angle',
        'rl_butt_size' => 40,
        'pp_butt_size' => 40,
        'butts_color' => 'FFFFFF',
        'butts_transparent' => 100,
        'hover_color' => 'CCCCCC',
        'nav_border_width' => 0,
        'nav_border_style' => 'none',
        'nav_border_color' => 'FFFFFF',
        'nav_border_radius' => '20px',
        'nav_bg_color' => 'FFFFFF',
        'bull_position' => 'bottom',
        'bull_style' => 'fa-square-o',
        'bull_size' => 20,
        'bull_color' => 'FFFFFF',
        'bull_act_color' => 'FFFFFF',
        'bull_margin' => 3,
        'film_pos' => 'none',
        'film_thumb_width' => 100,
        'film_thumb_height' => 50,
        'film_bg_color' => '000000',
        'film_tmb_margin' => 0,
        'film_act_border_width' => 0,
        'film_act_border_style' => 'none',
        'film_act_border_color' => 'FFFFFF',
        'film_dac_transparent' => 50,
        'timer_bar_type' => 'none',
        'timer_bar_size' => 5,
        'timer_bar_color' => 'FFFFFF',
        'timer_bar_transparent' => 50,
        'built_in_watermark_type' => 'none',
        'built_in_watermark_position' => 'middle-center',
        'built_in_watermark_size' => 15,
        'built_in_watermark_url' => WD_S_URL . '/images/watermark.png',
        'built_in_watermark_text' => 'web-dorado.com',
        'built_in_watermark_font_size' => 20,
        'built_in_watermark_font' => '',
        'built_in_watermark_color' => 'FFFFFF',
        'built_in_watermark_opacity' => 70,
        'css' => '',
        'stop_animation' => 0,
        'spider_uploader' => 0,
        'right_butt_url' => WD_S_URL . '/images/arrow/arrow11/1/2.png',
        'left_butt_url' => WD_S_URL . '/images/arrow/arrow11/1/1.png',
        'right_butt_hov_url' => WD_S_URL . '/images/arrow/arrow11/1/4.png',
        'left_butt_hov_url' => WD_S_URL . '/images/arrow/arrow11/1/3.png',
        'rl_butt_img_or_not' => 'style',
        'bullets_img_main_url' => WD_S_URL . '/images/bullet/bullet1/1/1.png',
        'bullets_img_hov_url' => WD_S_URL . '/images/bullet/bullet1/1/2.png',
        'bull_butt_img_or_not' => 'style',
        'play_butt_url' => WD_S_URL . '/images/button/button4/1/1.png',
        'paus_butt_url' => WD_S_URL . '/images/button/button4/1/3.png',
        'play_butt_hov_url' => WD_S_URL . '/images/button/button4/1/2.png',
        'paus_butt_hov_url' => WD_S_URL . '/images/button/button4/1/4.png',
        'play_paus_butt_img_or_not' => 'style',
        'start_slide_num' => 1,
        'effect_duration' => 800,
        'carousel' => 0,
        'carousel_image_counts' => 7,
        'carousel_image_parameters' => '0.85',
        'carousel_fit_containerWidth' => 0,
        'carousel_width' => 1000,
        'parallax_effect' => 0,
        'mouse_swipe_nav' => 0,
        'bull_hover' => 1,
        'touch_swipe_nav' => 1,
        'mouse_wheel_nav' => 0,
        'keyboard_nav' => 0,
        'possib_add_ffamily' => '',
        'show_thumbnail' => 0,
        'thumb_size' => '0.2',
	'fixed_bg' => 0,
      )
    );
  }
  if (!$wpdb->get_var("SELECT * FROM " . $wpdb->prefix . "wdsslide")) {
    $wpdb->query('INSERT INTO `' . $wpdb->prefix . 'wdsslide` VALUES(1, 1, "Slide 1", "image", "' . WD_S_URL . '/demo/1.jpg", "' . WD_S_URL . '/demo/1-150x150.jpg", 1, "", 1, 0)');
    $wpdb->query('INSERT INTO `' . $wpdb->prefix . 'wdsslide` VALUES(2, 1, "Slide 2", "image", "' . WD_S_URL . '/demo/2.jpg", "' . WD_S_URL . '/demo/2-150x150.jpg", 1, "", 2, 0)');
    $wpdb->query('INSERT INTO `' . $wpdb->prefix . 'wdsslide` VALUES(3, 1, "Slide 3", "image", "' . WD_S_URL . '/demo/3.jpg", "' . WD_S_URL . '/demo/3-150x150.jpg", 1, "", 3, 0)');
  }
}
register_activation_hook(__FILE__, 'wds_activate');

function wds_install() {
  $version = get_option("wds_version");
  $new_version = '1.1.0';
  if ($version && version_compare($version, $new_version, '<')) {
    require_once WD_S_DIR . "/sliders-update.php";
    wds_update($version);
    update_option("wds_version", $new_version);
  }
  elseif (!$version) {
    require_once WD_S_DIR . "/sliders-insert.php";
    wds_insert();
    add_option("wds_theme_version", '1.0.0', '', 'no');
    add_option("wds_version", $new_version, '', 'no');
    add_option("wds_version_1.0.46", 1, '', 'no');
  }
}
if (!isset($_GET['action']) || $_GET['action'] != 'deactivate') {
  add_action('admin_init', 'wds_install');
}

// Plugin styles.
function wds_styles() {
  $version = get_option("wds_version");
  wp_admin_css('thickbox');
  wp_enqueue_style('wds_tables', WD_S_URL . '/css/wds_tables.css', array(), $version);
  wp_enqueue_style('wds_tables_640', WD_S_URL . '/css/wds_tables_640.css', array(), $version);
  wp_enqueue_style('wds_tables_320', WD_S_URL . '/css/wds_tables_320.css', array(), $version);
  $google_fonts = array('ABeeZee' => 'ABeeZee', 'Abel' => 'Abel', 'Abril+Fatface' => 'Abril Fatface', 'Aclonica' => 'Aclonica', 'Acme' => 'Acme', 'Actor' => 'Actor', 'Adamina' => 'Adamina', 'Advent+Pro' => 'Advent Pro', 'Aguafina+Script' => 'Aguafina Script', 'Akronim' => 'Akronim', 'Aladin' => 'Aladin', 'Aldrich' => 'Aldrich', 'Alegreya' => 'Alegreya', 'Alegreya+SC' => 'Alegreya SC', 'Alex+Brush' => 'Alex Brush', 'Alfa+Slab+One' => 'Alfa Slab One', 'Alice' => 'Alice', 'Alike' => 'Alike', 'Alike+Angular' => 'Alike Angular', 'Allan' => 'Allan', 'Allerta' => 'Allerta', 'Allura' => 'Allura', 'Almendra' => 'Almendra', 'Almendra+display' => 'Almendra Display', 'Almendra+sc' => 'Almendra SC', 'Amarante' => 'Amarante', 'Amaranth' => 'Amaranth', 'Amatic+sc' => 'Amatic SC', 'Amethysta' => 'Amethysta', 'Anaheim' => 'Anaheim', 'Andada' => 'Andada', 'Andika' => 'Andika', 'Angkor' => 'Angkor', 'Annie+Use+Your+Telescope' => 'Annie Use Your Telescope', 'Anonymous+Pro' => 'Anonymous Pro', 'Antic' => 'Antic', 'Antic+Didone' => 'Antic Didone', 'Antic+Slab' => 'Antic Slab', 'Anton' => 'Anton', 'Arapey' => 'Arapey', 'Arbutus' => 'Arbutus', 'Arbutus+slab' => 'Arbutus Slab', 'Architects+daughter' => 'Architects Daughter', 'Archivo+black' => 'Archivo Black', 'Archivo+narrow' => 'Archivo Narrow', 'Arimo' => 'Arimo', 'Arizonia' => 'Arizonia', 'Armata' => 'Armata', 'Artifika' => 'Artifika', 'Arvo' => 'Arvo', 'Asap' => 'Asap', 'Asset' => 'Asset', 'Astloch' => 'Astloch', 'Asul' => 'Asul', 'Atomic+age' => 'Atomic Age', 'Aubrey' => 'Aubrey', 'Audiowide' => 'Audiowide', 'Autour+one' => 'Autour One', 'Average' => 'Average', 'Average+Sans' => 'Average Sans', 'Averia+Gruesa+Libre' => 'Averia Gruesa Libre', 'Averia+Libre' => 'Averia Libre', 'Averia+Sans+Libre' => 'Averia Sans Libre', 'Averia+Serif+Libre' => 'Averia Serif Libre', 'Bad+Script' => 'Bad Script', 'Balthazar' => 'Balthazar', 'Bangers' => 'Bangers', 'Basic' => 'Basic', 'Battambang' => 'Battambang', 'Baumans' => 'Baumans', 'Bayon' => 'Bayon', 'Belgrano' => 'Belgrano', 'BenchNine' => 'BenchNine', 'Bentham' => 'Bentham', 'Berkshire+Swash' => 'Berkshire Swash', 'Bevan' => 'Bevan', 'Bigelow+Rules' => 'Bigelow Rules', 'Bigshot+One' => 'Bigshot One', 'Bilbo' => 'Bilbo', 'Bilbo+Swash+Caps' => 'Bilbo Swash Caps', 'Bitter' => 'Bitter', 'Black+Ops+One' => 'Black Ops One', 'Bokor' => 'Bokor', 'Bonbon' => 'Bonbon', 'Boogaloo' => 'Boogaloo', 'Bowlby+One' => 'Bowlby One', 'bowlby+One+SC' => 'Bowlby One SC', 'Brawler' => 'Brawler', 'Bree+Serif' => 'Bree Serif', 'Bubblegum+Sans' => 'Bubblegum Sans', 'Bubbler+One' => 'Bubbler One', 'Buda' => 'Buda', 'Buenard' => 'Buenard', 'Butcherman' => 'Butcherman', 'Butterfly+Kids' => 'Butterfly Kids', 'Cabin' => 'Cabin', 'Cabin+Condensed' => 'Cabin Condensed', 'Cabin+Sketch' => 'Cabin Sketch', 'Caesar+Dressing' => 'Caesar Dressing', 'Cagliostro' => 'Cagliostro', 'Calligraffitti' => 'Calligraffitti', 'Cambo' => 'Cambo', 'Candal' => 'Candal', 'Cantarell' => 'Cantarell', 'Cantata+One' => 'Cantata One', 'Cantora+One' => 'Cantora One', 'Capriola' => 'Capriola', 'Cardo' => 'Cardo', 'Carme' => 'Carme', 'Carrois+Gothic' => 'Carrois Gothic', 'Carrois+Gothic+SC' => 'Carrois Gothic SC', 'Carter+One' => 'Carter One', 'Caudex' => 'Caudex', 'Cedarville+cursive' => 'Cedarville Cursive', 'Ceviche+One' => 'Ceviche One', 'Changa+One' => 'Changa One', 'Chango' => 'Chango', 'Chau+philomene+One' => 'Chau Philomene One', 'Chela+One' => 'Chela One', 'Chelsea+Market' => 'Chelsea Market', 'Chenla' => 'Chenla', 'Cherry+Cream+Soda' => 'Cherry Cream Soda', 'Chewy' => 'Chewy', 'Chicle' => 'Chicle', 'Chivo' => 'Chivo', 'Cinzel' => 'Cinzel', 'Cinzel+Decorative' => 'Cinzel Decorative', 'Clicker+Script' => 'Clicker Script', 'Coda' => 'Coda', 'Coda+Caption' => 'Coda Caption', 'Codystar' => 'Codystar', 'Combo' => 'Combo', 'Comfortaa' => 'Comfortaa', 'Coming+soon' => 'Coming Soon', 'Concert+One' => 'Concert One', 'Condiment' => 'Condiment', 'Content' => 'Content', 'Contrail+One' => 'Contrail One', 'Convergence' => 'Convergence', 'Cookie' => 'Cookie', 'Copse' => 'Copse', 'Corben' => 'Corben', 'Courgette' => 'Courgette', 'Cousine' => 'Cousine', 'Coustard' => 'Coustard', 'Covered+By+Your+Grace' => 'Covered By Your Grace', 'Crafty+Girls' => 'Crafty Girls', 'Creepster' => 'Creepster', 'Crete+Round' => 'Crete Round', 'Crimson+Text' => 'Crimson Text', 'Croissant+One' => 'Croissant One', 'Crushed' => 'Crushed', 'Cuprum' => 'Cuprum', 'Cutive' => 'Cutive', 'Cutive+Mono' => 'Cutive Mono', 'Damion' => 'Damion', 'Dancing+Script' => 'Dancing Script', 'Dangrek' => 'Dangrek', 'Dawning+of+a+New+Day' => 'Dawning of a New Day', 'Days+One' => 'Days One', 'Delius' => 'Delius', 'Delius+Swash+Caps' => 'Delius Swash Caps', 'Delius+Unicase' => 'Delius Unicase', 'Della+Respira' => 'Della Respira', 'Denk+One' => 'Denk One', 'Devonshire' => 'Devonshire', 'Didact+Gothic' => 'Didact Gothic', 'Diplomata' => 'Diplomata', 'Diplomata+SC' => 'Diplomata SC', 'Domine' => 'Domine', 'Donegal+One' => 'Donegal One', 'Doppio+One' => 'Doppio One', 'Dorsa' => 'Dorsa', 'Dosis' => 'Dosis', 'Dr+Sugiyama' => 'Dr Sugiyama', 'Droid+Sans' => 'Droid Sans', 'Droid+Sans+Mono' => 'Droid Sans Mono', 'Droid+Serif' => 'Droid Serif', 'Duru+Sans' => 'Duru Sans', 'Dynalight' => 'Dynalight', 'Eb+Garamond' => 'EB Garamond', 'Eagle+Lake' => 'Eagle Lake', 'Eater' => 'Eater', 'Economica' => 'Economica', 'Electrolize' => 'Electrolize', 'Elsie' => 'Elsie', 'Elsie+Swash+Caps' => 'Elsie Swash Caps', 'Emblema+One' => 'Emblema One', 'Emilys+Candy' => 'Emilys Candy', 'Engagement' => 'Engagement', 'Englebert' => 'Englebert', 'Enriqueta' => 'Enriqueta', 'Erica+One' => 'Erica One', 'Esteban' => 'Esteban', 'Euphoria+Script' => 'Euphoria Script', 'Ewert' => 'Ewert', 'Exo' => 'Exo', 'Expletus+Sans' => 'Expletus Sans', 'Fanwood+Text' => 'Fanwood Text', 'Fascinate' => 'Fascinate', 'Fascinate+Inline' => 'Fascinate Inline', 'Faster+One' => 'Faster One', 'Fasthand' => 'Fasthand', 'Federant' => 'Federant', 'Federo' => 'Federo', 'Felipa' => 'Felipa', 'Fenix' => 'Fenix', 'Finger+Paint' => 'Finger Paint', 'Fjalla+One' => 'Fjalla One', 'Fjord+One' => 'Fjord One', 'Flamenco' => 'Flamenco', 'Flavors' => 'Flavors', 'Fondamento' => 'Fondamento', 'Fontdiner+swanky' => 'Fontdiner Swanky', 'Forum' => 'Forum', 'Francois+One' => 'Francois One', 'Freckle+Face' => 'Freckle Face', 'Fredericka+the+Great' => 'Fredericka the Great', 'Fredoka+One' => 'Fredoka One', 'Freehand' => 'Freehand', 'Fresca' => 'Fresca', 'Frijole' => 'Frijole', 'Fruktur' => 'Fruktur', 'Fugaz+One' => 'Fugaz One', 'GFS+Didot' => 'GFS Didot', 'GFS+Neohellenic' => 'GFS Neohellenic', 'Gabriela' => 'Gabriela', 'Gafata' => 'Gafata', 'Galdeano' => 'Galdeano', 'Galindo' => 'Galindo', 'Gentium+Basic' => 'Gentium Basic', 'Gentium+Book+Basic' => 'Gentium Book Basic', 'Geo' => 'Geo', 'Geostar' => 'Geostar', 'Geostar+Fill' => 'Geostar Fill', 'Germania+One' => 'Germania One', 'Gilda+Display' => 'Gilda Display', 'Give+You+Glory' => 'Give You Glory', 'Glass+Antiqua' => 'Glass Antiqua', 'Glegoo' => 'Glegoo', 'Gloria+Hallelujah' => 'Gloria Hallelujah', 'Goblin+One' => 'Goblin One', 'Gochi+Hand' => 'Gochi Hand', 'Gorditas' => 'Gorditas', 'Goudy+Bookletter+1911' => 'Goudy Bookletter 1911', 'Graduate' => 'Graduate', 'Grand+Hotel' => 'Grand Hotel', 'Gravitas+One' => 'Gravitas One', 'Great+Vibes' => 'Great Vibes', 'Griffy' => 'Griffy', 'Gruppo' => 'Gruppo', 'Gudea' => 'Gudea', 'Habibi' => 'Habibi', 'Hammersmith+One' => 'Hammersmith One', 'Hanalei' => 'Hanalei', 'Hanalei+Fill' => 'Hanalei Fill', 'Handlee' => 'Handlee', 'Hanuman' => 'Hanuman', 'Happy+Monkey' => 'Happy Monkey', 'Headland+One' => 'Headland One', 'Henny+Penny' => 'Henny Penny', 'Herr+Von+Muellerhoff' => 'Herr Von Muellerhoff', 'Holtwood+One +SC' => 'Holtwood One SC', 'Homemade+Apple' => 'Homemade Apple', 'Homenaje' => 'Homenaje', 'IM+Fell+DW+Pica' => 'IM Fell DW Pica', 'IM+Fell+DW+Pica+SC' => 'IM Fell DW Pica SC', 'IM+Fell+Double+Pica' => 'IM Fell Double Pica', 'IM+Fell+Double+Pica+S' => 'IM Fell Double Pica S', 'IM+Fell+English' => 'IM Fell English', 'IM+Fell+English+SC' => 'IM Fell English SC', 'IM+Fell+French+Canon' => 'IM Fell French Canon', 'IM+Fell+French+Canon+SC' => 'IM Fell French Canon SC', 'IM+Fell+Great+Primer' => 'IM Fell Great Primer', 'IM+Fell+Great+Primer+SC' => 'IM Fell Great Primer SC', 'Iceberg' => 'Iceberg', 'Iceland' => 'Iceland', 'Imprima' => 'Imprima', 'Inconsolata' => 'Inconsolata', 'Inder' => 'Inder', 'Indie+Flower' => 'Indie Flower', 'Inika' => 'Inika', 'Irish+Grover' => 'Irish Grover', 'Istok+Web' => 'Istok Web', 'Italiana' => 'Italiana', 'Italianno' => 'Italianno', 'Jacques+Francois' => 'Jacques Francois', 'Jacques+Francois+Shadow' => 'Jacques Francois Shadow', 'Jim+Nightshade' => 'Jim Nightshade', 'Jockey+One' => 'Jockey One', 'Jolly+Lodger' => 'Jolly Lodger', 'Josefin+Sans' => 'Josefin Sans', 'Josefin+Slab' => 'Josefin Slab', 'Joti+One' => 'Joti One', 'Judson' => 'Judson', 'Julee' => 'Julee', 'Julius+Sans+One' => 'Julius Sans One', 'Junge' => 'Junge', 'Jura' => 'Jura', 'Just+Another+Hand' => 'Just Another Hand', 'Just+Me+Again+Down+Here' => 'Just Me Again Down Here', 'Kameron' => 'Kameron', 'Karla' => 'Karla', 'Kaushan+Script' => 'Kaushan Script', 'Kavoon' => 'Kavoon', 'Keania+One' => 'Keania One', 'kelly+Slab' => 'Kelly Slab', 'Kenia' => 'Kenia', 'Khmer' => 'Khmer', 'Kite+One' => 'Kite One', 'Knewave' => 'Knewave', 'Kotta+One' => 'Kotta One', 'Koulen' => 'Koulen', 'Kranky' => 'Kranky', 'Kreon' => 'Kreon', 'Kristi' => 'Kristi', 'Krona+One' => 'Krona One', 'La+Belle+Aurore' => 'La Belle Aurore', 'Lancelot' => 'Lancelot', 'Lato' => 'Lato', 'League+Script' => 'League Script', 'Leckerli+One' => 'Leckerli One', 'Ledger' => 'Ledger', 'Lekton' => 'Lekton', 'Lemon' => 'Lemon', 'Libre+Baskerville' => 'Libre Baskerville', 'Life+Savers' => 'Life Savers', 'Lilita+One' => 'Lilita One', 'Limelight' => 'Limelight', 'Linden+Hill' => 'Linden Hill', 'Lobster' => 'Lobster', 'Lobster+Two' => 'Lobster Two', 'Londrina+Outline' => 'Londrina Outline', 'Londrina+Shadow' => 'Londrina Shadow', 'Londrina+Sketch' => 'Londrina Sketch', 'Londrina+Solid' => 'Londrina Solid', 'Lora' => 'Lora', 'Love+Ya+Like+A+Sister' => 'Love Ya Like A Sister', 'Loved+by+the+King' => 'Loved by the King', 'Lovers+Quarrel' => 'Lovers Quarrel', 'Luckiest+Guy' => 'Luckiest Guy', 'Lusitana' => 'Lusitana', 'Lustria' => 'Lustria', 'Macondo' => 'Macondo', 'Macondo+Swash+Caps' => 'Macondo Swash Caps', 'Magra' => 'Magra', 'Maiden+Orange' => 'Maiden Orange', 'Mako' => 'Mako', 'Marcellus' => 'Marcellus', 'Marcellus+SC' => 'Marcellus SC', 'Marck+Script' => 'Marck Script', 'Margarine' => 'Margarine', 'Marko+One' => 'Marko One', 'Marmelad' => 'Marmelad', 'Marvel' => 'Marvel', 'Mate' => 'Mate', 'Mate+SC' => 'Mate SC', 'Maven+Pro' => 'Maven Pro', 'McLaren' => 'McLaren', 'Meddon' => 'Meddon', 'MedievalSharp' => 'MedievalSharp', 'Medula+One' => 'Medula One', 'Megrim' => 'Megrim', 'Meie+Script' => 'Meie Script', 'Merienda' => 'Merienda', 'Merienda+One' => 'Merienda One', 'Merriweather' => 'Merriweather', 'Merriweather+Sans' => 'Merriweather Sans', 'Metal' => 'Metal', 'Metal+mania' => 'Metal Mania', 'Metamorphous' => 'Metamorphous', 'Metrophobic' => 'Metrophobic', 'Michroma' => 'Michroma', 'Milonga' => 'Milonga', 'Miltonian' => 'Miltonian', 'Miltonian+Tattoo' => 'Miltonian Tattoo', 'Miniver' => 'Miniver', 'Miss+Fajardose' => 'Miss Fajardose', 'Modern+Antiqua' => 'Modern Antiqua', 'Molengo' => 'Molengo', 'Molle' => 'Molle', 'Monda' => 'Monda', 'Monofett' => 'Monofett', 'Monoton' => 'Monoton', 'Monsieur+La+Doulaise' => 'Monsieur La Doulaise', 'Montaga' => 'Montaga', 'Montez' => 'Montez', 'Montserrat' => 'Montserrat', 'Montserrat+Alternates' => 'Montserrat Alternates', 'Montserrat+Subrayada' => 'Montserrat Subrayada', 'Moul' => 'Moul', 'Moulpali' => 'Moulpali', 'Mountains+of+Christmas' => 'Mountains of Christmas', 'Mouse+Memoirs' => 'Mouse Memoirs', 'Mr+Bedfort' => 'Mr Bedfort', 'Mr+Dafoe' => 'Mr Dafoe', 'Mr+De+Haviland' => 'Mr De Haviland', 'Mrs+Saint+Delafield' => 'Mrs Saint Delafield', 'Mrs+Sheppards' => 'Mrs Sheppards', 'Muli' => 'Muli', 'Mystery+Quest' => 'Mystery Quest', 'Neucha' => 'Neucha', 'Neuton' => 'Neuton', 'New+Rocker' => 'New Rocker', 'News+Cycle' => 'News Cycle', 'Niconne' => 'Niconne', 'Nixie+One' => 'Nixie One', 'Nobile' => 'Nobile', 'Nokora' => 'Nokora', 'Norican' => 'Norican', 'Nosifer' => 'Nosifer', 'Nothing+You+Could+Do' => 'Nothing You Could Do', 'Noticia+Text' => 'Noticia Text', 'Nova+Cut' => 'Nova Cut', 'Nova+Flat' => 'Nova Flat', 'Nova+Mono' => 'Nova Mono', 'Nova+Oval' => 'Nova Oval', 'Nova+Round' => 'Nova Round', 'Nova+Script' => 'Nova Script', 'Nova+Slim' => 'Nova Slim', 'Nova+Square' => 'Nova Square', 'Numans' => 'Numans', 'Nunito' => 'Nunito', 'Odor+Mean+Chey' => 'Odor Mean Chey', 'Offside' => 'Offside', 'Old+standard+tt' => 'Old Standard TT', 'Oldenburg' => 'Oldenburg', 'Oleo+Script' => 'Oleo Script', 'Oleo+Script+Swash+Caps' => 'Oleo Script Swash Caps', 'Open+Sans' => 'Open Sans', 'Open+Sans+Condensed' => 'Open Sans Condensed', 'Oranienbaum' => 'Oranienbaum', 'Orbitron' => 'Orbitron',  'Oregano' => 'Oregano', 'Orienta' => 'Orienta', 'Original+Surfer' => 'Original Surfer', 'Oswald' => 'Oswald', 'Over+the+Rainbow' => 'Over the Rainbow', 'Overlock' => 'Overlock', 'Overlock+SC' => 'Overlock SC', 'Ovo' => 'Ovo', 'Oxygen' => 'Oxygen', 'Oxygen+Mono' => 'Oxygen Mono', 'PT+Mono' => 'PT Mono', 'PT+Sans' => 'PT Sans', 'PT+Sans+Caption' => 'PT Sans Caption', 'PT+Sans+Narrow' => 'PT Sans Narrow', 'PT+Serif' => 'PT Serif', 'PT+Serif+Caption' => 'PT Serif Caption', 'Pacifico' => 'Pacifico', 'Paprika' => 'Paprika', 'Parisienne' => 'Parisienne', 'Passero+One' => 'Passero One', 'Passion+One' => 'Passion One', 'Patrick+Hand' => 'Patrick Hand', 'Patrick+Hand+SC' => 'Patrick Hand SC', 'Patua+One' => 'Patua One', 'Paytone+One' => 'Paytone One', 'Peralta' => 'Peralta', 'Permanent+Marker' => 'Permanent Marker', 'Petit+Formal+Script' => 'Petit Formal Script', 'Petrona' => 'Petrona', 'Philosopher' => 'Philosopher', 'Piedra' => 'Piedra', 'Pinyon+Script' => 'Pinyon Script', 'Pirata+One' => 'Pirata One', 'Plaster' => 'Plaster', 'Play' => 'Play', 'Playball' => 'Playball', 'Playfair+Display' => 'Playfair Display', 'Playfair+Display+SC' => 'Playfair Display SC', 'Podkova' => 'Podkova', 'Poiret+One' => 'Poiret One', 'Poller+One' => 'Poller One', 'Poly' => 'Poly', 'Pompiere' => 'Pompiere', 'Pontano+Sans' => 'Pontano Sans', 'Port+Lligat+Sans' => 'Port Lligat Sans', 'Port+Lligat+Slab' => 'Port Lligat Slab', 'Prata' => 'Prata', 'Preahvihear' => 'Preahvihear', 'Press+start+2P' => 'Press Start 2P', 'Princess+Sofia' => 'Princess Sofia', 'Prociono' => 'Prociono', 'Prosto+One' => 'Prosto One', 'Puritan' => 'Puritan', 'Purple+Purse' => 'Purple Purse', 'Quando' => 'Quando', 'Quantico' => 'Quantico', 'Quattrocento' => 'Quattrocento', 'Quattrocento+Sans' => 'Quattrocento Sans', 'Questrial' => 'Questrial', 'Quicksand' => 'Quicksand', 'Quintessential' => 'Quintessential', 'Qwigley' => 'Qwigley', 'Racing+sans+One' => 'Racing Sans One', 'Radley' => 'Radley', 'Raleway' => 'Raleway', 'Raleway+Dots' => 'Raleway Dots', 'Rambla' => 'Rambla', 'Rammetto+One' => 'Rammetto One', 'Ranchers' => 'Ranchers', 'Rancho' => 'Rancho', 'Rationale' => 'Rationale', 'Redressed' => 'Redressed', 'Reenie+Beanie' => 'Reenie Beanie', 'Revalia' => 'Revalia', 'Ribeye' => 'Ribeye', 'Ribeye+Marrow' => 'Ribeye Marrow', 'Righteous' => 'Righteous', 'Risque' => 'Risque', 'Roboto' => 'Roboto', 'Roboto+Condensed' => 'Roboto Condensed', 'Rochester' => 'Rochester', 'Rock+Salt' => 'Rock Salt', 'Rokkitt' => 'Rokkitt', 'Romanesco' => 'Romanesco', 'Ropa+Sans' => 'Ropa Sans', 'Rosario' => 'Rosario', 'Rosarivo' => 'Rosarivo', 'Rouge+Script' => 'Rouge Script', 'Ruda' => 'Ruda', 'Rufina' => 'Rufina', 'Ruge+Boogie' => 'Ruge Boogie', 'Ruluko' => 'Ruluko', 'Rum+Raisin' => 'Rum Raisin', 'Ruslan+Display' => 'Ruslan Display', 'Russo+One' => 'Russo One', 'Ruthie' => 'Ruthie', 'Rye' => 'Rye', 'Sacramento' => 'Sacramento', 'Sail' => 'Sail', 'Salsa' => 'Salsa', 'Sanchez' => 'Sanchez', 'Sancreek' => 'Sancreek', 'Sansita+One' => 'Sansita One', 'Sarina' => 'Sarina', 'Satisfy' => 'Satisfy', 'Scada' => 'Scada', 'Schoolbell' => 'Schoolbell', 'Seaweed+Script' => 'Seaweed Script', 'Sevillana' => 'Sevillana', 'Seymour+One' => 'Seymour One', 'Shadows+Into+Light' => 'Shadows Into Light', 'Shadows+Into+Light+Two' => 'Shadows Into Light Two', 'Shanti' => 'Shanti', 'Share' => 'Share', 'Share+Tech' => 'Share Tech', 'Share+Tech+Mono' => 'Share Tech Mono', 'Shojumaru' => 'Shojumaru',  'Short+Stack' => 'Short Stack', 'Siemreap' => 'Siemreap', 'Sigmar+One' => 'Sigmar One', 'Signika' => 'Signika', 'Signika+Negative' => 'Signika Negative', 'Simonetta' => 'Simonetta', 'Sintony' => 'Sintony', 'Sirin+Stencil' => 'Sirin Stencil', 'Six+Caps' => 'Six Caps', 'Skranji' => 'Skranji', 'Slackey' => 'Slackey', 'Smokum' => 'Smokum', 'Smythe' => 'Smythe', 'Sniglet' => 'Sniglet', 'Snippet' => 'Snippet', 'Snowburst+One' => 'Snowburst One', 'Sofadi+One' => 'Sofadi One', 'Sofia' => 'Sofia', 'Sonsie+One' => 'Sonsie One', 'Sorts+Mill+Goudy' => 'Sorts Mill Goudy', 'Source+Code+Pro' => 'Source Code Pro', 'Source+Sans+Pro' => 'Source Sans Pro', 'Special+Elite' => 'Special Elite', 'Spicy+Rice' => 'Spicy Rice', 'Spinnaker' => 'Spinnaker', 'Spirax' => 'Spirax', 'Squada+One' => 'Squada One', 'Stalemate' => 'Stalemate', 'Stalinist+One' => 'Stalinist One', 'Stardos+Stencil' => 'Stardos Stencil', 'Stint+Ultra+Condensed' => 'Stint Ultra Condensed', 'Stint+Ultra+Expanded' => 'Stint Ultra Expanded', 'Stoke' => 'Stoke', 'Strait' => 'Strait', 'Sue+Ellen+Francisco' => 'Sue Ellen Francisco', 'Sunshiney' => 'Sunshiney', 'Supermercado+One' => 'Supermercado One', 'Suwannaphum' => 'Suwannaphum', 'Swanky+and+Moo+Moo' => 'Swanky and Moo Moo',  'Syncopate' => 'Syncopate', 'Tangerine' => 'Tangerine', 'Taprom' => 'Taprom', 'Tauri' => 'Tauri', 'Telex' => 'Telex', 'Tenor+Sans' => 'Tenor Sans', 'Text+Me+One' => 'Text Me One', 'The+Girl+Next+Door' => 'The Girl Next Door', 'Tienne' => 'Tienne', 'Tinos' => 'Tinos', 'Titan+One' => 'Titan One', 'Titillium+Web' => 'Titillium Web', 'Trade+Winds' => 'Trade Winds', 'Trocchi' => 'Trocchi', 'Trochut' => 'Trochut', 'Trykker' => 'Trykker', 'Tulpen+One' => 'Tulpen One', 'Ubuntu' => 'Ubuntu', 'Ubuntu+Condensed' => 'Ubuntu Condensed', 'Ubuntu+Mono' => 'Ubuntu Mono', 'Ultra' => 'Ultra', 'Uncial+Antiqua' => 'Uncial Antiqua', 'Underdog' => 'Underdog', 'Unica+One' => 'Unica One', 'UnifrakturCook' => 'UnifrakturCook', 'UnifrakturMaguntia' => 'UnifrakturMaguntia', 'Unkempt' => 'Unkempt', 'Unna' => 'Unna', 'VT323' => 'VT323', 'Vampiro+One' => 'Vampiro One', 'Varela' => 'Varela', 'Varela+Round' => 'Varela Round', 'Vast+Shadow' => 'Vast Shadow', 'Vibur' => 'Vibur', 'Vidaloka' => 'Vidaloka', 'Viga' => 'Viga', 'Voces' => 'Voces', 'Volkhov' => 'Volkhov', 'Vollkorn' => 'Vollkorn',  'Voltaire' => 'Voltaire', 'Waiting+for+the+sunrise' => 'Waiting for the Sunrise', 'Wallpoet' => 'Wallpoet', 'Walter+Turncoat' => 'Walter Turncoat', 'Warnes' => 'Warnes', 'Wellfleet' => 'Wellfleet', 'Wendy+One' => 'Wendy One', 'Wire+One' => 'Wire One', 'Yanone+Kaffeesatz' => 'Yanone Kaffeesatz', 'Yellowtail' => 'Yellowtail', 'Yeseva+One' => 'Yeseva One', 'Yesteryear' => 'Yesteryear', 'Zeyada' => 'Zeyada');
  $query = implode("|", str_replace(' ', '+', $google_fonts));
  $url = 'https://fonts.googleapis.com/css?family=' . $query;
  wp_enqueue_style('wds_googlefonts', $url, null, null);
}

// Plugin scripts.
function wds_scripts() {
  $version = get_option("wds_version");
  wp_enqueue_media();
  wp_enqueue_script('thickbox');
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-sortable');
  wp_enqueue_script('jquery-ui-draggable');
  wp_enqueue_script('jquery-ui-tooltip');
  wp_enqueue_script('wds_admin', WD_S_URL . '/js/wds.js', array(), $version);
  wp_enqueue_script('jscolor', WD_S_URL . '/js/jscolor/jscolor.js', array(), '1.3.9');
  wp_enqueue_style('wds_font-awesome', WD_S_URL . '/css/font-awesome-4.0.1/font-awesome.css', array(), '4.0.1');
  wp_enqueue_style('wds_effects', WD_S_URL . '/css/wds_effects.css', array(), $version);
  wp_enqueue_style('wds_tooltip', WD_S_URL . '/css/jquery-ui-1.10.3.custom.css', array(), $version);
}

function wds_front_end_scripts() {
  $version = get_option("wds_version");
  wp_enqueue_script('jquery');

  wp_enqueue_script('wds_jquery_mobile', WD_S_FRONT_URL . '/js/jquery.mobile.js', array(), $version);

  wp_enqueue_script('wds_frontend', WD_S_FRONT_URL . '/js/wds_frontend.js', array(), $version);
  wp_enqueue_style('wds_frontend', WD_S_FRONT_URL . '/css/wds_frontend.css', array(), $version);
  wp_enqueue_style('wds_effects', WD_S_FRONT_URL . '/css/wds_effects.css', array(), $version);

  wp_enqueue_style('wds_font-awesome', WD_S_FRONT_URL . '/css/font-awesome-4.0.1/font-awesome.css', array(), '4.0.1');
  global $wpdb;
  $rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wdslayer ORDER BY `depth` ASC");
  $font_array = array();
  foreach ($rows as $row) {
    if (isset($row->google_fonts) && ($row->google_fonts == 1) && ($row->ffamily != "") && !in_array($row->ffamily, $font_array)) {
      $font_array[] = $row->ffamily;
	  }
  }
  $query = implode("|", $font_array);
  if ($query != '') {
    $url = 'https://fonts.googleapis.com/css?family=' . $query;
    wp_enqueue_style('wds_googlefonts', $url, null, null);
  }
}
add_action('wp_enqueue_scripts', 'wds_front_end_scripts');

// Languages localization.
function wds_language_load() {
  load_plugin_textdomain('wds', FALSE, basename(dirname(__FILE__)) . '/languages');
}
add_action('init', 'wds_language_load');

if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
	include_once(WD_S_DIR . '/sliders-notices.php');
  new WDS_Notices();
}
?>