<?php
/** 
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 * 	\file		socialnetwork/class/actions_socialnetwork.class.php
 *	\ingroup	socialnetwork
 *	\brief		Ensemble de fonctions d'action de base pour le module socialnetwork
 *
 */

/**
 *	\class	actionssocialnetwork
 *	\brief	Classe d'action du trigger et rayonnage
 */
class Actionssocialnetwork
{
	private $db; //!< To store db handler
	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

    /**
     * Constructor
     *
     * @param		DoliDB		$db      Database handler
     */
    public function __construct($db)
    {
        $this->db = $db;
		return 1;
    }
	
	/**
	 *	Editer le nom d'un salon
	 *
	 *	@param		string	$name	Valeur du nouveau nom
	 *
	 *	@return		setEventMessage
	 *	@todo		vérifier si le salon du meme nom existe deja
	 */
	function ajouter_salon($name){
		global $user;
		if( $user->rights->socialnetwork->ecrire || $user->admin && trim($name) != '')
		{

			$sql  = "INSERT INTO `".MAIN_DB_PREFIX."salon`";
			$sql .= " (`label`)";
			$sql .= " VALUES ('".$this->db->escape($name)."')";

			$result = $this->db->query($sql);
			
			if($result){
				return setEventMessage('Nouveau salon enregistré');
			}else{
				return setEventMessage('Impossible d\'enregistrer ce salon','errors');
				
			}
		}
		
	}
	
	/**
	 *	Editer le nom d'un salon
	 *
	 *	@param		int		$id		Id du salon à éditer
	 *	@param		string	$name	Valeur du nouveau nom
	 *
	 *	@return		setEventMessage
	 */
	function editer_salon($id,$name)
	{
		global $user;
		if( $user->rights->socialnetwork->ecrire || $user->admin && is_numeric($id) && trim($name) != ''){
			//
			$sql  = "UPDATE `".MAIN_DB_PREFIX."salon`";	
			$sql .= " SET `label` = '".$this->db->escape($name)."'";
			$sql .= " WHERE `llx_salon`.`rowid` = '".$id."'";
			// print $sql;
			$result = $this->db->query($sql);
			
			if($result){
				return setEventMessage('Modification effectuée');
			}else{
				return setEventMessage('Ce salon n\'existe pas','errors');
				
			}
		}
		
	}
	
	/**
	 *	Supprimer un salon
	 *
	 *	@param		int		$id		Id du salon à éditer
	 *	@param		string	$name	Valeur du nouveau nom
	 *
	 *	@return		senteventmessage
	 */
	function supprimer_salon($id)
	{
		global $user;
		
		if( $user->rights->socialnetwork->ecrire || $user->admin && is_numeric($id) ){
	
			$sql  = "DELETE";
			$sql .= " FROM `".MAIN_DB_PREFIX."salon`";
			$sql .= " WHERE `".MAIN_DB_PREFIX."salon`.rowid=".$id;

			$result = $this->db->query($sql);
			
			if($result){
				return setEventMessage('Suppression effectuée');
			}else{
				return setEventMessage('Ce salon n\'existe pas','errors');
				
			}

		}
		
	}
	

	
	
	
	
}