<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#victro-modal-system-admin" onclick="$('#inputcommand').focus();" data-toggle="modal"><i class="fa fa-terminal"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading"><?php victro_translate("Functions"); ?></h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-male bg-blue"></i>

              <div class="menu-info">
                  <h4 class="control-sidebar-subheading"><?php victro_translate("Robot"); ?></h4>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-plug bg-blue"></i>

              <div class="menu-info">
                  <h4 class="control-sidebar-subheading"><?php victro_translate("Power"); ?></h4>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:open_addon()">
              <i class="menu-icon fa fa-puzzle-piece bg-blue"></i>

              <div class="menu-info">
                  <h4 class="control-sidebar-subheading"><?php victro_translate("Addon"); ?></h4>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-cog bg-blue"></i>

              <div class="menu-info">
                  <h4 class="control-sidebar-subheading"><?php victro_translate("System"); ?></h4>
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo SITE_URL."sys/update"; ?>">
              <i class="menu-icon fa fa-cloud-download  bg-blue"></i>

              <div class="menu-info">
                  <h4 class="control-sidebar-subheading"><?php victro_translate("Check Update"); ?></h4>
              </div>
            </a>
          </li>
        </ul>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
<div class="control-sidebar-bg"></div>
