<?php
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
    // Messages d'erreurs
    const MSG_ERREUR_CONTROL_EXISTENCE_NORMEE = 'Champ obligatoire [<strong>%s</strong>] non saisi. ';
	
    /////////////////////////////////////////////////
	// Messages dynamiques
    const DYN_DISPLAYED_PAGINATION = '%1$s - %2$s sur %3$s';
}
