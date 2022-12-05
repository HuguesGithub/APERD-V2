<?php
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
    // Allowed Pages :
    const PAGE_ADMIN             = 'admin';

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