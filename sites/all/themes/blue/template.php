<?php
// $Id

require_once("common_methods.php");

switch (get_drupal_version()) {
	case 5:
	  require_once("drupal5_theme_methods.php");
	  break;
	case 6:
	  require_once("drupal6_theme_methods.php");
	  break;
	case 7:
	  require_once("drupal7_theme_methods.php");
	  break;
    default:
		  break;
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

function get_artx_drupal_view() {
	if (get_drupal_version() == 7)
		return new artx_view_drupal7();
	return new artx_view_drupal56();
}

if (!function_exists('render'))	{
	function render($var) {
		return $var;
	}
}

class artx_view_drupal56 {
	
	public function print_head($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo get_page_language($language); ?>" xml:lang="<?php echo get_page_language($language); ?>" <?php if (isset($language->dir)) { echo 'dir="'.$language->dir.'"'; }?> >
<head>
  <?php echo $head; ?>
  <title><?php if (isset($head_title )) { echo $head_title; } ?></title>  
  <?php echo $styles ?>
  <?php echo $scripts ?>
  <!--[if IE 6]><link rel="stylesheet" href="<?php echo $base_path . $directory; ?>/style.ie6.css" type="text/css" /><![endif]-->  
  <!--[if IE 7]><link rel="stylesheet" href="<?php echo $base_path . $directory; ?>/style.ie7.css" type="text/css" media="screen" /><![endif]-->
  <script type="text/javascript"><?php /* Needed to avoid Flash of Unstyle Content in IE */ ?> </script>
</head>

<body <?php if (!empty($body_classes)) { echo 'class="'.$body_classes.'"'; } ?>>
<?php
	}


	public function print_closure($vars) {
	echo $vars['closure'];
?>
</body>
</html>
<?php
	}

	public function print_comment($vars) {
		foreach (array_keys($vars) as $name)
		$$name = & $vars[$name];
?>
<div class="comment<?php print ($comment->new) ? ' comment-new' : ''; print ' '. $status; print ' '. $zebra; ?>">

  <div class="clear-block">
  <?php if ($submitted): ?>
    <span class="submitted"><?php print $submitted; ?></span>
  <?php endif; ?>

  <?php if ($comment->new) : ?>
    <span class="new"><?php print drupal_ucfirst($new) ?></span>
  <?php endif; ?>

  <?php print $picture ?>

    <span style="display: none;"><?php print $title; ?></span>

    <div class="content">
      <?php print $content ?>
      <?php if ($signature): ?>
      <div class="clear-block">
        <div></div>
        <?php print $signature ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($links): ?>
    <div class="links"><?php print $links ?></div>
  <?php endif; ?>
</div>
<?php
	}
		
	public function print_comment_wrapper($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<div id="comments">
  <?php print $content; ?>
</div>
	<?php
	}
	
	public function print_comment_node($vars) {
		return;
	}
}


class artx_view_drupal7 {

	public function print_head($vars) {
		print render($vars['page']['header']);
	}
	
	public function print_closure($vars) {
		return;
	}

	public function print_comment($vars) {
		foreach (array_keys($vars) as $name)
		$$name = & $vars[$name];
?>
<div class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php print $picture ?>

  <div class="submitted">
    <?php print $permalink; ?>
    <?php
      print t('Submitted by !username on !datetime.',
        array('!username' => $author, '!datetime' => $created));
    ?>
  </div>

  <?php if ($new): ?>
    <span class="new"><?php print $new ?></span>
  <?php endif; ?>

  <?php print render($title_prefix); ?>
  <h3><?php print $title ?></h3>
  <?php print render($title_suffix); ?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['links']);
      print render($content);
    ?>
    <?php if ($signature): ?>
    <div class="user-signature clearfix">
      <?php print $signature ?>
    </div>
    <?php endif; ?>
  </div>

  <?php print render($content['links']) ?>
</div>
<?php
	}

	public function print_comment_wrapper($vars)	{
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<div id="comments" class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php if ($content['comments'] && $node->type != 'forum'): ?>
    <?php print render($title_prefix); ?>
    <h2 class="art-postheader"><?php print t('Comments'); ?></h2>
    <?php print render($title_suffix); ?>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

  <?php if ($content['comment_form']): ?>
    <h2 class="art-postheader"><?php print t('Add new comment'); ?></h2>
    <?php print render($content['comment_form']); ?>
  <?php endif; ?>
</div>
	<?php
	}
		
	public function print_comment_node($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
		$comments = (isset($content['comments']) ? render($content['comments']) : '');
		if (!empty($comments) && $page):
?>
<div class="art-post">
    <div class="art-post-body">
<div class="art-post-inner">

<div class="art-postcontent">
	    <!-- article-content -->
	
<?php
		echo $comments;
?>

	    <!-- /article-content -->
	</div>
	<div class="cleared"></div>
	

</div>

		<div class="cleared"></div>
    </div>
</div>
<?php
      endif;
	} // end public function print_comment_node()
} // end class artx_view_drupal7

function blue_print_terms($node, $vid = NULL, $ordered_list = TRUE) {
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
