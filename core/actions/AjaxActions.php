<?php
namespace core\actions;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AjaxActions
 * @author Hugues
 * @since 1.22.12.07
 * @version 1.22.12.18
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
        $ajaxAction = $_POST[self::AJAX_ACTION];
        switch ($ajaxAction) {
            case self::AJAX_CSV_EXPORT:
                $returned = self::dealWithCsvExport();
                break;
            case self::AJAX_IMPORT_FILE:
                $returned = self::dealWithImportFile();
                break;
            default :
                $saisie = stripslashes($ajaxAction);
                $returned  = 'Erreur dans AjaxActions le $_POST['.self::AJAX_ACTION.'] : '.$saisie.'<br>';
                break;
        }
        return $returned;
    }
  
    /**
     * Réoriente vers les exports CSV dédiés
     * @since 1.22.12.08
     * @version 1.22.12.18
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
            case self::ONGLET_ELEVES :
                $returned = EleveActions::getCsvExport();
                break;
            case self::ONGLET_MATIERES :
                $returned = MatiereActions::getCsvExport();
                break;
            case self::ONGLET_PARENTS :
                $returned = AdulteActions::getCsvExport();
                break;
            case self::SUBONGLET_PARENTS_DELEGUES :
                $returned = AdulteDivisionActions::getCsvExport();
                break;
            default :
                $obj = new AjaxActions();
                $saisie = stripslashes($_POST[self::ATTR_TYPE]);
                $msg = vsprintf(self::MSG_ERREUR_AJAX_DATA, array(self::AJAX_CSV_EXPORT, $saisie, self::ATTR_TYPE));
                $returned = $obj->getToastContentJson(self::NOTIF_DANGER, 'Echec', $msg);
                break;
        }
        return $returned;
    }
  
    /**
     * Réoriente vers les imports dédiés
     * @since 1.22.12.09
     * @version 1.22.12.18
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
            case self::ONGLET_ELEVES :
                $returned = EleveActions::importFile();
                break;
            case self::ONGLET_MATIERES :
                $returned = MatiereActions::importFile();
                break;
            case self::ONGLET_PARENTS :
                $returned = AdulteActions::importFile();
                break;
            case self::SUBONGLET_PARENTS_DELEGUES :
                $returned = AdulteDivisionActions::importFile();
                break;
            default :
                $obj = new AjaxActions();
                $saisie = stripslashes($_POST[self::ATTR_TYPE]);
                $msg = vsprintf(self::MSG_ERREUR_AJAX_DATA, array(self::AJAX_IMPORT_FILE, $saisie, 'importType'));
                $returned = $obj->getToastContentJson(self::NOTIF_DANGER, 'Echec', $msg);
                break;
        }
        return $returned;
    }

}
