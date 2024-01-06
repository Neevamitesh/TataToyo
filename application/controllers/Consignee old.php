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
        private $user_id,$Msg=array(
            "Msg"=>"Somwthing Went Wrong",
            "Res" => false
        );
        public function __construct(){
            parent::__construct();
            $this->load->model("GetUserData","GD");
            $this->load->model("ConTran","CT");
            $this->user_id = $this->session->userdata('admin_id');;
        }
        private function Support($view,$data=[]){
            $NavFoot = $this->GD->NavData();
            $data = array_merge_recursive($NavFoot,$data);
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
            $data = array(
                "States" => $States,
                "Type"=> $LabelType,
                "title"=> "Add Consignee"
            );
            $this->Support("Consignee/Add",$data);   
        }

        public function Edit(){
            $Consignee = $this->GetParam();
            $Consignee = $this->CT->Consignee($Consignee);
            $States = $this->GD->GetStates();
            $LabelType = $this->GD->GetType();
            $data = array(
                "States" => $States,
                "Type"=> $LabelType,
                "Consignee" => $Consignee
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
                foreach($LabelType as $key=>$Title){
                    $this->CT->Lables(array(
                        "INS",0,$CMID,$Title,$Detail[$key]
                    ));
                }
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

            return $Master = array(
                $Type,$ID,$Code,$Name,$Address,
                $Contact,$City,$State,$PinCode,"India",
                "NULL","NULL","NULL",$this->user_id,"NULL",
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
                "Ref" =>$Result->CMID
            );
            echo json_encode($Data);
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

        private function ReplaceData($Path,$Detail=[]){
            $NewFile = false;
            if(is_file($Path)){
                $File = file_get_contents($Path);
                $NewFile = str_replace(
                    array_keys($Detail),
                    array_values($Detail),
                    $File
                );    
            }
            return $NewFile;
        }
        private function ReadPRN($File){
            $ch = curl_init();
            $Link = "http://api.labelary.com/v1/printers/8dpmm/labels/4x6/0/";
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
            $Printer = $this->CT->GetPrinter();
            $Data = array(
                "Products" => $Prd,
                "title" => "Create Batch",
                "Printer" => $Printer
            );
            $this->Support("Batches/CreateBatch",$Data);
        }
        public function GetBatchDetails(){
            $PID = $this->input->Post("Prd",true);
            if(empty($PID)){
                echo "false";
                exit;
            }
            $info = $this->CT->GetProduct(array(
                "GETPrd",$this->user_id,0,$PID
            ))->row();
            $Prd = $this->GetProductLabel($info->CMID,$PID);
            $Consignee = $this->GetLabelInTable($info->CMID,"Safe");
            if(empty($Prd)){
                echo "false";
            }else{
                echo json_encode(array(
                    "Prd" => $Prd,
                    "Consignee" => $Consignee
                ));
            }
        }
        public function GetPreview(){
            $PrdTitle = $this->input->post("PrdTitle",true);
            $PrdValue = $this->input->post("PrdValue",true);
            $ConTitle = $this->input->post("ConTitle",true);
            $ConValue = $this->input->post("ConValue",true);
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
            if($Prd->num_rows() > 0){
                foreach ($Prd->result() as $key => $nrow) {
                    ?>
                    <tr class="main">
                        <td><?= $key+1?></td>
                        <td class="editable"><?= $nrow->KeyName;?></td>
                        <td class="editable"><?= $nrow->ValueData?></td>
                        <td>
                            <button type="button" class="btn btn-outline-danger cust-rm-t2 " title="Remove" title="remove">&times;</button>
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
                        <input type="hidden" name="Labs[]" value="<?= $nrow->LMID?>">
                        <input type="hidden" name="Consignee[]" value="<?= $nrow->CMID?>">
                        <td><?= $key+1?></td>
                        <td class="editable"><?= $nrow->Title;?></td>
                        <td class="editable"><?= $nrow->Detail?></td>
                        <td>
                            <button type="button" class="btn btn-outline-danger cust-rm-t1 " title="Remove" title="remove">&times;</button>
                        </td>
                    </tr>
                    <?php
                }
            }
            return ob_get_clean();
        }
        // public function PrintLable(){
        //     $PrdTitle = $this->input->post("PrdTitle",true);
        //     $PrdValue = $this->input->post("PrdVal",true);
        //     $ConTitle = $this->input->post("ConTitle",true);
        //     $ConValue = $this->input->post("ConVal",true);
        //     $Printer = $this->input->post("Printer",true);
        //     if(count($PrdTitle) == count($PrdValue) && count($ConTitle) == count($ConValue) && !empty($PID)){
        //         for($i=0;$i<count($PrdTitle);$i++){
        //             $Main[$PrdTitle[$i]] = $PrdValue[$i];
        //         }
        //         for ($i=0; $i < count($ConTitle); $i++) { 
        //             $Main[$ConTitle[$i]] = $ConValue[$i];
        //         }
        //         $PRNFile = $this->GetPrnFile($PID);
        //         if($PRNFile->num_rows()>0 && is_file(FCPATH.$PRNFile->row()->Path)){
        //             $File = FCPATH.$PRNFile->row()->Path;
        //             $File = $this->ReplaceData($File,$Main);
        //             $File = "data:image/png;base64,".base64_encode($this->ReadPRN($File));
        //             $Res = true;
        //         }else{
        //             $File = "File Not Found";
        //         }
        //     }else{
        //         $File = "Something went Wrong";
        //     }
        // }
    }
    
?>
