<?php
class Inventory_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	public function getBranchProductBalance($customer_id,$product_id){
		$balance=0;
		$resp=$this->db->select('branchBalance')->where(array('productId'=>$product_id,'CustomerId'=>$customer_id))->order_by('id','desc')->get('tbl_inventory')->row();
		if(!empty($resp)){ $balance=$resp->branchBalance;}
		return $balance;
	}

	public function getOverallProductBalance($product_id){
		$balance=0;
		$resp=$this->db->select('overallBalance')->where(array('productId'=>$product_id))->order_by('id','desc')->get('tbl_inventory')->row();
		if(!empty($resp)){ $balance=$resp->overallBalance;}
		return $balance;
	}

	public function generateTag($id){
		$time=date('Y-m-d h:i:s');
		return strtoupper(md5($time)).$id;
	}

	public function insertInventory($data){
		return $this->db->insert('tbl_inventory',$data);
	}

	public function getInventoryPendingQty($customer,$product){
		return $this->db->where(array('productId'=>$product,'CustomerId'=>$customer,'PendingQty!='=>0))->order_by('id','ASC')->get('tbl_inventory')->result();
	}

	public function getInvPendingQtyId($id){
		$pendingQty=0;
		$resp=$this->db->where(array('id'=>$id))->order_by('id','ASC')->get('tbl_inventory')->row();
		if(!empty($resp)){$pendingQty=$resp->PendingQty;}
		return $pendingQty;
	}

	public function getInventoryDeductionDetails($customer_id,$product_id,$quantity){
    	$inv_ids=$this->getInvIdsToDeduct($customer_id,$product_id,$quantity);
    	$resp=$this->reduceExactInventory($quantity,$inv_ids);
    	return $resp;
    }

    public function getInvIdsToDeduct($customer,$product,$quantity){
    	$inv_ids=array();$qtys=0;
    	$stock=$this->getInventoryPendingQty($customer,$product);
    	foreach($stock as $stk){
    		$qtys+=$stk->PendingQty;
    		array_push($inv_ids,$stk->id);
    		if($qtys > $quantity){
    			break;
    		}
    	}
    	return $inv_ids;
    }

    public function reduceExactInventory($quantity,$inv_ids){
    	$qty=$pending=$temp_qty=0;$res=array();
    	$len=count($inv_ids);
    	for($i=0;$i<$len;$i++){
    		$ress=$this->getInvPendingQtyId($inv_ids[$i]);
    		if(!empty($ress)){
    			$qty=$ress;
    		}

			if($qty > $quantity){
				$pending=$qty-$quantity;
				$temp_qty=$quantity;
				$quantity=0;
    		}else{
    			$pending=0;
				$temp_qty=$qty;
				$quantity=$quantity-$qty;
    		}
    		$res['PendingQty'][]=$pending;
    		$res['UsedQty'][]=$temp_qty;
    		$res['UpdateId'][]=$inv_ids[$i];
    	}
    	return $res;
    }

    public function getInvDetails($id){
    	return $this->db->where('id',$id)->get('tbl_inventory')->row();
    }

    public function updateInventory($id,$data){
    	return $this->db->where('id',$id)->set($data)->update('tbl_inventory');
    }
	
	public function getInventoryList($limit,$start){
    	$this->db->select('PRD.*,GRP.name as group_name,SC.materialSubcategoryName as subcatname,CAT.materialCategoryName as categoryName,UOM.uom')->from('tbl_inventory as INV');
        $this->db->join('tbl_products as PRD','PRD.id=INV.productId');
        $this->db->join('tbl_group as GRP','GRP.id=PRD.group');
        $this->db->join('tbl_material_sub_category as SC','SC.id=PRD.subcategory');
        $this->db->join('tbl_material_category as CAT','CAT.id=PRD.category');
        $this->db->join('tbl_uom as UOM','UOM.id=PRD.uom');
        $this->db->limit($limit,$start);
        $this->db->group_by('INV.productId');
        $this->db->order_by('INV.id','desc');
        return $this->db->get()->result();
    }
    

    public function getDistinctCustomerInv($productId){
    	$this->db->select('PRD.*,GRP.name as group_name,SC.materialSubcategoryName as subcatname,CAT.materialCategoryName as categoryName,UOM.uom,CUS.CustomerName,CUS.CustomerCode,CTYPE.CustomerType,CUS.id as customer_id')->from('tbl_inventory as INV');
    	$this->db->join('tbl_products as PRD','PRD.id=INV.productId');
        $this->db->join('tbl_group as GRP','GRP.id=PRD.group');
        $this->db->join('tbl_material_sub_category as SC','SC.id=PRD.subcategory');
        $this->db->join('tbl_material_category as CAT','CAT.id=PRD.category');
        $this->db->join('tbl_uom as UOM','UOM.id=PRD.uom');
        $this->db->join('tbl_customers as CUS','CUS.id=INV.CustomerId');
        $this->db->join('tbl_customer_type as CTYPE','CTYPE.id=CUS.CustomerType');
		return $this->db->where('INV.productId',$productId)->group_by('INV.CustomerId')->get()->result();
    }

    public function getInventoryTrackByCUSID($customer_id,$productId){
    	$this->db->select('INV.*,PRD.productName,PRD.Sku,GRP.name as group_name,SC.materialSubcategoryName as subcatname,CAT.materialCategoryName as categoryName,UOM.uom,CUS.CustomerName,CUS.CustomerCode,CTYPE.CustomerType,CUS.id as customer_id')->from('tbl_inventory as INV');
    	$this->db->join('tbl_products as PRD','PRD.id=INV.productId');
        $this->db->join('tbl_group as GRP','GRP.id=PRD.group');
        $this->db->join('tbl_material_sub_category as SC','SC.id=PRD.subcategory');
        $this->db->join('tbl_material_category as CAT','CAT.id=PRD.category');
        $this->db->join('tbl_uom as UOM','UOM.id=PRD.uom');
        $this->db->join('tbl_customers as CUS','CUS.id=INV.CustomerId');
        $this->db->join('tbl_customer_type as CTYPE','CTYPE.id=CUS.CustomerType');
		return $this->db->where('INV.CustomerId',$customer_id)->where('INV.productId',$productId)->get()->result();
    }
    public function checkStockWhileDelivery($productId,$quantity,$customerId){
    	$procudt_cnt=count($productId);
    	$resp=array();
    	for($i=0;$i<$procudt_cnt;$i++){
    		$avail_qty=$this->getBranchProductBalance($customerId,$productId[$i]);
    		if($avail_qty >= $quantity[$i]){
    			$resp['status'][]=true;
    			$resp['message'][]='Success';
    			$resp['error_code'][]='200';
    		}else{
    			$product_name=$this->common_model->getProductNameByID($productId[$i]);
				$resp['status'][]=false;
    			$resp['message'][]='<b>'.$product_name.'</b> has only <b>'.$avail_qty.'</b> quantity';
    			$resp['error_code'][]=400;
    		}
    	}
    	return $resp;
    }

	public function getBranchProductBalanceBetDate($customer_id,$product_id,$date){
		$balance=0;
		$resp=$this->db->select('branchBalance')->where('CreatedAt <=',date('Y-m-d h:i:s',strtotime($date.' 11:59:59')))->where(array('productId'=>$product_id,'CustomerId'=>$customer_id))->order_by('id','desc')->get('tbl_inventory')->row();
		if(!empty($resp)){ $balance=$resp->branchBalance;}
		return $balance;
	}

	public function getOverallProductBalanceBetDate($product_id,$date){
		$balance=0;
		$resp=$this->db->select('overallBalance')->where('CreatedAt <=',date('Y-m-d h:i:s',strtotime($date.'11:59:59')))->where(array('productId'=>$product_id))->order_by('id','desc')->get('tbl_inventory')->row();
		if(!empty($resp)){ $balance=$resp->overallBalance;}
		return $balance;
	}

	public function GetProductNetTotal($cus_id,$prd_id,$date){
		$qty=$this->getBranchProductBalanceBetDate($cus_id,$prd_id,$date);
		$dpprice=$this->getActivePriceOfProduct($prd_id);
		$price=$qty*$dpprice;
		return $price;
	}

	public function getActivePriceOfProduct($id){
		$PRPrice=0;
		$res=$this->db->select('PRL.DPPrice')->from('tbl_pricebook as PR')->join('tbl_pricebook_list as PRL','PRL.pricebookId=PR.id')->where('PR.status','1')->where('PRL.productId',$id)->get()->row();
		if(!empty($res)){ $PRPrice=$res->DPPrice;}
		return $PRPrice;
	}

	public function insertStockAdjustment($data){
		$this->db->insert('tbl_stock_adjustment',$data);
		return $this->db->insert_id();
	}

	public function insertStockAdjItems($data){
		return $this->db->insert('tbl_stock_adjustment_items',$data);
	}

	public function GetStockAdjustmentList($limit,$start){
        $this->db->select('OS.*,CST.customerType')->from('tbl_stock_adjustment as OS');
        $this->db->join('tbl_customer_type as CST','CST.id=OS.location');
        $this->db->limit($limit,$start);
        $this->db->order_by('OS.id','desc');
        return $this->db->get()->result();
    }

	public function getStockAdjustment($id){
    	return $this->db->where('id',$id)->get('tbl_stock_adjustment')->row();
    }

    public function getStockAdjustmentItems($id){
    	return $this->db->where('stockAdjustmentId',$id)->get('tbl_stock_adjustment_items')->result();
    }

	public function updateStockAdjustment($id,$data){
		return $this->db->where('id',$id)->set($data)->update('tbl_stock_adjustment');
	}

	public function deleteStockAdjItems($id){
		return $this->db->where('stockAdjustmentId',$id)->delete('tbl_stock_adjustment_items');
	}

	public function getStockAdjStatus($id){
    	$status="Draft";
    	$resp=$this->db->where('id',$id)->get('tbl_stock_adjustment')->row();
    	if(!empty($resp)){$status=$resp->status;}
    	return $status;
    } 
    public function getStockAdjCust($customer_id){
        return $this->db->where(array('customerId'=>$customer_id))->get('tbl_stock_adjustment')->row();
    }
     public function getStockAdjustmentReport($limit,$start,$from_date,$to_date,$stockist){
    	$this->db->select('SA.*,CUST.CustomerCode,PRD.productName,PRD.Sku')->from('tbl_stock_adjustment as SA');
    	//$this->db->join('tbl_stock_adjustment_items as SAI','SA.id=SAI.stockAdjustmentId');
    	$this->db->join('tbl_customers as CUST','SA.customerId=CUST.id');
    	$this->db->join('tbl_products as PRD','SA.customerId=PRD.id');
    	if(!empty($from_date)){
            $this->db->where('SA.addedDate >=',$from_date);
        }
        if(!empty($to_date)){
            $this->db->where('SA.addedDate <=',$to_date);
        }
        if(!empty($stockist)){
            $this->db->where('SA.customerId',$stockist);
        }
        $this->db->limit($limit,$start);
        //$this->db->order_by('SA.id','desc');
        $res=$this->db->get()->result();
        echo $this->db->last_query();
        return $res;
    	
    }
    public function stockAdjuExcelDownload($from_date,$to_date,$stockist){
        $this->db->select('SA.*,CUST.CustomerName,CUST.CustomerCode,PRD.productName,PRD.Sku')->from('tbl_stock_adjustment as SA');
       // $this->db->join('tbl_stock_adjustment_items as SAI','SA.id=SAI.stockAdjustmentId');
        $this->db->join('tbl_customers as CUST','SA.customerId=CUST.id');
    	$this->db->join('tbl_products as PRD','SA.customerId=PRD.id');
       
        if(!empty($from_date)){
            $this->db->where('SA.addedDate >=',$from_date);
        }
        if(!empty($to_date)){
            $this->db->where('SA.addedDate <=',$to_date);
        }
        if(!empty($stockist)){
            $this->db->where('SA.customerId',$stockist);
        }
        $this->db->order_by('SA.id','desc');
        $res=$this->db->get()->result();
        return $res;
    }

}
?>