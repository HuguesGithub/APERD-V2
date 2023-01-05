<?php
namespace core\actions;

use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\LabelsInterface;
use core\interfaceimpl\UrlsInterface;
use core\services\AdministrationServices;
use core\services\AdulteServices;
use core\services\AdulteDivisionServices;
use core\services\DivisionServices;
use core\services\EleveServices;
use core\services\EnseignantServices;
use core\services\EnseignantPrincipalServices;
use core\services\MatiereServices;
use core\services\MatiereEnseignantServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * LocalActions
 * @author Hugues
 * @since 1.22.12.07
 * @version v2.23.01.02
 */
class LocalActions implements ConstantsInterface, LabelsInterface, UrlsInterface
{
    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->objAdministrationServices = new AdministrationServices();
        $this->objAdulteServices         = new AdulteServices();
        $this->objAdulteDivisionServices = new AdulteDivisionServices();
        $this->objDivisionServices       = new DivisionServices();
        $this->objEleveServices          = new EleveServices();
        $this->objEnseignantServices     = new EnseignantServices();
        $this->objEnseignantPrincipalServices = new EnseignantPrincipalServices();
        $this->objMatiereServices        = new MatiereServices();
        $this->objMatiereEnseignantServices = new MatiereEnseignantServices();
    }
    
    /**
     * @return bool
     * @since 1.22.12.07
     * @version 1.22.12.07
     */
    public static function isAdmin()
    { return current_user_can('manage_options'); }

    /**
     * @since 1.22.12.07
     * @version 1.22.12.07
     */
    public function exportFile($data, $prefix)
    {
        $dirName = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $fileName = self::CST_EXPORT.'_'.strtolower($prefix).'_'.date('Ymd_His').'.csv';
        $dst = fopen($dirName.$fileName, 'w');
        fputs($dst, implode(self::CST_EOL, $data));
        fclose($dst);
        $fileName = '/wp-content/plugins/hj-v2-aperd/web/rsc/csv-files/'.$fileName;
        
        $msg = sprintf(self::MSG_SUCCESS_EXPORT, $fileName);
        return $this->getToastContentJson(self::NOTIF_SUCCESS, 'Succès', $msg);
    }
    
    /**
     * @since 1.22.12.09
     * @version 1.22.12.24
     */
    public function importFile()
    {
        $importType = $_POST['type'];
        $dirName    = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $fileName   = $dirName.'import_'.$this->importType.'.csv';
        if ($importType==$this->importType &&
            is_uploaded_file($_FILES['fileToImport']['tmp_name']) &&
            rename($_FILES['fileToImport']['tmp_name'], $fileName)) {
                return $this->dealWithImport($fileName);
            }
    }
    
    /**
     * @since 1.22.12.07
     * @version 1.22.12.07
     */
    public function getToastContentJson($type, $title, $msg)
    { return '{"toastContent": '.json_encode($this->getToastContent($type, $title, $msg)).'}'; }

    /**
     * @since 1.22.12.07
     * @version 1.22.12.07
     */
    public function getToastContent($type, $title, $msg)
    {
        $strContent  = '<div class="toast fade show bg-'.$type.'">';
        $strContent .= '  <div class="toast-header">';
        $strContent .= '    <i class="fa-solid fa-exclamation-circle mr-2"></i>';
        $strContent .= '    <strong class="me-auto">'.$title.'</strong>';
        $strContent .= '  </div>';
        $strContent .= '  <div class="toast-body">'.$msg.'</div>';
        $strContent .= '</div>';
        return $strContent;
    }
    
    public function getDismissableButton($notif, $msg)
    {
        $strContent  = '<div class="alert alert-'.$notif.' alert-dismissible fade show" role="alert">';
        $strContent .= $msg.'<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $strContent .= '<span aria-hidden="true">×</span></button></div>';
        return $strContent;
    }
    
    /**
     * @param string $srcFile
     * @return string
     * @since v2.22.12.24
     * @version v2.22.12.24
     */
    public function dealWithImport($srcFile)
    {
        $notif = '';
        $msg = '';
        $msgError = '';
        
        $fileContent = file_get_contents($srcFile);
        $arrContent  = explode(self::CST_EOL, $fileContent);
        $blnOk = $this->controlerDonneesImport($arrContent, $notif, $msg, $msgError);
        
        if ($blnOk) {
            $notif = self::NOTIF_SUCCESS;
            $msg   = self::MSG_SUCCESS_IMPORT;
            $theList = '';
            $jsonAlertBlock = json_encode($this->getDismissableButton($notif, $msg));
            return '{"the-list": '.json_encode($theList).',"alertBlock": '.$jsonAlertBlock.'}';
        } else {
            return '{"alertBlock": '.json_encode($this->getDismissableButton($notif, $msgError)).'}';
        }
    }
    
    /**
     * Vérifie la validité du fichier importé.
     * @param array $arrContent
     * @param string $notif
     * @param string $msg
     * @param string $msgError
     * @return boolean
     * @since v2.22.12.18
     * @version v2.22.12.18
     */
    public function controlerDonneesImport($arrContent, &$notif, &$msg, &$msgError)
    {
        $headerRow = array_shift($arrContent);
        $blnOk     = $this->obj->controlerEntete($headerRow, $notif, $msgError);
        
        if ($blnOk) {
            $rkRow = 2;
            while (!empty($arrContent) && $blnOk) {
                $rowContent = array_shift($arrContent);
                $blnOk  = $this->obj->controlerImportRow($rowContent, $notif, $msg);
                if (!$blnOk) {
                    $msgError = 'Ligne '.$rkRow.' : '.$msg;
                }
                $rkRow++;
            }
        }
        return $blnOk;
    }
    
    /**
     * @param string $filters
     * @return string[]
     * @since v2.22.12.28
     * @version v2.22.12.28
     */
    public function getActiveFilters($filters)
    {
        // Si plusieurs filtres actifs, on doit les séparer.
        $arrFilters = explode(',', $filters);
        $arrActiveFilters = array();
        
        while (!empty($arrFilters)) {
            $filter = array_shift($arrFilters);
            // On récupère le type de filtre et sa valeur.
            list($key, $value) = explode('=', $filter);
            
            if ($key=='filter-adherent') {
                ///////////////////////////////////////////
                // Filtre sur Adherent
                if ($value=='oui') {
                    $adh = 1;
                } elseif ($value=='non') {
                    $adh = 0;
                } else {
                    $adh = '%';
                }
                $arrActiveFilters[self::FIELD_ADHERENT] = $adh;
            } elseif ($key=='filter-delegue') {
                ///////////////////////////////////////////
                // Filtre sur Délégué
                if ($value=='oui') {
                    $delegue = 1;
                } elseif ($value=='non') {
                    $delegue = 0;
                } else {
                    $delegue = '%';
                }
                $arrActiveFilters[self::FIELD_DELEGUE] = $delegue;
            } elseif ($key=='filter-division') {
                ///////////////////////////////////////////
                // Filtre sur Division
                $arrActiveFilters[self::FIELD_DIVISIONID] = $value;
            }
            ///////////////////////////////////////////
        }
        return $arrActiveFilters;
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
                $returned = $this->getCsvExport();
                break;
            case self::AJAX_IMPORT_FILE:
                $returned = $this->importFile();
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