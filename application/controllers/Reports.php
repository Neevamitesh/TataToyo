<?php
require './vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
        $this->load->model("ConTran"); //@@26092019
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

    //@@ 26092019
    public function Download(){
        $CMID = $this->input->post("consignee");
        $Download = $this->ConTran->GetDownload(array(
            $CMID
        ));
        $Msg = 'File Not Found';
        if($Download->num_rows() > 0){
            $Info = $Download->row();
            $file = $Info->Path;
            $Msg = 'Success';
            force_download($file, NULL);
            //sleep(0.5);
            ?>
                <script>
                    //alert('<?= $Msg?>');
                    window.location.href = '<?=base_url('Reports/ProductReport');?>';
                </script>
            <?php
        }else{
            ?>
                <script>
                    alert('<?= $Msg?>');
                    window.location.href = '<?=base_url('Reports/ProductReport');?>';
                </script>
            <?php
        }
    }
    public function CreateBatchReport(){
        $view = "Reports/CreateBatchReport";
        $Consignee = $this->ConTran->GetConsignee(array(
            $this->admin_id
        ));
            $datas= array(
                'page' => 'reports',
                'type' => 'Batch Report',
                'title' => 'Batch Report',
                'consignees' => $Consignee,
            );
        $this->Support($view, $datas);
    }
    public function GetBatchDetailsReport(){
      
         $ConsigneeID = $this->input->post('Consignee',true);
         $ProductName = $this->input->post('Product',true);
         $fromdate = $this->input->post('fromdate',true);
        $todate = $this->input->post('todate',true);
        $comp = array("Get_Report","","$ConsigneeID","$ProductName","$fromdate","$todate");
        $res = $this->ConTran->STP_BatchReport($comp); 
   
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
    public function GetProductInfo(){
        $Consignee = $this->input->post("Consignee",true);
        $Products = $this->ConTran->GetProducts($Consignee,$this->admin_id);
        //$Productcode = $this->CT->GetProducts($Consignee,$this->admin_id);
        ob_start();
        foreach ($Products->result() as $key => $row) {
            ?>
            <option data_id = "<?= $row->id?>" value="<?= $row->productname?>"><?= $row->productname?></option>
            <?php
        }
        $ProductName = ob_get_clean();
        ob_start();
        foreach ($Products->result() as $key => $row) {
            ?>
            <option data_id = "<?= $row->id?>" value="<?= $row->productname?>"><?= $row->productcode?></option>
            <?php
        }
        $Productcode = ob_get_clean();
        # @@ End
                    
                    echo json_encode([
                        "Prd" => $ProductName,
                        "PrdCode" => $Productcode,
                    ]);
    }
   
   
    public function GetExcel(){
        $ConsigneeID = $this->input->post('Consignee',true);
        $ProductName = $this->input->post('Product',true);
        $fromdate = $this->input->post('fromdate',true);
        $todate = $this->input->post('todate',true);
        $comp = array("Get_Report","","$ConsigneeID","$ProductName","$fromdate","$todate");
        $res = $this->ConTran->STP_BatchReport($comp);
        $Media = '';
        $msg = 'Data Not Found';
        $status = false;
        if($res->num_rows() > 0){
            
            $start = 0;
            $limit = 500;
            if(!is_dir('uploads/'))mkdir('uploads/');
            if(!is_dir('uploads/'.uniqid()))mkdir('uploads/'.uniqid());
            $Path = 'uploads/'.$res->row()->consigneename.'('. $ProductName.')'.'.xls';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $table_columns = array("Sr. No.", "BatchID","ConsigneeName","Location", "ProductCode", "VC", "Rev", "Date", "SerialNo", "GeneratedAt", "Barcode Data");
            $column = 1;
            foreach($table_columns as $field){
                $sheet->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $excel_row = 2;
            $srno = 1;
            foreach($res->result() as $row){
               
                    $sheet->setCellValueByColumnAndRow(1, $excel_row, $srno);
                    $sheet->setCellValueByColumnAndRow(2, $excel_row, $row->batchID);
                    $sheet->setCellValueByColumnAndRow(3, $excel_row, $row->consigneename);
                    $sheet->setCellValueByColumnAndRow(4, $excel_row, $row->locationname);
                    $sheet->setCellValueByColumnAndRow(5, $excel_row, $row->productcode);
                    $sheet->setCellValueByColumnAndRow(6, $excel_row, $row->vccode);
                    $sheet->setCellValueByColumnAndRow(7, $excel_row, $row->Rev);
                    $sheet->setCellValueByColumnAndRow(8, $excel_row, $row->mmyy);
                    $sheet->setCellValueByColumnAndRow(9, $excel_row, $row->Srno);
                    $sheet->setCellValueByColumnAndRow(10, $excel_row, $row->GeneratedAt);
                    $sheet->setCellValueByColumnAndRow(11, $excel_row, $row->BarcodeData);
                    //$sheet->setCellValueByColumnAndRow(10, $excel_row, $BarcodeData);
                    $excel_row++;
                    $srno++;
                
            }
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
            $writer->save($Path);
            $Media = [$Path];
            $msg = 'Success';
            $status = true;
        }
            echo json_encode([
                "ExcelFile" => $Media,
                'Msg' =>$msg,
                'Status' => $status,
            ]);

    }

    public function VerifyBarcode($CMID = null, $fromdate = null, $todate = null){
        $msg = $this->session->flashdata('msg');
        $consignees = $this->GetReports->GetAllConsignees();
        // echo "<pre>consignees :"; print_r($consignees->result()); exit;
        $verify = $this->GetReports->GetVerifyReport($CMID, $fromdate, $todate);
        // echo "<pre>db :"; print_r($this->db->last_query());
        // echo "<pre>verify :"; print_r($verify->result()); exit;
        $isData = 0;
        if($verify->num_rows() > 0){
            $isData = 1;
        }
        $view = "Reports/VerifyReport";
        $data = array(
            'page' => 'reports',
            'type' => 'verifyreport',
            'title' => 'Verify Report',
            'consignees' => $consignees,
            'msg' => $msg,
            'role' => $this->role,
            'CMID' => $CMID,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'verify' => $verify,
            'isdata' => $isData
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }

    public function EOLVerifyReport($CMID = null, $fromdate = null, $todate = null){
        $msg = $this->session->flashdata('msg');
        $consignees = $this->GetReports->GetAllConsignees();
        // echo "<pre>consignees :"; print_r($consignees->result()); exit;
        $verify = $this->GetReports->GetEOLVerifyReport($CMID, $fromdate, $todate);
        // echo "<pre>db :"; print_r($this->db->last_query());
        // echo "<pre>verify :"; print_r($verify->result()); exit;
        $isData = 0;
        if($verify->num_rows() > 0){
            $isData = 1;
        }
        $view = "Reports/EOLVerifyReport";
        $data = array(
            'page' => 'reports',
            'type' => 'eolverifyreport',
            'title' => 'EOLVerify Report',
            'consignees' => $consignees,
            'msg' => $msg,
            'role' => $this->role,
            'CMID' => $CMID,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'verify' => $verify,
            'isdata' => $isData
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }

    public function WCPrintReport($CMID = null, $fromdate = null, $todate = null){
        $msg = $this->session->flashdata('msg');
        $consignees = $this->GetReports->GetAllConsignees();
        // echo "<pre>consignees :"; print_r($consignees->result()); exit;
        $wcprint = $this->GetReports->GetWCPrintReport($CMID, $fromdate, $todate);
        // echo "<pre>db :"; print_r($this->db->last_query());
        // echo "<pre>wcprint :"; print_r($wcprint->result()); exit;
        $isData = 0;
        if($wcprint->num_rows() > 0){
            $isData = 1;
        }
        $view = "Reports/WCPrintReport";
        $data = array(
            'page' => 'reports',
            'type' => 'wcprintreport',
            'title' => 'WCPrint Report',
            'consignees' => $consignees,
            'msg' => $msg,
            'role' => $this->role,
            'CMID' => $CMID,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'wcprint' => $wcprint,
            'isdata' => $isData
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }

    public function WCErrorReport($CMID = null, $fromdate = null, $todate = null){
        $msg = $this->session->flashdata('msg');
        $consignees = $this->GetReports->GetAllConsignees();
        // echo "<pre>consignees :"; print_r($consignees->result()); exit;
        $wcerror = $this->GetReports->GetWCErrorReport($CMID, $fromdate, $todate);
        // echo "<pre>db :"; print_r($this->db->last_query());
        // echo "<pre>wcerror :"; print_r($wcerror->result()); exit;
        $isData = 0;
        if($wcerror->num_rows() > 0){
            $isData = 1;
        }
        $view = "Reports/WCErrorReport";
        $data = array(
            'page' => 'reports',
            'type' => 'wcerrorreport',
            'title' => 'WCError Report',
            'consignees' => $consignees,
            'msg' => $msg,
            'role' => $this->role,
            'CMID' => $CMID,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'wcerror' => $wcerror,
            'isdata' => $isData
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }
}

    
?>