<?php
/** 
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 * 	\file		socialnetwork/admin/about.php
 *	\ingroup    socialnetwork
 *	\brief      A propos de ce module
 */

/// \cond IGNORER

require '../../../main.inc.php';
global $langs, $user;



// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once '../lib/socialnetwork.lib.php';
require __DIR__ . '/../vendor/autoload.php';

// Translations
$langs->load("socialnetwork@socialnetwork");


//Contrôle d'accès et statut du module
require_once '../core/modules/modSocialnetwork.class.php';	
$bstatus = new modSocialnetwork($db);
$const_name = 'MAIN_MODULE_'.strtoupper($bstatus->name);
if ( ( !$user->rights->socialnetwork->admin && !$user->admin ) || empty($conf->global->$const_name) ){
	accessforbidden();
}


 
/*
 * View
 */
$page_name = "A propos du module";
llxHeader('', $langs->trans($page_name));

// Subheader
$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">'
	. $langs->trans("BackToModuleList") . '</a>';
print_fiche_titre($langs->trans($page_name), $linkback,'title_setup');

// Configuration header
$head = socialnetworkAdminPrepareHead();
dol_fiche_head(
	$head,
	'about',
	$langs->trans("Module710001Name"),
	0,
	'socialnetwork@socialnetwork'
);




$bstatus = new modSocialnetwork($db);

print "
<h2>Id du module</h2>
<p>Ce module fonctione sous l'id ".$bstatus->numero.".";

				
print"<br />Plus d'informations sur la liste des id disponibles sur : <a href=\"https://wiki.dolibarr.org/index.php/List_of_modules_id\" title=\"wiki.dolibarr.org\">https://wiki.dolibarr.org/index.php/List_of_modules_id</a></p>


<h2>Objet</h2>
<p>Afficher un réseau de tiers.</p>";





// Page end
dol_fiche_end();
llxFooter();
/// \endcond
?>