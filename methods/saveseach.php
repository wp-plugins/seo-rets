<?php

if (!$this->api_key) {
    $currentPage->post_title = 'Search Results';
    $currentPage->post_content = 'You must activate the SEO RETS plugin.';
} else {

    $page_name = "User Favorites";
    if ($this->template_settings['type'] == "all") {
        wp_cache_set($post_id, array("_wp_page_template" => array($this->template_settings['all-value'])), "post_meta");
    } else {
        wp_cache_set($post_id, array("_wp_page_template" => array($this->template_settings['every-values'][$page_name])), "post_meta");
    }

    $users = get_option('sr_users');
    $index = $this->get_session_data('user_index');

    function sr_save_search($users, $index)
    {
//        $parts = explode(",", $_GET['add'], 2);


        date_default_timezone_set("US/Central");

        $users[$index]['savesearch'][] = array(
            'base64link' => $_GET['add'],
            'add_time' => date("Y-m-d H:i:s")
        );


        update_option('sr_users', $users);
    }

    if (isset($_GET['add'])) {
        if ($index !== false) {

            sr_save_search($users, $index);
            header("Location: " . get_bloginfo('url') . "/sr-search-fav");
            exit;
        } elseif (!$this->new_session) {//Only add later if we are sure that they support cookies
            $this->set_session_data('add_later', $_GET['add']);
        }
    }


    if ($index !== false) {

        $user = $users[$index];



        if (isset($_GET['remove'])) {

            unset($users[$index]['savesearch'][intval($_GET['remove'])]);

            $users[$index]['savesearch'] = array_values($users[$index]['savesearch']);

            update_option('sr_users', $users);
            header("Location: " . get_bloginfo('url') . "/sr-search-fav");


            exit;
        }

        $currentPage->post_title = $user['name'] . '\'s Saved Search';
        $currentPage->post_content = $this->include_return('methods/includes/savesearch/display.php', get_defined_vars());

    } else {
        header("Location: " . get_bloginfo('url') . "/sr-login");
        exit;
    }

}
