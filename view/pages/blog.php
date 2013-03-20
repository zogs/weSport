<div class="container">

	<div class="banner">
		<div class="banner-title">Le Blog du Sportif</div>
		<img src="<?php echo Router::url('img/blog_banner.jpg');?>" alt="">
	</div>

	<?php echo $this->session->flash(); ?>

	<div class="blog-content">
		<?php 

			if($this->session->user()->getRole()=='admin') $allowComment = true;
			else $allowComment = false;
			$this->request('comments','show',array(
													array(
														'context'=>'blog',
														'context_id'=>1,
														'allowTitle'=>true,
														'allowComment'=>$allowComment,
														'allowReply'=>true,
														'displayRenderButtons'=>false,
														'showFormReply'=>true,
														'enablePreview'=>true,
														'enableInfiniteScrolling'=>true
														),
													)
							);

 	?>

	</div>

	<div class="blog-column">
		
		<div class="column-part">Pub</div>
		<div class="column-part">Annonce</div>
		<div class="column-part">Images</div>
		<div class="column-part">Pub</div>
	</div>


</div>


