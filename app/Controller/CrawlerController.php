<?php
App::uses('AppController', 'Controller');

class CrawlerController extends AppController {

	public $helpers = array('Html', 'Session');
	
	public $uses = array('Url', 'Emailz');
	
////////////////////////////////////////////////////////////
	
	public function index() {

		$emails = '';
		$links = '';
		
		// FIND URL
		$url = $this->Url->find('all', array(
			'recursive' => -1,
			'fields' => array('url'),
			'conditions' => array(
				'Url.visited' => 'no'
			)
		));
		
		if(!empty($url)){
			foreach($url as $key => $value){
	 			$conteudo = @file_get_contents($value['Url']['url']); 
	
	 			// 1. FIND LINKS 			
	 			$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
	 			if(preg_match_all("/$regexp/siU", $conteudo, $resultados, PREG_SET_ORDER)) { 
	 			 	foreach($resultados as $resultado) {
			 			$links[]['url'] = $resultado[2];
	 			 	}
	 			}	
	
	 			// 2. FIND EMAILS 			
	 			if(preg_match_all('/\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $conteudo, $resultados2, PREG_SET_ORDER)) {
	 			 	foreach($resultados2 as $resultado2) {
		 				$emails[]['email'] = $resultado2[0];
	 			 	 }
	 			}	
	 		} 		
	
	 		// 3. MODEL VALIDATES UNIQUE VALUE AND BELOW, I REMOVED ALL DUPLICATES
	 		$this->Url->SaveAll(array_unique($links));
	 		$this->Emailz->SaveAll(array_unique($emails));
	 		
	 		// 4. REDIRECT SELF PARA MATAR TODOS OS LINKS EXISTENTES(NOVOS), CASO NÌO EXISTAM MAIS A ROTINA PARA. 
	 		$this->redirect( array('controller'=>'crawler', 'action' => 'index'));
		
		}else{
		
				// todas as urls foram visitadas;	
				exit;
		
		}
			
	}		

////////////////////////////////////////////////////////////
	


}
