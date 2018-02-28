<?php


/*-----------------------------------------------------------------------------------*/
# Add Panel Page
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_menu', 'tie_insta_lite_add_admin' );
function tie_insta_lite_add_admin() {

	$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

	add_menu_page( TIEINSTA_LITE_PLUGIN_NAME.' '. esc_html__( 'Settings', 'tieinsta_lite' ), TIEINSTA_LITE_PLUGIN_NAME, 'install_plugins', 'instanowlite', 'tie_insta_lite_options', ''  );
	add_submenu_page( 'instanowlite', TIEINSTA_LITE_PLUGIN_NAME.' '.esc_html__( 'Settings', 'tieinsta_lite' ), esc_html__( 'Settings', 'tieinsta_lite' ), 'install_plugins', 'instanowlite', 'tie_insta_lite_options' );
	add_submenu_page( 'instanowlite', TIEINSTA_LITE_PLUGIN_NAME.' '.esc_html__( 'PRO Features', 'tieinsta_lite' ), '<span style="color:#00aded">'.esc_html__( 'PRO Features', 'tieinsta_lite' ). '<span>', 'install_plugins', 'instanow-pro', 'tie_insta_lite_pro_features' );

	if( isset( $_REQUEST['action'] ) && $current_page == 'instanowlite' ){

		# Save settings ----------
		if( 'save' == $_REQUEST['action'] ) {

			$tie_instagramy['css'] = htmlspecialchars(stripslashes( $_REQUEST['css'] ) );

			update_option( 'tie_instagramy', $tie_instagramy);

			header( 'Location: admin.php?page=instanowlite&saved=true' );
			die;
		}

		# Get the Access Token ----------
		elseif( 'authorize-instanowlite' == $_REQUEST['action'] ){

			$client_id     = $_REQUEST['client_id'];
			$client_secret = $_REQUEST['client_secret'];
			$current_page  = urlencode ( admin_url( 'admin.php?page=instanowlite&service=authorize' ) );

			set_transient( 'instanowlite_client_id',     $client_id, HOUR_IN_SECONDS );
			set_transient( 'instanowlite_client_secret', $client_secret, HOUR_IN_SECONDS );

			$url = "https://api.instagram.com/oauth/authorize/?client_id=$client_id&redirect_uri=$current_page&response_type=code&scope=basic";

			header( 'Location:'. $url );
		}
	}
}



/*-----------------------------------------------------------------------------------*/
# Pro Features Page
/*-----------------------------------------------------------------------------------*/
function tie_insta_lite_pro_features(){ ?>

	<div class="wrap">
		<h1><?php esc_html_e( 'Upgrade to InstaNOW PRO', 'tieinsta_lite' ) ?></h1>
		<a class="tie-insta-pro-uprade" href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><?php esc_html_e( 'Upgrade NOW', 'tieinsta_lite' ) ?></a>

		<div class="clear"></div>
		<div id="poststuff">
			<ul class="tie-insta-lite-pro-features">
				<li>
					<a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><img src="<?php echo plugins_url( 'assets/images/pro-unlimited.jpg', __FILE__) ?>" alt="" /></a>
					<h3><a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><?php esc_html_e( 'Show Unlimited Photos.', 'tieinsta_lite' ) ?></a></h3>
				</li>
				<li>
					<a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><img src="<?php echo plugins_url( 'assets/images/pro-lightbox.jpg', __FILE__) ?>" alt="" /></a>
					<h3><a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><?php esc_html_e( 'Open photos in an incredible lightbox.', 'tieinsta_lite' ) ?></a></h3>
				</li>
				<li>
					<a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><img src="<?php echo plugins_url( 'assets/images/pro-skins.jpg', __FILE__) ?>" alt="" /></a>
					<h3><a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><?php esc_html_e( 'More Skins options.', 'tieinsta_lite' ) ?></a></h3>
				</li>
				<li>
					<a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><img src="<?php echo plugins_url( 'assets/images/pro-layouts.jpg', __FILE__) ?>" alt="" /></a>
					<h3><a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><?php esc_html_e( 'More Account Info area layouts.', 'tieinsta_lite' ) ?></a></h3>
				</li>
				<li>
					<a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><img src="<?php echo plugins_url( 'assets/images/pro-visual.jpg', __FILE__) ?>" alt="" /></a>
					<h3><a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><?php esc_html_e( 'Compatibility with the Visual Composer plugin.', 'tieinsta_lite' ) ?></a></h3>
				</li>
				<li>
					<a href="http://plugins.tielabs.com/instanow/instanow-load-more-demo/" target="_blank"><img src="http://plugins.tielabs.com/images/insta-load-more.gif" alt="" /></a>
					<h3><a href="http://plugins.tielabs.com/instanow/instanow-load-more-demo/" target="_blank"><?php esc_html_e( 'Separate the feed to pages via Load More option.', 'tieinsta_lite' ) ?></a></h3>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
	</div>
	<?php
}



/*-----------------------------------------------------------------------------------*/
# Admin Panel
/*-----------------------------------------------------------------------------------*/
function tie_insta_lite_options() {

	$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

	$pro_label = '<span class="tie-insta-pro-only"><a href="'. TIEINSTA_LITE_PRO_URL .'" target="_blank">'. esc_html__( 'PRO ONLY', 'tieinsta_lite' ) .'</a>';

	if( isset( $_REQUEST['service'] ) && 'authorize' == $_REQUEST['service'] && $current_page == 'instanowlite' ){

		delete_option( 'instanow_lite_access_token' );

		if( ! empty( $_REQUEST['code'] ) ){
			$code          = $_REQUEST['code'];
			$current_page  = admin_url( 'admin.php?page=instanowlite&service=authorize' ) ;
			$client_id     = get_transient( 'instanowlite_client_id' );
			$client_secret = get_transient( 'instanowlite_client_secret' );

			# http post arguments ----------
			$args = array(
				'body'            => array(
					'client_id'     => $client_id,
					'client_secret' => $client_secret,
					'grant_type'    => 'authorization_code',
					'redirect_uri'  => $current_page,
					'code'          => $code,
				)
			);

			add_filter( 'https_ssl_verify', '__return_false' );
			$response = wp_remote_post( 'https://api.instagram.com/oauth/access_token', $args );
			$response = json_decode( wp_remote_retrieve_body( $response ) );

			if( ! empty( $response->access_token )){
				$access_token = $response->access_token;

				# Set the access token ----------
				update_option( 'instanow_lite_access_token', $access_token );

				delete_transient( 'instanowlite_client_id' );
				delete_transient( 'instanowlite_client_secret' );

				# Set the Self username ----------
				$data_user_serach = tie_insta_lite_remote_get( 'https://api.instagram.com/v1/users/self/?access_token='. $access_token );
				if( ! empty( $data_user_serach['data']['username'] )){
					update_option( 'instanow_lite_username', $data_user_serach['data']['username'] );
				}

				echo "<script type='text/javascript'>window.location='".admin_url( 'admin.php?page=instanowlite' )."';</script>";

				exit;
			}
		}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Instagram App info', 'tieinsta_lite' ) ?></h1>
		<br />
		<form method="post">
			<div id="poststuff">
				<div id="post-body" class="columns-2">
					<div id="post-body-content" class="instanow-lite-authorize-content">
						<div class="postbox">
							<h3 class="hndle"><span><?php esc_html_e( 'Instagram App info', 'tieinsta_lite' ) ?></span></h3>
							<div class="inside">
								<table class="links-table" cellpadding="0">
									<tbody>
										<tr>
											<th scope="row"><label for="client_id"><?php esc_html_e( 'Client ID', 'tieinsta_lite' ) ?></label></th>
											<td><input type="text" name="client_id" id="client_id" value=""></td>
										</tr>
										<tr>
											<th scope="row"><label for="client_secret"><?php esc_html_e( 'Client Secret', 'tieinsta_lite' ) ?></label></th>
											<td><input type="text" name="client_secret" id="client_secret" value=""></td>
										</tr>
									</tbody>
								</table>
								<div class="clear"></div>
							</div>
						</div> <!-- Box end /-->
					</div> <!-- Post Body COntent -->

					<div id="publishing-action">
						<input type="hidden" name="action" value="authorize-instanowlite" />
						<input name="save" type="submit" class="button-large button-primary" id="publish" value="<?php esc_html_e( 'Submit', 'tieinsta_lite' ) ?>">
					</div>
					<div class="clear"></div>

					<div id="instanowlite-docs">
						<h3><?php esc_html_e( 'How to Get Instagram Client ID and Client Secret?', 'tieinsta_lite' ) ?></h3>

						<ol>
							<li><?php printf( esc_html__( 'Go to %1$shttp://instagram.com/developer%2$s. Login to your account or create one.', 'tieinsta_lite' ), '<a href="http://instagram.com/developer" target="_blank">', '</a>' ) ?></li>
							<li><?php printf( esc_html__( 'Click on %1$s Manage Clients %2$s.', 'tieinsta_lite' ), '<strong>', '</strong>' )  ?></li>
							<li><?php esc_html_e( 'If this is the first time you are creating a client, Instagram will ask you a few questions.', 'tieinsta_lite' ) ?>

								<br>
								<img src="<?php echo plugins_url( 'assets/images/docs1.png', __FILE__) ?>" alt="" />

								<p><?php esc_html_e( 'Check all the fields on the web page:', 'tieinsta_lite' ) ?></p>
								<ul>
									<li><?php printf( esc_html__( '%1$s your website %2$s – the URL of your website.', 'tieinsta_lite' ), '<strong>', '</strong>' ) ?></li>
									<li><?php printf( esc_html__( '%1$s phone number %2$s – any phone number.', 'tieinsta_lite' ), '<strong>', '</strong>' ) ?></li>
									<li><?php printf( esc_html__( '%1$s what do you want to build with API %2$s – any short description.', 'tieinsta_lite' ), '<strong>', '</strong>' ) ?></li>
								</ul>
								<?php printf( sprintf( esc_html__( 'click %1$s Sign Up %2$s, and then click %1$s Manage Clients %2$s again.', 'tieinsta_lite' ), '<strong>', '</strong>' )) ?>
							</li>

							<li><?php printf( esc_html__( 'Click on %1$s Register your application %2$s button.', 'tieinsta_lite' ), '<strong>', '</strong>' ) ?>
								<br>
								<img src="<?php echo plugins_url( 'assets/images/docs2.png', __FILE__) ?>" alt="" />
							</li>

							<li><?php esc_html_e( 'Check the fields on the web page:', 'tieinsta_lite' ) ?>

								<br>
								<img src="<?php echo plugins_url( 'assets/images/docs3.png', __FILE__) ?>" alt="" />

								<ul>
									<li><?php printf( esc_html__( '%1$s application name %2$s – choose any appropriate name, which fits Instagram requirements', 'tieinsta_lite' ), '<strong>', '</strong>' ) ?></li>
									<li><?php printf( esc_html__( '%1$s description %2$s – any short description', 'tieinsta_lite' ), '<strong>', '</strong>' ) ?></li>
									<li><?php printf( esc_html__( '%1$s website URL %2$s – have to be %3$s', 'tieinsta_lite' ), '<strong>', '</strong>', '<a href="'. home_url( '/' ) .'" target="_blank">'.  home_url( '/' ) .'</a>' ) ?></li>
									<li><?php printf( esc_html__( '%1$s valid redirect URIs %2$s – have to be %3$s', 'tieinsta_lite' ), '<strong>', '</strong>', '<a href="'. admin_url( 'admin.php?page=instanowlite&service=authorize' ) .'" target="_blank">'. admin_url( 'admin.php?page=instanowlite&service=authorize' ) .'</a>' ) ?></li>
								</ul>
							</li>
							<li><?php printf( esc_html__( 'Now %1$s confirm your data %2$s and proceed to the next page. Here you can see received Instagram Client ID and Client Secret.', 'tieinsta_lite' ), '<strong>', '</strong>' ) ?></li>
							<li><?php printf( esc_html__( 'Сopy your all-new Instagram Client ID and Client Secret and paste them in the fields above, then Click on %1$s Submit %2$s button.', 'tieinsta_lite' ), '<strong>', '</strong>' ) ?>
								<br>
								<img src="<?php echo plugins_url( 'assets/images/docs4.png', __FILE__) ?>" alt="" />
							</li>
							<li><?php esc_html_e( 'You will connect to Instagram’s servers. Instagram will ask you whether you want to grant the client you will connect to Instagram’s servers. Instagram will ask you whether you want to grant the App access to your account.', 'tieinsta_lite' ) ?></li>
						</ol>
					</div>

				</div><!-- post-body /-->
			</div><!-- poststuff /-->
		</form>
	</div>
	<?php
	}

else{

	if( !empty( $_REQUEST['error'] ) ){
		delete_option( 'instanow_lite_access_token' );
	?>
		<div id="setting-error-settings_updated" class="error settings-error">
			<p><strong><?php esc_html_e( 'Error:', 'tieinsta_lite' ) ?></strong> <?php echo $_REQUEST['error']; ?></p>
			<?php if( !empty( $_REQUEST['error_reason'] )){?><p><strong><?php esc_html_e( 'Error Reason:', 'tieinsta_lite' ) ?></strong> <?php echo $_REQUEST['error_reason']; ?></p><?php }?>
			<?php if( !empty( $_REQUEST['error_description'] )){?><p><strong><?php esc_html_e( 'Error Description:', 'tieinsta_lite' ) ?></strong> <?php echo $_REQUEST['error_description']; ?></p><?php }?>
		</div>

	<?php
	}

	elseif ( isset($_REQUEST['saved']) ){ ?>
		<div id="setting-error-settings_updated" class="updated settings-error"><p><strong><?php esc_html_e( 'Settings saved.', 'tieinsta_lite' ) ?></strong></p></div>
		<?php
	}

	$tieinsta_options = get_option( 'tie_instagramy' );

	$tie_table_class  = 'tie-insta-not-authorized';
	if( get_option( 'instanow_lite_access_token' ) ){
		$tie_table_class = 'tie-insta-authorized';
	}

	$current_page  = admin_url( 'admin.php?page=instanowlite' );
	$authorize_url = admin_url( 'admin.php?page=instanowlite&service=authorize' );

	?>
	<div class="wrap">
		<h1><?php echo TIEINSTA_LITE_PLUGIN_NAME ?>  <small><?php printf( __( 'Instagram Feed plugin for WordPress by <a href="%s" target="_blank">TieLabs</a>', 'tieinsta_lite' ), 'http://tielabs.com' ) ?></small></h1>
		<form method="post">
			<div id="poststuff">
				<div id="post-body" class="columns-2">
					<div id="post-body-content" class="tieinsta-content">
						<div class="postbox tie-insta-authorize-block">
							<table class="links-table <?php echo $tie_table_class ?>" cellpadding="0">
								<tbody>
									<tr>
										<td>
										<?php if( get_option( 'instanow_lite_access_token' ) ){ ?>
											<h3><?php esc_html_e( 'Your account has successfully been authorized', 'tieinsta_lite' ); ?></h3>
											<div class="inside">
												<p><?php esc_html_e( 'Feeds not displaying? There might be a problem with your current Authorization. Use the button below to try re-authorizing with Instagram.', 'tieinsta_lite' ); ?></p>
												<a class="tieinsta-get-api-key tieinsta-get-api-key-gray" href="<?php echo $authorize_url ?>"><span></span><?php esc_html_e( 'Re-Authorize with Instagram', 'tieinsta_lite' ) ?></a>
											</div>
										<?php
										}
										else{
										?>
											<h3><?php esc_html_e( 'Account not yet Authorized', 'tieinsta_lite' ); ?></h3>
											<div class="inside">
												<p><?php esc_html_e( 'Use the button below to begin the Authorization process. You will be redirected to Instagram to sign in and authorize this plugin. Once you authorize the plugin, you will be redirected to this page.', 'tieinsta_lite' ); ?></p>
												<a class="tieinsta-get-api-key" href="<?php echo $authorize_url ?>"><span></span><?php esc_html_e( 'Authorize with Instagram', 'tieinsta_lite' ) ?></a>
											</div>
										<?php } ?>
										</td>
										<th scope="row">
											<span class="dashicons dashicons-lock"></span>
											<span class="dashicons dashicons-unlock"></span>
										</th>
									</tr>
								</tbody>
							</table>
							<div class="clear"></div>
						</div>

						<div class="postbox">
							<h3 class="hndle"><span><?php esc_html_e( 'General Settings', 'tieinsta_lite' ) ?></span></h3>
							<div class="inside">
								<table class="links-table" cellpadding="0">
									<tbody>
										<tr>
											<th scope="row"><label for="css"><?php esc_html_e( 'Custom CSS', 'tieinsta_lite' ) ?></label></th>
											<td>
												<textarea name="css" rows="10" cols="50" id="css" class="large-text code"><?php if( !empty( $tieinsta_options['css'] ) ) echo htmlspecialchars_decode( $tieinsta_options['css'] ); ?></textarea>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="clear"></div>
							</div>
						</div>

					<?php
						$load_ilightbox = apply_filters( 'tie_instagram_force_avoid_ilightbox', true );
						if( true === $load_ilightbox ) : ?>

						<div class="postbox">
							<h3 class="hndle"><span><?php esc_html_e( 'LightBox Settings', 'tieinsta_lite' ) ?> <?php echo $pro_label ?></span></h3>
							<div class="inside">
								<table class="links-table" cellpadding="0">
									<tbody>
										<tr>
											<th scope="row"><label for="lightbox_skin"><?php esc_html_e( 'Skin', 'tieinsta_lite' ) ?></label></th>
											<td>
												<select name="lightbox_skin" id="lightbox_skin" disabled="disabled">
													<?php
														$lightbox_skins = array( 'dark', 'light', 'smooth', 'metro-black', 'metro-white', 'mac' );
														foreach ( $lightbox_skins as $skin ){ ?>
															<option><?php echo $skin ?></option>
															<?php
														}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="lightbox_thumbs"><?php esc_html_e( 'Thumbnail Position', 'tieinsta_lite' ) ?></label> </th>
											<td>
												<select name="lightbox_thumbs" id="lightbox_thumbs" disabled="disabled">
													<option><?php esc_html_e( 'Vertical', 'tieinsta_lite' ) ?></option>
													<option><?php esc_html_e( 'Horizontal', 'tieinsta_lite' ) ?></option>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="lightbox_arrows"><?php esc_html_e( 'Show Arrows', 'tieinsta_lite' ) ?></label> </th>
											<td>
												<input name="lightbox_arrows" disabled="disabled" value="true" type="checkbox" />
											</td>
										</tr>
									</tbody>
								</table>
								<div class="clear"></div>
							</div>
						</div>
						<?php endif; ?>

						<div id="publishing-action">
							<input type="hidden" name="action" value="save" />
							<input name="save" type="submit" class="button-large button-primary" id="publish" value="<?php esc_html_e( 'Save', 'tieinsta_lite' ) ?>">
						</div>
						<div class="clear"></div>

					</div> <!-- Post Body COntent -->

					<div id="postbox-container-1" class="postbox-container">

						<div class="postbox tie-insta-block">
							<span class="dashicons dashicons-awards"></span>
							<strong><?php esc_html_e( 'Upgrade to InstaNOW PRO', 'tieinsta_lite' ) ?></strong>
							<h4><?php esc_html_e( 'Upgrade to InstaNOW PRO and get a lot of features:', 'tieinsta_lite' ) ?></h4>
							<ul class="tieinsta-lite-features">
								<li><?php esc_html_e( 'Show Unlimited Photos.', 'tieinsta_lite' ) ?></li>
								<li><?php esc_html_e( 'Show Photos from any public account.', 'tieinsta_lite' ) ?></li>
								<li><?php esc_html_e( 'Show Photos from any hashtag.', 'tieinsta_lite' ) ?></li>
								<li><a href="http://plugins.tielabs.com/instanow/instanow-load-more-demo/" rel="nofollow"><?php esc_html_e( 'Separate the feed to pages via Load More option.', 'tieinsta_lite' ) ?></a></li>
								<li><?php esc_html_e( 'Open photos in an incredible lightbox.', 'tieinsta_lite' ) ?></li>
								<li><?php esc_html_e( 'More Skins options.', 'tieinsta_lite' ) ?></li>
								<li><?php esc_html_e( 'Faster Support.', 'tieinsta_lite' ) ?></li>
								<li><?php esc_html_e( 'More Account Info area layouts.', 'tieinsta_lite' ) ?></li>
								<li><?php esc_html_e( 'Compatibility with the Visual Composer plugin.', 'tieinsta_lite' ) ?></li>
							</ul>

							<div class="clear"></div>
							<a class="tie-insta-pro-uprade" href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><?php esc_html_e( 'Upgrade NOW', 'tieinsta_lite' ) ?></a>
						</div>

						<div class="postbox tie-insta-block tie-insta-rate">
							<span class="dashicons dashicons-heart"></span>
							<strong><?php esc_html_e( 'Enjoying InstaNOW Lite?', 'tieinsta_lite' ) ?></strong>
							<p><?php printf( __( 'If you like our product please <a href="%s" target="_blank">Leave us a positive review</a>, that will help us a lot on improving our items and the support as well … A huge thank you from TieLabs in advance!', 'tieinsta_lite' ), 'https://wordpress.org/support/view/plugin-reviews/instanow-lite' ) ?></p>
							<ul>
								<li><a href="<?php echo TIEINSTA_LITE_PRO_URL ?>" target="_blank"><?php esc_html_e( '- Upgrade to the PRO version', 'tieinsta_lite' ) ?></a></li>
								<li><a href="http://themeforest.net/user/tielabs?ref=tielabs&utm_source=InstaNow-Lite&utm_medium=link&utm_campaign=dashboard-links" target="_blank"><?php esc_html_e( '- More Themes & plugins', 'tieinsta_lite' ) ?></a></li>
								<li><?php printf( __( '- Follow us on <a href="%1$s" target="_blank">Twitter</a> or <a href="%2$s" target="_blank">Facebook</a>.', 'tieinsta_lite' ), 'http://twitter.com/tielabs', 'https://www.facebook.com/tielabs' ) ?></li>
							</ul>
							<div class="clear"></div>
						</div>

					</div><!-- postbox-container /-->

				</div><!-- post-body /-->
			</div><!-- poststuff /-->
		</form>
	</div>
	<?php
	}
}


/*-----------------------------------------------------------------------------------*/
# Widget
/*-----------------------------------------------------------------------------------*/
add_action( 'widgets_init', 'tie_insta_lite_widget_box' );
function tie_insta_lite_widget_box() {
	register_widget( 'tie_insta_lite_widget' );
}
class tie_insta_lite_widget extends WP_Widget {

	function tie_insta_lite_widget() {
		$widget_ops  = array( 'classname' => 'tie_insta-widget', 'description' => esc_html__( 'Instagram Feed', 'tieinsta_lite' ));
		$control_ops = array( 'id_base' => 'tie_insta-widget' );
		parent::__construct( 'tie_insta-widget',  TIEINSTA_LITE_PLUGIN_NAME , $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		$instance['title'] = ! empty( $instance['title'] ) ? $instance['title'] : '';
		extract( $args );

		if( empty( $instance['box_only'] )){
			echo $before_widget . $before_title .  $instance['title'] . $after_title;
		}

		tie_insta_lite_media( $instance );

		if( empty( $instance['box_only'] )){
			echo $after_widget;
		}
	}


	function update( $new_instance, $instance ) {
		$instance['title']                 =  $new_instance['title'];
		$instance['box_only']              =  $new_instance['box_only'];
		$instance['instagram_logo']        =  $new_instance['instagram_logo'];
		$instance['new_window']            =  $new_instance['new_window'];
		$instance['nofollow']              =  $new_instance['nofollow'];
		$instance['credit']                =  $new_instance['credit'];
		$instance['hashtag_info']          =  $new_instance['hashtag_info'];
		$instance['account_info']          =  $new_instance['account_info'];
		$instance['account_info_position'] =  $new_instance['account_info_position'];
		$instance['full_name']             =  $new_instance['full_name'];
		$instance['website']               =  $new_instance['website'];
		$instance['bio']                   =  $new_instance['bio'];
		$instance['stats']                 =  $new_instance['stats'];
		$instance['avatar_shape']          =  $new_instance['avatar_shape'];
		$instance['avatar_size']           =  $new_instance['avatar_size'];
		$instance['media_number']          =  $new_instance['media_number'];
		$instance['link']                  =  $new_instance['link'];
		$instance['media_layout']          =  $new_instance['media_layout'];
		$instance['columns_number']        =  $new_instance['columns_number'];
		$instance['flat']                  =  $new_instance['flat'];
		$instance['slider_speed']          =  $new_instance['slider_speed'];
		$instance['slider_effect']         =  $new_instance['slider_effect'];
		$instance['comments_likes']        =  $new_instance['comments_likes'];

		delete_transient( 'instanow_lite_data' );

		return $instance;
	}


	function form( $instance ) {
		$defaults = array(
			'title'                 => esc_html__( 'Instagram', 'tieinsta_lite' ),
			'box_only'              => false,
			'box_style'             => 'default',
			'instagram_logo'        => 'true',
			'new_window'            => 'true',
			'nofollow'              => 'true',
			'credit'                => 'true',
			'account_info'          => 'true',
			'account_info_position' => 'top',
			'account_info_layout'   => 1,
			'full_name'             => false,
			'website'               => false,
			'bio'                   => 'true',
			'stats'                 => 'true',
			'avatar_shape'          => 'round',
			'avatar_size'           => 70,
			'media_number'          => 9,
			'link'                  => 'page',
			'media_layout'          => 'grid',
			'columns_number'        => 3,
			'slider_speed'          => 3000,
			'slider_effect'         => 'scrollHorz',
			'comments_likes'        => 'true',
			'flat'                  => false,
		);
		$instance  = wp_parse_args( (array) $instance, $defaults );

		$widget_id = $this->get_field_id( 'widget_id' ).'-container';
		$pro_label = '<span class="tie-insta-pro-only"><a href="'. TIEINSTA_LITE_PRO_URL .'" target="_blank">'. esc_html__( 'PRO ONLY', 'tieinsta_lite' ) .'</a>';

		?>

		<script type="text/javascript">
			jQuery(document).ready(function($) {
				var selected_item = jQuery("select[name='<?php echo $this->get_field_name( 'media_layout' ); ?>'] option:selected").val();
				if( selected_item == 'grid' )   jQuery( '#tie-grid-settings-<?php echo $this->get_field_id( 'media_layout' ); ?>' ).show();
				if( selected_item == 'slider' ) jQuery( '#tie-slider-settings-<?php echo $this->get_field_id( 'media_layout' ); ?>' ).show();
			});
		</script>

		<div id="<?php echo $widget_id ?>">
			<p></p>

			<div class="tieinsta-widget-content" style="display:block;">

				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Widget Title', 'tieinsta_lite' ) ?> </label>
					<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( ! empty($instance['title']) ) echo $instance['title']; ?>" class="widefat" type="text" />
				</p>

				<div class="tieinsta-widget-option">
					<label class="tieinsta-widget-option-label" for="<?php echo $this->get_field_id( 'box_style' ); ?>"><?php esc_html_e( 'Widget Skin', 'tieinsta_lite' ) ?></label>

					<div class="tieinsta-widget-option-options">
						<label>
							<input name="<?php echo $this->get_field_name( 'box_style' ); ?>" type="radio" value="default" <?php checked( $instance['box_style'], 'default' ) ?>>
							<?php esc_html_e( 'Default Skin', 'tieinsta_lite' ) ?>
						</label>
						<label>
							<input disabled="disabled" type="radio">
							<?php esc_html_e( 'Lite Skin', 'tieinsta_lite' ) ?> <?php echo $pro_label ?>
						</label>
						<label>
							<input disabled="disabled" type="radio">
							<?php esc_html_e( 'Dark Skin', 'tieinsta_lite' ) ?> <?php echo $pro_label ?>
						</label>
					</div>
					<div class="clear"></div>
				</div><!-- .tieinsta-widget-option -->

				<p>
					<input id="<?php echo $this->get_field_id( 'box_only' ); ?>" name="<?php echo $this->get_field_name( 'box_only' ); ?>" value="true" <?php checked( $instance['box_only'], 'true' ) ?> type="checkbox" />
					<label for="<?php echo $this->get_field_id( 'box_only' ); ?>"><?php esc_html_e( 'Show the Instagram Box only', 'tieinsta_lite' ) ?></label>
					<br /><small><?php esc_html_e( 'Will avoid the theme widget design and hide the widget title .', 'tieinsta_lite' ) ?></small>
				</p>

				<p>
					<input id="<?php echo $this->get_field_id( 'instagram_logo' ); ?>" name="<?php echo $this->get_field_name( 'instagram_logo' ); ?>" value="true" <?php checked( $instance['instagram_logo'], 'true' ) ?> type="checkbox" />
					<label for="<?php echo $this->get_field_id( 'instagram_logo' ); ?>"><?php esc_html_e( 'Show the Instagram logo bar', 'tieinsta_lite' ) ?></label>
				</p>

				<p>
					<input id="<?php echo $this->get_field_id( 'new_window' ); ?>" name="<?php echo $this->get_field_name( 'new_window' ); ?>" value="true" <?php checked( $instance['new_window'], 'true' ) ?> type="checkbox" />
					<label for="<?php echo $this->get_field_id( 'new_window' ); ?>"><?php esc_html_e( 'Open links in a new window', 'tieinsta_lite' ) ?></label>
				</p>

				<p>
					<input id="<?php echo $this->get_field_id( 'nofollow' ); ?>" name="<?php echo $this->get_field_name( 'nofollow' ); ?>" value="true" <?php checked( $instance['nofollow'], 'true' ) ?> type="checkbox" />
					<label for="<?php echo $this->get_field_id( 'nofollow' ); ?>"><?php esc_html_e( 'Nofollow', 'tieinsta_lite' ) ?></label>
				</p>

				<p>
					<input id="<?php echo $this->get_field_id( 'credit' ); ?>" name="<?php echo $this->get_field_name( 'credit' ); ?>" value="true" <?php checked( $instance['credit'], 'true' ) ?> type="checkbox" />
					<label for="<?php echo $this->get_field_id( 'credit' ); ?>"><?php esc_html_e( 'Give us a credit', 'tieinsta_lite' ) ?></label>
				</p>
			</div><!-- tieinsta-widget-content -->


			<div class="tieinsta-widget-option">
				<label class="tieinsta-widget-option-label" for="<?php echo $this->get_field_id( 'media_source' ); ?>"><?php esc_html_e( 'Get media from', 'tieinsta_lite' ) ?></label>

				<div class="tieinsta-widget-option-options">
					<label>
						<input type="radio" checked="checked">
						<?php echo get_option( 'instanow_lite_username' ) ? get_option( 'instanow_lite_username' ) : esc_html__( 'Self', 'tieinsta_lite' ) ?>
					</label>
					<label>
						<input type="radio" disabled="disabled">
						<?php esc_html_e( 'Public User Account', 'tieinsta_lite' ) ?> <?php echo $pro_label ?>
					</label>
					<label>
						<input type="radio" disabled="disabled">
						<?php esc_html_e( 'Hash Tag', 'tieinsta_lite' ) ?> <?php echo $pro_label ?>
					</label>
				</div>
				<div class="clear"></div>
			</div><!-- .tieinsta-widget-option -->


			<p>
				<input id="<?php echo $this->get_field_id( 'account_info' ); ?>" name="<?php echo $this->get_field_name( 'account_info' ); ?>" value="true" <?php checked( $instance['account_info'], 'true' ) ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'account_info' ); ?>"><?php esc_html_e( 'Show the Account Info area', 'tieinsta_lite' ) ?></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'account_info_position' ); ?>"><?php esc_html_e( 'Position', 'tieinsta_lite' ) ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'account_info_position' ); ?>" name="<?php echo $this->get_field_name( 'account_info_position' ); ?>" >
					<option value="top" <?php selected( $instance['account_info_position'], 'top' ) ?>><?php esc_html_e( 'Top of the widget', 'tieinsta_lite' ) ?></option>
					<option value="bottom" <?php selected( $instance['account_info_position'], 'bottom' ) ?>><?php esc_html_e( 'End of the widget', 'tieinsta_lite' ) ?></option>
				</select>
			</p>

			<div class="tieinsta-widget-option">
				<label class="tieinsta-widget-option-label" for="<?php echo $this->get_field_id( 'account_info_layout' ); ?>"><?php esc_html_e( 'Layout', 'tieinsta_lite' ) ?></label>

				<div class="tieinsta-widget-option-options">
					<label>
						<input name="<?php echo $this->get_field_name( 'account_info_layout' ); ?>" type="radio" value="1" <?php checked( $instance['account_info_layout'], '1' ) ?>>
						<a><?php esc_html_e( 'Layout 1', 'tieinsta_lite' ) ?>
							<span class="tieinsta-tooltip"><img src="<?php echo plugins_url( 'assets/images/lay1.png', __FILE__) ?>" alt="" /></span>
						</a>
					</label>
					<label>
						<input disabled="disabled" type="radio">
						<a><?php esc_html_e( 'Layout 2', 'tieinsta_lite' ) ?>
							<span class="tieinsta-tooltip"><img src="<?php echo plugins_url( 'assets/images/lay2.png', __FILE__) ?>" alt="" /></span>
						</a>
						<?php echo $pro_label ?>
					</label>
					<label>
						<input disabled="disabled" type="radio">
						<a><?php esc_html_e( 'Layout 3', 'tieinsta_lite' ) ?>
							<span class="tieinsta-tooltip"><img src="<?php echo plugins_url( 'assets/images/lay3.png', __FILE__) ?>" alt="" /></span>
						</a>
						<?php echo $pro_label ?>
					</label>
				</div>
				<div class="clear"></div>
			</div><!-- .tieinsta-widget-option -->


			<p>
				<input id="<?php echo $this->get_field_id( 'full_name' ); ?>" name="<?php echo $this->get_field_name( 'full_name' ); ?>" value="true" <?php checked( $instance['full_name'], 'true' ) ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'full_name' ); ?>"><?php esc_html_e( 'Show the Full name', 'tieinsta_lite' ) ?></label>
			</p>

			<p>
				<input id="<?php echo $this->get_field_id( 'website' ); ?>" name="<?php echo $this->get_field_name( 'website' ); ?>" value="true" <?php checked( $instance['website'], 'true' ) ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'website' ); ?>"><?php esc_html_e( 'Show the Website URL', 'tieinsta_lite' ) ?></label>
			</p>

			<p>
				<input id="<?php echo $this->get_field_id( 'bio' ); ?>" name="<?php echo $this->get_field_name( 'bio' ); ?>" value="true" <?php checked( $instance['bio'], 'true' ) ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'bio' ); ?>"><?php esc_html_e( 'Show the bio', 'tieinsta_lite' ) ?></label>
			</p>

			<p>
				<input id="<?php echo $this->get_field_id( 'stats' ); ?>" name="<?php echo $this->get_field_name( 'stats' ); ?>" value="true" <?php checked( $instance['stats'], 'true' ) ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'stats' ); ?>"><?php esc_html_e( 'Show the account stats', 'tieinsta_lite' ) ?></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'avatar_shape' ); ?>"><?php esc_html_e( 'Avatar shape', 'tieinsta_lite' ) ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'avatar_shape' );?>" name="<?php echo $this->get_field_name( 'avatar_shape' ); ?>" >
					<option value="square" <?php selected( $instance['avatar_shape'], 'square' ) ?>><?php esc_html_e( 'Square', 'tieinsta_lite' ) ?></option>
					<option value="round"  <?php selected( $instance['avatar_shape'], 'round'  ) ?>><?php esc_html_e( 'Round',  'tieinsta_lite' ) ?></option>
					<option value="circle" <?php selected( $instance['avatar_shape'], 'circle' ) ?>><?php esc_html_e( 'Circle', 'tieinsta_lite' ) ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'avatar_size' ); ?>"><?php esc_html_e( 'Avatar Width & Height', 'tieinsta_lite' ) ?></label>
				<input id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" name="<?php echo $this->get_field_name( 'avatar_size' ); ?>" value="<?php if(isset( $instance['avatar_size'] )) echo $instance['avatar_size']; ?>" style="width:40px;" type="text" /> <?php esc_html_e( 'px', 'tieinsta_lite' ) ?>
			</p>

			<div>
				<h4 class="tieinsta-widget-title"><?php esc_html_e( '- Media Settings -', 'tieinsta_lite' ) ?></h4>
				<div class="tieinsta-widget-content">
					<p>
						<label for="<?php echo $this->get_field_id( 'media_number' ); ?>"><?php esc_html_e( 'Number of Media items', 'tieinsta_lite' ) ?></label>
						<select id="<?php echo $this->get_field_id( 'media_number' ); ?>" name="<?php echo $this->get_field_name( 'media_number' ); ?>" >
							<?php for( $i=1 ; $i<=20 ; $i++ ){ ?>
								<option value="<?php echo $i ?>" <?php selected( $instance['media_number'], $i ) ?>><?php echo $i ?></option>
							<?php } ?>
						</select>
						<br /><span><?php printf( __( '- Upgrade to the <span class="tie-insta-pro-only"><a href="%s" target="_blank">PRO version</a><span> to show more than 20 photos.', 'tieinsta_lite' ), TIEINSTA_LITE_PRO_URL ) ?></span>
					</p>

					<div class="tieinsta-widget-option">
						<label class="tieinsta-widget-option-label" for="<?php echo $this->get_field_id( 'link' ); ?>"><?php esc_html_e( 'Link to', 'tieinsta_lite' ) ?></label>

						<div class="tieinsta-widget-option-options">
							<label>
								<input type="radio" name="<?php echo $this->get_field_name( 'link' ); ?>" value="none" <?php checked( $instance['link'], 'none' ) ?>><?php esc_html_e( 'None', 'tieinsta_lite' ) ?></option>
							</label>
							<label>
								<input type="radio" name="<?php echo $this->get_field_name( 'link' ); ?>" value="page" <?php checked( $instance['link'], 'page' ) ?>><?php esc_html_e( 'Media page on Instagram', 'tieinsta_lite' ) ?></option>
							</label>
							<label>
								<input type="radio" disabled="disabled"><?php esc_html_e( 'Media File - LightBox', 'tieinsta_lite' ) ?> <?php echo $pro_label ?></option>
							</label>
						</div>
					</div>

					<p class="tie_media_layout">
						<label for="<?php echo $this->get_field_id( 'media_layout' ); ?>"><?php esc_html_e( 'Layout', 'tieinsta_lite' ) ?></label>
						<select class="widefat" id="<?php echo $this->get_field_id( 'media_layout' ); ?>" name="<?php echo $this->get_field_name( 'media_layout' ); ?>" >
							<option value="grid" <?php selected( $instance['media_layout'], 'grid' ) ?>><?php esc_html_e( 'Grid', 'tieinsta_lite' ) ?></option>
							<option value="slider" <?php selected( $instance['media_layout'], 'slider' ) ?>><?php esc_html_e( 'Slider', 'tieinsta_lite' ) ?></option>
						</select>
					</p>

					<div style="display:none;" class="tie-grid-settings" id="tie-grid-settings-<?php echo $this->get_field_id( 'media_layout' ); ?>">
						<p>
							<label for="<?php echo $this->get_field_id( 'columns_number' ); ?>"><?php esc_html_e( 'Number of Columns', 'tieinsta_lite' ) ?></label>
							<select id="<?php echo $this->get_field_id( 'columns_number' ); ?>" name="<?php echo $this->get_field_name( 'columns_number' ); ?>" >
							<?php for( $i=1 ; $i<=10 ; $i++ ){ ?>
								<option value="<?php echo $i ?>" <?php selected( $instance['columns_number'], $i ) ?>><?php echo $i ?></option>
							<?php } ?>
							</select>
						</p>
						<p>
							<input id="<?php echo $this->get_field_id( 'flat' ); ?>" name="<?php echo $this->get_field_name( 'flat' ); ?>" value="true" <?php checked( $instance['flat'], 'true' ) ?> type="checkbox" />
							<label for="<?php echo $this->get_field_id( 'flat' ); ?>"><?php esc_html_e( 'Flat Images (Without borders, margins and shadows)', 'tieinsta_lite' ) ?></label>
						</p>
						<p>
							<input disabled="disabled" type="checkbox" />
							<label for="<?php echo $this->get_field_id( 'load_more' ); ?>"><?php esc_html_e( 'Enable Load More Button', 'tieinsta_lite' ) ?> <?php echo $pro_label ?></label>
						</p>
					</div>

					<div style="display:none;" class="tie-slider-settings" id="tie-slider-settings-<?php echo $this->get_field_id( 'media_layout' ); ?>">
						<p>
							<label for="<?php echo $this->get_field_id( 'slider_speed' ); ?>"><?php esc_html_e( 'Slider Speed', 'tieinsta_lite' ) ?></label>
							<input id="<?php echo $this->get_field_id( 'slider_speed' ); ?>" name="<?php echo $this->get_field_name( 'slider_speed' ); ?>" value="<?php if( ! empty( $instance['slider_speed'] )) echo $instance['slider_speed']; ?>" style="width:60px;" type="text" /> <?php esc_html_e( 'ms', 'tieinsta_lite' ) ?>
						</p>
						<p>
							<label for="<?php echo $this->get_field_id( 'slider_effect' ); ?>"><?php esc_html_e( 'Animation Effect', 'tieinsta_lite' ) ?></label>
							<select class="widefat" id="<?php echo $this->get_field_id( 'slider_effect' ); ?>" name="<?php echo $this->get_field_name( 'slider_effect' ); ?>" >
								<?php
									$effects = array ( 'blindX', 'blindY', 'blindZ', 'cover', 'curtainX', 'curtainY', 'fade', 'fadeZoom', 'growX', 'growY', 'scrollUp', 'scrollDown', 'scrollLeft', 'scrollRight', 'scrollHorz', 'scrollVert', 'slideX', 'slideY', 'toss', 'turnUp', 'turnDown', 'turnLeft', 'turnRight', 'uncover', 'wipe', 'zoom' );
									foreach ( $effects as $effect){ ?>
										<option value="<?php echo $effect ?>" <?php selected( $instance['slider_effect'], $effect ) ?>><?php echo $effect ?></option>
										<?php
									}
								?>
							</select>
						</p>

						<p>
							<input id="<?php echo $this->get_field_id( 'comments_likes' ); ?>" name="<?php echo $this->get_field_name( 'comments_likes' ); ?>" value="true" <?php checked( $instance['comments_likes'], 'true' ) ?> type="checkbox" />
							<label for="<?php echo $this->get_field_id( 'comments_likes' ); ?>"><?php esc_html_e( 'Show Media comments and likes number', 'tieinsta_lite' ) ?></label>
						</p>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
}


/*-----------------------------------------------------------------------------------*/
# Shortcodes
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_head', 'tie_insta_lite_add_editor_button' );
function tie_insta_lite_add_editor_button() {
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'tie_insta_lite_add_editor_plugin'  );
		add_filter( 'mce_buttons',          'tie_insta_lite_register_editor_button' );
	}
}

// Declare script for new button
function tie_insta_lite_add_editor_plugin( $plugin_array ) {
	$plugin_array['tie_insta_mce_button'] = plugins_url( 'assets/js/mce.js', __FILE__);
	return $plugin_array;
}

// Register new button in the editor
function tie_insta_lite_register_editor_button( $buttons ) {
	array_push( $buttons, 'tie_insta_mce_button' );
	return $buttons;
}

// Shortcode action in Front end
function tie_insta_lite_scodecodes( $atts, $content = null ) {

	# Defaults ----------
	$atts = wp_parse_args( $atts, array(
		'source'       => '',
		'hashtag'      => '',
		'show_hashtag' => '',
		'name'         => '',
		'style'        => '',
		'logo'         => '',
		'credit'       => '',
		'nofollow'     => '',
		'info'         => '',
		'info_pos'     => '',
		'info_layout'  => '',
		'full_name'    => '',
		'website'      => '',
		'bio'          => '',
		'stats'	       => '',
		'shape'        => '',
		'size'         => '',
		'media'        => '',
		'link'         => '',
		'layout'       => '',
		'columns'      => '',
		'speed'        => '',
		'effect'       => '',
		'com_like'     => '',
		'flat'         => '',

	));

	@extract( $atts );

	if( ! empty( $source ) ){
		$options['media_source'] = $source;
	}
	else{
		if( ! empty( $hashtag ) ){
			$options['media_source'] = 'hashtag';
		}
		elseif( ! empty( $name ) ){
			$options['media_source'] = 'user';
		}
	}

	$options['username']              = $name;
	$options['hashtag']               = $hashtag;
	$options['hashtag_info']          = $show_hashtag;
	$options['instagram_logo']        = $logo;
	$options['new_window']            = $window;
	$options['nofollow']              = $nofollow;
	$options['credit']                = $credit;
	$options['account_info']          = $info;
	$options['account_info_position'] = $info_pos;
	$options['full_name']             = $full_name;
	$options['website']               = $website;
	$options['bio']                   = $bio;
	$options['stats']                 = $stats;
	$options['avatar_shape']          = $shape;
	$options['avatar_size']           = $size;
	$options['media_number']          = $media;
	$options['media_layout']          = $layout;
	$options['link']                  = $link;
	$options['columns_number']        = $columns;
	$options['flat']                  = $flat;
	$options['slider_speed']          = $speed;
	$options['slider_effect']         = $effect;
	$options['comments_likes']        = $com_like;
	$options['large_img']             = true;

	ob_start();
	tie_insta_lite_media ( $options );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;

}
add_shortcode( 'instanow', 	 'tie_insta_lite_scodecodes' );
add_shortcode( 'instagramy', 'tie_insta_lite_scodecodes' ); //The Old Shortcode


/*-----------------------------------------------------------------------------------*/
# Add Custom Links to the plugins page
/*-----------------------------------------------------------------------------------*/
function tie_insta_lite_custom_plugin_links($links) {

    $links[] = '<a href="' . esc_url(admin_url( 'admin.php?page=instanowlite' )) . '">'. esc_html__( "Settings", "tieinsta_lite" ) .'</a>';
    $links[] = '<a href="' . esc_url( TIEINSTA_LITE_PRO_URL ) . '" target="_blank">'. esc_html__( "PRO Version", "tieinsta_lite" ) .'</a>';
    return $links;
}
add_filter( 'plugin_action_links_' .TIEINSTA_LITE_PLUGIN_SLUG, 'tie_insta_lite_custom_plugin_links' );

?>