<?php
	/****
		Template Name: Manual Sync
	****/

	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	wp_head();

	$addcounter = 0;
	$updatecounter = 0;
	$auctions = get_field( 'auctions_to_sync', 'wwa_options' );

	foreach( $auctions as $auction )
	{
		if( function_exists( 'wwa_pull_listings_by_event' ) )
		{
			ini_set('memory_limit', '51200M');
        	set_time_limit ( 0 );

			//for ( $i=0; $i < $estimate; $i+=20 )
			//{
				//wp_die( var_dump( $i ) );
				//$listings = wwa_pull_listings_by_event( $auction['id'], 't_inventory', '', '', true, 20, $i );

				$listings = wwa_pull_listings_by_event( $auction['id'], 't_inventory', '', '', false );

				if( empty( $listings ) || ! $listings )
				{
					wp_die( 'All data has been synced!!!'.$auction['id'].$auction['name'] );
				}

				foreach ( $listings as $listing )
				{
					//if( $listing['sreference'] != '8' ) continue;

					$post_info = [
						'post_author'	=> get_current_user_id(),
						'post_title'	=> $listing['syear'] . ' ' . $listing['smake'] . ' ' . $listing['smodel'] . ' ' . $listing['sstyle'],
						'post_type'		=> 'product',
						'post_status'	=> 'publish',
						'meta_input'	=> [
							'reference_no' 				=> $listing['sreference'],
							'auction_no' 				=> $auction['id'],
							'lot_number'				=> $listing['slotnumber'],
							'selling_day'				=> $listing['sday'],
							'item_color'				=> $listing['scolor'],
							'offered_without_reserve'	=> $listing['breserve'] == '1' ? 0 : 1,
							'short_description'			=> $listing['sshortdescription'],
							'long_description'			=> $listing['longdescription'],
							'features'					=> $listing['sfeatureitems'],
							'make'  					=> $listing['smake'],
							'addendum'					=> $listing['saddendum'],
							'featured'					=> $listing['bfeatured'] == '1' ? 1 : 0,
							'year'						=> $listing['syear'],
							'vin'						=> $listing['svin'],
							'raw_data'					=> json_encode( $listing ),
						]
					];

					$meta_query = [
						'relation' 		=> 'AND',
						[
							'key' 		=> 'auction_no',
							'value' 	=> $auction['id'],
							'compare' 	=> '=',
						],
						[
							'key' 		=> 'reference_no',
							'value' 	=> $listing['sreference'],
							'compare' 	=> '=',
						],
					];
		
					$args = [
						'meta_query' 		=> $meta_query,
						'posts_per_page' 	=> '1',
						'post_type' 		=> 'product',
						'post_status' 		=> ['publish'],
						'fields'			=> 'ids'
					];

					$product = get_posts( $args );

					if( empty( $product ) )
					{
						//echo $listing['slongdescription'];
						$post_id = wp_insert_post( $post_info );
						$product_image_url = "https://listings.worldwideauctioneers.com/photos/{$auction['id']}/{$listing['sreference']}/1.jpg";

					$upload_dir = wp_upload_dir();

					$attachment_id = attachment_url_to_postid( $product_image_url );

			        if( ! $attachment_id )
			        {
						$upload_dir = wp_upload_dir();

						$img_url = "/srv/users/worldwideauctioneers/apps/worldwideauctioneers/public/photos/{$auction['id']}/{$listing['sreference']}/1.jpg";

						if( file_exists( $img_url ) )
						{
					        require_once( ABSPATH . "/wp-load.php");
							require_once( ABSPATH . "/wp-admin/includes/image.php");
							require_once( ABSPATH . "/wp-admin/includes/file.php");
							require_once( ABSPATH . "/wp-admin/includes/media.php");
							
							$img_url = "https://listings.worldwideauctioneers.com/photos/{$auction['id']}/{$listing['sreference']}/1.jpg";

							// Download url to a temp file
							$tmp = download_url( $img_url );

							if ( is_wp_error( $tmp ) ) return false;
							
							// Get the filename and extension ("photo.png" => "photo", "png")
							$filename = pathinfo($img_url, PATHINFO_FILENAME);
							$extension = pathinfo($img_url, PATHINFO_EXTENSION);
							
							// An extension is required or else WordPress will reject the upload
							if ( ! $extension ) {
								// Look up mime type, example: "/photo.png" -> "image/png"
								$mime = mime_content_type( $tmp );
								$mime = is_string($mime) ? sanitize_mime_type( $mime ) : false;
								
								// Only allow certain mime types because mime types do not always end in a valid extension (see the .doc example below)
								$mime_extensions = array(
									'image/jpg'          => 'jpg',
									'image/jpeg'         => 'jpeg',
									'image/gif'          => 'gif',
									'image/png'          => 'png',
								);
								
								if ( isset( $mime_extensions[$mime] ) ) 
								{
									// Use the mapped extension
									$extension = $mime_extensions[$mime];
								}
								else
								{
									// Could not identify extension
									@unlink($tmp);
									return false;
								}
							}
							
							// Upload by "sideloading": "the same way as an uploaded file is handled by media_handle_upload"
							$args = array(
								'name' => "$filename.$extension",
								'tmp_name' => $tmp,
							);

							update_option( 'auction_no', $auction['id'] );
							update_option( 'reference_no', $listing['sreference'] );

							add_filter( 'upload_dir', 'wwa_photos_directory' );

							// Do the upload
							$attachment_id = media_handle_sideload( $args, $post_id );
							
							// Cleanup temp file
							@unlink($tmp);
							
							// Error uploading
							if ( is_wp_error($attachment_id) ) return false;
						}
						else
						{
							// set default image if not uploaded to server
							$attachment_id = attachment_url_to_postid( 'https://listings.worldwideauctioneers.com/wp-content/uploads/WA_Final2-2-1.png' );
						}
			        }

			        set_post_thumbnail( $post_id, $attachment_id );
			       
			       	update_post_meta( $post_id, '_product_image_gallery', $attachment_id );

						$addcounter++;
					}
					

					else
					{
						$post_info['ID'] = $product[0];
						$post_id = wp_update_post( $post_info, false );
						$updatecounter++;
					}

					$term = term_exists( $auction['slug'], 'product_cat' );
					
					
					if ( null === $term )
					{
						$term = wp_insert_term( $auction['name'], 'product_cat' );
					}

					if ( ! is_wp_error( $term ) )
					{
						wp_set_object_terms( $post_id, $term['name'], 'product_cat' );
					}
					else 
					{
						$error_string = $term->get_error_message();

						wp_die( var_dump( $error_string ) );
					}


					
					/*echo '<pre>';
					var_dump( $attachment_id );
					wp_die( var_dump( $post_id ) );
					echo '</pre>';*/
					
				}
				echo $addcounter." product(s) added.";
				echo $updatecounter." product(s) updated.";	
						
			//}

			//wp_die( var_dump( $i ) );
		}
		
	}
?>