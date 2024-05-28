<?php
/**
 * Trip Reviews Menu for WP Travel Engine Trip Reviews
 * 
 */
if ( ! class_exists( 'WTE_Trip_Reviews_Backend_Menu' ) ) :
	/**
	 * Menu Class.
	 */
	class WTE_Trip_Reviews_Backend_Menu extends WTE_Trip_Review_Library{

		//Create Settings and Review Page for Trip Reviews
		public function init() {
			
	        //add_action( 'wp_travel_engine_trip_review_form',array( $this,'wp_travel_engine_trip_review_form') );
	        add_action( 'admin_menu' , array( $this, 'trip_review_page' ) );
	        add_filter( 'wpte_get_global_extensions_tab', array($this,'wp_travel_engine_trip_review_form' ) );
	        //Load Ajax Action
	        add_action( 'wp_ajax_wpte_insert_comment', array( $this, 'wpte_insert_comment' ) );
	        add_action( 'wp_ajax_nopriv_wpte_insert_comment', array( $this,'wpte_insert_comment' ) );
		}

		/**
		 * Load Trip Review Settings
		 */
		public function wp_travel_engine_trip_review_form($sub_tabs)
	    { 
	        $sub_tabs['wte_trip_reviews'] = array(
            'label'        => __( 'Trip Reviews', 'wte-trip-review' ),
            'content_path' => WTE_TRIP_REVIEW_BASE_PATH.'/includes/admin/wte-trip-reviews-global-extension-subsetting.php',
            'current' => false
         );
        return $sub_tabs;
	   }
		/**
		 * Load Trip Reviews Page
		 */
		public function trip_review_page()
	    { 
	        add_submenu_page('edit.php?post_type=booking', 'Trip Reviews', 'Trip Reviews', 'manage_options', 'reviews', array($this,'wp_travel_engine_reviews_callback_function'));
	    }

	    /**
	    * 
	    * Displays reviews.
	    *
	    * @since 1.0.0
	    */
	    public function wp_travel_engine_reviews_callback_function() { 
	        require WTE_TRIP_REVIEW_BASE_PATH . '/includes/admin/reviews.php'; 
	    }

	    /**
	    * 
	    * Insert Comment through Backend Ajax.
	    *
	    * @since 1.0.0
	    */
	    public function wpte_insert_comment() {
			$id     = isset($_POST['id'])?$_POST['id']:'';
			$name   = isset($_POST['name'])?$_POST['name']:'';
			$email  = isset($_POST['email'])?$_POST['email']:'';
			$msg    = isset($_POST['msg'])?$_POST['msg']:'';
			$title  = isset($_POST['title'])?$_POST['title']:'';
			$stars  = isset($_POST['stars'])?$_POST['stars']:'';
			$upload = isset($_POST['upload'])?$_POST['upload']:'';
			$experience_date = isset($_POST['experience_date'])?$_POST['experience_date']:'';
			$client_location = isset($_POST['client_location'])?$_POST['client_location']:'';
			$date   = isset($_POST['date'])?$_POST['date']:'';
			$gallery_images = isset($_POST['gallery_images'])?$_POST['gallery_images']:'';
			$gallery_max_count =  isset($_POST['gallery_max_count'])?$_POST['gallery_max_count']:'';		
	        
	        global $current_user; //for this example only :)

	        $commentdata = array(
				'comment_post_ID'      => $id, // to which post the comment will show up
				'comment_author_url'   => '',
				'comment_author'       => $name, //fixed value - can be dynamic 
				'comment_author_email' => $email, //fixed value - can be dynamic 
				'comment_content'      => $msg, //fixed value - can be dynamic 
				'comment_type'         => '', //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
				'comment_parent'       => 0, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
				'comment_approved'     => 0,
				'user_id'              => $current_user->ID, //passing current user ID or any predefined as per the demand
				'comment_date'         => $date,
				'experience_date'      => $experience_date,
				'client_location'      => $client_location,
				'gallery_images' 	   => $gallery_images,
				'gallery_max_count'	   => $gallery_max_count
			);
			
	        $comment_id = wp_new_comment( $commentdata );

	        if ( ( isset( $_POST['stars'] ) ) && ( $_POST['stars'] != '') )
	        {
	            $stars = wp_filter_nohtml_kses($_POST['stars']);
	            add_comment_meta( $comment_id, 'stars', $stars );
	        }

	        if ( ( isset( $_POST['upload'] ) ) && ( $_POST['upload'] != '') )
	        {
	            $photo = wp_filter_nohtml_kses($_POST['upload']);
	            add_comment_meta( $comment_id, 'photo', $photo );
	        }

	        if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') )
	        {
	            $title = wp_filter_nohtml_kses($_POST['title']);
	            add_comment_meta( $comment_id, 'title', $title );
			}

			if ( ( isset( $_POST['experience_date'] ) ) && ( $_POST['experience_date'] != '') )
	        {
	            $experience_date = wp_filter_nohtml_kses($_POST['experience_date']);
	            add_comment_meta( $comment_id, 'experience_date', $experience_date );
			}

			if ( ( isset( $_POST['client_location'] ) ) && ( $_POST['client_location'] != '') )
	        {
	            $client_location = wp_filter_nohtml_kses($_POST['client_location']);
	            add_comment_meta( $comment_id, 'client_location', $client_location );
			}

			if ( ( isset( $_POST['gallery_images'] ) ) && ( $_POST['gallery_images'] != '') )
	        {
	            $gallery_images = $_POST['gallery_images'];
	            add_comment_meta( $comment_id, 'gallery_images', $gallery_images );
			}

			if ( ( isset( $_POST['gallery_max_count'] ) ) && ( $_POST['gallery_max_count'] != '') )
	        {
	            $gallery_max_count = wp_filter_nohtml_kses($_POST['gallery_max_count']);
	            add_comment_meta( $comment_id, 'gallery_max_count', $gallery_max_count );
			}
			
	        wp_set_comment_status( $comment_id, 'approve' );
	        if($comment_id){
	            echo 'Your response has been recorded.';
	        }
	        exit();   
	    }
	}
endif;

$obj = new WTE_Trip_Reviews_Backend_Menu();
$obj->init();
