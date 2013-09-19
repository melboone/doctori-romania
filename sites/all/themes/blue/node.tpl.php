<div id="node-<?php print $node->nid; ?>" >
<div class="art-post">
    <div class="art-post-body">
<div class="art-post-inner">
<h2 class="art-postheader"><?php echo art_node_title_output($title, $node_url, $page); ?>
</h2>

<div class="art-postcontent">
    <!-- article-content -->
<div class="art-article"><?php print $picture; ?><?php echo $content; ?>
<?php if (isset($node->links['node_read_more'])) { echo '<div class="read_more">'.get_html_link_output($node->links['node_read_more']).'</div>'; }?></div>
<?php
 if ($terms && !$teaser) { print blue_print_terms($node, $vid = NULL, $unordered_list = TRUE);} 
?>

    <!-- /article-content -->
</div>
<div class="localitate-specializare"></div>

</div>

		<div class="cleared"></div>
    </div>
</div>

<?php
	$vars = get_defined_vars();
	$view = get_artx_drupal_view();
	$view->print_comment_node($vars);
?>
</div>
