<?php
namespace core\actions;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AjaxActions
 * @author Hugues
 * @since 1.22.12.07
 * @version 1.22.12.24
 */
class AjaxActions extends LocalActions
{

    /**
     * Gère les actions Ajax
     * @since 1.22.12.07
     * @version 1.22.12.24
     */
    public static function dealWithAjax()
    {
        switch ($_POST[self::ATTR_TYPE]) {
            case self::ONGLET_ADMINISTRATIFS :
                $obj = new AdministrationActions();
                break;
            case self::ONGLET_DIVISIONS :
                $obj = new DivisionActions();
                break;
            case self::ONGLET_ELEVES :
                $obj = new EleveActions();
                break;
            case self::ONGLET_MATIERES :
                $obj = new MatiereActions();
                break;
            case self::ONGLET_PARENTS :
                $obj = new AdulteActions();
                break;
            case self::SUBONGLET_PARENTS_DELEGUES :
                $obj = new AdulteDivisionActions();
                break;
            default :
                $obj = new AjaxActions();
                $saisie = stripslashes($_POST[self::ATTR_TYPE]);
                $msg = vsprintf(self::MSG_ERREUR_AJAX_DATA, array('dealWithAjax()', $saisie, self::ATTR_TYPE));
                return $obj->getToastContentJson(self::NOTIF_DANGER, 'Echec', $msg);
                break;
        }
        return $obj->getDealWithAjax();
    }

    /**
     * @return string
     * @since v2.22.12.24
     * @version v2.22.12.24
     */
    public function getDealWithAjax()
    {
        switch ($_POST[self::AJAX_ACTION]) {
            case self::AJAX_CSV_EXPORT:
                $returned = $this->dealWithCsvExport();
                break;
            case self::AJAX_IMPORT_FILE:
                $returned = $this->dealWithImportFile();
                break;
            default :
                $saisie = stripslashes($_POST[self::AJAX_ACTION]);
                $msg = vsprintf(self::MSG_ERREUR_AJAX_DATA, array('getDealWithAjax()', $saisie, self::AJAX_ACTION));
                $returned = $this->getToastContentJson(self::NOTIF_DANGER, 'Echec', $msg);
                break;
        }
        return $returned;
    }

}
