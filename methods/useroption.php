<?php

if (!$this->api_key) {
    $currentPage->post_title = 'User Cabinet';
    $currentPage->post_content = 'You must activate the SEO RETS plugin.';
} else {

    $page_name = "User Cabinet";
    if ($this->template_settings['type'] == "all") {
        wp_cache_set($post_id, array("_wp_page_template" => array($this->template_settings['all-value'])), "post_meta");
    } else {
        wp_cache_set($post_id, array("_wp_page_template" => array($this->template_settings['every-values'][$page_name])), "post_meta");
    }

    $users = get_option('sr_users');
    $index = $this->get_session_data('user_index');
    function sr_save_search($users, $index)
    {
        date_default_timezone_set("US/Central");
        $users[$index]['u_mobile'] = $_GET['mobile'];
        $users[$index]['u_phone'] = $_GET['phone'];
        $users[$index]['al_1'] = $_GET['al_1'];
        $users[$index]['al_2'] = $_GET['al_2'];
        $users[$index]['u_city'] = $_GET['u_city'];
        $users[$index]['u_state'] = $_GET['u_state'];
        $users[$index]['u_zip'] = $_GET['u_zip'];
        $users[$index]['name'] = $_GET['u_name'];
        $users[$index]['full_name'] = $_GET['full_name'];
        update_option('sr_users', $users);
    }

    if (isset($_GET['user-save'])) {
        if ($index !== false) {
            sr_save_search($users, $index);
            header("Location: " . get_bloginfo('url') . "/sr-user");
            exit;
        } elseif (!$this->new_session) {//Only add later if we are sure that they support cookies
            $this->set_session_data('add_later', $_GET['add']);
        }
    }
    if ($index !== false) {

        $user = $users[$index];
        $currentPage->post_title = $user['name'] . '\'s User Cabinet';
        $currentPage->post_content = $this->include_return('methods/includes/user/display.php', get_defined_vars());

    } else {
        header("Location: " . get_bloginfo('url') . "/sr-login");
        exit;
    }

}
