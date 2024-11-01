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

require('../../../wp-load.php' );

if (!current_user_can('manage_options')) die('meeeeeec!');


$fb_enabled = get_option('fb_enabled');
$fb_appid = get_option('fb_appid');
$fb_appsecret = get_option('fb_appsecret');
$fb_token = get_option('fb_token');

if (isset($_GET['code'])) {
   $fb_code = $_GET['code'];
   $fb_token_url = 'https://graph.facebook.com/oauth/access_token?client_id='.$fb_appid.'&redirect_uri='.urlencode($fb_url).'&client_secret='.$fb_appsecret.'&code='.$fb_code;
   $fb_token = autosocial_graph($fb_token_url);
   parse_str($fb_token, $fb);
   update_option('fb_token', $fb['access_token']);
   update_option('fb_token_time', time());
   update_option('fb_token_expires', $fb['expires']);
   header('Location: '.$plugin_url.'&fb_connect=1');
} else {
   header('Location: '.$plugin_url.'&fb_connect=0');
}

?>
