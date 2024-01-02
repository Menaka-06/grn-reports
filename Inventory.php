<?php

 class Inventory extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('inventory_model');
         $this->load->model('goodsreceivednote_model');
         $this->load->model('openingstock_model');
    }
    public function stores(){
        $data['page_name']='Stores';
		$data['sub_page']='Inventory/Stores';
		$this->load->view('user_index',$data);
    }
    public function Addstores(){
        $data['page_name']='Add Stores';
		$data['sub_page']='Inventory/AddStores';
		$this->load->view('user_index',$data);
    }

    public function wareHouse(){
        $data['page_name']='Warehouse';
		$data['sub_page']='Inventory/Warehouse';
		$this->load->view('user_index',$data);
    }
    public function addwareHouse(){
        $data['page_name']='Add Ware House';
		$data['sub_page']='Inventory/AddwareHouse';
		$this->load->view('user_index',$data);
    }

    public function stock(){
        $data['page_name']='Stock';
		$data['sub_page']='Inventory/Stock';
		$this->load->view('user_index',$data);
    }
    public function addStock(){
        $data['page_name']='Add Stock';
		$data['sub_page']='Inventory/Addstock';
		$this->load->view('user_index',$data);
    }
   
    public function goodsRecievednote(){
        $data['page_name']='Goods Recieved Note';
		$data['sub_page']='Inventory/GoodsRecievednote';
        $config['base_url'] = base_url()."Inventory/goodsRecievednote"; 
        $config['total_rows'] = $this->common_model->getTotalRecords('tbl_goods_received_notes','');
        $config['per_page'] = PAGINATION_COUNT; 
        $config=$this->common_model->paginationStyle($config);
        $this->pagination->initialize($config); 
        $lmt=0;
        $lmt=$this->uri->segment(3);
        $data['lmt']=$lmt;
        $data['stockist']=$this->common_model->getActiveCustomer();
        $data['goods_receivednote'] = $this->goodsreceivednote_model->getGoodreceivednote($config['per_page'],$lmt);
		$this->load->view('user_index',$data);
    }

    public function goodsreceivednotereport(){
         $data['page_name']='Goods Recieved Note Report';
        $data['sub_page']='Inventory/goodsreceivednotereport';
        $config['base_url'] = base_url()."Inventory/goodsreceivednotereport"; 
        $data['grn_report']=$this->goodsreceivednote_model->goodsreceivednotereport();
     
        $this->load->view('user_index',$data);
    }
    public function searchGoodsreceivednote()
    {
        $this->common_model->CommentsLog();
        if(isset($_POST['search_goodsreceived_note'])){
            $lmt=$grn_number=$po_number=$vendor_name=$store_name=$grn_from_date=$grn_to_date=$grn_status="";
            $grn_number=$this->security->xss_clean($this->input->post('grn_number'));
             $po_number=$this->security->xss_clean($this->input->post('po_number'));
              $vendor_name=$this->security->xss_clean($this->input->post('vendor_name'));
               $store_name=$this->security->xss_clean($this->input->post('store_name'));
                $grn_from_date=$this->security->xss_clean($this->input->post('grn_from_date'));
                 $grn_to_date=$this->security->xss_clean($this->input->post('grn_to_date'));
                  $grn_status=$this->security->xss_clean($this->input->post('grn_status'));

        if(empty($grn_number)){ 
            $grn_number=$this->input->get('grn_number');
            if(!empty($grn_number)){
             $grn_number=$this->common_model->decode($grn_number);
        }} 
        if(empty($po_number)){ 
            $po_number=$this->input->get('po_number');
            if(!empty($po_number)){
            $po_number=$this->common_model->decode($po_number);
        }}
         if(empty($vendor_name)){ 
            $vendor_name=$this->input->get('vendor_name');
            if(!empty($vendor_name)){
            $vendor_name=$this->common_model->decode($vendor_name);
        }}
        if(empty($store_name)){ 
            $store_name=$this->input->get('store_name');
            if(!empty($store_name)){
            $store_name=$this->common_model->decode($store_name);
        }}
        if(empty($grn_from_date)){ 
            $grn_from_date=$this->input->get('grn_from_date');
            if(!empty($grn_from_date)){
            $grn_from_date=$this->common_model->decode($grn_from_date);
        }}
        if(empty($grn_to_date)){ 
            $grn_to_date=$this->input->get('grn_to_date');
            if(!empty($grn_to_date)){
            $grn_to_date=$this->common_model->decode($grn_to_date);
        }}
        if(empty($grn_status)){ 
            $grn_status=$this->input->get('grn_status');
            if(!empty($grn_status)){
            $grn_status=$this->common_model->decode($grn_status);
        }}   
        if($lmt==""){
                $lmt=$this->input->get('lmt');
            }
            $encoded_grn_number=$encoded_po_number=$encoded_vendor_name=$encoded_store_name=$encoded_grn_from_date=$encoded_grn_to_date=$encoded_grn_status="";  
            if(!empty($grn_number)){$encoded_grn_number=$this->common_model->encode($grn_number);}
            if(!empty($po_number)){$encoded_po_number=$this->common_model->encode($po_number);}
            if(!empty($vendor_name)){$encoded_vendor_name=$this->common_model->encode($vendor_name);}
           
            if(!empty($grn_number)){
                $config['base_url']=base_url()."inventory/searchGoodsreceivednote?grn_number=".$encoded_grn_number.$lmt;
                $config['total_rows']=$this->common_model->getTotalRecords('tbl_goods_received_notes',array('GrnNumber'=>$grn_number));
            }
            if(!empty($po_number)){
                $config['base_url']=base_url()."inventory/searchGoodsreceivednote?po_number=".$encoded_po_number.$lmt;
                $config['total_rows']=$this->common_model->getTotalRecords('tbl_goods_received_notes',array('PoNum'=>$po_number));
            }
         if(!empty($vendor_name)){
                $config['base_url']=base_url()."inventory/searchGoodsreceivednote?vendor_name=".$encoded_vendor_name.$lmt;
                $config['total_rows']=$this->common_model->getTotalRecords('tbl_goods_received_notes',array('fromCustomerId'=>$vendor_name));
            }
            

            $data['page_name']='Search Goods Recieved Note';
                $data['sub_page']='Inventory/GoodsRecievednote';
                $config['base_url'] = base_url()."Inventory/searchGoodsreceivednote"; 
                $config['per_page'] = PAGINATION_COUNT; 
                $config=$this->common_model->paginationStyle($config);
                $this->pagination->initialize($config);
                 $config['total_rows'] = $this->common_model->getTotalRecords('tbl_goods_received_notes',''); 
                 $data['stockist']=$this->common_model->getActiveCustomer();
                $data['lmt']=$lmt;
                $data['grn_number']=$grn_number;
                $data['po_number']=$po_number;
                $data['ssstockist']=$vendor_name;
                $data['goods_receivednote'] = $this->goodsreceivednote_model->getGoodreceivednoteList($config['per_page'],$lmt,$grn_number,$po_number,$vendor_name);
                $this->load->view('user_index',$data);     

    }}
    public function addgoodsRecievednote(){
        $data['page_name']='Add Goods Recieved Note';
		$data['sub_page']='Inventory/AddgoodsRecievednote';
		$this->load->view('user_index',$data);
    }
    public function packingList(){
        $data['page_name']='Packing List';
		$data['sub_page']='Inventory/Packinglist';
		$this->load->view('user_index',$data);
    }
    public function addPackinglist(){
        $data['page_name']='Add Packing List';
		$data['sub_page']='Inventory/Addpackinglist';
		$this->load->view('user_index',$data);
    }

    public function inventoryDeliverynote(){
        $data['page_name']='Inventory Delivery Note';
		$data['sub_page']='Inventory/InventoryDeliverynote';
		$this->load->view('user_index',$data);
    }
    public function addinventoryDeliverynote(){
        $data['page_name']='Add Inventory Delivery Note';
		$data['sub_page']='Inventory/AddinventoryDeliverynote';
		$this->load->view('user_index',$data);
    }

    public function salesReturnnote(){
        $data['page_name']='Sales Return Note';
		$data['sub_page']='Inventory/SalesReturnnote';
		$this->load->view('user_index',$data);
    }
    public function addsalesReturnnote(){
        $data['page_name']='Add Sales Return Note';
		$data['sub_page']='Inventory/AddsalesReturnnote';
		$this->load->view('user_index',$data);
    }
    public function purchaseReturnnote(){
        $data['page_name']='Purchase Return Note';
		$data['sub_page']='Inventory/Purchasereturnnote';
		$this->load->view('user_index',$data);
    }
    public function addpurchaseReturnnote(){
        $data['page_name']='Add Purchase Return Note';
		$data['sub_page']='Inventory/AddpurchaseReturnnote';
		$this->load->view('user_index',$data);
    }
    public function stockAdjustment(){
        $data['page_name']='Stock Adjustment';
		    $data['sub_page']='Inventory/StockAdjustment';
        $config['base_url'] = base_url()."inventory/StockAdjustment"; 
        $config['total_rows'] = $this->common_model->getTotalRecords('tbl_stock_adjustment','');
        $config['per_page'] = PAGINATION_COUNT; 
        $config=$this->common_model->paginationStyle($config);
        $this->pagination->initialize($config); 
        $lmt=0;
        $lmt=$this->uri->segment(3);
        $lmt=mysqli_real_escape_string($this->db->conn_id,$lmt);
        $data['stockAdjustment'] = $this->inventory_model->GetStockAdjustmentList($config['per_page'],$lmt);
		    $this->load->view('user_index',$data);
    }
    public function addstockAdjustment(){
      $data['page_name']='Add Stock Adjustment';
      $data['sub_page']='Inventory/AddstockAdjustment';
      $data['customer_type']=$this->openingstock_model->getCustomerType();
      $count=$this->common_model->get_general_settings('STK_ADJUSTMENT_COUNT');
      $data['stock_adjustment_code']=$this->common_model->generate_emp_code($count->Description,PREFIX_LENGTH,4);
      $this->load->view('user_index',$data);
  }

  public function createStockAdjustment(){
      if(isset($_POST['stk_adjust_code'])){
          $stock_adj_code=$this->security->xss_clean($this->input->post('stock_adj_code'));
          $location=$this->security->xss_clean($this->input->post('location'));
          $customer_code=$this->security->xss_clean($this->input->post('customer_code'));
          $customer_id=$this->security->xss_clean($this->input->post('customer_id'));
          $stock_adj_date=$this->security->xss_clean($this->input->post('stock_adj_date'));
          $stock_adjust_status=$this->security->xss_clean($this->input->post('stock_adjust_status'));
    
          $last_table_count=$this->security->xss_clean($this->input->post('last_table_count'));
    
          $os_product_id=$this->security->xss_clean($this->input->post('os_product_id'));
          $os_avail_qty=$this->security->xss_clean($this->input->post('os_avail_qty'));
          $os_quantity=$this->security->xss_clean($this->input->post('os_quantity'));
          $i=0;$len=count($os_product_id);
          $count=$this->common_model->get_general_settings('STK_ADJUSTMENT_COUNT');
          $stock_adjust_code=$this->common_model->generate_emp_code($count->Description,PREFIX_LENGTH,4);
          if(!isset($_POST['os_product_id'])){
            $this->session->set_flashdata('error','Product Does Not Exist');
            redirect(base_url().'Inventory/stockAdjustment');
          }
          
          $stock_adj=array(
            'stockAdjustmentCode'=>$stock_adjust_code,
            'location'=>$location,
            'customerId'=>$customer_id,
            'customerName'=>$customer_code,
            'itemCount'=>$len,
            'addedDate'=>$stock_adj_date,
            'status'=>'Draft',
            'createdBy'=>$this->session->userdata('user_id'),
            'createdAt'=>date('Y-m-d h:i:s'),
          );
          $stkAdjID=$this->inventory_model->insertStockAdjustment($stock_adj);
          for($i=0;$i<$len;$i++){
            $stockAdjItems=array(
              'stockAdjustmentId'=>$stkAdjID,
              'productId'=>$os_product_id[$i],
              'availQty'=>$os_avail_qty[$i],
              'quantity'=>$os_quantity[$i],
            );
            $this->inventory_model->insertStockAdjItems($stockAdjItems);
          }
          $this->common_model->updateEmpCount('STK_ADJUSTMENT_COUNT');
          $this->session->set_flashdata('success','Stock Adjustment Created Successfully');
          redirect(base_url().'Inventory/stockAdjustment');
      }else{
        $this->session->set_flashdata('error','Invalid Request');
        redirect(base_url().'Inventory/stockAdjustment');
      }
  }
  
  public function EditStockAdjustment($id){
    $id=$this->common_model->decode($id);
    // $this->session->set_flashdata('error','Invalid Request');
    // redirect(base_url().'Inventory/stockAdjustment');
    $data['customer_type']=$this->openingstock_model->getCustomerType();
    $data['stock_adjustment']=$this->inventory_model->getStockAdjustment($id);
    $data['stock_adjustment_items']=$this->inventory_model->getStockAdjustmentItems($id);
    $data['page_name']='Edit Stock Adjustment';
    $data['sub_page']='Inventory/EditStockAdjustment';
    $this->load->view('user_index',$data);
  }

  public function UpdateStockAdjustment(){
    if(isset($_POST['Open_Inv_update'])){
      $adjustment_code=$this->security->xss_clean($this->input->post('adjustment_code'));
      $location=$this->security->xss_clean($this->input->post('location'));
      $customer_code=$this->security->xss_clean($this->input->post('customer_code'));
      $customer_id=$this->security->xss_clean($this->input->post('customer_id'));
      $added_date=$this->security->xss_clean($this->input->post('added_date'));
      $stock_adj_status=$this->security->xss_clean($this->input->post('stock_adj_status'));
      $adjustment_id=$this->security->xss_clean($this->input->post('adjustment_id'));
      
      $last_table_count=$this->security->xss_clean($this->input->post('last_table_count'));

      $os_product_id=$this->security->xss_clean($this->input->post('os_product_id'));
      $os_avail_qty=$this->security->xss_clean($this->input->post('os_avail_qty'));
      $os_quantity=$this->security->xss_clean($this->input->post('os_quantity'));
      $i=0;$len=count($os_product_id);
      if(empty($adjustment_id)){
        $this->session->set_flashdata('warning','Something Went Wrong');
        redirect(base_url().'Inventory/stockAdjustment');
      }
      $old_status=$this->inventory_model->getStockAdjStatus($adjustment_id);
      if($old_status=="Approved"){
        $this->session->set_flashdata('warning','Stock Adjustment Already Approved, Cannot be modified');
        redirect(base_url().'Inventory/stockAdjustment');
      }
      if($stock_adj_status=="Approved"){
        $res=$this->inventory_model->checkStockWhileDelivery($os_product_id,$os_quantity,$customer_id);
          $succ=false;
          $count=count($res['status']);
          if($count!=1){
            $message='';
            for($l=0;$l<$count;$l++){
              $succ=$res['status'][$l];
              if($succ==false){
                break;
              }
            }				
            for($l=0;$l<$count;$l++){$succs=$res['status'][$l];if($succs==false){$message.=$res['message'][$l].'<br>';}}

            if($succ==false){	
              if(empty($message)){ $message='Some of the listed Product has less Quantity';}
              $this->session->set_flashdata('warning',$message);
                    redirect(base_url().'Inventory/stockAdjustment');
                  }
          }
      }
      $stock_adj=array(
        'location'=>$location,
        'customerId'=>$customer_id,
        'customerName'=>$customer_code,
        'itemCount'=>$len,
        'addedDate'=>$added_date,
        'status'=>$stock_adj_status,
        'updatedBy'=>$this->session->userdata('user_id'),
        'updatedAt'=>date('Y-m-d h:i:s'),
      );
      $stkAdjID=$this->inventory_model->updateStockAdjustment($adjustment_id,$stock_adj);
      $this->inventory_model->deleteStockAdjItems($adjustment_id);
      for($i=0;$i<$len;$i++){
        $stockAdjItems=array(
          'stockAdjustmentId'=>$adjustment_id,
          'productId'=>$os_product_id[$i],
          'availQty'=>$os_avail_qty[$i],
          'quantity'=>$os_quantity[$i],
        );
        $this->inventory_model->insertStockAdjItems($stockAdjItems);
      }

      //Update in Inventory
      if($stock_adj_status=="Approved"){
        for($i=0;$i<$len;$i++){
              $inv_response=$this->inventory_model->getInventoryDeductionDetails($customer_id,$os_product_id[$i],$os_quantity[$i]);
              $inv_len_count=count($inv_response['PendingQty']);

              for($k=0;$k<$inv_len_count;$k++){
                $branch_balance=$overallBalance=0;
                $branch_old_balance=$this->inventory_model->getBranchProductBalance($customer_id,$os_product_id[$i]);
                $overall_old_balance=$this->inventory_model->getOverallProductBalance($os_product_id[$i]);
                $branch_balance=$branch_old_balance-$inv_response['UsedQty'][$k];
                $overall_old_balance=$overall_old_balance-$inv_response['UsedQty'][$k];

              $inv_details_unique=$this->inventory_model->getInvDetails($inv_response['UpdateId'][$k]);
              if(!empty($inv_details_unique)){

                $inv_insert=array(
                  'trackingId'=>$inv_details_unique->trackingId,
                  'productId'=>$os_product_id[$i],
                  'ControlNo'=>$inv_details_unique->ControlNo,
                  'BatchNo'=>$inv_details_unique->BatchNo,
                  'InvType'=>'OUTWARD',
                  'SalesId'=>$adjustment_id,
                  'SaleOrder'=>$adjustment_code,
                  'RecivedQty'=>0,
                  'IssuedQty'=>$inv_response['UsedQty'][$k],
                  'PendingQty'=>0,
                  'branchBalance'=>$branch_balance,
                  'overallBalance'=>$overall_old_balance,
                  'DateOfExpiry'=>$inv_details_unique->DateOfExpiry,
                  'CustomerId'=>$customer_id,
                  'CusomerCode'=>$customer_code,
                  'CustomerType'=>'B2B',
                  'Tag'=>'B2B',
                  'IssueId'=>$inv_response['UpdateId'][$k],
                  'CustomerTag'=>'STK-ADJ',
                  'CreatedAt'=>date('Y-m-d h:i:s'),
                  'CreatedBy'=>$this->session->userdata('user_id'),
                );
                if(!empty($inv_response['UsedQty'][$k])){
                  $this->inventory_model->insertInventory($inv_insert);
                }
                
                //Update Inventory
                $update_Inventory_dt=array(	
                  'PendingQty'=>$inv_response['PendingQty'][$k],
                  'UpdatedAt'=>date('Y-m-d h:i:s'),
                  'UpdatedBy'=>$this->session->userdata('user_id'),
                );
                if(!empty($inv_response['UsedQty'][$k])){
                  $this->inventory_model->updateInventory($inv_response['UpdateId'][$k],$update_Inventory_dt);
                }
                //Update Inventory
              }else{
              $this->session->set_flashdata('error','Inventory Issue has been Occured!... Please Contact Adminstrator Before Proceding other Purchase');
                    redirect(base_url().'saleinvoice/listsalesInvoice');
              }
              
            }
        }
      }
      //Update in Inventory
      $this->session->set_flashdata('success','Stock Adjustment Updated Successfully');
      redirect(base_url().'Inventory/stockAdjustment');
    }else{
      $this->session->set_flashdata('error','Invalid Request');
      redirect(base_url().'Inventory/stockAdjustment');
    }
  }
    public function stockTransfer(){
        $data['page_name']='Stock Transfer';
		$data['sub_page']='Inventory/Stocktransfer';
		$this->load->view('user_index',$data);
    }
    public function addstockTransfer(){
        $data['page_name']='Add Stock Transfer';
		$data['sub_page']='Inventory/Addstocktransfer';
		$this->load->view('user_index',$data);
    }
    public function wastage(){
        $data['page_name']='Wastage';
		$data['sub_page']='Inventory/Wastage';
		$this->load->view('user_index',$data);
    }
    public function addWastage(){
        $data['page_name']='Add Wastage';
		$data['sub_page']='Inventory/Addwastage';
		$this->load->view('user_index',$data);
    }
    public function stockLedger(){
        $data['page_name']='Stock Ledger';
		$data['sub_page']='Inventory/Stockledger';
		$this->load->view('user_index',$data);
    }
    public function addstockLedger(){
        $data['page_name']='Add Stock Ledger';
		$data['sub_page']='Inventory/AddstockLedger';
		$this->load->view('user_index',$data);
    }
    public function inventoryReports(){
        $data['page_name']='Inventory Reports';
		$data['sub_page']='Inventory/Inventoryreports';
		$this->load->view('user_index',$data);
    }
    public function inventorySettings(){
        $data['page_name']='Inventory Settings';
		$data['sub_page']='Inventory/Inventorysettings';
		$this->load->view('user_index',$data);
    }
    public function Openingstockreport(){
     $data['page_name']='Opening Stock report';
     $data['sub_page']='Inventory/Openingstockreport';
     $this->load->view('user_index',$data);
    }
   
     public function Packinglistreport(){
      $data['page_name']='Packing List report';
      $data['sub_page']='Inventory/Packinglistreport';
      $this->load->view('user_index',$data);
     }
     public function Inventorydeliverynotereport(){
      $data['page_name']='Inventory Delivery Note report';
      $data['sub_page']='Inventory/Inventorydeliverynotereport';
      $this->load->view('user_index',$data);
     }
    
    public function Stocktransferreport(){
      $data['page_name']='Stock Transfer report';
      $data['sub_page']='Inventory/Stocktransferreport';
      $this->load->view('user_index',$data);
     }
     public function Stockadjustmentreport(){
      $data['page_name']='Stock Adjustment report';
      $data['sub_page']='Inventory/Stockadjustmentreport';
      $config['base_url'] = base_url()."inventory/Stockadjustmentreport";
      $data['customer']=$this->common_model->getActiveCustomer();
      $this->load->view('user_index',$data);
     }
     public function generateStockAdjusmentReport(){
        $customer_id=$this->session->userdata('customer_id');
        $from_date=$this->security->xss_clean($this->input->post('from_date'));
        $to_date=$this->security->xss_clean($this->input->post('to_date'));
        $stockist=$this->security->xss_clean($this->input->post('stockist'));
        if(empty($from_date) | empty($to_date) | empty($stockist)){
            $this->session->set_flashdata('warning', 'Some fields are empty');
            redirect(base_url().'inventory/Stockadjustmentreport');
        }
        $config['base_url'] = base_url()."inventory/Stockadjustmentreport"; 
        $config['total_rows'] = $this->common_model->getTotalRecords('tbl_stock_adjustment',array());
        $config['per_page'] = PAGINATION_COUNT; 
        $config=$this->common_model->paginationStyle($config);
        $this->pagination->initialize($config); 
        $lmt=0;
        $lmt=$this->uri->segment(3);
        $lmt=mysqli_real_escape_string($this->db->conn_id,$lmt);
        $data['stockadjustment_report']=$this->inventory_model->getStockAdjustmentReport($config['per_page'],$lmt,$from_date,$to_date,$stockist);
        $data['page_name']='Stock Adjustment Report';
        $data['sub_page']='inventory/Stockadjustmentreport';
        $data['customer']=$this->common_model->getActiveCustomer();
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['stockist']=$stockist;
        $this->load->view('user_index',$data);
    }
     public function downloadstockadjustmentExcelReport(){
        $date=$this->security->xss_clean($this->input->post('search_from_date'));
        $to_date=$this->security->xss_clean($this->input->post('search_to_date'));
        $stockist=$this->security->xss_clean($this->input->post('search_stockist_id'));
        if(empty($date) | empty($to_date) | empty($stockist)){
            $this->session->set_flashdata('warning', 'Some fields are empty');
            redirect(base_url().'inventory/Stockadjustmentreport');
        }

        $stockadjList=$this->inventory_model->stockAdjuExcelDownload($from_date,$to_date,$stockist);
        if(empty($stockadjList)){
            $this->session->set_flashdata('warning','No data To Display');
            redirect(base_url().'inventory/Stockadjustmentreport');
        }
        if(!empty($stockadjList)){
            foreach($stockadjList as $SAL){
                $perBV['Stock Date']=date('d-m-Y',strtotime($SAL->addedDate));
                $perBV['Stock Adjustment Code']=$SAL->stockAdjustmentCode;
               
                $perBV['Stockist Name']=$SAL->customerName;
                $perBV['Stockist Code']=$SAL->CustomerCode;
                $perBV['Product SKU']=$SAL->Sku;
                $perBV['Product Name']=$SAL->productName;
                $perBV['Available Quantity']=$SAL->quantity;
               
                $perBV['Quantity Deducted']=$SAL->quantity;
                
                $per_ata[]=$perBV;
                
            }
            $data=$per_ata;
            $filename=$date.'-'.$to_date.'-'.$stockist."Stock-Adjustment-report";
            $export=$this->common_model->ExcelExport($filename,$data);
        }
    }
    
     public function Wastageentriesreport(){
      $data['page_name']='Wastage entries report';
      $data['sub_page']='Inventory/Wastageentriesreport';
      $this->load->view('user_index',$data);
     }
     public function Salesreturnnotereport(){
      $data['page_name']='Sales return note report';
      $data['sub_page']='Inventory/Salesreturnnotereport';
      $this->load->view('user_index',$data);
     }
     public function Purchasereturnnotereport(){
      $data['page_name']='Purchase return note report';
      $data['sub_page']='Inventory/Purchasereturnnotereport';
      $this->load->view('user_index',$data);
     }
     public function Stockledgerreport(){
      $data['page_name']='Stock Ledger report';
      $data['sub_page']='Inventory/Stockledgerreport';
      $this->load->view('user_index',$data);
     }
     public function Stockvaluereport(){
      $data['page_name']='Stock Value report';
      $data['sub_page']='Inventory/Stockvaluereport';
      $this->load->view('user_index',$data);
     }
     public function Wastagereport(){
      $data['page_name']='Wastage Report';
      $data['sub_page']='Inventory/Wastagereport';
      $this->load->view('user_index',$data);
     }

     public function Storessettings(){
      $data['page_name']='Stores Settings';
      $data['sub_page']='Inventory/Storessettings';
      $this->load->view('user_index',$data);
     }
     public function Warehousesettings(){
      $data['page_name']='Warehouse Settings';
      $data['sub_page']='Inventory/Warehousesettings';
      $this->load->view('user_index',$data);
     }
     public function Products(){
      $data['page_name']='Products';
      $data['sub_page']='Inventory/Products';
      $this->load->view('user_index',$data);
     }
     public function Racks(){
      $data['page_name']='Racks';
      $data['sub_page']='Inventory/Racks';
      $this->load->view('user_index',$data);
     }
     public function Bin(){
      $data['page_name']='Bin';
      $data['sub_page']='Inventory/Bin';
      $this->load->view('user_index',$data);
     }
     public function Generalsettings(){
      $data['page_name']='General Settings';
      $data['sub_page']='Inventory/Generalsettings';
      $this->load->view('user_index',$data);
     }
     public function Permission(){
      $data['page_name']='Permission';
      $data['sub_page']='Inventory/Permission';
      $this->load->view('user_index',$data);
     }
    public function Stockdetails(){
        $data['page_name']='Stock details';
        $data['sub_page']='Inventory/Stockdetails';
        $config['base_url'] = base_url()."inventory/Stockdetails"; 
        $config['total_rows'] = $this->common_model->getTotalRecords('tbl_inventory','');
        // $config['per_page'] = PAGINATION_COUNT; 
        $config['per_page'] = 130; 
        $config=$this->common_model->paginationStyle($config);
        $this->pagination->initialize($config); 
        $lmt=0;
        $lmt=$this->uri->segment(3);
        $lmt=mysqli_real_escape_string($this->db->conn_id,$lmt);
        $data['inventory'] = $this->inventory_model->getInventoryList($config['per_page'],$lmt);
        $this->load->view('user_index',$data);
    }
    public function StockReport(){
      $data['page_name']='Stock Reports';
      $data['sub_page']='Inventory/stockReports';
      $this->load->view('user_index',$data);
    }
    public function generateStockReports(){
      if(isset($_POST['search_stock_report'])){
        $date=$this->security->xss_clean($this->input->post('date'));
        $customer=$this->common_model->getActiveCustomer();
        $products=$this->common_model->getAllActiveProducts();
        if(empty($customer)){
          $this->session->set_flashdata('Warning', 'No Data To Display');
          redirect(base_url().'inventory/StockReport');
        }
        if(!empty($customer)){
          foreach($customer as $cus){
            $perBV['Stockist Id']=trim($cus->CustomerCode);
            $perBV['Stockist Name']=trim($cus->CustomerName);
            foreach($products as $prd){
              $perBV[trim($prd->productName).'-'.trim($prd->productCode)]=$this->inventory_model->getBranchProductBalanceBetDate($cus->id,$prd->id,$date);
            }
            $per_ata[]=$perBV;
          }
            
          $data=$per_ata;
          $filename=$date."-stock-details";
          $export=$this->common_model->ExcelExport($filename,$data);
        }
      }else{
        $this->session->set_flashdata('Warning', 'Invalid Request');
			  redirect(base_url().'inventory/StockReport');
      }
    }
 }