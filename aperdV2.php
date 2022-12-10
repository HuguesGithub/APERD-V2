<?php
/**
 * Plugin Name: HJ - APERD V2
 * Description: Plugin APERD V2. Gestion des compte-rendus
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.05
 */
define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_PACKAGE', 'APERD_V2');
session_start([]);

class AperdV2
{
    public function __construct()
    {
        add_filter('template_include', array($this,'template_loader'));
    }

    public function template_loader()
    {
        wp_enqueue_script('jquery');
        return PLUGIN_PATH.'web/pages/publique/main-publique-page.php';
    }
}
$objAperd = new AperdV2();

/**
#######################################################################################
### Autoload des classes utilisées
#######################################################################################
*/
spl_autoload_register(PLUGIN_PACKAGE.'_autoloader');
function aperd_v2_autoloader($classname)
{
    $pattern = "/(Bean|DaoImpl|Dao|Services|Actions|Utils|Interface|Class)/";
    if (preg_match($pattern, $classname, $matches)) {
        if (isset($matches[1])) {
            if (strpos($classname, '\\')!==false) {
                $classname = substr($classname, strrpos($classname, '\\')+1);
            }
            switch ($matches[1]) {
                case 'Interface' :
                    if (file_exists(PLUGIN_PATH.'core/interfaceimpl/'.$classname.'.php')) {
                        include_once(PLUGIN_PATH.'core/interfaceimpl/'.$classname.'.php');
                    }
                    break;
                case 'Class' :
                    if (file_exists(PLUGIN_PATH.'core/domain/'.$classname.'.php')) {
                        include_once(PLUGIN_PATH.'core/domain/'.$classname.'.php');
                    }
                    break;
                case 'Bean' :
                    if (file_exists(PLUGIN_PATH.'core/bean/'.$classname.'.php')) {
                        include_once(PLUGIN_PATH.'core/bean/'.$classname.'.php');
                    }
                    break;
        case 'Actions' :
      case 'Dao' :
      case 'DaoImpl' :
      case 'Services' :
        if (file_exists(PLUGIN_PATH.'core/'.strtolower($matches[1]).'/'.$classname.'.php')) {
          include_once(PLUGIN_PATH.'core/'.strtolower($matches[1]).'/'.$classname.'.php');
        }
      break;
      default :
        // On est dans un cas o? on a match? mais pas pr?vu le traitement...
      break;
    }
        }
    } else {
        $classfile = sprintf('%score/domain/%s.class.php', PLUGIN_PATH, str_replace('_', '-', $classname));
        if (!file_exists($classfile)) {
            $classfile = sprintf('%s../mycommon/core/domain/%s.class.php', PLUGIN_PATH, str_replace('_', '-', $classname));
        }
        if (file_exists($classfile)) {
            include_once($classfile);
        }
    }
}

/**
#######################################################################################
###  Ajout d'une entrée dans le menu d'administration.
#######################################################################################
**/
function aperd_v2_menu()
{
  $urlRoot = 'hj-v2-aperd/admin_manage.php';
  if (function_exists('add_menu_page')) {
    $uploadFiles = 'upload_files';
    $pluginName = 'APERD v2';
    add_menu_page($pluginName, $pluginName, $uploadFiles, $urlRoot, '', plugins_url('/hj-v2-aperd/web/rsc/img/icons/aperd.png'));
    if (function_exists('add_submenu_page')) {
      $arrUrlSubMenu = array(
        'administration' => 'Administratifs',
        'annee-scolaire' => 'Années Scolaires',
        'compodivision'  => 'Composition Divisions',
        'compte-rendu'   => 'Compte-Rendus',
        'questionnaire'  => 'Config. Questionnaire',
        'division'       => 'Divisions',
        'eleve'          => 'Élèves',
        'enseignant'     => 'Enseignants',
        'matiere'        => 'Matières',
        'parent'         => 'Parents',
        'parent-delegue' => 'Parents Délégués',
        '-'              => '-----------------',

        'data-question'  => 'Questionnaires',
        'schema-table'  => 'Adm : Schéma Base',
      );
      foreach ($arrUrlSubMenu as $key => $value) {
        $urlSubMenu = $urlRoot.'&amp;onglet='.$key;
        add_submenu_page($urlRoot, $value, $value, $uploadFiles, $urlSubMenu, $key);
      }
    }
  }
}
add_action('admin_menu', 'aperd_v2_menu');
/**
#######################################################################################
### Ajout d'une action Ajax
### Description: Entrance point for Ajax Interaction.
#######################################################################################
*/
add_action('wp_ajax_dealWithAjax', 'dealWithAjax_callback');
add_action('wp_ajax_nopriv_dealWithAjax', 'dealWithAjax_callback');
function dealWithAjax_callback()
{
    echo core\actions\AjaxActions::dealWithAjax();
    die();
}


/**
#######################################################################################
### Gestion des Exceptions
### Description: Met en forme les exceptions
#######################################################################################
*/
function exception_handler($Exception) {
  echo
    '<div class="card border-danger" style="max-width: 100%;margin-right: 15px;">'.
    '  <div class="card-header bg-danger text-white"><strong>'.$Exception->getMessage().'</strong></div>'.
    '  <div class="card-body text-danger">'.
    '    <p>Une erreur est survenue dans le fichier <strong>'.$Exception->getFile().
    '</strong> à la ligne <strong>'.$Exception->getLine().'</strong>.</p>'.
    '    <ul class="list-group">';

    $arrTraces = $Exception->getTrace();
    foreach ($arrTraces as $trace) {
        echo '<li class="list-group-item">Fichier <strong>'.$trace['file'].'</strong> ligne <em>'.$trace['line'].'</em> :<br>';
        if (is_array($trace['args'])) {
            echo $trace['function'].'()</li>';
        } else {
            echo $trace['class'].$trace['type'].$trace['function'].'('.implode(', ', $trace['args']).')</li>';
        }
    }

  echo
    '    </ul>'.
    '  </div>'.
    '  <div class="card-footer"></div>'.
    '</div>';
}
set_exception_handler('exception_handler');