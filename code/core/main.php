<?php
class Main {
    private $func;

    public function __construct(Functions $functions) {
        $this->func = $functions;
    }

    public function search_filter($params){
        $retValue = array();
        

        if(isset($params) && !empty($params)){
            if(isset($params['lb_title']) && !empty($params['lb_title'])){
                $retValue = array("status" => "1", "message" => "Get search book title data");
                $retValue['data'] = $this->get_book_title($params['lb_title']);

            } elseif(isset($params['auth_name']) && !empty($params['auth_name'])){
                $retValue = array("status" => "1", "message" => "Get search author data");
                $retValue['data'] = $this->get_author($params['auth_name']);

            } elseif(isset($params['sub_title']) && !empty($params['sub_title'])){
                $retValue = array("status" => "1", "message" => "Get search subject data");
                $retValue['data'] = $this->get_subject($params['sub_title']);
            } else{
                $retValue = array("status" => "0", "message" => "Please set at least one parameter like ( keyword, lb_title, auth_name, sub_title)");
            }
        } else {
            $retValue = array("status" => "0", "message" => "Please set at least one parameter like ( keyword, lb_title, auth_name, sub_title)");
        }
        return $retValue;
    }

    public function get_subject($params){
        $retValue = array();

        $qryWhere = "";
        if(isset($params['sub_id']) && $params['sub_id'] > 0){
            $qryWhere = " AND sub_id = '".$this->func->dbStr(trim($params['sub_id']))."'";
        }
        if(isset($params['sub_title']) && !empty($params['sub_title']) ){
            $qryWhere = " AND sub_title LIKE '%".$this->func->dbStr(trim($params['sub_title']))."%'";
        }
        
        $Query = "SELECT * FROM subject WHERE sub_status = '1' ".$qryWhere." ORDER BY sub_orderby ASC LIMIT 0,20";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if(mysqli_num_rows($rs) > 0){
            
            while($rw = mysqli_fetch_object($rs)){
                $retValue[] = array(
                    "sub_id" => strval($rw->sub_id),
                    "sub_title" => strval($rw->sub_title),
                    "sub_orderby" => strval($rw->sub_orderby),
                    "sub_status" => strval($rw->sub_status)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }
    
    public function get_author($params){
        $retValue = array();

        $qryWhere = "";
        if(isset($params['auth_id']) && $params['auth_id'] > 0){
            $qryWhere = " AND auth_id = '".$this->func->dbStr(trim($params['auth_id']))."'";
        }
        if(isset($params['auth_name']) && !empty($params['auth_name']) ){
            $qryWhere = " AND auth_name LIKE '%".$this->func->dbStr(trim($params['auth_name']))."%'";
        }
        
        $Query = "SELECT * FROM author WHERE auth_status = '1' AND auth_type = '0' ".$qryWhere." ORDER BY auth_id ASC LIMIT 0,20";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if(mysqli_num_rows($rs) > 0){
            while($rw = mysqli_fetch_object($rs)){
                $retValue[] = array(
                    "auth_id" => strval($rw->auth_id),
                    "auth_name" => strval($rw->auth_name),
                    "auth_status" => strval($rw->auth_status)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }
    
    public function get_book_title($params){
        $retValue = array();

        $qryWhere = "";
        if(isset($params['lb_title']) && !empty($params['lb_title']) ){
            $qryWhere .= " AND lb_title LIKE '%".$this->func->dbStr(trim($params['lb_title']))."%'";
        }
        
        $Query = "SELECT * FROM library_books WHERE lb_status = '1' ".$qryWhere." ORDER BY lb_id ASC LIMIT 0,20";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if(mysqli_num_rows($rs) > 0){
            while($rw = mysqli_fetch_object($rs)){
                $retValue[] = array(
                    "lb_id" => strval($rw->lb_id),
                    "lb_title" => strval($rw->lb_title)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }
    
    public function get_library_books($params){
        $retValue = array();

        $qryWhere = "WHERE 1 = 1";
        if(isset($params['lb_id']) && $params['lb_id'] > 0){
            $qryWhere .= " AND lb.lb_id = '".$this->func->dbStr(trim($params['lb_id']))."'";
        }
        if(isset($params['sub_id']) && $params['sub_id'] > 0){
            $qryWhere .= " AND lb.sub_id = '".$this->func->dbStr(trim($params['sub_id']))."'";
        }
        if(isset($params['auth_id']) && $params['auth_id'] > 0){
            $qryWhere .= " AND lb.auth_id = '".$this->func->dbStr(trim($params['auth_id']))."'";
        }
        if(isset($params['pub_id']) && $params['pub_id'] > 0){
            $qryWhere .= " AND lb.pub_id = '".$this->func->dbStr(trim($params['pub_id']))."'";
        }
        if(isset($params['lb_title']) && !empty($params['lb_title']) ){
            $qryWhere .= " AND lb.lb_title LIKE '%".$this->func->dbStr(trim($params['lb_title']))."%'";
        }
        
        $Query = "SELECT lb.*, sub.sub_title, auth.auth_name, subauth.auth_name AS subauthor_name, pub.pub_name FROM library_books AS lb LEFT OUTER JOIN subject AS sub ON sub.sub_id = lb.sub_id LEFT OUTER JOIN author AS  auth ON auth.auth_id = lb.auth_id LEFT OUTER JOIN author AS  subauth ON subauth.auth_id = lb.sub_auth_id LEFT OUTER JOIN publisher AS pub ON pub.pub_id = lb.pub_id " . $qryWhere . " ORDER BY lb.sub_id ASC";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if(mysqli_num_rows($rs) > 0){
            $retValue = array("status" => "1", "message" => "Get library books data");
            while($rw = mysqli_fetch_object($rs)){
                $retValue['data'][] = array(
                    "lb_id" => strval($rw->lb_id),
                    "sub_id" => strval($rw->sub_id),
                    "sub_title" => strval($rw->sub_title),
                    "auth_id" => strval($rw->auth_id),
                    "auth_name" => strval($rw->auth_name),
                    "sub_auth_id" => strval($rw->sub_auth_id),
                    "subauthor_name" => strval($rw->subauthor_name),
                    "pub_id" => strval($rw->pub_id),
                    "pub_name" => strval($rw->pub_name),
                    "lb_title" => strval($rw->lb_title),
                    "lb_subtitle" => strval($rw->lb_subtitle),
                    "lb_accno" => strval($rw->lb_accno),
                    "lb_dccno" => strval($rw->lb_dccno),
                    "lb_entrydate" => strval($rw->lb_entrydate),
                    "lb_price" => strval($rw->lb_price),
                    "lb_place" => strval($rw->lb_place),
                    "lb_year" => strval($rw->lb_year),
                    "lb_source" => strval($rw->lb_source),
                    "lb_edition" => strval($rw->lb_edition),
                    "lb_volume" => strval($rw->lb_volume),
                    "lb_page" => strval($rw->lb_page),
                    "lb_series" => strval($rw->lb_series),
                    "lb_language" => strval($rw->lb_language),
                    "lb_isbn" => strval($rw->lb_isbn),
                    "lb_note" => strval($rw->lb_note)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }
    
    public function get_banner($params){
        $retValue = array();
        
        $Query = "SELECT * FROM `banners` WHERE `ban_status` = '1' ORDER BY `ban_order` ASC";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if(mysqli_num_rows($rs) > 0){
            $retValue = array("status" => "1", "message" => "Get banner data");
            while($rw = mysqli_fetch_object($rs)){
                $retValue['data'][] = array(
                    "ban_id" => strval($rw->ban_id),
                    "ban_name" => strval($rw->ban_name),
                    "ban_details" => strval($rw->ban_details),
                    "ban_file" => strval($GLOBALS['siteURL']."files/banners/".$rw->ban_file)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }
    
    public function get_faqs($params){
        $retValue = array();
        
        $Query = "SELECT * FROM `faqs` WHERE `faq_status` = '1' ORDER BY `faq_orderby` ASC";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if(mysqli_num_rows($rs) > 0){
            $retValue = array("status" => "1", "message" => "Get FAQs data");
            while($rw = mysqli_fetch_object($rs)){
                $retValue['data'][] = array(
                    "faq_id" => strval($rw->faq_id),
                    "faq_orderby" => strval($rw->faq_orderby),
                    "faq_question" => strval($rw->faq_question),
                    "faq_answer" => strval($rw->faq_answer)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }
    public function get_social_links($params){
        $retValue = array();
        
        $Query = "SELECT * FROM social_links WHERE sl_status = '1' ORDER BY `si_orderby` ASC";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if(mysqli_num_rows($rs) > 0){
            $retValue = array("status" => "1", "message" => "Get Social Network link data");
            while($rw = mysqli_fetch_object($rs)){
                $retValue['data'][] = array(
                    "sl_id" => strval($rw->sl_id),
                    "sl_title" => strval($rw->sl_title),
                    "sl_url" => strval($rw->sl_url),
                    "sl_icon" => strval($rw->sl_icon)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }
    
    public function get_page_content($params){
        $retValue = array();
        
        $qryWhere = "";
        if(isset($params['cnt_id']) && $params['cnt_id'] > 0){
        $Query = "SELECT * FROM contents WHERE cnt_id = '".$this->func->dbStr(trim($params['cnt_id']))."' ";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if(mysqli_num_rows($rs) > 0){
            $retValue = array("status" => "1", "message" => "Get content data");
            while($rw = mysqli_fetch_object($rs)){
                $retValue['data'][] = array(
                    "cnt_id" => strval($rw->cnt_id),
                    "cnt_title" => strval($rw->cnt_title),
                    "cnt_heading" => strval($rw->cnt_heading),
                    "cnt_keywords" => strval($rw->cnt_keywords),
                    "cnt_metades" => strval($rw->cnt_metades),
                    "cnt_details" => strval($rw->cnt_details)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
    } else {
        $retValue = array("status" => "0", "message" => "Please select the required parameters");
    }
        return $retValue;
    }
    
    public function get_page_widgets($params){
        $retValue = array();
        
        $qryWhere = "";
        if( (isset($params['cnt_id']) && $params['cnt_id'] > 0) && (isset($_REQUEST['wid_params']) && !empty($_REQUEST['wid_params']))){
        $Query = "SELECT * FROM widgets WHERE cnt_id = '".$this->func->dbStr(trim($params['cnt_id']))."' AND wid_params = '".$this->func->dbStr(trim($params['wid_params']))."' ";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if(mysqli_num_rows($rs) > 0){
            $retValue = array("status" => "1", "message" => "Get widgets data");
            while($rw = mysqli_fetch_object($rs)){
                $retValue['data'][] = array(
                    "wid_id" => strval($rw->wid_id),
                    "cnt_id" => strval($rw->cnt_id),
                    "wid_params" => strval($rw->wid_params),
                    "wid_heading" => strval($rw->wid_heading),
                    "wid_details" => strval($rw->wid_details),
                    "wid_img" => strval($GLOBALS['siteURL']."files/widgets/".$rw->wid_img)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
    } else {
        $retValue = array("status" => "0", "message" => "Please select the required parameters");
    }
        return $retValue;
    }
}
?>