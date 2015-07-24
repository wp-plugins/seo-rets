<?php

$text_settings = get_option('sr-plugintext');
				
if ( $text_settings ) {
	$text_settings_put = $text_settings;
} else {
	$text_settings_put = $this->text_defaults;
}

?>
<p><?php eval("?>" . $text_settings_put['login']) ?></p>
<?php if ( isset($errors) && count($errors) > 0 ): foreach ( $errors as $error ): ?>
<p style="color: red;">* <?php echo $error?></p>
<?php endforeach; endif; ?>
<form action="" method="post">
	<table>
		<tr>
			<td>Email:</td>
			<td><input type="text" name="email" value="<?php echo empty($_POST['email'])?'':htmlentities($_POST['email'])?>" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" name="password" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" value="Login" /></td>
		</tr>

	</table>
</form>
<br />
<p><a href="<?php echo get_bloginfo('url')?>/sr-signup">Sign Up</a> | <a href="<?php echo get_bloginfo('url')?>/sr-forgot">Forgot Password</a></p>

