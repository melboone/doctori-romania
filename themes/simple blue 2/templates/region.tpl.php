<?php if ($content): ?>
  <div class="<?php print $classes; ?>">
    <?php switch ($region) {
			case "navigation":
				print art_menu_worker($content, true, 'art-menu');
				break;
			case "vnavigation_left":
			case "vnavigation_right":

				break;
			default:
				print $content;
				break;
		}?>
  </div>
<?php endif; ?>