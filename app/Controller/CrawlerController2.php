<?php
App::uses('AppController', 'Controller');

class CrawlerController extends AppController {

	public $helpers = array('Html', 'Session');
	
	public $uses = array('Url', 'Emailz');
	
////////////////////////////////////////////////////////////
	
	public function index() {

		// FIND URL
		$url = $this->Url->find('all', array(
			'recursive' => -1,
			'fields' => array('id', 'url'),
			'conditions' => array(
				'Url.visited' => 'no'
			)
		));
		
		if(!empty($url)){

			$allEmails['url'] = array();
			$allLinks['email'] = array();
		
			foreach($url as $key => $value){
	 			$conteudo = @file_get_contents($value['Url']['url']); 
	
	 			$urlVisited[] = $value['Url']['id'];
	 			
	 			// 1. FIND LINKS 			
	 			$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
	 			if(preg_match_all("/$regexp/siU", $conteudo, $resultados, PREG_SET_ORDER)) { 
	 			 	foreach($resultados as $resultado) {
			 			$allLinks['url'][] = $resultado[2];
	 			 	}

	 			}	
	
	 			// 2. FIND EMAILS 			
	 			if(preg_match_all('/\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $conteudo, $resultados2, PREG_SET_ORDER)) {
	 			 	foreach($resultados2 as $resultado2) {
		 				$allEmails['email'][] = $resultado2[0];
	 			 	 }
	 			
	 			}
	 			
			}	 			

			

			// 3. REMOVED ALL DUPLICATES
			$linksClear = array_unique($allLinks['url']);
	 		$emailsClear = array_unique($allEmails['email']);
			foreach($linksClear as $lk) {
 		 		$links[]['url'] = $lk; 		
			}
			
			foreach($emailsClear as $lk2) {
 		 		$emails[]['email'] = $lk2;
			}
			
	 		// 4. MODEL VALIDATES UNIQUE URL BEFORE SAVE AND UPDATE VISITED
	 		$this->Url->SaveAll($links, array('validate'=> true));
	 		$this->Emailz->SaveAll($emails, array('validate'=> true));
	 		
	 		
	 		
	 		echo print_r($urlVisited);
	 		
        	// $this->Url->updateAll(array('Url.visited' => "'yes'"), array('Url.id' => $urlVisited));
	 		
	 		// 5. REDIRECT SELF PARA MATAR TODOS OS LINKS EXISTENTES(NOVOS), CASO NÌO EXISTAM MAIS A ROTINA PARA. 
	 		// $this->redirect( array('controller'=>'crawler', 'action' => 'index'));
		
		}else{
		
				// todas as urls foram visitadas;	
				exit;
		
		}
			
	}		

////////////////////////////////////////////////////////////
	


}
