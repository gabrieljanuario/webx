<?php
App::uses('AppController', 'Controller');

class CrawlerController extends AppController {

	public $helpers = array('Html', 'Session');
	
	public $uses = array('Url', 'Emailz');
	
////////////////////////////////////////////////////////////
	
	public function index() {
		
		$this->autoRender = false;
	
		
		// FIND URL
		$url = $this->Url->find('all', array(
			'recursive' => -1,
			'fields' => array('id', 'url'),
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

						if (!$this->Url->hasAny(array('url' => $resultado[2]))){
							$link = array('url' => $resultado[2]);
							$this->Url->create();
							$this->Url->saveAll($link);
						}
						
	 			 	}

	 			}	
	
	 			// 2. FIND EMAILS 			
	 			if(preg_match_all('/\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $conteudo, $resultados2, PREG_SET_ORDER)) {
	 			 	foreach($resultados2 as $resultado2) {

						if (!$this->Emailz->hasAny(array('email' => $resultado2[0]))){
							$email = array('email' => $resultado2[0]);
							$this->Emailz->create();
							$this->Emailz->saveAll($email);
						}
		 			 	
	 			 	}
	 			
	 			}
	 			
	 			$urlVisited[] = $value['Url']['id'];
			}	 			

	 		// 4. MODEL VALIDATES UNIQUE URL BEFORE SAVE AND UPDATE VISITED
        	$this->Url->updateAll(array('Url.visited' => "'yes'"), array('Url.id' => $urlVisited));
	 		
	 		// 5. SELF REDIRECT PARA MATAR TODOS OS LINKS EXISTENTES(NOVOS), CASO N�O EXISTAM MAIS A ROTINA PARA. 
	 		$this->redirect( array('controller'=>'crawler', 'action' => 'index'));
		
		}else{
				// todas as urls foram visitadas;	
				exit;
		}
		
			
	}		

////////////////////////////////////////////////////////////
	


}
