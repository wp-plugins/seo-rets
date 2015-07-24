<?php
$sr = $seo_rets_plugin;

if (!defined("DONOTCACHEPAGE")) {//support for WP Super Cache
    define("DONOTCACHEPAGE",true);
}


if ( !$sr->api_key ) return '<p class="sr-error">You must activate the SEO RETS plugin.</p>';
if ( !$sr->is_type_valid($params['type']) ) return '<p class="sr-error">Shortcode parameter "type" not set or invalid.</p>';

$validation=array_key_exists('object', $params);
if (!$validation) {
    return '<p class="sr-error">Shortcode parameter "object" not set or invalid.</p>';
}

$type = $params['type'];
$object = $params['object'];
unset($params['type']);
unset($params['object']);



$perpage = isset($params['perpage']) ? ((intval($params['perpage']) == 0 ) ? 10 : intval($params['perpage'])) : 10;
$only    = isset($params['onlymylistings']) && strtolower($params['onlymylistings']) != "no";
$page    = ($wp_query->query_vars['page'] == 0) ? 1 : $wp_query->query_vars['page'];
$order   = isset($params['order']) ? explode(":", $params['order']) : array();
$widgetize = isset($params['widgetize']) && strtolower($params['widgetize']) != "no";

$cond=array(
    'type'=>$type,
    'object'=>$object,
    'conditions'=>$params
);

if ($only && count($prioritization) > 0) {
    array_pop($query);
}

$request = $this->api_request("get_list", $cond);
if ($request->count==0){
    echo 'Sorry, but there is no data for your shortcode';
    exit;
}


recoveringListData($request);

usort($request->result,'compareStrings');

$count = $request->count;

//

include($sr->server_plugin_dir . "/templates/list.php");

function compareStrings($a,$b){

    $a=trim($a);
    $b=trim($b);
    preg_match('/\s/', $a, $matches, PREG_OFFSET_CAPTURE);

    $aLenHalf=round(strlen($a)/2);

    if (empty($matches)|| ($matches[0][1]>$aLenHalf)){
        $a=preg_replace('/^[^A-Za-z\s]+/','',$a);
    }
    else{
        $a=preg_replace('/^[^A-Za-z\s]+.*?\s/','',$a);
    }

    preg_match('/\s/', $b, $matchesB, PREG_OFFSET_CAPTURE);
    $bLenHalf=round(strlen($b)/2);

    if (empty($matchesB)|| ($matchesB[0][1]>$bLenHalf)){
        $b=preg_replace('/^[^A-Za-z\s]+/','',$b);
    }
    else{
        $b=preg_replace('/^[^A-Za-z\s]+.*?\s/','',$b);
    }

    $res=strcasecmp ( $a,$b );
    if ($res==0) {
        return 0;
    }
    elseif($res>0){
        return 1;
    }
    else{
        return -1;
    }

}

function recoveringListData(&$data){

    $z=0;
    foreach ($data->result as $key=>$value){
        $data->result[$key]=trim($value);

        $a=trim($value);
        if ($a=='14th & Coal Un 01'){
            $y=1;
        }
        else{
            $y=0;
        }
        preg_match('/\s/', $a, $matches, PREG_OFFSET_CAPTURE);

        $aLenHalf=round(strlen($a)/2);

        if (empty($matches)|| ($matches[0][1]>$aLenHalf)){
            $a=preg_replace('/^[^A-Za-z\s]+/','',$a);
        }
        else{
            $a=preg_replace('/^[^A-Za-z\s]+.*?\s/','',$a);
        }
        $a=trim($a);
        $firstSymbolValid=!preg_match('/[A-Za-z]/',$a[1]);
        if (empty($a) ||!preg_match('/[a-zA-Z]/',$a) || strlen($a)<2 || strlen($a)>50 || $firstSymbolValid){
            unset($data->result[$key]);
        }
    }

}

