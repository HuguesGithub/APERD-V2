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
    const AJAX_IMORT_FILE        = 'importFile';
    
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
    const CST_EDIT               = 'edit';
    const CST_EOL                = "\r\n";
    const CST_ERR_LOGIN          = 'err_login';
    const CST_EXPORT             = 'export';
    const CST_ICON               = 'icon';
    const CST_IDS                = 'ids';
    const CST_LABEL              = 'label';
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
    // Table Administration
    const FIELD_GENRE            = 'genre';
    const FIELD_NOMTITULAIRE     = 'nomTitulaire';
    const FIELD_LABELPOSTE       = 'labelPoste';
    // Table Adulte
    const FIELD_NOMADULTE        = 'nomAdulte';
    const FIELD_PRENOMADULTE     = 'prenomAdulte';
    const FIELD_MAILADULTE       = 'mailAdulte';
    const FIELD_ADHERENT         = 'adherent';
    const FIELD_PHONEADULTE      = 'phoneAdulte';
    // Table AdulteDivision
    const FIELD_ADULTEID         = 'adulteId';
    const FIELD_DIVISIONID       = 'divisionId';
    const FIELD_DELEGUE          = 'delegue';
    // Table Division
    const FIELD_LABELDIVISION    = 'labelDivision';
    
    /////////////////////////////////////////////////
    // Icons
    const I_ANGLE_LEFT           = 'angle-left';
    const I_ANGLES_LEFT          = 'angles-left';
    const I_CARET_LEFT           = 'caret-left';
    const I_CARET_RIGHT          = 'caret-right';
    const I_CIRCLE               = 'circle';
    const I_DELETE               = 'trash-can';
    const I_DESKTOP              = 'desktop';
    const I_DOWNLOAD             = 'download';
    const I_EDIT                 = 'pen-to-square';
    const I_FILTER_CIRCLE_XMARK  = 'filter-circle-xmark';
    const I_REFRESH              = 'arrows-rotate';
    const I_SCHOOL               = 'school';
    const I_SQUARE_CHECK         = 'square-check';
    const I_SQUARE_XMARK         = 'square-xmark';
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
    const ONGLET_PARENTS          = 'parents';
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
    const TAG_LI                  = 'li';
    const TAG_OPTION             = 'option';
    const TAG_P                   = 'p';
    const TAG_SELECT             = 'select';
    const TAG_STRONG              = 'strong';
    const TAG_TD                  = 'td';
    const TAG_TH                  = 'th';
    const TAG_TR                  = 'tr';
    const TAG_UL                  = 'ul';
    
    /*
  /////////////////////////////////////////////////
  // Action Ajax
  const AJAX_GETNEWMATIERE = 'getNewMatiere';
  const AJAX_PAGED         = 'paged';
  const AJAX_SAVE          = 'save';
  const AJAX_SEARCH        = 'search';
  const AJAX_UPLOAD        = 'ajaxUpload';

  /////////////////////////////////////////////////
  // Attributs
  const ATTR_MULTIPLE    = 'multiple';
  const ATTR_PLACEHOLDER = 'placeholder';
  const ATTR_READONLY    = 'readonly';
  const ATTR_REQUIRED    = 'required';
  const ATTR_ROWS        = 'rows';

    /////////////////////////////////////////////////
    // On conserve malgré tout quelques constantes
  const CST_ARCHIVE        = 'archive';
  const CST_BULK           = 'Bulk';
  const CST_BULK_EXPORT    = 'Bulk-export';
  const CST_BULK_TRASH     = 'Bulk-trash';
  const CST_BULK_MAILING   = 'Bulk-mailing';
  const CST_CREATE         = 'create';
  const CST_DEFAULT_SELECT = 'Choisir...';
  const CST_DOWNLOAD       = 'download';
  const CST_FORMCONTROL    = 'form-control';
  const CST_HIDDEN         = 'hidden';
  const CST_ID             = 'id';
  const CST_IMPORT         = 'import';
  const CST_INF            = 'inf';
  const CST_MAILING        = 'mailing';
  const CST_MD_SELECT      = 'form-control md-select';
  const CST_MD_TEXTAREA    = 'form-control md-textarea';
  const CST_POST           = 'post';
  const CST_SELECTED       = 'selected';
  const CST_SUP            = 'sup';
  const CST_SUPPRESSION    = 'Suppression';
  const CST_TEXT           = 'text';
  const CST_TRASH          = 'trash';
  const CST_VIEW           = 'view';

  
    /////////////////////////////////////////////////
    // Fields
    const FIELD_ICON             = 'icon';
    const FIELD_LABEL            = 'label';
  
  const FIELD_ADMINISTRATION_ID     = 'administrationId';
  const FIELD_ANNEESCOLAIRE         = 'anneeScolaire';
  const FIELD_ANNEESCOLAIRE_ID      = 'anneeScolaireId';
  const FIELD_AUTEURREDACTION       = 'auteurRedaction';
  const FIELD_BILANELEVES           = 'bilanEleves';
  const FIELD_BILANPARENTS          = 'bilanParents';
  const FIELD_BILANPROFPRINCIPAL    = 'bilanProfPrincipal';
  const FIELD_COMPTERENDU_ID        = 'compteRenduId';
  const FIELD_CONFIG_KEY            = 'configKey';
  const FIELD_CONFIG_VALUE          = 'configValue';
  const FIELD_CRKEY                 = 'crKey';
  const FIELD_DATA                  = 'data';
  const FIELD_DATECONSEIL           = 'dateConseil';
  const FIELD_DATEREDACTION         = 'dateRedaction';
  const FIELD_DISPLAY_ORDER         = 'displayOrder';
  const FIELD_ENFANT1               = 'delegueEleve1Id';
  const FIELD_ENFANT2               = 'delegueEleve2Id';
  const FIELD_ENSEIGNANT_ID         = 'enseignantId';
  const FIELD_ENSEIGNANT_MATIERE_ID = 'enseignantMatiereId';
  const FIELD_LABELMATIERE          = 'labelMatiere';
  const FIELD_MAILCONTACT           = 'mailContact';
  const FIELD_MATIERE_ID            = 'matiereId';
  const FIELD_NBCOMPLIMENTS         = 'nbCompliments';
  const FIELD_NBELEVES              = 'nbEleves';
  const FIELD_NBENCOURAGEMENTS      = 'nbEncouragements';
  const FIELD_NBFELICITATIONS       = 'nbFelicitations';
  const FIELD_NBMGCPT               = 'nbMgComportement';
  const FIELD_NBMGTVL               = 'nbMgTravail';
  const FIELD_NBMGCPTTVL            = 'nbMgComportementTravail';
  const FIELD_NOMELEVE              = 'nomEleve';
  const FIELD_NOMENSEIGNANT         = 'nomEnseignant';
  const FIELD_OBSERVATIONS          = 'observations';
  const FIELD_PARENT1               = 'delegueParent1Id';
  const FIELD_PARENT2               = 'delegueParent2Id';
  const FIELD_PRENOMELEVE           = 'prenomEleve';
  const FIELD_PRENOMENSEIGNANT      = 'prenomEnseignant';
  const FIELD_PROFPRINCIPAL_ID      = 'profPrincId';
  const FIELD_STATUS                = 'status';
  const FIELD_TRIMESTRE             = 'trimestre';

  /////////////////////////////////////////////////
  // Formats
  const FORMAT_DATE_JJMMAAAA = 'jj/mm/aaaa';
  const FORMAT_DATE_YMDHIS   = 'Y-m-d H:i:s';

  /////////////////////////////////////////////////
  // Message
  const MSG_ACTION_INDEFINIE                = 'Action indéterminée : [<strong>%s</strong>].';
  const MSG_BULK_DELETE_IMPOSSIBLE          = 'Suppressions impossibles : aucun élément sélectionné.';
  const MSG_BULK_EXPORT_IMPOSSIBLE          = 'Export impossible : aucun élément sélectionné.';
  const MSG_BULK_ACTION_IMPOSSIBLE          = 'Action Bulk <strong>%s</strong> impossible : aucun élément sélectionné.';
  const MSG_BULK_ACTION_INDEFINIE           = 'Action Bulk indéterminée : [<strong>%s</strong>].';
  const MSG_BULK_DELETE_SUCCESS             = 'Suppressions réussies.';
  const MSG_CONFIRM_SUPPR_ADMINISTRATIONS   = 'Voulez-vous vraiment <strong>supprimer</strong> les Administratifs <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_ADMINISTRATION    = 'Voulez-vous vraiment <strong>supprimer</strong> l\'Administratif <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_ANNEESCOLAIRES    = 'Voulez-vous vraiment <strong>supprimer</strong> les Années Scolaires <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_ANNEESCOLAIRE     = 'Voulez-vous vraiment <strong>supprimer</strong> l\'Année Scolaire <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_COMPO_DIVISIONS   = 'Voulez-vous vraiment <strong>supprimer</strong> les assignations "Enseignant/Division" <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_COMPO_DIVISION    = 'Voulez-vous vraiment <strong>supprimer</strong> l\'assignation "Enseignant/Division" <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_COMPTE_RENDU      = 'Voulez-vous vraiment <strong>supprimer</strong> le Compte Rendu <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_COMPTE_RENDUS     = 'Voulez-vous vraiment <strong>supprimer</strong> les Comptes Rendus <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_DATA_QUESTIONS    = 'Voulez-vous vraiment <strong>supprimer</strong> les Questionnaires <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_DATA_QUESTION     = 'Voulez-vous vraiment <strong>supprimer</strong> le Questionnaire <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_DIVISIONS         = 'Voulez-vous vraiment <strong>supprimer</strong> les Division <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_DIVISION          = 'Voulez-vous vraiment <strong>supprimer</strong> la Division <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_ELEVES            = 'Voulez-vous vraiment <strong>supprimer</strong> les Elèves <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_ELEVE             = 'Voulez-vous vraiment <strong>supprimer</strong> l\'Elève <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_ENSEIGNANTS       = 'Voulez-vous vraiment <strong>supprimer</strong> les Enseignants <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_ENSEIGNANT        = 'Voulez-vous vraiment <strong>supprimer</strong> l\'Enseignant <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_MATIERES          = 'Voulez-vous vraiment <strong>supprimer</strong> les Matières <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_MATIERE           = 'Voulez-vous vraiment <strong>supprimer</strong> la Matière <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_PARENTS           = 'Voulez-vous vraiment <strong>supprimer</strong> les Parents <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_PARENT            = 'Voulez-vous vraiment <strong>supprimer</strong> le Parent <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_PARENT_DELEGUES   = 'Voulez-vous vraiment <strong>supprimer</strong> les Parents Délégués <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_PARENT_DELEGUE    = 'Voulez-vous vraiment <strong>supprimer</strong> le Parent Délégué <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_QUESTIONNAIRES    = 'Voulez-vous vraiment <strong>supprimer</strong> les Clefs <strong>%s</strong> ?';
  const MSG_CONFIRM_SUPPR_QUESTIONNAIRE     = 'Voulez-vous vraiment <strong>supprimer</strong> la Clef <strong>%s</strong> ?';
  const MSG_ERREUR_CONTROL_UNICITE          = 'Champ qui ne respecte pas le critère d\'unicité. ';
  const MSG_ERREUR_CONTROL_INEXISTENCE      = 'Une donnée utilisée (%s) n\'existe pas en base. ';
  const MSG_ERREUR_CONTROL_ID               = 'Identifiant non valide. ';
  const MSG_ERREUR_CONTROL_FORMAT           = 'Format attendu [<strong>%s</strong>] non respecté. ';
  const MSG_SUCCESS_DELETE                  = 'Suppression réussie.';
  const MSG_SUCCESS_PARTIEL_IMPORT          = 'L\'importation a été partiellement effectuée.';
  const MSG_SUCCESS_CREATE                  = 'Création réussie.';
  const MSG_SUCCESS_UPDATE                  = 'Mise à jour réussie. ';

  const MSG_SUCCESS_ZIP_EXPORT              = 'Compression réussie. Les fichiers peuvent être téléchargés <a href="%s">ici</a>.';

  /////////////////////////////////////////////////
  // Notifications
  const NOTIF_INFO       = 'info';
  const NOTIF_IS_INVALID = 'is-invalid';
  const NOTIF_LIGHT      = 'light';

    /////////////////////////////////////////////////
    // Onglets
    const ONGLET_CDC              = 'cdc';
    const ONGLET_ELEVES           = 'eleves';
    const ONGLET_ENSEIGNANTS      = 'enseignants';
    const ONGLET_INBOX            = 'inbox';
    const ONGLET_QUESTIONS        = 'questionnaire';

  
    /////////////////////////////////////////////////
    // Allowed Pages :
  const PAGE_ADMINISTRATION = 'administration';
  const PAGE_ANNEE_SCOLAIRE = 'annee-scolaire';
  const PAGE_COMPO_DIVISION = 'compodivision';
  const PAGE_COMPTE_RENDU   = 'compte-rendu';
  const PAGE_CONFIGURATION  = 'configuration';
  const PAGE_CONSEILSCLASSE = 'conseils-de-classes';
  const PAGE_DATA_QUESTIONS = 'data-question';
  const PAGE_DIVISION       = 'division';
  const PAGE_ELEVE          = 'eleve';
  const PAGE_ENSEIGNANT     = 'enseignant';
  const PAGE_MATIERE        = 'matiere';
  const PAGE_PARENT         = 'parent';
  const PAGE_PARENT_DELEGUE = 'parent-delegue';
  const PAGE_QUESTIONNAIRE  = 'questionnaire';

  /////////////////////////////////////////////////
  // Statuts :
  const STATUS_ARCHIVED  = 'archived';
  const STATUS_FUTURE    = 'future';
  const STATUS_PUBLISHED = 'published';
  const STATUS_PENDING   = 'pending';
  const STATUS_WORKING   = 'working';
  const STATUS_MAILED    = 'mailed';

    /////////////////////////////////////////////////
    // Tags
    const TAG_IMG                = 'img';
    const TAG_SPAN               = 'span';
    const TAG_TEXTAREA           = 'textarea';

    /////////////////////////////////////////////////
    // Divers
    const EOL          = "\r\n";
    */
    
    /////////////////////////////////////////////////
    // Divers
    const VERSION                = 'v2.22.12.07';
}
