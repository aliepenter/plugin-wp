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
       'has_archive' => true,
       'supports' => array('title', 'thumbnail'),
       'exclude_from_search' => true,
       'publicly_queryable' => false,
       'capability' => 'manage_options',
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
    $args = array('post_type'=> 'simple_slider');
    ?>  
    <div class = "slick">
    <?php
    wp_reset_query();
    $query = new WP_Query($args);
    while($query->have_posts()) : $query->the_post();
        if(has_post_thumbnail()) {  ?>
            <?php the_post_thumbnail(); ?>
            <?php }
    endwhile;
        ?>
    </div>
    <?php
}
}
new sliderShow;