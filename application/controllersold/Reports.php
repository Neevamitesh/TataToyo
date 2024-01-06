<?php
class Reports extends CI_Controller
{
    public $admin_id;
    public $role;
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->role = $this->session->userdata('role');
        if (empty($this->admin_id)) {
            redirect('Admin');
        }
        $this->load->model("GetReports");
        $this->load->model("GetProducts");
        $this->load->model("GetUserData");
    }
    public function NavData()
    {
        $NavData =  array(
            'error' => ''
        );
        return $NavData;
    }

    private function Support($view, $data = [])
    {
        $NavData = $this->GetUserData->NavData();
        $data = array_merge_recursive($NavData, $data);
        $this->load->view("NavFoot/navbar", $data);
        $this->load->view($view);
        $this->load->view("NavFoot/footer");
    }
    public function ProductReport($CMID = null)
    {
        $consignees = $this->GetProducts->GetAllConsignee();
        $Products = $this->GetProducts->GetAllProducts();
        $msg = $this->session->flashdata('msg');
        $view = "Reports/ProductReport";
        $data = array(
            'page' => 'reports',
            'type' => 'productreport',
            'title' => 'Product',
            // 'category' => $category,
            'Products' => $Products,
            'msg' => $msg,
            'consignees' => $consignees, 
            'CMID' => $CMID,
            'role' => $this->role,
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }
    public function UserReport($msg = null)
    {
        $users = $this->GetReports->GetUserReport();
        $msg = $this->session->flashdata('msg');
        $view = "Reports/UserReport";
        $data = array(
            'page' => 'reports',
            'type' => 'userreport',
            'title' => 'User Report',
            'users' => $users,
            'msg' => $msg,
            'role' => $this->role,
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }
    public function Consignee($UID = null){
        $msg = $this->session->flashdata('msg');
        $location = $this->GetReports->GetAllLocation();
        $consignees = $this->GetProducts->GetAllConsignee();
        $view = "Reports/ConsigneeReport";
        $data = array(
            'page' => 'reports',
            'type' => 'consigneereport',
            'title' => 'Consignee Report',
            'location' => $location,
            'msg' => $msg,
            'role' => $this->role,
            'consignees' => $consignees, 
            'UID' => $UID
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }

    public function UserLog(){
        $location = "empty";
        $fromdate = "empty";
        $todate = "empty";
        $logs = "empty";
        if(!empty($this->input->post("user")) && !empty($this->input->post("todate")) && !empty($this->input->post("fromdate"))){
            $location = $this->input->post("user");
            $fromdate = $this->input->post("fromdate");
            $todate = $this->input->post("todate");
            $logs = $this->GetReports->GetUserLogs($location);
        }
        $msg = $this->session->flashdata('msg');
        $locations = $this->GetReports->GetAllLocations();
        $view = "Reports/UserLogReport";
        $data = array(
            'page' => 'reports',
            'type' => 'userlogreport',
            'title' => 'User Logs Report',
            'logs' => $logs,
            'msg' => $msg,
            'role' => $this->role,
            'locations' => $locations,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'UID' => $location
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }

    public function GetConsigneeJSON(){
        $fromdate = $this->input->post("fromdate", null);
        $todate = $this->input->post("todate", null);
        $location = $this->input->post("location", null);

        $consignee = $this->GetReports->GetConsigneeReport($fromdate, $todate, $location, $this->admin_id);
        $Data = [];
        foreach ($consignee->result() as $key => $row) {
            $Data["data"][] = array(
                    $key+1,
                    $row->consigneecode,
                    $row->consigneename,
                    $row->address,
                    $row->mobileno,
                    $row->city_name,
                    $row->state_name,
                    $row->pincode,
            );
        }
        echo json_encode($Data);
    }

    public function LocationMaster(){
        $msg = $this->session->flashdata('msg');
        $location = $this->GetReports->GetAllLocation();
        $view = "Reports/LocationReport";
        $data = array(
            'page' => 'reports',
            'type' => 'locationreport',
            'title' => 'Consignee Report',
            'location' => $location,
            'msg' => $msg,
            'role' => $this->role,
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }

    public function BatchReport($UID = null, $fromdate = null, $todate = null){
        $msg = $this->session->flashdata('msg');
        $consignees = $this->GetReports->GetAllConsignees();
        $batches = $this->GetReports->GetBatchReport($UID, $fromdate, $todate);
        $isData = 0;
        if($batches->num_rows() > 0){
            $isData = 1;
        }
        $view = "Reports/BatchReport";
        $data = array(
            'page' => 'reports',
            'type' => 'batchreport',
            'title' => 'Batch Report',
            'consignees' => $consignees,
            'msg' => $msg,
            'role' => $this->role,
            'UID' => $UID,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'batches' => $batches,
            'isdata' => $isData
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }

    // public function LabelDesign(){
    //     $msg = $this->session->flashdata('msg');
    //     $consignees = $this->GetProducts->GetAllConsignee();
    //     $view = "Reports/LabelDesignReport";
    //     $data = array(
    //         'page' => '',
    //         'type' => '',
    //         'title' => 'Label Design Report',
    //         'location' => $location,
    //         'msg' => $msg,
    //         'role' => $this->role,
    //         'consignees' => $consignees, 
    //         'CMID' => $CMID
    //     );
    //     // print_r($Products->result());
    //     $this->Support($view, $data);
    // }

}
?>