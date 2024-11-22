<?php
ini_set('max_execution_time', 300);
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
{    
    $user = $_SESSION["user"];
    $contractor_name = $_SESSION["contractor"];
	$area = $_SESSION["area"];
	$temp = explode('/',$area);
	$n = sizeof($temp);
	$opmc = $_SESSION["opmc"];
    
    $refno=date("ymdHis");
}
else 
{     
    echo '<script type="text/javascript"> document.location = "Login.php";</script>'; 
}

function my_simple_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}
?>
  
<?php if($contractor_name == 'SLT' ){ ?>

   <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="index.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                DASHBOARD
              </p>
            </a>

          </li>

          <section class="content" style="font-size: 14px;">
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                SERVICE ORDER
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="slt_pen.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>PENDING</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="slt_com.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>COMPLETED</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="slt_ret.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>RETURNED</p>
				</a>
			  </li>
			</ul>   
          </li>
          
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                CONTRACTOR
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="con_list.php" class="nav-link active">
				  <i class="far fa-circle nav-icon"></i>
				  <p>LIST</p>
				</a>
			  </li>
			  
			<?php if($user == '007402' ||$user == '010552' ||$user == '010559' ||$user == '012583'){    ?>  
			  <li class="nav-item">
				<a href="con_new.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>ADD / UPDATE</p>
				</a>
			  </li>
			<?php } ?>  
			</ul>
          </li>
      
      <?php if($user == '007402' ||$user == '010552' ||$user == '010559' ||$user == '012368'||$user == '012583'){ ?>    
		    <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                INVOICE
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="invlist.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>INVOICE VIEW</p>
				</a>
			  </li>
			</ul> 

			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="invreset.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>INVOICE RESET</p>
				</a>
			  </li>
			</ul>   
          </li>
		  
		  
		  <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                NC DETAIL UPDATE
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="ncdet.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>CONNECTION DETAIL</p>
				</a>
			  </li>
			</ul> 

			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="ncqtydet.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>CONNECTION DETAIL QUALITY</p>
				</a>
			  </li>
			</ul>   

          </li>
      
     <?php  } ?>    
          <li class="nav-item">
            <a href="query.php" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                SEARCH
                
              </p>
            </a>
          </li>


            <li class="nav-item">
            <a href="search.php" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                QUALITY
                <i class="right fas fa-angle-left"></i>
                
              </p>
            </a>
			<ul class="nav nav-treeview">
			  
		   <?php if($user == '007402' ||$user == '012583'){    ?>     
			  <li class="nav-item">
				<a href="vatsample.php?id=sample" class="nav-link active">
				  <i class="far fa-circle nav-icon"></i>
				  <p>SAMPLE RATE</p>
				</a>
			  </li>
		  
		  <?php } if($user == '007402' ||$user == '010552' ||$user == '010559' ||$user == '012583'){    ?>      
			  <li class="nav-item">
				<a href="vatsample.php?id=vat" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>VAT RATE</p>
				</a>
			  </li>
			  
		 <?php } if($user == '007402' ||$user == '010552' ||$user == '010559' ||$user == '012583'){    ?>       
			  <li class="nav-item">
				<a href="unit.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>UNIT RATE</p>
				</a>
			  </li>
			  
			  <?php } ?>   
			</ul>
          </li>
          
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                OPMC INBOX
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="assignsod.php" class="nav-link active">
				  <i class="far fa-circle nav-icon"></i>
				  <p>ASSGIN SOD</p>
				</a>
			  </li>
			  
			  <li class="nav-item">
				<a href="sltsod.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>SLT SOD</p>
				</a>
			  </li>
			 <li class="nav-item">
  
			<?php
			
			$a  = my_simple_crypt( $user, 'e' );
			$b = my_simple_crypt($contractor_name,'e' );
				$mylink = 'https://serviceportalimg.slt.lk/CMS/opmc.php?user='.$a.'&con='.$b;
			  //  $mylink2  = 'https://serviceportalimg.slt.lk/CMS/index.php/url='.urlencode($mylink);
			  echo' <a href="'.$mylink.'" class="nav-link" target="_blank">';
				
			?> 

				  <i class="far fa-circle nav-icon"></i>
				  
				  <p>
					QUALITY VIEW
				  </p>
				  
				</a>
			  </li>
			</ul>
          </li>
		  
		  <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                TEAM
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="teamviewslt.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>TEAM VIEW</p>
				</a>
			  </li>

			</ul>
          </li>
		
		<?php if(isset($opmc) && $opmc != '') { ?>
			
		  <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                OPMC STAFF INBOX 
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="opmc_sod_assign.php" class="nav-link active">
				  <i class="far fa-circle nav-icon"></i>
				  <p>SOD ASSIGN</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="opmc_add_group.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>ADD GROUP</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="opmc_add_user.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>ADD USER</p>
				</a>
			  </li>
			</ul>
          </li>
          
        <?php  } ?>  

        </ul>
      </nav>




<?php } else { ?> 
   <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="dash.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                DASHBOARD
              </p>
            </a>

          </li>


          <section class="content" style="font-size: 14px;">
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                SERVICE ORDER
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="CO_SO_List.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>PENDING</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="CO_SO_CON_Comp.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>COMPLETED</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="CO_SO_CON_Compap.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>APPROVED</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="CO_SO_Ret.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>RETURNED</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="CO_SO_Mob_assign.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>MOBILE ASSIGN</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="CO_SO_Mob_change.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>CHANGE MOBILE ASSIGN</p>
				</a>
			  </li>
			</ul>   
          </li>
          
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                INVOICE
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="CO_SO_Invoice_Gen.php" class="nav-link active">
				  <i class="far fa-circle nav-icon"></i>
				  <p>NEW</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="CO_SO_INV_VIEW.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>LIST</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="CO_MET.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>CHECK METERIAL</p>
				</a>
			  </li>
			</ul>
          </li>
          
          <li class="nav-item">
          
			<?php
			
			$a  = my_simple_crypt( $user, 'e' );
			$b = my_simple_crypt($contractor_name,'e' );
			$mylink = 'https://serviceportalimg.slt.lk/CMS/index.php?user='.$a.'&con='.$b;
			  //  $mylink2  = 'https://serviceportalimg.slt.lk/CMS/index.php/url='.urlencode($mylink);
			echo' <a href="'.$mylink.'" class="nav-link" target="_blank">';
				
			?> 

            <i class="nav-icon fas fa-calendar-alt"></i>
             
			 <p>
                QUALITY
              </p>
			  
            </a>
          </li>
          
          <li class="nav-item">
            <a href="query.php" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                SEARCH
                
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="appoint.php" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                APPOINTMENTS
                
              </p>
            </a>
          </li>
		  
		 <li class="nav-item">
			<a href="" class="nav-link">
			  <i class="nav-icon fas fa-calendar-alt"></i>
			  <p>
				TEAM
				<i class="right fas fa-angle-left"></i>
			  </p>
			</a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="team.php" class="nav-link active">
				  <i class="far fa-circle nav-icon"></i>
				  <p>TEAM ADD</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="teamview.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>TEAM VIEW</p>
				</a>
			  </li>
			</ul>
          </li>
          
		  
		    
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                OPMC STAFF INBOX
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="CO_SO_Invoice_Gen.php" class="nav-link active">
				  <i class="far fa-circle nav-icon"></i>
				  <p>NEW</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="CO_SO_INV_VIEW.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>LIST</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="CO_MET.php" class="nav-link">
				  <i class="far fa-circle nav-icon"></i>
				  <p>CHECK METERIAL</p>
				</a>
			  </li>
			</ul>
          </li>
          

        </ul>
      </nav>
<?php } ?>
