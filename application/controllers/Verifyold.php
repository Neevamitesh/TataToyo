<?php
    class Verify extends CI_Controller{
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
            $this->load->view("NavFoot/navbar",$data);
            $this->load->view($view);
            $this->load->view("NavFoot/footer");
        }

        public function index(){
            $Prd = $this->CT->GetProduct(array(
                "GET",$this->user_id,0,0
            ));
            $Consignee = $this->CT->GetConsignee(array(
                $this->user_id
            ));
            $Data = array(
                "Products" => $Prd,
                "Consignee" => $Consignee,
                "title" => "",
                'UserRole' => $this->role,
            );
            $this->Support("Batches/Verify",$Data);
        }   

        public function GetData(){
            // $Check = "T764330934[DATE][SHT][SRNO]";
            // $inputscan = "T7643309340722100005"; 
            $ConsigneeID =  $this->input->post('Consignee',true);
            $ProductID =  $this->input->post('productid',true);
            $ScanData = $this->input->post('ScanData',true);
            $Date = $this->input->post('Date',true);
            $Opt = $this->input->post("AddOns",true); 
            $Param = $this->GetParam("GET",$ConsigneeID);   
            $Info = $this->CT->Consignee($Param);
            $Check = 0;
            $param = array("Get",'',"","",$ScanData,'','','');
            $VerifyRes = $this->CT->VerifyBarcode($param);
            
            if($VerifyRes->num_rows() > 0){
                $File = "";$msg = "Barcode already scan and saved";$status = false;        
            }else{
                
                if($Info->num_rows() > 0){
                    $conrow = $Info->row();
                    $SrnoLength = $conrow->SRNO;
                    $AddOns = (empty($Opt))?[]:$this->CT->GetAddOns($ConsigneeID,$Opt);
                    $Prd = $this->CT->GetProduct(array(
                        "GETWithLB " ,$this->user_id , $ConsigneeID , $ProductID
                    ));  
                    if(!empty($AddOns)){
                        foreach ($AddOns->result() as $key => $row) {
                            $Main['['.trim($row->Title).']'] = trim($row->Data);
                        }
                    }
                    if($Prd->num_rows() > 0){
                        foreach ($Prd->result() as $key => $row) {
                            $Main['['.trim($row->KeyName).']'] = trim($row->ValueData);
                            if(trim($row->KeyName) == 'DATE'){
                                // $isReverse = array_search('isReverse',array_column($Prd->result(),'KeyName'));
                                // $isReverse = ($isReverse > 0)? $Prd->result()[$isReverse]->ValueData : 0;
                                // $ConDate = ($isReverse == "1")? strrev(date($row->ValueData,strtotime($Date))) :(date($row->ValueData,strtotime($Date)));
                                 $Main['[DATE]'] = date($row->ValueData,strtotime($Date)); 
                                //$Main['[DATE]'] =$ConDate;
                            }
                            if(trim($row->KeyName) == 'BarcodeData'){
                               $Check = $row->ValueData; 
                            }
                        }
                    }  
                   
                    $File = "";$msg = "please check Excel Barcodedata column";$status = false;        
                    if($Check != 0 ){
                        $Main['[SRNO]'] = '';
                        $Check = str_replace(
                            array_keys($Main),
                            array_values($Main),
                            $Check
                        );
                        $srnoStatus = (strlen(trim($ScanData)) - strlen(trim($Check))) == $SrnoLength;
                        $File = "";$msg = "please check srno length, $SrnoLength";$status = false;
                        if($srnoStatus){
                            $resCheck = substr($ScanData,0,strlen(trim($Check)));
                           
                            $isValid = $Check == $resCheck;
                            
                            $File = "";$msg = "MisMatch Data, please check barcode";$status = false;
                            if($isValid){
                                $TypeOfLabel = false;
                                if($Prd->num_rows() > 0){
                                     
                                    foreach ($Prd->result() as $key => $row) {
                                        if(trim($row->KeyName) == "Logo" && trim($row->ValueData) == 0){
                                            $TypeOfLabel = true;
                                        } 
                                    }
                                    // $PRNFi091:0304AAM01501N:DT011:100821:1:00user_id,$ConsigneeID,0
                                    // ));04
                                    $PRNFile = $this->GetPrnFile($ConsigneeID);
                                    if($PRNFile->num_rows() > 0 ){
                                        $Main['$[image]'] = ($TypeOfLabel)?:base_url($PRNFile->row()->Image);
                                        $Main['<img'] = '<img onerror="cust_rm(this)"';
                                        
                                        $Paths = $PRNFile->row()->Path;
                                        
                                        $ReplaceData = $this->ReplaceData($Paths,$Main,1,$SrnoLength); 
                                        $ReplaceData = str_replace(
                                            '$',
                                            '',
                                            $ReplaceData
                                        );
                                        $File .= $ReplaceData['Data'];
                                        $Result  = $this->ScanDataStore($ConsigneeID,$ProductID,$ScanData);
                                        if($Result == "Success"){
                                            $msg = "success";$status = true;
                                        }
                                    }
                                }
                            }
                        }
    
                    }
                }
            }
            echo json_encode([
                'File' => $File,
                'Res' => $status,
                'Msg' => $msg,
            ]);
        }
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

        private function GetPrnFile($CMID){
            return $this->PT->GetFile($CMID);
            // Temp
            return $this->CT->GetProduct(array(
                "GetPrnFile",$this->user_id,$CMID,0
            ));
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

        // private function GetPrnFile($CMID){
        //     return $this->PT->GetFile($CMID);
        //     // Temp
        //     return $this->CT->GetProduct(array(
        //         "GetPrnFile",$this->user_id,$CMID,0
        //     ));
        // }
        public function ScanDataStore($CMID = null,$PID = null,$ScanData = null){
            $param = array("INS",'',$CMID,$PID,$ScanData,$this->user_id,$this->DateTime,'');
            $VerifyRes = $this->CT->VerifyBarcode($param);
            if($VerifyRes->num_rows() > 0){
                $msg  = "Success";
            }
            return $msg;
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
            // $SRAuto = ($this->input->post('SrAuto',true) == 'yes') ? 1 : 0;
            $SRAutoVal = $this->input->post('SrAuto',true);
            //@@25092019
            $SRAuto = 0;
          
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
            return $Result;
        }
       
        public function BarcodeReport($ToDate = null,$FromDate = null){
            $param = array("GetBYDate",'','','','','',$ToDate,$FromDate);
            $VerifyRes = $this->CT->VerifyBarcode($param);
            $Data = array(
                "title" => "",
                'VerifyRes' => $VerifyRes,
                'ToDate' => $ToDate,
                'FromDate' => $FromDate
            );
            $this->Support("Batches/VerifyReport",$Data);
        }
        
        public function GetScanInfo(){
            $ScanData = $this->input->post('ScanData',true);
            $param = array("Get",'','','',$ScanData,'','','');
            $VerifyRes = $this->CT->VerifyBarcode($param);
            $ScanData ="";$PrdName ="";$PrdCode ="";
            $ScanBy ="";$ScanDate ="";$Consignee ="";
            $msg = "Please Scan Verified Barcode only";
            if($VerifyRes->num_rows() > 0){
                $row = $VerifyRes->row();
                $ScanData = $row->ScanData;
                $PrdName =  $row->productname;
                $PrdCode =  $row->productcode;
                $ScanDate = $row->scandate;
                $ScanBy = $row->username;
                $Consignee = $row->consigneename;  
                $msg = "Success";              
            }
            echo json_encode([
                'ScanData' => $ScanData,
                'PrdName' => $PrdName,
                'PrdCode' => $PrdCode,
                'ScanDate' => $ScanDate,
                'Consignee' => $Consignee,
                'ScanBy' => $ScanBy,
                'msg' => $msg
            ]);
        }
    }
?>