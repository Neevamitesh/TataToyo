<?php
    class GetProducts extends CI_Model{
        function __construct(){
            parent::__construct();
            $this->load->library('session');
            $this->load->database();
            
        }
        // public function UploadProduct($data){
        //     $insert_user_stored_proc = "CALL spproductmaster(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        //     $query = $this->db->query($insert_user_stored_proc, $data);
        //     mysqli_next_result( $this->db->conn_id );
        //     return $query->row();
        // }

        public function GetLastID($consignee){
            return $this->db->query("SELECT MAX(id) as maxid FROM productmaster where CMID = $consignee;")->row()->maxid;
        }

        public function UploadProduct($data){
            $this->db->insert("productmaster", $data);
            return $this->db->insert_id();
        }

        public function isConsigneeExist($CMID){
            return $this->db->get_where("productmaster", ["CMID" => $CMID, "isactive" => 1])->num_rows();
        }

        public function RemoveProductByCMID($CMID){
            $this->db->set("isactive", 0);
            $this->db->where("CMID", $CMID);
            return $this->db->update("productmaster");
        }

        public function RemoveProductDetailsByCMID($CMID){
            $this->db->set("BitStatus", 0);
            $this->db->where("CMID", $CMID);
            return $this->db->update("productdetail");
        }

        public function UpdateProduct($data, $PID){
            // $insert_user_stored_proc = "CALL spproductmaster(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            // $query = $this->db->query($insert_user_stored_proc, $data);
            // mysqli_next_result( $this->db->conn_id );
            // return $query->row();
            $this->db->set($data);
            $this->db->where("id", $PID);
            return $this->db->update("productmaster");

        }
        public function UploadProductsExcel($productDetail){
            return $this->db->insert_batch("ProductDetail", $productDetail);
        }
        public function GetAllProducts(){
            $this->db->join("consigneemaster", "consigneemaster.CMID = productmaster.CMID");
           return $this->db->get_where("productmaster", ["productmaster.isactive" => 1]);
        }
        public function GetAllConsignee(){
            $this->db->join("cities", "cities.city_id = consigneemaster.city");
            $this->db->join("states", "states.state_id = consigneemaster.state");
            return $this->db->get_where("consigneemaster", ["isactive" => 1]);
        }
        public function GetProductVariables($PID){
            return $this->db->get_where("productdetail", ["BitStatus" => 1, "PID" => $PID]);
            // mysqli_next_result( $this->db->conn_id );
            // $this->db->close();
            // return $query;
        }
        public function GetProductDetail($PID){
            return $this->db->get_where("productmaster", ["id" => $PID])->row();
        }
        public function AddProductDetail($detail){
            return $this->db->insert_batch("ProductDetail", $detail);
        }
        public function UpdateProductDetail($detail, $PID){
            $this->db->set("BitStatus", 0);
            $this->db->where("PID", $PID);
            $res = $this->db->update("ProductDetail");
            if($res){
                return $this->db->insert_batch("ProductDetail", $detail);
            }
            else{
                return 0;
            }
        }
        public function DeleteProduct($CVID){
            $this->db->select("FVID");
            $this->db->from("Color_Variations");
            $this->db->where("CVID", $CVID);
            $ID = $this->db->get()->row();
            $FVID = $ID->FVID;
            
            $this->db->select("FVID");
            $this->db->from("Color_Variations");
            $this->db->where("FVID", $FVID);
            $res = $this->db->get();
            $count = $res->num_rows();

            if($count==1){                
                $this->db->set("BitStatus", 0);
                $this->db->where("FVID", $FVID);
                $this->db->update("Feature_Variations");
            }

            $this->db->set("BitStatus", 0);
            $this->db->where("CVID", $CVID);
            $this->db->update("Product_Attachments");

            $this->db->set("BitStatus", 0);
            $this->db->where("CVID", $CVID);
            return $this->db->update("Color_Variations");

        }

        public function GetCVIDByPID($PID){
            $this->db->join("Color_Variations", "Color_Variations.FVID = Feature_Variations.FVID");
            $this->db->where("Feature_Variations.PID", $PID);
            return $this->db->get("Feature_Variations");
        }

        public function InsertMoreDetails($data){
            return $this->db->insert('More_Detail',$data);  
        }

        public function GetMoreDetailsByCVID($CVID){
            return $this->db->get_where("More_Detail", ["CVID" => $CVID]);
        }

        public function UpdateMoreDetails($data, $CVID){
            return $this->db->update("More_Detail",$data,["CVID"=>$CVID]);
        }

        public function GetProductBySID($SID){
            $this->db->select("*");
            $this->db->from("Feature_Variations");
            $this->db->where("SID", $SID);
            $this->db->join("Color_Variations", "Color_Variations.FVID = Feature_Variations.FVID");
            return $this->db->get();
        }

    }
?>