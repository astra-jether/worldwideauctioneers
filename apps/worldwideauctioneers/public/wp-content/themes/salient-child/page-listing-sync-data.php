<?php
	/****
		Template Name: Manual Sync Data
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
							'still_on_sale'				=> $listing['sstillonsale'] == '1' ? 1 : 0,
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


					
				}
				echo $addcounter." product(s) added.";
				echo $updatecounter." product(s) updated.";	
						
			//}

			//wp_die( var_dump( $i ) );
		}
		
	}
?>