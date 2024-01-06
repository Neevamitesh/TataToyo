<?php
    class AddOnsTran extends CI_Model{
        public function AddTitle($Data){
            $this->db->insert('AddOns', $Data);
            return $this->db->affected_rows();
        }
        public function GetTitle($CMID = null){
            if(!empty($CMID)){
                $this->db->select("* , IF(ConsigneeAddOns.CMID = $CMID,'yes','no') as Sel,AddOns.AOID as AOID, ConsigneeAddOns.CMID as CMID");
                $this->db->join('ConsigneeAddOns', 'ConsigneeAddOns.AOID = AddOns.AOID AND ConsigneeAddOns.BitStatus = 1','left');
                $this->db->join('ConsigneeMaster',"ConsigneeMaster.CMID = ConsigneeAddOns.CMID AND ConsigneeMaster.isactive = 1",'left');
                $this->db->group_by('AddOns.AOID');
                // $this->db->where('ConsigneeAddOns.CMID', $CMID);
            }
            return $this->db->get_where("AddOns",[
                "AddOns.BitStatus" => 1
            ]);
        }
        public function GetOptions(){
            $this->db->join("AddOnsDetail","AddOnsDetail.AOID = AddOns.AOID AND AddOnsDetail.BitStatus = 1");
            return $this->db->get_where("AddOns",[
                "AddOns.BitStatus" => 1
            ]);
        }
        public function AddData($Data){
            $this->db->insert('AddOnsDetail', $Data);
            return $this->db->affected_rows();
        }
        public function AddDate($Data){
            $this->db->insert('MyDate', $Data);
            return $this->db->affected_rows();
        }
        public function GetDates(){
            return $this->db->get_where("MyDate",[
                "BitStatus" => 1
            ]);
        }
        public function DeleteDate($MDID){
            $this->db->update('MyDate', [
                "BitStatus" => 0
            ],[
                "MDID" => $MDID
            ]);
            return $this->db->affected_rows();
        }
        public function DeleteTitle($AOID){
            $this->db->update('AddOns', [
                "BitStatus" => 0
            ],[
                "AOID" => $AOID
            ]);
            return $this->db->affected_rows();
        }
        public function DeleteOption($ADID){
            $this->db->update('AddOnsDetail', [
                "BitStatus" => 0
            ],[
                "ADID" => $ADID
            ]);
            return $this->db->affected_rows();
        }
        public function EditTitle($Data,$AOID){
            $this->db->update('AddOns',$Data,[
                "AOID" => $AOID,
                "BitStatus" => 1
            ]);
            return $this->db->affected_rows();
        }
        public function EditDate($Data,$MDID){
            $this->db->update('MyDate',$Data,[
                "MDID" => $MDID,
                "BitStatus" => 1
            ]);
            return $this->db->affected_rows();
        }
        public function EditData($Data,$ADID){
            $this->db->update('AddOnsDetail',$Data,[
                "ADID" => $ADID,
                "BitStatus" => 1
            ]);
            return $this->db->affected_rows();
        }
    }
?>