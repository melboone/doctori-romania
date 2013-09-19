<?php
// $Id

require_once("common_methods.php");

if (get_drupal_version() == 5) {
  require_once("drupal5_methods.php");
}
else {
  require_once("drupal6_methods.php");
}

/* Common methods */

function get_drupal_version() {	
	$tok = strtok(VERSION, '.');
	//return first part of version number
	return (int)$tok[0];
}

function get_page_language($language) {
  if (get_drupal_version() >= 6) return $language->language;
  return $language;
}

function get_full_path_to_theme() {
  return base_path().path_to_theme();
}

/**
 * Allow themable wrapping of all breadcrumbs.
 */
function doc_breadcrumb($breadcrumb) {
  if (!empty($breadcrumb)) {
    return '<div class="breadcrumb art-postcontent">'. implode(' | ', $breadcrumb) .'</div>';
  }
}

function doc_service_links_node_format($links) {
  return '<div class="service-links"><div class="service-label">'. t('Bookmark/Search this post with: ') .'</div>'. art_links_woker($links) .'</div>';
}

/**
 * Theme a form button.
 *
 * @ingroup themeable
 */
function doc_button($element) {
  // Make sure not to overwrite classes.
  if (isset($element['#attributes']['class'])) {
    $element['#attributes']['class'] = 'form-'.$element['#button_type'].' '.$element['#attributes']['class'].' art-button';
  }
  else {
    $element['#attributes']['class'] = 'form-'.$element['#button_type'].' art-button';
  }
   	
  return '<span class="art-button-wrapper">'.
    '<span class="art-button-l"></span>'.
    '<span class="art-button-r"></span>'.
    '<input type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name']
         .'" ')  .'id="'. $element['#id'].'" value="'. check_plain($element['#value']) .'" '. drupal_attributes($element['#attributes']).'/>'.
	'</span>';
}

/**
 * Image assist module support.
 * Added Artisteer styles in IE
*/
function doc_img_assist_page($content, $attributes = NULL) {
  $title = drupal_get_title();
  $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
  $output .= '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">'."\n";
  $output .= "<head>\n";
  $output .= '<title>'. $title ."</title>\n";
  
  // Note on CSS files from Benjamin Shell:
  // Stylesheets are a problem with image assist. Image assist works great as a
  // TinyMCE plugin, so I want it to LOOK like a TinyMCE plugin. However, it's
  // not always a TinyMCE plugin, so then it should like a themed Drupal page.
  // Advanced users will be able to customize everything, even TinyMCE, so I'm
  // more concerned about everyone else. TinyMCE looks great out-of-the-box so I
  // want image assist to look great as well. My solution to this problem is as
  // follows:
  // If this image assist window was loaded from TinyMCE, then include the
  // TinyMCE popups_css file (configurable with the initialization string on the
  // page that loaded TinyMCE). Otherwise, load drupal.css and the theme's
  // styles. This still leaves out sites that allow users to use the TinyMCE
  // plugin AND the Add Image link (visibility of this link is now a setting).
  // However, on my site I turned off the text link since I use TinyMCE. I think
  // it would confuse users to have an Add Images link AND a button on the
  // TinyMCE toolbar.
  // 
  // Note that in both cases the img_assist.css file is loaded last. This
  // provides a way to make style changes to img_assist independently of how it
  // was loaded.
  $output .= drupal_get_html_head();
  $output .= drupal_get_js();
  $output .= "\n<script type=\"text/javascript\"><!-- \n";
  $output .= "  if (parent.tinyMCE && parent.tinyMCEPopup && parent.tinyMCEPopup.getParam('popups_css')) {\n";
  $output .= "    document.write('<link href=\"' + parent.tinyMCEPopup.getParam('popups_css') + '\" rel=\"stylesheet\" type=\"text/css\">');\n";
  $output .= "  } else {\n";
  foreach (drupal_add_css() as $media => $type) {
    $paths = array_merge($type['module'], $type['theme']);
    foreach (array_keys($paths) as $path) {
      // Don't import img_assist.css twice.
      if (!strstr($path, 'img_assist.css')) {
        $output .= "  document.write('<style type=\"text/css\" media=\"{$media}\">@import \"". base_path() . $path ."\";<\/style>');\n";
      }
    }
  }
  $output .= "  }\n";
  $output .= "--></script>\n";
  // Ensure that img_assist.js is imported last.
  $path = drupal_get_path('module', 'img_assist') .'/img_assist_popup.css';
  $output .= "<style type=\"text/css\" media=\"all\">@import \"". base_path() . $path ."\";</style>\n";
  
  $output .= '<link rel="stylesheet" href="'.get_full_path_to_theme().'/style.css" type="text/css" />'."\n";
  $output .= '<!--[if IE 6]><link rel="stylesheet" href="'.get_full_path_to_theme().'/style.ie6.css" type="text/css" /><![endif]-->'."\n";
  $output .= '<!--[if IE 7]><link rel="stylesheet" href="'.get_full_path_to_theme().'/style.ie7.css" type="text/css" /><![endif]-->'."\n";

  $output .= "</head>\n";
  $output .= '<body'. drupal_attributes($attributes) .">\n";
  
  $output .= theme_status_messages();
  
  $output .= "\n";
  $output .= $content;
  $output .= "\n";
  $output .= '</body>';
  $output .= '</html>';
  return $output;
}
function doc_print_terms($node, $vid = NULL, $ordered_list = TRUE) {
     $vocabularies = taxonomy_get_vocabularies();
     if ($ordered_list) $output .= '<ul>'; //checks to see if you want an ordered list
     if ($vid) { //checks to see if you've passed a number with vid, prints just that vid
        $output = '<div class="tags-'. $vid . '">';

        foreach($vocabularies as $vocabulary) {
         if ($vocabulary->vid == $vid) {
           $terms = taxonomy_node_get_terms_by_vocabulary($node, $vocabulary->vid);
           if ($terms) {
             $links = array();
             $output .= '<span class="only-vocabulary-'. $vocabulary->vid . '">';
             if ($ordered_list) $output .= '<li class="vocabulary-'. $vocabulary->vid . '">' . $vocabulary->name . ': ';
             foreach ($terms as $term) {
               $links[] = '<span class="term-' . $term->tid . '">' . l($term->name, taxonomy_term_path($term), array('rel' => 'tag', 'title' => strip_tags($term->description))) .'</span>';
             }
             $output .= implode(', ', $links);
             if ($ordered_list) $output .= '</li>';
             $output .= '</span>';
           }
         }
       }
     }
     else {
       $output = '<div class="localitate-specialitate">';
       foreach($vocabularies as $vocabulary) {
         if ($vocabularies) {
           $terms = taxonomy_node_get_terms_by_vocabulary($node, $vocabulary->vid);
           if ($terms) {
             $links = array();
             $output .= '<ul class="vocabular-'. $vocabulary->vid . '">';
             if ($ordered_list) $output .= '<div class="vocabular-'. $vocabulary->vid . '">' . $vocabulary->name . ': ';
             foreach ($terms as $term) {
               $links[] = '<span class="termen-' . $term->tid . '">' . l($term->name, taxonomy_term_path($term), array('rel' => 'tag', 'title' => strip_tags($term->description))) .'</span>';
             }
             $output .= implode(', ', $links);
             if ($ordered_list) $output .= '</li>';
             $output .= '</ul>';
           }
         }
       }
     }
     if ($ordered_list) $output .= '</ul>';
     $output .= '</div>';
     return $output;
}
