<?php
    class ConTran extends CI_Model{
        public function __construct(){
            $this->load->database();
        }
        private function Api($Name,$Param=[]){ 
            $Result = null;
            try{
                $Param = $this->FilterArray($Param);
                $Q = $this->QM($Param);
                $Result = $this->db->query("CALL {$Name}({$Q})",$Param);
                $Result->next_result();
            }catch(Exception $e){
                echo $e->getMessage();
            }
            return $Result;
        }
        private function QM($Array){
            $Q = [];
            for($i=0;$i<count($Array);$i++){
                $Q[] = "?";
            }
            return implode(",",$Q);
        }
        private function FilterArray($Array=[]){
            foreach ($Array as $key => $value) {
                if(empty($value)){
                    $Array[$key] = "null";
                }
            }
            return $Array;
        }
        public function Consignee($Data){
            return $this->Api("spconsigneemaster",$Data);
        }
        public function Lables($Data){
            return $this->Api("LabelVariables",$Data);
        }
        public function UploadFile($Data){
            return $this->Api("FileUpload",$Data);
        }
        public function GetProduct($Data){
            return $this->Api("ProductMaster",$Data);
        }
        public function GetPrinter($PID=null){
            empty($PID)?:$this->db->where("PRID",$PID,true);
            return $this->db->get_where("Printers");
        }
        public function AddOptions($Options,$CMID){
            $this->db->update('ConsigneeAddons', [
                'BitStatus' => 0
            ],[
                'CMID' => $CMID,
            ]);
            if(count($Options) > 0){
                $this->db->insert_batch("ConsigneeAddons",$Options);
            }
            return $this->db->affected_rows();
        }
        public function isConsignee($CMID,$user_id){
            return $this->db->get_where('ConsigneeMaster',[
                "CMID" => $CMID,
                "createdby" => $user_id
            ]);
        }
        // public function GetConsignee($user){
        //     return $this->db->get_where("ConsigneeMaster",[
        //         "isactive" => 1,
        //         // "createdby" => $user
        //     ]);
        // }

        //@@26092019
        public function GetConsignee($Data){
            return $this->Api("SP_ConsigneeMaster_LOC",$Data);
        }
        //@@ end
        public function GetProducts($CMID,$UID){
            // $this->db->select("ProductMaster.id, ProductMaster.CMID, ProductMaster.productcode, concat(ProductMaster.productname,' (',ProductMaster.productcode,')') productname, ProductMaster.productdescriptionone,consigneemaster.*");
            $this->db->join('ConsigneeMaster', 'ConsigneeMaster.CMID = ProductMaster.CMID AND ConsigneeMaster.isactive = 1');
            return $this->db->get_where('ProductMaster',[
                'ProductMaster.CMID' => $CMID,
                "ProductMaster.isactive" => 1,
                // "ConsigneeMaster.createdby" => $UID
            ]);
        }
        public function GetAddOns($CMID,$ADIDs = []){
            $this->db->join('AddOns', 'AddOns.AOID = ConsigneeAddOns.AOID');
            $this->db->join('AddOnsDetail', 'AddOnsDetail.AOID = AddOns.AOID');
            empty($ADIDs)?:$this->db->where_in('AddOnsDetail.ADID', $ADIDs);
            $this->db->order_by('AddOns.AOID');
            return $this->db->get_where("ConsigneeAddOns",[
                "ConsigneeAddOns.CMID" => $CMID,
                "ConsigneeAddOns.BitStatus" => 1
            ]);
        }
        // public function GetQty($CMID){
        //     $this->db->select('q.qmid,c.CreatedAt Date,Qty Maxi');
        //     $this->db->join('createbatch c','c.cbid = q.cbid');
        //     $this->db->order_by('q.qmid', 'desc');
        //     return $this->db->get_where('QtyMaster q',[
        //         'Q.CMID' => $CMID,
        //         "q.BitStatus" => 1 ,
        //         "c.BitStatus" => 1
        //     ],1);   
        // }
        public function tGetQty($CMID){
            $this->db->select('* , max(Qty) as Maxi');
            $this->db->join('CreateBatch','CreateBatch.CMID AND QtyMaster.CMID AND BitStatus = 1');
            return $this->db->get_where('QtyMaster',[
                'BitStatus' => 1,
                "CMID" => $CMID,
                "Status" => 1
            ]);   
        }

        public function GetQty($CMID, $PID = null){

            return $this->db->query('
            SELECT *,c.CreatedAt Date, Qty as Maxi  FROM qtymaster qm 
            INNER JOIN CreateBatch c on  qm.CBID = c.CBID
            where qmid = 
            (SELECT max(qmid) FROM qtymaster qm
            INNER JOIN CreateBatch cb on  qm.CBID = cb.CBID
            WHERE qm.BitStatus = 1 AND cb.CMID = ? AND (IF(ISNULL(?),TRUE,PID = ? ) )   )',[
                $CMID,$PID,$PID
            ]);
            $this->db->select('* ,CreateBatch.CreatedAt Date, Qty as Maxi');
            $this->db->join('CreateBatch','CreateBatch.CBID AND QtyMaster.CBID AND QtyMaster.BitStatus = 1');
            if(is_numeric($PID))$this->db->where('PID', $PID);
            $this->db->order_by('CreateBatch.CBID', 'desc');
            return $this->db->get_where('QtyMaster',[
                'QtyMaster.BitStatus' => 1,
                "CreateBatch.CMID" => $CMID,
                "CreateBatch.Status" => 1
            ], 1);   
        }

        // $this->db->select('* ,CreateBatch.CreatedAt Date, Qty as Maxi');
        //     $this->db->join('CreateBatch','CreateBatch.CBID AND QtyMaster.CBID AND QtyMaster.BitStatus = 1');
        //     if(is_numeric($PID))$this->db->where('PID', $PID);
        //     $this->db->order_by('CreateBatch.CBID', 'desc');
        //     return $this->db->get_where('QtyMaster',[
        //         'QtyMaster.BitStatus' => 1,
        //         "CreateBatch.CMID" => $CMID,
        //         "CreateBatch.Status" => 1
        //     ], 1);   
        
        public function CreateBatch($Data){
            $this->db->insert('CreateBatch', $Data);
            return $this->db->insert_id();
        }
        public function QtyBatch($Data){
            $this->db->insert('QtyMaster', $Data);
            return $this->db->insert_id();
        }
        public function BatchDetail($Data){
            $this->db->insert_batch("BatchDetails",$Data);
        }
        public function GetBatchInfo($CBID){
            return $this->db->get_where('CreateBatch',[
                'BitStatus' => 1,
                'CBID' => $CBID
            ]);
        }
        public function UpdateFile($CBID,$File,$State = 0){
            $this->db->update('CreateBatch', [
                'Path' => $File,
                'Status' => $State
            ],[
                'CBID' => $CBID,
            ]);
        }
        public function GetBatches($user_id){
            return $this->db->get_where('CreateBatch',[
                'BitStatus' => 1,
                'CreatedBy' => $user_id
            ]);
        }
        public function GetBatchQty($CBID,$user_id){
            $this->db->join('QtyMaster', 'QtyMaster.CBID = CreateBatch.CBID AND CreateBatch.BitStatus = 1'); 
            return $this->db->get_where('CreateBatch',[
                'CreateBatch.BitStatus' => 1,
                'CreateBatch.CreatedBy' => $user_id,
                "CreateBatch.CBID" => $CBID
            ]);
        }
        public function GetFile($CMID){
            $this->db->join('LabelFiles', 'LabelFiles.CMID = ConsigneeMaster.CMID');
            return $this->db->get_where('ConsigneeMaster',[
                "ConsigneeMaster.CMID" => $CMID,
                "ConsigneeMaster.isactive" => 1
            ]);
        }
        public function GetAdditionalLabel($CMID){
            return $this->db->get_where("AdditionalLabel",[
                "BitStatus" => 1,
                "CMID" => $CMID
            ]);
        }
        //@@ 26092019
        public function ExcelFile($Data){
            return $this->Api("ExcelUpload",$Data);
        }

        public function GetDownload($Data){
            return $this->Api("SP_GetDownload_Data",$Data);
        }
        //@@ end
        //221019
        public function AddLocation($Data){
            return $this->Api("P_Addlocation",$Data);
        }
        public function STP_Loc($Data){
            return $this->Api("STP_Location",$Data);
        }
//221019
    }
?>