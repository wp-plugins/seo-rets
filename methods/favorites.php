<?php

if ( !$this->api_key ) {
	$currentPage->post_title = 'Search Results';
	$currentPage->post_content = 'You must activate the SEO RETS plugin.';
} else {
	
	$page_name = "User Favorites";
	if ( $this->template_settings['type'] == "all" ) {
		wp_cache_set($post_id, array("_wp_page_template" => array($this->template_settings['all-value'])), "post_meta");
	} else {
		wp_cache_set($post_id, array("_wp_page_template" => array($this->template_settings['every-values'][$page_name])), "post_meta");
	}

	$users = get_option('sr_users');
	$index = $this->get_session_data('user_index');

	function sr_add_favorite($users, $index) {
		$parts = explode(",", $_GET['add'], 2);
		
		
		foreach ( $users[$index]['favorites'] as $favorite ) {
			if ( $favorite['mls'] == $parts[0] ) return false;
		}
		
		$users[$index]['favorites'][] = array(
			'mls' => $parts[0],
			'type' => $parts[1]
		);
		
		update_option('sr_users', $users);
	}

	if ( isset($_GET['add']) ) {
		if ( $index !== false ) {
			$parts = explode(",", $_GET['add'], 2);
			
			$request = $this->api_request('get_listings', array(
				'type' => $parts[1],
				'query' => array(
					'boolopr' => 'AND',
					'conditions' => array(
						array(
							'field' => 'mls_id',
							'operator' => '=',
							'value' => $parts[0]
						)
					)
				),
				'limit' => array(
					'range' => 1,
					'offset' => 0
				)
			));
			
			$url = $this->listing_to_url($request->result[0], $parts[1]);
			SEO_RETS_Plugin::lead_alert("{$users[$index]['name']} <{$users[$index]['email']}> listing: " . site_url() . $url, "Favorites");
			sr_add_favorite($users, $index);
			header("Location: " . get_bloginfo('url') . "/sr-favorites");
			exit;
		} elseif (!$this->new_session) {//Only add later if we are sure that they support cookies
			$this->set_session_data('add_later', $_GET['add']);
		}
	}


	if ( $index !== false ) {
		
		$user = $users[$index];
		
		
		if ( isset($_GET['remove']) ) {
			
			unset($users[$index]['favorites'][intval($_GET['remove'])]);
			
			$users[$index]['favorites'] = array_values($users[$index]['favorites']);
			
			update_option('sr_users', $users);
			header("Location: " . get_bloginfo('url') . "/sr-favorites");
			
			
			exit;
		}
		
		$currentPage->post_title = $user['name'] . '\'s Favorites';
		$currentPage->post_content = $this->include_return('methods/includes/favorites/display.php', get_defined_vars());
		
	} else {
		header("Location: " . get_bloginfo('url') . "/sr-login");
		exit;
	}

}
