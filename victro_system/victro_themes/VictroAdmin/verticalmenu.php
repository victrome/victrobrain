		<li class="treeview <?php if(isset($victro_url[0]) and $victro_url[0] == "system" and isset($victro_url[1]) and $victro_url[1] == "home"){ echo "active";} ?>">
			<a href="<?php echo SITE_URL; ?>sys/home">
				<i class="fa fa-home"></i>
				<span><?php victro_translate('home'); ?></span>
			</a>
		</li>

		<?php foreach($victro_menu as $victro_itens){ ?>
                    <li class="treeview">
                        <a href="#">
                            <i class="<?php echo $victro_itens['menu']['icon']; ?>"></i> <span><?php echo $victro_itens['menu']['name']; ?></span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <?php
                        if(count($victro_itens['submenu']) > 0){ ?>
                            <ul class="treeview-menu">
                                    <?php foreach($victro_itens['submenu'] as $victro_subitens){ ?>
                                    <li><a href="<?php echo $victro_subitens['link']; ?>">
																			<i class="<?php echo $victro_subitens['icon']; ?>"></i> <span><?php echo $victro_subitens['name']; ?></span>
								
																		</a></li>
                                    <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>

		<?php } ?>
