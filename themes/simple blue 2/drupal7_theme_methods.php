<?php

/* Drupal 7 methods definitons */

function SimpleBlue_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible art-postheader">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb art-postcontent">' . implode(' » ', $breadcrumb) . '</div>';
    return $output;
  }
}

/**
 * Returns HTML for a button form element.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #attributes, #button_type, #name, #value.
 *
 * @ingroup themeable
 */
function SimpleBlue_button($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'submit';
  element_set_attributes($element, array('id', 'name', 'value'));

  $element['#attributes']['class'][] = 'form-' . $element['#button_type'] . ' art-button';
  if (!empty($element['#attributes']['disabled'])) {
    $element['#attributes']['class'][] = 'form-button-disabled';
  }

  return '<span class="art-button-wrapper">'.
    '<span class="l"></span>'.
    '<span class="r"></span>'.
	'<input' . drupal_attributes($element['#attributes']) . ' />'.
	'</span>';
}

/**
 * Override or insert variables into the page template.
 */
function SimpleBlue_preprocess_page(&$vars) {
  $vars['tabs'] = menu_primary_local_tasks();
  $vars['tabs2'] = menu_secondary_local_tasks();
}

/**
 * Returns HTML for a single local task link.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: A render element containing:
 *     - #link: A menu link array with 'title', 'href', and 'localized_options'
 *       keys.
 *     - #active: A boolean indicating whether the local task is active.
 *
 * @ingroup themeable
 */
function SimpleBlue_menu_local_task($variables) {
  $link = $variables['element']['#link'];
  $link_text = $link['title'];

  if (!empty($variables['element']['#active'])) {
    // Add text to indicate active tab for non-visual users.
    $active = '<span class="element-invisible">' . t('(active tab)') . '</span>';

    // If the link does not contain HTML already, check_plain() it now.
    // After we set 'html'=TRUE the link will not be sanitized by l().
    if (empty($link['localized_options']['html'])) {
      $link['title'] = check_plain($link['title']);
    }
    $link['localized_options']['html'] = TRUE;
    $link_text = t('!local-task-title!active', array('!local-task-title' => $link['title'], '!active' => $active));
  }

  //added art-class
  $link['localized_options']['attributes']['class'] = array('art-button');

  return '<li>' .
	  '<span class="art-button-wrapper">'.
	  '<span class="l"></span>'.
      '<span class="r"></span>'.
	  l($link_text, $link['href'], $link['localized_options']) .
	  "</span></li>\n";
}

/**
 * Returns HTML for a feed icon.
 *
 * @param $variables
 *   An associative array containing:
 *   - url: The url of the feed.
 *   - title: A descriptive title of the feed.
 */
function SimpleBlue_feed_icon($variables) {
  $text = t('Subscribe to @feed-title', array('@feed-title' => $variables['title']));
  return l(NULL, $variables['url'], array('html' => TRUE, 'attributes' => array('class' => array('feed-icon', 'art-rss-tag-icon'), 'title' => $text)));
}

/**
 * Return a Artisteer themed set of links.
 *
 * @param $links
 *   A keyed array of links to be themed.
 * @param $attributes
 *   A keyed array of attributes
 * @return
 *   A string containing an unordered list of links.
 */
function art_links_woker_D7($content) {
  $result = '';
  if (!isset($content['links'])) return $result;
  foreach (array_keys($content['links']) as $name) {
	$$name = & $content['links'][$name];
	if (isset($content['links'][$name]['#links'])) {
	  $links = $content['links'][$name]['#links'];
	  if (is_array($links)) {
		$output = get_links_html_output_D7($links);
		$result .= ($result == '') ? $output : '&nbsp;|&nbsp;' . $output;
	  }
    }
  }

$links = array();
  ob_start();?>
   <?php
  $output .= ob_get_clean();
  $output .= t('Tags: ');
  foreach ($terms as $term) {
    $links[] = l($term->name, taxonomy_term_path($term), array('rel' => 'tag', 'title' => strip_tags($term->description)));
  }  
  $output .= implode(', ', $links);
  

  return $result;  
}

function get_links_html_output_D7($links) {
	$output = '';
	$num_links = count($links);
    $index = 0;

	foreach ($links as $key => $link) {
	  $class = array($key);

      // Add first, last and active classes to the list of links to help out themers.
      if ($index == 0) {
        $class[] = 'first';
      }
      if ($index == $num_links) {
        $class[] = 'last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
          && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      
	  $link_output = '';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $link_output = l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $link_output = '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }
		
if (strpos ($class, "comment") !== FALSE) {
		
		  if ($index > 0) {
		  $output .= '&nbsp;|&nbsp;';
		}
		ob_start();?>
		<img class="art-metadata-icon" src="<?php echo get_full_path_to_theme(); ?>/images/postcommentsicon.png" width="18" height="18" alt="" /> <?php
		$output .= ob_get_clean();
		$output .= $link_output;
		$index++;
		continue;
		}
		


	}
	return $output;
}
