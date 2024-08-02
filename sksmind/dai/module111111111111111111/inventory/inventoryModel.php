<?php
//include('../../../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
class inventoryModel
{
    public $table;
	public $table_product;
	public $table_product_value;
	public $helper;
	 function __construct()
    {
        try {
            $this->table  = "dai_inward";
			$this->table_product  = "dai_product";
			$this->table_product_value  = "dai_product_value";
			$this->helper  = new Helper;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all ledger Data
    public function getMyInventory($post,$form_type)
    {
		$location = $sku = $carat = $type = $package = $shape = $color =  $intensity = $overtone = $clarity = $f_intensity = $memo = $symmentry = $cut = $polish =  "";
		// Packat - SKU - Report Search
		if(isset($post['sku']) and $post['sku']!="")
		{ 
			$i=0;
			$post['sku'] = str_replace(' ', '', $post['sku']);
			$tem= explode(',', $post['sku']);
		
			if(count($tem) != 1)
			{
				$skua = implode("','",$tem);
				$sku .= " and ( p.mfg_code IN ('".$skua."') or p.sku IN ('".$skua."') or pv.report_no IN ('".$skua."') )";
				//$sku .= " and ( p.sku IN ('".$skua."') or pv.report_no IN ('".$skua."') )";
				//$sku .= " and ( p.sku NOT IN ('".$skua."')  )";
			}
			else
			{
				$sku = " and ( p.mfg_code LIKE '%".$post['sku']."%' or p.sku LIKE '%".$post['sku']."%' or pv.report_no LIKE '%".$post['sku']."%' ) ";
			}
		}		
		//carat from and to search
		if( (isset($post['cfrom']) && $post['cfrom']!="") && (isset($post['cto']) && $post['cto']!=""))
		{ 
			$carat = " and p.polish_carat BETWEEN ".$post['cfrom']." and ".$post['cto'];
		}

		if( (isset($post['cfrom']) && $post['cfrom']!="") && (isset($post['cto']) && $post['cto']==""))
		{ 
			$carat = " and p.polish_carat BETWEEN ".$post['cfrom']." and 9999";
		}
		if( (isset($post['cfrom']) && $post['cfrom']=="") && (isset($post['cto']) && $post['cto']!=""))
		{ 
			$carat = " and p.polish_carat BETWEEN 0 and ".$post['cto'];
		}	
		// Memo
		if(isset($post['memo']))
		{ 
		
		
			$memo = "and ( ";
			$count = count($post['memo']) - 1;
			foreach($post['memo'] as $k=>$v)
			{	
				if($v == 'memo')
				{
					$memo .= "p.outward = 'memo' ";
					$memo .= "  ||  p.outward = 'consign' ";
				}
				if($v == 'gia')
					 $memo .= "p.lab = 'GIA' ";
				if($v == 'lab')
					 $memo .= "p.outward = 'lab' ";
				if($v == 'nong')
					 $memo .= "p.lab = '' ";
			/*	if($v == 'single')
					 $memo .= "group_type = 'single' ";	 
				if($v == 'box')
					 $memo .= "group_type = 'box' ";	 
				if($v == 'parcel')
					 $memo .= "group_type = 'parcel' ";	 	 */
					 

				if($count != $k )
					$memo .= " || ";
			}
			$memo .= ")";
		}
		//type of diamond package
		if(isset($post['type']))
		{ 
			$type = " and (";
			$count = count($post['type']) - 1;
			foreach($post['type'] as $k=>$v)
			{
				if($count == $k )
					$type .= " p.group_type = '".$v."'"; 
				else
					$type .= " p.group_type = '".$v."' ||"; 
			}
			$type .= ") ";
		}
		$hold = "";
		if(isset($post['hold']))
		{ 
			$hold = " and ";			
			$hold .= " p.hold = 1"; 
			
		}
		$nonmemo = "";
		if(isset($post['nm']))
		{ 
			$nonmemo .= " and p.outward <> 'memo' ";
			
		}
		if(isset($post['shape']))
		{
			$shape = " and (";
			$count = count($post['shape']) - 1;
			foreach($post['shape'] as $k=>$v)
			{
				if($count == $k )
					$shape .= " pv.shape Like '%".$v."%'"; 
				else
					$shape .= " pv.shape Like '%".$v."%' ||"; 
			}
			$shape .= ") ";
		}
		if(isset($post['package']))
		{
			$package = " and (";
			$count = count($post['package']) - 1;
			foreach($post['package'] as $k=>$v)
			{
				if($count == $k )
					$package .= " pv.package = '".$v."'"; 
				else
					$package .= " pv.package = '".$v."' ||"; 
			}
			$package .= ") ";
		}
		
		if(isset($post['location']))
		{
			$location = " and (";
			$count = count($post['location']) - 1;
			foreach($post['location'] as $k=>$v)
			{
				if($count == $k )
					$location .= " p.location = '".$v."'"; 
				else
					$location .= " p.location = '".$v."' ||"; 
			}
			$location .= ") ";
		}
		if(isset($post['color']) && $form_type=="white")
		{
			$color = " and (";
			$count = count($post['color']) - 1;
			foreach($post['color'] as $k=>$v)
			{
				if($count == $k )
					$color .= " pv.color LIKE '".$v."' || pv.color LIKE '".$v."-%'"; 
				else
					$color .= " pv.color LIKE '".$v."' || pv.color LIKE '".$v."-%' || "; 
			}
			$color .= ") ";
		}
		if(isset($post['color']) && $form_type=="fancy")
		{
			$color = " and (";
			$count = count($post['color']) - 1;
			foreach($post['color'] as $k=>$v)
			{
				if($count == $k )
					$color .= " pv.color = '".$v."'"; 
				else
					$color .= " pv.color = '".$v."' ||"; 
			}
			$color .= ") ";
		}
		
		if(isset($post['intensity']))
		{
			$intensity = " and (";
			$count = count($post['intensity']) - 1;
			foreach($post['intensity'] as $k=>$v)
			{
				if($count == $k )
					$intensity .= " pv.intensity = '".$v."'"; 
				else
					$intensity .= " pv.intensity = '".$v."' ||"; 
			}
			$intensity .= ") ";
		}
		
		
		if(isset($post['f_intensity']))
		{
			$f_intensity = " and (";
			$count = count($post['f_intensity']) - 1;
			foreach($post['f_intensity'] as $k=>$v)
			{
				if($count == $k )
					$f_intensity .= " pv.f_intensity = '".$v."'"; 
				else
					$f_intensity .= " pv.f_intensity = '".$v."' ||"; 
			}
			$f_intensity .= ") ";
		}
		if(isset($post['clarity']))
		{
			$clarity = " and (";
			$count = count($post['clarity']) - 1;
			foreach($post['clarity'] as $k=>$v)
			{
				if($count == $k )
					$clarity .= " pv.clarity = '".$v."'"; 
				else
					$clarity .= " pv.clarity = '".$v."' ||"; 
			}
			$clarity .= ") ";
		}
		if(isset($post['overtone']))
		{
			$overtone = " and (";
			$count = count($post['overtone']) - 1;
			foreach($post['overtone'] as $k=>$v)
			{
				if($count == $k )
					$overtone .= " pv.overtone = '".$v."'"; 
				else
					$overtone .= " pv.overtone = '".$v."' ||"; 
			}
			$overtone .= ") ";
		}
		
		if(isset($post['cut']))
		{
			$cut = " and (";
			$count = count($post['cut']) - 1;
			foreach($post['cut'] as $k=>$v)
			{
				if($count == $k )
					$cut .= " pv.cut = '".$v."'"; 
				else
					$cut .= " pv.cut = '".$v."' ||"; 
			}
			$cut .= ") ";
		}
		
		if(isset($post['polish']))
		{
			$polish = " and (";
			$count = count($post['polish']) - 1;
			foreach($post['polish'] as $k=>$v)
			{
				if($count == $k )
					$polish .= " pv.polish = '".$v."'"; 
				else
					$polish .= " pv.polish = '".$v."' ||"; 
			}
			$polish .= ") ";
		}
		if(isset($post['symmentry']))
		{
			$symmentry = " and (";
			$count = count($post['symmentry']) - 1;
			foreach($post['symmentry'] as $k=>$v)
			{
				if($count == $k )
					$symmentry .= " pv.symmentry = '".$v."'"; 
				else
					$symmentry .= " pv.symmentry = '".$v."' ||"; 
			}
			$symmentry .= ") ";
		}
		
		$sort = ' p.lab desc,p.sku ';
		if(isset($post['sort']) && $post['sort'] !='' && $post['sort'] != 'rapnet' && $post['sort'] != 'rapnet' && $post['sort'] != 'discount')
			$sort = ' '.$post['sort'].' ';
		
		
		$ascType = '';
		if(isset($post['sorttype']) && $post['sorttype'] !='' )
			$ascType = ' '.$post['sorttype'].' ';
		
		$sort = $sort.' '.$ascType;
		
		
		$diamond = '';
		if(isset($post['diamond']))
		{
			if($post['diamond'] == 'F')
			{
				$diamond =" and pv.intensity !=''";
			}
			else
			{
				$diamond =" and pv.intensity ='' ";
			}			
		}
		
		/* if($s == "gia")
			$rs = mysql_query("SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and outward='' and box_id='' and parcel_id='' and lab='GIA'  ORDER BY lab desc");
		else if($s == "lab")
			$rs = mysql_query("SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and outward='lab' and box_id='' and parcel_id=''  ORDER BY lab desc");
		else if($s == "memo")
			$rs = mysql_query("SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and outward='memo' and box_id='' and parcel_id=''  ORDER BY lab desc");
		else if($s == "ng")
			$rs = mysql_query("SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and outward='' and lab='' and box_id='' and parcel_id=''  ORDER BY lab desc");
		else */
	
		//echo "SELECT * FROM ".$this->table_product ." WHERE company=".$_SESSION['companyId']." and (outward=''|| outward='memo' || outward='lab') and box_id='' and parcel_id='' ".$sku.$carat.$type."  ORDER BY lab desc";
		if(isset($post['type']))
		{ 
		
			
			if(isset($post['memo']))			
				$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and visibility=1 and polish_carat <>0 and p.box_id='' and p.parcel_id='' and p.outward <> 'sale' and p.outward <> 'export' ".$hold.$sku.$carat.$location.$memo.$type.$shape.$package.$color.$intensity.$f_intensity.$overtone.$clarity.$symmentry.$cut.$polish.$diamond."  ORDER BY ".$sort);
			else
				$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and visibility=1 and polish_carat <>0 and  p.outward <> 'sale' and p.outward <> 'export' and (p.outward='') and p.box_id='' and p.parcel_id='' ".$nonmemo.$hold.$sku.$carat.$memo.$type.$shape.$color.$shape.$package.$location.$color.$intensity.$f_intensity.$overtone.$clarity.$symmentry.$cut.$polish.$diamond."  ORDER BY ".$sort);
				
			
			$data = array();
			while($row = mysql_fetch_assoc($rs))
			{
				if(($row['polish_carat'] == "0" && $row['group_type']=="box" && $row['box_products'] =="" ) || ($row['group_type']=="parcel" && $row['parcel_products'] =="" && $row['polish_carat'] == "0" ))
						continue;
						
				$data[] =  $row;
			}
		}
		else
		{
			//single
		
			if(isset($post['memo']))			
				$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and visibility=1 and polish_carat <>0 and p.box_id='' and p.parcel_id='' and p.outward <> 'sale' and group_type='single' and p.outward <> 'export' ".$hold.$sku.$carat.$location.$memo.$shape.$package.$color.$intensity.$f_intensity.$overtone.$clarity.$symmentry.$cut.$polish.$diamond."  ORDER BY ".$sort);
			else
				$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and visibility=1 and polish_carat <>0 and  p.outward <> 'sale' and p.outward <> 'export' and p.box_id='' and p.parcel_id='' and group_type='single' ".$nonmemo.$hold.$sku.$carat.$memo.$shape.$color.$shape.$package.$location.$color.$intensity.$f_intensity.$overtone.$clarity.$symmentry.$cut.$polish.$diamond."  ORDER BY ".$sort);
				
			$data = array();
			while($row = mysql_fetch_assoc($rs))
			{
				if(($row['polish_carat'] == "0" && $row['group_type']=="box" && $row['box_products'] =="" ) || ($row['group_type']=="parcel" && $row['parcel_products'] =="" && $row['polish_carat'] == "0" ))
						continue;
						
				$data[] =  $row;
			}
			
			//box
			
			if(isset($post['memo']))			
				$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and visibility=1 and polish_carat <>0 and p.box_id='' and p.parcel_id='' and p.outward <> 'sale' and group_type='box' and p.outward <> 'export' ".$hold.$sku.$carat.$location.$memo.$shape.$package.$color.$intensity.$f_intensity.$overtone.$clarity.$symmentry.$cut.$polish.$diamond."  ORDER BY ".$sort);
			else
				$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and visibility=1 and polish_carat <>0 and  p.outward <> 'sale' and p.outward <> 'export' and p.box_id='' and p.parcel_id='' and group_type='box' ".$nonmemo.$hold.$sku.$carat.$memo.$shape.$color.$shape.$package.$location.$color.$intensity.$f_intensity.$overtone.$clarity.$symmentry.$cut.$polish.$diamond."  ORDER BY ".$sort);
				
			
			while($row = mysql_fetch_assoc($rs))
			{
				if(($row['polish_carat'] == "0" && $row['group_type']=="box" && $row['box_products'] =="" ) || ($row['group_type']=="parcel" && $row['parcel_products'] =="" && $row['polish_carat'] == "0" ))
						continue;
						
				$data[] =  $row;
			}

			//parcel
			if(isset($post['memo']))			
				$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and visibility=1 and polish_carat <>0 and p.box_id='' and p.parcel_id='' and p.outward <> 'sale' and group_type='parcel' and p.outward <> 'export' ".$hold.$sku.$carat.$location.$memo.$shape.$package.$color.$intensity.$f_intensity.$overtone.$clarity.$symmentry.$cut.$polish.$diamond."  ORDER BY ".$sort);
			else
				$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and visibility=1 and polish_carat <>0 and  p.outward <> 'sale' and p.outward <> 'export' and p.box_id='' and p.parcel_id='' and group_type='parcel' ".$nonmemo.$hold.$sku.$carat.$memo.$shape.$color.$shape.$package.$location.$color.$intensity.$f_intensity.$overtone.$clarity.$symmentry.$cut.$polish.$diamond."  ORDER BY ".$sort);
				
			
			while($row = mysql_fetch_assoc($rs))
			{
				if(($row['polish_carat'] == "0" && $row['group_type']=="box" && $row['box_products'] =="" ) || ($row['group_type']=="parcel" && $row['parcel_products'] =="" && $row['polish_carat'] == "0" ))
						continue;
						
				$data[] =  $row;
			}
				
		}
		//print_r($data);
		return  $data;			
    }
	
	
	
	public function getMyExportInventory($post)
    {
		$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE p.company=".$_SESSION['companyId']." and p.visibility=1 and p.id in(".$post.")");
		
		$data = array();
		while($row = mysql_fetch_assoc($rs))
		{
			$data[] =  $row;
		}
		return  $data;			
    }

	public function getDetail($id,$t='')
    {
		$data = array();		
		if($id == "" )
			return $data;
		$rs = mysql_query("SELECT * FROM ".$this->table_product ." p LEFT JOIN ".$this->table_product_value ." pv ON p.id = pv.product_id WHERE company=".$_SESSION['companyId']." and id=".$id);
		while($row = mysql_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
	public function updateTextValue($post)
	{
		$pid = $post['id'];
		$tval = $post['tval'];
		
		//$data = $this->getDetail($pid);
		if($post['att'] == "package" )
		{
			$sql = "UPDATE ".$this->table_product_value." SET package='$tval' WHERE product_id=".$pid;		
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
				return $rs;
			}
		}
		else if($post['att'] == "price")
		{
			$data = $this->getDetail($pid);
			$amount = $data['polish_carat'] * $tval;
			
			$sql = "UPDATE ".$this->table_product." SET rapnet_upload = 0,rap_price='$tval',rap_amount='$amount' WHERE id=".$pid;		
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
				return $rs;
			}
		}
		else if($post['att'] == "remark")
		{
			$sql = "UPDATE ".$this->table_product." SET remark='$tval' WHERE id=".$pid;		
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
				return $rs;
			}
		}
		return $rs;
	}
	function  saveStock($post)
	{
		/* echo "<pre>";
		print_r($post);
		exit;  */ 
		if($post['stockdate'] !='' && ($post['olddate'] =='' || $post['olddate'] =='0') )
		{
			$stock['stockdate'] = $post['stockdate'];
			$stock['data'] = json_encode($post['action']);
			
			$data = $this->helper->getInsertString($stock);	
			$sql = "INSERT INTO dai_stockmanage (". $data[0].") VALUES (".$data[1].")";
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
				return $rs;
			}
		}
		else
		{
			echo "<pre>";
			$stockdata = $post['action'];
			//print_r($stockdata);
			$rs = mysql_query("SELECT * FROM dai_stockmanage WHERE id=".$post['olddate'] );
			$data = array();
			while($row = mysql_fetch_assoc($rs))
			{
				$data =  $row;
			}
			$dataAction = (array)json_decode($data['data'],true);
			
			$temp = $dataAction;
			$finalData = array();
			foreach($dataAction as $k=>$da)
			{
				
				if(isset($stockdata[$k]))
				{
					
				
					$finalData[$k] = $stockdata[$k];						
					unset($stockdata[$k]);
					unset($temp[$k]);
				}					
			}
			
			foreach($temp as $k=>$da)
			{
				
				$finalData[$k] = $da;											
			}
			foreach($stockdata as $k=>$da)
			{
				$finalData[$k] = $da;											
			}
			
			$sfd = json_encode($finalData);			
			$sql = "UPDATE dai_stockmanage SET data='".$sfd."' WHERE id=".$post['olddate'];
			$rs = mysql_query($sql);
			if(!$rs)
			{
				$rs = mysql_error();
				return $rs;
			} 
		}
	}
	
	function setMailData($post)
	{
		$post['date'] = date("Y-m-d H:i:s");
		$post['user'] = $_SESSION['userid'];
		$data = $this->helper->getInsertString($post);	
		$sql = "INSERT INTO dai_mail (". $data[0].") VALUES (".$data[1].")";
		$rs = mysql_query($sql);
	}
	
	
	function  updateProduct($post)
	{
		/* echo "<pre>";
		print_r($post);
		exit;  */
		$record = $post['record'];
		$post['site_upload'] = 0;
		$post['rapnet_upload'] = 0;
		
		
		if(isset($post['is_uploadsite']))
			$post['is_uploadsite'] = 1;		
		else
			$post['is_uploadsite'] = 0;
		
		if(isset($post['is_uploadrapnet']))
			$post['is_uploadrapnet'] = 1;		
		else
			$post['is_uploadrapnet'] = 0;
		
		if(isset($post['hide']))
			$post['hide'] = 1;		
		else
			$post['hide'] = 0;
			
		unset( $post['record']);
		
		$data = $this->helper->getUpdateString($post);	
		$sql = "UPDATE dai_product SET ".$data." WHERE id=".$post['id'];		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			return mysql_error();				
		}
		
		$data = $this->helper->getUpdateString($record);	
		$sql = "UPDATE dai_product_value SET ".$data." WHERE product_id=".$post['id'];		
		$rs = mysql_query($sql);
		if(!$rs)
		{
			return mysql_error();				
		}
		return $rs;
	}
	
}

