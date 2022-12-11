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
    
    /////////////////////////////////////////////////
    // Libellés divers
    const LABEL_ACTIONS             = 'Actions';
    const LABEL_ADMINISTRATIFS      = 'Administratifs';
    const LABEL_ANNULER             = 'Annuler';
    const LABEL_BUREAU              = 'Bureau';
    const LABEL_CREATION            = 'Création';
    const LABEL_CREER               = 'Créer';
    const LABEL_CREER_ENTREE        = 'Créer une entrée';
    const LABEL_DIVISIONS           = 'Divisions';
    const LABEL_EXPORTER_LISTE      = 'Exporter la liste';
    const LABEL_LIST_ADMINISTRATIFS = 'Liste des Administratifs';
    const LABEL_LIST_PARENTS        = 'Liste des Parents d\'élèves';
    const LABEL_LIST_DIVISIONS      = 'Liste des Divisions';
    const LABEL_MODIFIER            = 'Modifier';
    const LABEL_PARENTS             = 'Parents';
    const LABEL_RETOUR              = 'Retour';
    const LABEL_SUPPRIMER           = 'Supprimer';
    const LABEL_PHONE               = 'Téléphone';
    
    /////////////////////////////////////////////////
    // Messages d'erreurs
    const MSG_ERREUR_CONTROL_ENTETE           = 'La première ligne ne correspond pas aux champs attendus : <strong>%s</strong>.';
    const MSG_ERREUR_CONTROL_EXISTENCE_NORMEE = 'Champ obligatoire [<strong>%s</strong>] non saisi. ';
    const MSG_ERREUR_CONTROL_IDENTIFICATION   = "Une erreur est survenue lors de la saisie de votre identifiant et de votre mot de passe.<br>L'un des champs était vide, ou les deux ne correspondaient pas à une valeur attendue.<br>Veuillez réessayer ou contacter un administrateur.<br><br>";
    const MSG_ERREUR_AJAX_DATA = 'Erreur dans AjaxActions %1$s, la valeur %2$s pour le tag %3$s n\'est pas une valeur attendue.';
    
    /////////////////////////////////////////////////
    // Messages de succès
    const MSG_SUCCESS_EXPORT = 'Exportation réussie. Le fichier peut être téléchargé <a href="%s" class="text-white">ici</a>.';
    const MSG_SUCCESS_IMPORT                  = 'L\'importation des données s\'est correctement déroulée.';

    /////////////////////////////////////////////////
    // Messages dynamiques
    const DYN_DISPLAYED_PAGINATION = '%1$s - %2$s sur %3$s';
}
