<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'front_end_job_submission', [$this, 'front_end_job_submission']);
		add_action( 'wp_ajax_frontend_post_job', [$this, 'frontend_post_job']);
		add_action('wp_ajax_nopriv_frontend_post_job', [$this, 'frontend_post_job']);
		add_shortcode( 'get_jobs_list', [$this, 'get_jobs_list'] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'data-tables-css', 'https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css' );
		wp_enqueue_style( 'date-time-picker-css', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'data-table-js', 'https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js', array( 'jquery' ), null, false );
		wp_enqueue_script( 'date-time-picker-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js', array( 'jquery' ), null, false );
		wp_enqueue_script( 'job-form-js', plugin_dir_url( __FILE__ ) . 'js/plugin-name-public.js', array( 'jquery','date-time-picker-js' ), null, false );
		wp_localize_script( 'job-form-js', 'ajax_object', array( 'ajaxurl' => admin_url('admin-ajax.php') ) );
	}

	function front_end_job_submission($atts)
	{
		//get job_categories, job_employment_type, job_type, topics

		$__job_categories = get_terms(array(
			"taxonomy" => "job_categories",
			"hide_empty"	=>	false
		));
		$__job_employment_types = get_terms(array(
			"taxonomy" => "job_employment_type",
			"hide_empty"	=>	false
		));
		$__job_types = get_terms(array(
			"taxonomy" => "job_type",
			"hide_empty"	=>	false
		));
		$__topics = get_terms(array(
			"taxonomy" => "topics",
			"hide_empty"	=>	false
		));
		
		
		$job_categories = '<label for="job_category">Choose Category<select name="job_category" id="job_category">';
		$job_employment_types = '<label for="job_employment_type">Choose Employment Type<select name="job_employment_type" id="job_employment_type">';
		$job_types = '<label for="job_type">Choose Job Type<select name="job_type" id="job_type">';
		$topics = '<label for="topic">Choose Topic<select name="topic" id="topic">';

		if( !empty($__job_categories) ){
			foreach( $__job_categories as $job_category ){
				$job_categories .= '<option value="'.esc_attr( $job_category->term_id ).'">'.esc_html( $job_category->name ).'</option>';
			}
		}

		if( !empty($__job_employment_types) ){
			foreach( $__job_employment_types as $job_employment_type ){
				$job_employment_types .= '<option value="'.esc_attr( $job_employment_type->term_id ).'">'.esc_html( $job_employment_type->name ).'</option>';
			}
		}

		if( !empty($__job_types) ){
			foreach( $__job_types as $job_type ){
				$job_types .= '<option value="'.esc_attr( $job_type->term_id ).'">'.esc_html( $job_type->name ).'</option>';
			}
		}

		if( !empty($__topics) ){
			foreach( $__topics as $topic ){
				$topics .= '<option value="'.esc_attr( $topic->term_id ).'">'.esc_html( $topic->name ).'</option>';
			}
		}
		
		$job_categories .= '</select></label>';
		$job_employment_types .= '</select></label>';
		$job_types .= '</select></label>';
		$topics .= '</select></label>';

		$atts = shortcode_atts(array(
			'id' => 'submit_job',
		), $atts);

		$html = '<div id="jobloader"><img src="/wp-content/themes/Avada-Child-Theme/images/jobloader.gif" /></div>
		<div id="jobadded"><p style="font-size:1.5rem;">Thank you for posting job opportunity at PublicGardens. We will review and post it in an hour(during business hours).</p></div>
		<div class="submit_job" id="jobform">
		<form action="#" method="POST" role="form">
		<label class="" for="job_title"> Job Title <br />
		<input type="text" name="job_title" class="form-control " id="job_title" />
		</label>
		
		<label class="" for="full_name"> Full name <br />
		<input type="text" name="full_name" class="form-control " id="full_name" />
		</label>
		<label class="" for="company"> Company <br />
		<input type="text" name="company" class="form-control " id="company" />
		</label>
		<label class="" for="job_location"> Job Location <br />
		<textarea name="job_location" class="form-control " id="job_location" rows="10" cols="10"></textarea>
		</label>
		<label class="" for="job_description"> Job Description <br />
		<textarea  name="job_description" class="form-control " id="job_description" rows="10" cols="10"></textarea>
		</label>
		<label class="" for="about_us"> About Company <br />
		<textarea name="about_us" class="form-control " id="about_us" rows="10" cols="10"></textarea>
		</label>
		<label class="" for="major_function"> Major Function <br />
		<textarea name="major_function" class="form-control " id="major_function" rows="10" cols="10"></textarea>
		</label>
		<label class="" for="job_qualifications"> Duties & Responsibilities <br />
		<textarea name="job_qualifications" class="form-control " id="job_qualifications" rows="10" cols="10"></textarea>
		</label>
		<label class="" for="education_and_experience"> Education and Experience <br />
		<textarea name="education_and_experience" class="form-control " id="education_and_experience" rows="10" cols="10"></textarea>
		</label>
		<label class="" for="additional_information"> Additional Information <br />
		<textarea name="additional_information" class="form-control " id="additional_information" rows="10" cols="10"></textarea>
		</label>
		<label class="" for="position_application"> Application Instructions <br />
		<textarea name="position_application" class="form-control " id="position_application" rows="10" cols="10"></textarea>
		</label>
		<label class="" for="application_deadline"> Application Deadline <br />
		<input type="text" name="application_deadline" class="form-control  date_field" id="application_deadline" />
		</label>
		<label class="" for="apply_url"> Apply URL <br />
		<input type="text" name="apply_url" class="form-control " id="apply_url" />
		</label>
		<label class="" for="apply_email"> Apply Email <br />
		<input type="text" name="apply_email" class="form-control " id="apply_email" />
		</label>';
		
		
		$html .= $job_categories;
		$html .= $job_employment_types;
		$html .= $job_types;
		$html .= $topics;
		
		$html .= '<button type="button" name="submit_job" id="submit_job" class="button"> Submit Job </button>
		</label>';
		$html .= '</form></div>';
		
		return $html;
	}
	function frontend_post_job() {
		if(isset($_POST['job_title']) && isset($_POST['job_location']) && ( isset($_POST['apply_url']) || isset($_POST['apply_email']) ) && isset($_POST['company'])){
			
			$category = $_POST['job_category'];
			$empType = $_POST['job_employment_type'];
			$jobType = $_POST['job_type'];
			$topic = $_POST['topic'];

			$inserted_post_id = wp_insert_post(array(
				'post_title' 	=> $_POST['job_title'],
				'post_type' 	=> 'job',
				'post_status' 	=> 'pending',
				'meta_input' 	=> array(
					'full_name' 				=> $_POST['full_name'],
					'job_title' 				=> $_POST['job_title'],
					'company' 					=> $_POST['company'],
					'job_location' 				=> $_POST['job_location'],
					'job_description' 			=> $_POST['job_description'],
					'about_us' 					=> $_POST['about_us'],
					'major_function' 			=> $_POST['major_function'],
					'job_qualifications' 		=> $_POST['job_qualifications'],
					'education_and_experience' 	=> $_POST['education_and_experience'],
					'additional_information' 	=> $_POST['additional_information'],
					'position_application' 		=> $_POST['position_application'],
					'application_deadline' 		=> $_POST['application_deadline'],
					'apply_url' 				=> $_POST['apply_url'],
					'apply_email' 				=> $_POST['apply_email']
				)
			));

			if($inserted_post_id){

				wp_set_object_terms($inserted_post_id, intval( $category ), 'job_categories');
				wp_set_object_terms($inserted_post_id, intval( $empType ), 'job_employment_type');
				wp_set_object_terms($inserted_post_id, intval( $jobType ), 'job_type');
				wp_set_object_terms($inserted_post_id, intval( $topic ), 'topics');

            	//send a mail to listed addresses notifying new job posting
                $to = 'james@bestwebsite.com';
                $subject = 'New Job Posting Submitted';
                $body = '<h5>Someone submitted a new job, please review the following job and publish.</h5><br/><p><a href="https://www.staging.publicgardens.org/wp-admin/post.php?post='.$inserted_post_id.'&action=edit" target="_blank">https://www.staging.publicgardens.org/wp-admin/post.php?post='.$inserted_post_id.'&action=edit</a></p>';
                $headers = array(
                    'Content-Type: text/html; charset=UTF-8'
                );
                wp_mail($to, $subject, $body, $headers);
                echo 'job added';
                
			}else{
				echo 'unable to add a job';
			}
		}else{
			echo 'invalid request';
		}
		die();
	}

	function get_jobs_list()
	{
		$jobs = new WP_Query(array(
			'post_type' => 'job',
			'posts_per_page' => -1
		));
		$html = '<div class="jobs-list">
		<table id="jobsTable" class="jobsTable">
		<thead>
		<tr>
		<th>Title</th>
		<th>Company</th>
		<th>Location</th>
		<th>Closing Date</th>
		<th></th>
		</tr>
		</thead><tbody>'; 
		if( $jobs->have_posts() ):
			while( $jobs->have_posts() ):
				$jobs->the_post();
				$html .= '<tr>';
				$jobs->the_post();
				// $html .= '<td>'.get_the_title().'</td>';
				// $html .= get_field('full_name');	
				$html .= '<td>'.get_field('job_title').'</td>'; 				
				$html .= '<td>'.get_field('company').'</td>'; 
				$html .= '<td>'.get_field('job_location').'</td>'; 				
				// $html .= get_field('job_description'); 			
				// $html .= get_field('about_us');					
				// $html .= get_field('major_function');		
				// $html .= get_field('job_qualifications');		
				// $html .= get_field('education_and_experience');	
				// $html .= get_field('additional_information');	
				// $html .= get_field('position_application');		
				$html .= '<td>'.get_field('application_deadline').'</td>';
				$html .= '<td><a href="'.get_the_permalink().'">View</a></td>';	
				// $html .= get_field('apply_url');		
				// $html .= get_field('apply_email');			
				$html .= '</tr>';
			endwhile;
		endif;
		$html .= '</tbody></table></div>';
		return $html;
	}

}
