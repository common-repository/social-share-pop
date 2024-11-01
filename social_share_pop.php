<?php
/*
Plugin Name: Social Share Pop
Plugin URI: 
Description: Cool Social Counter for your Post.
Version: 1.0
Author: iLen
Author URI: 
*/
if ( !class_exists('social_share_pop') ) {

class social_share_pop{

	/* no remove */
	public $parameter 		= array();
	public $options 		= array();
	public $components		= array();

	
	function __construct(){


		if( is_admin() ){

			self::configuration_plugin();
			require_once( plugin_dir_path( __FILE__ )."assets/ilenframework/assets/lib/plugin.class.php" );


		}elseif( ! is_admin() ) {

			// set parameter 
			self::parameters();


			//global $options_my_plugin_share_pop;
			//$options_my_plugin_share_pop = get_option( $this->parameter['name_option']."_options" ) ;

			

			//if( $options_my_plugin_share_pop[ $this->parameter['name_option'].'_automatically_append' ] !='1' ){
 
			add_action('the_content',array( &$this,'show') );	
				
			//}

			

			// add scripts & styles
			add_action('wp_enqueue_scripts', array( &$this,'load_script_and_style_social_share_pop') );

		}




	}


	function parameters(){
		

		$this->parameter = array('id'			  =>'social_share_pop_id',
								 'id_menu'		  =>'social_share_pop_menu',
								 'name'			  =>'Social Share Pop',
								 'name_long'	  =>'Social Share Pop',
								 'name_option'	  =>'social_share_pop',
								 'name_plugin_url'=>'social_share_pop',
								 'descripcion'    =>'Gets the related post on your blog with any design characteristics.',
								 'version'        =>'1.0',
								 'url'            =>'',
								 //'logo'			  =>$this->_theme_images'logo.png',
								 'logo'			  =>'<i class="fa fa-bolt"></i>',
								 'logo_text'	  =>'My Plugin Test',
								 'slogan'		  =>'powered by <a href="">iLenTheme</a>',
								 'url_framework'  =>plugins_url('/assets/ilenframework',__FILE__),
								 'theme_imagen'	  =>plugins_url('/assets/images',__FILE__),
								 'type'		  	  =>'plugin',
								 'method'		  =>'free');
		
	}

	function myoptions_build(){
		
		$this->options = array('a'=>array(	'title'	 	 => __('Display Options',$this->parameter['name_option']), 		//title section
											'title_large'=> __('Display Options',$this->parameter['name_option']),//title large section
											'description'=> '',	//description section
											'icon'		 => 'fa fa-circle-o',

											'options'	 => array(  
																	 

																	array(	'title'	=>__('Where you want it to appear',$this->parameter['name_option']),
																	 		'help' 	=>'',
																	 		'type' 	=>'select',
																	 		'value'	=>1,
																	 		'items'	=>array(1=>'Top Content',2=>'Under Content'),
																	 		'id' 	=>$this->parameter['name_option'].'_display',
																	 		'name'	=>$this->parameter['name_option'].'_display',
																	 		'class'	=>'',
																	 		'row'	=>array('a','b')),


																	array(	'title'	=>__('Text Twitter',$this->parameter['name_option']),
																	 		'help' 	=>'',
																	 		'type' 	=>'text',
																	 		'value'	=>'Tweet',
																	 		'id' 	=>$this->parameter['name_option'].'_text_tweet',
																	 		'name'	=>$this->parameter['name_option'].'_text_tweet',
																	 		'class'	=>'',
																	 		'row'	=>array('a','b')),

																	array(	'title'	=>__('Text Facebook',$this->parameter['name_option']),
																	 		'help' 	=>'',
																	 		'type' 	=>'text',
																	 		'value'	=>'Like',
																	 		'id' 	=>$this->parameter['name_option'].'_text_facebook',
																	 		'name'	=>$this->parameter['name_option'].'_text_facebook',
																	 		'class'	=>'',
																	 		'row'	=>array('a','b')),

																	array(	'title'	=>__('Text Google+ share',$this->parameter['name_option']),
																	 		'help' 	=>'',
																	 		'type' 	=>'text',
																	 		'value'	=>'+1',
																	 		'id' 	=>$this->parameter['name_option'].'_text_google',
																	 		'name'	=>$this->parameter['name_option'].'_text_google',
																	 		'class'	=>'',
																	 		'row'	=>array('a','b')),
 

																	

															)
										),
							'last_update'=>time(),


							 );


		return $this->options;
		
	}


	function use_components(){
		
		null;

	}


	function configuration_plugin(){
		

		// set parameter 
		self::parameters();


		// my configuration 
		self::myoptions_build();


		// my component to use
		self::use_components();

		
	}


	function getGoogleCount( $url = ""){
		
 
		 $ch = curl_init();  
		 curl_setopt($ch, CURLOPT_URL, "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ");
		 curl_setopt($ch, CURLOPT_POST, 1);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		 
		   
		 $curl_results = curl_exec ($ch);
		 curl_close ($ch);
		 
		 $parsed_results = json_decode($curl_results, true);
		 
		 return  $parsed_results[0]['result']['metadata']['globalCounts']['count'];
	}

	// MAKE HTML of PLUGIN
	function show( $content="" ){


 
    	global $post,$options_my_plugin_share_pop;  

    	$url = get_permalink();
		$options_my_plugin_share_pop = get_option( $this->parameter['name_option']."_options" ) ;
		
		$_html = '<div class="social-share-pop-fixed">
				  <ul class="social-share-pop">
				    <li>
				      <a class="popup" href="http://twitter.com/share?text='.get_the_title()." ".$url.'"><div class="bubble count-tw"><span>0</span></div></a>
				    </li>
				    <li>
				      <a class="popup"  href="https://www.facebook.com/sharer/sharer.php?u='.$url.'"><div class="bubble count-fb"><span>0</span></div></a>
				    </li>
				    <li>
				      <a class="popup"  href="https://plus.google.com/share?url='.$url.'" ><div class="bubble count-gp"><span>'.self::getGoogleCount( $url ).'</span></div></a>
				    </li>
				  </ul>
				  </div>
				  ';
		$_html .="
				<script>
				 var permalink = '$url';
				 var getFacebookCount = function () {
					  jQuery.getJSON('http://graph.facebook.com/?ids='+permalink+'&callback=?', function(data){
					    var facebookShares = data[permalink].shares;
					    jQuery('.bubble.count-fb').text(facebookShares);
					  });
				  };
				  getFacebookCount();

				  var getTwitterCount = function () {
					  jQuery.getJSON('http://urls.api.twitter.com/1/urls/count.json?url='+permalink+'&callback=?', function(data){
					    var twitterShares = data.count;
					    jQuery('.bubble.count-tw').text(twitterShares);
					  });
					};
					getTwitterCount();

 
					 jQuery('.popup').click(function(event) {
					    var width  = 575,
					        height = 400,
					        left   = (jQuery(window).width()  - width)  / 2,
					        top    = (jQuery(window).height() - height) / 2,
					        url    = this.href,
					        opts   = 'status=1' +
					                 ',width='  + width  +
					                 ',height=' + height +
					                 ',top='    + top    +
					                 ',left='   + left;

					    window.open(url, 'twitte', opts);

					    return false;
					  }); 

				</script>";
 

	    $style="<style>
					.social-share-pop li:nth-of-type(1):after {  
						content: '{$options_my_plugin_share_pop[$this->parameter['name_option'].'_text_tweet']}';
					}
					.social-share-pop li:nth-of-type(2):after {  
						content: '{$options_my_plugin_share_pop[$this->parameter['name_option'].'_text_facebook']}';
					}
					.social-share-pop li:nth-of-type(3):after {  
						content: '{$options_my_plugin_share_pop[$this->parameter['name_option'].'_text_google']}';
					}
	    		</style>";

 		//$content .=$style;


 		if( $options_my_plugin_share_pop[$this->parameter['name_option'].'_display'] == 1 ){
			return $_html.$content.$style;
		}elseif( $options_my_plugin_share_pop[$this->parameter['name_option'].'_display'] == 2 ){
			return $content.$_html.$style;
		}

	}


	




	function load_script_and_style_social_share_pop(){
	

		// Register styles
		wp_register_style( 'social-share-pop', plugins_url('/assets/css/style.css',__FILE__) );

		// Enqueue styles
		wp_enqueue_style( 'social-share-pop' );

		wp_enqueue_script( 'social-share-pop-script', plugins_url('/assets/js/0.8.0.min.js',__FILE__), array( 'jquery' ), '0.8.0', true );

	}


} // end class

	global $IF_CONFIG;
	$IF_CONFIG = new social_share_pop;

} // end if


if( is_admin() ){
	require_once "assets/ilenframework/core.php";
}

?>