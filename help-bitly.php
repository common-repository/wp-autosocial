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

<div id="bl_help" style="display:none;border: 1px solid #DDD; background:#fff;">
   <ol>

      <li>
         <?php _e('Go to <a href="http://bitly.com/a/your_api_key/">Bit.ly API Key</a>.', 'wp-autosocial'); ?>
      </li>

      <li>
         <?php _e('Copy your username and API key in the fileds after this lines.', 'wp-autosocial'); ?>
      </li>

   </ol>
</div>
