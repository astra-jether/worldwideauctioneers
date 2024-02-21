<?php

    function wwa_pull_listings_by_event($eventID, $tablename="t_inventory", $startingLot="", $endingLot="", $is_all=false, $limit=5, $offset=0){

        $hostname = 'mysql24.ezhostingserver.com';
        $username = 'wa_myadmin';
        $password = 'W4_my@hm!N';
        $dbname = 'wa_online_db';

       
        $conn = mysqli_connect($hostname, $username, $password, $dbname);
  
        if (!$conn) {
        die("Connection failed: ! " . mysqli_connect_error());
        }
        
        if( !$is_all )
        {
            if(isset($_REQUEST['orderby'])){
                if($_REQUEST['orderby'] == "lotnumber"){
                    if(($startingLot != "") && ($endingLot != "")){
                        $sql = "SELECT svin,nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, sshortdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice, bfeatured, saddendum, CONVERT(slongdescription, VARCHAR(9999)) as longdescription FROM ".$tablename." WHERE neventid = ".$eventID." and convert(slotnumber, float) >= ".$startingLot." and convert(slotnumber, float) <= ".$endingLot." and ncategoryid = 1 ORDER BY convert(slotnumber, float)";
                    }else{
                        $sql = "SELECT svin,nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, sshortdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice, bfeatured, saddendum, CONVERT(slongdescription, VARCHAR(9999)) as longdescription FROM ".$tablename." WHERE (neventid = ".$eventID." ) and ncategoryid = 1 ORDER BY convert(slotnumber, float)";
                    }
                }else{
                    if(($startingLot != "") && ($endingLot != "")){
                        $sql = "SELECT svin,nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, sshortdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice, bfeatured, saddendum, CONVERT(slongdescription, VARCHAR(9999)) as longdescription FROM ".$tablename." WHERE neventid = ".$eventID." and convert(slotnumber, float) >= ".$startingLot." and convert(slotnumber, float) <= ".$endingLot." and ncategoryid = 1  ORDER BY smake, syear";
                    }else{
                        $sql = "SELECT svin,nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, sshortdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice, bfeatured, saddendum, CONVERT(slongdescription, VARCHAR(9999)) as longdescription FROM ".$tablename." WHERE (neventid = ".$eventID." ) and ncategoryid = 1 ORDER BY smake, syear";
                    }
                }
            }else{
                if(($startingLot != "") && ($endingLot != "")){
                    $sql = "SELECT svin,nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, sshortdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice, bfeatured, saddendum, CONVERT(slongdescription, VARCHAR(9999)) as longdescription FROM ".$tablename." WHERE neventid = ".$eventID." and convert(slotnumber, float) >= ".$startingLot." and convert(slotnumber, float) <= ".$endingLot." and ncategoryid = 1 ORDER BY smake, syear";
                }else{
                    $sql = "SELECT svin,nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, sshortdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice, bfeatured, saddendum, CONVERT(slongdescription, VARCHAR(9999)) as longdescription FROM ".$tablename."  WHERE (neventid = ".$eventID.") and ncategoryid = 1 ORDER BY smake, syear";
                }
            }
        }
        else
        {
            $sql = "SELECT svin,nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, scolor, sshortdescription, CONVERT(slongdescription, VARCHAR(9999)) as longdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice, nlowestimate, nhighestimate, svin , bfeatured, saddendum FROM ".$tablename." WHERE (neventid = ".$eventID.") and ncategoryid = 1 ORDER BY smake, syear";
        }
        
        
        $stmt = mysqli_query($conn, $sql);

        
        $carlist = array();
        while( $row = mysqli_fetch_assoc($stmt)) {
            $carlist[] = $row;
        }
        mysqli_close($conn);
        
        return $carlist;
    }
    
    function wwa_pull_single_listing($eventID, $referenceID, $tablename="t_inventory"){
        //$serverName = "sql11.ezhostingserver.com";
        //$connInfo_Website = array( "Database"=>"tforce_worldwideauctioneers", "UID"=>"tforce_admin", "PWD"=>"S6C4yg21exqd");
        
        $hostname = 'mysql24.ezhostingserver.com';
        $username = 'wa_myadmin';
        $password = 'W4_my@hm!N';
        $dbname = 'wa_online_db';

       
        $conn1 = mysqli_connect($hostname, $username, $password, $dbname);

        $sql1 = "SELECT nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, scolor, sshortdescription, CONVERT(slongdescription, VARCHAR(999)) as longdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice, nlowestimate, nhighestimate, svin, saddendum, svideo FROM ".$tablename." WHERE neventid = '".$eventID."' AND sreference = '".$referenceID."'";
        //$conn = sqlsrv_connect( $serverName, $connInfo_Website) or die( print_r( sqlsrv_errors(), true));
        //$stmt = sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        
        $stmt1 = mysqli_query($conn1, $sql1);

        
        /*echo "Has Rows? <br />";
        echo var_dump(sqlsrv_has_rows ( $stmt ));
        
        echo "Num Rows? <br />";
        echo var_dump(sqlsrv_num_rows ( $stmt ));
        
        echo "First Row:<br />";
        echo var_dump(sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) );*/
        
        $carlist1 = array();
        while( $row1 = mysqli_fetch_assoc($stmt1) ) {
            /*echo "<pre>";
            print_r($row);
            echo "</pre>";*/
            $carlist1[] = $row1;
        }
        
        mysqli_close($conn1);
        
        return $carlist1[0];
    }
    
    function wwa_pull_memorabilia_listings($eventID, $tablename="t_inventory", $startingLot="", $endingLot=""){
        //$serverName = "sql11.ezhostingserver.com";
        //$connInfo_Website = array( "Database"=>"tforce_worldwideauctioneers", "UID"=>"tforce_admin", "PWD"=>"S6C4yg21exqd");
        $hostname = 'mysql24.ezhostingserver.com';
        $username = 'wa_myadmin';
        $password = 'W4_my@hm!N';
        $dbname = 'wa_online_db';

        $conn2 = mysqli_connect($hostname, $username, $password, $dbname);

        if(($startingLot != "") && ($startingLot != "")){
            $sql = "SELECT nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, sshortdescription, slongdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice FROM ".$tablename." WHERE neventid = ".$eventID." and convert(slotnumber, float) >= ".$startingLot." and convert(slotnumber, float) <= ".$endingLot." and ncategoryid = 2 ORDER BY CONVERT(slotnumber, float), convert(slotnumber, float)";
        }else{
			if($eventID == 80){
				$sql2 = "SELECT nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, sshortdescription, slongdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice FROM ".$tablename." WHERE neventid = ".$eventID." and ncategoryid = 2 ORDER BY convert(slotnumber, float), convert(slotnumber, float)";
				//and (convert(float,slotnumber) <> 7166 and convert(float,slotnumber) <> 7167 and convert(float,slotnumber) <> 7168) 
			} else {
            	$sql2 = "SELECT nid, neventid, syear, smake, smodel, sstyle, slotnumber, sfeatureitems, sshortdescription, slongdescription, sreference, bsold, breserve, sday, ncategoryid, nbidprice FROM ".$tablename." WHERE (neventid = ".$eventID." and ncategoryid = 2) ORDER BY convert(slotnumber, float), convert(slotnumber, float)";
				//and (convert(float, slotnumber) <> 7633 and convert(float, slotnumber) <> 7637) 
			}
        }
        
        //$conn = sqlsrv_connect( $serverName, $connInfo_Website) or die( print_r( sqlsrv_errors(), true));  //and convert(float,slotnumber) > 7000
        //$stmt = sqlsrv_query( $conn, $sql );
        
        $stmt2 = mysqli_query($conn2, $sql2);

        $memlist = array();
        while( $row2 = mysqli_fetch_assoc($stmt2) ) {
            $memlist[] = $row2;
        }
        
        mysqli_close($conn2);
        
        return $memlist;
    }
    
    function wwa_pull_private_treaty_listings(){
        //$serverName = "sql11.ezhostingserver.com";
        //$connInfo_Website = array( "Database"=>"tforce_worldwideauctioneers", "UID"=>"tforce_admin", "PWD"=>"S6C4yg21exqd");
        
        $hostname = 'mysql24.ezhostingserver.com';
        $username = 'wa_myadmin';
        $password = 'W4_my@hm!N';
        $dbname = 'wa_online_db';

        $sql3 = "SELECT nid, neventid, syear, smake, smodel, sstyle, slotnumber, sshortdescription, sreference, bsold, breserve, sday FROM t_inventory WHERE neventid = 29 ORDER BY bfeatured desc, bsold, smake, syear, smodel";
        
        $conn3 = mysqli_connect($hostname, $username, $password, $dbname);
        
        $stmt3 = mysqli_query($conn3, $sql3);

        
        $carlist2 = array();
        while( $row3 = mysqli_fetch_assoc($stmt3) ) {
            $carlist2[] = $row3;
        }
        
        mysqli_close($conn3);
        
        return $carlist2;
    }

?>