<?php
$url       = 'https://wpmudev.com/docs/wpmu-dev-plugins/forminator/#field-group';
$user      = wp_get_current_user();
$banner_1x = forminator_plugin_url() . 'assets/images/new-feature-19.png';
$banner_2x = forminator_plugin_url() . 'assets/images/new-feature-19@2x.png';
?>

<div class="sui-modal sui-modal-md">

	<div
		role="dialog"
		id="forminator-new-feature"
		class="sui-modal-content"
		aria-live="polite"
		aria-modal="true"
		aria-labelledby="forminator-new-feature__title"
	>

		<div class="sui-box forminator-feature-modal" data-prop="forminator_dismiss_feature_1190" data-nonce="<?php echo esc_attr( wp_create_nonce( 'forminator_dismiss_notification' ) ); ?>">

			<div class="sui-box-header sui-flatten sui-content-center">

				<figure class="sui-box-banner" aria-hidden="true">
					<img
						src="<?php echo esc_url( $banner_1x ); ?>"
						srcset="<?php echo esc_url( $banner_1x ); ?> 1x, <?php echo esc_url( $banner_2x ); ?> 2x"
						alt=""
					/>
				</figure>

				<button class="sui-button-icon sui-button-white sui-button-float--right forminator-dismiss-new-feature" data-modal-close>
					<span class="sui-icon-close sui-md" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog.', 'forminator' ); ?></span>
				</button>

				<h3 class="sui-box-title sui-lg" style="overflow: initial; white-space: initial; text-overflow: initial;"><?php esc_html_e( 'New: Now You Can Group and Repeat Fields!', 'forminator' ); ?></h3>

				<p class="sui-description">
					<?php
					printf(
						/* translators: 1. User name. 2. Open a tag. 3. Close a tag. */
						esc_html__( 'Hey %1$s! You asked for it and we\'ve delivered. Take a look at the new Field Group and Repeater options which let you group multiple reusable fields together and collect repeated data like event attendees, education/employment history, address lists, etc. Check out this %2$sTutorial%3$s to learn more about grouping and repeating fields.', 'forminator' ),
						esc_html( ucfirst( $user->display_name ) ),
						'<a href="' . esc_url( $url ) . '" target="_blank">',
						'</a>'
					);
					?>
				</p>

			</div>

<!--			<div class="sui-box-body sui-spacing-top--20">

				<ul style="margin: 0 0 0 20px; list-style: disc;">

					<li style="margin-bottom: 0;">
						<h4 style="margin-bottom: 0;"></h4>
						<p class="sui-description" style="margin-left: -20px;"></p>
					</li>

				</ul>

			</div>-->

			<div class="sui-box-footer sui-flatten sui-content-center">

				<button class="sui-button forminator-dismiss-new-feature" data-modal-close><?php esc_html_e( 'Awesome, Let’s go!', 'forminator' ); ?></button>

			</div>

		</div>

	</div>

</div>

<script type="text/javascript">
	jQuery( '#forminator-new-feature .forminator-dismiss-new-feature' ).on( 'click', function( e ) {
		e.preventDefault();

		var $notice = jQuery( e.currentTarget ).closest( '.forminator-feature-modal' );
		var ajaxUrl = '<?php echo esc_url( forminator_ajax_url() ); ?>';

		jQuery.post(
			ajaxUrl,
			{
				action: 'forminator_dismiss_notification',
				prop: $notice.data('prop'),
				_ajax_nonce: $notice.data('nonce')
			}
		).always( function() {
			$notice.hide();
		});
	});
</script>
