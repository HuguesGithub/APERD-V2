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
            case self::ONGLET_ENSEIGNANTS :
                $obj = new EnseignantActions();
                break;
            case self::ONGLET_MATIERES :
                $obj = new MatiereActions();
                break;
            case self::ONGLET_PARENTS :
                $obj = new AdulteActions();
                break;
            case self::CST_MATIERES_ENSEIGNANTS :
                $obj = new MatiereEnseignantActions();
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

}
