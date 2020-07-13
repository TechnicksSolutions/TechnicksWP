<?php
add_action('acf/save_post', 'acf_save_post_processing', 20);

function acf_save_post_processing($post_id)
{
    $screen = get_current_screen();
    //error_log('Screen ID = '.$screen->id);
    if ((strpos($screen->id, 'theme-general-settings') == true) || (strpos($screen->id, 'master-page-settings') == true) || (strpos($screen->id, 'header-rows') == true) || (strpos($screen->id, 'footer-rows') == true)) {
        //$header = getHeaderSCSS();
        $header = getHeaderSCSS_ex();
        $footer = getFooterSCSS_ex();
        //$typography = getTypographySCSS();
        $defaultSCSS = getDefaultSCSS();
        $wooSCSS = getWooSCSS();
        $defaultJS = getDefaultJS();
        //file_put_contents(get_template_directory() . '/assets/styles/server/theme-settings/' . '_typography.scss', $typography);
        file_put_contents(get_template_directory() . '/assets/styles/server/theme-settings/' . '_header.scss', $header);
        file_put_contents(get_template_directory() . '/assets/styles/server/theme-settings/' . '_footer.scss', $footer);
        file_put_contents(get_template_directory() . '/assets/styles/server/theme-settings/' . '_default.scss', $defaultSCSS);
        file_put_contents(get_template_directory() . '/assets/styles/server/theme-settings/' . '_woo.scss', $wooSCSS);
        file_put_contents(get_template_directory() . '/assets/scripts/server/theme/' . 'theme.js', $defaultJS);
        update_css();
        compileJS();
    }
}

add_action('save_post', 'css_save_post', 10, 3);

function css_save_post($post_id, $post, $update)
{
    // Autosave, do nothing
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
// AJAX? Not used here
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }
// Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
// Return if it's a post revision
    if (false !== wp_is_post_revision($post_id)) {
        return;
    }

    $postSCSS = '';
    $scssFilename = '';
    $jsFilename = '';
    $scssImport = '';
    $z_postSCSS = '';
    $postJS = '';

    if ($post->post_type == 'post') {
        $z_postSCSS .= '.postid-' . $post_id . ' {';
        $scssFilename = '_postid-' . $post_id . '.scss';
        $jsFilename = '_postid-' . $post_id . '.js';
        $scssImport = '@import "postid-' . $post_id . '";';
    }
    if ($post->post_type == 'page') {
        $z_postSCSS .= '.page-id-' . $post_id . ' {';
        $scssFilename = '_page-id-' . $post_id . '.scss';
        $jsFilename = '_page-id-' . $post_id . '.js';
        $scssImport = '@import "page-id-' . $post_id . '";';
    }

    $blocks = parse_blocks($post->post_content);
    foreach ($blocks as $block) {
        if ($block['blockName'] == 'acf/acfgbcblockherosection') {
            $heroSection = $block['attrs']['data'];
            $sliderID = cssName($heroSection['id']);
            $sliderSCSS = $heroSection['cssscss'];
            if ($sliderSCSS != '') {
                if ($postSCSS == '') {
                    $postSCSS .= $z_postSCSS;
                }
                $postSCSS .= '#' . $sliderID . ' {';
                $postSCSS .= $sliderSCSS;
            }
            $num_slides = $heroSection['slides'];
            for ($x = 0; $x <= $num_slides; $x++) {
                $slideSCSS = $heroSection['slides_' . $x . '_cssscss'];
                if ($slideSCSS != '') {
                    if ($postSCSS == '') {
                        $postSCSS .= $z_postSCSS . '#' . $sliderID . ' {';
                    }
                    $postSCSS .= '.slide-' . $x . ' {';
                    $postSCSS .= $slideSCSS;
                    $postSCSS .= '}'; //.slide-number
                }
            }
            if ($sliderSCSS != '') {
                $postSCSS .= '}'; //#sliderID
            }
            //error_log(print_r($block['attrs']['data'], true));
        }

        if ($block['blockName'] == 'acf/acfgbcblockimagegridlinks') {
            $section = $block['attrs']['data'];
            $sectionID = cssName($section['id']);
            $sectionSCSS = $section['cssscss'];
            if ($sectionSCSS != '') {
                if ($postSCSS == '') {
                    $postSCSS .= $z_postSCSS;
                }
                $postSCSS .= '#' . $sectionID . ' {';
                $postSCSS .= $sectionSCSS;
            }

            if ($sectionSCSS != '') {
                $postSCSS .= '}'; //#sliderID
            }
        }
    }

    if (have_rows('page_content', $post_id)):
        while (have_rows('page_content', $post_id)): the_row();
            $sectionID = cssName(get_sub_field('id'));
            $sectionSCSS = get_sub_field('cssscss');
            $sectionJS = get_sub_field('jquery');
            if ($sectionSCSS != '') {
                if ($postSCSS == '') {
                    $postSCSS .= $z_postSCSS;
                }
                $postSCSS .= '#' . $sectionID . ' {';
                $postSCSS .= $sectionSCSS;
                $postSCSS .= '}';
            }
            if ($sectionJS != '') {
                $postJS .= '/* added in ' . $sectionID . ' */' . $sectionJS;
            }
        endwhile;
    endif;

    if ($postSCSS != '') {
        $postSCSS .= '}'; //#postID
    }

    if ($postSCSS != '') {
        file_put_contents(get_template_directory() . '/assets/styles/server/posts/' . $scssFilename, $postSCSS);
        $importFile = file_get_contents(get_template_directory() . '/assets/styles/server/posts/posts.scss');
        if (strpos($importFile, $scssImport) === false) {
            $importFile .= $scssImport;
            file_put_contents(get_template_directory() . '/assets/styles/server/posts/posts.scss', $importFile);
        }
        update_css();
    }

    if ($postJS != '') {
        file_put_contents(get_template_directory() . '/assets/scripts/server/posts/' . $jsFilename, $postJS);
        compileJS();
    }
}