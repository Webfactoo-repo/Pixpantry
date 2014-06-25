<?php
/**
 * Define the Tabs appearing on the Theme Options page
 * Tabs contains sections
 * Options are assigned to both Tabs and Sections
 * See README.md for a full list of option types
 */

$general_settings_tab = array(
    "name" => "general_tab",
    "title" => __( "General", "gpp" ),
    "sections" => array(
        "general_section_1" => array(
            "name" => "general_section_1",
            "title" => __( "General", "gpp" ),
            "description" => ""
        )
    )
);

gpp_register_theme_option_tab( $general_settings_tab );

$colors_tab = array(
    "name" => "colors_tab",
    "title" => __( "Colors", "gpp" ),
    "sections" => array(
        "colors_section_1" => array(
            "name" => "colors_section_1",
            "title" => __( "Colors", "gpp" ),
            "description" => ""
        )
    )
);

gpp_register_theme_option_tab( $colors_tab );


 /**
 * The following example shows you how to register theme options and assign them to tabs and sections:
*/
$options = array(
    'logo' => array(
        "tab" => "general_tab",
        "name" => "logo",
        "title" => "Logo",
        "description" => __( "Use a transparent png or jpg image", "gpp" ),
        "section" => "general_section_1",
        "since" => "1.0",
        "id" => "general_section_1",
        "type" => "image",
        "default" => ""
    ),

    'favicon' => array(
        "tab" => "general_tab",
        "name" => "favicon",
        "title" => "Favicon",
        "description" => __( "Use a transparent png or ico image", "gpp" ),
        "section" => "general_section_1",
        "since" => "1.0",
        "id" => "general_section_1",
        "type" => "image",
        "default" => ""
    ),

    'font' => array(
        "tab" => "general_tab",
        "name" => "font",
        "title" => "Headline Font",
        "description" => __( '<a href="' . get_option('siteurl') . '/wp-admin/admin-ajax.php?action=fonts&font=header&height=600&width=640" class="thickbox">Preview and choose a font</a>', "gpp" ),
        "section" => "general_section_1",
        "since" => "1.0",
        "id" => "general_section_1",
        "type" => "select",
        "default" => "News Cycle:400,700",
        "valid_options" => gpp_font_array()
    ),

    'font_alt' => array(
        "tab" => "general_tab",
        "name" => "font_alt",
        "title" => "Body Font",
        "description" => __( '<a href="' . get_option('siteurl') . '/wp-admin/admin-ajax.php?action=fonts&font=body&height=600&width=640" class="thickbox">Preview and choose a font</a>', "gpp" ),
        "section" => "general_section_1",
        "since" => "1.0",
        "id" => "general_section_1",
        "type" => "select",
        "default" => "Cardo:400,400italic,700",
        "valid_options" => gpp_font_array()
    ),

	"slideshow_thumbs" => array(
	    "tab" => "general_tab",
	    "name" => "slideshow_thumbs",
	    "title" => "Enable Slideshow Thumbnails",
	    "description" => __( "Show slideshow thumbnails.", "gpp" ),
	    "section" => "general_section_1",
	    "since" => "1.0",
	    "id" => "general_section_1",
	    "type" => "select",
	    "default" => "yes",
		"valid_options" => array(
		        "yes" => array(
		            "name" => "yes",
		            "title" => __( "Yes", "gpp" )
		        ),
		        "no" => array(
		            "name" => "no",
		            "title" => __( "No", "gpp" )
		        )
	    )
	),

    'color' => array(
        "tab" => "colors_tab",
        "name" => "color",
        "title" => "Color",
        "description" => __( "Select a color palette", "gpp" ),
        "section" => "colors_section_1",
        "since" => "1.0",
        "id" => "colors_section_1",
        "type" => "select",
        "default" => "fresh",
        "valid_options" => array(
            "light" => array(
                "name" => "light",
                "title" => __( "Light", "gpp" )
            ),
            "dark" => array(
                "name" => "dark",
                "title" => __( "Dark", "gpp" )
            ),
            "spring" => array(
                "name" => "spring",
                "title" => __( "Spring", "gpp" )
            ),
            "fresh" => array(
                "name" => "fresh",
                "title" => __( "Fresh", "gpp" )
            )
        )
    ),

    "css" => array(
        "tab" => "colors_tab",
        "name" => "css",
        "title" => "Custom CSS",
        "description" => __( "Add some custom CSS to your theme.", "gpp" ),
        "section" => "colors_section_1",
        "since" => "1.0",
        "id" => "colors_section_1",
        "type" => "textarea",
        "sanitize" => "html",
        "default" => ""
    ),

);


gpp_register_theme_options( $options );

?>