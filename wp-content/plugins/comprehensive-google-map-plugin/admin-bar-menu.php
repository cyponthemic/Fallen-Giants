<?php
/*
Copyright (C) 2011-08/2014  Alexander Zagniotov

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

if ( !function_exists('cgmp_admin_bar_menu') ):
    function cgmp_admin_bar_menu() {

        global $wp_admin_bar;
        if ( !is_super_admin() || !is_admin_bar_showing() )
            return;

        $wp_admin_bar->add_menu( array(
            'parent' => "new-content",
            'id' => "cgmp-admin-bar-menu-new-shortcode",
            'title' => "<span class='ab-icon'></span><span class='ab-label'>Shortcodes</span>",
            'href' => "admin.php?page=cgmp-shortcodebuilder",
            'meta' => FALSE
        ) );

        $root_id = "cgmp";
        $wp_admin_bar->add_menu( array(
            'id'   => $root_id,
            'meta' => array(),
            'title' => "<span class='ab-icon'></span><span class='ab-label'>Google Map</span>",
            'href' => FALSE ));

        $wp_admin_bar->add_menu( array(
            'parent' => $root_id,
            'id' => "cgmp-admin-bar-menu-documentation",
            'title' => "Documentation",
            'href' => "admin.php?page=cgmp-documentation",
            'meta' => FALSE
        ) );

        $wp_admin_bar->add_menu( array(
            'parent' => $root_id,
            'id' => "cgmp-admin-bar-menu-shortcode-builder",
            'title' => "Shortcode Builder",
            'href' => "admin.php?page=cgmp-shortcodebuilder",
            'meta' => FALSE
        ) );

        $wp_admin_bar->add_menu( array(
            'parent' => $root_id,
            'id' => "cgmp-admin-bar-menu-saved-shortcodes",
            'title' => "Saved Shortcodes",
            'href' => "admin.php?page=cgmp-saved-shortcodes",
            'meta' => FALSE
        ) );

        $wp_admin_bar->add_menu( array(
            'parent' => $root_id,
            'id' => "cgmp-admin-bar-menu-settings",
            'title' => "Settings",
            'href' => "admin.php?page=cgmp-settings",
            'meta' => FALSE
        ) );

        $wp_admin_bar->add_menu( array(
            'parent' => $root_id,
            'id' => "cgmp-admin-bar-menu-info",
            'title' => '<span style="background:#F99755;color:#000;padding:2px;text-shadow:none;">Notice of plugin discontinuation&nbsp;&nbsp;&nbsp;</span>',
            'href' => "admin.php?page=cgmp_info",
            'meta' => FALSE
        ) );

        $wp_admin_bar->add_menu( array(
            'parent' => $root_id,
            'id' => "cgmp-admin-bar-menu-export",
            'title' => '<span style="background:#F99755;color:#000;padding:2px;text-shadow:none;">Transfer maps to Maps Marker Pro</span>',
            'href' => "admin.php?page=cgmp_export",
            'meta' => FALSE
        ) );
		
    }
endif;

?>