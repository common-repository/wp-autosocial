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

<div id="tw_help" style="display:none;border: 1px solid #DDD; background:#fff;">
   <ol>

      <li>
         <?php _e('Go to <a href="https://dev.twitter.com/apps">Twitter Developers</a>.', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('tw_step1')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>twitter1.png" alt="twitter1" style="width:50%;display:none;" id="tw_step1" />
      </li>

      <li>
         <?php _e('Create a new app with <strong>Website</strong> and <strong>Callback URL</strong> pointing to your website.', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('tw_step2')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>twitter2.png" alt="twitter2" style="width:50%;display:none;" id="tw_step2" />
      </li>

      <li>
         <?php _e('Change in <strong>Settings</strong> section the <strong>Application type</strong> to <strong>Read and write</strong>.', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('tw_step3')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>twitter3.png" alt="twitter3" style="width:50%;display:none;" id="tw_step3" />
      </li>

      <li>
         <?php _e('Create an <strong>access token</strong> in <strong>Details</strong> section.', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('tw_step4')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>twitter4.png" alt="twitter4" style="width:50%;display:none;" id="tw_step4" />
      </li>

      <li>
         <?php _e('Check your configuration and copy it in the fileds after this lines.', 'wp-autosocial'); ?>
         <a href="#" onclick="return autosocial_help('tw_step5')"><img src="<?php echo $img_url; ?>help.png" alt="<?php _e('Help', 'wp-autosocial'); ?>" /></a>
         <br />
         <img src="<?php echo $img_url; ?>twitter5.png" alt="twitter5" style="width:50%;display:none;" id="tw_step5" />
      </li>

   </ol>
</div>
