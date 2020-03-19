<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tahir.codes/
 * @since      1.0.0
 *
 * @package    Idlcheckqs
 * @subpackage Idlcheckqs/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Idlcheckqs
 * @subpackage Idlcheckqs/admin
 * @author     Tahir Iqbal <tahiriqbal09@gmail.com>
 */
class Idlcheckqs_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Idlcheckqs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Idlcheckqs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/idlcheckqs-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Idlcheckqs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Idlcheckqs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/idlcheckqs-admin.js', array( 'jquery' ), $this->version, false );

	}

	 function action_woocommerce_checkout_order_processed( $order_id ){
	 	$items =  WC()->cart->get_cart();
		 $term_id = 0;
		 $productCatMetaTitle ="";
		foreach($items as $cart_item ){
            $product_id = $cart_item['product_id'];
			$terms = get_the_terms( $product_id, 'product_cat' );
			foreach ($terms as $term) {
			   $term_id = $term->term_id;
			   if (get_term_meta($term_id, 'idlpharmcheck_checkbox', true)) {
			   		$productCatMetaTitle = get_term_meta($term_id, 'idlpharmcheck_checkbox', true);
			   }
			}
		}

		if ($productCatMetaTitle == "on") {
			$options = get_option( 'idl_quiz_option' );
			$to = get_option( 'admin_email' );
			//echo($to);$message = urlencode($row->sms);
		    $subject = 'Questions And Anss From Quiz against order: '.$order_id;
		    $message = "Questions Are: "."\n";
		    if ($options['quiz1_checkbox']) {
		    	$message = "Q:".$options["quiz1"]."\n"."Ans: ".$_POST['customised_field_name_age']."\n";
		    }
		    if ($options['quiz2_checkbox']) {
		    	$message .= "Q:".$options["quiz2"]."\n"."Ans: ".$_POST['customised_field_symtoms']."\n";
		    }
		    if ($options['quiz3_checkbox']) {
		    	$message .= "Q:".$options["quiz3"]."\n"."Ans: ".$_POST['customised_field_pregnant']."\n";
		    }
		    if ($options['quiz4_checkbox']) {
		    	$message .= "Q:".$options["quiz4"]."\n"."Ans: ".$_POST['customised_field_breast_feeding']."\n";
		    }
		    if ($options["quiz5_checkbox"]) {
		    	$message .= "Q:".$options["quiz5"]."\n"."Ans: ".$_POST['customised_field_medical_conditions']."\n";
		    	$message .= "Q:".$options["quiz5"]."/if yes"."\n"."Ans: ".$_POST['customised_field_medical_conditions_describe']."\n";
		    }
		    if ($options["quiz6_checkbox"]) {
		    	$message .= "Q:".$options["quiz6"]."\n"."Ans: ".$_POST['customised_field_medicines']."\n";
		    	$message .= "Q:".$options["quiz6"]."/if yes"."\n"."Ans: ".$_POST['customised_field_medicines_describe']."\n";
		    }
		    $headers = array('Content-Type: text/html; charset=UTF-8');
		    wp_mail($to, $subject, $message);
		}
	}

	function idlpharmcheck_add_new_meta_field() {    ?>
	    <div class="form-field">
	        <label for="idlpharmcheck_checkbox"><?php _e('Add Quiz to Category', 'wh'); ?></label>
	        <input type="checkbox" name="idlpharmcheck_checkbox" id="idlpharmcheck_checkbox">
	    </div>
	    <?php
	}

	function idlpharmcheck_cat_edit_form_fields($term) {
	    $term_id = $term->term_id;
	    $idlpharmcheck_checkbox = get_term_meta($term_id, 'idlpharmcheck_checkbox', true);
	    $wh_meta_desc = get_term_meta($term_id, 'wh_meta_desc', true);
	    $value ="";
	    if (esc_attr($idlpharmcheck_checkbox)=="on") {
	    	$value= "checked";
	    }
	    ?>
	    <tr class="form-field">
	        <th scope="row" valign="top"><label for="idlpharmcheck_checkbox"><?php _e('Add Quiz to Category', 'wh'); ?></label></th>
	        <td>
	            <input type="checkbox" name="idlpharmcheck_checkbox" id="idlpharmcheck_checkbox"  <?php echo $value; ?> >
	        </td>
	    </tr>
	    <?php
	}

	function idlpharmcheck_save_taxonomy_custom_meta($term_id) {
	    $idlpharmcheck_checkbox = filter_input(INPUT_POST, 'idlpharmcheck_checkbox');
	    update_term_meta($term_id, 'idlpharmcheck_checkbox', $idlpharmcheck_checkbox);
	}

	function custom_checkout_field($checkout) {
		 $items =  WC()->cart->get_cart();
		 $term_id = 0;
		 $productCatMetaTitle ="";
		foreach($items as $cart_item ){
            $product_id = $cart_item['product_id'];
			$terms = get_the_terms( $product_id, 'product_cat' );
			foreach ($terms as $term) {
			   $term_id = $term->term_id;
			   if (get_term_meta($term_id, 'idlpharmcheck_checkbox', true)) {
			   		$productCatMetaTitle = get_term_meta($term_id, 'idlpharmcheck_checkbox', true);
			   }
			}
		}
		if ($productCatMetaTitle == "on") {
			$options = get_option( 'idl_quiz_option' );
			if ($options['heading_label_checkbox']) {
				echo '<div id="customise_checkout_field"><h4>' . __($options["heading_label"]) . '</h4>';
				echo '</div>';
			}
			if ($options['quiz1_checkbox']) {
				woocommerce_form_field('customised_field_name_age', array(
				    'type' => 'text',
				    'class' => array(
				      'my-field-class form-row-wide'
				    ) ,
				    'label' => __($options["quiz1"]) ,
				    //'placeholder' => __('age') ,
				    'required' => true,
				  ) , $checkout->get_value('customised_field_name_age'));
			}
			
			if ($options['quiz2_checkbox']) {
				woocommerce_form_field('customised_field_symtoms', array(
				    'type' => 'text',
				    'class' => array(
				      'my-field-class form-row-wide'
				    ) ,
				    'label' => __($options["quiz2"]) ,
				    //'placeholder' => __('symtoms') ,
				    'required' => true,
				  ) , $checkout->get_value('customised_field_symtoms'));
				
			}

			if ($options['quiz3_checkbox']) {
				woocommerce_form_field('customised_field_pregnant', array(
				    'type' => 'select',
				    'class' => array(
				      'my-field-class form-row-wide'
				    ) ,
				    'label' => __($options["quiz3"]) ,
				    'required' => true,
				    'options'     => array(
	                      'Yes' => __('Yes'),
	                      'No' => __('No')
	    			),
				  ) , $checkout->get_value('customised_field_pregnant'));
			}

			if ($options['quiz4_checkbox']) {
				woocommerce_form_field('customised_field_breast_feeding', array(
				    'type' => 'select',
				    'class' => array(
				      'my-field-class form-row-wide'
				    ) ,
				    'label' => __($options["quiz4"]) ,
				    'required' => true,
				    'options'     => array(
	                      'Yes' => __('Yes'),
	                      'No' => __('No')
	    			),
				  ) , $checkout->get_value('customised_field_breast_feeding'));
			}

			if ($options['quiz5_checkbox']) {
				woocommerce_form_field('customised_field_medical_conditions', array(
				    'type' => 'select',
				    'class' => array(
				      'my-field-class form-row-wide'
				    ) ,
				    'label' => __($options["quiz5"]) ,
				    'required' => true,
				    'options'     => array(
	                      'Yes' => __('Yes'),
	                      'No' => __('No')
	    			),
				  ) , $checkout->get_value('customised_field_medical_conditions'));
				
				woocommerce_form_field('customised_field_medical_conditions_describe', array(
				    'type' => 'textarea',
				    'class' => array(
				      'my-field-class form-row-wide'
				    ) ,
				    'label' => __('if yes: please specify below') ,
				  //  'placeholder' => __('medical conditions') ,
				  ) , $checkout->get_value('customised_field_medical_conditions_describe'));
			}
			if ($options['quiz6_checkbox']) {
				woocommerce_form_field('customised_field_medicines', array(
				    'type' => 'select',
				    'class' => array(
				      'my-field-class form-row-wide'
				    ) ,
				    'label' => __($options["quiz6"]) ,
				    'required' => true,
				    'options'     => array(
	                      'Yes' => __('Yes'),
	                      'No' => __('No')
	    			),
				  ) , $checkout->get_value('customised_field_medicines'));

				woocommerce_form_field('customised_field_medicines_describe', array(
				    'type' => 'textarea',
				    'class' => array(
				      'my-field-class form-row-wide'
				    ) ,
				    'label' => __('if yes: please specify below') ,
				   // 'placeholder' => __('medicines') ,
				  ) , $checkout->get_value('customised_field_medicines_describe'));
			}

			if ($options['terms_condition_checkbox']) {
					woocommerce_form_field('checkbox_for_terms_and_conditions', array(
					    'type' => 'checkbox',
					    'class' => array(
					      'my-field-class form-row-wide'
					    ) ,
					    'label' => __($options["terms_condition"]) ,
					    'required' => true,
					  ) , $checkout->get_value('checkbox_for_terms_and_conditions'));
			}
		}
	}

	function customised_checkout_field_process() {
		$options = get_option( 'idl_quiz_option' );
		$items =  WC()->cart->get_cart();
		 $term_id = 0;
		 $productCatMetaTitle ="";
		foreach($items as $cart_item ){
            $product_id = $cart_item['product_id'];
			$terms = get_the_terms( $product_id, 'product_cat' );
			foreach ($terms as $term) {
			   $term_id = $term->term_id;
			   if (get_term_meta($term_id, 'idlpharmcheck_checkbox', true)) {
			   		$productCatMetaTitle = get_term_meta($term_id, 'idlpharmcheck_checkbox', true);
			   }
			}
		}
		//$productCatMetaTitle = get_term_meta($term_id, 'idlpharmcheck_checkbox', true);
		if ($productCatMetaTitle == "on") {
			if ($options['quiz1_checkbox']) {
				if (!$_POST['customised_field_name_age']) wc_add_notice(__($options["quiz1"]) , 'error');
			}
			if ($options['quiz2_checkbox']) {
				if (!$_POST['customised_field_symtoms']) wc_add_notice(__($options["quiz2"]) , 'error');
			}
			if ($options['quiz3_checkbox']) {
				if (!$_POST['customised_field_pregnant']) wc_add_notice(__($options["quiz3"]) , 'error');
			}
			if ($options['quiz4_checkbox']) {
				if (!$_POST['customised_field_breast_feeding']) wc_add_notice(__($options["quiz4"]) , 'error');
			}
			if ($options['quiz5_checkbox']) {
				if (!$_POST['customised_field_medical_conditions']) wc_add_notice(__($options["quiz5"]) , 'error');
				if (!$_POST['customised_field_medical_conditions_describe']) wc_add_notice(__($options["quiz5"]."/if yes describe them") , 'error');
			}
			if ($options['quiz6_checkbox']) {
				if (!$_POST['customised_field_medicines']) wc_add_notice(__($options["quiz6"]) , 'error');
				if (!$_POST['customised_field_medicines_describe']) wc_add_notice(__($options["quiz6"]."/if yes describe them") , 'error');
			}
			if ($options['terms_condition_checkbox']) {
				if (!$_POST['checkbox_for_terms_and_conditions']) wc_add_notice(__($options["terms_condition"]) , 'error');
			}
		}
	}

	function custom_checkout_field_update_order_meta($order_id)	{
		$options = get_option( 'idl_quiz_option' );
		if (!empty($_POST['customised_field_name_age'])) {
			update_post_meta($order_id, $options["quiz1"],sanitize_text_field($_POST['customised_field_name_age']));
		}
		if (!empty($_POST['customised_field_symtoms'])) {
			update_post_meta($order_id, $options["quiz2"] ,sanitize_text_field($_POST['customised_field_symtoms']));
		}
		if (!empty($_POST['customised_field_pregnant'])) {
			update_post_meta($order_id, $options["quiz3"] ,sanitize_text_field($_POST['customised_field_pregnant']));
		}
		if (!empty($_POST['customised_field_breast_feeding'])) {
			update_post_meta($order_id, $options["quiz4"] ,sanitize_text_field($_POST['customised_field_breast_feeding']));
		}
		if (!empty($_POST['customised_field_medical_conditions'])) {
			update_post_meta($order_id, $options["quiz5"] ,sanitize_text_field($_POST['customised_field_medical_conditions']));
		}
		if (!empty($_POST['customised_field_medical_conditions_describe'])) {
			update_post_meta($order_id, $options["quiz5"].' described' ,sanitize_text_field($_POST['customised_field_medical_conditions_describe']));
		}
		if (!empty($_POST['customised_field_medicines'])) {
			update_post_meta($order_id, $options["quiz6"] ,sanitize_text_field($_POST['customised_field_medicines']));
		}
		if (!empty($_POST['customised_field_medicines_describe'])) {
			update_post_meta($order_id, $options["quiz6"].' described' ,sanitize_text_field($_POST['customised_field_medicines_describe']));
		}

		/*if (!empty($_POST['checkbox_for_terms_and_conditions'])) {
			update_post_meta($order_id, 'terms and conditions',sanitize_text_field($_POST['checkbox_for_terms_and_conditions']));
		}*/
	}

	public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Idl checkout quiz', 
            'manage_options', 
            'my-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'idl_quiz_option' );
        ?>
        <div class="wrap">
            <!-- <h1>Lulu Print Api</h1> -->
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'my-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'idl_quiz_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Checkout Quizes', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        ); 

        add_settings_field(
            'heading_label', 
            'Enter Heading For Quiz Section', 
            array( $this, 'heading_label_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );    

        add_settings_field(
            'quiz1', 
            'Enter 1st Question', 
            array( $this, 'quiz1_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );   

        add_settings_field(
            'quiz2', 
            'Enter 2nd Question', 
            array( $this, 'quiz2_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );

        add_settings_field(
            'quiz3', 
            'Enter 3rd Question', 
            array( $this, 'quiz3_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );

        add_settings_field(
            'quiz4', 
            'Enter 4th Question', 
            array( $this, 'quiz4_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );

        add_settings_field(
            'quiz5', 
            'Enter 5th Question', 
            array( $this, 'quiz5_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );

        add_settings_field(
            'quiz6', 
            'Enter 6th Question', 
            array( $this, 'quiz6_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );

        add_settings_field(
            'terms_condition', 
            'Enter terms & conditions', 
            array( $this, 'terms_condition_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );

    }

    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['heading_label'] ) )
            $new_input['heading_label'] = sanitize_text_field( $input['heading_label'] );
        	 $new_input['heading_label_checkbox'] = sanitize_text_field( $input['heading_label_checkbox'] );
        if( isset( $input['quiz1'] ) )
            $new_input['quiz1'] = sanitize_text_field( $input['quiz1'] );
        	 $new_input['quiz1_checkbox'] = sanitize_text_field( $input['quiz1_checkbox'] );
         if( isset( $input['quiz2'] ) )
            $new_input['quiz2'] = sanitize_text_field( $input['quiz2'] );
        	 $new_input['quiz2_checkbox'] = sanitize_text_field( $input['quiz2_checkbox'] );
        if( isset( $input['quiz3'] ) )
            $new_input['quiz3'] = sanitize_text_field( $input['quiz3'] );
        	 $new_input['quiz3_checkbox'] = sanitize_text_field( $input['quiz3_checkbox'] );
        if( isset( $input['quiz4'] ) )
            $new_input['quiz4'] = sanitize_text_field( $input['quiz4'] );
        	 $new_input['quiz4_checkbox'] = sanitize_text_field( $input['quiz4_checkbox'] );
        if( isset( $input['quiz5'] ) )
            $new_input['quiz5'] = sanitize_text_field( $input['quiz5'] );
        	 $new_input['quiz5_checkbox'] = sanitize_text_field( $input['quiz5_checkbox'] );
        if( isset( $input['quiz6'] ) )
            $new_input['quiz6'] = sanitize_text_field( $input['quiz6'] );
        	 $new_input['quiz6_checkbox'] = sanitize_text_field( $input['quiz6_checkbox'] );
        if( isset( $input['terms_condition'] ) )
            $new_input['terms_condition'] = sanitize_text_field( $input['terms_condition'] );
        	 $new_input['terms_condition_checkbox'] = sanitize_text_field( $input['terms_condition_checkbox'] );
        	 
        return $new_input;
    }

    public function print_section_info()
    {
        print 'Write and Edit Questions and Select checkbox to appear on Checkout page';
    }
	
	public function heading_label_callback()
    {
        printf(
            '<input type="textarea" class="width_of_input" id="heading_label" name="idl_quiz_option[heading_label]" value="%s" />',
            isset( $this->options['heading_label'] ) ? esc_attr( $this->options['heading_label']) : ''
        );
        printf(
            '<input type="checkbox" id="heading_label_checkbox" name="idl_quiz_option[heading_label_checkbox]" value="1"' . checked( 1, $this->options['heading_label_checkbox'], false ) . '/>' ,
            isset( $this->options['heading_label_checkbox'] ) ? esc_attr( $this->options['heading_label_checkbox']) : ''
        );
    }

    public function quiz1_callback()
    {
        printf(
            '<input type="textarea" class="width_of_input" id="quiz1" name="idl_quiz_option[quiz1]" value="%s" />',
            isset( $this->options['quiz1'] ) ? esc_attr( $this->options['quiz1']) : ''
        );
        printf(
            '<input type="checkbox" id="quiz1_checkbox" name="idl_quiz_option[quiz1_checkbox]" value="1"' . checked( 1, $this->options['quiz1_checkbox'], false ) . '/>' ,
            isset( $this->options['quiz1_checkbox'] ) ? esc_attr( $this->options['quiz1_checkbox']) : ''
        );
    }

    public function quiz2_callback()
    {
        printf(
            '<input type="textarea" class="width_of_input" id="quiz2" name="idl_quiz_option[quiz2]" value="%s" />',
            isset( $this->options['quiz2'] ) ? esc_attr( $this->options['quiz2']) : ''
        );
        printf(
            '<input type="checkbox" id="quiz2_checkbox" name="idl_quiz_option[quiz2_checkbox]" value="1"' . checked( 1, $this->options['quiz2_checkbox'], false ) . '/>' ,
            isset( $this->options['quiz2_checkbox'] ) ? esc_attr( $this->options['quiz2_checkbox']) : ''
        );
    }

    public function quiz3_callback()
    {
        printf(
            '<input type="textarea" class="width_of_input" id="quiz3" name="idl_quiz_option[quiz3]" value="%s" />',
            isset( $this->options['quiz3'] ) ? esc_attr( $this->options['quiz3']) : ''
        );
        printf(
            '<input type="checkbox" id="quiz3_checkbox" name="idl_quiz_option[quiz3_checkbox]" value="1"' . checked( 1, $this->options['quiz3_checkbox'], false ) . '/>' ,
            isset( $this->options['quiz3_checkbox'] ) ? esc_attr( $this->options['quiz3_checkbox']) : ''
        );
    }

    public function quiz4_callback()
    {
        printf(
            '<input type="textarea" class="width_of_input" id="quiz4" name="idl_quiz_option[quiz4]" value="%s" />',
            isset( $this->options['quiz4'] ) ? esc_attr( $this->options['quiz4']) : ''
        );
        printf(
            '<input type="checkbox" id="quiz4_checkbox" name="idl_quiz_option[quiz4_checkbox]" value="1"' . checked( 1, $this->options['quiz4_checkbox'], false ) . '/>' ,
            isset( $this->options['quiz4_checkbox'] ) ? esc_attr( $this->options['quiz4_checkbox']) : ''
        );
    }

    public function quiz5_callback()
    {
        printf(
            '<input type="textarea" class="width_of_input" id="quiz5" name="idl_quiz_option[quiz5]" value="%s" />',
            isset( $this->options['quiz5'] ) ? esc_attr( $this->options['quiz5']) : ''
        );
        printf(
            '<input type="checkbox" id="quiz5_checkbox" name="idl_quiz_option[quiz5_checkbox]" value="1"' . checked( 1, $this->options['quiz5_checkbox'], false ) . '/>' ,
            isset( $this->options['quiz5_checkbox'] ) ? esc_attr( $this->options['quiz5_checkbox']) : ''
        );
    }

    public function quiz6_callback()
    {
        printf(
            '<input type="textarea" class="width_of_input" id="quiz6" name="idl_quiz_option[quiz6]" value="%s" />',
            isset( $this->options['quiz6'] ) ? esc_attr( $this->options['quiz6']) : ''
        );
        printf(
            '<input type="checkbox" id="quiz6_checkbox" name="idl_quiz_option[quiz6_checkbox]" value="1"' . checked( 1, $this->options['quiz6_checkbox'], false ) . '/>' ,
            isset( $this->options['quiz6_checkbox'] ) ? esc_attr( $this->options['quiz6_checkbox']) : ''
        );
    }

    public function terms_condition_callback()
    {
        printf(
            '<input type="textarea" class="width_of_input" id="terms_condition" name="idl_quiz_option[terms_condition]" value="%s" />',
            isset( $this->options['terms_condition'] ) ? esc_attr( $this->options['terms_condition']) : ''
        );
        printf(
            '<input type="checkbox" id="terms_condition_checkbox" name="idl_quiz_option[terms_condition_checkbox]" value="1"' . checked( 1, $this->options['terms_condition_checkbox'], false ) . '/>' ,
            isset( $this->options['terms_condition_checkbox'] ) ? esc_attr( $this->options['terms_condition_checkbox']) : ''
        );
    }
    

}
