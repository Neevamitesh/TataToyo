<?php
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
            !empty($this->user_id)?:redirect("Admin");
            $this->load->model("GetUserData","GD");
            $this->load->model("ConTran","CT");
            $this->load->model("AddOnsTran","AT");
            date_default_timezone_set("Asia/Kolkata");
            $this->DateTime = date('Y-m-d H:i:s');
            $this->load->model("PreTran","PT");
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
            $data = array(
                "States" => $States,
                "Type"=> $LabelType,
                "title"=> "Add Consignee",
                "Options" => $Options,
                "Dates" => $Dates
            );
            $this->Support("Consignee/Add",$data);   
        }

        public function Edit(){
            $Consignee = $this->GetParam();
            $Consignee = $this->CT->Consignee($Consignee);
            $States = $this->GD->GetStates();
            $LabelType = $this->GD->GetType();
            $Options = $this->AT->GetTitle();
            $Dates = $this->AT->GetDates(); 
            $data = array(
                "States" => $States,
                "Type"=> $LabelType,
                "Consignee" => $Consignee,
                "Dates" => $Dates
            );
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
            if(count($LabelType) == count($Detail)){
                $Master = $this->GetParam("INS");
                $CMID = $this->CT->Consignee($Master)->row()->CMID;
                $Data = [];
                if(count($LabelType) > 0){
                    foreach($LabelType as $key=>$Title){
                        $this->CT->Lables(array(
                            "INS",0,$CMID,$Title,$Detail[$key]
                        ));
                    }
                }
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
            $SRAuto = ($this->input->post('SrAuto',true) == 'yes') ? 1 : 0;
            return $Master = array(
                $Type,$ID,$Code,$Name,$Address,
                $Contact,$City,$State,$PinCode,"India",
                $MDID,$SRNO,$SRAuto,$this->user_id,"NULL",
                "NULL","NULL","NULL","NULL","NULL",
            );
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
            $Data = array(
                "Name" => $Result->consigneename,
                "Code" => $Result->consigneecode,
                "Contact" => $Result->mobileno,
                "Address" => $Result->address,
                "State" => $Result->state,
                "City" => $City,
                "PinCode" => $Result->pincode,
                "Label" => $Labels,
                "Ref" =>$Result->CMID,
                "Len" => $Result->SRNO,
                "SRMain"=> ($Result->AutoSR=="1")?'yes':'no',
                "AddOpt" => $AddOpt
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
        public function GetBatchInfo(){
            $Consignee = $this->input->post("Consignee",true);
            $Products = $this->CT->GetProducts($Consignee,$this->user_id);
            ob_start();
            foreach ($Products->result() as $key => $row) {
                ?>
                <option data-code="<?= $row->id?>" data-name="<?= $row->productname?>" value="<?= $row->id?>"><?= $row->productname?></option>
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
$Time =  strtotime(date("H:i"));
$Shit = 3;
if($Time >=  strtotime('6:00') && $Time <= strtotime('14:59')){
    $Shit = 1;
}else if($Time >= strtotime('15:00') && $Time <= strtotime('23:30')){
    $Shit = 2;
}


foreach ($NewAddOns as $key => $row) {
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

# @@ End


            $NewAddOns = ob_get_clean();
            echo json_encode([
                "Prd" => $Products,
                "Addons"=> $NewAddOns
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
            $Upd = $this->input->post("Update",true);
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
                "INS",0,$CMID,$Path
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
        public function CreateBatch(){
            $Prd = $this->CT->GetProduct(array(
                "GET",$this->user_id,0,0
            ));
            $Consignee = $this->CT->GetConsignee($this->user_id);
            $Printer = $this->CT->GetPrinter();
            $Data = array(
                "Products" => $Prd,
                "Consignee" => $Consignee,
                "title" => "Create Batch",
                "Printer" => $Printer
            );
            $this->Support("Batches/CreateBatch",$Data);
        }
        public function GetBatchDetails(){
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

                $File = FCPATH.$PRNFile->row()->Path;
                $File = $this->ReplaceData($File,$Main);
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
            // echo "<pre>";
            //     print_r($Info);
            // echo "</pre>";
            $Res = false;$Msg = $this->Msg["Msg"];
            $Opt = $this->input->post("Opt",true); 
            $Prd = $this->CT->GetProduct(array(
                "GETWithLB " ,$this->user_id , $CMID , $PID
            ));
            $Main = [];
            $Main['$[Rev]'] = "   ";
            $Date = ($Info->isReverse == "1")? strrev(date($Info->Data)) :(date($Info->Data));
            $Main['$[DATE]'] = $Date;
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
                }else{
                    $Main['$['.trim($row->KeyName).']'] = trim($row->ValueData);
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
                $Str = (1+$Pre);
                $End = ($Qty + $Pre);
               
                $Index =  str_pad($Str,$Info->SRNO,0,STR_PAD_LEFT);
                $Main['$[SRNO]'] = $Index;
                // $Main['SRNO'] = $Index;
            return $Main;
        }

        /*start 15072019
*/
public function PrintLable(){
    $CMID = $this->input->post("Consignee",true);
    $PID = $this->input->post("Product",true);
    $Printer = $this->input->post("Printer",true);
    $Level = $this->input->post("Level",true);
    $Param = $this->GetParam("GET",$CMID);   
    $Info = $this->CT->Consignee($Param);
    $PrinterName = $this->CT->GetPrinter($Printer);
    
    $Info = $Info->row();
    $PrintInfo = $PrinterName->row();
    $PRID = $PrintInfo->PRID;
    $Qty = $this->input->post("Qty",true); $Res = false;$Msg = $this->Msg["Msg"];
    $Opt = $this->input->post("AddOns",true); 
    $Prd = $this->CT->GetProduct(array(
        "GETWithLB " ,$this->user_id , $CMID , $PID
    ));
    $Param = array(
        "GET",0,$CMID,0,"Null"
    );
    $Labels =  $this->CT->Lables($Param);
    $AddOns = (empty($Opt))?[]:$this->CT->GetAddOns($CMID,$Opt);
    $Main = [];
    $Date = ($Info->isReverse == "1")? strrev(date($Info->Data)) :(date($Info->Data));
    $Main['$[DATE]'] = $Date;
    
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
        }else{
            // $row->ValueData = str_replace('®','©',$row->ValueData);
            $Main['$['.trim($row->KeyName).']'] = trim($row->ValueData);
            // $Main[trim($row->KeyName)] = trim($row->ValueData);   
        }
    }
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
   
    $PRNFile = ($TypeOfLabel)? $this->CT->GetAdditionalLabel($CMID) : $this->CT->GetProduct(array(
        "GetPrnFile",$this->user_id,$CMID,0
    ));
    $NewFile = ''; 
    
  
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
        /*start 08072019*/
        $Sr = substr_count(file_get_contents($File),'$[SRNO]');
        $Qty = ($Sr > 1)? ceil($Qty / $Sr ) : $Qty;
        $Qty = ($Qty > 0 )? $Qty : 1;
        /*end*/
        $Str = (1+$Pre);
        $End = ($Qty + $Pre); $SrIndex = $Str;
        for($i = $Str ;$i<= $End;$i++){        
            $Index =  str_pad($i,$Info->SRNO,0,STR_PAD_LEFT);
            $Main['$[SRNO]'] = $Index;
            // $Main['SRNO'] = $Index;
            $QMID = $this->CT->QtyBatch([
                "CMID" => $CMID,
                "Qty" => $Index,
                "CBID" => $CBID
            ]);
            $ReplaceData = $this->ReplaceData($File,$Main,$SrIndex,$Info->SRNO); 
            $NewFile .= $ReplaceData['Data'];
            $SrIndex = empty($ReplaceData['Srno'])? $i : $ReplaceData['Srno'] - 1;
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
            // $this->ReadPRN($File);
        }
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
            'Msg' => 'SUCCESS'
        ]);
    }else{
        echo json_encode([
            'File' => 'none',
            'Res' => 'false',
            'Msg' => 'File Not Found'
        ]);
    }
    // $this->ReadPRN($File);
    // redirect("Consignee/ViewFile/{$CBID}");
}
/*end 15072019
*/        
        
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
        public function GetFileDemo(){
            $CMID = $this->input->post("Consignee");
            $PRNFile = $this->CT->GetFile($CMID); $Res = false;
            if($PRNFile->num_rows()>0 && is_file(FCPATH.$PRNFile->row()->Path)){
                $File = file_get_contents(FCPATH.$PRNFile->row()->Path);
                $File = "data:image/png;base64,".base64_encode($this->ReadPRN($File));
                $Res = true;
            }else{
                $File = "File Not Found";
            }
            echo json_encode(array(
                "Res" => $Res,
                "File" => $File,
            ));
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
    }
?>