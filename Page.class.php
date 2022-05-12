<?php
class Page extends Entity
{
	public static $table = "sr_pages";
	public static $columns = array("name","modules","template","menu","footer");
	public static $id_column = "id";
	public $id,$name,$modules,$template;
	
	public static function GetById($id){
		$new_page = parent::GetById($id);
		if($new_page){ 
			$modules_decoded = json_decode($new_page->modules);   
			$number_of_columns = count($modules_decoded);  
			$module_ids = array(); 
			foreach($modules_decoded as $modules_decoded_arr){  
					$module_ids = array_merge($modules_decoded_arr,$module_ids);
			}  
			$module_sequence = Modul::GetSequence($module_ids); 
			$modules_sorted = array(); 
			foreach($module_sequence as $mod){
				$modules_sorted[(string)$mod->id] = $mod;
			} 
			$new_page->modules_arr = array(); 
			for($i=0;$i<$number_of_columns;$i++){
				$new_page->modules_arr[] = array();
			} 
			foreach($modules_decoded as $col=>$module_ids){  
				foreach($module_ids as $modul){
					$new_page->modules_arr[$col][] = $modules_sorted[$modul];
				}
			} 
		} 
		return $new_page; 
	}
	public function RenderModules($col=0){
		foreach($this->modules_arr[$col] as $module){
			$module->render();
		}
	}
	
	public function Render(){
		include APP_DIR."/templates/".$this->template."/index.php";
	}
}