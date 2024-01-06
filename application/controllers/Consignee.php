<?php
// ini_set('memmory_limit','-1');
require 'vendor/autoload.php';
//use PhpOffice\PhpSpreadsheet\
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;


/**
 * 
 *  PROCEDURE `spconsigneemaster`
 * (IN `itype` VARCHAR(100),
 *  IN `iid` INT(11),
 *  IN `iconsigneecode` VARCHAR(100),
 *  IN `iconsigneename` VARCHAR(100), 
 * IN `iaddress` VARCHAR(100), 
 * IN `imobileno` VARCHAR(100), 
 * IN `icity` VARCHAR(100), 
 * IN `istate` VARCHAR(100),
 *  IN `ipincode` VARCHAR(100),
 *  IN `icountry` VARCHAR(100),
 *  IN `iother1` VARCHAR(100), 
 * IN `iother2` VARCHAR(100), 
 * IN `iother3` VARCHAR(100), 
 * IN `icreatedby` INT(11), 
 * IN `iupdatedflag` VARCHAR(100), 
 * IN `irefdate` VARCHAR(100), 
 * IN `ifinyear` VARCHAR(100), 
 * IN `iupdatedtimestamp` VARCHAR(100),
 *  IN `icreatedthrough` VARCHAR(100), 
 * IN `iupdatedthrough` VARCHAR(100)) 
 */
    class Consignee extends CI_Controller{
        private $DateTime,$user_id,$Msg=array(
            "Msg"=>"Something Went Wrong",
            "Res" => false
        );
        public function __construct(){
            parent::__construct();
            $this->user_id = $this->session->userdata('admin_id');
            $this->role = $this->session->userdata('role');
            !empty($this->user_id)?:redirect("Admin");
            $this->load->model("GetUserData","GD");
            $this->load->model("ConTran","CT");
            $this->load->model("AddOnsTran","AT");
            date_default_timezone_set("Asia/Kolkata");
            $this->DateTime = date('Y-m-d H:i:s');
            $this->load->model("PreTran","PT");
            //@@25092019
            $this->load->model('GetLocation');
            //@@25092019 end
        }
        private function Support($view,$data=[]){
            $NavFoot = $this->GD->NavData();
            $data = array_merge_recursive($NavFoot,$data);
            
            // echo "<pre>";
            // print_r ($data['role']);
            // echo "</pre>";
            // exit;
            $this->load->view("NavFoot/navbar",$data);
            $this->load->view($view);
            $this->load->view("NavFoot/footer");
        }
        /**
         * 
         ===============================      Label Variable         ==========================================
         * 
         */
        public function Add(){
            $States = $this->GD->GetStates();
            $LabelType = $this->GD->GetType();
            $Options = $this->AT->GetTitle();
            $Dates = $this->AT->GetDates(); 
            //@@25092019
            $location = $this->GetLocation->GetAllLocation();
            //  echo "<pre>";
            // print_r ( $location->result());
            // echo "</pre>";
            // exit;
            $data = array(
                "States" => $States,
                "Type"=> $LabelType,
                "title"=> "Add Consignee",
                "Options" => $Options,
                "location" => $location,
                "Dates" => $Dates
            );
            //@@25092019 end
            $this->Support("Consignee/Add",$data);   
        }

        public function Edit(){
            $Consignee = $this->GetParam();
            $Consignee = $this->CT->Consignee($Consignee);
            $States = $this->GD->GetStates();
            $LabelType = $this->GD->GetType();
            $Options = $this->AT->GetTitle();
            $Dates = $this->AT->GetDates(); 
            //@@25092019
            $location = $this->GetLocation->GetAllLocation();
            $data = array(
                "States" => $States,
                "Type"=> $LabelType,
                "Consignee" => $Consignee,
                "location" => $location,
                "Dates" => $Dates
            );
            //@@25092019 end
            $this->Support("Consignee/Edit",$data);   
        }

        public function AddVariables(){
            $LabelType = $this->input->post('LabelType',true);
            $Detail = $this->input->post("Value",true);
            $CMID = $this->input->post("Consignee"); #CMID
            if(count($LabelType) == count($Detail)){
                foreach($LabelType as $key=>$Title){
                    $this->CT->Lables(array(
                        "INS",0,$CMID,$Title,$Detail[$key]
                    ));
                }
                $this->Msg = array(
                    "Res" => true,
                    "Msg" => "Consignee Label Variable(s) Successfully"
                );
            }
            $this->session->set_flashdata("Lab-Consignee-Edit",$this->Msg);
            redirect("Consignee/Edit"); 
        }
        public function AddConsignee(){
            $LabelType = $this->input->post('LabelType',true);
            $Detail = $this->input->post("Value",true);
    //221019
            $Location = $this->input->post("Location",true);
            if(count($LabelType) == count($Detail)){
                $Master = $this->GetParam("INS");
                $CMID = $this->CT->Consignee($Master)->row()->CMID;
                foreach ($Location as $row) {
                    $Masters = $this->GetPara("INS",0,$CMID,$row);
                    $Location =$this->CT->AddLocation($Masters);
                }
                $Data = [];
                if(count($LabelType) > 0){
                    foreach($LabelType as $key=>$Title){
                        $this->CT->Lables(array(
                            "INS",0,$CMID,$Title,$Detail[$key]
                        ));
                    }
                }
    //221019
                $this->AddOpt($CMID);
                $this->Msg = array(
                    "Res" => true,
                    "Msg" => "Consignee Added Successfully"
                );
            }
            $this->session->set_flashdata("Lab-Consignee-Add",$this->Msg);
            redirect("Consignee/Add");
        }
        public function GetCity($State=null,$Ob=false,$CityID=null){
            $State = (!isset($State))?$this->input->post("State",true) : $State;
            $City = $this->GD->GetCity($State);
            (!$Ob)?:ob_start();
            foreach ($City->result() as $row) {
                ?>
                <option value="<?= $row->city_id?>" <?= ($CityID!=$row->city_id)?"":"selected" ?>><?= $row->city_name?></option>
                <?php
            }
            return (!$Ob)?:ob_get_clean();
        }
//06122019
        private function GetPara($Type="GET",$LOCCID= 0,$CMID=null,$LOCID=null){
            return $Masters = array(
                $Type,$LOCCID,$CMID,$this->user_id,$LOCID

            );
        }
        //06122019
        private function GetParam($Type="GET",$ID = 0){
            $Name = $this->input->post('Name',true);
            $Code = $this->input->post('Code',true);
            $Address = $this->input->post('Address',true);
            $State = $this->input->post('State',true);
            $City = $this->input->post('City',true);
            $PinCode = $this->input->post('PinCode',true);
            $Contact = $this->input->post('Contact',true);
            $MDID = $this->input->post('Date',true);
            $SRNO = $this->input->post('IndexLen',true);
            // $SRAuto = ($this->input->post('SrAuto',true) == 'yes') ? 1 : 0;
            $SRAutoVal = $this->input->post('SrAuto',true);
            //@@25092019
            $SRAuto = 0;
            if($SRAutoVal == 'yes'){
                $SRAuto = 1;
            }
            else if($SRAutoVal == 'pro'){
                $SRAuto = 2;
            }
            else if($SRAutoVal == 'con'){
                $SRAuto = 3;
            }
            else if($SRAutoVal == 'pro-12'){
                $SRAuto = 4;
            }
            else if($SRAutoVal == 'pro-year'){
                $SRAuto = 5;
            }
            else if($SRAutoVal == 'pro-month'){
                $SRAuto = 6;
            }
            else if($SRAutoVal == 'pro-week'){
                $SRAuto = 7;
            }
            $Location = $this->input->post('Location',true);
            return $Master = array(
                $Type,$ID,$Code,$Name,$Address,
                $Contact,$City,$State,$PinCode,"India",
                $MDID,$SRNO,$SRAuto,$this->user_id,"NULL",
                "NULL","NULL","NULL","NULL","NULL"
                ,$Location
            );
            //@@25092019 end
        }
        public function GetConsignee(){
            $LabelType = $this->GD->GetType()->result();
            $ID = $this->input->post("Consignee",true);
            echo !empty($ID)?"":false;
            $Param = $this->GetParam("GET",$ID);
            $Result = $this->CT->Consignee($Param);
            ($Result->num_rows()>0)?:die();
            $Result = $Result->row();
            $Labels = $this->GetLabel($Result->CMID,$LabelType);
            $AddOpt = $this->GetAddOns($Result->CMID);
            $City = $this->GetCity($Result->state,true,$Result->city);
           //221019
            $Location = $this->input->post('Location',true);
            $Para = $this->GetPara("GET",null,$Result->CMID);
            $Location = $this->CT->AddLocation($Para);
            if($Result->AutoSR == "1"){
                $No= "yes";
            }
            elseif($Result->AutoSR == "2"){
                $No = "pro";
            }
            elseif($Result->AutoSR == "4"){
                $No = "pro-12";
            }
            elseif($Result->AutoSR == "5"){
                $No = "pro-year";
            }
            elseif($Result->AutoSR == "6"){
                $No = "pro-month";
            }
            elseif($Result->AutoSR == "7"){
                $No = "pro-week";
            }
            else{
                $No = "no";
            }

                
            $Data = array(
                /*@@*/
                "Name" => $Result->consigneedesc,
                "Code" => $Result->consigneecode,
                "Contact" => $Result->mobileno,
                "Address" => $Result->address,
                "State" => $Result->state,
                "City" => $City,
                "PinCode" => $Result->pincode,
                "Label" => $Labels,
                "Ref" =>$Result->CMID,
                "Len" => $Result->SRNO,
                "SRMain"=> $No,
                "AddOpt" => $AddOpt,
                "Dates" => $Result->MDID,
                //221019 // "Location" => $Result->location,
                "Location" => array_column($Location->result(),'LOCID')
            );
            echo json_encode($Data);
        }
        private function GetAddOns($CMID){
            $Options = $this->AT->GetTitle($CMID);
            
            // echo "<pre>";
            // print_r ($Options->result());
            // echo "</pre>";
            // exit;
            ob_start();
            foreach ($Options->result() as $key => $row) {
                ?>
                    <option value="<?= $row->AOID?>" <?= ($row->Sel =="yes")? 'selected': '' ?> ><?= $row->Title?></option>
                <?php
            }
            return ob_get_clean();
        }
        private function GetLabel($CMID,$Type){
            ob_start();
            $Param = array(
                "GET",0,$CMID,0,"Null"
            );
            $Labels =  $this->CT->Lables($Param);
            if($Labels->num_rows() > 0){
                foreach ($Labels->result() as $key => $nrow) {
                    ?>
                    <div class="col-lg-3 col-md-6 col-12 main">
                    <input type="hidden" name="Labs[]" value="<?= $nrow->LMID?>">
                    <input type="hidden" name="Consignee[]" value="<?= $nrow->CMID?>">
                        <div class="card">
                            <div class="card-header gradient-forest">
                                <span class="card-title text-white">Label Variables</span>
                                <?php
                                    if($Type != "Safe"){
                                        ?>
                                        <button type="button" class="close btn-outline-danger cust-rm-alert"  data-lab="<?= $nrow->LMID?>" data-toggle="modal" data-target="#cust-rm-alert"  title="Remove" title="remove">&times;</button>
                                        <?php
                                    }else{
                                        ?>
                                        <button type="button" class="close btn-outline-danger cust-rm" title="Remove" title="remove">&times;</button>
                                        <?php
                                    }
                                ?>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="LabelType">Title</label>
                                    <input class="form-control consignee-title" value="<?= $nrow->Title;?>" type="text" name="LabelType[]">
                                </div>
                                <div class="form-group">
                                    <label for="Value">Value</label>
                                    <textarea placeholder="Value" name="Value[]" class="form-control " required><?= $nrow->Detail?></textarea>
                                </div>  
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }else{
                ?>
                <div class="jumbotron gradient-forest mx-auto ">
                    <h4 class="text-center text-white">Varible(s) Not Avialable</h4>
                </div>
                <?php
            }
           
            return ob_get_clean();
        }
        public function DeleteLabelVariable(){
            $LMID = $this->input->post("LabelVariable",true);
            $this->session->set_flashdata("Lab-Consignee-Edit",$this->Msg);
            (!(empty($LMID)))?:redirect("Consignee/Edit");
            $Param = array(
                "DEL",$LMID,0,0,"Null"
            );            
            $res = $this->CT->Lables($Param);
            $this->Msg = ($this->CT->Lables($Param)->row()->msg == "success")?"Label Deleted Successfully":$this->Msg;
            $this->session->set_flashdata("Lab-Consignee-Edit",array(
                "Res" => true,
                "Msg" => $this->Msg
            ));
            redirect("Consignee/Edit");
        }
        public function EditLabels(){
            $LMID = $this->input->post('Labs',true);
            $LabelType = $this->input->post('LabelType',true);
            $Detail = $this->input->post("Value",true);
            $CMID = $this->input->post("Consignee",true);
            foreach($LabelType as $key=>$Title){
                $this->CT->Lables(array(
                    "UPD",$LMID[$key],$CMID[$key],$Title,$Detail[$key]
                ));
            }
            $this->session->set_flashdata("Lab-Consignee-Edit",array(
                "Res" => true,
                "Msg" => "Consignee Label Edited Successfully"
            ));
            redirect("Consignee/Edit");
        }
        public function EditInfo(){
            $ID = $this->input->post("ConsigneeRef");
            $Master = $this->GetParam("UPD",$ID);
            $CMID = $this->CT->Consignee($Master);
            // echo $this->db->last_query();
            // exit;
           //231019
            $Location = $this->input->post("Location");
            $Masters = $this->GetPara("UPD",0,$ID,0);
            $this->CT->AddLocation($Masters);
            foreach ($Location as $row) {
                $Masters = $this->GetPara("INS",0,$ID,$row);
                $Location = $this->CT->AddLocation($Masters);
            //231019
            }
            // exit;
            $this->session->set_flashdata("Lab-Consignee-Edit",array(
                "Res" => true,
                "Msg" => "Consignee Info Edited Successfully"
            ));
            redirect("Consignee/Edit");
        }

        public function EditOpt(){
            $CMID = $this->input->post('Consignee',true);
            $CMID = $this->CT->isConsignee($CMID,$this->user_id); $Res = 0;
            if($CMID->num_rows() > 0){
                $Res = $this->AddOpt($CMID->row()->CMID);
            }
            $this->session->set_flashdata("Lab-Consignee-Edit",[
                "Msg" => ($Res > 0)? "Additional Option(s) Edited Successfully" : $this->Msg["Msg"],
                "Res" => ($Res > 0)
            ]);
            redirect("Consignee/Edit");
        }

        public function GetDpiInfo(){
            $Consignee = $this->input->post("Consignee",true);
            // $Products = $this->CT->GetProducts($Consignee,$this->user_id);
            $Products = $this->CT->GetProduct(array(
                "GetPrnFile",$this->user_id,$Consignee ,null
            ));
            // echo "<pre>Products : "; print_r($Products->result());
            // echo "<pre>db : "; print_r($this->db->last_query()); exit;
            ob_start();
            foreach ($Products->result() as $key => $row) {
                ?>
                <?php if(!empty($row->Path) && !empty($row->DpiPrnPath) ){ ?>
                <option value="203 DPI">203 DPI</option>
                <option value="300 DPI">300 DPI</option>
            <?php }elseif(empty($row->Path) && empty($row->DpiPrnPath)){?>
                <option value="">Please Add Lable</option>
            <?php }elseif(empty($row->DpiPrnPath)){?>
                <option value="203 DPI">203 DPI</option>
            <?php }else{?> 
                <option value="300 DPI">300 DPI</option>
            <?php }?>    
                <?php
            }
            $Products = ob_get_clean();

            echo json_encode([
                "Prd" => $Products,
            ]);
        }

        public function GetBatchInfo(){
            $Consignee = $this->input->post("Consignee",true);
            $Products = $this->CT->GetProducts($Consignee,$this->user_id);
          
            $PrdCode = "";
            ob_start();
            foreach ($Products->result() as $key => $row) {
                $BarcodeLength = explode(',',$row->ValueData);
               
                $BarcodeLength = count(array_map(function($batrcodeRow){
                    return trim($batrcodeRow) != "";
                }, $BarcodeLength));
                ?>
                <option data-Barcodecount = "<?=$BarcodeLength;?>" value="<?= $row->id?>"><?= $row->productcode?></option>
                <?php
            }
            $PrdCode = ob_get_clean();
            
            ob_start();
            foreach ($Products->result() as $key => $row) {
                $BarcodeLength = explode(',',$row->ValueData);
               
                $BarcodeLength = count(array_map(function($batrcodeRow){
                    return trim($batrcodeRow) != "";
                }, $BarcodeLength));
                ?>
                <option data-code="<?= $row->productcode?>" data-Barcodecount = "<?=$BarcodeLength;?>" data-name="<?= $row->productname?>" value="<?= $row->id?>"><?= $row->productname?></option>
                <?php
            }
            $Products = ob_get_clean();
            $AddOns = $this->CT->GetAddOns($Consignee)->result_array();
            $NewAddOns = [];
            for ($i=0; $i < count($AddOns); $i++) {
                if(!empty($AddOns[$i+1]) && $AddOns[$i]['AOID'] == $AddOns[$i+1]['AOID']){
                    $NewAddOns[$i]['Name'] = $AddOns[$i]['Title'];
                    $NewAddOns[$i]['opt'][] = $AddOns[$i];
                    $c= $i;
                    AddOns:
                    $NewAddOns[$c]['opt'][] = $AddOns[$i+1];
                    $i++;
                    if(!empty($AddOns[$i+1]) && $AddOns[$i]['AOID'] == $AddOns[$i+1]['AOID']){
                        goto AddOns;
                    }
                }else{
                    $NewAddOns[$i]['Name'] = $AddOns[$i]['Title'];
                    $NewAddOns[$i]['opt'][] = $AddOns[$i];
                }
            }
            ob_start();


            # @@ Start
            // $Time =  strtotime(date("H:i"));
            // $Shit = 3;
            // if($Time >=  strtotime('7:00') && $Time <= strtotime('15:30')){
            //     $Shit = 1;
            // }else if($Time >= strtotime('15:31') && $Time <= strtotime('23:58')){
            //     $Shit = 2;
            // }else if($Time >= strtotime('23:59') && $Time <= strtotime('6:59')){
            //     $Shit = 3;
            // }


            foreach ($NewAddOns as $key => $row) {
                // echo "<pre> NewAddOns :"; print_r($NewAddOns); 
                // echo "<pre> db :"; print_r($this->db->last_query()); 
                // echo "<pre> role :"; print_r($this->role); exit;
                if((strtolower($row['Name']) != 'shift' && strtolower($row['Name']) != 'sht' ) || $this->role == "SuperAdmin"){
                ?>
                    <div class="col-12 col-lg-6 col-md-4">
                        <div class="form-group">
                            <label><?= $row['Name']?></label>
                            <select name="Opt[]" class="form-control AddOnsOpts " required>
                                <option value="" selected><?= "Select {$row['Name']}"?></option>
                                <?php
                                foreach ($row['opt'] as $key => $nrow) {
                                    $Select = ( (strtolower($row['Name']) == 'shift' || strtolower($row['Name']) == 'sht' ) && $nrow['Data'] == $Shit)?'selected':'';
                                    ?>
                                        <option  value="<?= $nrow['ADID']?>"  <?= $Select?> ><?= $nrow['Data']?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                <?php
                }
            }

            # @@ End


            $NewAddOns = ob_get_clean();
            echo json_encode([
                "Prd" => $Products,
                "Addons"=> $NewAddOns,
                'PrdCode' => $PrdCode
            ]);
        }
        public function GetVerifyBatchInfo(){
            $Consignee = $this->input->post("Consignee",true);
            $Products = $this->CT->GetProducts($Consignee,$this->user_id);
            $PrdCode = "";
            ob_start();
            foreach ($Products->result() as $key => $row) {
                 
                $BarcodeLength = explode(',',$row->ValueData);
               
                $BarcodeLength = count(array_map(function($batrcodeRow){
                    return trim($batrcodeRow) != "";
                }, $BarcodeLength));
                ?>
                <option  data-ValueData = "<?=$row->ValueData;?>" data-Barcodecount = "<?=$BarcodeLength;?>" value="<?= $row->id?>"><?= $row->productcode?></option>
                <?php
            }
            $PrdCode = ob_get_clean();
            
            ob_start();
            foreach ($Products->result() as $key => $row) {
                 
                $BarcodeLength = explode(',',$row->ValueData);
               
                $BarcodeLength = count(array_map(function($batrcodeRow){
                    return trim($batrcodeRow) != "";
                }, $BarcodeLength));
                ?>
                <option  data-ValueData = "<?=$row->ValueData;?>" data-Barcodecount = "<?=$BarcodeLength;?>" data-code="<?= $row->productcode?>" data-name="<?= $row->productname?>" value="<?= $row->id?>"><?= $row->productname?></option>
                <?php
            }
            $Products = ob_get_clean();
            $AddOns = $this->CT->GetAddOns($Consignee)->result_array();
            $NewAddOns = [];
            for ($i=0; $i < count($AddOns); $i++) {
                if(!empty($AddOns[$i+1]) && $AddOns[$i]['AOID'] == $AddOns[$i+1]['AOID']){
                    $NewAddOns[$i]['Name'] = $AddOns[$i]['Title'];
                    $NewAddOns[$i]['opt'][] = $AddOns[$i];
                    $c= $i;
                    AddOns:
                    $NewAddOns[$c]['opt'][] = $AddOns[$i+1];
                    $i++;
                    if(!empty($AddOns[$i+1]) && $AddOns[$i]['AOID'] == $AddOns[$i+1]['AOID']){
                        goto AddOns;
                    }
                }else{
                    $NewAddOns[$i]['Name'] = $AddOns[$i]['Title'];
                    $NewAddOns[$i]['opt'][] = $AddOns[$i];
                }
            }
            ob_start();


            # @@ Start
            // $Time =  strtotime(date("H:i"));
            // $Shit = 3;
            // if($Time >=  strtotime('7:00') && $Time <= strtotime('15:30')){
            //     $Shit = 1;
            // }else if($Time >= strtotime('15:31') && $Time <= strtotime('23:58')){
            //     $Shit = 2;
            // }else if($Time >= strtotime('23:59') && $Time <= strtotime('6:59')){
            //     $Shit = 3;
            // }


            foreach ($NewAddOns as $key => $row) {
                if((strtolower($row['Name']) != 'shift' && strtolower($row['Name']) != 'sht' ) || $this->role == "Admin" || $this->role == "SuperAdmin" ){
                ?>
                    <div class="col-12 col-lg-6 col-md-4">
                        <div class="form-group">
                            <label><?= $row['Name']?></label>
                            <select name="Opt[]" class="form-control AddOnsOpts " required>
                                <option value="" selected><?= "Select {$row['Name']}"?></option>
                                <?php
                                foreach ($row['opt'] as $key => $nrow) {
                                    $Select = ( (strtolower($row['Name']) == 'shift' || strtolower($row['Name']) == 'sht' ) && $nrow['Data'] == $Shit)?'selected':'';
                                    ?>
                                        <option  value="<?= $nrow['ADID']?>"  <?= $Select?> ><?= $nrow['Data']?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                <?php
                }
            }

            # @@ End


            $NewAddOns = ob_get_clean();
            echo json_encode([
                "Prd" => $Products,
                "Addons"=> $NewAddOns,
                'PrdCode' => $PrdCode
            ]);
        }
        private function AddOpt($CMID){
            $Options = (array)$this->input->post('Options',true);
            $Opt = [];
            foreach ($Options as $key => $row) {
                $Opt[] = [
                    "AOID" => $row,
                    "CMID" => $CMID,
                    "CreatedAt" => $this->DateTime,
                    "UpdatedAt" => $this->DateTime,
                    "CreatedBy" => $this->user_id,
                ];
            }
            return $this->CT->AddOptions($Opt,$CMID);
        }
        /**
         * 
        ==============================       Lable Section      ==================================================
         * 
         */
        public function PRNUplaod(){
            $Consignee = $this->CT->Consignee($this->GetParam("GetWithoutFile"));
            $Data = array(
                "OurConsignee" => $Consignee,
                "title" => "Add Consignee PRN File"
            );
            $this->Support("Label/PRNUpload",$Data);
        }
        public function PRNEdit(){
            $Consignee = $this->CT->Consignee($this->GetParam("GetWithFile"));
            $Data = array(
                "OurConsignee" => $Consignee,
                "title" => "Replace Consignee PRN File"
            );
            $this->Support("Label/PRNEdit",$Data);
        }
        public function AddPRNFile(){
            $CMID = $this->input->post('Consignee',true); # CMID
            $Printer = $this->input->post('PrinterDpi',true); # PrinterDpi
            $Upd = $this->input->post("Update",true);
            // echo "<pre>CMID"; print_r($CMID);
            // echo "<pre>Printer"; print_r($Printer);
            // echo "<pre>Upd"; print_r($Upd); exit;
            is_dir('upload/')?:mkdir('upload/');
            is_dir('upload/'.$this->user_id)?:mkdir('upload/'.$this->user_id);            
            $config = array(
                "upload_path" => "upload/{$this->user_id}/",
                'allowed_types' => "*"
            );
            
            $this->load->library("upload",$config);
            $this->upload->do_upload('PRNFile');
            $Result = $this->upload->data();
            $Path = "upload/{$this->user_id}/{$Result['file_name']}";
            $this->CT->UploadFile(array(
                "INS",0,$CMID,$Path,$Printer
            ));
            $Page = empty($Upd) ? "Add" : "Edit"; 
            $this->session->set_flashdata("Lab-File-{$Page}",array(
                "Res" => true,
                "Msg" => "Consignee Label File Uploaded Successfully"
            ));
            $Upd = empty($Upd) ? "PRNUplaod" : "PRNEdit";
            redirect("Consignee/$Upd");
        }
        public function UpdatePRNFile(){
            $CMID = $this->input->post('Consignee',true); # CMID
            $Printer = $this->input->post('PrinterDpi',true); # PrinterDpi
            $Upd = $this->input->post("Update",true);
            // echo "<pre>CMID"; print_r($CMID);
            // echo "<pre>Printer"; print_r($Printer);
            // echo "<pre>Upd"; print_r($Upd); exit;
            is_dir('upload/')?:mkdir('upload/');
            is_dir('upload/'.$this->user_id)?:mkdir('upload/'.$this->user_id);            
            $config = array(
                "upload_path" => "upload/{$this->user_id}/",
                'allowed_types' => "*"
            );
            
            $this->load->library("upload",$config);
            $this->upload->do_upload('PRNFile');
            $Result = $this->upload->data();
            $Path = "upload/{$this->user_id}/{$Result['file_name']}";
            $this->CT->UploadFile(array(
                "UPD",0,$CMID,$Path,$Printer
            ));
            $Page = empty($Upd) ? "Add" : "Edit"; 
            $this->session->set_flashdata("Lab-File-{$Page}",array(
                "Res" => true,
                "Msg" => "Consignee Label File Uploaded Successfully"
            ));
            $Upd = empty($Upd) ? "PRNUplaod" : "PRNEdit";
            redirect("Consignee/$Upd");
        }
        /**
        ============================== Consignee Batch Section =======================================
         */

        /*start 15072019*/
        private function SetSerial($File,$i,$SrSize){
            $Sr = substr_count($File,'$[SRNO]');
            $HowMuch = 1; $i = empty($i) ? 0 : $i ;
            $Limit = ($Sr + $i);
            
            for($i; $i <= $Limit; $i++){
                $Index =  str_pad($i,$SrSize,0,STR_PAD_LEFT);
                $Pos = strpos($File,'$[SRNO]');
                if($Pos !== false){
                    $File = substr_replace($File,$Index,$Pos,strlen('$[SRNO]'));
                    $End = (strpos($File,'$[SRNO]') - strlen('$[SRNO]'));
                    $Sub = substr($File,$Pos,$End);    
                    $Sub = str_replace('$[SNO]',$Index,$Sub);
                    $File =  ($End > 0)? substr_replace($File,$Sub,$Pos,$End) : str_replace('$[SNO]',$Index,$File);
                }
            }
            return [
                'Data' => $File,
                'Index' => $i
            ];
        }

        private function ReplaceData($Path,$Detail=[],$index=null,$SrSize = 1){
            $NewFile = false;
            if(is_file($Path)){
                $File = file_get_contents($Path);
                $SetSrno = empty($index)? ['Data' => $File,'Index' => null] : $this->SetSerial($File,$index,$SrSize); # Remove TO Revert Back Change
                $File = $SetSrno['Data'];
                $NewFile = str_replace(
                    array_keys($Detail),
                    array_values($Detail),
                    $File
                );    
            }
            //print_r($NewFile);exit;
            return [
                'Data' => $NewFile,
                'Srno' => $SetSrno['Index']
            ];
        }

        /*end*/ 
        private function ReadPRN($File,$index=0){
            $ch = curl_init();
            $Link = "http://api.labelary.com/v1/printers/8dpmm/labels/4x6/{$index}/";
            curl_setopt_array($ch,array(
                CURLOPT_URL => $Link,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $File,
                CURLOPT_RETURNTRANSFER => TRUE
            ));
            $Result = curl_exec($ch);
            curl_close($ch);
            // $Link = "assets/view/demo.png";
            // $File = fopen($Link,"w");
            // fwrite($File,$Result);
            // fclose($File);
            return $Result;
        }
        public function ReadFile(){
            $Consignee = $this->input->post("Consignee",true);
            if(empty($Consignee = 1)){
                echo "miss";
                exit;
            }else{
                $Path = $this->ReadPRN(base_url('upload/1/TESTSATO4.prn'));
                ?>
                    <img src="<?= base_url($Path)?>" class="img-fluid">
                <?php
            }
        }

        private function callLoc($Type= null,$userID = null){
            return $this->CT->STP_Loc([
                $Type,
                $userID
            ]);
        }
        private function getLocation($userID = null){
            $userLoc = $this->callLoc('getLoc',$userID);
            if($userLoc->num_rows() > 0){
                $userLoc = $userLoc->row()->LocName;
            }else{
                $userLoc = '';
            }
            return $userLoc;
        }

        public function CreateBatch(){
            $loc = $this->getLocation($this->user_id);
            $shift = $this->CT->getShift();
            //echo $this->db->last_query();exit;
           // print_r($shift);exit;
            $Prd = $this->CT->GetProduct(array(
                "GET",$this->user_id,0,0
            ));

            //@@ 26092019
            $Consignee = $this->CT->GetConsignee(array(
                $this->user_id
            ));
            //$Consignee = $this->CT->GetConsignee($this->user_id);
            $Printer = $this->CT->GetPrinter();
            $Data = array(
                "Products" => $Prd,
                "Consignee" => $Consignee,
                "title" => "Create Batch",
                "Printer" => $Printer,
                'location' => $loc,
                'UserRole' => $this->role,
                'shift' => $shift
            );
            $this->Support("Batches/CreateBatch",$Data);
        }
        public function GetBatchDetails(){
            // echo "<pre> post :"; print_r($_POST); exit;
            $PID = $this->input->Post("Product",true);
            if(empty($PID)){
                echo "false";
                exit;
            }
            $info = $this->CT->GetProduct(array(
                "GETPrd",$this->user_id,0,$PID
            )); $Prd = null;$Consignee = null;$State = false;

            if($info->num_rows() > 0) {
                $State = true;
                $info = $info->row();
                $Prd = $this->GetProductLabel($info->CMID,$PID);
                $Consignee = $this->GetLabelInTable($info->CMID,"Safe"); 
                // echo "<pre>prd :"; print_r($Prd);
                // echo "<pre>Consignee :"; print_r($Consignee); exit;
                echo json_encode(array(
                    "Product" => $Prd,
                    "Consignee" => $Consignee,
                    "State" => $State
                ));
            }else{
                echo "false";
            }
        }
        public function GetPreview(){
            $CMID = $this->input->post("Consignee",true);
            $PID = $this->input->post("Product",true);


            $Main = $this->LabelData();$TypeOfLabel = false;
            // echo "<pre>Main"; print_r ($Main); 
            foreach ($Main as $key => $value) {
                if(trim($key) == "$[Logo]" && trim($value) == 0){
                    $TypeOfLabel = true;
                } 
            }

          
            $Res = false;$File = "Select Product First !.";
            $PRNFile = $this->GetPrnFile($CMID);

            if($PRNFile->num_rows() > 0 && is_file(FCPATH.$PRNFile->row()->Path)){
                
                $Main['$[image]'] = ($TypeOfLabel)?:base_url($PRNFile->row()->Image);
                $Main['<img'] = '<img onerror="cust_rm(this)"';
                // $ndate = date('dmy');
                // $Main['$[NDATE]'] = $ndate;
                $File = FCPATH.$PRNFile->row()->Path;
                // print_r ($File);exit;
                // echo "<pre>File OLD"; print_r ($File); 
                $File = $this->ReplaceData($File,$Main);
                
                // echo "<pre> File New :"; print_r ($File); exit;
                // echo "</pre>";
                // exit;
                // $File = "data:image/png;base64,".base64_encode($this->ReadPRN($File));
                $Res = true;
            }else{
                $File = "File Not Found";
            }
            echo json_encode(array(
                "Res" => $Res,
                "File" => $File,
            ));
        }
        /*
        public function GetPreview(){
            $PrdTitle = (array)$this->input->post("PrdTitle",true);
            $PrdValue = (array)$this->input->post("PrdValue",true);
            $ConTitle = (array)$this->input->post("ConTitle",true);
            $ConValue = (array)$this->input->post("ConValue",true);
            $PID = $this->input->post("Prd",true);
            $Main = [];$Res = false;$File = "Select Product First !.";
            if(count($PrdTitle) == count($PrdValue) && count($ConTitle) == count($ConValue) && !empty($PID)){
                for($i=0;$i<count($PrdTitle);$i++){
                    $Main[$PrdTitle[$i]] = $PrdValue[$i];
                }
                for ($i=0; $i < count($ConTitle); $i++) { 
                    $Main[$ConTitle[$i]] = $ConValue[$i];
                }
                $PRNFile = $this->GetPrnFile($PID);
                if($PRNFile->num_rows()>0 && is_file(FCPATH.$PRNFile->row()->Path)){
                    $File = FCPATH.$PRNFile->row()->Path;
                    $File = $this->ReplaceData($File,$Main);
                    $File = "data:image/png;base64,".base64_encode($this->ReadPRN($File));
                    $Res = true;
                }else{
                    $File = "File Not Found";
                }
            }else{
                $File = "Something went Wrong";
            }
            echo json_encode(array(
                "Res" => $Res,
                "File" => $File,
            ));
        }
        */
        
        /*
        private function GetProductLabel($CMID,$PID){
            ob_start();
            $Prd = $this->CT->GetProduct(array(
                "GETWithLB",$this->user_id,$CMID,$PID
            ));
            if($Prd->num_rows() > 0){
                foreach ($Prd->result() as $key => $nrow) {
                    ?>
                    <tr class="main">
                        <td><?= $key+1?></td>
                        <td>
                            <input class="form-control prd-title" value="<?= $nrow->KeyName;?>" type="text" name="PrdType[]">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-rounded prd-value" value="<?= $nrow->ValueData?>" name="PrdValue[]" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger cust-rm waves-effect waves-light" title="Remove" title="remove">&times;</button>
                        </td>
                    </tr>
                    <?php
                }
            }else{
                ?>
                <div class="jumbotron gradient-forest mx-auto ">
                    <h4 class="text-center text-white">Varible(s) Not Avialable</h4>
                </div>
                <?php
            }
           
            return ob_get_clean();
        }
        private function GetPrnFile($PID){
            return $this->CT->GetProduct(array(
                "GetPrnFile",$this->user_id,0,$PID
            ));
        }
        public function GetLabelInTable($CMID){
            ob_start();
            $Param = array(
                "GET",0,$CMID,0,"Null"
            );
            $Labels =  $this->CT->Lables($Param);
            if($Labels->num_rows() > 0){
                foreach ($Labels->result() as $key => $nrow) {
                    ?>
                    <tr class="main">
                        <input type="hidden" name="Labs[]" value="<?= $nrow->LMID?>">
                        <input type="hidden" name="Consignee[]" value="<?= $nrow->CMID?>">
                        <td><?= $key+1?></td>
                        <td>
                            <input class="form-control consignee-title" value="<?= $nrow->Title;?>" type="text" name="LabelType[]">
                        </td>
                        <td>
                            <input type="text" class="form-control consignee-value" value="<?= $nrow->Detail?>" name="Value[]" id="Value" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger cust-rm waves-effect waves-light" title="Remove" title="remove">&times;</button>
                        </td>
                    </tr>
                    <?php
                }
            }
            return ob_get_clean();
        }
        */
        private function GetProductLabel($CMID,$PID){
            ob_start();
            $Prd = $this->CT->GetProduct(array(
                "GETWithLB",$this->user_id,$CMID,$PID
            ));
            
            // echo "<pre>";
            // echo $CMID;
            // echo " ".$PID;
            // print_r ($Prd->result());
            // echo "</pre>";
            // exit;
            if($Prd->num_rows() > 0){
                foreach ($Prd->result() as $key => $nrow) {
                    ?>
                    <tr class="main">
                        <input type="hidden" class="titleData" name="PrdTitle[]" value="<?= $nrow->KeyName?>">
                        <input type="hidden" class="detailData" name="PrdVal[]" value="<?= $nrow->ValueData?>">
                        <td><?= $key+1?></td>
                        <td class="editable prd-title titleSet"><?= $nrow->KeyName;?></td>
                        <td class="editable prd-value detailSet"><?= $nrow->ValueData?></td>
                    </tr>
                    <?php
                }
            }else{
                ?>
                <div class="jumbotron gradient-forest mx-auto ">
                    <h4 class="text-center text-white">Varible(s) Not Avialable</h4>
                </div>
                <?php
            }
           
            return ob_get_clean();
        }
        private function GetPrnFile($CMID){
            return $this->PT->GetFile($CMID);
            // Temp
            return $this->CT->GetProduct(array(
                "GetPrnFile",$this->user_id,$CMID,0
            ));
        }
        public function GetLabelInTable($CMID){ #Consignee
            ob_start();
            $Param = array(
                "GET",0,$CMID,0,"Null"
            );
            $Labels =  $this->CT->Lables($Param);
            if($Labels->num_rows() > 0){
                foreach ($Labels->result() as $key => $nrow) {
                    ?>
                    <tr class="main">
                        <input type="hidden" class="titleData" name="ConTitle[]" value="<?= $nrow->Title?>">
                        <input type="hidden" class="detailData" name="ConVal[]" value="<?= $nrow->Detail?>">
                        <td><?= $key+1?></td>
                        <td class="editable consignee-title titleSet" name="Consignee-Title"><?= $nrow->Title;?></td>
                        <td class="editable consignee-value detailSet" name="Consignee-Value"><?= $nrow->Detail?></td>
                    </tr>
                    <?php
                }
            }
            return ob_get_clean();
        }
        /**
         =========================Print Section===============================
         */
        /*
         public function PrintLable(){
            $PrdTitle = $this->input->post("PrdTitle",true);
            $PrdValue = $this->input->post("PrdVal",true);
            $ConTitle = $this->input->post("ConTitle",true);
            $ConValue = $this->input->post("ConVal",true);
            $Printer = $this->input->post("Printer",true);
            $PID = $this->input->post("Product",true);
            $Qty = $this->input->post("Qty",true); $Res = false;$Msg = $this->Msg["Msg"];
            $PrinterName = $this->CT->GetPrinter($PID);$NewData = "";
            
            // echo "<pre>";
            // print_r ($_POST);
            // echo "</pre>";
            exit;
            if(count($PrdTitle) == count($PrdValue) && count($ConTitle) == count($ConValue) && !empty($PID) && $PrinterName->num_rows()>0){
                $PrinterName = $PrinterName->row()->PrinterName;
                
                for($i=0;$i<count($PrdTitle);$i++){
                    $Main[$PrdTitle[$i]] = $PrdValue[$i];
                }
                for ($i=0; $i < count($ConTitle); $i++) { 
                    $Main[$ConTitle[$i]] = $ConValue[$i];
                }
                $PRNFile = $this->GetPrnFile($PID);
                if($PRNFile->num_rows()>0 && is_file(FCPATH.$PRNFile->row()->Path)){
                    $File = FCPATH.$PRNFile->row()->Path; $NewFile = [];
                    for($i=0;$i<$Qty;$i++){
                        $NewData .= $this->ReplaceData($File,$Main);
                        /*
                        $NewFile[$i] = "data:image/png;base64,".base64_encode($this->ReadPRN($NewData));
                        ?>
                        <img src="<?= $NewFile[$i]?>" alt="">
                        <?php
                        */ /*
                    }
                    is_dir('./upload/')?:mkdir("./upload");
                    is_dir("./upload/{$this->user_id}/")?:mkdir("./upload/{$this->user_id}/");
                    $Path = "upload/{$this->user_id}/myprn.prn";
                    $Prn = fopen($Path,'w');
                    fwrite($Prn,$NewData);
                    fclose($Prn);

                    $Bat = file_get_contents(base_url('upload/MyPrinter.bat'));
                    $NewBat = str_replace('[$PRINTER]',FCPATH.$PrinterName,$Bat);
                    $NewBat = str_replace('[$PATH]',$Path,$NewBat);
                    $Res = exec($NewBat);$Res = true;
                    $Msg = "Print Request Sent Successfully";
                }else{
                    $Msg = "File Not Found";
                }
            }
            $this->Msg["Msg"] = $Msg;
            $this->Msg["Res"] = $Res;
            $this->session->set_flashdata("Lab-CreateBatch",$this->Msg);
            redirect("Consignee/CreateBatch");
        }
         */
        private function LabelData(){
            $CMID = $this->input->post("Consignee",true);
            $PID = $this->input->post("Product",true);
            $Qty = empty($this->input->post("Qty",true))? 1 : $this->input->post("Qty",true);

            $Param = $this->GetParam("GET",$CMID);   
            $Info = $this->CT->Consignee($Param);
            $Info = $Info->row();
            $Res = false;$Msg = $this->Msg["Msg"];
            $Opt = $this->input->post("Opt",true); 
            $Prd = $this->CT->GetProduct(array(
                "GETWithLB " ,$this->user_id , $CMID , $PID
            ));
            $Main = [];
            $Main['$[Rev]'] = "   ";
            $Date = ($Info->isReverse == "1")? strrev(date($Info->Data)) :(date($Info->Data));
            $Main['$[DATE]'] = $Date;
            $ndate = date('Y-m-d'); 
            // echo "<pre>date :"; print_r($ndate);
            // Convert the date string to a DateTime object
            $NewdateTime = new DateTime($ndate);
            // echo "<pre>NewdateTime :"; print_r($NewdateTime);
            // Format the date as "23W29"
            $formattedDate = $NewdateTime->format("y\WW");
            // echo "<pre>formattedDate :"; print_r($formattedDate); exit;
            $Main['$[NDATE]'] = $formattedDate;

            $Param = array(
                "GET",0,$CMID,0,"Null"
            );
            $Labels =  $this->CT->Lables($Param);
            $AddOns = (empty($Opt))?[]:$this->CT->GetAddOns($CMID,$Opt);

            foreach ($Labels->result() as $key => $row) {
                $Main['$['.trim($row->Title).']'] = trim($row->Detail);
                // $Main[trim($row->Title)] = trim($row->Detail);
            }

            foreach ($Prd->result() as $key => $row) {
                if(trim($row->KeyName) == "DATE"){
                    $isReverse = array_search('isReverse',array_column($Prd->result(),'KeyName'));
                    $isReverse = ($isReverse > 0)? $Prd->result()[$isReverse]->ValueData : 0;
                    $Date = ($isReverse == "1")? strrev(date($row->ValueData)) :(date($row->ValueData));
                    $Main['$[DATE]'] = $Date;
                    $Main['$[NDATE]'] = $formattedDate;
                    // echo "<pre>formattedDate :"; print_r($Main['$[NDATE]']); exit;
                }
                elseif(trim($row->KeyName) == 'NDATE'){
                                $ndate = date('Y-m-d');
                                $NewDateTime = new DateTime($ndate);
                                $formattedDate = $NewDateTime->format("y\WW");
                                $Main['[NDATE]'] = $formattedDate;
                                // echo "<pre>NDATE : "; print_r($Main['[NDATE]']); exit; 
                }
                elseif(trim($row->KeyName) == 'MYDATE'){
                                $ndate = date('Y-m-d');
                                $NewDateTime = new DateTime($ndate);
                                $formattedDate = $NewDateTime->format("my");
                                $Main['[MYDATE]'] = $formattedDate;
                                // echo "<pre>MYDATE : "; print_r($Main['[MYDATE]']); exit; 
                }
                else{
                   // $Main['$['.trim($row->KeyName).']'] = trim($row->ValueData);
                   $strval = ($CMID == 46 && trim($row->KeyName) == 'Description') ? substr(trim($row->ValueData),0,36) : trim($row->ValueData);

                    $Main['$['.trim($row->KeyName).']'] = $strval;
                    // $Main[trim($row->KeyName)] = trim($row->ValueData);   
                }
            }
            
            // echo "<pre>";
            // print_r ($Main);
            // echo "</pre>";
            // exit;

            if(!empty($AddOns)){
                foreach ($AddOns->result() as $key => $row) {
                    $Main['$['.trim($row->Title).']'] = trim($row->Data);
                    // $Main[trim($row->Title)] = trim($row->Data);
                }
            }

            
            $Pre = 0;
            if($Info->AutoSR == "1"){
                $Pre = $this->CT->GetQty($CMID);
                $Pre = empty($Pre->row()->Maxi)?0:$Pre->row()->Maxi;
            }

            
            // echo "<pre>";
            // print_r ($Main);
            // echo "</pre>";
            // exit;
            $isResetSerial = false;
            if(!empty($AddOns)){
                foreach ($AddOns->result() as $key => $row) {
                    $Main['$['.trim($row->Title).']'] = trim($row->Data);
                    if(trim($row->Title) == 'ResetOnYear' && trim($row->Data) == "1"){
                        $isResetSerial = true;  
                    }
                    // $Main[trim($row->Title)] = trim($row->Data);
                }
            }
        
        
                $Str = (1+$Pre);
                $End = ($Qty + $Pre);
               
                $Index =  str_pad($Str,$Info->SRNO,0,STR_PAD_LEFT);
                $Main['$[SRNO]'] = $Index;
                if($this->role == "Admin"){

                    $Main['$[SHT]'] = $this->CT->getShift();
                }
                
                // $Main['SRNO'] = $Index;
            return $Main;
        }

        /*start 15072019
*/

// public function test(){
//     $Statue = $this->CT->GetQty(30);
//     echo $this->db->last_query();
    
//     echo "<pre>";
//     print_r ($Statue->result());
//     echo "</pre>";
    
// }
public function PrintLable(){
    $CMID = $this->input->post("Consignee",true);
    $PrinterDpi = $this->input->post('PrinterDpi',true); # PrinterDpi
    $PID = $this->input->post("Product",true);
    $Printer = $this->input->post("Printer",true);
    // echo "<pre>CMID : "; print_r($CMID);
    // echo "<pre>PrinterDpi : "; print_r($PrinterDpi);
    // echo "<pre>PID : "; print_r($PID);
    // echo "<pre>Printer : "; print_r($Printer); exit;
    $Level = $this->input->post("Level",true);
    $Param = $this->GetParam("GET",$CMID);   
    $Info = $this->CT->Consignee($Param);
    // echo "<pre> db :"; print_r($this->db->last_query());
    // echo "<pre> info :"; print_r($Info->row()); exit;
    $PrinterName = $this->CT->GetPrinter($Printer);
    
    $Info = $Info->row();
    $PrintInfo = $PrinterName->row();
    $PRID = $PrintInfo->PRID;
    $Qty = $this->input->post("Qty",true); $Res = false;$Msg = $this->Msg["Msg"];
    $Opt = $this->input->post("AddOns",true); 
    // echo "<pre>Opt : "; print_r($Opt);
    $Prd = $this->CT->GetProduct(array(
        "GETWithLB " ,$this->user_id , $CMID , $PID
    ));
    // echo "<pre>Prd : "; print_r($Prd->result()); exit;
    $Param = array(
        "GET",0,$CMID,0,"Null"
    );
    $Labels =  $this->CT->Lables($Param);
    // echo "<pre>Labels : "; print_r($Labels->result()); 
    // echo "<pre>db : "; print_r($this->db->last_query()); exit;
    $AddOns = (empty($Opt))?[]:$this->CT->GetAddOns($CMID,$Opt);
    // echo "<pre>AddOns : "; print_r($AddOns->result()); 
    // echo "<pre>db : "; print_r($this->db->last_query()); exit;
    $Main = [];
    $Date = ($Info->isReverse == "1")? strrev(date($Info->Data)) :(date($Info->Data));
    $Main['$[DATE]'] = $Date;
    $ndate = date('Y-m-d'); 
    // echo "<pre>date :"; print_r($ndate);
    // Convert the date string to a DateTime object
    $NewdateTime = new DateTime($ndate);
    // echo "<pre>NewdateTime :"; print_r($NewdateTime);
    // Format the date as "23W29"
    $formattedDate = $NewdateTime->format("y\WW");
    // echo "<pre>formattedDate :"; print_r($formattedDate); exit;
    $Main['$[NDATE]'] = $formattedDate;
    $Main['$[SHT]'] = $this->CT->getShift();
    foreach ($Labels->result() as $key => $row) {
        $Main['$['.trim($row->Title).']'] = trim($row->Detail);
        // $Main[trim($row->Title)] = trim($row->Detail);
    }
    $TypeOfLabel = false;
    foreach ($Prd->result() as $key => $row) {
        if(trim($row->KeyName) == "Logo" && trim($row->ValueData) == 0){
            $TypeOfLabel = true;
        } 
        if(trim($row->KeyName) == "DATE"){
            $isReverse = array_search('isReverse',array_column($Prd->result(),'KeyName'));
            $isReverse = ($isReverse > 0)? $Prd->result()[$isReverse]->ValueData : 0;
            $Date = ($isReverse == "1")? strrev(date($row->ValueData)) :(date($row->ValueData));
            $Main['$[DATE]'] = $Date;
        }
        elseif(trim($row->KeyName) == 'NDATE'){
            $ndate = date('Y-m-d');
            $NewDateTime = new DateTime($ndate);
            $formattedDate = $NewDateTime->format("y\WW");
            $Main['[NDATE]'] = $formattedDate;
            // echo "<pre>NDATE : "; print_r($Main['[NDATE]']); exit; 
        }
        elseif(trim($row->KeyName) == 'MYDATE'){
            $ndate = date('Y-m-d');
            $NewDateTime = new DateTime($ndate);
            $formattedDate = $NewDateTime->format("my");
            $Main['[MYDATE]'] = $formattedDate;
            // echo "<pre>MYDATE : "; print_r($Main['[MYDATE]']); exit; 
        }
        else{
                // $row->ValueData = str_replace('®','©',$row->ValueData);
                //print_r(count($row->ValueData));

                $strval = ($CMID == 46 && trim($row->KeyName) == 'Description') ? substr(trim($row->ValueData),0,36) : trim($row->ValueData);
                $Main['$['.trim($row->KeyName).']'] = $strval;
                // $Main[trim($row->KeyName)] = trim($row->ValueData);   
        }
    }
    $isResetSerial = false;
    if(!empty($AddOns)){
        foreach ($AddOns->result() as $key => $row) {
            $Main['$['.trim($row->Title).']'] = trim($row->Data);
            if(trim($row->Title) == 'ResetOnYear' && trim($row->Data) == "1"){
                $isResetSerial = true;  
            }
            // $Main[trim($row->Title)] = trim($row->Data);
        }
    }


  
        /** 31-12-19 */ $Pre = $this->SetSerialNo($Info,$CMID,$isResetSerial, $PID);  // Set Serial Initial No.   
    
     // echo "<pre>serial : ";print_r($Pre); exit;
   
    // $PRNFile = ($TypeOfLabel)? $this->CT->GetAdditionalLabel($CMID) : $this->CT->GetProduct(array(
    //     $PrinterDpi,$this->user_id,$CMID,0
    // ));
    $PRNFile = ($TypeOfLabel)? $this->CT->GetAdditionalLabel($PrinterDpi,$CMID) : $this->CT->GetProduct(array($PrinterDpi,$this->user_id,$CMID,0
    ));
    $NewFile = ''; 
   //  echo "<pre>PRN : "; print_r($PRNFile->result());
   // echo "<pre>db : "; print_r($this->db->last_query()); exit;

    if($PRNFile->num_rows()>0 && is_file(FCPATH.$PRNFile->row()->Path)){

 
        $CBID = $this->CT->CreateBatch([
            "CMID" => $CMID,
            "PID" => $PID,
            "CreatedAt" => $this->DateTime,
            "CreatedBy" => $this->user_id,
            "Quantity" => $Qty,
            "PRID" => $PRID
        ]);

              

        $File = FCPATH.$PRNFile->row()->Path;
        // if($this->user_id == 1){

        //     echo json_encode([
        //         'File' => file_get_contents($File),
        //         'Res' => 'true',
        //         'Msg' => 'SUCCESS',
        //         'Excel' => null
        //     ]);
        //     return;
        // }
        /*start 08072019*/
        $Sr = substr_count(file_get_contents($File),'$[SRNO]');
        
        $Qty = ($Sr > 1)? ceil($Qty / $Sr ) : $Qty;
        $Qty = ($Qty > 0 )? $Qty : 1;
        /*end*/
        $Str = (1+$Pre);
        $End = ($Qty + $Pre); $SrIndex = $Str;
        $ExcelData = [];
        for($i = $Str ;$i<= $End;$i++){        
            $Index =  str_pad($i,$Info->SRNO,0,STR_PAD_LEFT);
            $Main['$[SRNO]'] = $Index;
            // $Main['SRNO'] = $Index;
            $ReplaceData = $this->ReplaceData($File,$Main,$SrIndex,$Info->SRNO); 

          
            $SrIndex = empty($ReplaceData['Srno'])? $i : $ReplaceData['Srno'] - 1;
            $NewFile .= $ReplaceData['Data']; 
            $ExcelData[] = $Main;
            if($this->user_id == "1"){
                // echo "  INdexEnd: {$SrIndex} Start = {$Str} End {$End} Count {$Sr} Qty: {$Qty}";
                // echo "Pre: {$Pre}";
                // echo "<pre>";
                // print_r ($Main);
                // echo "PRN DATA";
                // print_r($ReplaceData);
                // echo "</pre>";
                
                // exit;
            }
            for($j = $i;$j<$SrIndex;$j++){
                $Index =  str_pad($j,$Info->SRNO,0,STR_PAD_LEFT);
                $QMID = $this->CT->QtyBatch([
                    "CMID" => $CMID,
                    "Qty" => $Index,
                    "CBID" => $CBID
                ]);
                
                $NewData = [];
                foreach ($Main as $key => $row) {
                    $NewData[] = [
                        "CBID" => $CBID,
                        "QMID" => $QMID,
                        "MyKey" => $key,
                        "MyData" => $row
                    ];
                }
                $this->CT->BatchDetail($NewData);
            }
            // $this->ReadPRN($File);
        }
        $excelFilePath = $this->CreateExcel($ExcelData,$Info,$TypeOfLabel); # For generate Excel file 
        $NewFile = $this->SetBrightness($NewFile,$Level);
        $RegState = strpos($NewFile,'®');
        if($RegState !== FALSE){
            $NewFile = str_replace('®','_A9',$NewFile);
            $NewFile = str_replace('^FH','^FH_',$NewFile);
        }
        $Res = true;
        $Base = 'PrintData/'.date('Y').'/'.date('M').'/'.date('d');
        is_dir('PrintData')?:mkdir('PrintData');
        is_dir('PrintData/'.date('Y'))?:mkdir('PrintData/'.date('Y'));
        is_dir('PrintData/'.date('Y').'/'.date('M'))?:mkdir('PrintData/'.date('Y').'/'.date('M'));
        is_dir($Base)?:mkdir($Base);
        $Path = $Base.'/'.$CBID.'.prn';
        $PRN = fopen($Path,"w");
        fwrite($PRN,$NewFile);
        fclose($PRN);
        $this->CT->UpdateFile($CBID,$Path,$State = 1);
        $Content = file_get_contents($Path);
        echo json_encode([
            'File' => $Content,
            'Res' => 'true',
            'Msg' => 'SUCCESS',
            'Excel' => $excelFilePath
        ]);
    }else{
        echo json_encode([
            'File' => 'none',
            'Res' => 'false',
            'Msg' => 'File Not Found',
            'Excel' => null
        ]);
    }
    // $this->ReadPRN($File);
    // redirect("Consignee/ViewFile/{$CBID}");
}
/*end 15072019
*/   

public function SetSerialNo($Info,$CMID,$isResetSerial, $PID){
    $Pre = 0;
    // echo "<pre>info : "; print_r($Info);exit;
    
    if($Info->AutoSR == "1" || $isResetSerial){
        $MaxQty = $this->CT->GetQty($CMID);
        // echo "<pre> db"; print_r($this->db->last_query()); exit;
        if($MaxQty->num_rows() > 0){
            $Row = $MaxQty->row();
            $Pre = $Row->Maxi;
            $CurrYear = date('Y'); $LastQtyYear = date('Y',strtotime($Row->DATE));
            $Pre = ($CurrYear == $LastQtyYear)? $Row->Maxi : 0;

        }
    }

    else if($Info->AutoSR == "6"  ){
        $MaxQty = $this->CT->GetQty($CMID, $PID);
        // echo "<pre> db"; print_r($this->db->last_query()); exit;
        if($MaxQty->num_rows() > 0){
            $Row = $MaxQty->row();
            $Pre = $Row->Maxi;
                $CurrDate = date('Y-m'); $LastQtyDate = date('Y-m',strtotime($Row->DATE));
                $Pre = ($CurrDate == $LastQtyDate)? $Row->Maxi : 0;
        }
    }

    
    // else if($Info->AutoSR == "3"){
    //     if($MaxQty->num_rows() > 0){
    //         $Row = $MaxQty->row();
    //         $Pre = $Row->Maxi;
    //         $CurrDate = date('Y-m-d'); $LastQtyDate = date('Y-m-d',strtotime($Row->DATE));
    //         $Pre = ($CurrDate == $LastQtyDate)? $Row->Maxi : 0;
    //     }
    // }
    else if($Info->AutoSR == "4"  ){
        $MaxQty = $this->CT->GetQty($CMID, $PID);
        // echo "<pre> CMID :"; print_r($CMID);
        // echo "<pre> PID :"; print_r($PID);
        // echo "<pre> data :"; print_r($MaxQty->row()); 
        // echo "<pre> db :"; print_r($this->db->last_query());
        if($MaxQty->num_rows() > 0){
            $Row = $MaxQty->row();
            $Pre = $Row->Maxi;
           // echo "<pre> Date :"; print_r($Row->DATE);
                $CurrDate = date('Y-m-d'); $LastQtyDate = date('Y-m-d',strtotime($Row->DATE));
                // echo "<pre> CurrDate :"; print_r($CurrDate); exit;
                $Pre = ($CurrDate != $LastQtyDate && (date('H:i') >= date('H:i',strtotime('00:00'))) )?  0 : $Row->Maxi;
            
        }
    }
    else if($Info->AutoSR == "7"  ){
        // echo "<pre> date : "; print_r(date('Y/w')); exit;
        $MaxQty = $this->CT->GetQty($CMID, $PID);
        // echo "<pre> 7 data : "; print_r($MaxQty->result());
        // echo "<pre>"; print_r($this->db->last_query());exit;
        if($MaxQty->num_rows() > 0){
            $Row = $MaxQty->row();
            $Pre = $Row->Maxi;
            // echo "<pre> Maxi : "; print_r($Row->Maxi);
            // echo "<pre> RowDate : "; print_r($Row->DATE);
                $CurrDate = date('Y/w'); 
                // echo "<pre> CurrDate : "; print_r($CurrDate);
                $LastQtyDate = date('Y/w',strtotime($Row->DATE));
                // echo "<pre> LastQtyDate : "; print_r($LastQtyDate);
                $Pre = ($CurrDate == $LastQtyDate)? $Row->Maxi : 0;
            // echo "<pre> week : "; print_r($Pre);exit;
        }
    }
    else if($Info->AutoSR == "2"){
        $MaxQty = $this->CT->GetQty($CMID, $PID);
        if($MaxQty->num_rows() > 0){
            $Row = $MaxQty->row();
            $Pre = $Row->Maxi;
            $CurrDate = date('Y-m-d'); $LastQtyDate = date('Y-m-d',strtotime($Row->DATE));
            $Pre = ($CurrDate != $LastQtyDate && (date('H:i') >= date('H:i',strtotime('06:30'))) )?  0 : $Row->Maxi;
           
        }
    }
    else if($Info->AutoSR == "5"){
        $MaxQty = $this->CT->GetQty($CMID,$PID);
        // echo "<pre> db"; print_r($this->db->last_query()); exit;
        if($MaxQty->num_rows() > 0){
            $Row = $MaxQty->row();
            $Pre = $Row->Maxi;
            if($isResetSerial){
                $CurrYear = date('Y'); $LastQtyYear = date('Y',strtotime($Row->DATE));
                $Pre = ($CurrYear == $LastQtyYear)? $Row->Maxi : 0;
            }
        }
    }
    return $Pre;
}
/**
 End 31-12-19
 */

    public function SetDarkness(){ 
        $CMID = $this->input->post("Consignee");
        $Path = $this->GetFileDemo($CMID,true);
        $FileData = file_get_contents($Path);
        echo '\nDarkness:'.$No = $this->input->post("darkness");
        echo '\nPosition:'.$Pos = strpos($FileData,'~SD');
        echo '\nReplace Val:'.$Repl = "~SD{$No}";
        echo '\nreplace:'.$NewFile = substr_replace($FileData,$Repl,$Pos,5);
        echo '\nNew File:'.$New = file_put_contents($Path,$NewFile);
            
    }
    public function ViewFile($CBID=null){
        $CBID = empty($CBID)? $this->input->post("Batch",true) : $CBID;
        $Info = $this->CT->GetBatchInfo($CBID);
        if($Info->num_rows() > 0){
            $Info = $Info->row();
            for($i=0;$i<=$Info->Quantity;$i++){
                $File = "data:image/png;base64,".base64_encode($this->ReadPRN(file_get_contents($Info->Path),$i));
                ?>
                    <img src="<?= $File?>" alt="">
                <?php
            }
        }
    }
    public function RePrint(){
        $Batches = $this->CT->GetBatches($this->user_id);
        $this->Support("Batches/RePrint",[
            "Batch" => $Batches
        ]);
    }
    public function GetBatchQty(){
        $CBID = $this->input->post('Batch',true);
        $Qtys = $this->CT->GetBatchQty($CBID,$this->user_id);
        foreach ($Qtys->result() as $key => $row) {
            ?>
                <option value="<?= $row->QMID?>"><?= $row->Qty?></option>
            <?php
        }
    }
    public function GetFileDemo($CMID = null,$Getarray=null){
        $Path = null;
        if($CMID == null){
            $CMID = $this->input->post("Consignee");
        }
        $PRNFile = $this->CT->GetFile($CMID); $Res = false;
        if($PRNFile->num_rows()>0 && is_file(FCPATH.$PRNFile->row()->Path)){
            $Path = FCPATH.$PRNFile->row()->Path;
            $File = file_get_contents(FCPATH.$PRNFile->row()->Path);
            $File = "data:image/png;base64,".base64_encode($this->ReadPRN($File));
            $Res = true;
           // print_r($File);exit;
        }else{
            $File = "File Not Found";
        }
        if($Getarray == null){
            echo json_encode(array(
                "Res" => $Res,
                "File" => $File,
            ));
        }
        else{
            return $Path;
        }
        
    }
    public function SetBrightness($NewFile,$Level = null){
        $index = 0;
        $Level = empty($Level)? (substr($NewFile,(strpos($NewFile,'~SD',$index) + 3),2)) : $Level;
        Bri:
        $Start = strpos($NewFile,'~SD',$index);
        $Br = "~SD{$Level}";
        $NewFile = substr_replace($NewFile,$Br,$Start,5);
        $index++;
        if((strpos($NewFile,'~SD',$index)) != false){
            goto Bri;
        }
        return $NewFile;
    }

    

    private function CreateExcel(Array $excelData ,$consigneeInfo = null,$isLogo=null){
        if(count($excelData) > 0){
            $dir = $this->GetDirectory('ExcelFiles');
            $spreadSheet = new Spreadsheet();
            $sheet = $spreadSheet->getActiveSheet();
            $header = array_keys(current($excelData));
            foreach($header as $key => $title){
                $title = str_replace(
                    [
                        '$[',
                        ']'
                    ],
                    [
                        '',
                        ''
                    ],
                    $title
                );
                $sheet->setCellValueByColumnAndRow($key + 1, 1 , $title);
            }
            $rowIndex = 1;
            foreach($excelData as $excelRows){
                $colIndex = 1;$rowIndex++;
                foreach($excelRows as $val){ 
                    $sheet->setCellValueByColumnAndRow($colIndex++,$rowIndex, $val);    
                }
            }
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadSheet);
            $consigneeInfo->Name = ($isLogo)? "{$consigneeInfo->Name}_logo" : $consigneeInfo->Name;
            $fileName = "{$dir}/{$consigneeInfo->Name}.xls";
            $writer->save($fileName);
            return base_url($fileName);
        }
    }


    public function GetDirectory($mainFolderName){
        $Base = "{$mainFolderName}/".date('Y').'/'.date('M').'/'.date('d');
        is_dir("{$mainFolderName}")?:mkdir("{$mainFolderName}");
        is_dir("{$mainFolderName}/".date('Y'))?:mkdir("{$mainFolderName}/".date('Y'));
        is_dir("{$mainFolderName}/".date('Y').'/'.date('M'))?:mkdir("{$mainFolderName}/".date('Y').'/'.date('M'));
        is_dir($Base)?:mkdir($Base);
        return $Base;
    }

    public function GetBatchDetailsReport(){
        
        $ConsigneeID = $this->input->post('Consignee',true);
        $ProductName = $this->input->post('Product',true);

       $fromdate = $this->input->post('fromdate',true);
        $todate = $this->input->post('todate',true);
        $comp = array("Get_Report","","$ConsigneeID","$ProductName","$fromdate","$todate");
        $res = $this->CT->STP_BatchReport($comp); 

        if($res->num_rows() > 0){
            $c = 1;
            foreach ($res->result() as $key) {
                ?>
                <tr>
                    <td><?= $c; ?></td>
                    <td><?= $key->batchID; ?></td>
                    <td><?= $key->consigneename; ?></td>
                    <td><?= $key->locationname; ?></td>
                    <td><?= $key->productcode; ?></td>
                    <td><?= $key->vccode; ?></td>
                    <td><?= $key->Rev; ?></td>
                    <td><?= $key->mmyy; ?></td>
              
                    <td><?= $key->Srno; ?></td>
                    <td><?= $key->GeneratedAt; ?></td>
                    <td><?= $key->BarcodeData; ?></td>
                    
                </tr>
            <?php
                $c++;
                
            }
        }
       
    }



    public function PrintBatch(){
       
       
        $loc = $this->getLocation($this->user_id);
        $shift = $this->CT->getShift();
        $Prd = $this->CT->GetProduct(array(
            "GET",$this->user_id,0,0
        ));

        //@@ 26092019
        // $Consignee = $this->CT->GetConsignee(array(
        //     $this->user_id
        // ));
        //$Consignee = $this->CT->GetConsignee($this->user_id);
        $Consignee  = $this->CT->GetProduct(array('GetCMID_Consignee','',46,""));
        $Printer = $this->CT->GetPrinter();
        $Data = array(
            "Products" => $Prd,
            "Consignee" => $Consignee,
            "title" => "Create Batch",
            "Printer" => $Printer,
            'location' => $loc,
            'UserRole' => $this->role,
            // 'shift' => $shift
        );
       
       
        $this->Support("Batches/PrintBatch",$Data);
    }

        public function GetPrintBatchInfo(){
            $Consignee = $this->input->post("Consignee",true);
            $Products = $this->CT->GetProduct(array('GetExcelBy_Consignee','',$Consignee,''));

            //$PrdCode = "";
            ob_start();
            foreach ($Products->result() as $key => $row) {
                ?>
                <option value="<?= $row->Path;?>"><?= $row->Path?></option>
                <?php
            }
            $PrdCode = ob_get_clean();
            
            echo json_encode([
                'PrdCode' => $PrdCode
            ]);
        }

        public function GetPrintBatchDetails(){
            $rawData = '';
            $ExcelPath = $this->input->Post("Product",true);
            $Consignee = $this->input->Post("Consignee",true);
            $HtmlPath = $this->CT->GetProduct(array('GetHtmlFile','',$Consignee,""));
            $rawData="";
            if($HtmlPath->num_rows() > 0 && is_file(FCPATH.$HtmlPath->row()->PATH)){
                
                $Main['$[image]'] = base_url($HtmlPath->row()->Image);
                $Main['<img'] = '<img onerror="cust_rm(this)"';
                $File = FCPATH.$HtmlPath->row()->PATH;
                $File = file_get_contents($File);
        
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load(FCPATH.$ExcelPath);
                $worksheet = $spreadsheet->getActiveSheet();

                // foreach($object->getWorksheetIterator() as $worksheet){
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumn = ord($highestColumn)-64;

        
                for($row=2; $row<=$highestRow; $row++){
                    //Getting Field Values from Excel
                    // $Pname[] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    // $Pcode[] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $productname = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $PARTNO = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $AssetCode = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $Description = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $BUNAME = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $companyCode = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $CapDate = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $strval_Description =  substr(trim($Description),0,36);
                    $CapDate = date('Y-m-d', Date::excelToTimestamp($CapDate));
                    $rawData = str_replace([
                            '$[BUNAME]','$[Description]','$[CapDT]','$[AssetCode]','$[CompanyCode]'
                        ],[
                            $BUNAME,$strval_Description,$CapDate,$PARTNO,$companyCode
                        ],
                        $File
                    );
                
                }
                            //print_r($Pname);exit;
                        // }
                    // $Prd='';
                    // $prdCon = $this->CT->GetProduct(array('Getproduct_by_CMID','',$Consignee,""));
                        // print_r($Pname);exit;
                        // if($prdCon->num_rows() > 0){
                        //     foreach($prdCon->result() as $prd){
                        //         if (in_array($prd->productname, $Pname) ){
                                        
                        //                 $Prd = $this->GetProductLabel($prd->CMID,$prd->id);
                        //                 $Consignee = $this->GetLabelInTable($prd->CMID,"Safe"); 
                        //         }else{
                        //             echo "error";
                        //         }
                                
                            
                        //     }
                        // }
                    
        
                        $Res = true;
            }else{
                $File = "File Not Found";$Res = false;
            }
            echo json_encode(array(
                "Res" => $Res,
                "File" => $rawData,
            ));
            //}
        }

        public function PrintBarcodeLabel(){
            $rawData = '';
            $ExcelPath = $this->input->Post("Product",true);
            $Consignee = $this->input->Post("Consignee",true);
            $PrnPath = $this->CT->GetProduct(array('GetPrnFile','',$Consignee,""));
            $row = $PrnPath->row();
            $Path = ($PrnPath->num_rows() > 0) ? $row->Path : '';
            $File = file_get_contents($Path);
            if(empty($ExcelPath)){
                echo "false";
                exit;
            }
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load(FCPATH.$ExcelPath);
            // $spreadsheet = $reader->load($ExcelPath);
            $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumn = ord($highestColumn)-64;
                for($row=2; $row<=$highestRow; $row++){
                    //Getting Field Values from Excel
                    $productname = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $PARTNO = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $AssetCode = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $Description = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $BUNAME = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $companyCode = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $CapDate = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $CapDate = date('Y-m-d', Date::excelToTimestamp($CapDate));
                    $strval_Description =  substr(trim($Description),0,36);

                
                    $rawData .= str_replace([
                        '$[BUNAME]','$[Description]','$[CapDT]','$[AssetCode]','$[CompanyCode]'
                    ],[
                        $BUNAME,$strval_Description,$CapDate,$PARTNO,$companyCode
                    ],
                    $File
                );
                    //$PrnFile = "data:image/png;base64,".base64_encode($this->ReadPRN($dat3));
                }

            //}
            // $prdCon = $this->CT->GetProduct(array('Getproduct_by_CMID','',$Consignee,""));
            // if($prdCon->num_rows() > 0){
                // foreach($prdCon->result() as $prd){
                //     if(in_array($prd->ProductName, $Pname)){
                //         $PrdID = $prd->PID;
                //         $Consignee = $prd->CMID;
                //         $PrinterName = $this->PC->GetPrinter($Printer);
                //         $PrintInfo = $PrinterName->row();
                //         $PRID = $PrintInfo->PRID;
                //         $CBID = $this->PC->GetBatch('INS','', $Consignee, $PrdID,1,$PRID,'','',$this->user_id);
                //         $row = $CBID->row();
                //         $CBID = ($CBID->num_rows() > 0) ? $row->id : '';
                //         $QMID = $this->PC->GetBatch('INS_Qty','', $CMID, $Index,$CBID);
                //         $row = $QMID->row();
                //         $QMID = ($QMID->num_rows() > 0) ? $row->QtyId : '';
                //     }else{
                //         echo "error";
                //     } 
                // }
            // }
                //print_r($dat3);exit;
                //if(!empty($rawData)) {

                    echo json_encode(array(
                        "PrnFile" => $rawData,
                        "Status" => True
                    ));
                // }else{
                //     echo "false";
                // }
        }
    }
?>