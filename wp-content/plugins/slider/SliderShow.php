<?php
/**
 * Plugin Name: SliderShow
 * Description: Create a simple SliderBar
 * Version: 1.0.0
 */
class sliderShow{
 public function __construct(){
     	
     add_action('init',array($this,'create_slider'));
     add_action('wp_enqueue_scripts', array($this,'load_assets'));
     add_shortcode('slick_slider', array($this, 'load_slider'));
     add_shortcode('slider_form', array($this, 'upload_images'));

 }
 
 public function load_assets(){
    wp_enqueue_style(
         'slick',
         plugin_dir_url(__FILE__).'vendor/slick/slick/slick.css',
         array(),
         1,
         'all'
     );
     wp_enqueue_style(
        'slick-themes',
        plugin_dir_url(__FILE__).'vendor/slick/slick/slick-theme.css',
        array(),
        1,
        'all'
    );
    wp_enqueue_script( 
        'my-great-script',
        "https://code.jquery.com/jquery-3.6.0.min.js",
        array( 'jquery' ), 
        '3.6.0', 
        true 
    );
    wp_enqueue_script(
        'slick',
        plugin_dir_url(__FILE__).'vendor/slick/slick/slick.js',
        array('jquery'),
        1,
        true
    );
    wp_enqueue_script(
        'app',
        plugin_dir_url(__FILE__).'js/app.js',
        array('jquery'),
        1,
        true
    );
    
 }
 
public function create_slider(){
        $args = array(
       'public' => true,
       'supports' => array('title', 'thumbnail'),
       'menu_icon' => 'dashicons-images-alt2',
       'labels' => array(
           'name' => 'Slider',
           'singular_name' => 'Slider entry'
       )
    );
    register_post_type('simple_slider',$args);
}
function load_slider()
{
    // $args = array('post_type'=> 'simple_slider');
    ?>  
    <div class = "slick">
    <?php
    global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key='custom-img-data'");
    foreach($result as $print){
        ?>
            <image src="<?php echo $print -> src;?>"/>
        <?php
    }
        
        ?>
    </div>
    <?php
}

}
new sliderShow;

function register_metaboxes(){
    add_meta_box('img_metabox','Image Uploader','metabox_image');
}
add_action('add_meta_boxes','register_metaboxes');
function register_admin_script(){
    wp_enqueue_script( 
        'admin-jquery-script',
        "https://code.jquery.com/jquery-3.6.0.min.js",
        array( 'jquery' ), 
        '3.6.0', 
        true 
    );
    wp_enqueue_script(
        'wp_img_upload',
        plugin_dir_url(__FILE__).'img-upload.js',
        array('jquery','media'),
        1,
        true
    );
}
add_action ('admin_enqueue_scripts','register_admin_script');
function metabox_image( $post_id ){
    wp_nonce_field(basename(__FILE__), 'custom_image_nonce');
    ?>
    <div>
        <img id="img-tag"/>
        <input type="hidden" id="img-hidden-field" name="custom-img-data"/>
        <input type="button" class="button" id="img-upload-button" value="Add Banner"/>
    </div>
    <?php
}

function save_image($post_id){
    if (isset($_POST['custom-img-data'])) {
        $image_data = json_decode(stripcslashes($_POST['custom-img-data']));
        if (is_object($image_data[0])) {
            $image_data = array('src' => esc_url_raw($image_data[0]->url));
        }
        else{
            $image_data = [];
        }
        update_post_meta($post_id,'custom-img-data', $image_data);
    }
}
add_action('save_post','save_image');
