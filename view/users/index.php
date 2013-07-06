<div class="indexuser">
	<div class="top-banner">
		<div class="void"></div>		
		<div class="flash">
			<?php echo $this->session->flash() ;?>			
		</div>
	</div>

	<div class="container">
		<div class="fresque fresque-mini"></div>		
		<div class="white-sheet">
			<section>
				<form action="<?php echo Router::url('users/index/');?>" method="GET">
					<?php				
						echo $this->request('world','locate',array(array("obj"=>$location_codes)));

						echo $this->Form->input('submit','',array('type'=>'submit','value'=>'Rechercher','class'=>'btn-ws btn-ws-small'));
					?>					
				</form>
			</section>

			<section>
				<h1>Les sportifs de 
					<?php

						foreach ($location as $key => $value) {
							$txt = $value;
						}
						echo $txt;
					?>
				</h1>
			</section>			

			<section>
				<div class="col_large">	

					<div class="block">
						<div class="userlist">
							<?php foreach ($users as $user):?>
								
								<a class="user" href="<?php echo $user->getLink();?>">
									<img src="<?php echo $user->getAvatar();?>" alt="">
									<span>
										<strong><?php echo $user->getLogin();?></strong>
										<small><?php echo $user->getAge();?></small>
									</span>
								</a>
							<?php endforeach; ?>
						</div>

						<div class="pagination">
							<ul>
								<?php for($i=1; $i <= $nbpage; $i++):?>
										<li <?php if($i==$this->request->get('page')) echo 'class="active"';?>><a href="<?php echo Router::url('users/index/?page='.$i);?>"><?php echo $i;?></a></li>
								<?php endfor; ?>	
							</ul>
						</div>
					</div>				
				</div>

				<div class="col_small">					
				</div>
			</section>
		</div>
		<div class="fresque"></div>
	</div>
</div>
