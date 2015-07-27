<?php

if ( !$this->api_key ) {
	$currentPage->post_title = 'Search Results';
	$currentPage->post_content = 'You must activate the SEO RETS plugin.';
} else {

	$page_name = "User Login";
	if ( $this->template_settings['type'] == "all" ) {
		wp_cache_set($post_id, array("_wp_page_template" => array($this->template_settings['all-value'])), "post_meta");
	} else {
		wp_cache_set($post_id, array("_wp_page_template" => array($this->template_settings['every-values'][$page_name])), "post_meta");
	}

	function sr_login($users) {
		if ( $users ) {
			foreach ( $users as $index => $user ) {
				$password = md5($user['salt'] . $_POST['password']);
				$user['index'] = $index;
				if ( $_POST['email'] === $user['email'] && $user['password'] === $password /*&& !isset($user['verify']) FIXME */ ) return $user;
			}
		}
		return false;
	}

	$user_index = $this->get_session_data('user_index');


	if ( $user_index !== false ) {

		header("Location: " . get_bloginfo('url') . "/sr-favorites");
		
		exit;

	} else {
		if ( isset($_POST['submit']) ) {
		
			$errors = array();
		
			$users = get_option('sr_users');
		
			$user = sr_login($users);
		
			if ( !$user ) {
				$errors[] = 'Wrong username or password';
			}
		
			if ( count($errors) == 0 ) {
				$this->set_session_data('user_index', $user['index']);
				
				$add = $this->get_session_data('add_later');
				
				if ( $add ) {
					$this->set_session_data('add_later', false);
					header("Location: " . get_bloginfo('url') . "/sr-favorites?add=" . $add);
				} else {
					header("Location: " . get_bloginfo('url') . "/sr-favorites");
				}
				exit;
			} else {
				$currentPage->post_title = 'Login';
				$currentPage->post_content = $this->include_return('methods/includes/login/form.php', get_defined_vars());
			} 
		} else {
			$currentPage->post_title = 'Login';
			$currentPage->post_content = $this->include_return('methods/includes/login/form.php', get_defined_vars());
		}
	}

}