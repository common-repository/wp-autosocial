<?php
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

if (!isset($autosocial) || $autosocial !== true) die('no direct access');

/*
 *  Facebook connect message
 */
if (isset($_GET['reset'])) {

   autosocial_uninstall();

   // Info
   echo '<div class="updated settings-error"><p><strong>'.__('Settings have been reset.', 'wp-autosocial').'</strong></p></div>';
}

/*
 *  Facebook connect message
 */
if (isset($_GET['fb_connect'])) {
   if ($_GET['fb_connect'] == 1) {
      echo '<div class="updated settings-error"><p><strong>'.__('Facebook connected successfully.', 'wp-autosocial').'</strong></p></div>';
   } else {
      echo '<div class="error settings-error"><p><strong>'.__('Facebook not connected!', 'wp-autosocial').'</strong></p></div>';
   }
}

/*
 *  Testing connections
 */
// Twitter
if (isset($_GET['tw_test'])) {
   $link = autosocial_twitter(0, 'Testing #wpautosocial : share to #twitter and #facebook from #wordpress', 'http://capo.cat/wp-autosocial/');
   echo '<div class="updated settings-error"><p><strong>'.__('Twitter connection tested.', 'wp-autosocial').'</strong> <a href="'.$link.'">'.__('Link', 'wp-autosocial').'</a></p></div>';
}
// Facebook
if (isset($_GET['fb_test'])) {
   $link = autosocial_facebook(0, 'Testing WP-Autosocial Plugin', 'Share to Twitter and Facebook your published posts in your Wordpress blog!', 'http://capo.cat/wp-autosocial/');
   echo '<div class="updated settings-error"><p><strong>'.__('Facebook connection tested.', 'wp-autosocial').'</strong> <a href="'.$link.'">'.__('Link', 'wp-autosocial').'</a></p></div>';
}
// Bit.ly
if (isset($_GET['bl_test'])) {
   $link = autosocial_bitly(0, 'http://capo.cat/wp-autosocial/');
   echo '<div class="updated settings-error"><p><strong>'.__('Bit.ly connection tested.', 'wp-autosocial').'</strong> <a href="'.$link.'">'.__('Link', 'wp-autosocial').'</a></p></div>';
}



/*
 *  Save settings
 */
if (isset($_POST['submit'])) {

   // Settings
   if (isset($_POST['autosocial_box']) && $_POST['autosocial_box'] == 'on')
      update_option('autosocial_box', 1);
   else
      update_option('autosocial_box', 0);

   if (isset($_POST['autosocial_auto']) && $_POST['autosocial_auto'] == 'on')
      update_option('autosocial_auto', 1);
   else
      update_option('autosocial_auto', 0);


   update_option('autosocial_types', $_POST['autosocial_types']);


   // Twitter
   if (isset($_POST['tw_enabled']) && $_POST['tw_enabled'] == 'on')
      update_option('tw_enabled', 1);
   else
      update_option('tw_enabled', 0);

   update_option('tw_pattern', $_POST['tw_pattern']);
   update_option('tw_consumerkey', $_POST['tw_consumerkey']);
   update_option('tw_consumersecret', $_POST['tw_consumersecret']);
   update_option('tw_oauthtoken', $_POST['tw_oauthtoken']);
   update_option('tw_oauthsecret', $_POST['tw_oauthsecret']);

   // Facebook
   if (isset($_POST['fb_enabled']) && $_POST['fb_enabled'] == 'on')
      update_option('fb_enabled', 1);
   else
      update_option('fb_enabled', 0);

   update_option('fb_pattern', $_POST['fb_pattern']);
   update_option('fb_appid', $_POST['fb_appid']);
   update_option('fb_appsecret', $_POST['fb_appsecret']);
   update_option('fb_page_id', $_POST['fb_page_id']);

   // Bit.ly
   if (isset($_POST['bl_enabled']) && $_POST['bl_enabled'] == 'on')
      update_option('bl_enabled', 1);
   else
      update_option('bl_enabled', 0);

   update_option('bl_username', $_POST['bl_username']);
   update_option('bl_apikey', $_POST['bl_apikey']);

   // Info
   echo '<div class="updated settings-error"><p><strong>'.__('Settings saved.', 'wp-autosocial').'</strong></p></div>';

}

/*
 *  Get defaults settings
 */

// Settings
if (get_option('autosocial_types') == false) {
   update_option('autosocial_types', array('post' => 'on'));
}

// Twitter
if (get_option('tw_pattern') == '') {
   update_option('tw_pattern', '#title# - #link# #wpautosocial');
}

// Facebook
$fb_pattern = get_option('fb_pattern');
if (get_option('fb_pattern') == '') {
   update_option('fb_pattern', 'New post: #title#');
}
if (get_option('fb_appid') == '' || get_option('fb_appsecret') == '') {
   update_option('fb_token', '');
   update_option('fb_page_id', '');
}


// Facebook token expiration control
$token_url = 'https://graph.facebook.com/oauth/authorize?client_id='.get_option('fb_appid').'&redirect_uri='.urlencode($fb_url).'&scope='.$fb_permisos;
if (!isset($_GET['fb_connect']) && get_option('fb_token_expires') != '' && get_option('fb_token') != '') {
   if (get_option('fb_token_expires')+get_option('fb_token_time') <= time()) {
      echo '<div class="updated settings-error"><p><strong>Facebook:</strong> <a href="'.$token_url.'">'.__('Get token!', 'wp-autosocial').'</a></p></div>';
   } else {
      $fb_token_url = 'https://graph.facebook.com/oauth/access_token?client_id='.get_option('fb_appid').'&client_secret='.get_option('fb_appsecret').'&redirect_uri=&grant_type=fb_exchange_token&fb_exchange_token='.get_option('fb_token');
      $result = autosocial_graph($fb_token_url);
      parse_str($result, $fb);
      update_option('fb_token', $fb['access_token']);
      update_option('fb_token_time', time());
      update_option('fb_token_expires', $fb['expires']);
   }
}

// Get Facebook page with the user token
if (get_option('fb_token') != '') {
   $fb_pages = json_decode(autosocial_graph('https://graph.facebook.com/me/accounts?access_token='.get_option('fb_token')), true);
   if (isset($fb_pages['data']))
      $fb_pages = $fb_pages['data'];
   else {
      unset($fb_pages);
      $fb_token = '';
   }
}

?>
<script type="text/javascript">
   function autosocial_help(id) {
      var disp = document.getElementById(id);
      if (disp.style.display != 'block')
         disp.style.display = 'block';
      else
         disp.style.display = 'none';
      return false;
   }
</script>
<form action="<?php echo $plugin_url; ?>" method="post">

   <div class="postbox">
      <h3 class="hndle"><?php _e('Global Settings', 'wp-autosocial'); ?></h3>
      <div class="inside">

         <table class="form-table" style="clear:none">

            <tr valign="top">
               <th scope="row"><label for="autosocial_box"><?php _e('Display WP-AutoSocial meta', 'wp-autosocial'); ?></label></th>
               <td>
                  <input name="autosocial_box" type="checkbox" id="autosocial_box"<?php echo (get_option('autosocial_box') == 1 ? ' checked="checked"' : ''); ?> />
                  <em><?php _e('<a href="./post-new.php">Post page</a> metabox with information and sending options.', 'wp-autosocial'); ?></em>
               </td>
            </tr>

            <tr valign="top">
               <th scope="row"><label for="autosocial_auto"><?php _e('Autopublish posts', 'wp-autosocial'); ?></label></th>
               <td>
                  <input name="autosocial_auto" type="checkbox" id="autosocial_auto"<?php echo (get_option('autosocial_auto') == 1 ? ' checked="checked"' : ''); ?> />
                  <em><?php _e('Publish posts automatically to Facebook and Twitter (future posts allowed).', 'wp-autosocial'); ?></em>
               </td>
            </tr>

            <tr valign="top">
               <th scope="row"><label for="autosocial_types"><?php _e('Post types', 'wp-autosocial'); ?></label></th>
               <td>
                  <?php $post_types=get_post_types(array('public' => true), 'objects'); ?>
                  <?php foreach ($post_types as $post_type ) : ?>
                     <?php if($post_type->name != 'attachment') : ?>
                        <input type="checkbox" name="autosocial_types[<?php echo $post_type->name; ?>]" id="autosocial_types_<?php echo $post_type->name; ?>"<?php echo (autosocial_is_type($post_type->name) == 1 ? ' checked="checked"' : ''); ?> />
                        <label for="autosocial_types_<?php echo $post_type->name; ?>"><?php echo $post_type->labels->name; ?></label><br />
                     <?php endif; ?>
                  <?php endforeach; ?>
               </td>
            </tr>

         </table>
      </div>
   </div>

   <div class="postbox">
      <h3 class="hndle">
         Twitter
         <a href="#" onclick="return autosocial_help('tw_help')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
      </h3>
      <div class="inside">

<?php include 'help-twitter.php'; ?>

         <p>
            <label for="tw_enabled"><?php _e('Enable', 'wp-autosocial'); ?></label>
            <input name="tw_enabled" type="checkbox" id="tw_enabled"<?php echo (get_option('tw_enabled') == 1 ? ' checked="checked"' : ''); ?> />
         </p>

         <div id="tw_box"<?php echo (get_option('tw_enabled') == 1 ? '': ' style="display:none"'); ?>>

            <table class="form-table" style="clear:none">

               <tr valign="top">
                  <th scope="row"><label for="tw_pattern"><?php _e('Pattern', 'wp-autosocial'); ?></label></th>
                  <td>
                     <input name="tw_pattern" type="text" id="tw_pattern" class="regular-text" value="<?php echo get_option('tw_pattern'); ?>" />
                     <em><?php _e('Fields', 'wp-autosocial'); ?>: #title#, #link#</em>
                  </td>
               </tr>

               <tr valign="top">
                  <th scope="row"><label for="tw_consumerkey">Consumer Key</label></th>
                  <td><input name="tw_consumerkey" type="text" id="tw_consumerkey" class="regular-text" value="<?php echo get_option('tw_consumerkey'); ?>" /></td>
               </tr>

               <tr valign="top">
                  <th scope="row"><label for="tw_consumersecret">Consumer Secret</label></th>
                  <td><input name="tw_consumersecret" type="text" id="tw_consumersecret" class="regular-text" value="<?php echo get_option('tw_consumersecret'); ?>" /></td>
               </tr>

               <tr valign="top">
                  <th scope="row"><label for="tw_oauthtoken">OAuth Token</label></th>
                  <td><input name="tw_oauthtoken" type="text" id="tw_oauthtoken" class="regular-text" value="<?php echo get_option('tw_oauthtoken'); ?>" /></td>
               </tr>

               <tr valign="top">
                  <th scope="row"><label for="tw_oauthsecret">OAuth Secret</label></th>
                  <td><input name="tw_oauthsecret" type="text" id="tw_oauthsecret" class="regular-text" value="<?php echo get_option('tw_oauthsecret'); ?>" /></td>
               </tr>

            </table>

            <?php if(get_option('tw_consumerkey') != '' && get_option('tw_consumersecret') != '' && get_option('tw_oauthsecret') != '' && get_option('tw_oauthtoken') != ''): ?>
            <p><a href="<?php echo $plugin_url; ?>&tw_test" class="button-secondary"><?php _e('Test it!', 'wp-autosocial'); ?></a></p>
            <?php elseif (get_option('tw_enabled') == 1): ?>
            <div class="error settings-error"><p><strong>Twitter:</strong> <?php _e('Configuration missing!', 'wp-autosocial'); ?></p></div>
            <?php endif;?>

         </div><!-- tw_box -->
      </div>
   </div>


   <div class="postbox">
      <h3 class="hndle">
         Facebook
         <a href="#" onclick="return autosocial_help('fb_help')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
      </h3>
      <div class="inside">

<?php include 'help-facebook.php'; ?>

         <p>
            <label for="fb_enabled"><?php _e('Enable', 'wp-autosocial'); ?></label>
            <input name="fb_enabled" type="checkbox" id="fb_enabled"<?php echo (get_option('fb_enabled') == 1 ? ' checked="checked"' : ''); ?> />
         </p>

         <div id="fb_box"<?php echo (get_option('fb_enabled') == 1 ? '': ' style="display:none"'); ?>>

            <table class="form-table" style="clear:none">

               <tr valign="top">
                  <th scope="row"><label for="fb_pattern"><?php _e('Pattern', 'wp-autosocial'); ?></label></th>
                  <td>
                     <input name="fb_pattern" type="text" id="fb_pattern" class="regular-text" value="<?php echo get_option('fb_pattern'); ?>" />
                     <em><?php _e('Fields', 'wp-autosocial'); ?>: #title#</em>
                  </td>
               </tr>

               <tr valign="top">
                  <th scope="row"><label for="fb_appid">App ID</label></th>
                  <td><input name="fb_appid" type="text" id="fb_appid" class="regular-text" value="<?php echo get_option('fb_appid'); ?>" /></td>
               </tr>

               <tr valign="top">
                  <th scope="row"><label for="fb_appsecret">App Secret</label></th>
                  <td><input name="fb_appsecret" type="text" id="fb_appsecret" class="regular-text" value="<?php echo get_option('fb_appsecret'); ?>" /></td>
               </tr>

               <?php if (get_option('fb_appid') != '' && get_option('fb_appsecret') != ''): ?>
               <tr valign="top">
                  <th scope="row"><label for="fb_token">Token</label></th>
                  <td>
                     <?php if (get_option('fb_token') != '') : ?>
                     <input name="fb_token" type="text" id="fb_token" class="regular-text" value="<?php echo get_option('fb_token'); ?>" readonly="readonly" /><br />
                     <em>
                        <?php _e('Last update', 'wp-autosocial'); ?>:
                           <?php echo  date_i18n(get_option('date_format'), get_option('fb_token_time')); ?>
                           @
                           <?php echo  date_i18n(get_option('time_format'), get_option('fb_token_time')); ?>
                        <br />
                        <?php _e('Expires at', 'wp-autosocial'); ?>:
                           <?php echo  date_i18n(get_option('date_format'), get_option('fb_token_time')+get_option('fb_token_expires')); ?>
                           @
                           <?php echo  date_i18n(get_option('time_format'), get_option('fb_token_time')+get_option('fb_token_expires')); ?>
                     </em>
                     <br />
                     <?php endif; ?>
                     <a href="<?php echo $token_url; ?>">
                        <?php _e('Get token!', 'wp-autosocial'); ?>
                     </a>
                  </td>
               </tr>
               <?php endif; ?>

               <?php if (isset($fb_pages)): ?>
               <tr valign="top">
                  <th scope="row"><label for="fb_page_id"><?php _e('Destination', 'wp-autosocial'); ?></label></th>
                  <td>
                     <select id="fb_page_id" name="fb_page_id">
                        <option value=""><?php _e('My profile', 'wp-autosocial'); ?></option>
                        <?php $fb_cat = ''; foreach($fb_pages as $fb_page) : ?>
                           <?php if ($fb_cat != $fb_page['category']) : ?>
                              <?php if ($fb_cat != '') : ?></optgroup><?php endif; ?>
                              <optgroup label="<?php echo $fb_page['category']; ?>">
                           <?php $fb_cat = $fb_page['category']; endif; ?>
                           <option value="<?php echo $fb_page['id']; ?>"<?php echo (get_option('fb_page_id') == $fb_page['id'] ? ' selected="selected"' : ''); ?>>
                              <?php echo $fb_page['name']; ?>
                           </option>
                        <?php endforeach; ?>
                        <?php if ($fb_cat != '') : ?></optgroup><?php endif; ?>
                     </select>
                  </td>
               </tr>
               <?php endif; ?>

            </table>

            <?php if(get_option('fb_token') != ''): ?>
            <p><a href="<?php echo $plugin_url; ?>&fb_test" class="button-secondary"><?php _e('Test it!', 'wp-autosocial'); ?></a></p>
            <?php elseif (get_option('fb_enabled') == 1): ?>
            <div class="error settings-error"><p><strong>Facebook:</strong> <?php _e('Configuration missing!', 'wp-autosocial'); ?></p></div>
            <?php endif;?>

         </div><!-- fb_box -->
      </div>
   </div>

   <div class="postbox">
      <h3 class="hndle">
         Bit.ly
         <a href="#" onclick="return autosocial_help('bl_help')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
      </h3>
      <div class="inside">

<?php include 'help-bitly.php'; ?>

         <p>
            <label for="bl_enabled"><?php _e('Enable', 'wp-autosocial'); ?></label>
            <input name="bl_enabled" type="checkbox" id="bl_enabled"<?php echo (get_option('bl_enabled') == 1 ? ' checked="checked"' : ''); ?> />
         </p>

         <div id="bl_box"<?php echo (get_option('bl_enabled') == 1 ? '': ' style="display:none"'); ?>>

            <table class="form-table" style="clear:none">

               <tr valign="top">
                  <th scope="row"><label for="bl_username"><?php _e('Username', 'wp-autosocial'); ?></label></th>
                  <td><input name="bl_username" type="text" id="bl_username" class="regular-text" value="<?php echo get_option('bl_username'); ?>" /></td>
               </tr>

               <tr valign="top">
                  <th scope="row"><label for="bl_apikey">API Key</label></th>
                  <td><input name="bl_apikey" type="text" id="bl_apikey" class="regular-text" value="<?php echo get_option('bl_apikey'); ?>" /></td>
               </tr>

            </table>

            <?php if (get_option('bl_username') != '' && get_option('bl_apikey') != ''): ?>
            <p><a href="<?php echo $plugin_url; ?>&bl_test" class="button-secondary"><?php _e('Test it!', 'wp-autosocial'); ?></a></p>
            <?php elseif (get_option('bl_enabled') == 1): ?>
            <div class="error settings-error"><p><strong>Bit.ly:</strong> <?php _e('Configuration missing!', 'wp-autosocial'); ?></p></div>
            <?php endif;?>

         </div><!-- tw_box -->
      </div>
   </div>

   <p class="submit">
      <input type="submit" name="submit" value="<?php _e('Save Changes', 'wp-autosocial'); ?>" class="button-primary" />
      <a href="<?php echo $plugin_url; ?>&reset" class="button-secondary"><?php _e('Reset', 'wp-autosocial'); ?></a>
   </p>
</form>
