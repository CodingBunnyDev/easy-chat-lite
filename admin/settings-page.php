<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once plugin_dir_path(__DIR__) . 'admin/display-button.php';

// Function to render the plugin settings page
function cbec_settings() {

	// Check if the form has been submitted
	if ( isset( $_POST['cbec_settings'] ) ) {
		if ( ! isset( $_POST['cbec_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cbec_nonce'] ) ), 'cbec_action' ) ) {
			wp_die( esc_html__( 'Security check failed', 'coding-bunny-easy-chat-lite' ) );
		}

		// Retrieve and sanitize form settings
		$settings = isset( $_POST['cbec_settings'] ) ? wp_unslash( $_POST['cbec_settings'] ) : [];
		$prefix_number = isset( $settings['prefix_number'] ) ? sanitize_text_field( $settings['prefix_number'] ) : '';
		$phone_number = isset( $settings['phone_number'] ) ? sanitize_text_field( $settings['phone_number'] ) : '';
		$message = isset( $settings['message'] ) ? sanitize_textarea_field( $settings['message'] ) : '';
		$position = isset( $settings['position'] ) ? sanitize_text_field( $settings['position'] ) : 'right';
		$icon_size = isset( $settings['icon_size'] ) ? absint( $settings['icon_size'] ) : 40;
		$show_on_desktop = isset( $settings['show_on_desktop'] ) ? sanitize_text_field( $settings['show_on_desktop'] ) : 'all device';
		$icon_type = isset( $settings['icon_type'] ) ? sanitize_text_field( $settings['icon_type'] ) : 'coding-bunny-simple-icon.svg';
		$custom_icon_url = isset( $settings['custom_icon_url'] ) ? esc_url_raw( $settings['custom_icon_url'] ) : '';
		$icon_color_primary = isset( $settings['icon_color_primary'] ) ? sanitize_hex_color( $settings['icon_color_primary'] ) : '#23D366';
		$icon_color_secondary = isset( $settings['icon_color_secondary'] ) ? sanitize_hex_color( $settings['icon_color_secondary'] ) : '#FFFFFF';
		$tooltip_text = isset( $settings['tooltip_text'] ) ? sanitize_text_field( $settings['tooltip_text'] ) : 'Need help?';
		$tooltip_font_size = isset( $settings['tooltip_font_size'] ) ? absint( $settings['tooltip_font_size'] ) : 14;

		// Update options in the database
		update_option( 'cbec_custom_icon_url', $custom_icon_url );
		update_option( 'cbec_prefix_number', $prefix_number );
		update_option( 'cbec_phone_number', $phone_number );
		update_option( 'cbec_message', $message );
		update_option( 'cbec_position', $position );		
		update_option( 'cbec_icon_size', $icon_size );
		update_option( 'cbec_show_on_desktop', $show_on_desktop );
		update_option( 'cbec_icon_type', $icon_type );
		update_option( 'cbec_icon_color_primary', $icon_color_primary );
		update_option( 'cbec_icon_color_secondary', $icon_color_secondary );
		update_option( 'cbec_tooltip_text', $tooltip_text );
		update_option( 'cbec_tooltip_font_size', $tooltip_font_size );
	}

	// Retrieve current settings from the database
	$prefix_number = get_option( 'cbec_prefix_number', '+39' );
	$phone_number = get_option( 'cbec_phone_number', '1234567890' );
	$message = get_option( 'cbec_message', 'Ciao!' );
	$position = get_option( 'cbec_position', 'right' );
	$icon_size = get_option( 'cbec_icon_size', 40 );
	$show_on_desktop = get_option( 'cbec_show_on_desktop', 'all device' );
	$icon_type = get_option( 'cbec_icon_type', 'coding-bunny-simple-icon.svg' );
	$icon_color_primary = get_option( 'cbec_icon_color_primary', '#23D366' );
	$icon_color_secondary = get_option( 'cbec_icon_color_secondary', '#FFFFFF' );
	$tooltip_text = get_option( 'cbec_tooltip_text', 'Need help?' );
	$tooltip_font_size = get_option( 'cbec_tooltip_font_size', 14 );

	$icon_svg = '';
	if ($icon_type === 'custom' && !empty(get_option('cbec_custom_icon_url'))) {
		$icon_url = esc_url(get_option('cbec_custom_icon_url'));
		$icon_svg = file_get_contents($icon_url);
	} else {
		$icon_path = plugin_dir_path(dirname(__FILE__)) . 'assets/images/' . esc_attr($icon_type);
		if (file_exists($icon_path)) {
			$icon_svg = file_get_contents($icon_path);
		}
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'CodingBunny Easy Chat', 'coding-bunny-easy-chat-lite' ); ?> 
			<span>v<?php echo esc_html( CBEC_LITE_VERSION ); ?></span></h1>
			<form method="post" action="">
				<?php wp_nonce_field( 'cbec_action', 'cbec_nonce' ); ?>
				<div class="cbec-content">
					<div class="cbec-section">
						<div class="cbec-header-section">
							<h3>
								<span class="cbec-header-icon">
									<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../assets/images/cb-option.svg' ); ?>" alt="<?php esc_attr_e( 'Settings', 'coding-bunny-easy-chat-lite' ); ?>" />
								</span>
								<?php esc_html_e( 'WhatsApp Settings', 'coding-bunny-easy-chat-lite' ); ?>
							</h3>
						</div>
						<table class="form-table">

							<!-- WhatsApp Number Input -->
							<tr>
								<th scope="row"><?php esc_html_e( "WhatsApp Number", 'coding-bunny-easy-chat-lite' ); ?></th>
								<td>
									<div class="cbec-number">
										<input
										type="text"
										name="cbec_settings[prefix_number]"
										value="<?php echo esc_attr( $prefix_number ); ?>"
										placeholder="<?php esc_html_e( 'Prefix', 'coding-bunny-easy-chat-lite' ); ?>"
										class="cbec-input-prefix"
										/>

										<input
										type="number"
										name="cbec_settings[phone_number]"
										value="<?php echo esc_attr( $phone_number ); ?>"
										placeholder="<?php esc_html_e( 'Phone Number', 'coding-bunny-easy-chat-lite' ); ?>"
										class="cbec-input-phone"
										/>
									</div>
								</td>
							</tr>

							<!-- Pre-filled Message Input -->
							<tr>
								<th scope="row"><?php esc_html_e( "Pre-filled Message", 'coding-bunny-easy-chat-lite' ); ?></th>
								<td>
									<input
									type="text"
									name="cbec_settings[message]"
									value="<?php echo esc_attr( $message ); ?>"
									class="cbec-input-message"
									/>
								</td>
							</tr>

							<!-- Add the tooltip text input field in the settings form -->
							<tr>
								<th scope="row"><?php esc_html_e( "Tooltip Text", 'coding-bunny-easy-chat-lite' ); ?></th>
								<td>
									<input
									type="text"
									name="cbec_settings[tooltip_text]"
									value="<?php echo esc_attr( $tooltip_text ); ?>"
									class="cbec-input-tooltip-text"
									/>
								</td>
							</tr>
							
							<!-- Tooltip Font Size Settings -->
							<tr>
								<th scope="row"><?php esc_html_e( "Tooltip Font Size", 'coding-bunny-easy-chat-lite' ); ?></th>
								<td>
									<input type="range"
									name="cbec_settings[tooltip_font_size]"
									id="tooltip-font-size-range"
									value="<?php echo esc_attr( $tooltip_font_size ); ?>"
									min="10"
									max="20"
									/>
									<span id="tooltip-font-size-value">
										<?php echo esc_attr( $tooltip_font_size ); ?>px
									</span>
								</td>
							</tr>
							
						</table>
					</div>
				</div>
				<br>
				<!-- Visibility Settings -->
				<div class="cbec-content">
					<div class="cbec-section">
						<div class="cbec-header-section">
							<h3>
								<span class="cbec-header-icon">
									<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../assets/images/cb-visibility.svg' ); ?>" alt="<?php esc_attr_e( 'Visibility', 'coding-bunny-easy-chat-lite' ); ?>" />
								</span>
								<?php esc_html_e( 'Visibility', 'coding-bunny-easy-chat-lite' ); ?>
							</h3>
						</div>
						<table class="form-table">

							<!-- Show on All Devices Settings -->
							<tr>
								<th scope="row"><?php esc_html_e( "Display on", 'coding-bunny-easy-chat-lite' ); ?></th>
								<td>
									<select name="cbec_settings[show_on_desktop]" class="cbec-select-show-desktop">
										<option value="yes" <?php selected( $show_on_desktop, 'yes' ); ?>><?php esc_html_e( 'All Device', 'coding-bunny-easy-chat-lite' ); ?></option>
										<option value="no" <?php selected( $show_on_desktop, 'no' ); ?>><?php esc_html_e( 'Only Mobile', 'coding-bunny-easy-chat-lite' ); ?></option>
									</select>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<br>

				<div class="cbec-content">
					<div class="cbec-section">
						<div class="cbec-header-section">
							<h3>
								<span class="cbec-header-icon">
									<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../assets/images/cb-position.svg' ); ?>" alt="<?php esc_attr_e( 'Position', 'coding-bunny-easy-chat-lite' ); ?>" />
								</span>
								<?php esc_html_e( 'Position', 'coding-bunny-easy-chat-lite' ); ?>
							</h3>
						</div>
						<table class="form-table">

							<!-- Widget Position Settings -->
							<tr>
								<th scope="row"><?php esc_html_e( "Widget Position", 'coding-bunny-easy-chat-lite' ); ?></th>
								<td>
									<select name="cbec_settings[position]" class="cbec-select-position">
										<option value="right" <?php selected( $position, 'right' ); ?>><?php esc_html_e( 'Right', 'coding-bunny-easy-chat-lite' ); ?></option>
										<option value="left" <?php selected( $position, 'left' ); ?>><?php esc_html_e( 'Left', 'coding-bunny-easy-chat-lite' ); ?></option>
									</select>
								</td>
							</tr>

						</table>
					</div>
				</div>
				<br>
				<div class="cbec-content">
					<div class="cbec-section">
						<div class="cbec-header-section">
							<h3>
								<span class="cbec-header-icon">
									<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../assets/images/cb-style.svg' ); ?>" alt="<?php esc_attr_e( 'Design', 'coding-bunny-easy-chat-lite' ); ?>" />
								</span>
								<?php esc_html_e( 'Design', 'coding-bunny-easy-chat-lite' ); ?>
							</h3>
						</div>
						<table class="form-table">

							<!-- Widget Style Settings -->
							<tr>
								<th scope="row"><?php esc_html_e( "Widget Style", 'coding-bunny-easy-chat-lite' ); ?></th>
								<td>
									<div style="display: flex; align-items: center;">
										<select name="cbec_settings[icon_type]" class="cbec-select-icon-type" onchange="updateIconPreview(this)">
											<option value="coding-bunny-simple-icon.svg" <?php selected( $icon_type, 'coding-bunny-simple-icon.svg' ); ?>><?php esc_html_e( 'Simple', 'coding-bunny-easy-chat-lite' ); ?></option>
											<option value="coding-bunny-round-icon.svg" <?php selected( $icon_type, 'coding-bunny-round-icon.svg' ); ?>><?php esc_html_e( 'Round', 'coding-bunny-easy-chat-lite' ); ?></option>
											<option value="coding-bunny-square-icon.svg" <?php selected( $icon_type, 'coding-bunny-square-icon.svg' ); ?>><?php esc_html_e( 'Square', 'coding-bunny-easy-chat-lite' ); ?></option>
											<option value="coding-bunny-chat-icon-01.svg" <?php selected( $icon_type, 'coding-bunny-chat-icon-01.svg' ); ?>><?php esc_html_e( 'Chat 1', 'coding-bunny-easy-chat-lite' ); ?></option>
											<option value="coding-bunny-chat-icon-02.svg" <?php selected( $icon_type, 'coding-bunny-chat-icon-02.svg' ); ?>><?php esc_html_e( 'Chat 2', 'coding-bunny-easy-chat-lite' ); ?></option>
											<option value="coding-bunny-chat-icon-03.svg" <?php selected( $icon_type, 'coding-bunny-chat-icon-03.svg' ); ?>><?php esc_html_e( 'Chat 3', 'coding-bunny-easy-chat-lite' ); ?></option>
											<option value="coding-bunny-chat-icon-04.svg" <?php selected( $icon_type, 'coding-bunny-chat-icon-04.svg' ); ?>><?php esc_html_e( 'Chat 4', 'coding-bunny-easy-chat-lite' ); ?></option>
											<option value="coding-bunny-chat-icon-05.svg" <?php selected( $icon_type, 'coding-bunny-chat-icon-05.svg' ); ?>><?php esc_html_e( 'Chat 5', 'coding-bunny-easy-chat-lite' ); ?></option>	
										</select>
										<img id="icon-preview" src="<?php echo esc_url( $icon_type === 'custom' && ! empty( get_option( 'cbec_custom_icon_url' ) ) ? get_option( 'cbec_custom_icon_url' ) : plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/' . esc_attr( $icon_type ) ); ?>" alt="<?php esc_attr_e( 'Preview Button', 'coding-bunny-easy-chat-lite' ); ?>"
										style="width: <?php echo esc_attr( $icon_size ); ?>px;
										height: <?php echo esc_attr( $icon_size ); ?>px;
										margin-left: 10px;"								
										/>
									</div>
								</td>
							</tr>

							<!-- Widget Color Settings -->
							<tr>
								<th scope="row"><?php esc_html_e( "Widget Primary Color", 'coding-bunny-easy-chat-lite' ); ?></th>
								<td>
									<input
									type="color"
									name="cbec_settings[icon_color_primary]"
									value="<?php echo esc_attr( get_option( 'cbec_icon_color_primary', '#7F54B2' ) ); ?>"
									class="cbec-input-icon-color" 
									/>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( "Widget Secondary Color", 'coding-bunny-easy-chat-lite' ); ?></th>
								<td>
									<input
									type="color"
									name="cbec_settings[icon_color_secondary]"
									value="<?php echo esc_attr( get_option( 'cbec_icon_color_secondary', '#FFFFFF' ) ); ?>"
									class="cbec-input-icon-color" 
									/>
								</td>
							</tr>

							<!-- Widget Size Settings -->
							<tr>
								<th scope="row"><?php esc_html_e( "Widget Size", 'coding-bunny-easy-chat-lite' ); ?></th>
								<td>
									<input type="range"
									name="cbec_settings[icon_size]"
									id="icon-size-range"
									value="<?php echo esc_attr( $icon_size ); ?>"
									min="20"
									max="100"
									/>
									<span id="icon-size-value">
										<?php echo esc_attr( $icon_size ); ?>px
									</span>
								</td>
							</tr>

						</table>
					</div>
				</div>

				<!-- Submit Button -->
				<p>
					<input type="submit" value="<?php esc_attr_e( 'Save Settings', 'coding-bunny-easy-chat-lite' ); ?>" class="button-primary"/>
				</p>

				<p>
					© <?php echo esc_html(gmdate('Y')); ?> - 
					<?php esc_html_e('Powered by CodingBunny', 'coding-bunny-easy-chat-lite'); ?> |
					<a href="https://coding-bunny.com/support/" target="_blank" rel="noopener"><?php esc_html_e('Support', 'coding-bunny-easy-chat-lite'); ?></a> |
					<a href="https://coding-bunny.com/documentation/easy-chat-doc/" target="_blank" rel="noopener"><?php esc_html_e('Documentation', 'coding-bunny-easy-chat-lite'); ?></a> |
					<a href="https://coding-bunny.com/changelog/" target="_blank" rel="noopener"><?php esc_html_e('Changelog', 'coding-bunny-easy-chat-lite'); ?></a>
				</p>
			</form>
		</div>

		<script>		
			// Function to update icon preview based on selected icon type
			function updateIconPreview(select) {
				var iconPreview = document.getElementById("icon-preview");
					iconPreview.src = "<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) ); ?>assets/images/preview/" + select.value;
				}
			</script>

			<?php
		}