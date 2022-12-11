<?php
namespace core\actions;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AjaxActions
 * @author Hugues
 * @since 1.22.12.07
 * @version 1.22.12.07
 */
class AjaxActions extends LocalActions
{

    /**
     * Gère les actions Ajax
     * @since 1.22.12.07
     * @version 1.22.12.07
     */
    public static function dealWithAjax()
    {
        switch ($_POST[self::AJAX_ACTION]) {
            case 'importFile':
                $returned = self::dealWithImportFile();
                break;
            case 'csvExport':
                $returned = self::dealWithCsvExport();
                break;
            default :
                $saisie = stripslashes($_POST[self::AJAX_ACTION]);
                $returned  = 'Erreur dans AjaxActions le $_POST['.self::AJAX_ACTION.'] : '.$saisie.'<br>';
                break;
        }
        return $returned;
    }
  
    /**
     * Réoriente vers les exports CSV dédiés
     * @since 1.22.12.08
     * @version 1.22.12.08
     */
    public static function dealWithCsvExport()
    {
        switch ($_POST[self::ATTR_TYPE]) {
            case self::ONGLET_ADMINISTRATIFS :
                $returned = AdministrationActions::getCsvExport();
                break;
            case self::ONGLET_DIVISIONS :
                $returned = DivisionActions::getCsvExport();
                break;
            case self::ONGLET_PARENTS :
                $returned = AdulteActions::getCsvExport();
                break;
            default :
                $obj = new AjaxActions();
                $saisie = stripslashes($_POST[self::ATTR_TYPE]);
                $msg = vsprintf(self::MSG_ERREUR_AJAX_DATA, array('csvExport', $saisie, self::ATTR_TYPE));
                $returned = $obj->getToastContentJson(self::NOTIF_DANGER, 'Echec', $msg);
                break;
        }
        return $returned;
    }
  
    /**
     * Réoriente vers les imports dédiés
     * @since 1.22.12.09
     * @version 1.22.12.09
     */
    public static function dealWithImportFile()
    {
        switch ($_POST['importType']) {
            case self::ONGLET_ADMINISTRATIFS :
                $returned = AdministrationActions::importFile();
                break;
            case self::ONGLET_DIVISIONS :
                $returned = DivisionActions::importFile();
                break;
            case self::ONGLET_PARENTS :
                $returned = AdulteActions::importFile();
                break;
            default :
                $obj = new AjaxActions();
                $saisie = stripslashes($_POST[self::ATTR_TYPE]);
                $msg = vsprintf(self::MSG_ERREUR_AJAX_DATA, array('importFile', $saisie, 'importType'));
                $returned = $obj->getToastContentJson(self::NOTIF_DANGER, 'Echec', $msg);
                break;
        }
        return $returned;
    }

}
