<?php
/** 
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 * 	\file		socialnetwork/class/socialnetwork.class.php
 *	\ingroup	socialnetwork
 *	\brief		Ensemble de fonctions de base pour le module socialnetwork
 */

/**
 *	\class	socialnetwork
 *	\brief	Classe du module tournée
 */
class Socialnetwork
{
	

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
    }
	/**
	 *	Afficher un formulaire de la liste des salons
	 *
	 *	@param		int		$id		Id du salon à éditer
	 *
	 *	@return		html
	 */
	function liste_salon_form($id=0)
	{
		global $bc,$var;
		$sql  = "SELECT";
		$sql .= " s.rowid, s.label";
		$sql .= " FROM `".MAIN_DB_PREFIX."salon` as s";
		$sql .= " ORDER BY s.label ASC";
		
		$resql = $this->db->query($sql);
		$html = '';
		if ($resql ){
			
			if( $this->db->num_rows($resql) !=0  ){
				
				$obj = $this->db->fetch_object($resql);
				$html .= '<table class="noborder" width="100%">';
				$html .= '<thead>';
				$html .= '<tr class="liste_titre"><td>Salon</td></tr>';
				$html .= '</thead>';
				do{
					$var = !$var;
					$html .= '<tr '.$bc[$var].'>';
				
					$html .= '<td>';
					$html .= $obj->label;
					$html .= '</td>';
					
					
					$html .= '</tr>';
					
				}while($obj = $this->db->fetch_object($resql));
				$html .= '</table>';
			}
		}
		return $html;
		
	}
	/**
	 *	Afficher la liste des salons 
	 *
	 *	@param		int		$id id de l'élément à modifier
	 *	@return		html
	 */
	function liste_salon_dispo($id, $action)
	{
		global $bc, $user;
		//liste des salons
		$sql  = "SELECT";
		$sql .= " s.rowid, s.label";
		$sql .= " FROM `".MAIN_DB_PREFIX."salon` as s";
		$sql .= " ORDER BY s.label ASC";
		
		$resql = $this->db->query($sql);
		

		if ($resql ){
			
			if( $this->db->num_rows($resql) !=0  ){
				//liste des salons utilisés et leur score
				$sql2  = "SELECT";
				$sql2 .= " x.salon";
				$sql2 .= " FROM `".MAIN_DB_PREFIX."societe_extrafields` as x, `".MAIN_DB_PREFIX."societe` as s";
				$sql2 .= " WHERE x.salon IS NOT NULL";
				$sql2 .= " AND x.fk_object = s.rowid";
				
				$resql2 = $this->db->query($sql2);
				
				if( $this->db->num_rows($resql2) !=0  ){
					$obj2 = $this->db->fetch_object($resql2);
					
					$s = array();//liste des salons
					do{

					$salon = explode(',',$obj2->salon);
						for($e=0 ; $e<count($salon) ; $e++){
							$s[$salon[$e]] = $s[$salon[$e]]+1;//ajout score salon
						}
				
					}while($obj2 = $this->db->fetch_object($resql2));
				}
				
				
				$obj = $this->db->fetch_object($resql);
				$var = true;
				
				do{
					
					print '<tr '.$bc[$var].'>';
					print '<td>';
					
						if( $id == $obj->rowid && $action=='editer' && ( $user->rights->socialnetwork->ecrire || $user->admin ) ){//edition d'un nom de salon
						
							print '<form action="" method="POST" id="social_rename">';
							print '<input type="text" name="label" value="'.$obj->label.'">';
							print '<input type="submit" class="button" name="modify" value="Modifier"  onclick="$(\'#social_rename\').attr(\'action\', \''.$_SERVER['PHP_SELF'].'?id='.$obj->rowid.'&action=\'+this.name);">';
							print '<input type="submit" class="button" name="cancel" value="Annuler" onclick="$(\'#social_rename\').attr(\'action\', \''.$_SERVER['PHP_SELF'].'?action=\'+this.name);">';
							print '</form>';
							
						}else{
							
							print $obj->label;
							
						}
					
					print '</td>';
					print '<td>';
					print $s[$obj->rowid];
					print '</td>';
					print '<td>';
					
					if(	$id != $obj->rowid || $action !='editer' ){
						
						if( $user->rights->socialnetwork->ecrire || $user->admin ){//editer le salon
						
							print '<a href="'.$_SERVER['PHP_SELF'].'?action=editer&id='.$obj->rowid.'">';
							print img_edit('modifier le nom de ce salon');
							print '</a>';
							
						}else{
							
							print img_edit('modifier le nom de ce salon',0,'style="opacity:0.2;"');
							
						}
											
					}
					
					print '</td>';
					print '<td>';
					
					if( $s[$obj->rowid] > 0 ){
						
						print '';//utilisé donc pas de suppression
						
					}else{
						
						if( $user->rights->socialnetwork->ecrire || $user->admin ){//supprimer un salon
							print '<a href="'.$_SERVER['PHP_SELF'].'?action=delete&id='.$obj->rowid.'">';
							print img_delete('supprimer ce salon');
							print '</a>';
						}else{
							print img_delete('supprimer ce salon','style="opacity:0.2;"');
						}
						
					}
					print '</td>';
					print '</tr>';
					
					$var = !$var;
					
				}while( $obj = $this->db->fetch_object($resql) );
				
			}
		}
		
	}
	
	/**
	 *	Afficher la liste des salons et clients
	 *
	 *	@return		javascript
	 */
	function liste_salon_client()
	{
		
		$sql  = "SELECT";
		$sql .= " s.rowid, s.label";
		$sql .= " FROM `".MAIN_DB_PREFIX."salon` as s";
		$sql .= " ORDER BY s.label ASC";
		
		$resql = $this->db->query($sql);
		$nodes = '';
		$edges = '';
		$salons = array();
		if ($resql ){
			
			if( $this->db->num_rows($resql) !=0  ){
				$i=0;
				
				$obj = $this->db->fetch_object($resql);
					
				$nodes .= 'var nodes = ['."\n";//tableau des noeuds javascript

				
				do{

					$nodes .= '{id: '.$i.', "label": "'.$obj->label.'", "group": 1},'."\n";//liste des salons
				
					$salons[$obj->rowid] = $i;
					$i++;
					
					
				}while($obj = $this->db->fetch_object($resql));
				
				
				$sql  = "SELECT";
				$sql .= " x.fk_object,x.salon,s.nom";
				$sql .= " FROM `".MAIN_DB_PREFIX."societe_extrafields` as x, `".MAIN_DB_PREFIX."societe` as s";
				$sql .= " WHERE x.salon IS NOT NULL";
				$sql .= " AND x.fk_object = s.rowid";
				
				$resql = $this->db->query($sql);
				if( $this->db->num_rows($resql) !=0  ){
						$obj = $this->db->fetch_object($resql);
					$edges .= 'var edges = ['."\n";//tableau des lignes javacript
					
					do{

				
					$nodes .= '{id: '.$i.', "label": "'.$obj->nom.'", "group": '.$i.'},'."\n";//liste des tiers rencontré sur un salon
					

					$salon = explode(',',$obj->salon);
						for($e=0 ; $e<count($salon) ; $e++){
							$edges .= '  {"from": '.$i.', "to": '.$salons[$salon[$e]].'},'."\n";
						}
					
					
					$i++;
					
					
					}while($obj = $this->db->fetch_object($resql));
					$edges .= ' ];'."\n";
				}
				
				
				
				$nodes .= ' ];'."\n";
			}
		}
		return $nodes.$edges;
	}

	
	
}	