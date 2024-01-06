<?php
    class AddOns extends CI_Controller{
        private $Date,$user_id,$Msg=[
            "Msg"=>"Somwthing Went Wrong",
            "Res" => false
        ];
        public function __construct(Type $var = null) {
            parent::__construct();
            $this->user_id = $this->session->userdata('admin_id');
            !empty($this->user_id)?:redirect("Admin");
            $this->load->model("AddOnsTran","AT");
            $this->load->model("GetUserData","GD");
            date_default_timezone_set("Asia/Kolkata");
            $this->Date = date("Y-m-d H:i:s");
        }
        private function Support($view,$data=[]){
            $NavFoot = $this->GD->NavData();
            $data = array_merge_recursive($NavFoot,$data);
            $this->load->view("NavFoot/navbar",$data);
            $this->load->view($view);
            $this->load->view("NavFoot/footer");
        }
        public function index(){
            $Titles = $this->AT->GetTitle();
            $Dates = $this->AT->GetDates();
            $Options = $this->AT->GetOptions();
            $this->Support("AddOns/Addon",[
                "Title" => $Titles,
                "Dates" => $Dates,
                "Options" => $Options
            ]);
        }
        public function AddTitle(){
            $Title = $this->input->post("Title",true);
            !(empty($Title))?:redirect("AddOns");
            $Res = $this->AT->AddTitle([
                "CreatedBy" => $this->user_id,
                "CreatedAt" => $this->Date,
                "Title" => $Title
            ]);
            if($Res>0){
                $this->Msg = [
                    "Res" => true,
                    "Msg" => "Title Added Successfully"
                ];
            }
            $this->session->set_flashdata("Lab-AddOns",$this->Msg);
            redirect("AddOns");
        }
        public function AddTitleData(){
            $AOID = $this->input->post("Title",true);
            $Data = $this->input->post("Data",true);
            (!empty($AOID) && !empty($Data))?:redirect("AddOns");
            $Res = $this->AT->AddData([
                "AOID" => $AOID,
                "Data" => $Data,
                "CreatedBy" => $this->user_id,
                "CreatedAt" => $this->Date,
            ]);
            if($Res>0){
                $this->Msg = [
                    "Res" => true,
                    "Msg" => "Title Data Added Successfully"
                ];
            }
            $this->session->set_flashdata("Lab-AddOns",$this->Msg);
            redirect("AddOns");
        }
        public function AddDate(){
            $Date = $this->input->post("Date",true);
            $Reverse = empty($this->input->post("Reverse",true))? 0 : 1;
            !(empty($Date))?:redirect("AddOns");
            $Res = $this->AT->AddDate([
                "CreatedBy" => $this->user_id,
                "CreatedAt" => $this->Date,
                "Data" => $Date,
                "isReverse" => $Reverse
            ]);
            if($Res>0){
                $this->Msg = [
                    "Res" => true,
                    "Msg" => "Date Added Successfully"
                ];
            }
            $this->session->set_flashdata("Lab-AddOns",$this->Msg);
            redirect("AddOns");
        }
        public function DeleteDate(){
            $MDID = $this->input->post("Date",true); #MDID
            !(empty($MDID))?:redirect("AddOns");
            $Res = $this->AT->DeleteDate($MDID);
            if($Res>0){
                $this->Msg = [
                    "Res" => true,
                    "Msg" => "Date Removed Successfully"
                ];
            }
            $this->session->set_flashdata("Lab-AddOns",$this->Msg);
            redirect("AddOns");
        }
        public function DeleteTitle(){
            $AOID = $this->input->post("Title",true); #AOID
            !(empty($AOID))?:redirect("AddOns");
            $Res = $this->AT->DeleteTitle($AOID);
            if($Res>0){
                $this->Msg = [
                    "Res" => true,
                    "Msg" => "Title Removed Successfully"
                ];
            }
            $this->session->set_flashdata("Lab-AddOns",$this->Msg);
            redirect("AddOns");
        }
        public function DeleteOption(){
            $ADID = $this->input->post("Option",true); #ADID
            !(empty($ADID))?:redirect("AddOns");
            $Res = $this->AT->DeleteOption($ADID);
            if($Res>0){
                $this->Msg = [
                    "Res" => true,
                    "Msg" => "Option Removed Successfully"
                ];
            }
            $this->session->set_flashdata("Lab-AddOns",$this->Msg);
            redirect("AddOns");
        }
        public function EditTitle(){
            $Title = $this->input->post("Title",true);
            $AOID =  $this->input->post("Title-Ref",true);
            (!empty($Title) && !empty($AOID))?:redirect("AddOns");
            $Res = $this->AT->EditTitle([
                "Title" => $Title
            ],$AOID);
            if($Res>0){
                $this->Msg = [
                    "Res" => true,
                    "Msg" => "Title Edited Successfully"
                ];
            }
            $this->session->set_flashdata("Lab-AddOns",$this->Msg);
            redirect("AddOns");
        }
        public function EditDate(){
            $Date = $this->input->post("Date",true);
            $MDID = $this->input->post("Date-Ref",true);
            $Reverse = empty($this->input->post("Reverse",true))? 0 : 1;
            (!empty($Date) && !empty($MDID))?:redirect("AddOns");
            $Res = $this->AT->EditDate([
                "Data" => $Date,
                "isReverse" => $Reverse
            ],$MDID);
            if($Res>0){
                $this->Msg = [
                    "Res" => true,
                    "Msg" => "Date Edited Successfully"
                ];
            }
            $this->session->set_flashdata("Lab-AddOns",$this->Msg);
            redirect("AddOns");
        }
        public function EditOption(){
            $AOID = $this->input->post("Title",true);
            $Data = $this->input->post("Data",true);
            $ADID = $this->input->post("Option-Ref");
            (!empty($AOID) && !empty($Data) && !empty($ADID))?:redirect("AddOns");
            $Res = $this->AT->EditData([
                "AOID" => $AOID,
                "Data" => $Data,
            ],$ADID);
            if($Res>0){
                $this->Msg = [
                    "Res" => true,
                    "Msg" => "Title Data Updated Successfully"
                ];
            }
            $this->session->set_flashdata("Lab-AddOns",$this->Msg);
            redirect("AddOns");
        }
    }
    
?>