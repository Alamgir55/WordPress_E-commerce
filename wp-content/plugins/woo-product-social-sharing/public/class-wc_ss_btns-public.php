<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://defthemes.com
 * @since      1.0.0
 *
 * @package    Wc_ss_btns
 * @subpackage Wc_ss_btns/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_ss_btns
 * @subpackage Wc_ss_btns/public
 * @author     DefThemes <defthemes@gmail.com>
 */
class Wc_ss_btns_Public {

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


	private $options;

	private $product_title;
	private $product_url;
	private $product_img;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name 	= $plugin_name;
		$this->version 		= $version;
		$this->options 		= json_decode( get_option( 'wc_ss_btns_options' ), true);
		$this->showHider 	= false;
		$this->render 		= true;

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
		 * defined in Wc_ss_btns_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_ss_btns_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc_ss_btns-public.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name . '-si', plugin_dir_url( __FILE__ ) . 'css/icons/socicon.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name . '-fa', plugin_dir_url( __FILE__ ) . 'css/fa/css/font-awesome.min.css', array(), $this->version, 'all' );
		
		// Selected Theme 
		if ( !empty( $this->options['display']['values']['theme'] ) && $this->options['display']['values']['theme'] != 'default-theme' )
			wp_enqueue_style( $this->plugin_name . '-' . $this->options['display']['values']['theme'], plugin_dir_url( __FILE__ ) . 'css/themes/wc_ss_btns-' . $this->options['display']['values']['theme'] . '.min.css', array(), $this->version, 'all' );

		// Corners?
		if ( isset( $this->options['display']['values']['corners'] ) && $this->options['display']['values']['corners'] )
			wp_enqueue_style( $this->plugin_name . '-rc', plugin_dir_url( __FILE__ ) . 'css/themes/wc_ss_btns-rounded-corners.min.css', array(), $this->version, 'all' );
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
		 * defined in Wc_ss_btns_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_ss_btns_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc_ss_btns-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * This function is used to retrive the permalink of several Post Types like this we can add support for categories and search results pages
	 */

	public function get_permalink() {
		if ( is_archive() ) {
			if ( is_tax() ) {
				$taxObject = get_queried_object();
				if (in_array($taxObject->taxonomy, array('product_cat', 'product_tag'))) {
					return get_term_link( get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				}
			}
		}

		if ( is_search() ) {
			global $wp;
			return add_query_arg( $wp->query_vars, home_url( $wp->request ) );
		}

		return get_permalink();
	}

	/**
	 * Adding OpenGraph support to the HTML tag
	 */
	public function add_opengraph_doctype( $output ) {
		return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
	}

	/**
	 * Inserts Facebook meta tags in the header section
	 */
	public function insert_fb_in_head() {
	    global $wp_query;

	    $site_name = get_bloginfo('name');
	    if ( function_exists('is_product_category') ) {
	    	if ( is_product_category() ) {
		    	$cat = $wp_query->get_queried_object();

		    	$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );

		    	$image = wp_get_attachment_url( $thumbnail_id );

		        echo '<meta property="og:title" content="' . $cat->name . '"/>';
		        echo '<meta property="og:type" content="object"/>';
		        echo '<meta property="og:url" content="' . $this->get_permalink() . '"/>';
		        echo '<meta property="og:site_name" content="'.$site_name.'"/>';
		    	
		    	if ( $image ) {
		    		echo '<meta property="og:image" content="' . $image . '"/>';
		    		echo '<meta property="og:image:secure_url" content="' . $image . '">';
		    	}
	    	}

	    } else if ( is_search() ) {
	    	global $wp;

			$permalink = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
			echo '<meta property="og:title" content="' . get_the_title() . '"/>';
	        echo '<meta property="og:type" content="product"/>';
	        echo '<meta property="og:url" content="' . $permalink . '"/>';
	        echo '<meta property="og:site_name" content="'.$wp->query_vars['s'].'"/>';

	        $image = wp_get_attachment_url( get_post_thumbnail_id() );
	    	
	    	if ( $image ) {
	    		echo '<meta property="og:image" content="' . $image . '"/>';
	    		echo '<meta property="og:image:secure_url" content="' . $image . '">';
	    	}
	    } else if ( function_exists('is_product') ) {
	    	if ( is_product() ) {
	    		echo '<meta property="og:title" content="' . get_the_title() . '"/>';
		        echo '<meta property="og:type" content="product"/>';
		        echo '<meta property="og:url" content="' . $this->get_permalink() . '"/>';
		        echo '<meta property="og:site_name" content="'.$site_name.'"/>';

		        $image = wp_get_attachment_url( get_post_thumbnail_id() );
		    	
		    	if ( $image ) {
		    		echo '<meta property="og:image" content="' . $image . '"/>';
		    		echo '<meta property="og:image:secure_url" content="' . $image . '">';
		    	}
	    	}
	    } else {
	    	echo '<meta property="og:title" content="' . get_the_title() . '"/>';
	        echo '<meta property="og:type" content="website"/>';
	        echo '<meta property="og:url" content="' . $this->get_permalink() . '"/>';
	        echo '<meta property="og:site_name" content="'.$site_name.'"/>';

	        $image = wp_get_attachment_url( get_post_thumbnail_id() );
	    	
	    	if ( $image ) {
	    		echo '<meta property="og:image" content="' . $image . '"/>';
	    		echo '<meta property="og:image:secure_url" content="' . $image . '">';
	    	}
	    }
	}

	public function insert_twitter_card() {
		$image = wp_get_attachment_url( get_post_thumbnail_id() );
		
		
		echo '<meta name="twitter:card" content="summary_large_image" />';
		// echo '<meta name="twitter:site" content="@example" />';
		echo '<meta name="twitter:title" content="' . get_the_title() .'" />';
		// echo '<meta name="twitter:description" content="A description" />';
		
		if ( $image ) {
    		echo '<meta name="twitter:image" content="' . $image . '"/>';
    	}
    	echo '<meta name="twitter:url" content="' . $this->get_permalink() . '" />';
	}


	public function display_ss_btns()
	{
		$theme = ( $this->options['display']['values']['theme'] ) ? $this->options['display']['values']['theme'] : 'default-theme';

		$this->theme = $theme;

		$this->product_title 	= get_the_title();

		// If is a search results page alter the title to reflect this
		if ( is_search() ) {
			global $wp;
			$this->product_title 	= $wp->query_vars['s'];
		}

		// Wether is a taxonomy or a custom post type get the right URL to share
		$this->product_url		= $this->get_permalink();
		$this->product_img		= wp_get_attachment_url( get_post_thumbnail_id() );

		$current_action 		= current_filter();
		$this->class 					= $theme;

		$this->links = array();


		// Check if WC is activated
		$this->wc_active 		= false;

		if ( $this->check_wc() )
			$this->wc_active 	= true;

		// Building the links with the information to share
		$this->build_links();
		
		// Based on theme selected display the HTML
		if ( !is_front_page() || is_singular('post') || is_singular('page') || is_archive() )
		{
			if ( $current_action == 'wp_footer' && !is_singular('product') )
			{
				// Default hide icons
				$this->showHider 	= true;
				$this->hideIcon 	= 'left';
				$this->showIcon 	= 'right';
				
				$this->class .= ' wc_ss_btns_float';
				if ( $this->options['general_settings']['floating_mode']['positions']['enabled_positions'] == 'right' )
				{
					$this->hideIcon 	= 'right';
					$this->showIcon 	= 'left';
					$this->class .= ' wc_ss_btns_float_right';
				}
			}

			if ( is_singular('product') )
				remove_action( 'wp_footer', array( $this, 'display_ss_btns') );

			

			if ( $this->render )
				echo $this->ss_btns_display_btns();
			else
				return $this->ss_btns_display_btns();
		}
	}

	public function build_links() {
		// 1. Facebook
		if ( isset( $this->options['networks']['values']['facebook'] ) )
			$this->links[]['facebook']['url'] 	= 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($this->product_url);

		// 2. Twitter
		if ( isset( $this->options['networks']['values']['twitter'] ) )
			$this->links[]['twitter']['url']		= 'http://twitter.com/intent/tweet?text=' . rawurlencode( $this->product_title ) . '+' . $this->product_url;

		// 3. Google
		if ( isset( $this->options['networks']['values']['google'] ) )
			$this->links[]['google']['url']		= 'https://plus.google.com/share?url=' . $this->product_url;

		// 4. LinkedIn
		if ( isset( $this->options['networks']['values']['linkedin'] ) )
			$this->links[]['linkedin']['url']		= 'https://www.linkedin.com/shareArticle?mini=true&url=' . $this->product_url;

		// 5. Pinterest
		if ( isset( $this->options['networks']['values']['pinterest'] ) )
			$this->links[]['pinterest']['url']	= 'http://pinterest.com/pin/create/bookmarklet/?media=' . $this->product_img . '&url=' . $this->product_url . '&is_video=false&description=' . rawurlencode( $this->product_title );

		// 6. Email
		if ( isset( $this->options['networks']['values']['email'] ) )
			$this->links[]['email']['url']		= 'mailto:?subject=' . rawurlencode( $this->product_title ) . '&body=' . $this->product_url;

		// 7. Reddit
		if ( isset( $this->options['networks']['values']['reddit'] ) )
			$this->links[]['reddit']['url']		= 'http://www.reddit.com/submit?url=' . rawurlencode( $this->product_url ) . '&title=' . rawurlencode( $this->product_title );

		// 8. Delicious
		if ( isset( $this->options['networks']['values']['delicious'] ) )
			$this->links[]['delicious']['url']		= 'https://delicious.com/save?url=' . rawurlencode( $this->product_url ) . '&title=' . rawurlencode( $this->product_title );

		// 9. Buffer
		if ( isset( $this->options['networks']['values']['buffer'] ) )
			$this->links[]['buffer']['url']		= 'http://digg.com/submit?title=' . rawurlencode( $this->product_title ) . '&url=' . rawurlencode( $this->product_url );

		// 10. Digg
		if ( isset( $this->options['networks']['values']['digg'] ) )
			$this->links[]['digg']['url']		= 'https://buffer.com/add?text=' . rawurlencode( $this->product_title ) . '&url=' . rawurlencode( $this->product_url );

		// 11. Tumblr
		if ( isset( $this->options['networks']['values']['tumblr'] ) )
			$this->links[]['tumblr']['url']		= 'https://www.tumblr.com/widgets/share/tool?title=' . rawurlencode( $this->product_title ) . '&canonicalUrl=' . rawurlencode( $this->product_url );

		// 12. StumbleUpon
		if ( isset( $this->options['networks']['values']['stumbleupon'] ) )
			$this->links[]['stumbleupon']['url']		= 'http://www.stumbleupon.com/submit?title=' . rawurlencode( $this->product_title ) . '&url=' . rawurlencode( $this->product_url );

		// 13. Blogger
		if ( isset( $this->options['networks']['values']['blogger'] ) )
			$this->links[]['blogger']['url']		= 'https://www.blogger.com/blog-this.g?n=' . rawurlencode( $this->product_title ) . '&u=' . rawurlencode( $this->product_url );

		// 14. LiveJournal
		if ( isset( $this->options['networks']['values']['livejournal'] ) )
			$this->links[]['livejournal']['url']		= 'http://www.livejournal.com/update.bml?subject=' . rawurlencode( $this->product_title ) . '&event=' . rawurlencode( $this->product_url );

		//15.  MySpace
		if ( isset( $this->options['networks']['values']['myspace'] ) )
			$this->links[]['myspace']['url']		= 'https://myspace.com/post?u=' . rawurlencode( $this->product_url ) . '&t=' . rawurlencode( $this->product_title );

		// 16. Yahoo
		if ( isset( $this->options['networks']['values']['yahoo'] ) )
			$this->links[]['yahoo']['url']		= 'http://compose.mail.yahoo.com/?body=' . rawurlencode( $this->product_url );

		// 17. FriendFeed
		if ( isset( $this->options['networks']['values']['friendfeed'] ) )
			$this->links[]['friendfeed']['url']		= 'http://friendfeed.com/?url=' . rawurlencode( $this->product_url );

		// 18. NewsVine
		if ( isset( $this->options['networks']['values']['newvine'] ) )
			$this->links[]['newvine']['url']		= 'http://www.newsvine.com/_tools/seed&save?u=' . rawurlencode( $this->product_url );

		// 19. EverNote
		if ( isset( $this->options['networks']['values']['evernote'] ) )
			$this->links[]['evernote']['url']		= 'http://www.evernote.com/clip.action?url=' . rawurlencode( $this->product_url );

		// 20. GetPocket
		if ( isset( $this->options['networks']['values']['getpocket'] ) )
			$this->links[]['getpocket']['url']		= 'https://getpocket.com/save?url=' . rawurlencode( $this->product_url );

		// 21. FlipBoard
		if ( isset( $this->options['networks']['values']['flipboard'] ) )
			$this->links[]['flipboard']['url']		= 'https://share.flipboard.com/bookmarklet/popout?v=2&title=' . rawurlencode( $this->product_title ) . '&url=' . rawurlencode( $this->product_url );

		// 22. InstaPaper
		if ( isset( $this->options['networks']['values']['instapaper'] ) )
			$this->links[]['instapaper']['url']		= 'http://www.instapaper.com/edit?url=' . rawurlencode( $this->product_url ) . '&title=' . rawurlencode( $this->product_title );

		// 23. Line.me
		if ( isset( $this->options['networks']['values']['lineme'] ) )
			$this->links[]['lineme']['url']		= 'https://lineit.line.me/share/ui?url=' . rawurlencode( $this->product_url );

		// 24. Skype
		if ( isset( $this->options['networks']['values']['skype'] ) )
			$this->links[]['skype']['url']		= 'https://web.skype.com/share?url=' . rawurlencode( $this->product_url );

		// 25. Viber
		if ( isset( $this->options['networks']['values']['viber'] ) )
			$this->links[]['viber']['url']		= 'viber://forward?text=' . rawurlencode( $this->product_url );

		// 26. WhatsApp
		if ( isset( $this->options['networks']['values']['whatsapp'] ) )
			$this->links[]['whatsapp']['url']		= 'https://api.whatsapp.com/send?text=' . rawurlencode( $this->product_url );


		// 27. VK
		if ( isset( $this->options['networks']['values']['vk'] ) )
			$this->links[]['vk']['url']		= 'http://vk.com/share.php?url=' . rawurlencode( $this->product_url );

		// 28. OKru
		if ( isset( $this->options['networks']['values']['okru'] ) )
			$this->links[]['okru']['url']		= 'https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=' . rawurlencode( $this->product_url ) . '&title=' . rawurlencode( $this->product_title );

		// 29. Baidu
		if ( isset( $this->options['networks']['values']['baidu'] ) )
			$this->links[]['baidu']['url']		= 'http://cang.baidu.com/do/add?it=' . rawurlencode( $this->product_title ) . '&iu=' . rawurlencode( $this->product_url );

		// 30. Weibo
		if ( isset( $this->options['networks']['values']['weibo'] ) )
			$this->links[]['weibo']['url']		= 'https://buffer.com/add?text=' . rawurlencode( $this->product_title ) . '&url=' . rawurlencode( $this->product_url );

		// 31. Renren
		if ( isset( $this->options['networks']['values']['renren'] ) )
			$this->links[]['renren']['url']		= 'http://widget.renren.com/dialog/share?title=' . rawurlencode( $this->product_title ) . '&resourceUrl=' . rawurlencode( $this->product_url ) . '&srcUrl=' . rawurlencode( $this->product_url );

		// 32. Xing
		if ( isset( $this->options['networks']['values']['xing'] ) )
			$this->links[]['xing']['url']		= 'https://www.xing.com/app/user?op=share&url=' . rawurlencode( $this->product_url );

		// 33. QZone
		if ( isset( $this->options['networks']['values']['qzone'] ) )
			$this->links[]['qzone']['url']		= 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' . rawurlencode( $this->product_url );

		// 34. Douban
		if ( isset( $this->options['networks']['values']['douban'] ) )
			$this->links[]['douban']['url']		= 'http://www.douban.com/recommend/?title=' . rawurlencode( $this->product_title ) . '&url=' . rawurlencode( $this->product_url );

		// 35. Telegram.me
		if ( isset( $this->options['networks']['values']['telegramme'] ) )
			$this->links[]['telegramme']['url']		= 'https://telegram.me/share/url?text=' . rawurlencode( $this->product_title ) . '&url=' . rawurlencode( $this->product_url );
	}

	public function ss_btns_display_btns()
	{
		$break = ( $this->theme == 'modern-theme' ) ? 3 : 5;
		if ( $this->showHider )
			$break 	= 5;
		
		$count = 0;

		$btns = '<div class="wc_ss_btns ' . $this->class . '">' .
			'<ul>';

		// Prepend header only if floating
		if ( !is_singular('product') && !is_singular('post') ) {
			$btns .= '<li class="wc_ss_btns_header">Share</li>';
		}
		

		foreach ( $this->links as $network ) {
			if ( isset( $this->options['networks']['values'][key($network)] ) ):
				$count++;
				$btns .= '<li class="'. key($network) .'">' .
				'<a target="_blank" href="javascript:void(0);" data-href="' . esc_url( $network[key($network)]['url'] ) . '" title="' . __('Share ' . $this->product_title . ' on ' . ucwords(key($network)) ) . '">';
				do_action( 'ss_btns_before_' . key($network) . '_button' );

				if ( key($network) == 'email' ):
					$btns .= '<i class="fa fa-envelope"></i><span>Mail</span>';
				elseif ( key($network) == 'vk' ):
					$btns .= '<i class="socicon-vkontakte"></i><span>Share</span>';
				else:
					$btns .= '<i class="socicon-' . key($network) . '"></i><span>Share</span>';
				endif;

				do_action( 'ss_btns_after_' . key($network) . '_button' );

				$btns .= '</a>' .
				'</li>';

				if ( $count == $break ):
					$btns .= '<li class="more"><a href="javascript:void(0);" class="wc_ss_btns-open" title="' . __( 'More...' ) . '"><i class="fa fa-plus"></i></a><div class="wc_ss_btns_more_buttons">' .
					'<div class="wc_ss_btns_more_buttons">';
				
					// More heading
					$btns .= '<div class="wc_ss_btns_more_buttons-heading">';
					// Apply filter for More Heading (can be changed in Display Settings)
					$btns .= apply_filters( 'ss_btns_heading', $this->options['display']['values']['wc_ss_btns_heading'] );
					$btns .= '<button class="wc_ss_btns-close"><i class="fa fa-close"></i></button>';
					$btns .= '</div>';

					// More content
					$btns .= '<div class="wc_ss_btns_more_buttons-content"><ul>';
					foreach ( $this->links as $network ) {
						if ( isset( $this->options['networks']['values'][key($network)] ) ):
							$btns .= '<li class="' . key($network) . '">' .
								'<a target="_blank" href="javascript:void(0);" data-href="' . esc_url( $network[key($network)]['url'] ) . '" title="' . __('Share ' . $this->product_title . ' on ' . ucwords(key($network)) ) . '">';
									do_action( 'ss_btns_before_' . key($network) . '_button' );

									if ( key( $network ) == 'email' ):
										$btns .= '<i class="fa fa-envelope"></i>';
									elseif ( key($network) == 'vk' ):
										$btns .= '<i class="socicon-vkontakte"></i>';
									else:
										$btns .= '<i class="socicon-<?php echo key($network); ?>"></i>';
									endif;

									do_action( 'ss_btns_after_' . key($network) . '_button' );
								$btns .= '</a>';
							$btns .= '</li>';
						endif;
					}
					$btns .= '</ul>';
					// End of More content
					$btns .= '	</div>';
					// End of More container
					$btns .= '</div>';
					// End of more list item
					$btns .= '</li>';
					break;
				endif;
			endif;
		}
		if ( $this->showHider ):
			$btns .= '<li class="wc_ss_btns_hide"><a href="javascript:void(0)" class="wc_ss_btns_float_hide"><i class="fa fa-angle-' . $this->hideIcon . '"></i></a></li>';
			$btns .= '<li class="wc_ss_btns_show"><a href="javascript:void(0)" class="wc_ss_btns_float_show"><i class="fa fa-angle-' . $this->showIcon . '"></i></a></li>';
		endif;

		$btns .= '</ul><span class="wc_ss_btns_flex"></span>';

		if ( isset( $this->options['display']['values']['display_share_message'] ) && is_singular( 'product' ) ):
			$btns .= '<span>' . apply_filters( 'ss_btns_message', $this->options['display']['values']['share_message_text'] ) . '</span>';
		endif;

		$btns .= '</div>';

		return $btns;
	}

	public function before_after_content( $content ) {
		if ( is_singular( 'page' ) || is_singular( 'post' ) ) {
			$this->render = false;
			$new_content = $this->display_ss_btns();
			$new_content .= $content;
			$new_content .= $this->display_ss_btns();
			return $new_content;
		}
		else
			return $content;
	}

	private function check_wc()
	{
		if (
			in_array( 
				'woocommerce/woocommerce.php', 
				apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
			) 
		)
			return true;
		else
			return false;
	}

	private function debug( $what )
	{
		echo '<pre>';
		var_dump( $what );
		echo '</pre>';
	}
}
