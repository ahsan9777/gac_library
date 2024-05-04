<?php
include("../lib/openCon.php");
include("../lib/functions.php");
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {

        case 'user_full_name':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE utype_id  IN (4) AND ( user_fname LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR user_lname LIKE '" . dbStr($_REQUEST['term']) . "%')";
            }
            $Query = "SELECT * FROM `users` " . $where . " ORDER BY user_id  LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'user_id' => strip_tags(html_entity_decode($row->user_id, ENT_QUOTES, 'UTF-8')),
                    'user_full_name' => strip_tags(html_entity_decode($row->user_fname . " " . $row->user_lname, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->user_fname . " " . $row->user_lname, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;
        case 'user_name':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE utype_id  IN (4) AND  user_name LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%'";
            }
            $Query = "SELECT * FROM `users` " . $where . " ORDER BY user_id  LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'user_id' => strip_tags(html_entity_decode(trim(((!empty($row->user_id)) ? $row->user_id : "")), ENT_QUOTES, 'UTF-8')),
                    'user_name' => strip_tags(html_entity_decode(((!empty($row->user_name)) ? $row->user_name : ""), ENT_QUOTES, 'UTF-8')),
                    'user_fname' => strip_tags(html_entity_decode(((!empty($row->user_fname)) ? $row->user_fname : ""), ENT_QUOTES, 'UTF-8')),
                    'user_phone' => strip_tags(html_entity_decode(((!empty($row->user_phone)) ? $row->user_phone : ""), ENT_QUOTES, 'UTF-8')),
                    'user_dob' => strip_tags(html_entity_decode(((!empty($row->user_dob)) ? $row->user_dob : ""), ENT_QUOTES, 'UTF-8')),
                    'user_house_no' => strip_tags(html_entity_decode(((!empty($row->user_house_no)) ? $row->user_house_no : ""), ENT_QUOTES, 'UTF-8')),
                    'user_street' => strip_tags(html_entity_decode(((!empty($row->user_street)) ? $row->user_street : ""), ENT_QUOTES, 'UTF-8')),
                    'user_town' => strip_tags(html_entity_decode(((!empty($row->user_town)) ? $row->user_town : ""), ENT_QUOTES, 'UTF-8')),
                    'user_countrie' => strip_tags(html_entity_decode(((!empty($row->user_countrie)) ? $row->user_countrie : ""), ENT_QUOTES, 'UTF-8')),
                    'user_state' => strip_tags(html_entity_decode(((!empty($row->user_state)) ? $row->user_state : ""), ENT_QUOTES, 'UTF-8')),
                    'user_city' => strip_tags(html_entity_decode(((!empty($row->user_city)) ? $row->user_city : ""), ENT_QUOTES, 'UTF-8')),
                    'user_address' => strip_tags(html_entity_decode(((!empty($row->user_address)) ? $row->user_address : ""), ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode(((!empty($row->user_name)) ? $row->user_name : ""), ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'search_author':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $auth_type = "";
                if (isset($_REQUEST['auth_type']) && $_REQUEST['auth_type'] < 2) {
                    $auth_type = " AND auth_type = '".$_REQUEST['auth_type']."'";
                }
                $where .= " WHERE auth_type = '0' AND auth_name LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' ".$auth_type."";
            }
            $Query = "SELECT * FROM author " . $where . " ORDER BY auth_id LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'auth_id' => strip_tags(html_entity_decode($row->auth_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->auth_name, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'publisher':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE pub_name LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' ";
            }
            $Query = "SELECT * FROM publisher " . $where . " ORDER BY pub_id LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'pub_id' => strip_tags(html_entity_decode($row->pub_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->pub_name, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'search_subject':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE sub_title LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' ";
            }
            $Query = "SELECT * FROM subject " . $where . " ORDER BY sub_id LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'sub_id' => strip_tags(html_entity_decode($row->sub_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->sub_title, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'search_title':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE lb_title LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' ";
            }
            $Query = "SELECT * FROM library_books " . $where . " ORDER BY lb_id LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'lb_id' => strip_tags(html_entity_decode($row->lb_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->lb_title, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;
    }
}
