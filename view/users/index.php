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
				<form action="">
					<?php				
						echo $this->request('world','locate',array(array("obj"=>$location_code)));
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
				</div>

				<div class="col_small">					
				</div>
			</section>
		</div>
		<div class="fresque"></div>
	</div>
</div>
