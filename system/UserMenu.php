<?php
session_start();
require_once("Database.class.php");
require_once("Data.class.php");
require_once("Project.class.php");
require_once("User.class.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
if(isset($_COOKIE['NCS_PROJECT']))
{
  $project = new Project($_COOKIE['NCS_PROJECT']);
  $mailbox = $data->GetMailbox($user, $project, true);
?>
    <!-- Mailbox Area -->
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" aria-expanded="false">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge"><?php echo $mailbox->num_rows; ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
        <?php
        if($mailbox->num_rows == 0)
        {
          ?>

<a href="javascript:void(0)" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <div class="media-body" align="center">
                <h3 class="dropdown-item-title">
                  <b>No new mails</b>
                </h3>
              </p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>

        <?php
        }else{
        $i = 0;
          while($row = mysqli_fetch_assoc($mailbox))
          {
            $i++;
        ?>
          <a href="dashboard/mailbox/readmail/<?php echo $row['ID']; ?>" class="dropdown-item"  <?php if(($i %2) != 0) echo "style='background-color:rgba(0,0,0,.05);'"; ?>>
            <!-- Message Start -->
            <div class="media">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  <b><?php echo $row['Name']; ?></b>
                </h3>
                <span class="float-right text-sm"><i class="fas fa-chevron-right"></i></span>
                <p class="text-sm"><?php if(strlen($row['Subject']) > 40) echo substr($row['Subject'],0,25)."..."; else echo $row['Subject']; ?></p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 
                <?php
                  echo $row['Date'];
                ?>
              </p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <?php }} ?>
          <a href="dashboard/mailbox" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
<?php } ?>
    
		<!-- Account Area -->
<li class="nav-item dropdown user-menu">
        <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <img src="<?php echo $user->Profile_Pic; ?>" onError="this.onError=null;this.src='images/placeholder.jpg';" class="user-image img-circle elevation-2" alt="User Image">
          <span class="d-none d-md-inline"><?php echo $user->First_Name." ".$user->Last_Name; ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px; bottom: 0px;">
          <!-- User image -->
          <div class="card card-widget widget-user-2">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-warning">
                <div class="widget-user-image">
                  <img class="img-circle elevation-2" src="<?php echo $user->Profile_Pic; ?>" onError="this.onError=null;this.src='images/placeholder.jpg';" alt="User Avatar">
                </div>
                <!-- /.widget-user-image -->
                <h4 class="widget-user-username"><?php echo $user->First_Name." ".$user->Last_Name; ?></h4>
                <span class="widget-user-desc text-sm">
				<?php
				if($user->Premium == "true")
					echo "<i class='fas fa-crown' style='color:yellow;'></i> Premium user";
				else {
					echo "<i class='fas fa-smile-beam' style='color:yellow;'></i> Basic user";
				} ?>
				</span>
              </div>
              <div class="card-footer p-0">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                      Send feedback
                    </a>
                  </li>
                  
                  <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                      Upgrade
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="settings-account" class="nav-link">
                      Settings
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="javascript:void(0)" onClick="logout()" class="nav-link">
                      Logout
                    </a>
                  </li>
                </ul>
              </div>
            </div>
        </div>
      </li>
