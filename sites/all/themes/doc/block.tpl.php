<div class="art-block clear-block block block-<?php print $block->module ?>" id="block-<?php print $block->module .'-'. $block->delta; ?>">
    <div class="art-block-body">

	<?php if (!empty($block->subject)): ?>
<div class="art-blockheader">
		    <div class="l"></div>
		    <div class="r"></div>
		    <h3 class="t subject">	
			<?php echo $block->subject; ?>
</h3>
		</div>
		    
	<?php endif; ?>
<div class="art-blockcontent content">
	    <div class="art-blockcontent-tl"></div>
	    <div class="art-blockcontent-tr"></div>
	    <div class="art-blockcontent-bl"></div>
	    <div class="art-blockcontent-br"></div>
	    <div class="art-blockcontent-tc"></div>
	    <div class="art-blockcontent-bc"></div>
	    <div class="art-blockcontent-cl"></div>
	    <div class="art-blockcontent-cr"></div>
	    <div class="art-blockcontent-cc"></div>
	    <div class="art-blockcontent-body">
	
		<?php echo $block->content; ?>

	
	    </div>
	</div>
	

    </div>
</div>
