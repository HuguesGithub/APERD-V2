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
interface LabelsInterface
{
    /////////////////////////////////////////////////
    // Labels de champs
    const LABEL_GENRE        = 'Genre';
    const LABEL_LABELPOSTE   = 'Libellé du poste';
    const LABEL_NOMTITULAIRE = 'Nom du titulaire';

    /////////////////////////////////////////////////
    // Libellés divers
    const LABEL_ACTIONS             = 'Actions';
    const LABEL_ADMINISTRATIFS      = 'Administratifs';
    const LABEL_BUREAU              = 'Bureau';
    const LABEL_CREATION            = 'Création';
    const LABEL_CREER_ENTREE        = 'Créer une entrée';
    const LABEL_EDITION             = 'Édition';
    const LABEL_LIST_ADMINISTRATIFS = 'Liste des Administratifs';
    
    /////////////////////////////////////////////////
    // Messages d'erreurs
    const MSG_ERREUR_CONTROL_EXISTENCE_NORMEE = 'Champ obligatoire [<strong>%s</strong>] non saisi. ';
    
    /////////////////////////////////////////////////
    // Messages dynamiques
    const DYN_DISPLAYED_PAGINATION = '%1$s - %2$s sur %3$s';
}
