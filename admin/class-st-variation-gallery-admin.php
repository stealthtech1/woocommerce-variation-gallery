<?php
/**
 * Admin-specific functionality.
 */
class ST_VariationGallery_Admin {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles() {
		$screen = get_current_screen();
		if ( $screen && 'product' === $screen->id ) {
			wp_enqueue_style( $this->plugin_name, ST_VARIATION_GALLERY_URL . 'admin/css/st-variation-gallery-admin.css', array(), $this->version );
		}
	}

	public function enqueue_scripts() {
		$screen = get_current_screen();
		if ( $screen && 'product' === $screen->id ) {
			wp_enqueue_media();
			wp_enqueue_script( $this->plugin_name, ST_VARIATION_GALLERY_URL . 'admin/js/st-variation-gallery-admin.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, true );
			wp_localize_script( $this->plugin_name, 'stVarGalleryAdmin', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'st_save_variation_gallery' ),
			) );
		}
	}

	public function variation_gallery_field( $loop, $variation_data, $variation ) {
		$variation_id = $variation->ID;
		$gallery_ids  = get_post_meta( $variation_id, '_st_variation_gallery', true );
		$gallery_ids  = is_array( $gallery_ids ) ? $gallery_ids : array();
		?>
		<div class="form-row form-row-full st-variation-gallery-wrap">
			<label><?php esc_html_e( 'Variation Gallery', 'st-variation-gallery' ); ?></label>
			<div class="st-variation-gallery-container" data-variation-id="<?php echo esc_attr( $variation_id ); ?>">
				<ul class="st-variation-gallery-images">
					<?php foreach ( $gallery_ids as $image_id ) :
						$image = wp_get_attachment_image_src( $image_id, 'thumbnail' );
						if ( $image ) : ?>
							<li class="st-gallery-image" data-id="<?php echo esc_attr( $image_id ); ?>">
								<img src="<?php echo esc_url( $image[0] ); ?>" />
								<a href="#" class="st-remove-image">&times;</a>
							</li>
						<?php endif;
					endforeach; ?>
				</ul>
				<a href="#" class="button st-add-gallery-images"><?php esc_html_e( 'Add Images', 'st-variation-gallery' ); ?></a>
			</div>
		</div>
		<?php
	}

	public function ajax_save_variation_gallery() {
		check_ajax_referer( 'st_save_variation_gallery', 'nonce' );

		if ( ! current_user_can( 'edit_products' ) ) {
			wp_send_json_error( 'Permission denied' );
		}

		$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		$gallery      = isset( $_POST['gallery'] ) ? sanitize_text_field( wp_unslash( $_POST['gallery'] ) ) : '';

		if ( ! $variation_id ) {
			wp_send_json_error( 'Invalid variation ID' );
		}

		$gallery_ids = array_filter( array_map( 'absint', explode( ',', $gallery ) ) );

		if ( ! empty( $gallery_ids ) ) {
			update_post_meta( $variation_id, '_st_variation_gallery', $gallery_ids );
		} else {
			delete_post_meta( $variation_id, '_st_variation_gallery' );
		}

		wp_send_json_success();
	}
}
