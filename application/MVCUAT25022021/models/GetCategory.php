<?php
    class GetCategory extends CI_Model{

        function __construct(){
            parent::__construct();
            $this->load->library('session');
            $this->load->database();
        }

        public function UploadCategory($data){
            return $this->db->insert("Category_Master", $data);
        }

        public function Category(){
            $this->db->order_by("CID", "desc");
            $result=$this->db->get_where('Category_Master', ["BitStatus" => 1]);
            return $result;
        }
        public function SearchCategory($keys){
            $this->db->select('*')->from('Category_Master')->where("Category_Name LIKE '$keys%'");
            $query=$this->db->get();
            return $query;
        }

        public function GetCategoryByCID($CID){
            return $this->db->get_where("Category_Master", ["CID" => $CID, "BitStatus" => 1])->row();
        }

        public function UpdateCategory($data, $cid){
            $this->db->set($data);
            $this->db->where("CID", $cid);
            return $this->db->update("Category_Master");
        }

        public function DeleteCategory($CID){
            //Setting Subcategory_Master BitStatus to 0
            $this->db->set("BitStatus", 0);
            $this->db->where("CID", $CID);
            $this->db->update("Category_Master");
            
            //Setting Subcategory_Master BitStatus to 0
            $this->db->set("isactive", 0);
            $this->db->where("productcategory", $CID);
            $this->db->update("productmaster");

            return 1;
        }
        
    }
?>