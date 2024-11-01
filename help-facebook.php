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
?>

<div id="fb_help" style="display:none;border: 1px solid #DDD; background:#fff;">
   <ol>

      <li>
         <?php _e('Go to <a href="https://developers.facebook.com/apps">Facebook Developers</a>.', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('fb_step1')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>facebook1.png" alt="facebook1" style="width:50%;display:none;" id="fb_step1" />
      </li>

      <li>
         <?php _e('Create a new app.', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('fb_step2')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>facebook2.png" alt="facebook2" style="width:50%;display:none;" id="fb_step2" />
      </li>

      <li>
         <?php _e('Put your domain in <strong>App Domain</strong> field and <strong>Site URL</strong> (in <strong>Website</strong> section).', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('fb_step3')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>facebook3.png" alt="facebook3" style="width:50%;display:none;" id="fb_step3" />
      </li>

      <li>
         <?php _e('Copy the <strong>App ID</strong> and <strong>App Secret</strong> in the correct fields after this lines. Save configuration.', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('fb_step3b')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>facebook3.png" alt="facebook3b" style="width:50%;display:none;" id="fb_step3b" />
      </li>

      <li>
         <?php _e('Get the token and Facebook ask you for permissions. Allow it.', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('fb_step4')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>facebook4.png" alt="facebook4" style="width:50%;display:none;" id="fb_step4" />
      </li>

      <li>
         <?php _e('Now you can see your token in the readonly field and a select with your pages and your profile options.', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('fb_step5')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>facebook5.png" alt="facebook5" style="width:50%;display:none;" id="fb_step5" />
      </li>

   </ol>

</div>
