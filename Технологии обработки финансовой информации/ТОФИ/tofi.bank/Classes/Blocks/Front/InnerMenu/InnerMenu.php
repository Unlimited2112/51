<?php
Loader::loadBlock('Menu', 'FrontBase');

class InnerMenu extends Menu
{
	protected $prefix = "im_";
	
	function initialize($params=array()) 
	{
		$page = $this->Structure->getByID($this->tplVars['current_page_id']);
		if($page['id_parent']) {
			$params['parent'] = $page['id_parent'];
			$pageParent = $this->Structure->getByID($page['id_parent']);
		}
		else {
			$params['parent'] = $this->tplVars['current_page_id'];
			$pageParent = $page;
		}
		
		$parentTemplate = $this->Core->getModel('StructureTemplates')->getByID($pageParent['id_template']);
		
		if($parentTemplate['system'] == 'DirectionPage') {
			// мы в разделе направления
			$this->tplVars['is_direction'] = true;
			$parentValues = $this->Structure->getContentValues($pageParent['id']);
			
			$this->tplVars['pscv_subheader'] = @$parentValues['scv_subheader'];
			$this->tplVars['pscv_annonce'] = @$parentValues['scv_annonce'];
			$this->tplVars['pscv_link'] = $pageParent['uri'];
			$this->tplVars['pscv_id'] = $pageParent['id'];
			
			Loader::loadBlock('ProductsBlock', 'Front');
			BlocksRegistry::getInstance()->registerBlock('ProductsBlock', new ProductsBlock());
			
			$this->tplVars['is_direction_page'] = true;
			if(isset($this->page_params[0])) {
				$projects = $this->Core->getModel('Projects');
				$project = $projects->getByURI($this->page_params[0]);
				if($project) {
					$this->tplVars['is_direction_page'] = false;
				}
			}
			
			if($this->tplVars['is_direction_page']) {
				$res = $this->Core->getModel('Projects')->getChoosen($pageParent['id']);
				if($res->rowCount()) {
					$this->tplVars['fav_count'] = 0;
					$this->tplVars['fav_image'] = array();
					$this->tplVars['fav_title'] = array();
					$this->tplVars['fav_uri'] = array();
					foreach($res as $row) {
						$this->tplVars['fav_image'][] = $row['image'];
						$this->tplVars['fav_title'][] = $row['title'];
						$this->tplVars['fav_uri'][] = $row['uri'];
						$this->tplVars['fav_count']++;
					}
				}
			}
			
		}
		else {
			// мы в информационном разделе
			$this->tplVars['is_direction'] = false;
		}
		
		$this->tplVars['path_title'] = $pageParent['title'];
		
		parent::initialize($params);
	}
}