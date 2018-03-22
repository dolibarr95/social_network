<?php
/** 
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 * 	\file		socialnetwork/lib/socialnetwork.lib.php
 *	\ingroup	socialnetwork
 *	\brief		Gestionnaire de menu du module
 */
 
 /**
 *	Affficher une barre onglets de navigation pour l'administration
 *	@return     array		
 */ 
function socialnetworkAdminPrepareHead()
{
	global $langs, $conf;

	$langs->load("socialnetwork@socialnetwork");

	$h = 0;
	$head = array();


	$head[$h][0] = dol_buildpath("/socialnetwork/admin/about.php", 1);
	$head[$h][1] = 'A propos';
	$head[$h][2] = 'about';
	$h++;

	
	complete_head_from_modules($conf, $langs, $object, $head, $h, 'socialnetwork');

	return $head;
}

/**
 *	Affficher une barre onglets de navigation pour utilisateur
 *	@return     array		
 */
function socialnetworkPrepareHead()
{
	global $langs, $conf;

	$langs->load("socialnetwork@socialnetwork");

	$h = 0;
	$head = array();	
	
	$head[$h][0] = dol_buildpath("/socialnetwork/salon.php",1);
	$head[$h][1] = "Salons";
	$head[$h][2] = 'salon';
	$h++;
	$head[$h][0] = dol_buildpath("/socialnetwork/gestionSalon.php",1);
	$head[$h][1] = "Gestion des salons";
	$head[$h][2] = 'gestionSalon';
	$h++;
	
	complete_head_from_modules($conf, $langs, $object, $head, $h, 'socialnetwork');

	return $head;
}

