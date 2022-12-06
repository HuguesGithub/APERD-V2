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
interface ConstantsInterface
{
    /////////////////////////////////////////////////
    // Attributs
    const ATTR_CLASS             = 'class';
    const ATTR_HREF              = 'href';
    const ATTR_TYPE              = 'type';

    /////////////////////////////////////////////////
    // Constantes
    const CST_ACTION             = 'action';
    const CST_AMP                = '&amp;';
    const CST_BLANK              = ' ';
    const CST_CHECKED            = 'checked';
    const CST_DELETE             = 'delete';
    const CST_EDIT               = 'edit';
    
    /////////////////////////////////////////////////
    // Fields
    const FIELD_ID               = 'id';
    // Table Administration
    const FIELD_GENRE            = 'genre';
    const FIELD_NOMTITULAIRE     = 'nomTitulaire';
    const FIELD_LABELPOSTE       = 'labelPoste';
 
    /////////////////////////////////////////////////
    // Notifications
    const NOTIF_DANGER           = 'danger';
    const NOTIF_WARNING          = 'warning';

    /////////////////////////////////////////////////
    // Pages
    const PAGE_ADMIN             = 'admin';
    
    /////////////////////////////////////////////////
    // SQL
    const SQL_LIMIT              = '__limit__';
    const SQL_ORDER_ASC          = 'ASC';
    const SQL_ORDER_DESC         = 'DESC';
    const SQL_ORDER_RAND         = 'RAND()';
    const SQL_ORDER              = '__sql_order__';
    const SQL_ORDERBY            = '__sql_orderby__';
    const SQL_WHERE              = '__sql_where__';
    
    /////////////////////////////////////////////////
    // Tags
    const TAG_A                  = 'a';
    const TAG_BUTTON             = 'button';
    const TAG_DIV                = 'div';
    const TAG_I                  = 'i';
    const TAG_INPUT              = 'input';
    const TAG_TH                 = 'th';
    
    /////////////////////////////////////////////////
    // Divers
    const CSV_SEP                = ';';
    const VERSION                = 'v2.22.12.05';
}
