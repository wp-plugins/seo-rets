<?php

$page_name = "Email Subscribe";
if ($this->template_settings['type'] == "all") {
    wp_cache_set($post_id, array("_wp_page_template" => array($this->template_settings['all-value'])), "post_meta");
} else {
    wp_cache_set($post_id, array("_wp_page_template" => array($this->template_settings['every-values'][$page_name])), "post_meta");
}

if (filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
    $conditions = array();

    if (isset($_GET['conditions'])) {
        foreach ($_GET['conditions'] as $condition) {
            if (isset($condition['value']) && $condition['value']) {
                if (is_array($condition['value'])) {
                    $newcondition['boolopr'] = "OR";
                    $newcondition['conditions'] = array();
                    foreach ($condition['value'] as $value) {
                        $subcondition = array('field' => $condition['field'], 'operator' => $condition['operator'], 'value' => $value);
                        if (isset($condition['loose'])) {
                            $subcondition['loose'] = $condition['loose'];
                        }
                        $newcondition['conditions'][] = $subcondition;
                    }
                    $conditions[] = $newcondition;
                } else {
                    $conditions[] = $condition;
                }
            }
        }

        if (($key = get_option("sr-mailchimptoken")) && ($list = get_option("sr-mailchimplist"))) {
            require_once($this->server_plugin_dir . "/includes/MCAPI.class.php");
            $mailchimp = new MCAPI($key, true);
            $mailchimp->listSubscribe($list, $_GET['email'], array("NAME" => $_GET['sr-name'], "OPTIN_IP" => $_SERVER['REMOTE_ADDR'], "OPTIN_TIME" => date("Y-m-d H:i:s")), 'html', false, true);
        }

        $this->lead_alert("{$_GET['sr-name']} <{$_GET['email']}>", "Listing Alerts");

        $request = $this->api_request("add_subscriber", array(
            'type' => $_GET['type'],
            'email' => $_GET['email'],
            'name' => $_GET['sr-name'],
            
            'request' => array(
                'boolopr' => 'AND',
                'conditions' => $conditions
            )
        ));

        if ($request->error == 0) {
            $currentPage->post_content = '<p>You have been successfully added to our email list.</p>';
            $currentPage->post_title = 'Success';
        } elseif ($request->error == 125) {
            $currentPage->post_content = '<p>Please go back and try again, security code was incorrect.</p>';
            $currentPage->post_title = 'Failure';
        } else {
            $currentPage->post_content = '<p>Please try again later, something went wrong.</p>';
            $currentPage->post_title = 'Failure';
        }
    } else {
        $currentPage->post_content = '<p>It looks like there is a problem with your permalink structure. Please set to "/%postname%".</p>';
        $currentPage->post_title = 'Error';
    }
} else {
    $currentPage->post_content = '<p>Sorry, but the email address you entered is invalid.</p>';
    $currentPage->post_title = 'Error';
}
