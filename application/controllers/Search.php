<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Search
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 26th Rab.Awwal, 1437A.H (Jan. 7th, 2016)
 */

class Search extends CI_Controller{
    protected $value;

    public function __construct() {
        parent::__construct();

        //$this->gen->checklogin();

        $this->genlib->ajaxOnly();

        $this->load->model(['transaction', 'item', 'productGroup', 'priority', 'product', 'region',
        'platform', 'company', 'productStatus', 'customerVender', 'productTransaction', 'customerType',
        'customerProject', 'productIssue', 'productPerformance']);

        $this->load->helper('text');

        $this->value = $this->input->get('v', TRUE);
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    public function index(){
        /**
         * function will call models to do all kinds of search just to check whether there is a match for the searched value
         * in the search criteria or not. This applies only to global search
         */



        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    public function productSearch(){
        $data['allItems'] = $this->product->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('products/productslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    public function productGroupSearch(){
        $data['allItems'] = $this->productGroup->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('productGroups/groupslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    public function prioritySearch(){
        $data['allItems'] = $this->priority->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('priority/prioritieslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    public function regionSearch(){
        $data['allItems'] = $this->region->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('regions/regionslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    public function platformSearch(){
        $data['allItems'] = $this->platform->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('platforms/platformslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /**
     *
     * @return [type] [description]
     */
    public function companySearch(){
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "Name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";

        $data['allItems'] = $this->company->itemsearch($orderBy, $orderFormat, $this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('companies/companieslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    public function productStatusSearch(){
        $data['allItems'] = $this->productStatus->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('productStatuses/productStatuseslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    public function customerVenderSearch(){
        $data['allItems'] = $this->customerVender->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('customerVenders/customerVenderslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    public function customerTypeSearch(){
        $data['allItems'] = $this->customerType->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('customerTypes/customerTypeslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    public function customerProjectSearch(){
        $data['allItems'] = $this->customerProject->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('customerProjects/customerProjectslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    public function productTransactionsearch(){
        $data['allItems'] = $this->productTransaction->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('productTransactions/productTranstable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /**
     * [productIssueSearch description]
     * @return [type] [description]
     */
    public function productIssueSearch() {
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "Name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";

        $data['allItems'] = $this->productIssue->itemsearch($orderBy, $orderFormat, $this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('productIssues/productIssueslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /**
     * [productIssueSearch description]
     * @return [type] [description]
     */
    public function productPerformanceSearch() {
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "Name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";

        $data['allItems'] = $this->productPerformance->itemsearch($orderBy, $orderFormat, $this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('productPerformances/productPerformanceslisttable', $data, TRUE) : "No match found {$this->value}";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    public function itemSearch(){
        $data['allItems'] = $this->item->itemsearch($this->value);
        $data['sn'] = 1;

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('items/itemslisttable', $data, TRUE) : "No match found";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */



    public function transSearch(){
        $data['allTransactions'] = $this->transaction->transsearch($this->value);
        $data['sn'] = 1;

        $json['transTable'] = $data['allTransactions'] ? $this->load->view('transactions/transtable', $data, TRUE) : "No match found";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function otherSearch(){


        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
}
