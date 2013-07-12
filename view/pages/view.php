<?php $title_for_layout = $page->title;?>
<div class="page <?php echo $page->slug;?>">
	<div class="top-banner">
		<div class="void"></div>
		<div class="flash">
			<?php echo $this->session->flash() ;?>			
		</div>		
	</div>
	<div class="container">
		<div class="white-sheet">
			<div class="fresque fresque-mini"></div>
			<div class="head-sheet"><h1 class="title-sheet"><?php echo $page->title;?></h1></div>

			<div class="col_large">
				<div class="page-content">
					<?php echo $page->content; ?>
				</div>				
			</div>
			<div class="col_small">
				
			</div>
			<div class="clearfix"></div>
			<div class="fresque"></div>			
		</div>
	</div>
</div>