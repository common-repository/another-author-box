<?php
/*
Plugin Name: Another Author Box
Plugin URI: http://kisalt.com/wppg1
Description: Show author box in <strong>posts</strong> or/and <strong>pages</strong> with social media icons
Author: Oğulcan Orhan
Version: 1.0.1
Author URI: http://ogulcan.org

Copyright 2011  Ogulcan Orhan  (email : mail@ogulcan.org)

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

    function author_box_style() {
        $plugin_style = "/another-author-box/style.css";
        echo '<link rel="stylesheet" type="text/css" media="all" href="'.plugins_url().$plugin_style.'" />'."\n";
    }

    function author_box_display($content) {
        $options["page"] = get_option("show_page");
        $options["post"] = get_option("show_post");
        $options["author"] = get_option("show_author");
        $options["social"] = get_option("show_social_link");        
        $options["twitter"] = get_option("social_twitter");
        $options["facebook"] = get_option("social_facebook");
        $options["linkedin"] = get_option("social_linkedin");

        //plugin images
        $image_content = plugins_url()."/another-author-box"."/images/";

        //author post links
        $link = get_author_posts_url( get_the_author_meta( 'ID' ) );
        
        if ( (is_single() && $options["post"]) || (is_page() && $options["page"]) ) {
            
            $bio_box = '<div class="author-box">
                        <a href="'.$link.'"/>'.get_avatar( get_the_author_meta("user_email"), "80" ).'</a>
                        <h2>'.get_the_author_meta("display_name").'</h2>
                        <p class="description">'.get_the_author_meta("description").'</p>';

            //show social links?
            if( $options["social"] ) {

                $twitter = empty ($options["twitter"]); 
                $facebook = empty ($options["facebook"]);
                $linkedin = empty ($options["linkedin"]);

                if( !$twitter || !$facebook || !$linkedin ) {
                    
                    $bio_box .= '<span class="social-icons">';
                    
                    if(!$twitter) {
                        $bio_box .= '<a href="http://twitter.com/'.$options["twitter"].'" target="_blank"><img src="'.$image_content.'twitter-icon.png " /></a>';
                    }

                    if(!$facebook) {
                        $bio_box .= '<a href="http://facebook.com/'.$options["facebook"].'" target="_blank"><img src="'.$image_content.'facebook-icon.png " /></a>';
                    }

                    if(!$linkedin) {
                        $bio_box .= '<a href="http://linkedin.com/in/'.$options["linkedin"].'" target="_blank"><img src="'.$image_content.'linkedin-icon.png " /></a>';
                    }

                    $bio_box .= '</span>';
                }

            }

            //show author link?
            if( $options["author"] ) {
                $bio_box .=  '<span class="author-link"><a href="'.$link.'">Yazara ait tüm yazılar &rarr;</a></span>';
            }
            
            $bio_box .= "</div>";

            return $content . $bio_box;
        } else { return $content; }
        
    }
    
    function author_box_settings() {

        if ($_POST["action"] == "update") {

            $_POST["show_pages"] == "on" ? update_option("show_page", "checked") : update_option("show_page", "");
            $_POST["show_posts"] == "on" ? update_option("show_post", "checked") : update_option("show_post", "");
            $_POST["author_links"] == "on" ? update_option("show_author", "checked") : update_option("show_author", "");
            $_POST["show_social_link"] == "on" ? update_option("show_social_link", "checked") : update_option("show_social_link", "");

            is_null($_POST["twitter"]) ?  update_option("social_twitter", "") :  update_option("social_twitter", $_POST["twitter"]);
            is_null($_POST["facebook"]) ?  update_option("social_facebook", "") :  update_option("social_facebook", $_POST["facebook"]);
            is_null($_POST["linkedin"]) ?  update_option("social_linkedin", "") :  update_option("social_linkedin", $_POST["linkedin"]);

            $message = '<div id="message" class="updated fade"><p><strong>Options saved</strong></p></div>';
        }

        $options["page"] = get_option("show_page");
        $options["post"] = get_option("show_post");
        $options["author"] = get_option("show_author");
        $options["social"] = get_option("show_social_link");

        $options["twitter"] = get_option("social_twitter");
        $options["facebook"] = get_option("social_facebook");
        $options["linkedin"] = get_option("social_linkedin");

        echo '
        <div class="wrap">
            '.$message.'
            <div id="icon-options-general" class="icon32"><br /></div>
            <h2>Author Box Settings</h2>
        </div>
        <table class="form-table">
            <form method="post" action="">
                <input type="hidden" name="action" value="update" />
                <tbody>
                    <tr valign="top">
                        <th scope="row">
                            <h3>Display Options</h3>
                        </th>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            Show on pages:
                        </th>
                        <td>
                            <input name="show_pages" type="checkbox" id="show_pages" '.$options["page"].' />
                            <span class="description">Default: false </span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            Show on posts:
                        </th>
                        <td>
                            <input name="show_posts" type="checkbox" id="show_posts" '.$options["post"].' />
                            <span class="description">Default: false </span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            Show author links:
                        </th>
                        <td>
                            <input name="author_links" type="checkbox" id="author_links" '.$options["author"].' />
                            <span class="description">Show author post link</span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <h3>Social Link Options</h3>
                        </th>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            Show Links:
                        </th>
                        <td>
                            <input name="show_social_link" type="checkbox" id="show_social_link" '.$options["social"].' />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="twitter">Twitter Username: </label>
                        </th>
                        <td>
                            <input name="twitter" type="text" id="twitter" value="'.$options["twitter"].'" />
                            <span class="description">Need just username (ex: ogulcanor)</span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="facebook">Facebook Username: </label>
                        </th>
                        <td>
                            <input name="facebook" type="text" id="facebook" value="'.$options["facebook"].'" />
                            <span class="description">Need just username (ex: ogulcan)</span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="linkedin">LinkedIn Username: </label>
                        </th>
                        <td>
                            <input name="linkedin" type="text" id="linkedin" value="'.$options["linkedin"].'" />
                            <span class="description">Need just username (ex: ogulcanorhan)</span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"></th>
                        <td>
                            <span class="description">Note: If textbox is blank, that icon would not shown</span><br />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <input type="submit" class="button-primary" value="Save All" />
                        </th>
                    </tr>
                </tbody>
            </form>
        </table>';
    }

    function author_box_admin_menu(){
        add_options_page("Author Box", "Author Box", 9, basename(__FILE__), "author_box_settings");
    }
    
    add_action("the_content", "author_box_display");
    add_action("wp_head", "author_box_style");
    add_action("admin_menu", "author_box_admin_menu");
?>