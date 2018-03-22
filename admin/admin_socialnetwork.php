<?php
/** 
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 * 	\file		socialnetwork/admin/admin_socialnetwork.php
 * 	\ingroup	socialnetwork
 * 	\brief		Page d'accueil de l'administration du module
 */
/// \cond IGNORER
// Load Dolibarr environment
require '../../../main.inc.php';


global $langs, $user;

// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once '../lib/socialnetwork.lib.php';
// Translations
$langs->load("socialnetwork@socialnetwork");

//Contrôle d'accès et statut du module
require_once '../core/modules/modSocialnetwork.class.php';	
$bstatus = new modSocialnetwork($db);
$const_name = 'MAIN_MODULE_'.strtoupper($bstatus->name);
if ( ( !$user->rights->socialnetwork->admin && !$user->admin ) || empty($conf->global->$const_name) ){
	accessforbidden();
}

// Parameters


/*
 * Actions
 */


/*
 * View
 */
$page_name = "Accueil du module";
llxHeader('', $page_name);

// Subheader
$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">'
	. $langs->trans("BackToModuleList") . '</a>';
print_fiche_titre($page_name, $linkback,'title_setup');

// Configuration header
$head = socialnetworkAdminPrepareHead();
dol_fiche_head(
	$head,
	'MyModuleIndex',
	$langs->trans("Module710001Name"),
	0,
	"socialnetwork@socialnetwork"
);

// Setup page goes here
echo 'Page d\'accueil du module socialnetwork';
// echo '<br><br>';
$objMod = new modSocialnetwork($db);
print '<ul>';
print '<li>Fonctionne avec Dolibarr : ';
foreach($objMod->need_dolibarr_version as $value) print $value.' ';
print '</li><li>Version du module : '.$objMod->version.'</li>';
print '<li>Description : '.$objMod->description.'</li>';
print '<li>Auteur : '.$objMod->editor_name.'</li>';
print '</ul>';
// Page end
dol_fiche_end();
llxFooter();
/// \endcond
?>