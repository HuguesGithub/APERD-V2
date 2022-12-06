<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageBean
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.05
 */
class WpPageBean extends UtilitiesBean
{
    protected $objWpPage;

    /**
     * @param string $post
     */
    public function __construct($post='')
    {
		/*
        if ($post=='') {
            $post = get_post();
        }
        $this->objWpPage = WpPage::convertElement($post);
		*/
    }

    /**
	 * @return mixed
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public static function getPageBean()
    {
        if (is_front_page()) {
            $returned = new WpPageHomeBean();
        } else {
            $uri = $_SERVER['REQUEST_URI'];
            $arrUri = explode('/', $uri);
            if (!isset($arrUri[1])) {
                $returned = new WpPageHomeBean();
            } elseif ($arrUri[1]==self::PAGE_ADMIN) {
                $returned = new WpPageAdminBean();
            } else {
                $returned = new WpPageHomeBean();
            }
        }
        return $returned;
    }
}
