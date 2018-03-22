<?php
/** 
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 * 	\file		socialnetwork/gestionSalon.php
 *	\ingroup    socialnetwork
 *	\brief      Gérer les salons
 *
 */

/// \cond IGNORER
// Load Dolibarr environment

require '../../main.inc.php';
require_once 'class/socialnetwork.class.php';
require_once 'lib/socialnetwork.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';



// Translations
$langs->load("socialnetwork@socialnetwork");
$langs->load("admin");
$langs->load("errors");


//Contrôle d'accès et statut du module
require_once 'core/modules/modSocialnetwork.class.php';	
$bstatus = new modSocialnetwork($db);
$const_name = 'MAIN_MODULE_'.strtoupper($bstatus->name);
if ( ( !$user->rights->socialnetwork->lire && !$user->admin ) || empty($conf->global->$const_name) ){
	accessforbidden();
}

/**
 * Parametres
 */
/**
 *	string : id du salon à traiter
 */ 
$id = GETPOST('id', 'int');
/**
 *	string : id de la commande
 */
$action = GETPOST('action', 'alpha'); 


 
$obj_b = new socialnetwork($db);


/// \cond IGNORER
/**
 *	action : action formulaire
 */


/**
 * Affichage
 */

// llxHeader('', 'rien');
// llxHeader("",$texte,$help_url);
llxHeader('', $langs->trans("Module710001Name"), '');


$head = socialnetworkPrepareHead();
dol_fiche_head(
	$head,
	'gestionSalon',
	$langs->trans("Module710001Name"),
	0,
	"socialnetwork@socialnetwork"
);


if( $user->rights->socialnetwork->ecrire || $user->admin){
	
	require_once 'class/actions_socialnetwork.class.php';

	if($action == 'cancel'){//annuler edition
	
		$id = '';
		$action ='';
	
	}elseif( $action == 'modify' ){//modifier le nom d'un salon
		$label = GETPOST('label'); 
		
		$obj_Ab = new Actionssocialnetwork($db);
		 $obj_Ab->editer_salon($id,dol_escape_htmltag($label));
		$id = '';
		$action ='';
		
	}elseif ($action == 'delete' ){//confirmation suppression salon
		
		require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
		$form = new Form($db);
		print $form->formconfirm($_SERVER["PHP_SELF"].'?id='.$id, "Supprimer ce salon", "Supprimer ce salon ?<br /><i>Ce salon n'est utilisé par aucun tiers.</i>", 'confirm_delete', '', 'yes', 1);
		$id = '';
		$action ='';
		
	}elseif($action == 'confirm_delete'  ){//suppression salon
	
		$confirm = GETPOST('confirm', 'alpha'); 
		if($confirm == 'yes'){
			$obj_Ab = new Actionssocialnetwork($db);
			$obj_Ab->supprimer_salon($id);
		}
		$confirm = '';
		$id = '';
		$action ='';
	
	} elseif( $action == 'new' ){//modifier le nom d'un salon
	
		$new_label = GETPOST('new_label'); 
		
		$obj_Ab = new Actionssocialnetwork($db);
		$obj_Ab->ajouter_salon(dol_escape_htmltag($new_label));
		
		$action ='';
		
	}
}



print '<table class="noborder" width="100%">';
print '<tr class="liste_titre"><td>Liste des salons disponibles</td><td>Nombre de société rencontrées sur ce salon</td><td style="width:20px;"></td><td style="width:20px;"></td></tr>';
$obj_b->liste_salon_dispo($id, $action);
print '</table>';

if( $user->rights->socialnetwork->ecrire || $user->admin){
	
print '<form  method="POST" action="'.$_SERVER["PHP_SELF"].'?action=new">';
print '<label for="new_label">Salon</label> <input type="text" name="new_label" id="new_label" placeholder="nom du salon"> ';

print '<input type="submit" class="button" name="create" value="Enregistrer ce nouveau salon" >';

print '</form>';
}





	
dol_fiche_end();

llxFooter();

$db->close();
/// \endcond
?>