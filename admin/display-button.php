<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'enqueue-scripts.php';

// Function to sanitize SVG content
function cbec_lite_sanitize_svg_content($svg) {
	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadXML($svg, LIBXML_NOERROR | LIBXML_NOWARNING);
	libxml_clear_errors();
	$xpath = new DOMXPath($dom);
	$elements = $xpath->query('//*');
	foreach ($elements as $element) {
		if (in_array($element->nodeName, ['script', 'style'])) {
			$element->parentNode->removeChild($element);
		}
	}
	return $dom->saveXML($dom->documentElement);
}

// Function to display the WhatsApp button
function cbec_lite_button() {
	$prefix_number = sanitize_text_field( get_option( 'cbec_prefix_number', '+39' ) );
	$phone_number = sanitize_text_field( get_option( 'cbec_phone_number', '1234567890' ) );
	$message = sanitize_text_field( get_option( 'cbec_message', 'Hello' ) );
	$position = sanitize_text_field( get_option( 'cbec_position', 'right' ) );
	$icon_size = intval( get_option( 'cbec_icon_size', 40 ) );
	$show_on_desktop = sanitize_text_field( get_option( 'cbec_show_on_desktop', 'only mobile' ) );
	$icon_type = sanitize_text_field( get_option( 'cbec_icon_type', 'coding-bunny-simple-icon.svg' ) );	
	$icon_color_primary = sanitize_hex_color( get_option( 'cbec_icon_color_primary', '#23D366' ) );
	$icon_color_secondary = sanitize_hex_color( get_option( 'cbec_icon_color_secondary', '#000000' ) );
	$tooltip_text = sanitize_text_field( get_option( 'cbec_tooltip_text', 'Need help?' ) );
	$tooltip_font_size = intval( get_option( 'cbec_tooltip_font_size', 14 ) );

	// Determine if the button should be displayed on mobile, desktop, or backend
	if ( wp_is_mobile() || $show_on_desktop === 'yes' || is_admin() ) {
		if ( ! wp_is_mobile() && $show_on_desktop !== 'yes' && ! is_admin() ) {
			return;
		}

		// Set the icon URL based on the selected icon type
		$icon_svg = '';
		$icon_img = '';
		if ($icon_type === 'custom' && !empty(get_option('cbec_custom_icon_url'))) {
			$icon_url = esc_url(get_option('cbec_custom_icon_url'));
			$file_extension = pathinfo($icon_url, PATHINFO_EXTENSION);

			// Check if the file extension is allowed
			$allowed_extensions = ['svg', 'jpeg', 'jpg', 'png', 'webp'];
			if (in_array($file_extension, $allowed_extensions)) {
				if ($file_extension === 'svg') {
					$icon_svg = file_get_contents($icon_url);
				} else {
					$icon_img = '<img src="' . $icon_url . '" alt="WhatsApp Icon" style="width: 100%; height: 100%;">';
				}
			} else {
				wp_die(esc_html__('Invalid file type.', 'coding-bunny-easy-chat-lite'));
			}
		} else {
			$icon_path = plugin_dir_path(dirname(__FILE__)) . 'assets/images/' . esc_attr($icon_type);
			if (file_exists($icon_path)) {
				$file_extension = pathinfo($icon_path, PATHINFO_EXTENSION);
				if ($file_extension === 'svg') {
					$icon_svg = file_get_contents($icon_path);
				} else {
					$icon_img = '<img src="' . plugin_dir_url(dirname(__FILE__)) . '/assets/images/' . esc_attr($icon_type) . '" alt="WhatsApp Icon" style="width: 100%; height: 100%;">';
				}
			}
		}

		// Output the styles for the button
		?>
		<style>
			.cbec-button {
				display: block;
				position: fixed;
				bottom: 20px;
				<?php echo esc_attr($position); ?>: 20px;
				width: <?php echo esc_attr($icon_size); ?>px;
				height: <?php echo esc_attr($icon_size); ?>px;
				z-index: 99;
			}

			.cbec-button:hover {
				transform: scale(1.1);
			}

			.cbec-button svg {
				width: 100%;
				height: 100%;
				fill: <?php echo esc_attr($icon_color_primary); ?>;
			}

			.cbec-button svg .primary {
				fill: <?php echo esc_attr($icon_color_primary); ?>;
			}

			.cbec-button svg .secondary {
				fill: <?php echo esc_attr($icon_color_secondary); ?>;
			}

			.cbec-button .tooltip {
				visibility: hidden;
				background-color: <?php echo esc_attr($icon_color_primary); ?>;
				color: <?php echo esc_attr($icon_color_secondary); ?>;
				font-weight: 700;
				font-size: <?php echo esc_attr($tooltip_font_size); ?>px;
				text-align: center;
				border-radius: 4px;
				padding: 5px 10px;
				position: absolute;
				z-index: 1;
				bottom: 125%;
				<?php echo esc_attr($position); ?>: 0;
				width: auto;
				max-width: 250px;
				white-space: nowrap;
			}

			.cbec-button .tooltip::after {
				content: "";
				position: absolute;
				top: 100%;
				<?php echo esc_attr($position); ?>: 15%;
				margin-left: -5px;
				border-width: 5px;
				border-style: solid;
				border-color: <?php echo esc_attr($icon_color_primary); ?> transparent transparent transparent;
			}

			.cbec-button:hover .tooltip {
				visibility: visible;
			}
			</style>

			<!-- WhatsApp link with the message -->
			<a href="https://wa.me/<?php echo esc_attr($prefix_number); ?><?php echo esc_attr($phone_number); ?>?text=<?php echo urlencode($message); ?>" class="cbec-button" target="_blank" rel="noopener" aria-label="Contact button">
				<?php
				if ($icon_svg) {
					echo $icon_svg; 
				} else {
					echo $icon_img;
				}
				?>
				<span class="tooltip">
					<?php echo esc_html($tooltip_text); ?>
				</span>
			</a>
			<?php
		}
	}

	add_action( 'wp_footer', 'cbec_lite_button' );