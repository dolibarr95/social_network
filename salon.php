<?php
/** 
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 * 	\file		socialnetwork/salon.php
 *	\ingroup    socialnetwork
 *	\brief      Générer un graphique relationel salons/clients
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
	'salon',
	$langs->trans("Module710001Name"),
	0,
	"socialnetwork@socialnetwork"
);




?>

    <style type="text/css">
        #mynetwork {
            width: 100%;
            height: 850px;
            border: 1px solid lightgray;
        }
    </style>

    <script type="text/javascript" src="js/vis.js"></script>
    <link href="css/vis-network.min.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        function draw() {


<?php
print $obj_b->liste_salon_client();

?>

            // create a network
            var container = document.getElementById('mynetwork');
            var data = {
                nodes: nodes,
                edges: edges
            };
            var options = {
                nodes: {
                    shape: 'dot',
                    size: 16
                },
                physics: {
                    forceAtlas2Based: {
                        gravitationalConstant: -26,
                        centralGravity: 0.005,
                        springLength: 230,
                        springConstant: 0.18
                    },
                    maxVelocity: 146,
                    solver: 'forceAtlas2Based',
                    timestep: 0.30,//0.35
                    stabilization: {iterations: 150}//150
                }
            };
            var network = new vis.Network(container, data, options);

        }
    </script>

<div id="mynetwork">
<img src="img/icons/wait.gif" onload="draw()" >Cette page peut-être assez longue à charger...soyez patient.
</div>
<?php


	
dol_fiche_end();

llxFooter();

$db->close();
/// \endcond
?>