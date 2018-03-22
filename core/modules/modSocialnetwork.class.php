<?php
/** 
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 * 	\file		socialnetwork/core/modules/modSocialnetwork.class.php
 *	\ingroup    socialnetwork	
 *	\brief      Gestion des tournées
 *

 */ 
include_once DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php";

/**
 *	\class      modSocialnetwork
 *	\brief      Classe de description et d'activation du module
 */
class modSocialnetwork extends DolibarrModules
{

	/**
	 *	\brief	Constructeur. Définir des noms, des constantes, des répertoires, des boîtes, des autorisations
	 *
	 * 	@param	DoliDB		$db	Gestionnaire de base de données
	 */
	public function __construct($db)
	{
		global $langs, $conf;

		// DolibarrModules is abstract in Dolibarr < 3.8
		if (is_callable('parent::__construct')) {
			parent::__construct($db);
		} else {
			$this->db = $db;
		}

		$this->numero = 710001;//numero de module a confirmer 
		$this->rights_class = 'socialnetwork';//
		
		$this->family = "societe";
		$this->editor_name = "^..^";//
		
		$this->name = preg_replace('/^mod/i', '', get_class($this));
		
		$this->description = "Générer un reseau entre les tiers";
		
		$this->version = '1.0.0';
		
		$this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
	
		$this->special = 0;//dans quel onglet apparait ce module
		
		$this->picto = 'socialnetwork@socialnetwork'; // Copyright fatcow.com
		
		$this->module_parts = array(
			
		);

		// Data directories to create when module is enabled.
		// Example: this->dirs = array("/mymodule/temp");
		$this->dirs = array();

		// Config pages. Put here list of php pages
		// stored into mymodule/admin directory, used to setup module.
		$this->config_page_url = array("admin_socialnetwork.php@socialnetwork");

		// Dependencies
		// A condition to hide module
		$this->hidden = false;
		// List of modules class name as string that must be enabled if this module is enabled
		// Example : $this->depends('modAnotherModule', 'modYetAnotherModule')
		//$this->depends = array('modGMaps');
		$this->depends = array('modSociete');
		
		// List of modules id to disable if this one is disabled
		$this->requiredby = array();
		// List of modules id this module is in conflict with
		$this->conflictwith = array();
		// Minimum version of PHP required by module
		$this->phpmin = array(5, 3);
		// Minimum version of Dolibarr required by module
		$this->need_dolibarr_version = array(4);
		// Language files list (langfiles@mymodule)
		$this->langfiles = array("socialnetwork@socialnetwork");
		// Constants
		// List of particular constants to add when module is enabled
		// (name, type ['chaine' or ?], value, description, visibility, entity ['current' or 'allentities'], delete on unactive)
		// Example:
		$this->const = array(
				
		);

		

		// Dictionaries
		if (! isset($conf->mymodule->enabled)) {
			$conf->mymodule=new stdClass();
			$conf->mymodule->enabled = 0;
		}
		$this->dictionaries = array();
	

		// Boxes
		// Add here list of php file(s) stored in core/boxes that contains class to show a box.
		$this->boxes = array(); // Boxes list
		// $r=0;
		// $this->boxes[$r][1] = "box_socialnetwork@".$this->name;
		
	
	
		// Permissions
		$this->rights = array(); // Permission array used by this module
		$r = 0;

		$r++;
		$this->rights[$r][0] = 710002;
		$this->rights[$r][1] = 'Afficher le reseau d\'un tiers';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 1;//oui par defaut lors d'enregistrement d'un nouvel utilisateur
		$this->rights[$r][4] = 'lire';

		$r++;
		$this->rights[$r][0] = 710003;
		$this->rights[$r][1] = 'Editer les noms des salons';
		$this->rights[$r][2] = 'w';
		$this->rights[$r][3] = 0;//non par defaut lors d'enregistrement d'un nouvel utilisateur
		$this->rights[$r][4] = 'ecrire';	
		
		$r++;
		$this->rights[$r][0] = 710004;
		$this->rights[$r][1] = 'Paramétrer le module';
		$this->rights[$r][2] = 'w';
		$this->rights[$r][3] = 0;//non par defaut lors d'enregistrement d'un nouvel utilisateur
		$this->rights[$r][4] = 'admin';	
		
		
	
	
	$r=0;
		$this->menu[$r]=array(	'fk_menu'=>'fk_mainmenu=companies,fk_leftmenu=thirdparties',
					'type'=>'left',
					'titre'=>'Salon',
					'mainmenu'=>'',
					'leftmenu'=>'',
					'url'=>'/socialnetwork/gestionSalon.php',
					'langs'=>'socialnetwork@socialnetwork',
					'position'=>100,
					'enabled'=>'1',
					'perms'=>'1',
					'target'=>'',
					'user'=>2);
		$r++;
	
	

	
		// Exports
		$r = 0;

		
	}

	/**
	 * Function called when module is enabled.
	 * The init function add constants, boxes, permissions and menus
	 * (defined in constructor) into Dolibarr database.
	 * It also creates data directories
	 *
	 * 	@param		string	$options	Options when enabling module ('', 'noboxes')
	 * 	@return		int					1 if OK, 0 if KO
	 */
	public function init($options = '')
	{
		$sql = array();

		$result = $this->loadTables();

		return $this->_init($sql, $options);
	}

	/**
	 * Function called when module is disabled.
	 * Remove from database constants, boxes and permissions from Dolibarr database.
	 * Data directories are not deleted
	 *
	 * 	@param		string	$options	Options when enabling module ('', 'noboxes')
	 * 	@return		int					1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = array();

		return $this->_remove($sql, $options);
	}

	/**
	 * Create tables, keys and data required by module
	 * Files llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys
	 * and create data commands must be stored in directory /mymodule/sql/
	 * This function is called by this->init
	 *
	 * 	@return		int		<=0 if KO, >0 if OK
	 */
	private function loadTables()
	{
		return $this->_load_tables('/socialnetwork/sql/');
		
	}

}
