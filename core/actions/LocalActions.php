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
        return $objActions->getToastContentJson('success', 'Succès', sprintf(self::MSG_SUCCESS_EXPORT, $fileName));
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
}
