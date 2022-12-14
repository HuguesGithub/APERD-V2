<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminAdulteDivisionBean
 * @author Hugues
 * @since 1.22.12.12
 * @version 1.22.12.12
 */
class WpPageAdminAdulteDivisionBean extends WpPageAdminAdulteBean
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_PARENTS;
        $this->slugSubOnglet = self::SUBONGLET_PARENTS_DELEGUES;
        $this->titreOnglet = self::LABEL_PARENTS_DELEGUES;
        
        $strNotification = '';
        $strMessage = '';
        
        $id = $this->initVar(self::ATTR_ID);
        $this->objAdulteDivision = $this->objAdulteDivisionServices->getAdulteDivisionById($id);
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
		$this->filtreAdherent = $this->initVar('filter-adherent', 'all');
		
        /////////////////////////////////////////
        // Vérification de la soumission d'un formulaire
        $postAction = $this->initVar(self::CST_POST_ACTION);
        if ($postAction!='') {
            // Un formulaire est soumis.
            // On récupère les données qu'on affecte à l'objet
            $this->objAdulteDivision->setField(self::FIELD_ADULTEID, $this->initVar(self::FIELD_ADULTEID));
            $this->objAdulteDivision->setField(self::FIELD_DIVISIONID, $this->initVar(self::FIELD_DIVISIONID));
            $this->objAdulteDivision->setField(self::FIELD_DELEGUE, 1);
            // Si le contrôle des données est ok
            if ($this->objAdulteDivision->controlerDonnees($strNotification, $strMessage)) {
                // Si l'id n'est pas défini
                if ($id=='') {
                    // On insère l'objet
                    $this->objAdulteDivision->insert();
                } else {
                    // On met à jour l'objet
                    $this->objAdulteDivision->update();
                }
            } else {
                // TODO : Le contrôle de données n'est pas bon. Afficher l'erreur.
            }
            // TODO : de manière générale, ce serait bien d'afficher le résultat de l'opération.
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Construction du Breadcrumbs
        $buttonContent = $this->getLink($this->titreOnglet, '#', self::CST_TEXT_WHITE);
        $buttonAttributes = array(self::ATTR_CLASS=>self::CSS_BTN_DARK.' '.self::CSS_DISABLED);
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
    }

    /**
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getOngletContent()
    {
		$this->blnBoutonCreation = false;
        $this->urlOngletContentTemplate = '';
        return $this->getCommonOngletContent();
    }

    /**
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getDeleteContent()
    {
		return '';
        $strElements = '';
        
        // On peut avoir une liste d'id en cas de suppression multiple.
        $ids = $this->initVar(self::ATTR_ID);
        foreach (explode(',', $ids) as $id) {
            $objAdulte = $this->objAdulteServices->getAdulteById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objAdulte->getName());
        }
        
        $urlElements = array(
            self::ATTR_ID => $ids,
            self::CST_CONFIRM => 1,
        );
        $urlTemplate = self::WEB_PPFC_DEL_ADM;
        $attributes = array(
            // Liste des éléments supprimés
            $strElements,
            // Url de confirmation
            $this->getUrl($urlElements),
            // URl d'annulation
            $this->getUrl(array(self::CST_SUBONGLET=>'')),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getDeletedContent()
    {
		return '';
        $strElements = '';
        
        // On peut avoir une liste d'id en cas de suppression multiple.
        $ids = $this->initVar(self::ATTR_ID);
        foreach (explode(',', $ids) as $id) {
            $objAdulte = $this->objAdulteServices->getAdulteById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objAdulte->getName());
            $objAdulte->delete();
        }
        
        $urlTemplate = self::WEB_PPFC_CONF_DEL;
        $attributes = array(
            // Liste des éléments supprimés
            $strElements,
            // URl d'annulation
            $this->getUrl(array(self::CST_SUBONGLET=>'')),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.0
     */
    public function getEditContent()
    {
		return '';
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objAdulte->getBean()->getForm($baseUrl);
    }
    
    /**
     * @param boolean $blnHasEditorRights
     * @return string
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function getListHeaderRow($blnHasEditorRights=false)
    {
		$cellCenter = array(self::ATTR_CLASS=>'text-center');
        // Selon qu'on a les droit d'administration ou non, on n'aura pas autant de colonnes à afficher.
        $trContent = '';
        if ($blnHasEditorRights) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::LABEL_NOMPRENOM);
        $trContent .= $this->getTh(self::LABEL_DIVISIONS);
        $trContent .= $this->getTh(self::LABEL_MAIL, $cellCenter);
        $trContent .= $this->getTh(self::LABEL_ADHERENT, $cellCenter);
        if ($blnHasEditorRights) {
            $trContent .= $this->getTh(self::LABEL_ACTIONS, $cellCenter);
        }
        return $this->getBalise(self::TAG_TR, $trContent);
    }
    
    /**
     * @return string
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function getListContent($blnHasEditorRights=false)
    {
        $this->attrDescribeList = self::LABEL_LIST_PARENTS_DELEGUES;
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $attributes = array(
            self::FIELD_DELEGUE => 1,
        );
        
        if ($this->filtreAdherent=='oui') {
            $attributes[self::FIELD_ADHERENT] = 1;
        } elseif ($this->filtreAdherent=='non') {
            $attributes[self::FIELD_ADHERENT] = 0;
        }
        
        $objItems = $this->objAdulteDivisionServices->getAdulteDivisionsWithFilters($attributes, self::FIELD_LABELDIVISION);
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems, $blnHasEditorRights);
    }
	
    
	public function getTrFiltres($blnHasEditorRights)
	{
        /////////////////////////////////////////
		// On va mettre en place la ligne de Filtre
		$trContent = '';
        if ($blnHasEditorRights) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::CST_NBSP);
        $trContent .= $this->getTh(self::CST_NBSP);
        $trContent .= $this->getTh(self::CST_NBSP);
		// Filtre Adhérent
        $trContent .= '<th class="text-center"><div role="group" class="btn-group">';
		$trContent .= '<button type="button" class="btn btn-default btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
		if ($this->filtreAdherent=='oui') {
			$trContent .= 'Oui';
		} elseif ($this->filtreAdherent=='non') {
			$trContent .= 'Non';
		} else {
			$trContent .= 'Tous';
		}
		$trContent .= '</button>';
		$trContent .= '<ul class="dropdown-menu" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(93.6px, 427.2px, 0px);" data-popper-placement="bottom-start">';
		$trContent .= '<li><a href="/admin?onglet=parents&subOnglet=parentDelegue&amp;filter-adherent=oui" class="dropdown-item text-white">Oui</a></li>';
		$trContent .= '<li><a href="/admin?onglet=parents&subOnglet=parentDelegue&amp;filter-adherent=non" class="dropdown-item text-white">Non</a></li>';
		$trContent .= '<li><a href="/admin?onglet=parents&subOnglet=parentDelegue&amp;filter-adherent=all" class="dropdown-item text-white">Tous</a></li>';
		$trContent .= '</ul></div></th>';

        if ($blnHasEditorRights) {
			$trContent .= '<th class="column-actions"><div class="row-actions text-center">';
			$trContent .= '<a href="/admin?onglet=parents&subOnglet=parentDelegue" class="" title="Nettoyer le filtre"><button type="button" class="btn btn-default btn-sm"><i class="fa-solid fa-filter-circle-xmark"></i></button></a>';
			$trContent .= '</div></th>';
        }
		$filterRow = $this->getBalise(self::TAG_TR, $trContent);

        /////////////////////////////////////////
		// On va mettre en place la ligne de Création / Edition
		$editRow = '';
        if ($blnHasEditorRights) {
			$trContent = '<form method="post" action="/admin/?onglet=parents&subOnglet=parentDelegue">';
			
			/////////////////////////////////////////
			// La case vide de la case à cocher
            $trContent .= $this->getTh(self::CST_NBSP);
			
			/////////////////////////////////////////
			// La liste des parents inscrits
			$strOptions = $this->getBalise(self::TAG_OPTION, '');
			$objsAdulte = $this->objAdulteServices->getAdultesWithFilters();
			while (!empty($objsAdulte)) {
				$objAdulte = array_shift($objsAdulte);
				$attr = array(
					self::ATTR_VALUE => $objAdulte->getField(self::FIELD_ID),
				);
				if ($objAdulte->getField(self::FIELD_ID)==$this->objAdulteDivision->getField(self::FIELD_ADULTEID)) {
					$attr = array(self::ATTR_SELECTED=>self::ATTR_SELECTED);
				}
				$strOptions .= $this->getBalise(self::TAG_OPTION, $objAdulte->getName(), $attr);
			}
			$selAttributes = array(
				self::ATTR_NAME => self::FIELD_ADULTEID,
				self::ATTR_CLASS => 'form-control',
			);
			$strSelect = $this->getBalise(self::TAG_SELECT, $strOptions, $selAttributes);
            $trContent .= $this->getTh($strSelect);

			/////////////////////////////////////////
			// La liste des divisions
			$strOptions = $this->getBalise(self::TAG_OPTION, '');
			$objsDivision = $this->objDivisionServices->getDivisionsWithFilters();
			while (!empty($objsDivision)) {
				$objDivision = array_shift($objsDivision);
				$attr = array(
					self::ATTR_VALUE => $objDivision->getField(self::FIELD_ID),
				);
				if ($objDivision->getField(self::FIELD_ID)==$this->objAdulteDivision->getField(self::FIELD_DIVISIONID)) {
					$attr = array(self::ATTR_SELECTED=>self::ATTR_SELECTED);
				}
				$strOptions .= $this->getBalise(self::TAG_OPTION, $objDivision->getField(self::FIELD_LABELDIVISION), $attr);
			}
			$selAttributes = array(
				self::ATTR_NAME => self::FIELD_DIVISIONID,
				self::ATTR_CLASS => 'form-control',
			);
			$strSelect = $this->getBalise(self::TAG_SELECT, $strOptions, $selAttributes);
            $trContent .= $this->getTh($strSelect);
			
			/////////////////////////////////////////
			// La case vide de l'email
            $trContent .= $this->getTh(self::CST_NBSP);
			// La case vide de adhérent
            $trContent .= $this->getTh(self::CST_NBSP);
			// Le bouton de validation
			$trContent .= '<th class="column-actions"><div class="row-actions text-center">';
			$trContent .= '<input type="hidden" name="postAction" value="edit"/>';
			$trContent .= '<input type="hidden" name="id" value="'.$this->objAdulteDivision->getField(self::FIELD_ID).'"/>';
			$trContent .= '<button type="submit" class="btn btn-default btn-sm btn-primary"><i class="fa-solid fa-pen-to-square"></i></button>';
			$trContent .= '</div></th></form>';
			$editRow = $this->getBalise(self::TAG_TR, $trContent);
        }
		
		return $filterRow.$editRow;
	}
}