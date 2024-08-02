<?php
//include('../../../../../variable.php');
include_once('../../../database.php');
include_once('../../Helper.php');
include_once('../../jHelper.php');
class inventoryModel
{
    public $table;
	public $table_product;
	public $table_product_value;
	public $helper;
	private $conn;
	 function __construct($db)
    {
        try {
			$this->conn = $db;
            $this->table  = "jew_inward";
			$this->table_product  = "jew_product";			
			$this->helper  = new Helper($db);
			$this->jhelper  = new jHelper($db);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	//get all Main Stone inventory Data
    public function getMyInventory($post,$form_type)
    {
		$location = $sku = $carat = $type = $package = $shape = $color =  $intensity = $overtone = $clarity = $f_intensity = $memo = $symmentry = $cut = $polish = $stype =  "";
		// Packat - SKU - Report Search
		if(isset($post['sku']) and $post['sku']!="")
		{ 
			$i=0;
			$post['sku'] = str_replace(' ', '', $post['sku']);
			$tem= explode(',', $post['sku']);
		
			if(count($tem) != 1)
			{
				$skua = implode("','",$tem);
				$sku .= " and ( p.sku IN ('".$skua."') or p.igi_code IN ('".$skua."'))";
				//$sku .= " and ( p.sku IN ('".$skua."') or pv.report_no IN ('".$skua."') )";
				//$sku .= " and ( p.sku NOT IN ('".$skua."')  )";
			}
			else
			{
				$sku = " and ( p.sku LIKE '%".$post['sku']."%' or p.igi_code LIKE '%".$post['sku']."%')";
			}
		}		
		//carat from and to search
		if( (isset($post['cfrom']) && $post['cfrom']!="") && (isset($post['cto']) && $post['cto']!=""))
		{ 
			$carat = " and p.carat BETWEEN ".$post['cfrom']." and ".$post['cto'];
		}

		if( (isset($post['cfrom']) && $post['cfrom']!="") && (isset($post['cto']) && $post['cto']==""))
		{ 
			$carat = " and p.carat BETWEEN ".$post['cfrom']." and 9999";
		}
		if( (isset($post['cfrom']) && $post['cfrom']=="") && (isset($post['cto']) && $post['cto']!=""))
		{ 
			$carat = " and p.carat BETWEEN 0 and ".$post['cto'];
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
		
		if(isset($post['stype']))
		{ 
			$stype = " and (";
			$count = count($post['stype']) - 1;
			foreach($post['stype'] as $k=>$v)
			{
				if($count == $k )
					$stype .= " p.lab = '".$v."'"; 
				else
					$stype .= " p.lab = '".$v."' ||"; 
			}
			$stype .= " ) ";
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
					$color .= " p.color LIKE '".$v."' || p.color LIKE '".$v."-%'"; 
				else
					$color .= " p.color LIKE '".$v."' || p.color LIKE '".$v."-%' || "; 
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
					$color .= " p.color = '".$v."'"; 
				else
					$color .= " p.color = '".$v."' ||"; 
			}
			$color .= ") ";
		}
		
	
		$sort = ' p.sku ';
		if(isset($post['sort']) && $post['sort'] !='' && $post['sort'] != 'rapnet' && $post['sort'] != 'rapnet' && $post['sort'] != 'discount')
			$sort = ' '.$post['sort'].' ';
		
		
		$ascType = '';
		if(isset($post['sorttype']) && $post['sorttype'] !='' )
			$ascType = ' '.$post['sorttype'].' ';
		
		$sort = $sort.' '.$ascType;
		
		//echo "SELECT * FROM ".$this->table_product ." p   WHERE p.company=".$_SESSION['companyId']." and visibility=1 and carat <>0 and  p.outward <> 'collet' and p.outward <> 'jewelry' and (p.outward='') and  p.parcel_id='' ".$sku.$carat.$color.$location.$color.$clarity."  ORDER BY ".$sort	
		
		//echo "SELECT * FROM ".$this->table_product ." p   WHERE p.company=".$_SESSION['companyId']." and visibility=1 and carat <>0 and p.outward='' ".$sku.$carat.$color.$location.$color.$clarity.$type."  ORDER BY ".$sort;
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." p WHERE p.company=".$_SESSION['companyId']." and visibility=1 and carat <>0 and (p.outward='' or p.outward='lab' or p.outward='consign') and p.is_collet = 0 ".$sku.$carat.$color.$location.$memo.$color.$clarity.$type.$stype."  ORDER BY ".$sort);
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			if(($row['carat'] == "0" && $row['group_type']=="box" && $row['box_products'] =="" ) || ($row['group_type']=="parcel" && $row['parcel_products'] =="" && $row['carat'] == "0" ))
					continue;
					
			$data[] = $row;
		}
		
		
		//print_r($data);
		return  $data;			
    }
	//get all loose stock inventory Data
    public function getMyLooseInventory($post,$form_type)
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
				$sku .= " and ( p.sku IN ('".$skua."') )";
				//$sku .= " and ( p.sku IN ('".$skua."') or pv.report_no IN ('".$skua."') )";
				//$sku .= " and ( p.sku NOT IN ('".$skua."')  )";
			}
			else
			{
				$sku = " and ( p.sku LIKE '%".$post['sku']."%') ";
			}
		}		
		//carat from and to search
		if( (isset($post['cfrom']) && $post['cfrom']!="") && (isset($post['cto']) && $post['cto']!=""))
		{ 
			$carat = " and p.carat BETWEEN ".$post['cfrom']." and ".$post['cto'];
		}

		if( (isset($post['cfrom']) && $post['cfrom']!="") && (isset($post['cto']) && $post['cto']==""))
		{ 
			$carat = " and p.carat BETWEEN ".$post['cfrom']." and 9999";
		}
		if( (isset($post['cfrom']) && $post['cfrom']=="") && (isset($post['cto']) && $post['cto']!=""))
		{ 
			$carat = " and p.carat BETWEEN 0 and ".$post['cto'];
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
					$color .= " p.color LIKE '".$v."' || p.color LIKE '".$v."-%'"; 
				else
					$color .= " p.color LIKE '".$v."' || p.color LIKE '".$v."-%' || "; 
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
					$color .= " p.color = '".$v."'"; 
				else
					$color .= " p.color = '".$v."' ||"; 
			}
			$color .= ") ";
		}
		
	
		$sort = ' p.sku ';
		if(isset($post['sort']) && $post['sort'] !='' && $post['sort'] != 'rapnet' && $post['sort'] != 'rapnet' && $post['sort'] != 'discount')
			$sort = ' '.$post['sort'].' ';
		
		
		$ascType = '';
		if(isset($post['sorttype']) && $post['sorttype'] !='' )
			$ascType = ' '.$post['sorttype'].' ';
		
		$sort = $sort.' '.$ascType;
		
		//echo "SELECT * FROM ".$this->table_product ." p   WHERE p.company=".$_SESSION['companyId']." and visibility=1 and carat <>0 and  p.outward <> 'collet' and p.outward <> 'jewelry' and (p.outward='') and  p.parcel_id='' ".$sku.$carat.$color.$location.$color.$clarity."  ORDER BY ".$sort	
		
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p WHERE p.company=".$_SESSION['companyId']." and visibility=1 and carat <>0 and (p.outward='' or p.outward='consign') ".$sku.$carat.$color.$location.$color.$clarity." ORDER BY ".$sort);
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			if(($row['carat'] == "0" && $row['group_type']=="box" && $row['box_products'] =="" ) || ($row['group_type']=="parcel" && $row['carat'] == "0" ))
					continue;
					
			$data[] =  $row;
		}
		
		
		//print_r($data);
		return  $data;			
    }
	
	
	
	public function getMyExportInventory($post,$t='main')
    {
		
		if($t == 'main')
		{
			$sql = "SELECT * FROM ".$this->table_product ." p WHERE p.company=".$_SESSION['companyId']." and p.id in(".$post.")";
		}
		elseif($t == 'collet')
		{
			$sql = "SELECT * FROM ".$this->table_product ." p LEFT JOIN jew_collet c ON p.id = c.product_id WHERE p.company=".$_SESSION['companyId']." and c.type='collet_receive' and c.deleted=0 and p.visibility=1 and p.carat <>0 and p.outward='collet'  and p.is_collet = 1 and p.id in(".$post.")";

		}
		else
		{
			$sql = "SELECT * FROM jew_loose_product p  WHERE p.company=".$_SESSION['companyId']." and p.id in(".$post.")";
		}
		$rs = mysqli_query($this->conn,$sql);
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[] =  $row;
		}
		return  $data;			
    }

	 public function getMyColletInventory($post)
    {
		$sku = $stype =  "";
		if(isset($post['sku']) and $post['sku']!="")
		{ 
			$i=0;
			$post['sku'] = str_replace(' ', '', $post['sku']);
			$tem= explode(',', $post['sku']);
		
			if(count($tem) != 1)
			{
				$skua = implode("','",$tem);
				$sku .= " and ( p.sku IN ('".$skua."') or p.igi_code IN ('".$skua."') )";
				//$sku .= " and ( p.sku IN ('".$skua."') or pv.report_no IN ('".$skua."') )";
				//$sku .= " and ( p.sku NOT IN ('".$skua."')  )";
			}
			else
			{
				$sku = " and ( p.sku LIKE '%".$post['sku']."%' or p.igi_code LIKE '%".$post['sku']."%' ) ";
			}
		}
		if(isset($post['stype']))
		{ 
			$stype = " and (";
			$count = count($post['stype']) - 1;
			foreach($post['stype'] as $k=>$v)
			{
				if($count == $k )
					$stype .= " p.lab = '".$v."'"; 
				else
					$stype .= " p.lab = '".$v."' ||"; 
			}
			$stype .= " ) ";
		}
		$rs = mysqli_query($this->conn,"SELECT *,p.id as pid FROM ".$this->table_product ." p LEFT JOIN jew_collet c ON p.id = c.product_id WHERE p.company=".$_SESSION['companyId']." and c.type='collet_receive' and c.deleted=0 and p.visibility=1 and p.carat <>0 and p.outward='collet'  and p.is_collet = 1 ".$sku.$stype." ORDER BY c.sku ");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			//$row['sku'] = $row['msku'];
			$row['id'] = $row['pid'];
			$data[] =  $row;
		}
		return  $data;			
    } 
	public function getDetail($id, $t='main')
    {
		$data = array();		
		if($id == "" )
			return $data;
		
		if($t == 'main')
		{
			$rs = mysqli_query($this->conn,"SELECT *,p.sku as msku FROM ".$this->table_product ." p  LEFT JOIN jew_collet c ON p.id = c.product_id WHERE p.company=".$_SESSION['companyId']." and p.id=".$id);
		
		}
		else
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p   WHERE company=".$_SESSION['companyId']." and id=".$id);
		}
		while($row = mysqli_fetch_assoc($rs))
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
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}
		else
		{
			$sql = "UPDATE ".$this->table_product." SET remark='$tval' WHERE id=".$pid;		
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
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
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
				return $rs;
			}
		}
		else
		{
			//echo "<pre>";
			$stockdata = $post['action'];
			//print_r($stockdata);
			$rs = mysqli_query($this->conn,"SELECT * FROM dai_stockmanage WHERE id=".$post['olddate'] );
			$data = array();
			while($row = mysqli_fetch_assoc($rs))
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
			$rs = mysqli_query($this->conn,$sql);
			if(!$rs)
			{
				$rs = mysqli_error();
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
		$rs = mysqli_query($this->conn,$sql);
	}
	
	
	function  updateProduct($post)
	{
		$record = array();
		if(isset($post['record']))
		{
			$record = $post['record'];
			unset($post['record']); 
		}
		$post['amount'] = $post['carat'] * $post['price'];
		$data = $this->helper->getUpdateString($post);	
		$sql = "UPDATE jew_product SET ".$data." WHERE id=".$post['id'];		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();				
		}
		if(!empty($record))
		{
			foreach($record as $r)
			{
				$data = $this->helper->getUpdateString($r);	
				$sql = "UPDATE jew_product_detail SET ".$data." WHERE id=".$r['id'];	
				$rs = mysqli_query($this->conn,$sql);
				if(!$rs)
				{
					return mysqli_error();				
				}
			}
		}	
				$history =array();
				$history['product_id'] = $post['id'];
				$history['action'] = 'stone_update';	
				$history['date'] = 	date("Y-m-d H:i:s");
				$history['description'] = "Stone updated from product";
				$history['pcs'] = $post['pcs'];
				$history['carat'] = $post['carat'];
				$history['amount'] =$post['amount'];
				$history['price'] = $post['price'];
				$history['sku'] = $post['sku'];
				$history['for_history'] = 'main';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
		return $rs;
	}
	
	function updateProductLoose($post)
	{
		$post['amount'] = $post['carat'] * $post['price'];
		$data = $this->helper->getUpdateString($post);	
		$sql = "UPDATE jew_loose_product SET ".$data." WHERE id=".$post['id'];		
		$rs = mysqli_query($this->conn,$sql);
		if(!$rs)
		{
			return mysqli_error();				
		}
				$history =array();
				$history['product_id'] = $post['id'];
				$history['action'] = 'stone_update';
				$history['date'] = 	date("Y-m-d H:i:s");
				$history['description'] = "Stone updated from Product";
				$history['pcs'] = $post['pcs'];
				$history['carat'] = $post['carat'];
				$history['amount'] =$post['amount'];
				$history['price'] = $post['price'];
				$history['sku'] = $post['sku'];
				$history['for_history'] = 'side';
				$rs = $this->jhelper->addHistory($history);
				if(!is_numeric($rs) && $rs!=1)
				{
					return $rs;	
				}	
		return $rs;
	}
	//get all ledger Data
    /* public function getMyColletInventory($post,$form_type)
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
				$sku .= " and ( p.sku IN ('".$skua."') or p.igi_code IN ('".$skua."') )";
				//$sku .= " and ( p.sku IN ('".$skua."') or pv.report_no IN ('".$skua."') )";
				//$sku .= " and ( p.sku NOT IN ('".$skua."')  )";
			}
			else
			{
				$sku = " and ( p.sku LIKE '%".$post['sku']."%' or p.igi_code LIKE '%".$post['sku']."%' ) ";
			}
		}		
		//carat from and to search
		if( (isset($post['cfrom']) && $post['cfrom']!="") && (isset($post['cto']) && $post['cto']!=""))
		{ 
			$carat = " and p.carat BETWEEN ".$post['cfrom']." and ".$post['cto'];
		}

		if( (isset($post['cfrom']) && $post['cfrom']!="") && (isset($post['cto']) && $post['cto']==""))
		{ 
			$carat = " and p.carat BETWEEN ".$post['cfrom']." and 9999";
		}
		if( (isset($post['cfrom']) && $post['cfrom']=="") && (isset($post['cto']) && $post['cto']!=""))
		{ 
			$carat = " and p.carat BETWEEN 0 and ".$post['cto'];
		}	
		// Memo
		
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
					$color .= " p.color LIKE '".$v."' || p.color LIKE '".$v."-%'"; 
				else
					$color .= " p.color LIKE '".$v."' || p.color LIKE '".$v."-%' || "; 
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
					$color .= " p.color = '".$v."'"; 
				else
					$color .= " p.color = '".$v."' ||"; 
			}
			$color .= ") ";
		}
		
	
		$sort = ' p.sku ';
		if(isset($post['sort']) && $post['sort'] !='' && $post['sort'] != 'rapnet' && $post['sort'] != 'rapnet' && $post['sort'] != 'discount')
			$sort = ' '.$post['sort'].' ';
		
		
		$ascType = '';
		if(isset($post['sorttype']) && $post['sorttype'] !='' )
			$ascType = ' '.$post['sorttype'].' ';
		
		$sort = $sort.' '.$ascType;
		
		//echo "SELECT * FROM ".$this->table_product ." p   WHERE p.company=".$_SESSION['companyId']." and visibility=1 and carat <>0 and p.outward='collet' ".$sku.$carat.$color.$location.$color.$clarity."  ORDER BY ".$sort;	
		$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." p   WHERE p.company=".$_SESSION['companyId']." and visibility=1 and carat <>0 and p.outward='collet' and p.is_collet = 1  ".$sku.$carat.$color.$location.$color.$clarity.$type."  ORDER BY ".$sort);
			
		
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			if(($row['carat'] == "0" && $row['group_type']=="box" && $row['box_products'] =="" ) || ($row['group_type']=="parcel" && $row['parcel_products'] =="" && $row['carat'] == "0" ))
					continue;
					
			$data[] =  $row;
		}
		
		
		//print_r($data);
		return  $data;			
    } */
	public function getDataBySku($sku)
    {
		$data = array();
		$rs = mysqli_query($this->conn,"SELECT * FROM jew_product p WHERE p.sku='".$sku."' and outward=''" );
		while($row = mysqli_fetch_assoc($rs))
		{
			$data = $row;
			break;
		}
		
		return  $data;			
    }
	
	public function getSaleDetail($id, $t='main')
    {
		$data = array();		
		if($id == "" )
			return $data;
		
		if($t == 'main')
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM ".$this->table_product ." p  WHERE p.company=".$_SESSION['companyId']." and p.id=".$id);
		
		}
		else
		{
			$rs = mysqli_query($this->conn,"SELECT * FROM jew_loose_product p   WHERE company=".$_SESSION['companyId']." and id=".$id);
		}
		while($row = mysqli_fetch_assoc($rs))
		{
			$data =  $row;
		}				
		return  $data;			
    }
	public function getAllMainFirm()
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM main_firm WHERE current=0");
		$data = array();
		while($row = mysqli_fetch_assoc($rs))
		{
			$data[$row['name']] =  $row['fname'];
		}		
		return  $data;			
    }
	public function getMainFirm()
    {
		$rs = mysqli_query($this->conn,"SELECT * FROM main_firm WHERE current=1");	
		$row = mysqli_fetch_assoc($rs);
		return  $row['name'];			
    }
	
	
}

