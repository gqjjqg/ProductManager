<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Items
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 31st Dec, 2015
 */
class Priorities extends CI_Controller{

    public function __construct(){
        parent::__construct();

        $this->genlib->checkLogin();

        $this->load->model(['priority']);
    }

    /**
     *
     */
    public function index(){
        $data['pageContent'] = $this->load->view('priority/priorities', '', TRUE);
        $data['pageTitle'] = "Priority";

        $this->load->view('main', $data);
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     * "lilt" = "load Items List Table"
     */
    public function lilt(){
        $this->genlib->ajaxOnly();

        $this->load->helper('text');

        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "Name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";

        //count the total number of items in db
        $totalItems = $this->db->count_all('priority');

        $this->load->library('pagination');

        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri

        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration

        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalItems, "priorities/lilt", $limit, ['onclick'=>'return lilt(this.href);']);

        $this->pagination->initialize($config);//initialize the library class

        //get all items from db
        $data['allItems'] = $this->priority->getAll($orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalItems > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['allItems'])) . " of " . $totalItems : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;

        $json['itemsListTable'] = $this->load->view('priority/prioritieslisttable', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */



    public function add(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('priorityValue', 'Priority Value', ['required', 'trim', 'numeric', 'is_unique[priority.Value]'], //numeric
                ['required'=>"required"]);
        $this->form_validation->set_rules('priorityName', 'Priority Name', ['required', 'trim', 'max_length[80]'], ['required'=>"required"]);


        if($this->form_validation->run() !== FALSE){
            $this->db->trans_start();//start transaction

            /**
             * insert info into db
             * function header: add($itemName, $itemQuantity, $itemPrice, $itemDescription, $itemCode)
             */
            $insertedId = $this->priority->add(set_value('priorityValue'), set_value('priorityName'), set_value('description'));

            $itemName = set_value('priorityName');

            //insert into eventlog
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $desc = "New addition of priority:{$itemName}";

            $insertedId ? $this->genmod->addevent("Creation of new priority", $insertedId, $desc, "priority", $this->session->admin_id) : "";

            $this->db->trans_complete();

            $json = $this->db->trans_status() !== FALSE ?
                    ['status'=>1, 'msg'=>"Priority successfully added"]
                    :
                    ['status'=>0, 'msg'=>"Oops! Unexpected server error! Please contact administrator for help. Sorry for the embarrassment"];
        }

        else{
            //return all error messages
            $json = $this->form_validation->error_array();//get an array of all errors

            $json['msg'] = "One or more required fields are empty or not correctly filled";
            $json['status'] = 0;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    /**
     * Primarily used to check whether an item already has a particular random code being generated for a new item
     * @param type $selColName
     * @param type $whereColName
     * @param type $colValue
     */
    public function gettablecol($selColName, $whereColName, $colValue){
        $a = $this->genmod->gettablecol('items', $selColName, $whereColName, $colValue);

        $json['status'] = $a ? 1 : 0;
        $json['colVal'] = $a;

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     *
     */
    public function gcoandqty(){
        $json['status'] = 0;

        $itemCode = $this->input->get('_iC', TRUE);

        if($itemCode){
            $item_info = $this->item->getItemInfo(['code'=>$itemCode], ['quantity', 'unitPrice', 'description']);

            if($item_info){
                $json['availQty'] = (int)$item_info->quantity;
                $json['unitPrice'] = $item_info->unitPrice;
                $json['description'] = $item_info->description;
                $json['status'] = 1;
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }



    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    public function updatestock(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('_iId', 'Item ID', ['required', 'trim', 'numeric'], ['required'=>"required"]);
        $this->form_validation->set_rules('_upType', 'Update type', ['required', 'trim', 'in_list[newStock,deficit]'], ['required'=>"required"]);
        $this->form_validation->set_rules('qty', 'Quantity', ['required', 'trim', 'numeric'], ['required'=>"required"]);
        $this->form_validation->set_rules('desc', 'Update Description', ['required', 'trim'], ['required'=>"required"]);

        if($this->form_validation->run() !== FALSE){
            //update stock based on the update type
            $updateType = set_value('_upType');
            $itemId = set_value('_iId');
            $qty = set_value('qty');
            $desc = set_value('desc');

            $this->db->trans_start();

            $updated = $updateType === "deficit"
                    ?
                $this->item->deficit($itemId, $qty, $desc)
                    :
                $this->item->newstock($itemId, $qty, $desc);

            //add event to log if successful
            $stockUpdateType = $updateType === "deficit" ? "Deficit" : "New Stock";

            $event = "Stock Update ($stockUpdateType)";

            $action = $updateType === "deficit" ? "removed from" : "added to";//action that happened

            $eventDesc = "<p>{$qty} quantities of {$this->genmod->gettablecol('items', 'name', 'id', $itemId)} was {$action} stock</p>
                Reason: <p>{$desc}</p>";

            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $updated ? $this->genmod->addevent($event, $itemId, $eventDesc, "items", $this->session->admin_id) : "";

            $this->db->trans_complete();//end transaction

            $json['status'] = $this->db->trans_status() !== FALSE ? 1 : 0;
            $json['msg'] = $updated ? "Stock successfully updated" : "Unable to update stock at this time. Please try again later";
        }

        else{
            $json['status'] = 0;
            $json['msg'] = "One or more required fields are empty or not correctly filled";
            $json = $this->form_validation->error_array();
        }


        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


   /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function edit(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('_iId', 'Item ID', ['required', 'trim', 'numeric']);
        $this->form_validation->set_rules('itemName', 'Priority Name', ['required', 'trim',
            'callback_crosscheckName['.$this->input->post('_iId', TRUE).']'], ['required'=>'required']);
        $this->form_validation->set_rules('itemValue', 'Priority Value', ['required', 'trim', 'numeric',
            'callback_crosscheckValue['.$this->input->post('_iId', TRUE).']'], ['required'=>"required"]);
        $this->form_validation->set_rules('itemDesc', 'Item Description', ['trim']);

        if($this->form_validation->run() !== FALSE){
            $itemId = set_value('_iId');
            $itemDesc = set_value('itemDesc');
            $itemName = set_value('itemName');
            $itemValue = set_value('itemValue');

            //update item in db
            $updated = $this->priority->edit($itemId, $itemName, $itemValue, $itemDesc);

            $json['status'] = $updated ? 1 : 0;

            //add event to log
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $desc = "Details of item with code '$itemId' was updated";

            $this->genmod->addevent("Group Info Update", $itemId, $desc, 'product group', $this->session->admin_id);
        }

        else{
            $json['status'] = 0;
            $json = $this->form_validation->error_array();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }




   /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function crosscheckName($itemName, $itemId){
        //check db to ensure name was previously used for the item we are updating
        $itemWithName = $this->genmod->getTableCol('priority', 'id', 'Name', $itemName);

        //if item name does not exist or it exist but it's the name of current item
        if(!$itemWithName || ($itemWithName == $itemId)){
            return TRUE;
        }

        else{//if it exist
            $this->form_validation->set_message('crosscheckName', 'There is an item with this name');

            return FALSE;
        }
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    /**
     *
     * @param type $item_code
     * @param type $item_id
     * @return boolean
     */
    public function crosscheckValue($item_code, $item_id){
        //check db to ensure item code was previously used for the item we are updating
        $item_with_code = $this->genmod->getTableCol('priority', 'id', 'Value', $item_code);

        //if item code does not exist or it exist but it's the code of current item
        if(!$item_with_code || ($item_with_code == $item_id)){
            return TRUE;
        }

        else{//if it exist
            $this->form_validation->set_message('crosscheckValue', 'There is an item with this value');

            return FALSE;
        }
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    public function delete(){
        $this->genlib->ajaxOnly();

        $json['status'] = 0;
        $item_id = $this->input->post('i', TRUE);

        if($item_id){
            $this->db->where('id', $item_id)->delete('priority');

            $json['status'] = 1;
        }

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}
