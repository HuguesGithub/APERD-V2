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
interface LabelsInterface
{
    /////////////////////////////////////////////////
    // Labels de champs
    const LABEL_GENRE         = 'Genre';
    const LABEL_LABELPOSTE    = 'Libellé du poste';
    const LABEL_NOMTITULAIRE  = 'Nom du titulaire';
    const LABEL_NOMPRENOM     = 'Nom et prénom';
    const LABEL_NOM           = 'Nom';
    const LABEL_PRENOM        = 'Prénom';
    const LABEL_MAIL          = 'Email';
    const LABEL_ADHERENT      = 'Adhérent';
    const LABEL_LABELDIVISION = 'Division';
    const LABEL_LABELMATIERE  = 'Matière';
    const LABEL_ELEVE         = 'Élève';
    
    /////////////////////////////////////////////////
    // Libellés divers
    const LABEL_ACTIONS               = 'Actions';
    const LABEL_ADMINISTRATIFS        = 'Administratifs';
    const LABEL_ANNULER               = 'Annuler';
    const LABEL_BUREAU                = 'Bureau';
    const LABEL_COMPOSITION           = 'Composition';
    const LABEL_CREATION              = 'Création';
    const LABEL_CREER                 = 'Créer';
    const LABEL_CREER_ENTREE          = 'Créer une entrée';
    const LABEL_DELEGUE               = 'Délégué';
    const LABEL_DIVISIONS             = 'Divisions';
    const LABEL_ELEVES                = 'Élèves';
    const LABEL_ENSEIGNANTS           = 'Enseignants';
    const LABEL_ENSEIGNANTS_PRINCIPAUX = 'Enseignants principaux';
    const LABEL_EXPORTER_LISTE        = 'Exporter la liste';
    const LABEL_LIST_ADMINISTRATIFS   = 'Liste des Administratifs';
    const LABEL_LIST_PARENTS          = 'Liste des Parents d\'élèves';
    const LABEL_LIST_PARENTS_DELEGUES = 'Liste des Parents d\'élèves délégués';
    const LABEL_LIST_DIVISIONS        = 'Liste des Divisions';
    const LABEL_LIST_ELEVES           = 'Liste des Élèves';
    const LABEL_LIST_MATIERES         = 'Liste des Matières';
    const LABEL_MATIERES              = 'Matières';
    const LABEL_MATIERES_ENSEIGNANTS  = 'Matière par enseignant';
    const LABEL_MODIFIER              = 'Modifier';
    const LABEL_CLEAR_FILTER          = 'Nettoyer le filtre';
    const LABEL_PARENTS               = 'Parents';
    const LABEL_PARENTS_ELEVES        = 'Parents d\'élèves';
    const LABEL_PARENTS_DELEGUES      = 'Parents délégués';
    const LABEL_RETOUR                = 'Retour';
    const LABEL_ROLE                  = 'Rôle';
    const LABEL_ROLE_ADMIN            = 'Administrateur';
    const LABEL_ROLE_ADHERENT         = 'Adhérent';
    const LABEL_ROLE_EDITEUR          = 'Éditeur';
    const LABEL_ROLE_CONTACT          = 'Contact';
    const LABEL_SUPPRIMER             = 'Supprimer';
    const LABEL_PHONE                 = 'Téléphone';
    
    /////////////////////////////////////////////////
    // Libellés longs
    const LABEL_INTERFACE_DIVISIONS_PRES = 'La liste des divisions du collège.';
    const LABEL_INTERFACE_ELEVES_PRES = 'La liste des élèves du collège.';
    const LABEL_INTERFACE_MATIERES_PRES = 'La liste des matières enseignées au collège.';
    const LABEL_INTERFACE_PARENTS_PRES = 'La liste des parents ayant simplement souhaité être en relation avec l\'association, ceux ayant adhéré, les parents délégués et ceux investis dans les instances du collège.';
    const LABEL_INTERFACE_ADMINISTRATIFS_PRES = 'Le personnel administratif présenté ici regroupe les personnes de l\'administration susceptibles d\'intervenir au sein notamment du conseil d\'administraion, mais aussi des conseils de classe.';
    
    /////////////////////////////////////////////////
    // Messages d'erreurs
    const MSG_ERREUR_CONTROL_ENTETE           = 'La première ligne ne correspond pas aux champs attendus : <strong>%s</strong>.';
    const MSG_ERREUR_CONTROL_EXISTENCE_NORMEE = 'Champ obligatoire [<strong>%s</strong>] non saisi. ';
    const MSG_ERREUR_CONTROL_EXISTENCE        = 'Champ saisi [<strong>%s</strong>] ne correspond à aucune donnée. ';
    const MSG_ERREUR_CONTROL_IDENTIFICATION   = "Une erreur est survenue lors de la saisie de votre identifiant et de votre mot de passe.<br>L'un des champs était vide, ou les deux ne correspondaient pas à une valeur attendue.<br>Veuillez réessayer ou contacter un administrateur.<br><br>";
    const MSG_ERREUR_AJAX_DATA = 'Erreur dans AjaxActions %1$s, la valeur %2$s pour le tag %3$s n\'est pas une valeur attendue.';
    
    /////////////////////////////////////////////////
    // Messages de succès
    const MSG_SUCCESS_EXPORT = 'Exportation réussie. Le fichier peut être téléchargé <a href="%s" class="text-white">ici</a>.';
    const MSG_SUCCESS_IMPORT = 'L\'importation des données s\'est correctement déroulée.';
    const MSG_SUCCESS_CREATE = 'La création s\'est correctement déroulée.';
    const MSG_SUCCESS_EDIT   = 'L\'édition s\'est correctement déroulée.';
    
    /////////////////////////////////////////////////
    // Messages dynamiques
    const DYN_DISPLAYED_PAGINATION = '%1$s - %2$s sur %3$s';
}
