<?php
/*
	Plugin Name: TieLabs InstaNOW Lite - Instagram Feed for WordPress
	Plugin URI: https://wordpress.org/plugins/instanow-lite/
	Description: User-friendly Plugin for displaying clean, customizable, and responsive user or hashtag Instagram feeds on your website
	Author: TieLabs
	Version: 1.1.2
	Author URI: http://tielabs.com
*/

define ( 'TIEINSTA_LITE_PLUGIN_NAME', 'InstaNOW Lite' );
define ( 'TIEINSTA_LITE_PLUGIN_SLUG', plugin_basename( __FILE__ )	);
define ( 'TIEINSTA_LITE_PRO_URL', 		'http://tielabs.com/go/instanow-pro/'	);

require_once( 'instanow-lite-admin.php' );


/*-----------------------------------------------------------------------------------*/
# Register and Enquee plugin's styles and scripts
/*-----------------------------------------------------------------------------------*/
function tie_insta_lite_scripts_styles(){

	wp_register_script( 'tie-insta-lite-slider-scripts', plugins_url( 'assets/js/slider-scripts.js', __FILE__ ) , array( 'jquery' ), false, false );
	wp_register_script( 'tie-insta-lite-admin-scripts' , plugins_url( 'assets/js/admin-scripts.js' , __FILE__ ) , array( 'jquery' ), false, true  );
	wp_register_style ( 'tie-insta-lite-style'         , plugins_url( 'assets/style.css', __FILE__ ) );

	if( ! is_admin()){
		wp_enqueue_style( 'tie-insta-lite-style' );
	}
	else{
		wp_enqueue_style ( 'tie-insta-lite-admin-css', plugins_url('assets/admin.css', __FILE__ ) );
		wp_enqueue_script( 'tie-insta-lite-admin-scripts' );

		$tieinsta_translation_array = array(
			'shortcodes_tooltip' => esc_html__( 'InstaNow Lite Shortcodes', 'tieinsta_lite' ),
			'mediaFrom'          => esc_html__( 'Get media from', 'tieinsta_lite' ),
			'logo_bar'           => esc_html__( 'Show the Instagram logo bar', 'tieinsta_lite' ),
			'new_window'         => esc_html__( 'Open links in a new window', 'tieinsta_lite' ),
			'nofollow'           => esc_html__( 'Nofollow', 'tieinsta_lite' ),
			'credit'             => esc_html__( 'Give us a credit', 'tieinsta_lite' ),
			'account_info'       => esc_html__( 'Show the Account Info area', 'tieinsta_lite' ),
			'position'           => esc_html__( 'Position', 'tieinsta_lite' ),
			'top'                => esc_html__( 'Top of the widget', 'tieinsta_lite' ),
			'bottom'             => esc_html__( 'End of the widget', 'tieinsta_lite' ),
			'full_name'          => esc_html__( 'Show the Full name', 'tieinsta_lite' ),
			'website_url'        => esc_html__( 'Show the Website URL', 'tieinsta_lite' ),
			'bio'                => esc_html__( 'Show the bio', 'tieinsta_lite' ),
			'account_stats'      => esc_html__( 'Show the account stats', 'tieinsta_lite' ),
			'avatar_shape'       => esc_html__( 'Avatar shape', 'tieinsta_lite' ),
			'square'             => esc_html__( 'Square', 'tieinsta_lite' ),
			'round'              => esc_html__( 'Round', 'tieinsta_lite' ),
			'circle'             => esc_html__( 'Circle', 'tieinsta_lite' ),
			'avatar_size'        => esc_html__( 'Avatar Width & Height', 'tieinsta_lite' ),
			'media_items'        => esc_html__( 'Number of Media items', 'tieinsta_lite' ),
			'link_to'            => esc_html__( 'Link to', 'tieinsta' ),
			'media_page'         => esc_html__( 'Media page on Instagram', 'tieinsta' ),
			'layout'             => esc_html__( 'Layout', 'tieinsta' ),
			'none'               => esc_html__( 'None', 'tieinsta' ),
			'grid'               => esc_html__( 'Grid', 'tieinsta_lite' ),
			'slider'             => esc_html__( 'Slider', 'tieinsta_lite' ),
			'columns'            => esc_html__( 'GRID - Number of Columns', 'tieinsta_lite' ),
			'flat'               => esc_html__( 'GRID - Flat Images (Without borders, margins and shadows)'	, 'tieinsta_lite' ),
			'slider_speed'       => esc_html__( 'SLIDER - Speed (ms)', 'tieinsta_lite' ),
			'slider_effect'      => esc_html__( 'SLIDER - Animation Effect', 'tieinsta_lite' ),
			'comments_likes'     => esc_html__( 'SLIDER - Show Media comments and likes number' , 'tieinsta_lite' )
		);

		wp_localize_script( 'tie-insta-lite-admin-scripts', 'tieinsta_js', $tieinsta_translation_array );
	}
}
add_action( 'init', 'tie_insta_lite_scripts_styles', 9 );


/*-----------------------------------------------------------------------------------*/
# Load Text Domain
/*-----------------------------------------------------------------------------------*/
add_action('plugins_loaded', 'tie_insta_lite_init');
function tie_insta_lite_init() {
	load_plugin_textdomain( 'tieinsta_lite' , false, dirname( TIEINSTA_LITE_PLUGIN_SLUG ).'/languages' );
}


/*-----------------------------------------------------------------------------------*/
# Get Data From API's
/*-----------------------------------------------------------------------------------*/
function tie_insta_lite_remote_get( $url , $json = true) {
	$request = wp_remote_get( $url , array( 'timeout' => 18 , 'sslverify' => false ) );
	$request = wp_remote_retrieve_body( $request );

	if( $json ){
		$request 	= @json_decode( $request , true );
	}

	return $request;
}


/*-----------------------------------------------------------------------------------*/
# Number Format Function
/*-----------------------------------------------------------------------------------*/
function tie_insta_lite_format_num( $number ){
	if( !is_numeric( $number ) ) return $number ;

	if($number >= 1000000){
		return round( ($number/1000)/1000 , 1) . "M";
    }
	elseif($number >= 100000){
		return round( $number/1000, 0) . "k";
    }
	else{
		return @number_format( $number );
	}
}


/*-----------------------------------------------------------------------------------*/
# Keep necessary data only
/*-----------------------------------------------------------------------------------*/
function tie_insta_lite_clean_data( $data ){
	unset( $data['pagination'] );
	unset( $data['meta'] );

	for( $i=0; ; $i++ ){
		if( !isset( $data['data'][$i] ) ) break;
		unset( $data['data'][$i]['tags'] );
		unset( $data['data'][$i]['attribution'] );
		unset( $data['data'][$i]['filter'] );
		unset( $data['data'][$i]['created_time'] );
		unset( $data['data'][$i]['users_in_photo'] );
		unset( $data['data'][$i]['user_has_liked'] );
		unset( $data['data'][$i]['location'] );
		unset( $data['data'][$i]['comments']['data'] );
		unset( $data['data'][$i]['likes']['data'] );
		unset( $data['data'][$i]['images']['thumbnail']['height'] );
		unset( $data['data'][$i]['images']['thumbnail']['width'] );
		unset( $data['data'][$i]['images']['standard_resolution']['height'] );
		unset( $data['data'][$i]['images']['standard_resolution']['width'] );
		unset( $data['data'][$i]['caption']['created_time'] );
		unset( $data['data'][$i]['caption']['from'] );
		unset( $data['data'][$i]['caption']['id'] );
		unset( $data['data'][$i]['user']);
		unset( $data['data'][$i]['id']);
	}

	return $data;
}


/*-----------------------------------------------------------------------------------*/
# Instagram Photos
/*-----------------------------------------------------------------------------------*/
function tie_insta_lite_media( $options ) {

	$tieinsta_options = get_option( 'tie_instagramy' );
	$access_token     = get_option( 'instanow_lite_access_token' );
	$cached_data      = get_transient( 'instanow_lite_data' );
	$username         = get_option( 'instanow_lite_username' );

	$cache = 12 ;
	$follow_text = esc_html__( 'Follow', 'tieinsta_lite');
	$tie_instagram_random_id = substr(str_shuffle("01234567HIJKLMNOPQRSTUVWXYZ"), 0, 5);


	$defaults = array(
		'media_source'          => 'user',
		'account_info_position' => 'top',
		'media_layout'          => 'grid',
		'columns_number'        => 3,
		'box_style'             => 'default',
	);

	$options = wp_parse_args( (array) $options, $defaults );

	extract( $options );

	$link_target  = ! empty( $new_window )   ? ' target="_blank"' : '';
	$rel_nofollow = ! empty( $nofollow )     ? ' rel="nofollow"' : '';
	$img_class    = ! empty( $avatar_shape ) ? ' class="'.$avatar_shape.'"' : '';
	$flat_class   = ! empty( $flat )         ? ' tie-insta-flat-images' : '';
	$img_size     = ! empty( $large_img )    ? 'standard_resolution' : 'low_resolution';


	$size = '';
	if( ! empty( $avatar_size )){
		$size = ' width="'.$avatar_size.'" height="'.$avatar_size.'" style="width:'.$avatar_size.'px; height:'.$avatar_size.'px;"';
		if( $avatar_size < 60 )
			$follow_text = ' + ';
	}

	$instagram_logo_url = $username;

	if( false !== $cached_data  ){
		$cached_data = json_decode( $cached_data , true );
		$data        = $cached_data['data'];
		$data_photos = $cached_data['data_photos'];
	}

	else{

		if( ! empty( $access_token )){

			$data                   = tie_insta_lite_remote_get("https://api.instagram.com/v1/users/self/?access_token=$access_token");
			$data_photos_source 	  = tie_insta_lite_remote_get("https://api.instagram.com/v1/users/self/media/recent/?access_token=$access_token");
			$data_photos            = tie_insta_lite_clean_data( $data_photos_source );
			$data_photos_meta_code 	= $data_photos_source['meta']['code'];

			if( $data['meta']['code'] == 200 && $data_photos_meta_code == 200 ){

				$tie_instagram_data = array(
					'data'			  =>	$data,
					'data_photos'	=>	$data_photos,
				);

				set_transient( 'instanow_lite_data', json_encode( $tie_instagram_data ) , $cache*60*60 );
				update_option( 'instanow_lite_data', json_encode( $tie_instagram_data ) );
			}

			else{
				if( get_option( 'instanow_lite_data' ) ){
					$tie_instagram_stored_data  = json_decode( get_option( 'instanow_lite_data' ), true );
					$data                       = $tie_instagram_stored_data['data'];
					$data_photos                = $tie_instagram_stored_data['data_photos'];
				}
				else{
					echo esc_html_e( "Error : Couldn't Get Data From Instegram" , "tieinsta_lite" );
				}
			}

		}else {
			esc_html_e( 'Set an access token first' ,  "tieinsta_lite" ) ;
		}
	}


	if( ! empty( $data ) || ! empty( $data_photos ) ){

		if( ! empty( $account_info ) ){

			$tie_instagram_header = '
				<div class="tie-instagram-header">
					<div class="tie-instagram-avatar">
						<a href="https://instagram.com/'.$data['data']['username'].'"'.$img_class.$link_target.$rel_nofollow.$size.'>
							<img src="'.$data['data']['profile_picture'].'" alt="'. $data['data']['username'] .'"'.$size.' />
							<span class="tie-instagram-follow"><span>'.$follow_text.'</span></span>
						</a>
					</div>
					<div class="tie-instagram-info">
						<a href="http://instagram.com/'.$data['data']['username'].'"'.$link_target.$rel_nofollow.' class="tie-instagram-username">'.$data['data']['username'] .'</a>';

				if( ! empty( $full_name ) && !empty( $data['data']['full_name'] ) ) $tie_instagram_header .= '<span class="tie-instagram-full_name">'.$data['data']['full_name'] .'</span>';
				if( ! empty( $website   ) && !empty( $data['data']['website']   ) ) $tie_instagram_header .= '<a href="'. $data['data']['website'] .'" class="tie-instagram-website"'.$link_target.$rel_nofollow.'>'. $data['data']['website'] .'</a>';

				$tie_instagram_header .= '
					</div>';

				if( !empty( $bio ) && !empty( $data['data']['bio'] ) )  $tie_instagram_header .= '<div class="tie-instagram-desc">'. tie_insta_lite_links_mentions ( $data['data']['bio'], true ) .'</div>';

				if( !empty( $stats ) )
					$tie_instagram_header .= '
					<div class="tie-instagram-counts">
						<ul>
							<li>
								<span class="number-stat">'. tie_insta_lite_format_num ( $data['data']['counts']['media'] ) .'</span>
								<span>'.esc_html__( 'Posts' , 'tieinsta_lite' ).'</span>
							</li>
							<li>
								<span class="number-stat">'. tie_insta_lite_format_num ( $data['data']['counts']['followed_by'] ) .'</span>
								<span>'.esc_html__( 'Followers' , 'tieinsta_lite' ).'</span>
							</li>
							<li>
								<span class="number-stat">'. tie_insta_lite_format_num ( $data['data']['counts']['follows'] ) .'</span>
								<span>'.esc_html__( 'Following' , 'tieinsta_lite' ).'</span>
							</li>
						</ul>
					</div> <!-- .tie-instagram-counts --> ';

				$tie_instagram_header .= '
				</div> <!-- .tie-instagram-header -->';

		}?>

			<div id="tie-instagram-<?php echo $tie_instagram_random_id ?>" class="tie-instagram <?php echo $box_style ?>-skin tieinsta-<?php echo $media_layout ?> grid-col-<?php echo $columns_number ?> header-layout-1 header-layout-<?php echo $account_info_position ?><?php echo $flat_class ?><?php if( empty( $instagram_logo ) ) echo ' no-insta-logo' ?>">

			<?php if( !empty( $instagram_logo ) ): ?>
				<div class="tie-instagram-bar">
					<a class="instagram-logo" href="https://instagram.com/<?php echo $instagram_logo_url ?>"<?php echo $link_target.$rel_nofollow; ?>></a>
				</div>
			<?php endif; ?>


			<?php
			if( ( !empty( $tie_instagram_header ) && $media_source != 'hashtag' && !empty( $account_info_position ) && $account_info_position == 'top' ) ||
					  ( !empty( $tie_instagram_header ) && $media_source == 'hashtag' )  ) echo $tie_instagram_header; ?>

				<div class="tie-instagram-photos">
					<div class="tie-instagram-photos-content">
						<div class="tie-instagram-photos-content-inner">
						<?php
							$count = 0;
							foreach ( $data_photos['data'] as $photo ){
								$count ++;

								$media_link = $photo['link'];?>
								<div class="tie-instagram-post-outer">
									<div class="tie-instagram-post-outer2">
										<div class="tie-instagram-post">
											<div class="tie-instagram-post-inner">
												<?php if( !empty( $link ) && $link != 'none' ): ?>
												<a href="<?php echo $media_link ?>"<?php echo $link_target.$rel_nofollow; ?>>
												<?php endif; ?>
													<img src="<?php echo $photo['images'][$img_size]['url'] ?>" alt="" width="320" height="320" />
												<?php
												if( $media_layout == 'slider' && !empty( $comments_likes ) ){ ?>
													<div class="media-comment-likes">
														<span class="media-likes"><i class="tieinstaicon-heart"></i><?php echo tie_insta_lite_format_num ( $photo['likes']['count'] ); ?></span>
														<span class="media-comments"><i class="tieinstaicon-comment-alt"></i><?php echo tie_insta_lite_format_num ( $photo['comments']['count'] ); ?></span>
													</div>
												<?php
												}
												if( !empty( $photo['videos']['standard_resolution']['url']) ){?>
													<span class="media-video"><i class="tieinstaicon-play"></i></span>
												<?php
												}
												if( !empty( $link ) && $link != 'none' ): ?>
												</a>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
								<?php
								if( ( !empty( $media_number ) && $count == $media_number ) || ( empty( $media_number ) && $count == 9  ) ) break;
							}
						?>

						</div> <!-- .tie-instagram-photos-content-inner -->

					</div> <!-- .tie-instagram-photos-content -->

					<?php if( $media_layout == 'slider' ){ ?>
					<div class="tie-instagram-nav" class="tie-instagram-nav-prev">
						<a id="prev-<?php echo $tie_instagram_random_id ?>" class="tie-instagram-nav-prev" href="#"><i class="tieinstaicon-left-open"></i></a>
						<a id="next-<?php echo $tie_instagram_random_id ?>" class="tie-instagram-nav-next" href="#"><i class="tieinstaicon-right-open"></i></a>
					</div>
					<?php } ?>

				</div>  <!-- .tie-instagram-photos -->

				<?php if( !empty( $tie_instagram_header ) && $media_source != 'hashtag' && !empty( $account_info_position ) && $account_info_position == 'bottom' ) echo $tie_instagram_header ?>

				<?php if( !empty( $credit ) ): ?>
				<span class="tie-instagram-credit"><a href="http://tielabs.com/"<?php echo $link_target.$rel_nofollow; ?>><?php esc_html_e( 'InstaNow Lite Plugin by TieLabs' , 'tieinsta_lite' ) ?></a><span>
				<?php endif; ?>
			</div> <!-- .tie-instagram -->
			<!-- InstaNOW Lite Plugin - by TieLabs | http://tielabs.com -->

			<?php
			if( $media_layout == 'slider' ){
				wp_enqueue_script( 'tie-insta-lite-slider-scripts' ); ?>
				<script type="text/javascript">
					jQuery( document ).ready(function() {
						new imagesLoaded( '#tie-instagram-<?php echo $tie_instagram_random_id ?>', function() {
							jQuery( '#tie-instagram-<?php echo $tie_instagram_random_id ?>' ).addClass( 'tieinsta-slider-active' );
							jQuery(function() {
								jQuery('#tie-instagram-<?php echo $tie_instagram_random_id ?>.tieinsta-slider .tie-instagram-photos-content-inner').cycle({
									fx:     '<?php if( !empty( $slider_effect ) ) echo $slider_effect ; else echo 'scrollHorz' ?>',
									timeout: <?php if( !empty( $slider_speed ) ) echo $slider_speed ; else echo '3000' ?>,
									next:   '#next-<?php echo $tie_instagram_random_id ?>',
									prev:   '#prev-<?php echo $tie_instagram_random_id ?>',
									after: instaNowLiteOnAfter,
									speed: 350,
									pause: true
								});
							});
						});
						function instaNowLiteOnAfter(curr, next, opts, fwd) {
	  					var $ht = jQuery(this).height();
							jQuery(this).parent().animate({height: $ht});
						}
					});
				</script>
				<?php
			}
	}
}


/*-----------------------------------------------------------------------------------*/
# Active Links and Mentions
/*-----------------------------------------------------------------------------------*/
function tie_insta_lite_links_mentions( $text , $html = false ){
	$text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1&lt;a href='\\2' target='_blank'&gt;\\2&lt;/a&gt;", $text);
	$text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1&lt;a href='http://\\2' target='_blank'&gt;\\2&lt;/a&gt;", $text);
	$text = preg_replace("/@(\w+)/", "&lt;a href='http://instagram.com/\\1' target='_blank'&gt;@\\1&lt;/a&gt;", $text);
	$text = preg_replace("/#(\w+)/", "&lt;a href='http://instagram.com/explore/tags/\\1' target='_blank'&gt;#\\1&lt;/a&gt; ", $text);

	if( $html ){
		$text = htmlspecialchars_decode( $text );
	}

	return $text;
}


/*-----------------------------------------------------------------------------------*/
# Custom CSS
/*-----------------------------------------------------------------------------------*/
add_action('wp_head', 'tie_insta_lite_css');
function tie_insta_lite_css() {

	$tieinsta_options = get_option( 'tie_instagramy' );
	if( !empty( $tieinsta_options['css'] ) ){ ?>

<style type="text/css" media="screen">

<?php $css_code = str_replace("<pre>" , "", htmlspecialchars_decode( $tieinsta_options['css'] ) );
 echo $css_code = str_replace("</pre>", "", $css_code )  , "\n";?>

</style>

	<?php
	}
}

?>