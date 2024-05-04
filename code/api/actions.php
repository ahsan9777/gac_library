<?php
session_start();
class Actions {
	public $recordsPerPage = '50';
	public $recordsPerPage2 = '50';
    private $functions;
	private $main;

	public function __construct(Functions $fun, Main $main){
        $this->functions = $fun;
		$this->main = $main;
	}
	

	public function get_subject($params){
		$retValue = array("status" => "1", "message" => "Get subject data");
		$retValue['data'] = $this->main->get_subject($params);
		return $retValue;
	}
	
	public function get_author($params){
		$retValue = array("status" => "1", "message" => "Get author data");
		$retValue['data'] = $this->main->get_author($params);
		return $retValue;
	}
	
	public function get_book_title($params){
		$retValue= $this->main->get_book_title($params);
		return $retValue;
	}
	
	public function get_library_books($params){
		$retValue = $this->main->get_library_books($params);
		return $retValue;
	}
	
	public function search_filter($params){
		$retValue = $this->main->search_filter($params);
		return $retValue;
	}
	
	public function get_banner($params){
		$retValue = $this->main->get_banner($params);
		return $retValue;
	}
	
	public function get_faqs($params){
		$retValue = $this->main->get_faqs($params);
		return $retValue;
	}
	
	public function get_social_links($params){
		$retValue = $this->main->get_social_links($params);
		return $retValue;
	}

	public function get_page_content($params){
		$retValue = $this->main->get_page_content($params);
		return $retValue;
	}
	
	public function get_page_widgets($params){
		$retValue = $this->main->get_page_widgets($params);
		return $retValue;
	}
}