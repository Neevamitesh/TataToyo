<?php
class Products extends CI_Controller
{
    public $admin_id;
    public $role;
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->role = $this->session->userdata('role');
        ini_set('max_execution_time',300);
        if (empty($this->admin_id)) {
            redirect('Admin');
        }
        $this->load->model("GetCategory");
        $this->load->model("GetProducts");
        $this->load->model("GetUserData","GD");
        //@@
        $this->load->model("ConTran","CT");
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
        $NavData = $this->GD->NavData();
        $data = array_merge_recursive($NavData, $data);
        $this->load->view("NavFoot/navbar", $data);
        $this->load->view($view);
        $this->load->view("NavFoot/footer");
    }
    public function index($msg = null)
    {
        // $category = $this->GetCategory->Category();
        $consignees = $this->GetProducts->GetAllConsignee();
        $Products = $this->GetProducts->GetAllProducts();
        $msg = $this->session->flashdata('msg');
        $view = "User/products";
        $data = array(
            'page' => 'product',
            'type' => 'addproduct',
            'title' => 'Product',
            // 'category' => $category,
            'Products' => $Products,
            'msg' => $msg,
            'consignees' => $consignees,
            'role' => $this->role,
        );
        // print_r($Products->result());
        $this->Support($view, $data);
    }
    public function UploadProducts()
    {
        $consignee = $this->input->post('consignee', true);
        $category = $this->input->post('category', true);
        $productcode = $this->input->post('productcode', true);
        $productname = $this->input->post('productname', true);
        $price = $this->input->post('price', true);
        $qty = $this->input->post('qty', true);
        $descone = $this->input->post('descone', true);
        // $desctwo = $this->input->post('desctwo', true);
        $key = $this->input->post('key', true);
        $value = $this->input->post('value', true);
        if (!empty($_FILES['banner']['tmp_name'])) { //Uploading Banner, Stay Out of This !
            if (!is_dir('./uploads')) {
                mkdir('./uploads', 755, true);
            }
            if (!is_dir('./uploads/Banner')) {
                mkdir('./uploads/Banner', 755, true);
            }

            $config['upload_path'] = './uploads/Banner';
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $chk = $this->upload->do_upload('banner');
            $data = $this->upload->data();
            $attachment = './uploads/Banner/' . $data['file_name'];
            $data = array(
                'itype' => 'INS',
                'iid' => 0,
                'iCMID' => $consignee,
                'iproductcode' => $productcode,
                'iproductname' => $productname,
                'iproductdescriptionone' => $descone,
                'iproductdescriptiontwo' => '',
                'iproductcategory' => $category,
                'iproductprice' => $price,
                'iproductstockquantity' => $qty,
                'iuploadimage' => $attachment,
                'iother1' => '',
                'iother2' => '',
                'iother3' => '',
                'icreatedby' => $this->admin_id,
                'iupdatedflag' => '',
                'irefdate' => '',
                'ifinyear' => '',
                'iupdatedtimestamp' => '',
                'icreatedthrough' => '',
                'iupdatedthrough' => '',
            );
            $res = $this->GetProducts->UploadProduct($data);
            if ($res->MESSAGE == "ok") {
                $PID = $res->id;
                $c = 0;
                foreach ($key as $k) {
                    $detail[] = array(
                        'PID' => $PID,
                        'KeyName' => $k,
                        'ValueData' => $value[$c]
                    );
                    $c++;
                }
                $count = $this->GetProducts->AddProductDetail($detail);
                if ($count > 0) {
                    $msg = "Product Uploaded !";
                } else {
                    $msg = "Something went wrong, Product details not added !";
                }
            } else {
                $msg = "Something went wrong !";
            }
            // print_r($res);
            $this->session->set_flashdata('msg', $msg);
            redirect('Products');
        }
    }

    public function UpdateProducts()
    {
        $consignee = $this->input->post('consignee', true);
        // $category = $this->input->post('category', true);
        // $productcode = $this->input->post('productcode', true);
        $productname = $this->input->post('productname', true);
        $productcode = $this->input->post('productcode', true);
        // $price = $this->input->post('price', true);
        // $qty = $this->input->post('qty', true);
        // $descone = $this->input->post('descone', true);
        // $previmage = $this->input->post('previmage', true);
        $PID = $this->input->post('pid', true);
        // $desctwo = $this->input->post('desctwo', true);
        $key = $this->input->post('key', true);
        $value = $this->input->post('value', true);
        // if (!empty($_FILES['banner']['name'])) {
        //     if (!empty($_FILES['banner']['tmp_name'])) { //Uploading Banner, Stay Out of This !
        //         if (!is_dir('./uploads')) {
        //             mkdir('./uploads', 755, true);
        //         }
        //         if (!is_dir('./uploads/Banner')) {
        //             mkdir('./uploads/Banner', 755, true);
        //         }

        //         $config['upload_path'] = './uploads/Banner';
        //         $config['allowed_types'] = '*';
        //         $this->load->library('upload', $config);
        //         $this->upload->initialize($config);  
        //         $chk = $this->upload->do_upload('banner');
        //         $data = $this->upload->data();
        //         $attachment = './uploads/Banner/' . $data['file_name'];
        //     }
        //     else{
        //         $msg = "Invalid Image !";
        //         $this->session->set_flashdata('msg', $msg);
        //         redirect('Products');    
        //     }
        // }
        // else{
        //     $attachment = $previmage;
            
        // }
        $data = array(
            'CMID' => $consignee,
            'productname' => $productname,
            'productcode' => $productcode
        );
        $res = $this->GetProducts->UpdateProduct($data, $PID);
        if ($res) {
            $c = 0;
            foreach ($key as $k) {
                $detail[] = array(
                    'PID' => $PID,
                    'KeyName' => $k,
                    'ValueData' => $value[$c]
                );
                $c++;
            }
            $count = $this->GetProducts->UpdateProductDetail($detail, $PID);
            if ($count > 0) {
                $msg = "Product Updated !";
            } else {
                $msg = "Something went wrong, Product details not Updated !";
            }
        } else {
            $msg = "Something went wrong !";
        }
        
        $this->session->set_flashdata('msg', $msg);
        redirect('Products');
        // try{
        //     print_r($_FILES["banner"]["error"]);
        // }
        // catch(Exception $e){
        //     echo $e->errorMessage();
        // }
    }

    public function UploadProductsExcel(){
        // print_r($_FILES);
        $consignee = $this->input->post('consignee', true);
        $CMID = $this->input->post('consignee', true);#CMID //@@26092019
        is_dir('upload') ?: mkdir('upload');
        is_dir('upload/Product') ?: mkdir('upload/Product');
        $config['upload_path'] = './upload/Product';
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);  
        $chk = $this->upload->do_upload('excel');
        
       
        $data = $this->upload->data();
        
            $attachment = './upload/' . $data['file_name'];
        $Result = $this->upload->data();
        // echo $CMID;
        $Path = "upload/Product/{$Result['file_name']}";
        $this->CT->ExcelFile(array(
            "INS",0,$CMID,$Path
        ));  //@@26092019 end
        $productname = "";
        $PARTNO = "";
        $PID = 0;
        $this->load->library('excel');
        $data = array();
        if(!empty($_FILES["excel"]["name"])){
            // $fileName = $_FILES["excel"]["name"];
            $FileType = $_FILES["excel"]["type"];
            if($FileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $FileType == "text/csv" || $FileType == "application/vnd.ms-excel" || $FileType == "application/octet-stream"){
                $path = $_FILES["excel"]["tmp_name"];
                $object = PHPExcel_IOFactory::load($path);
                // print_r($object);
                $productDetail = array();
                $proIndex = 0;
                $PartIndex = 0;
                $conResult = $this->GetProducts->isConsigneeExist($consignee);
                if($conResult > 0){
                    $upres = $this->GetProducts->RemoveProductByCMID($consignee);
                    if($upres){
                        $deRes = $this->GetProducts->RemoveProductDetailsByCMID($consignee);
                    }
                }
                // return;
                foreach($object->getWorksheetIterator() as $worksheet){
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    $highestColumn = ord($highestColumn)-64;
                    //@@10182019 bob
                    // for($col=1; $col<$highestColumn; $col++){
                    //     $key = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                    //     if(!empty($key)){
                    //         if($proIndex == 0 || $PartIndex == 0){
                    //             if($key == "PRODUCT_NAME"){
                    //                 $proIndex = $col;
                    //             }
                    //             else if($key == "PARTNO"){
                    //                 $PartIndex = $col;
                    //             }
                    //         }
                    //     }
                    // }
                    //@@10182019 end
                    for($row=2; $row<=$highestRow; $row++){
                        //Getting Field Values from Excel
                        //@@10182019 bob
                        $productname = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $PARTNO = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        //@@10182019 end
                        $productData = array(
                            'CMID' => $consignee,
                            'productname' => $productname,
                            'productcode' => $PARTNO,
                            'createdby' => $this->admin_id,
                            'updatedtimestamp' => date('d-m-Y H:i:s')
                        );
                        // echo "<pre>";
                        // print_r($productData);
                        // echo "</pre>";exit;
                        $PID = $this->GetProducts->UploadProduct($productData);
                        // echo "Highest Col: ".$highestColumn;
                        for($col=0; $col<=$highestColumn; $col++){
                            $key = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            
                            if(!empty($key)){
                                // if($value == ""){
                                //     $value = 0;
                                // }
                                $productDetail[] = array(
                                    'CMID' => $consignee,
                                    'PID' => $PID,
                                    'KeyName' => $key,
                                    'ValueData' => $value
                                );
                            }
                        }

                        $PID = 0;
                    }
                    // echo "<pre>";
                    // print_r($productDetail);
                    // echo "</pre>";exit;
                    $res = $this->GetProducts->UploadProductsExcel($productDetail);
                    // echo $res;
                    if($res > 0){
                        $msg = "success";
                        $this->session->set_flashdata('msg', $msg);
                        redirect("Products");
                    }
                }   
            }
            else{
                $msg = "Invalid Excel Format !";
                $this->session->set_flashdata('msg', $msg);
                redirect("Products");
            }
        }
        else{
            $msg = "Something went wrong !";
            $this->session->set_flashdata('msg', $msg);
            redirect("Products");
        }
    }


    public function ViewProduct(){
        $PID = $this->input->post('pid', true);
        $consignees = $this->GetProducts->GetAllConsignee();
        $res = $this->GetProducts->GetProductDetail($PID);
        // $res = $detail->result();
        $Variables = $this->GetProducts->GetProductVariables($PID);
        ?>
        <div class="card">
            <div class="card-body">
                <div class="card-title">Edit Product</div>
                <hr>
                <form method="post" action="<?= base_url(); ?>Products/UpdateProducts" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-4">
                            <input type="hidden" name="pid" value="<?= $res->id; ?>">
                            <div class="form-group">
                                <label for="consignee">Consignee</label>
                                <select class="form-control form-control-rounded" required id="consignee" name="consignee">
                                    <option value="" selected disabled>Select Consignee</option>
                                    <?php
                                    foreach ($consignees->result() as $cat) {
                                        ?>
                                        <option <?= $cat->CMID == $res->CMID ? "selected" : ""; ?> value="<?= $cat->CMID; ?>"><?= $cat->consigneename . " (" . $cat->consigneecode . ")"; ?></option>
                                    <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="productname">Product Name</label>
                                <input type="text" value="<?= $res->productname; ?>" name="productname" required class="form-control form-control-rounded" id="productname" placeholder="Enter Product Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="productcode">Product Code</label>
                                <input type="text" value="<?= $res->productcode; ?>" name="productcode" required class="form-control form-control-rounded" id="productcode" placeholder="Enter Product Code">
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-outline-primary waves-effect waves-light m-1" onclick="AddField('edit')" title="Add Field"> <i class="fa fa-plus"></i> </button>
                        </div>
                    </div>
                    <br>
                    <div class="row" id="editvariables">
                        <?php
                        $count = 0;
                        foreach ($Variables->result() as $var) {
                            ?>
                            <div class="col-md-3 newfield<?= $var->PDID; ?>">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <?php
                                            if($count == 0){
                                                ?>
                                                    <button class="btn btn-danger" type="button"><i class="fa fa-asterisk"></i></button>
                                                <?php
                                            }
                                            else{
                                                ?>
                                                    <button class="btn btn-danger" onclick="DeleteNewField('<?= $var->PDID; ?>')" type="button"><i class="fa fa-minus"></i></button>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <input type="text" class="form-control form-control-rounded" required name="key[]" value="<?= $var->KeyName; ?>" required placeholder="Enter Attribute Key">
                                </div>
                            </div>
                            <div class="col-md-3 newfield<?= $var->PDID; ?>">
                                <div class="form-group">
                                    <input type="text" required name="value[]" required class="form-control form-control-rounded" value="<?= $var->ValueData; ?>" id="" placeholder="Enter Attribute Value">
                                </div>
                            </div>
                        <?php
                        $count++;
                    }
                    ?>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary shadow-primary pull-right btn-round px-5"><i class="icon-plus"></i> Update</button>
                    </div>
                </form>
            </div>
        </div>
    <?php
    }
public function EditProducts()
{
    // $this->load->model("GetProducts");
    // $result=$this->GetProducts->GetAllProducts();
    $this->load->model("GetCategory");
    $result = $this->GetCategory->Category();
    // $Products = $result->result();
    // $this->load->model('GetNotifications');
    // $note = $this->GetNotifications->NavNotify();
    // echo "<pre>";
    // print_r($result->result());
    // echo "</pre>";
    $this->load->view("Admin/app/navbar");
    $this->load->view("Admin/app/sidebar", array('page' => 'Products', 'type' => 'EditProducts'));
    $this->load->view("Admin/app/EditProducts", ['result' => $result]);
}
public function AddDetail()
{
    $this->load->model("GetCategory");
    $result = $this->GetCategory->Category();
    $this->load->view("Admin/app/navbar");
    $this->load->view("Admin/app/sidebar", array('page' => 'MoreDetail', 'type' => 'AddDetails', 'error' => ''));
    $this->load->view("Admin/app/AddMoreDetails", ['result' => $result]);
}
public function EditDetail()
{
    // today
    $this->load->model("GetCategory");
    $result = $this->GetCategory->Category();
    $this->load->view("Admin/app/navbar");
    $this->load->view("Admin/app/sidebar", array('page' => 'MoreDetail', 'type' => 'EditDetails', 'error' => ''));
    $this->load->view("Admin/app/EditMoreDetails", ['result' => $result]);
}
public function ByBrands()
{
    // $this->load->model("GetCourses");
    // $result=$this->GetCourses->Courses();
    // $this->load->model('GetNotifications');
    // $note = $this->GetNotifications->NavNotify();
    // print_r($result->result());
    $this->load->view("Admin/app/navbar");
    $this->load->view("Admin/app/sidebar", array('page' => 'Products', 'type' => 'EditProducts'));
    $this->load->view("Admin/app/ByBrands");
}
public function ProductDetail($CVID = null)
{
    if (empty($CVID)) {
        redirect("Products/EditProducts");
    } else {
        $CVID = htmlspecialchars($CVID);
        $this->load->model("GetProducts");
        $result = $this->GetProducts->GetProductDetail($CVID);
        $attachments = $this->GetProducts->GetProductAttachments($CVID);
        // echo "<pre>";
        // print_r($result->result());
        // echo "</pre><br><br>";
        // echo "<pre>";
        // print_r($attachments->result());
        // echo "</pre>";
        $this->load->view("Admin/app/navbar");
        $this->load->view("Admin/app/sidebar", array('page' => 'Products', 'type' => 'EditProducts'));
        $this->load->view("Admin/app/ProductDetail", ["Detail" => $result, "attachments" => $attachments]);
    }
}
public function DeleteProduct($CVID)
{
    $this->load->model("GetProducts");
    $result = $this->GetProducts->DeleteProduct($CVID);
    if ($result) {
        // echo "DEleted";
        ?>
        <script>
            alert("Product Deleted Successfully !");
            window.location.href = "<?= base_url(); ?>Products/EditProducts";
        </script>
    <?php
} else {
    // echo "Not DEleted";
    ?>
        <script>
            alert("Error Deleting Product !");
            window.location.href = "<?= base_url(); ?>Products/EditProducts";
        </script>
    <?php
}
}
public function UpdateProduct()
{
    $design = htmlspecialchars($_POST["design"]);
    $feature = htmlspecialchars($_POST["feature"]);
    // $description = htmlspecialchars($_POST["description"]);
    $price = htmlspecialchars($_POST["price"]);
    $discount = htmlspecialchars($_POST["discount"]);
    $InStock = "";
    $IsHot = "";

    if (empty($_POST["stock"])) {
        $InStock = 0;
    } else {
        $InStock = 1;
    }

    if (empty($_POST["hot"])) {
        $IsHot = 0;
    } else {
        $IsHot = 1;
    }

    $feat = array(
        'Feature' => $feature,
        // 'Description' => $description,
    );
    $col = array(
        'Color' => $design,
        'Price' => $price,
        'Discount' => $discount,
        'InStock' => $InStock,
        'Hot' => $IsHot
    );
    $CVID = $_POST["cvid"];
    $this->load->model("GetProducts");
    $result = $this->GetProducts->UpdateProduct($CVID, $feat, $col);
    if ($result == 1) {
        ?>
        <script>
            alert("Product Updated Successfully !");
            window.location.href = "<?= base_url(); ?>Products/ProductDetail/<?= $CVID ?>";
        </script>
    <?php
} else {
    ?>
        <script>
            alert("Error Updating Product !");
            window.location.href = "<?= base_url(); ?>Products/ProductDetail/<?= $CVID ?>";
        </script>
    <?php
}
}

}
?>