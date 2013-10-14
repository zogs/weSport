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
				<div id="forum-content">
					<?php echo $this->request('forum','index',array($type,$slug));?>					
				</div>
			</section>
		</div>
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){

		$('.link-forum').click(function(e){
			
			e.preventDefault();

			var url = $(this).attr('href');
			var container = $("#forum-content");

			$.ajax({
				url:url,
				type:'GET',
				success: function(html){

					container.empty().html(html);
				}
			})
		});

	});
</script>