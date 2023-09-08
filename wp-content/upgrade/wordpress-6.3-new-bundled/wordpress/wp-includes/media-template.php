<?php
/**
 * WordPress media templates.
 *
 * @package WordPress
 * @subpackage Media
 * @since 3.5.0
 */

/**
 * Outputs the markup for an audio tag to be used in an Underscore template
 * when data.model is passed.
 *
 * @since 3.9.0
 */
function wp_underscore_audio_template() {
	$audio_types = wp_get_audio_extensions();
	?>
<audio style="visibility: hidden"
	controls
	class="wp-audio-shortcode"
	width="{{ _.isUndefined( data.model.width ) ? 400 : data.model.width }}"
	preload="{{ _.isUndefined( data.model.preload ) ? 'none' : data.model.preload }}"
	<#
	<?php
	foreach ( array( 'autoplay', 'loop' ) as $attr ) :
		?>
	if ( ! _.isUndefined( data.model.<?php echo $attr; ?> ) && data.model.<?php echo $attr; ?> ) {
		#> <?php echo $attr; ?><#
	}
	<?php endforeach; ?>#>
>
	<# if ( ! _.isEmpty( data.model.src ) ) { #>
	<source src="{{ data.model.src }}" type="{{ wp.media.view.settings.embedMimes[ data.model.src.split('.').pop() ] }}" />
	<# } #>

	<?php
	foreach ( $audio_types as $type ) :
		?>
	<# if ( ! _.isEmpty( data.model.<?php echo $type; ?> ) ) { #>
	<source src="{{ data.model.<?php echo $type; ?> }}" type="{{ wp.media.view.settings.embedMimes[ '<?php echo $type; ?>' ] }}" />
	<# } #>
		<?php
	endforeach;
	?>
</audio>
	<?php
}

/**
 * Outputs the markup for a video tag to be used in an Underscore template
 * when data.model is passed.
 *
 * @since 3.9.0
 */
function wp_underscore_video_template() {
	$video_types = wp_get_video_extensions();
	?>
<#  var w_rule = '', classes = [],
		w, h, settings = wp.media.view.settings,
		isYouTube = isVimeo = false;

	if ( ! _.isEmpty( data.model.src ) ) {
		isYouTube = data.model.src.match(/youtube|youtu\.be/);
		isVimeo = -1 !== data.model.src.indexOf('vimeo');
	}

	if ( settings.contentWidth && data.model.width >= settings.contentWidth ) {
		w = settings.contentWidth;
	} else {
		w = data.model.width;
	}

	if ( w !== data.model.width ) {
		h = Math.ceil( ( data.model.height * w ) / data.model.width );
	} else {
		h = data.model.height;
	}

	if ( w ) {
		w_rule = 'width: ' + w + 'px; ';
	}

	if ( isYouTube ) {
		classes.push( 'youtube-video' );
	}

	if ( isVimeo ) {
		classes.push( 'vimeo-video' );
	}

#>
<div style="{{ w_rule }}" class="wp-video">
<video controls
	class="wp-video-shortcode {{ classes.join( ' ' ) }}"
	<# if ( w ) { #>width="{{ w }}"<# } #>
	<# if ( h ) { #>height="{{ h }}"<# } #>
	<?php
	$props = array(
		'poster'  => '',
		'preload' => 'metadata',
	);
	foreach ( $props as $key => $value ) :
		if ( empty( $value ) ) {
			?>
		<#
		if ( ! _.isUndefined( data.model.<?php echo $key; ?> ) && data.model.<?php echo $key; ?> ) {
			#> <?php echo $key; ?>="{{ data.model.<?php echo $key; ?> }}"<#
		} #>
			<?php
		} else {
			echo $key
			?>
			="{{ _.isUndefined( data.model.<?php echo $key; ?> ) ? '<?php echo $value; ?>' : data.model.<?php echo $key; ?> }}"
			<?php
		}
	endforeach;
	?>
	<#
	<?php
	foreach ( array( 'autoplay', 'loop' ) as $attr ) :
		?>
	if ( ! _.isUndefined( data.model.<?php echo $attr; ?> ) && data.model.<?php echo $attr; ?> ) {
		#> <?php echo $attr; ?><#
	}
	<?php endforeach; ?>#>
>
	<# if ( ! _.isEmpty( data.model.src ) ) {
		if ( isYouTube ) { #>
		<source src="{{ data.model.src }}" type="video/youtube" />
		<# } else if ( isVimeo ) { #>
		<source src="{{ data.model.src }}" type="video/vimeo" />
		<# } else { #>
		<source src="{{ data.model.src }}" type="{{ settings.embedMimes[ data.model.src.split('.').pop() ] }}" />
		<# }
	} #>

	<?php
	foreach ( $video_types as $type ) :
		?>
	<# if ( data.model.<?php echo $type; ?> ) { #>
	<source src="{{ data.model.<?php echo $type; ?> }}" type="{{ settings.embedMimes[ '<?php echo $type; ?>' ] }}" />
	<# } #>
	<?php endforeach; ?>
	{{{ data.model.content }}}
</video>
</div>
	<?php
}

/**
 * Prints the templates used in the media manager.
 *
 * @since 3.5.0
 */
function wp_print_media_templates() {
	$class = 'media-modal wp-core-ui';

	$alt_text_description = sprintf(
		/* translators: 1: Link to tutorial, 2: Additional link attributes, 3: Accessibility text. */
		__( '<a href="%1$s" %2$s>Learn how to describe the purpose of the image%3$s</a>. Leave empty if the image is purely decorative.' ),
		esc_url( 'https://www.w3.org/WAI/tutorials/images/decision-tree' ),
		'target="_blank" rel="noopener"',
		sprintf(
			'<span class="screen-reader-text"> %s</span>',
			/* translators: Hidden accessibility text. */
			__( '(opens in a new tab)' )
		)
	);
	?>

	<?php // Template for the media frame: used both in the media grid and in the media modal. ?>
	<script type="text/html" id="tmpl-media-frame">
		<div class="media-frame-title" id="media-frame-title"></div>
		<h2 class="media-frame-menu-heading"><?php _ex( 'Actions', 'media modal menu actions' ); ?></h2>
		<button type="button" class="button button-link media-frame-menu-toggle" aria-expanded="false">
			<?php _ex( 'Menu', 'media modal menu' ); ?>
			<span class="dashicons dashicons-arrow-down" aria-hidden="true"></span>
		</button>
		<div class="media-frame-menu"></div>
		<div class="media-frame-tab-panel">
			<div class="media-frame-router"></div>
			<div class="media-frame-content"></div>
		</div>
		<h2 class="media-frame-actions-heading screen-reader-text">
		<?php
			/* translators: Hidden accessibility text. */
			_e( 'Selected media actions' );
		?>
		</h2>
		<div class="media-frame-toolbar"></div>
		<div class="media-frame-uploader"></div>
	</script>

	<?php // Template for the media modal. ?>
	<script type="text/html" id="tmpl-media-modal">
		<div tabindex="0" class="<?php echo $class; ?>" role="dialog" aria-labelledby="media-frame-title">
			<# if ( data.hasCloseButton ) { #>
				<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">
					<?php
					/* translators: Hidden accessibility text. */
					_e( 'Close dialog' );
					?>
				</span></span></button>
			<# } #>
			<div class="media-modal-content" role="document"></div>
		</div>
		<div class="media-modal-backdrop"></div>
	</script>

	<?php // Template for the window uploader, used for example in the media grid. ?>
	<script type="text/html" id="tmpl-uploader-window">
		<div class="uploader-window-content">
			<div class="uploader-editor-title"><?php _e( 'Drop files to upload' ); ?></div>
		</div>
	</script>

	<?php // Template for the editor uploader. ?>
	<script type="text/html" id="tmpl-uploader-editor">
		<div class="uploader-editor-content">
			<div class="uploader-editor-title"><?php _e( 'Drop files to upload' ); ?></div>
		</div>
	</script>

	<?php // Template for the inline uploader, used for example in the Media Library admin page - Add New. ?>
	<script type="text/html" id="tmpl-uploader-inline">
		<# var messageClass = data.message ? 'has-upload-message' : 'no-upload-message'; #>
		<# if ( data.canClose ) { #>
		<button class="close dashicons dashicons-no"><span class="screen-reader-text">
			<?php
			/* translators: Hidden accessibility text. */
			_e( 'Close uploader' );
			?>
		</span></button>
		<# } #>
		<div class="uploader-inline-content {{ messageClass }}">
		<# if ( data.message ) { #>
			<h2 class="upload-message">{{ data.message }}</h2>
		<# } #>
		<?php if ( ! _device_can_upload() ) : ?>
			<div class="upload-ui">
				<h2 class="upload-instructions"><?php _e( 'Your browser cannot upload files' ); ?></h2>
				<p>
				<?php
					printf(
						/* translators: %s: https://apps.wordpress.org/ */
						__( 'The web browser on your device cannot be used to upload files. You may be able to use the <a href="%s">native app for your device</a> instead.' ),
						'https://apps.wordpress.org/'
					);
				?>
				</p>
			</div>
		<?php elseif ( is_multisite() && ! is_upload_space_available() ) : ?>
			<div class="upload-ui">
				<h2 class="upload-instructions"><?php _e( 'Upload Limit Exceeded' ); ?></h2>
				<?php
				/** This action is documented in wp-admin/includes/media.php */
				do_action( 'upload_ui_over_quota' );
				?>
			</div>
		<?php else : ?>
			<div class="upload-ui">
				<h2 class="upload-instructions drop-instructions"><?php _e( 'Drop files to upload' ); ?></h2>
				<p class="upload-instructions drop-instructions"><?php _ex( 'or', 'Uploader