<?php
namespace core\interfaceimpl;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.08
 */
interface ConstantsInterface
{
    const DB_PREFIX              = 'wp_20_aperd_';
    
    /////////////////////////////////////////////////
    // Action Ajax
    const AJAX_ACTION            = 'ajaxAction';
    const AJAX_CSV_EXPORT        = 'csvExport';
    const AJAX_IMPORT_FILE       = 'importFile';
    
    /////////////////////////////////////////////////
    // Attributs
    const ATTR_CLASS             = 'class';
    const ATTR_DATA_AJAX         = 'data-ajax';
    const ATTR_DATA_TARGET       = 'data-target';
    const ATTR_DATA_TRIGGER      = 'data-trigger';
    const ATTR_DATA_TYPE         = 'data-type';
    const ATTR_HREF              = 'href';
    const ATTR_ID                = 'id';
    const ATTR_NAME              = 'name';
    const ATTR_SELECTED    = 'selected';
    const ATTR_STYLE             = 'style';
    const ATTR_TITLE             = 'title';
    const ATTR_TYPE              = 'type';
    const ATTR_VALUE             = 'value';
    
    /////////////////////////////////////////////////
    // Css
    const CSS_BTN_DARK           = 'btn-dark';
    const CSS_DISABLED           = 'disabled';
    
    /////////////////////////////////////////////////
    // Constantes
    const CST_ACTION             = 'action';
    const CST_ACTIVE             = 'active';
    const CST_ALL                = 'all';
    const CST_AMP                = '&amp;';
    const CST_BLANK              = ' ';
    const CST_CHECKED            = 'checked';
    const CST_CHILDREN           = 'children';
    const CST_CONFIRM            = 'confirm';
    const CST_CSV_EXPORT         = 'csvExport';
    const CST_CURPAGE            = 'curPage';
    const CST_DELETE             = 'delete';
    const CST_DISABLED           = 'disabled';
    const CST_COMPOSITION_DIVISIONS  = 'compoDivision';
    const CST_EDIT               = 'edit';
    const CST_ENSEIGNANTS_PRINCIPAUX = 'enseignantPrinc';
    const CST_EOL                = "\r\n";
    const CST_ERR_LOGIN          = 'err_login';
    const CST_EXPORT             = 'export';
    const CST_ICON               = 'icon';
    const CST_IDS                = 'ids';
    const CST_LABEL              = 'label';
    const CST_MATIERES_ENSEIGNANTS   = 'matiereEnseignant';
    const CST_NBSP               = '&nbsp;';
    const CST_ONGLET             = 'onglet';
    const CST_PARENTS_DELEGUES   = 'parentDelegue';
    const CST_PARENTS_DIVISIONS  = 'parentDelegue';
    const CST_POST_ACTION        = 'postAction';
    const CST_RIGHT              = 'right';
    const CSV_SEP                = ';';
    const CST_SUBONGLET          = 'subOnglet';
    const CST_TEXT_WHITE         = 'text-white';
    const CST_WRITE              = 'write';
    
    /////////////////////////////////////////////////
    // Fields
    const FIELD_ID               = 'id';
    const FIELD_DIVISIONID       = 'divisionId';
    const FIELD_DELEGUE          = 'delegue';
    const FIELD_ENSEIGNANTID     = 'enseignantId';
    const FIELD_GENRE            = 'genre';
    // Table Administration
    const FIELD_NOMTITULAIRE     = 'nomTitulaire';
    const FIELD_LABELPOSTE       = 'labelPoste';
    // Table Adulte
    const FIELD_NOMADULTE        = 'nomAdulte';
    const FIELD_PRENOMADULTE     = 'prenomAdulte';
    const FIELD_MAILADULTE       = 'mailAdulte';
    const FIELD_ADHERENT         = 'adherent';
    const FIELD_PHONEADULTE      = 'phoneAdulte';
    const FIELD_ROLEADULTE       = 'roleAdulte';
    // Table AdulteDivision
    const FIELD_ADULTEID         = 'adulteId';
    // Table Composition Division
    // wp_14_aperd_compo_division
    const FIELD_ENSEIGNANTMATIEREID = 'enseignantMatiereId';
    // Table Division
    const FIELD_LABELDIVISION    = 'labelDivision';
    // Table Eleve
    const FIELD_NOMELEVE         = 'nomEleve';
    const FIELD_PRENOMELEVE      = 'prenomEleve';
    // Table Enseignant
    // wp_14_aperd_enseignant
    const FIELD_NOMENSEIGNANT    = 'nomEnseignant';
    const FIELD_PRENOMENSEIGNANT = 'prenomEnseignant';
    const FIELD_MAILENSEIGNANT   = 'mailEnseignant';
    // Table Enseignant Division
    // wp_14_aperd_enseignant_division
    // Pas de champ au nom unique.
    // Table Matière
    const FIELD_LABELMATIERE     = 'labelMatiere';
    // Table Matiere Enseignant
    // wp_14_aperd_enseignant_matiere
    const FIELD_MATIEREID        = 'matiereId';
    
    /////////////////////////////////////////////////
    // Icons
    const I_ANGLE_LEFT           = 'angle-left';
    const I_ANGLES_LEFT          = 'angles-left';
    const I_CARET_LEFT           = 'caret-left';
    const I_CARET_RIGHT          = 'caret-right';
    const I_CHALKBOARD           = 'chalkboard';
    const I_CIRCLE               = 'circle';
    const I_DELETE               = 'trash-can';
    const I_DESKTOP              = 'desktop';
    const I_DOWNLOAD             = 'download';
    const I_EDIT                 = 'pen-to-square';
    const I_FILTER_CIRCLE_XMARK  = 'filter-circle-xmark';
    const I_GEAR                 = 'gear';
    const I_REFRESH              = 'arrows-rotate';
    const I_SCHOOL               = 'school';
    const I_SQUARE_CHECK         = 'square-check';
    const I_SQUARE_XMARK         = 'square-xmark';
    const I_USER_GRADUATE        = 'user-graduate';
    const I_USERS                = 'users';
    
    /////////////////////////////////////////////////
    // Notifications
    const NOTIF_DANGER           = 'danger';
    const NOTIF_SUCCESS          = 'success';
    const NOTIF_WARNING          = 'warning';

    /////////////////////////////////////////////////
    // Onglets
    const ONGLET_ADMINISTRATIFS   = 'administratifs';
    const ONGLET_DESK             = 'desk';
    const ONGLET_DIVISIONS        = 'divisions';
    const ONGLET_ELEVES           = 'eleves';
    const ONGLET_ENSEIGNANTS      = 'enseignants';
    const ONGLET_MATIERES         = 'matieres';
    const ONGLET_PARENTS          = 'parents';
    const ONGLET_PARAMETRES       = 'parametres';
    const SUBONGLET_PARENTS_DELEGUES          = 'parentDelegue';
        
    /////////////////////////////////////////////////
    // Pages
    const PAGE_ADMIN              = 'admin';

    /////////////////////////////////////////////////
    // Rôles
    const ROLE_ADMIN              = 9;
    const ROLE_ADHERENT           = 2;
    const ROLE_EDITEUR            = 5;
    const ROLE_CONTACT            = 1;
    
    /////////////////////////////////////////////////
    // Variable de Session
    const SESSION_APERD_ID        = 'aperd_id';
    
    /////////////////////////////////////////////////
    // SQL
    const SQL_JOKER               = '%';
    const SQL_LIMIT               = '__limit__';
    const SQL_ORDER_ASC           = 'ASC';
    const SQL_ORDER_DESC          = 'DESC';
    const SQL_ORDER_RAND          = 'RAND()';
    const SQL_ORDER               = '__sql_order__';
    const SQL_ORDERBY             = '__sql_orderby__';
    const SQL_WHERE               = '__sql_where__';
    
    /////////////////////////////////////////////////
    // Tags
    const TAG_A                   = 'a';
    const TAG_BUTTON              = 'button';
    const TAG_DIV                 = 'div';
    const TAG_I                   = 'i';
    const TAG_INPUT               = 'input';
    const TAG_LABEL              = 'label';
    const TAG_LI                  = 'li';
    const TAG_OPTION             = 'option';
    const TAG_P                   = 'p';
    const TAG_SELECT             = 'select';
    const TAG_STRONG              = 'strong';
    const TAG_TD                  = 'td';
    const TAG_TH                  = 'th';
    const TAG_TR                  = 'tr';
    const TAG_UL                  = 'ul';
    
    /////////////////////////////////////////////////
    // Divers
    const VERSION                = 'v2.22.12.07';
}
