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
    const WEB_PAGES_PUBLIC = 'web/pages/publique/';
    const WEB_PA_FRAGMENTS = self::WEB_PAGES_ADMIN.'fragments/';
    const WEB_PP_FRAGMENTS = self::WEB_PAGES_PUBLIC.'fragments/';
    const WEB_PPF_ARTICLE  = self::WEB_PP_FRAGMENTS.'article/';
    const WEB_PPF_CARD     = self::WEB_PP_FRAGMENTS.'card/';
    const WEB_PAF_FORM     = self::WEB_PA_FRAGMENTS.'form/';
    const WEB_PPF_FORM     = self::WEB_PP_FRAGMENTS.'form/';
    const WEB_PPF_NAV      = self::WEB_PP_FRAGMENTS.'nav/';
    const WEB_PPF_SECTION  = self::WEB_PP_FRAGMENTS.'section/';
    const WEB_PAF_TR       = self::WEB_PA_FRAGMENTS.'tr/';
    const WEB_PPF_TR       = self::WEB_PP_FRAGMENTS.'tr/';
    
    // Files
    const WEB_A_ROW_ADMINISTRATION  = self::WEB_PAF_TR.'admin-fragments-row-administration.tpl';
    const WEB_A_FORM_ADMINISTRATION = self::WEB_PAF_FORM.'admin-fragments-form-administration.tpl';
    const WEB_A_FORM_ADULTE         = self::WEB_PAF_FORM.'admin-fragments-form-adulte.tpl';
    const WEB_P_ROW_ADMINISTRATION  = self::WEB_PPF_TR.'publique-fragments-row-administration.tpl';
    const WEB_PP_BOARD              = self::WEB_PAGES_PUBLIC.'publique-board.tpl';
    const WEB_PPFC_CARD             = self::WEB_PPF_CARD.'publique-fragments-card.tpl';
    const WEB_PPFC_UPLOAD           = self::WEB_PPF_CARD.'publique-fragments-card-upload.tpl';
    const WEB_PPFC_LIST_ADM         = self::WEB_PPF_CARD.'publique-fragments-card-administratif-liste.tpl';
    const WEB_PPFC_DELETE           = self::WEB_PPF_CARD.'publique-fragments-card-delete.tpl';
    const WEB_PPFC_CONF_DEL         = self::WEB_PPF_CARD.'publique-fragments-card-delete-confirmed.tpl';
    const WEB_PPFC_PRES_ADM         = self::WEB_PPF_CARD.'publique-fragments-card-administratif-presentation.tpl';
    const WEB_PPFC_PRES_ADULTE      = self::WEB_PPF_CARD.'publique-fragments-card-adulte-presentation.tpl';
    const WEB_PPFC_PRES_DIVISION    = self::WEB_PPF_CARD.'publique-fragments-card-division-presentation.tpl';
    const WEB_PPFC_LIST_DEFAULT     = self::WEB_PPF_CARD.'publique-fragments-card-default-liste.tpl';
    const WEB_PPFN_NAV_BAR          = self::WEB_PPF_NAV.'publique-fragments-nav-bar.tpl';
    const WEB_PPFN_NAV_SIDEBAR      = self::WEB_PPF_NAV.'publique-fragments-nav-sidebar.tpl';
    const WEB_PPFS_CONNEXION_PANEL  = self::WEB_PPF_SECTION.'publique-fragments-section-connexion-panel.tpl';
    const WEB_PPFS_CONTENT_HEADER   = self::WEB_PPF_SECTION.'publique-fragments-section-content-header.tpl';
    const WEB_PPFS_CONTENT_NAVBAR   = self::WEB_PPF_SECTION.'publique-fragments-section-content-navigation-bar.php';
    const WEB_PPFS_ONGLET           = self::WEB_PPF_SECTION.'publique-fragments-section-onglet.php';
    const WEB_PPFS_ONGLET_LIST      = self::WEB_PPF_SECTION.'publique-fragments-section-onglet-list.php';
    const WEB_PPFS_CONTENT_ONE_4TH  = self::WEB_PPF_SECTION.'section-onglet-content-one-fourth.tpl';
    const WEB_PPF_FILTRE            = self::WEB_PP_FRAGMENTS.'publique-fragments-filtre.tpl';
}
