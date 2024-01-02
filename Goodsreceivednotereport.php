<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 flex-grow-1"><?php echo $page_name;?></h4>
                </div>
                

                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>

                                    <th scope="col">Sno</th>
                                    <th scope="col">GRN Date</th>
                                    
                                    <th scope="col">GRN Number</th>
                                    <th scope="col">Sales Invoice Number</th>
                                    
                                    <th scope="col">Sales Order Number</th>
                                    <th scope="col">Purchase Order Number</th>
                                    <th scope="col">Purchase Invoice Number</th>
                                    <th scope="col">Order Amount</th>
                                    <th scope="col">Total BV</th>
                                    
                                    <th scope="col">Total Amount</th>
                                    
                                    <th scope="col">Order Status</th>
                                    <th scope="col">Delivery Status</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; if(!empty($grn_report)){ foreach($grn_report as $GRN){ ?>
                                    <tr>
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php if(!empty($GRN->createdAt)){ echo $GRN->createdAt; }?>
                                        </td>
                                        <td>
                                            <?php if(!empty($GRN->GrnNumber)){ echo $GRN->GrnNumber; }?>
                                        </td>
                                        <td>
                                            <?php if(!empty($GRN->SINum)){ echo $GRN->SINum; }?>
                                            
                                        </td>
                                       <td><?php if(!empty($GRN->saleOrderNum)){ echo $GRN->saleOrderNum; }?></td>
                                        <td>
                                            <?php if(!empty($GRN->PoNum)){ echo $GRN->PoNum; }?>
                                            
                                        </td>
                                        <td>
                                            <?php if(!empty($GRN->purchaseInvoiceNum)){ echo $GRN->purchaseInvoiceNum; }?>
                                        </td>
                                        <td>
                                            <?php if(!empty($GRN->MRP)){ echo $GRN->MRP; }?>
                                        </td>
                                        <td>
                                            <?php if(!empty($GRN->TotalBv)){ echo $GRN->TotalBv; }?>
                                        </td>
                                        
                                        <td>
                                            <?php if(!empty($GRN->mrpGrossAmount)){ echo $GRN->mrpGrossAmount; }?>
                                        </td>
                                        <td>
                                             <?php if(!empty($GRN->status)){ echo $GRN->status; }?>
                                        </td>
                                        <td>
                                           
                                        </td>
                                        <td>
                                            
                                        </td>
                                        <td>
                                            
                                        </td>
                                        <td>
                                            
                                        </td>
                                    </tr>
                                    <?php $i++;} }else{ ?>
                                        <tr>
                                            <td colspan="17" align="center">No Records Found</td>
                                        </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->



</div>
<!-- container-fluid -->