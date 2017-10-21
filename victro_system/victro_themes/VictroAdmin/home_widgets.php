<?php if($victro_widgets != null) { foreach($victro_widgets as $victro_dataw){ ?>
<div class="col-md-<?php echo $victro_dataw['width']; ?> col-sm-12 col-xs-12">
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<div class="panel-heading-btn">
				<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
				<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
			</div>
			<h4 class="panel-title"><?php echo $victro_dataw['name']; ?></h4>
		</div>
	
		<div class="panel-body">
			
			<?php if($victro_dataw['type'] == "sql"){ ?>
				<table class="table table-hover">
					<thead>
                                         	<tr>
						<?php foreach($victro_dataw['title'] as $victro_dataw1){ ?>
							<th><?php echo $victro_dataw1; ?></th>
						<?php } ?>
						</tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($victro_dataw['content'] as $victro_dataw2){ ?>
                                        	<tr>
						<?php foreach($victro_dataw2 as $victro_dataw3){ ?>	
							<td><?php echo $victro_dataw3; ?></td>	
						<?php } ?>
						</tr>
					<?php } ?>
                                        </tbody>
                                </table>
				<?php } else if($victro_dataw['type'] == "HTML") { echo base64_decode($victro_dataw['content']);
                                } else {
                                if(file_exists('widgets/'.$victro_dataw['content'])){
                                    include('widgets/'.$victro_dataw['content']);
                                } else {
                                    echo $victro_language['nofile'];
                                }
                    }
            ?>
                         <?php  if($victro_dataw['button'] == true){ echo "<center>".$victro_dataw['button']."</center>"; } ?>
			
		</div>
	</div>
</div>
<?php }} ?>