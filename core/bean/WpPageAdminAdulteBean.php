<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminAdulteBean
 * @author Hugues
 * @since 1.22.12.08
 * @version 1.22.12.08
 */
class WpPageAdminAdulteBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_PARENTS;
        $this->titreOnglet = self::LABEL_PARENTS;
        
        // Initialisation des templates
        $this->urlOngletContentTemplate = self::WEB_PPFC_PRES_ADULTE;
        
        $strNotification = '';
        $strMessage = '';
        
        $id = $this->initVar(self::ATTR_ID);
        $this->objAdulte = $this->objAdulteServices->getAdulteById($id);
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        $this->filtreAdherent = $this->initVar('filter-adherent', 'all');
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        
        /////////////////////////////////////////
        // Vérification de la soumission d'un formulaire
        $postAction = $this->initVar(self::CST_POST_ACTION);
        if ($postAction!='') {
            // Un formulaire est soumis.
            // On récupère les données qu'on affecte à l'objet
            $this->objAdulte->setField(self::FIELD_NOMADULTE, $this->initVar(self::FIELD_NOMADULTE));
            $this->objAdulte->setField(self::FIELD_PRENOMADULTE, $this->initVar(self::FIELD_PRENOMADULTE));
            $this->objAdulte->setField(self::FIELD_MAILADULTE, $this->initVar(self::FIELD_MAILADULTE));
            $this->objAdulte->setField(self::FIELD_ADHERENT, $this->initVar(self::FIELD_ADHERENT, 0));
            $strPhoneAdulte = str_replace(' ', '', $this->initVar(self::FIELD_PHONEADULTE));
            $this->objAdulte->setField(self::FIELD_PHONEADULTE, $strPhoneAdulte);

            // Si le contrôle des données est ok
            if ($this->objAdulte->controlerDonnees($strNotification, $strMessage)) {
                // Si l'id n'est pas défini
                if ($id=='') {
                    // On insère l'objet
                    $this->objAdulte->insert();
                } else {
                    // On met à jour l'objet
                    $this->objAdulte->update();
                }
            } else {
                // TODO : Le contrôle de données n'est pas bon. Afficher l'erreur.
            }
            // TODO : de manière générale, ce serait bien d'afficher le résultat de l'opération.
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Construction du Breadcrumbs
        if ($this->slugSubOnglet=='') {
            $href = '#';
            $buttonAttributes = array(self::ATTR_CLASS=>self::CSS_BTN_DARK.' '.self::CSS_DISABLED);
        } else {
            $urlElements = array(self::CST_ONGLET=>$this->slugOnglet, self::CST_SUBONGLET=>'');
            $href = $this->getUrl($urlElements);
            $buttonAttributes = array(self::ATTR_CLASS=>self::CSS_BTN_DARK);
        }
        $buttonContent = $this->getLink($this->titreOnglet, $href, self::CST_TEXT_WHITE);
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
    }
    
    /**
     * @since v2.22.12.12
     * @version v2.22.12.12
     */
    public static function getStaticWpPageBean($slugSubContent)
    {
        if ($slugSubContent == self::CST_PARENTS_DELEGUES) {
            $objBean = new WpPageAdminAdulteDivisionBean();
        } else {
            $objBean = new WpPageAdminAdulteBean();
        }
        return $objBean;
    }
      
    /**
     * Construction d'une liste d'éléments dont les identifiants sont passés en paramètre.
     * Si $blnDelete est à true, on en profite pour effacer l'élément.
     * @param int|string $ids
     * @param boolean $blnDelete
     * @return string
     * @since v2.22.12.18
     * @version v2.22.12.18
     */
    public function getListElements($ids, $blnDelete=false)
    {
        $strElements = '';
        // On peut avoir une liste d'id en cas de suppression multiple.
        foreach (explode(',', $ids) as $id) {
            $objAdulte = $this->objAdulteServices->getAdulteById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objAdulte->getName());
            if ($blnDelete) {
                $objAdulte->delete();
            }
        }
        return $strElements;
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.18
     */
    public function getSpecificHeaderRow()
    {
        $cellCenter = array(self::ATTR_CLASS=>'text-center');
        $trContent  = $this->getTh(self::LABEL_NOMPRENOM);
        $trContent .= $this->getTh(self::LABEL_MAIL);
        return $trContent.$this->getTh(self::LABEL_ADHERENT, $cellCenter);
    }
      
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_PARENTS;
        /////////////////////////////////////////
        // On va prendre en compte les éventuels filtres
        $attributes = array();
          
        if ($this->filtreAdherent=='oui') {
            $attributes[self::FIELD_ADHERENT] = 1;
        } elseif ($this->filtreAdherent=='non') {
            $attributes[self::FIELD_ADHERENT] = 0;
        }
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objAdulteServices->getAdultesWithFilters($attributes);
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objAdulte->getBean()->getForm($baseUrl);
    }
    
    /**
     * Retourne les éléments visibles dans la dropdown de Download
     * @return string
     * @since v2.22.12.18
     * @version v2.22.12.18
     */
    public function getDownloadUls()
    {
        $ulContent  = $this->getLiDropdown('Sélection', 'dropdownDownload');
        $ulContent .= $this->getLiDropdown('Filtre', 'dropdownDownload');
        return $ulContent.$this->getLiDropdown('Tous', 'dropdownDownload');
    }
    
    /**
     * Retourne le filtre spécifique à l'écran.
     * TODO : A implémenter plus proprement
     * @return string
     * @param v2.22.12.18
     * @since v2.22.12.18
     */
    public function getTrFiltres()
    {
        /////////////////////////////////////////
        // On va mettre en place la ligne de Filtre
        $trContent = '';
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::CST_NBSP);
        $trContent .= $this->getTh(self::CST_NBSP);
        // Filtre Adhérent
        $trContent .= '<th class="text-center"><div role="group" class="btn-group">';
        $trContent .= '<button type="button" class="btn btn-default btn-sm btn-light dropdown-toggle" ';
        $trContent .= 'data-bs-toggle="dropdown" aria-expanded="false">';
        if ($this->filtreAdherent=='oui') {
            $trContent .= 'Oui';
        } elseif ($this->filtreAdherent=='non') {
            $trContent .= 'Non';
        } else {
            $trContent .= 'Tous';
        }
        $trContent .= '</button>';
        $trContent .= '<ul class="dropdown-menu" ';
        $trContent .= 'style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(93.6px, 427.2px, 0px);" ';
        $trContent .= 'data-popper-placement="bottom-start">';
        $trContent .= '<li><a href="/admin?onglet=parents&amp;filter-adherent=oui" ';
        $trContent .= 'class="dropdown-item text-white">Oui</a></li>';
        $trContent .= '<li><a href="/admin?onglet=parents&amp;filter-adherent=non" ';
        $trContent .= 'class="dropdown-item text-white">Non</a></li>';
        $trContent .= '<li><a href="/admin?onglet=parents&amp;filter-adherent=all" ';
        $trContent .= 'class="dropdown-item text-white">Tous</a></li>';
        $trContent .= '</ul></div></th>';
        
        
        if ($this->curUser->hasEditorRights()) {
            $trContent .= '<th class="column-actions"><div class="row-actions text-center">';
            $trContent .= '<a href="/admin?onglet=parents" class="" title="Nettoyer le filtre">';
            $trContent .= '<button type="button" class="btn btn-default btn-sm"><i class="fa-solid ';
            $trContent .= 'fa-filter-circle-xmark"></i></button></a>';
            $trContent .= '</div></th>';
        }
        return $this->getBalise(self::TAG_TR, $trContent);
    }
}
