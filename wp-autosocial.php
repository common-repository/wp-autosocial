<?php
/*
Plugin Name: WP-AutoSocial
Plugin URI: http://capo.cat/wp-autosocial/
Description: Share to Twitter and Facebook your published posts in your Wordpress blog!
Version: 0.4.3
Author: Pau Capó <pau@capo.cat>
Author URI: http://capo.cat/
Text Domain: wp-autosocial
License: 
*/
/*  Copyright 2011  Pau Capó Pons  (email : pau@capo.cat)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// generate global urls
$plugin_dir = basename(dirname(__FILE__));
$plugin_url = get_admin_url().'options-general.php?page='.plugin_basename(__FILE__);
$fb_permisos = 'publish_stream,manage_pages,offline_access';
$fb_url = plugins_url('fb-access.php', __FILE__);
$img_url = plugins_url('img/', __FILE__);

// debug mode, not sending to twitter and facebook
$autosocial_debug = false;

function autosocial_init() {
   $plugin_dir = basename(dirname(__FILE__));
   load_plugin_textdomain( 'wp-autosocial', false, $plugin_dir.'/lang');
}
add_action('init', 'autosocial_init');

// action: wp_insert_post
function autosocial_insert_post($post_ID) {
   // if post_ID is a revision, break
   if (wp_is_post_revision($post_ID)) return;

   // get post data
   $post = get_post($post_ID);

   // if current status is not publish, break
   if ($post->post_status != 'publish') return;

   // if current post type is not set to on, break
   if (autosocial_is_type($post->post_type) != 1) return;

   // by default try to send to all networks
   $twitter = ((get_option('autosocial_auto') == 1) && (get_post_meta($post->ID, '_autosocial_twitter_link', true) == ''));
   $facebook = ((get_option('autosocial_auto') == 1) && (get_post_meta($post->ID, '_autosocial_facebook_link', true) == ''));

   // detect if is saved from web
   if (isset($_POST['post_status'])) {

      // if current status is not publish, break
      if ($_POST['post_status'] != 'publish') return;

      // if original status is publish and not wants to resend, break
      if (isset($_POST['original_post_status']) && $_POST['original_post_status'] == 'publish') {

         // metabox twitter resending
         $twitter = (isset($_POST['tw_send_now']) && $_POST['tw_send_now'] == 'on');

         // metabox facebook sending
         $facebook = (isset($_POST['fb_send_now']) && $_POST['fb_send_now'] == 'on');

         // not twitter of facebook resend, break
         if (!$twitter && !$facebook) return;
      }

      // if this post is a future scheduled, break
      if ($_POST['publish'] == __('Schedule')) return;

   }

   // send to autosocial
   autosocial($post_ID, $twitter, $facebook);

}

function autosocial($post_ID, $twitter = true, $facebook = true) {
   if (!$twitter && !$facebook) return;
   // get title, description and link to send to facebook and twitter
   $post = get_post($post_ID);
   $title = __($post->post_title);
   if ($post->post_password == '')
      $description = __(strip_tags(do_shortcode($post->post_content)));
   else
      $description = '...';
   $link = autosocial_bitly($post_ID, get_permalink($post_ID));

   if ($twitter) autosocial_twitter($post_ID, $title, $link);
   if ($facebook) autosocial_facebook($post_ID, $title, $description, $link);
}

function autosocial_bitly($post_ID = 0, $link = '') {
   // thanks to: http://www.garymartinphotography.co.nz/

   if (get_option('bl_enabled') != 1 || $link == '') return $link;

   $login = get_option('bl_username');
   $api_key = get_option('bl_apikey');

   $bl_link = urlencode($link);
   $bitly = json_decode(autosocial_graph('http://api.bit.ly/shorten?version=2.0.1&login='.$login.'&apiKey='.$api_key.'&longUrl='.$bl_link.'&history=1'));

   if ($bitly->errorCode == 0) {
      $bitly = $bitly->results->$link;
      $link = $bitly->shortUrl;
      if ($post_ID > 0) {
         add_post_meta($post_ID, '_autosocial_bitly', $link, true) or update_post_meta($post_ID, '_autosocial_bitly', $link);
      }
   }

   return $link;
}

function autosocial_twitter($post_ID = 0, $title = '', $link = '') {
   global $autosocial_debug;

   // post to twitter
   $tw_enabled = get_option('tw_enabled');
   if ($tw_enabled != 1) return false;

   $tw_pattern = __(get_option('tw_pattern'));

   // text from pattern and cut it to 130 chars
   $text = str_replace('#link#', $link, $tw_pattern);
   $title_short = trim(substr($title, 0, 130 - (strlen($text)-7)));
   $title_short .= ($title != $title_short ? '...' : '');
   $text = str_replace('#title#', $title_short, $text);

   $tw_consumerkey = get_option('tw_consumerkey');
   $tw_consumersecret = get_option('tw_consumersecret');
   $tw_oauthtoken = get_option('tw_oauthtoken');
   $tw_oauthsecret = get_option('tw_oauthsecret');

   if (!$autosocial_debug) {
      if (!class_exists('TwitterOAuth')) require_once 'twitteroauth.php';

      $connection = new TwitterOAuth($tw_consumerkey, $tw_consumersecret, $tw_oauthtoken, $tw_oauthsecret);
      $content = $connection->get('account/verify_credentials');

      $result = $connection->post('statuses/update', array('status' => $text));
      $tw_link = 'https://twitter.com/'.$result->user->screen_name.'/status/'.$result->id;
   }

   if ($post_ID > 0) {
      add_post_meta($post_ID, '_autosocial_twitter', time(), true) or update_post_meta($post_ID, '_autosocial_twitter', time());
      add_post_meta($post_ID, '_autosocial_twitter_link', $tw_link, true) or update_post_meta($post_ID, '_autosocial_twitter_link', $tw_link);
   }

   return $tw_link;
}

function autosocial_facebook($post_ID = 0, $title = '', $description = '', $link = '') {
   global $autosocial_debug;

   // post to facebook
   $fb_enabled = get_option('fb_enabled');
   if ($fb_enabled != 1) return false;

   $fb_pattern = __(get_option('fb_pattern'));
   // set text from pattern
   $text = str_replace('#title#', $title, $fb_pattern);

   $fb_appid = get_option('fb_appid');
   $fb_appsecret = get_option('fb_appsecret');
   $fb_token = get_option('fb_token');
   $fb_page_id = get_option('fb_page_id');

   if ($fb_page_id != '') {
      $fb_pages = json_decode(autosocial_graph('https://graph.facebook.com/me/accounts?access_token='.$fb_token), true);
      $fb_pages = $fb_pages['data'];
      foreach ($fb_pages as $fb_page) {
         if ($fb_page['id'] == $fb_page_id) {
            $fb_token = $fb_page['access_token'];
            break;
         }
      }
   }

   if (!$autosocial_debug) {
      if (!class_exists('FacebookApiException')) require_once 'facebook.php';
      $facebook = new Facebook(array('appId' => $fb_appid, 'secret' => $fb_appsecret, 'cookie' => true));
      $attachment = array(
         'access_token' => $fb_token,
         'message' => $text,
         'name' => $title,
         'caption' => $title,
         'link' => $link,
         'description' => substr($description, 0, 1000)
      );

      $result = $facebook->api('/me/feed/', 'post', $attachment);

      $result = explode('_', $result['id']);
      $fb_link = 'http://www.facebook.com/permalink.php?story_fbid='.$result[1].'&id='.$result[0];

   }

   if ($post_ID > 0) {
      add_post_meta($post_ID, '_autosocial_facebook', time(), true) or update_post_meta($post_ID, '_autosocial_facebook', time());
      add_post_meta($post_ID, '_autosocial_facebook_link', $fb_link, true) or update_post_meta($post_ID, '_autosocial_facebook_link', $fb_link);
   }

   return $fb_link;

}

function autosocial_adminpage() {
   if (!current_user_can('manage_options')) return;
   /*
    *  Settings page
    */
   global $fb_url, $fb_permisos, $plugin_url, $img_url;
?>
<div class="wrap">
   <div id="icon-options-general" class="icon32"><br /></div>
   <h2>WP-AutoSocial</h2>
   <div class="metabox-holder has-right-sidebar">
      <div class="inner-sidebar">
         <?php $autosocial = true; include 'support.php'; ?>
      </div>
      <div class="has-sidebar sm-padded">
         <div id="post-body-content" class="has-sidebar-content">
            <div class="meta-box-sortabless">
               <?php $autosocial = true; include 'settings.php'; ?>
            </div>
         </div>
      </div>
      <br style="clear:both" />
   </div>
</div>
<?php
}

function autosocial_graph($url) {
   // function to get facebook graph api results
   if (function_exists('curl_version')) {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_URL, $url);
      $output = curl_exec($curl);
      curl_close($curl);
   } else
      $output = @file_get_contents($url);
   return $output;
}

function autosocial_adminmenu() {
   // add to options admin menu
   if (current_user_can('manage_options'))
      add_options_page('AutoSocial', 'AutoSocial', 1, __FILE__, 'autosocial_adminpage');
}
function autosocial_settings_link($links, $file) {
   if ($file == plugin_basename(__FILE__)) {
      $settings_link = '<a href="options-general.php?page='.plugin_basename(__FILE__).'" title="'.__('Settings').'">'.__('Settings').'</a>';
      array_unshift($links, $settings_link);
   }
   return $links;
}

function autosocial_add_box() {
   if (get_option('autosocial_box') == 1) {
      $pt = get_option('autosocial_types');
      foreach ($pt as $type => $null) {
         add_meta_box('wp-autosocial', 'WP-AutoSocial ', 'autosocial_box', $type, 'side');
      }
   }
}

function autosocial_box($post) {
   // Use nonce for verification
   wp_nonce_field(plugin_basename(__FILE__), 'autosocial_nonce');
   /*if ($post->post_status != 'publish') {
      _e('Publish first.', 'wp-autosocial');
      return;
   }*/
?>

   <?php if (get_option('tw_enabled') == 1) : ?>
   <h4><strong>Twitter</strong></h4>

      <?php if (get_post_meta($post->ID, '_autosocial_twitter', true) != '') : ?>
      <p>
         <?php _e('Published on:', 'wp-autosocial'); ?>
         <a href="<?php echo get_post_meta($post->ID, '_autosocial_twitter_link', true); ?>">
         <?php echo date(__('j F, Y @ G:i'), get_post_meta($post->ID, '_autosocial_twitter', true)); ?>
         </a>
      </p>
      <?php endif; ?>

      <p>
         <?php if ($post->post_status == 'publish') : ?>
            <label for="tw_send_now"><?php _e('Send now', 'wp-autosocial'); ?>:</label>
            <input type="checkbox" name="tw_send_now" id="tw_send_now" />
         <?php else: ?>
            <?php _e('Publish first.', 'wp-autosocial'); ?>
         <?php endif; ?>
      </p>

   <?php endif; ?>

   <?php if (get_option('fb_enabled') == 1) : ?>
   <h4><strong>Facebook</strong></h4>

      <?php if (get_post_meta($post->ID, '_autosocial_facebook', true) != '') : ?>
      <p>
         <?php _e('Published on:', 'wp-autosocial'); ?>
         <a href="<?php echo get_post_meta($post->ID, '_autosocial_facebook_link', true); ?>">
         <?php echo date(__('j F, Y @ G:i'), get_post_meta($post->ID, '_autosocial_facebook', true)); ?>
         </a>
      </p>
      <?php endif; ?>

      <p>
         <?php if ($post->post_status == 'publish') : ?>
            <label for="fb_send_now"><?php _e('Send now', 'wp-autosocial'); ?>:</label>
            <input type="checkbox" name="fb_send_now" id="fb_send_now" />
         <?php else: ?>
            <?php _e('Publish first.', 'wp-autosocial'); ?>
         <?php endif; ?>
      </p>

   <?php endif; ?>

   <?php if (get_option('bl_enabled') == 1 && get_post_meta($post->ID, '_autosocial_bitly', true) != '') : ?>
   <p>
      <a href="<?php echo get_post_meta($post->ID, '_autosocial_bitly', true); ?>+">
         Bit.ly statistics
      </a>
   </p>
   <?php endif; ?>

<?php
}

function autosocial_is_type($type) {
   $pt = get_option('autosocial_types');
   return isset($pt[$type]);
}

function autosocial_uninstall() {
   // Settings
   delete_option('autosocial_box');
   delete_option('autosocial_auto');
   delete_option('autosocial_types');

   // Twitter
   delete_option('tw_enabled');
   delete_option('tw_pattern');
   delete_option('tw_consumerkey');
   delete_option('tw_consumersecret');
   delete_option('tw_oauthtoken');
   delete_option('tw_oauthsecret');

   // Facebook
   delete_option('fb_enabled');
   delete_option('fb_pattern');
   delete_option('fb_appid');
   delete_option('fb_appsecret');
   delete_option('fb_page_id');

   // Bit.ly
   delete_option('bl_enabled');
   delete_option('bl_username');
   delete_option('bl_apikey');
}

//add_action('publish_post', 'autosocial_publish');
//add_action('save_post', 'autosocial_save_post');
add_action('wp_insert_post', 'autosocial_insert_post');

if (get_option('autosocial_box') == 1) {
   add_action('add_meta_boxes', 'autosocial_add_box');
}

add_action('admin_menu', 'autosocial_adminmenu');
add_filter('plugin_action_links', 'autosocial_settings_link', 10, 2);

register_uninstall_hook(__FILE__, 'autosocial_uninstall');


