<?php
/* template name: Auction Result Listings */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Require a user to be logged in for this.
/*if ( !is_user_logged_in() ) {
	auth_redirect();
}*/

get_header();
nectar_page_header( $post->ID );
$nectar_fp_options = nectar_get_full_page_options();

if (isset($_REQUEST['auctionid'])){
    $ID = $_REQUEST['auctionid'];
} else {
    $ID = get_field('auction_id');
}

//$serverName = "sql11.ezhostingserver.com";
//$connInfo_Website = array( "Database"=>"tforce_worldwideauctioneers", "UID"=>"tforce_admin", "PWD"=>"S6C4yg21exqd");

$hostname = 'mysql24.ezhostingserver.com';
$username = 'wa_myadmin';
$password = 'W4_my@hm!N';
$dbname = 'wa_online_db';

// Create connection
$conn = mysqli_connect($hostname, $username, $password, $dbname);
// Check connection
if (!$conn) {
die("Connection failed:!" . mysqli_connect_error());
}

?>

<div class="container-wrap">
	<div class="<?php if ( $nectar_fp_options['page_full_screen_rows'] !== 'on' ) { echo 'container'; } ?> main-content">
		<div class="row">
			<?php
				nectar_hook_before_content(); 
				
				if ( have_posts() ) :
					while ( have_posts() ) :
						
						the_post();
						the_content();
						
					endwhile;
				endif;
				
				nectar_hook_after_content();
                
            ?>
            
            <form action="/car-search/" method="post"> 
                <input type="hidden" name="search" value="1">
                <table align="center">
                    <tr>
                        <td>Auction:</td>
                        <td>
                            <SELECT NAME="auctionid">
                                <OPTION VALUE="0">All</OPTION>
                                <?php
                                    $sql = "SELECT concat(convert(DATE_FORMAT(dstart, '%Y'),char), ' ', sname) as name, neventid FROM t_event where dstart < now() and neventid in (SELECT distinct neventid from t_inventory) and (neventid <> 29 and neventid <> 104) order by dstart desc";
                                    //$conn = sqlsrv_connect( $serverName, $connInfo_Website) or die( print_r( sqlsrv_errors(), true));
                                    //$stmt = sqlsrv_query( $conn, $sql );
                                    $stmt = mysqli_query($conn, $sql);
                                    
                                    /*if( $stmt === false) {
                                        die( print_r( sqlsrv_errors(), true) );
                                    }*/

                                    /*while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                                        $auctions[] = $row;
                                    }*/ 

                                    while( $row = mysqli_fetch_assoc($stmt)) {
                                        $auction[] = $row;
                                    }

                                    //sqlsrv_free_stmt( $stmt);
                                    //sqlsrv_close($conn);
                                    
                                    

                                    foreach ($auction as $item) {
                                        if ($item['neventid']==$ID){
                                            echo('<option value='.$item['neventid'].' selected="selected">'.$item['name'].'</option>');
                                        } else {
                                            echo('<option value='.$item['neventid'].'>'.$item['name'].'</option>');
                                        }
                                    }
                                    mysqli_close($conn);
                                ?>
                            </SELECT>
                        </td>
                        <td>
                            Lot #:
                        </td>
                        <td>
                            <?php 
                                if (isset($_POST['lotnumber']) && $_POST['lotnumber']>''){ 
                                    echo('<input type="text" name="lotnumber" value="'.$_POST['lotnumber'].'" />');
                                } else {
                                    echo('<input type="text" name="lotnumber" />');
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Make:</td>
                        <td>
                            <select name="make">
                                <OPTION VALUE="All">All</OPTION>
                                <?php
                                    $sql = "SELECT DISTINCT smake FROM t_inventory WHERE ncategoryid = '1' AND smake !='' and smake != '.' ORDER BY smake";
                                    $conn = mysqli_connect($hostname, $username, $password, $dbname);
                                    $stmt = mysqli_query($conn, $sql);
                                    
                                    while( $rows = mysqli_fetch_assoc($stmt)) {
                                        $make[] = $rows;
                                    }

                                    mysqli_close($conn);
                                    foreach ($make as $items) {
                                        if ($items['smake'] == $_POST['make']){
                                            echo('<option value='.$items['smake'].' selected="selected">'.$items['smake'].'</option>');
                                        } else {
                                            echo('<option value='.$items['smake'].'>'.$items['smake'].'</option>');
                                        }
                                    }
                                    
                                ?>
                            </select>
                        </td>
                        <td>Year:</td>
                        <td>
                            <?php 
                                if (isset($_POST['startyear']) && $_POST['startyear']>''){ 
                                    echo('<input type="text" name="startyear" value="'.$_POST['startyear'].'" />');
                                } else {
                                    echo('<input type="text" name="startyear" />');
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Model:</td>
                        <td>
                            <?php 
                                if (isset($_POST['model']) && $_POST['model']>''){ 
                                    echo('<input type="text" name="model" value="'.$_POST['model'].'" />');
                                } else {
                                    echo('<input type="text" name="model" />');
                                }
                            ?>
                        </td>
                        <td>End Year Range:</td>
                        <td>
                            <?php 
                                if (isset($_POST['yearend']) && $_POST['yearend']>''){ 
                                    echo('<input type="text" name="yearend" value="'.$_POST['yearend'].'" />');
                                } else {
                                    echo('<input type="text" name="yearend" />');
                                }
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="4" style="text-align:center;"><br /><input type="submit" />&nbsp;&nbsp;&nbsp;<input type="button" value="Clear Filter" onclick="location.href='/car-search/';" /></td></tr>
                </table>
            </form>
            
            <?php
                $sql = "SELECT nid, t_inventory.neventid, syear, smake, smodel, sstyle, slotnumber, sshortdescription, sreference, bsold, nbidprice, concat(convert(DATE_FORMAT(dstart, '%Y'),char), ' ', sname) as name FROM t_inventory left join t_event on t_event.neventid = t_inventory.neventid WHERE bsold = 1";
                if ($ID>0){ $sql .= " and t_inventory.neventid = ".$ID;} else { $sql .= " and t_inventory.neventid <> 29";}
                if (isset($_POST['make']) && ($_POST['make'] > '' && $_POST['make'] <> 'All')){ $sql .= " and smake = '".$_POST['make']."'";}
                if (isset($_POST['startyear']) && $_POST['startyear'] > ''){ 
                    if (isset($_POST['yearend']) && $_POST['yearend'] > '') { 
                        $sql .= " and syear between ". $_POST['startyear'] ." and ". $_POST['yearend'];
                    } else {
                        $sql .= " and syear = '".$_POST['startyear']."'";
                    }
                }
                if (isset($_POST['lotnumber']) && $_POST['lotnumber'] > ''){ $sql .= " and slotnumber = '".$_POST['lotnumber']."'";}
                if (isset($_POST['model']) && $_POST['model'] > ''){ $sql .= " and smodel = '".$_POST['model']."'";}
                $sql .= " ORDER BY nbidprice desc";
				$conn = mysqli_connect($hostname, $username, $password, $dbname);
                
                //$conn = sqlsrv_connect( $serverName, $connInfo_Website) or die( print_r( sqlsrv_errors(), true));
                //$stmt = sqlsrv_query( $conn, $sql );
                /*if( $stmt === false) {
                    die( print_r( sqlsrv_errors(), true) );
                }
                
                $carlist = array();
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    $carlist[] = $row;
                } 
                sqlsrv_free_stmt( $stmt);
                sqlsrv_close($conn);*/

                $stmt = mysqli_query($conn, $sql);
                
                $carlist = array();
                while( $row = mysqli_fetch_assoc($stmt)) {
                    $carlist[] = $row;
                }

  				mysqli_close($conn);
                if (count($carlist) > 0){
                    foreach ($carlist as $item) {
                        echo ('<div class="col span_12" style="margin:1rem 0;">
                        <div class="col span_4">');
                        if ($item['nbidprice'] > 0){
							if ($item['neventid'] > 87){
								echo('<img src="/photos/'.$item['neventid'].'/'.$item['sreference'].'/1.jpg" class="image-round" alt="'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'" width="300px">');
							} else {
								echo('<img src="/photos/'.$item['neventid'].'/'.$item['sreference'].'.jpg" class="image-round" alt="'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'" width="300px">');	
							}                            
                        } else {
                            echo('<img src="/photos/'.$item['neventid'].'/'.$item['sreference'].'/1.jpg" class="image-round" alt="'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'" width="300px">');
                        }
                        echo('</div><div class="col span_8">');
                        echo($item['name'].'<br>');
                        if ($item['slotnumber']>"") { echo('<h4>Lot '.$item['slotnumber'].'</h4>');}
                        echo ('<h3 class="text-primary">'.$item['syear'].' '.$item['smake'].' '.$item['smodel'].' '.$item['sstyle'].'</h6>');
                        if ($item['bsold'] == 1){echo('<font style="color: #a71c20; font-weight: bold; font-size: 20pt;">SOLD</font><br />');}
                        if ($item['nbidprice'] > 0){ echo('<h4>$'. number_format($item['nbidprice']) .'</h4>');}
                        echo('</div></div>');
                    }
                }else{
                    echo "<p>No results found.</p>";
                }
            ?>
		</div><!--/row-->
	</div><!--/container-->
</div><!--/container-wrap-->

<?php get_footer(); ?>