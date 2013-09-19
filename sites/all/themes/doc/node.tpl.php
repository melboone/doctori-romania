<div id="node-<?php print $node->nid; ?>" class="node<?php if(!empty($type)) { echo ' '.$type; } if ($sticky) { echo ' sticky'; } if ($promote) { print ' promote'; } if (!$status) { print ' node-unpublished'; } ?>">
<div class="art-post">
    <div class="art-post-body">
<div class="art-post-inner">
<h2 class="art-postheader"><?php echo art_node_title_output($title, $node_url, $page); ?>
</h2>
<div class="art-postcontent">
<div class="art-article"><?php print $picture; ?><?php echo $content; ?>
<?php if (isset($node->links['node_read_more'])) { echo '<div class="read_more">'.get_html_link_output($node->links['node_read_more']).'</div>'; }?></div>
</div>
<div class="cleared"></div>
</div>    </div></div></div>
<div id="localizare-specializare">
<?php
 if ($terms && !$teaser) { print doc_print_terms($node, $vid = NULL, $unordered_list = TRUE);} 
?>
</div>
