<?php 

class Feed {

	public $meta_fields = array(
								'feed_custom_creationid' => 'Creation ID',
								'feed_custom_domainid' => 'Domain ID',
								'feed_custom_vendor' => 'Vendor',
								'feed_custom_iframe_url' => 'Link Iframe',
								'feed_custom_software' => 'Software',
								'feed_custom_slottypes' => 'Slot type',
								'feed_custom_slotthemes' => 'Slot themes',
								'feed_custom_slotrtp' => 'Slot rtp',
								'camp_descriere_2' => 'Camp descriere2',

							);

     public function __construct()
    {
    	add_action( 'wp_ajax_wpfcrud_update_posts', array($this, 'updateFeedPostType') );
    }
    public function getFeed($feed_url) {
		if( !empty($feed_url) ) {

			$ch = curl_init();
	        // set url
	        curl_setopt($ch, CURLOPT_URL, $feed_url);
	        //return the transfer as a string
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        // $output contains the output string
	        $output = curl_exec($ch);
	        // close curl resource to free up system resources
	        curl_close($ch);  

			// $out_clear =  str_replace("\r\n", ",", $output);
			$out_clear =  str_replace("*", "0", $output);
	        $out1 = explode("\r\n", $out_clear);

// $limit=0;

	        foreach ($out1 as $o) {
	        	// if($limit < 5){
	        		$post_type = "game";
	        		$feed_data_clean =  json_decode($o,true);

	        		if( ( isset( $feed_data_clean['data']['enabled'] ) && $feed_data_clean['data']['enabled'] == 1 ) && 
	        			( isset( $feed_data_clean['data']['playMode']['fun'] ) && $feed_data_clean['data']['playMode']['fun'] == 1 ) ) 	{

	        			$this->addFeedToPostType( $post_type, $feed_data_clean );

	        		}

	        	// }

// $limit++;	
	        }

	        echo "done"; //ajax response
	        
		} else {
			return false; //no url feed
		}

		wp_die();

    }

    public function updateFeedFromFile() {

    	$uploaded_file_name = $this->uploadFile();
    	$file_location = dirname(__FILE__) . "/../wpfeedcrud_uploads/".$uploaded_file_name['file_name'];


    	if( !empty($uploaded_file_name) ){

    		$file_data = file_get_contents ( $file_location );

    		$file_data_arr = explode( "\n",  $file_data );

			$this->showContentTable($file_data_arr);

			// delete the file
			if(file_exists($file_location)){
				unlink($file_location);

			}else{
				print_r('Selected file does not exist');
			}
    	}


    }

    public function uploadFile( $input_file_name = 'updatefile' ){

    	if(isset($_FILES[$input_file_name])){

			$errors= array();
			$uploaded_file_data = array(
				'file_name' =>  $_FILES[$input_file_name]['name'],
				'file_size' => $_FILES[$input_file_name]['size'],
				'file_tmp' => $_FILES[$input_file_name]['tmp_name'],
				'file_type' => $_FILES[$input_file_name]['type'],
				'file_ext' => strtolower(end(explode('.',$_FILES[$input_file_name]['name']))),

			);

			$extensions= array("csv");

			if( in_array( $uploaded_file_data['file_ext'], $extensions ) === false ) {
				$errors[]="extension not allowed, please choose a csv file.";
			}

			if(empty($errors)==true){
		         move_uploaded_file( $uploaded_file_data['file_tmp'], dirname(__FILE__) . "/../wpfeedcrud_uploads/" . $uploaded_file_data['file_name'] );
		         return $uploaded_file_data;
		      }else{
		         print_r($errors);
		         return false;
		      }

		}

    }

    public function showContentTable( $file_data_arr = null ) {
    	
    	$posts_table_fields = $this->getPostTableFields(); //get post table fields
		$drodpown_table_fields = array();

		
    	foreach ( $posts_table_fields  as $posts_table_field ) {
			$drodpown_table_fields[] = $posts_table_field->Field;
    	}

    	
    	foreach ( $this->meta_fields  as $meta_field ) {
			$drodpown_table_fields[]  = $meta_field;
    	}

    	//$drodpown_table_fields is accesed in the template now

    	include_once( __DIR__ . '/../templates/contenttable.php');
    }

    public function updateFeedPostType( $post_type = "game", $feed_array = array() ) {
    	echo "ssss";
    	wp_die();
    }

    public function addFeedToPostType( $post_type = "game", $feed_array = array() ) {

    	$content = (!empty($feed_array['data']['presentation']['description']) && isset($feed_array['data']['presentation']['description'][0]) )? $feed_array['data']['presentation']['description'][0]:'';
    	$args = array(
					'post_type' => "game",
					'post_name' => $feed_array['data']['presentation']['gameName'][0],
					'post_title' => $feed_array['data']['presentation']['gameName'][0],
					'post_content' => $content,
					'post_status' => 'publish',
					'comment_status' => 'closed',   // if you prefer
					'ping_status' => 'closed',      // if you prefer
					'post_modified' => date('Y-m-d h:i:s', strtotime($feed_array['data']['creation']['lastModified'])),
					'post_date_gmt' => date('Y-m-d h:i:s', strtotime($feed_array['data']['creation']['lastModified'])),
					// 'post_category' => implode(",", $feed_array['data']['categories']),
					'tags_input' =>  (!empty($feed_array['data']['tags']))? implode(",", $feed_array['data']['tags']): '',

    				);	

    	if( $post_id = wp_insert_post( $args ) ) {
    		update_post_meta( $post_id, 'feed_custom_creationid', $feed_array['data']['id'] );
    		update_post_meta( $post_id, 'feed_custom_domainid', $feed_array['domainID'] );
    		update_post_meta( $post_id, 'feed_custom_vendor', $feed_array['data']['vendor'] );
    		update_post_meta( $post_id, 'feed_custom_iframe_url', esc_html( $feed_array['data']['url']) );
    		update_post_meta( $post_id, 'feed_custom_software', $feed_array['data']['property']['license']);
    		update_post_meta( $post_id, 'feed_custom_slottypes', $feed_array['data']['categories'][0]);
    		update_post_meta( $post_id, 'feed_custom_slotthemes', $feed_array['data']['presentation']['gameName'][0]);
    		update_post_meta( $post_id, 'feed_custom_slotrtp', $feed_array['data']['theoreticalPayOut']);

    		$this->addPostImage($feed_array['data']['presentation']['thumbnail'][0], $post_id);
    	}

    }

    /*
    *	$image_url = link of image .. without http:
    *	$parent_post_id = $post_id
    */
    public function addPostImage($image_url, $parent_post_id){
    	// $filename should be the path to a file in the upload directory.
    	$uploaded_file = $this->downloadPostImage($image_url);


		$filename = $uploaded_file['filename'];

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $uploaded_file['local_url'] . '/' . $uploaded_file['filename'] . $uploaded_file['type'], 
			'post_mime_type' => $uploaded_file['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
		
		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		set_post_thumbnail( $parent_post_id, $attach_id );

    }

    public function downloadPostImage($url){
    	// Gives us access to the download_url() and wp_handle_sideload() functions
		require_once( ABSPATH . 'wp-admin/includes/file.php' );

		$url = "http:" . $url;
		$timeout_seconds = 5;

		// Download file to temp dir
		$temp_file = download_url( $url, $timeout_seconds );


		$uploaded_file = array();

		if ( !is_wp_error( $temp_file ) ) {

		    // Array based on $_FILE as seen in PHP file uploads
		    $file = array(
		        'name'     => basename($url), // ex: wp-header-logo.png
		        'type'     => 'image/png',
		        'tmp_name' => $temp_file,
		        'error'    => 0,
		        'size'     => filesize($temp_file),
		    );

		    $overrides = array(
		        // Tells WordPress to not look for the POST form
		        // fields that would normally be present as
		        // we downloaded the file from a remote server, so there
		        // will be no form fields
		        // Default is true
		        'test_form' => false,

		        // Setting this to false lets WordPress allow empty files, not recommended
		        // Default is true
		        'test_size' => true,
		    );

		    // Move the temporary file into the uploads directory
		    $results = wp_handle_sideload( $file, $overrides );

		    if ( !empty( $results['error'] ) ) {
		        // Insert any error handling here
		    	//echo "failed to load image";
		    } else {

		        $uploaded_file['filename']  = $results['file']; // Full path to the file
		        $uploaded_file['local_url'] = $results['url'];  // URL to the file in the uploads dir
		        $uploaded_file['type']      = $results['type']; // MIME type of the file

		        // Perform any actions here based in the above results

		    }

		    return $uploaded_file;

		}


    }

    public function getPostTableFields(){
    	global $wpdb;

    	$query = "DESCRIBE " . $wpdb->prefix . 'posts';
    	$existing_columns =$wpdb->get_results( $query );
		return $existing_columns;
    }



}