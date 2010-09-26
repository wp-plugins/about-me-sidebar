<?php
/*
Plugin Name: About Me Sidebar
Plugin URI: http://www.creatingmyempire.com/wordpress-plugins
Description: Setup a nice about me widget in your sidebar.  Allows you to link to any 
			 page on your site.
Author: Dustin Stevens-Baier
Version: 1.0
Author URI: www.creatingmyempire.com
*/


class AboutMeSideBar extends WP_Widget {

   /** constructor */
    function AboutMeSideBar() {
        parent::WP_Widget(false, $name = 'About Me Sidebar', $widget_options = array('description' => 'Setup a nice About Me widget in the sidebar'));
    }
	
	    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
	extract($args);
	?>
				<div class="about-me-sidebar">
    <div class="about-me-sidebar-tl"></div>
    <div class="about-me-sidebar-tr"></div>
    <div class="about-me-sidebar-bl"></div>
    <div class="about-me-sidebar-br"></div>
    <div class="about-me-sidebar-tc"></div>
    <div class="about-me-sidebar-bc"></div>
    <div class="about-me-sidebar-cl"></div>
    <div class="about-me-sidebar-cr"></div>
    <div class="about-me-sidebar-cc"></div>
		<div class="about-me-sidebar-title-container">
				<div class="about-me-sidebar-title">
				<?php   $title = apply_filters('widget_title', esc_attr($instance['title'])); 
			       					echo $title ?>
				</div>
				</div>
	
    <div class="about-me-sidebar-body">
	<div class="about-me-sidebar-content">
	
    	<div class="about-me-sidebar-content-body">
		<p style="float:left; margin: 0 10px 0 0;">
		<?php $page_id = intval($instance['page_id']);
										$permalink = get_permalink($page_id);	
									$post = get_post($page_id); ?>
									
			<a id="about-me-link" title= "<?php echo $post->post_title ?>" href=" <?php echo $permalink; ?> "> 
			
			<?php   $imagepath = apply_filters('widget_title', esc_attr($instance['imagepath'])); ?>
									
				<img id="about-me-image-small" src="<?php echo $imagepath ?>"/></a>
			</p>
			<div class="about-me-sidebar-caption">
			<p style="font-size:13px; line-height:1.4; ">
			<?php   $aboutme = apply_filters('widget_title', esc_attr($instance['aboutme'])); 
			       					echo $aboutme ?>

			
									
			<a id="about-me-link" title= "<?php echo $post->post_title ?>" href=" <?php echo $permalink; ?> "> 
									
			<?php   $aboutmelink = apply_filters('widget_title', esc_attr($instance['aboutmelink'])); 
			       					echo $aboutmelink ?> </a>
</p>
			</div>
			

		</div>
	</div>
		
    </div>
</div>


<?php
    }
	
	    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {		
	    if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['imagepath'] = $new_instance['imagepath'];
		$instance['aboutme'] = $new_instance['aboutme'];
		$instance['aboutmelink'] = $new_instance['aboutmelink'];
		$instance['page_id'] = intval($new_instance['page_id']);		
        return $instance;
    }
	
	 /** @see WP_Widget::form */
    function form($instance) {
	global $wpdb;
		$instance = wp_parse_args((array) $instance, array('title' => __('About Me', 'about-me-sidebar'), 'imagepath'=>__('Image Path', 'about-me-sidebar'), 'aboutme' => __('Input About Me Text Here!', 'about-me-sidebar' ),  'post_id' => 0));
		$title = esc_attr($instance['title']);
		$imagepath = esc_attr($instance['imagepath']);
		$page_id = intval($instance['page_id']);
		$aboutme = esc_attr($instance['aboutme']);
		$aboutmelink = esc_attr($instance['aboutmelink']);
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'about-me-sidebar'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('imagepath'); ?>"><?php _e('Image Path:', 'about-me-sidebar'); ?> <input class="widefat" id="<?php echo $this->get_field_id('imagepath'); ?>" name="<?php echo $this->get_field_name('imagepath'); ?>" type="text" value="<?php echo $imagepath; ?>" /></label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('aboutme'); ?>">
		
			<?php _e('About Me Blurb:', 'about-me-sidebar'); ?> 
			<textarea style="height:120px;" class="widefat" id="<?php echo $this->get_field_id('aboutme'); ?>" name="<?php echo $this->get_field_name('aboutme'); ?>"><?php echo $aboutme; ?></textarea>
			</label>
			</p>
			<p>
			
		<label for="<?php echo $this->get_field_id('aboutmelink'); ?>">
		
			<?php _e('About Me Link Blurb:', 'about-me-sidebar'); ?> 
			<textarea style="height:60px;" class="widefat" id="<?php echo $this->get_field_id('aboutmelink'); ?>" name="<?php echo $this->get_field_name('aboutmelink'); ?>"><?php echo $aboutmelink; ?></textarea>
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('page_id'); ?>"><?php _e('About Me Page:', 'about-me-sidebar'); ?>
				<select name="<?php echo $this->get_field_name('page_id'); ?>" id="<?php echo $this->get_field_id('page_id'); ?>" class="widefat">
					<?php
					$querystr = "
								SELECT wposts.* 
								FROM $wpdb->posts wposts
								WHERE wposts.post_status = 'publish' 
								AND wposts.post_type = 'page' 
								ORDER BY wposts.post_date DESC
							 ";

					$myposts = $wpdb->get_results($querystr, OBJECT);

					
					if($myposts) 
					{
						foreach($myposts as $post) 
						{
						    setup_postdata($post);
							
							$title = $post->post_title;
							$postq_id = intval($post->ID);
							
							if($postq_id == $page_id) 
							{
								echo "<option value=\"$postq_id\" selected=\"selected\">$title</option>\n";
							} 
							else 
							{
								echo "<option value=\"$postq_id\">$title</option>\n";
							}
						}
					}
					
					?>
				</select>
			</label>
		</p>
		
		<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php
	}
	
}  // class AboutMeSidebar





// register AboutMeSidebar widget
add_action('widgets_init', create_function('', 'return register_widget("AboutMeSidebar");'));


	
?>