<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminParametreBean
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class WpPageAdminParametreBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_PARAMETRES;
        $this->titreOnglet = self::LABEL_PARAMETRES;
        // Initialisation des données du bloc de présentation
        $this->blnBoutonCreation = false;
        $this->hasPresentation = false;
        $this->strPresentationTitle = self::LABEL_PARAMETRES;
        $this->strPresentationContent = '';
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = false;
        // Initialisation d'un éventuel objet dédié.
        // Initialisation de la pagination
        // Initialisation des filtres
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Vérification de la soumission d'un formulaire
        if ($this->curUser->hasAdminRights() && $this->postAction!='') {
            $this->dealWithForm();
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Construction du Breadcrumbs
        $this->buildBreadCrumbs();
        /////////////////////////////////////////
    }
    
    /**
     * En cas de formulaire, on le traite. A priori, Création ou édition pour l'heure
     * @since v2.22.12.21
     * @version v2.22.12.21
     */
    public function dealWithForm()
    {
        /*
        $strNotification = '';
        $strMessage = '';
        
        /////////////////////////////////////////
        // Un formulaire est soumis.
        // On récupère les données qu'on affecte à l'objet
        $this->objAdministratif->setField(self::FIELD_GENRE, $this->initVar(self::FIELD_GENRE));
        $this->objAdministratif->setField(self::FIELD_NOMTITULAIRE, $this->initVar(self::FIELD_NOMTITULAIRE));
        $this->objAdministratif->setField(self::FIELD_LABELPOSTE, $this->initVar(self::FIELD_LABELPOSTE));
        
        // Si le contrôle des données est ok
        if ($this->objAdministratif->controlerDonnees($strNotification, $strMessage)) {
            // Si l'id n'est pas défini
            if ($this->objAdministratif->getField(self::FIELD_ID)=='') {
                // On insère l'objet
                $this->objAdministratif->insert();
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_CREATE);
            } else {
                // On met à jour l'objet
                $this->objAdministratif->update();
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_EDIT);
            }
        } else {
            // Le contrôle de données n'est pas bon. Afficher l'erreur.
            $this->strNotifications = $this->getAlertContent($strNotification, $strMessage);
        }
        /////////////////////////////////////////
         */
    }
    
    public function getOngletContent()
    {
        $firstCol  = $this->getFirstCol();
		
        $secondCol = $this->getCardPdf();
		
        $thirdCol  = '3';
        $fourthCol = $this->getCardNavigation();
        
        $urlTemplate = self::WEB_PPFS_CONTENT_CARD_GRID;
        $attributes = array(
            // Identifiant de la page
            $this->slugOnglet,
            // La colonne de gauche
            $firstCol,
            // 
            $secondCol,
            //
            $thirdCol,
            //
            $fourthCol,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
	
	public function getMeta($metaKey)
	{
		return '';
	}
	
	private function getCardPdf()
	{
        $cardTitle = 'Données PDF';
		
		$cardContent = '';
		$inputAttributes = array(
			self::ATTR_CLASS => 'form-control form-control-sm',
			self::ATTR_TYPE => 'text',
		);
		
		$pdfDatas = array(
			'titrePdf' => 'Titre du Pdf',	// "Association de Parents d'Élèves du Collège Raoul Dufy"
			'titreAnneeScolaire' => 'Année Scolaire',	// "ANNÉE SCOLAIRE %s"
			'titreHeaderLine1' => 'Titre Header 1',	// "Compte-rendu du conseil de classe du %s trimestre"
			'titreHeaderLine2' => 'Titre Header 2',	// "Classe de : %s. Effectif de la classe : %s élèves"
			'titreHeaderLine3' => 'Titre Header 3',	// "Le conseil de classe s'est tenu le %s sous la présidence de %s, en présence de "%s, des autres professeurs de la classe, %s %s"
			'conclusionLine1' => 'Conclusion Réunions',	// "Réunions mensuelles : L'association des Parents d'Élèves se réunit un mercredi par mois (hors vacances scolaires). Vous pouvez également découvrir la vie du collège et les actions de l'association sur son site internet."
			'conclusionLine2' => 'Conclusion Rédaction',	// "Compte rendu fait le %s par %s, sous %s responsabilité."
		);
		
		foreach ($pdfDatas as $key => $value) {
			$dataAttributes = array_merge(
				$inputAttributes,
				array(
					self::FIELD_ID => $key,
					self::ATTR_NAME => $key,
					self::ATTR_VALUE => $this->getMeta($key),
				),
			);
			$cardInput = $this->getBalise(self::TAG_INPUT, '', $dataAttributes);
			$cardLabel = $this->getBalise(self::TAG_LABEL, $value, array('for'=>$key));
			$cardContent .= $this->getDiv($cardInput.$cardLabel, array(self::ATTR_CLASS=>'form-floating mb-3'));
		}
       
        $urlTemplate = self::WEB_PPFC_CARD;
        $attributes = array(
            $cardTitle,
            $cardContent,
        );
        return $this->getRender($urlTemplate, $attributes);
	
	}

	private function getFirstCol()
	{
		$strFirstColContent = '';
		
		$arrFirstCol = array(
			'anneeScolaire' => 'Année Scolaire',
			'email' => 'Email',
			'phone' => 'Téléphone',
		);
		
		foreach ($arrFirstCol as $key => $value) {
			$inputAttributes = array(
				self::FIELD_ID => $key,
				self::ATTR_NAME => $key,
				self::ATTR_CLASS => 'form-control form-control-sm',
				self::ATTR_VALUE => $this->getMeta($key),
				self::ATTR_TYPE => 'text',
			);
			$cardInput = $this->getBalise(self::TAG_INPUT, '', $inputAttributes);
			$cardLabel = $this->getBalise(self::TAG_LABEL, $value, array('for'=>$key));
			$cardContent = $this->getDiv($cardInput.$cardLabel, array(self::ATTR_CLASS=>'form-floating mb-3'));
		   
			$urlTemplate = self::WEB_PPFC_CARD;
			$attributes = array(
				$value,
				$cardContent,
			);
			$strFirstColContent .= $this->getRender($urlTemplate, $attributes);
		}
		
		return $strFirstColContent;
	}
	    
    private function getCardNavigation()
    {
        $cardTitle = 'Arborescence du site';
        
       
        $urlTemplate = self::WEB_PPFC_CARD;
        $attributes = array(
            $cardTitle,
            '',
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}