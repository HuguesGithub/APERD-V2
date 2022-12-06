<?php
namespace core\interfaceimpl;
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.05
 */
interface UrlsInterface
{
    // Directories
    const WEB_PAGES_ADMIN  = 'web/pages/admin/';
    const WEB_PAGES_PUBLIC = 'web/pages/public/';
    const WEB_PA_FRAGMENTS = self::WEB_PAGES_ADMIN.'fragments/';
    const WEB_PP_FRAGMENTS = self::WEB_PAGES_PUBLIC.'fragments/';
    const WEB_PPF_ARTICLE  = self::WEB_PP_FRAGMENTS.'article/';
    const WEB_PAF_FORM     = self::WEB_PA_FRAGMENTS.'form/';
    const WEB_PPF_FORM     = self::WEB_PP_FRAGMENTS.'form/';
    const WEB_PPF_SECTION  = self::WEB_PP_FRAGMENTS.'section/';
    const WEB_PAF_TR       = self::WEB_PA_FRAGMENTS.'tr/';
    const WEB_PPF_TR       = self::WEB_PP_FRAGMENTS.'tr/';

    // Files
    const WEB_A_ROW_ADMINISTRATION  = self::WEB_PAF_TR.'admin-fragments-row-administration.tpl';
    const WEB_A_FORM_ADMINISTRATION = self::WEB_PAF_FORM.'admin-fragments-form-administration.tpl';
    const WEB_P_ROW_ADMINISTRATION  = self::WEB_PPF_TR.'public-fragments-row-administration.tpl';
    const WEB_PPFS_CONTENT_HEADER = self::WEB_PPF_SECTION.'public-fragments-section-content-header.php';
    const WEB_PPFS_CONTENT_NAVBAR = self::WEB_PPF_SECTION.'public-fragments-section-content-navigation-bar.php';
    const WEB_PPFS_ONGLET         = self::WEB_PPF_SECTION.'public-fragments-section-onglet.php';
    const WEB_PPFS_ONGLET_LIST    = self::WEB_PPF_SECTION.'public-fragments-section-onglet-list.php';
}
