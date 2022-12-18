<?php
namespace core\actions;

use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\LabelsInterface;
use core\interfaceimpl\UrlsInterface;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * LocalActions
 * @author Hugues
 * @since 1.22.12.07
 * @version 1.22.12.07
 */
class LocalActions implements ConstantsInterface, LabelsInterface, UrlsInterface
{
    /**
     * Class Constructor
     */
    public function __construct()
    { }
    
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
    public static function exportFile($data, $prefix)
    {
        $dirName = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $fileName = self::CST_EXPORT.'_'.strtolower($prefix).'_'.date('Ymd_His').'.csv';
        $dst = fopen($dirName.$fileName, 'w');
        fputs($dst, implode(self::CST_EOL, $data));
        fclose($dst);
        $fileName = '/wp-content/plugins/hj-v2-aperd/web/rsc/csv-files/'.$fileName;
        
        $objActions = new LocalActions();
        $msg = sprintf(self::MSG_SUCCESS_EXPORT, $fileName);
        return $objActions->getToastContentJson(self::NOTIF_SUCCESS, 'Succès', $msg);
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
}
