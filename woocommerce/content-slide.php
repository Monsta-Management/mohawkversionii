<?php
global $product;

$attachment_ids = $product->get_gallery_image_ids();
$main_links  = [];
$thumb_links = [];

// Function to safely get image size (fallback to actual file if metadata missing)
function monsta_get_image_size_data( $attachment_id ) {
	$meta = wp_get_attachment_metadata( $attachment_id );
	$url  = wp_get_attachment_url( $attachment_id );
	$width  = 0;
	$height = 0;

	if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
		$width  = $meta['width'];
		$height = $meta['height'];
	} else {
		// fallback: try to read directly from file
		$file_path = get_attached_file( $attachment_id );
		if ( file_exists( $file_path ) ) {
			$img_size = getimagesize( $file_path );
			if ( is_array( $img_size ) ) {
				$width  = $img_size[0];
				$height = $img_size[1];
			}
		}
	}

	return [
		'url'    => $url,
		'width'  => $width > 0 ? $width : 1600,
		'height' => $height > 0 ? $height : 1600,
	];
}

// Featured image
if ( has_post_thumbnail( $product->get_id() ) ) {
	$thumb_id   = get_post_thumbnail_id( $product->get_id() );
	$full_data  = monsta_get_image_size_data( $thumb_id );
	$thumb_data = wp_get_attachment_image_src( $thumb_id, 'thumbnail' );

	if ( $full_data['url'] ) {
		$main_links[] = $full_data;
	}
	if ( is_array( $thumb_data ) ) {
		$thumb_links[] = $thumb_data[0];
	}
}

// Gallery images
if ( ! empty( $attachment_ids ) ) {
	foreach ( $attachment_ids as $attachment_id ) {
		$data = monsta_get_image_size_data( $attachment_id );
		if ( $data['url'] ) {
			$main_links[]  = $data;
			$thumb_links[] = $data['url'];
		}
	}
}

// Placeholder fallback
if ( empty( $main_links ) ) {
	$placeholder = get_stylesheet_directory_uri() . '/images/placeholder.png';
	$main_links[] = [ 'url' => $placeholder, 'width' => 800, 'height' => 800 ];
	$thumb_links[] = $placeholder;
}
?>

<div class="product-slider-wrap u-pr">
	<?php if ( count( $thumb_links ) > 1 ) : ?>
		<div class="product-slider-thumbs u-pa h-100">
			<div class="thumb-slider">
				<?php foreach ( $thumb_links as $thumb_link ) : ?>
					<div class="thumb-item">
						<img src="<?php echo esc_url( $thumb_link ); ?>" alt="<?php echo esc_attr( basename( $thumb_link ) ); ?>">
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="product-slider-main">
		<div class="main-slider pswp-gallery" id="product-gallery">
			<?php foreach ( $main_links as $img ) : ?>
				<div class="main-item">
					<a  
						class="pswp-gallery__item"
						href="<?php echo esc_url( $img['url'] ); ?>"
						data-pswp-width="<?php echo esc_attr( $img['width'] ); ?>"
						data-pswp-height="<?php echo esc_attr( $img['height'] ); ?>"
					>
					    <div class="zoomit">
                            <svg xmlns="http://www.w3.org/2000/svg" height="32" width="32" viewBox="0 0 640 640">
                                <path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z"/>
                            </svg>
					    </div>
					
    					<img 
    						src="<?php echo esc_url( $img['url'] ); ?>" 
    						alt="<?php echo esc_attr( basename( $img['url'] ) ); ?>" 
    						class="pswp-trigger"
    					/>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
