<div class="forum-board">
	<div class="top-banner">
		<div class="void"></div>
		<div class="flash">
			<?php echo $this->session->flash();?>
		</div>
	</div>

	<div class="container">
		<div class="fresque fresque-mini"></div>
		<div class="white-sheet">
			<section>
				<div class="content-header">
					<div class="title-header">
						<h1>FORUM</h1>
					</div>
				</div>
			</section>

			<section>
				
				<?php echo $this->request('forum','index',array());?>

			</section>
		</div>
	</div>

</div>