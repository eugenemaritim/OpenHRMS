<?php

// +----------------------------------------------------------------------
// | OpenHRMS | Open Source Human Resource Management and Information System
// +----------------------------------------------------------------------
// | Copyright (c) 2012 http://www.openhrms.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Eugene Maritim <maritim@openhrms.net>
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------

/**
 +------------------------------------------------------------------------------
 * Employee Management
 +------------------------------------------------------------------------------
 */

class EmployeeController extends Zend_Controller_Action {

    protected $_model;
    protected $current_period;

    public function init() {
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            return $this->_redirect('/user/login');
        }//if
        $this->_helper->layout()->setLayout('admin');
        $this->_model = new Model_Employee();

        //get a list of all departments
        $dept_model = new Model_Department();
        $departments = $dept_model->getDepartments();
        $this->view->departments = $departments;

        //get all job Groups
        $job_group_model = new Model_JobGroup();
        $job_groups = $job_group_model->getJobGroups(false);
        $this->view->job_groups = $job_groups;

        //get all job Titles
        $job_title_model = new Model_JobTitle();
        $job_titles = $job_title_model->getJobTitles();
        $this->view->job_titles = $job_titles;

        //get the current payPeriod
        $period_model = new Model_PayPeriod();
        $current_period = $period_model->getCurrentPayPeriod();
        $this->current_period = $current_period;

        //get the list of bank
        $bank_model = new Model_Bank();
        $banks = $bank_model->getBanks();
        $this->view->banks = $banks;
        
    }//init

    public function indexAction() {
        return $this->_forward('list');
    }//indexAction

    public function listAction() {
        $this->view->pageTitle = "List of Employees";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $this->_helper->layout()->setLayout('admin');
        $request = $this->getRequest();

        $perPage = 10;
	$query 	 = $request->getParam('query', '');
	$params  = array();
	if ($query == '') {
		$pageIndex = 1;
		$params['pageIndex'] = $pageIndex;
                $employees = $this->_model->findEmployees();
	} else {
		$params = Zend_Json::decode($query);
		$pageIndex = $params['pageIndex'];

		foreach (array('last_name', 'department', 'job_group', 'job_title') as $key) {
			if ((isset($params[$key]) && $params[$key] == '') || !isset($params[$key])) {
				$params[$key] = null;
			}
		}
                $employees = $this->_model->findEmployees($params['last_name'], $params['department'], $params['job_group'], $params['job_title']);
	}

		/**
		 * Paginator
		 */
	$paginator = new Zend_Paginator($employees);
	$paginator->setCurrentPageNumber($pageIndex);
	$paginator->setItemCountPerPage($perPage);

	$this->view->employees =  $employees;
        $dept_model = new Model_Department();
        $departments = $dept_model->getDepartments(false);
        $this->view->departments = $departments;

	$this->view->paginator =  $paginator;
	$this->view->paginatorOptions = array(
			'path' 	   => '',
			'itemLink' => 'javascript: filterEmployees(%d, ' . urlencode(Zend_Json::encode($params)) . ');',
		);

	if ($query != '') {
		$this->_helper->getHelper('viewRenderer')->setNoRender();
		$this->_helper->getHelper('layout')->disableLayout();
		$content = $this->view->render('employee/_filter.phtml');
		$this->getResponse()->setBody($content);
	}
    }//listAction

    public function addAction() {
        $this->view->pageTitle = "Add Employee";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();

         if($request->isPost()) {
             $surname = $request->getPost('surname');
             $other_name = $request->getPost('other_name');
             $dept_id = $request->getPost('department');
             $job_title = $request->getPost('job_title');
             $job_group = $request->getPost('job_group');
             $emp = $this->_model->createEmployee($surname, $other_name, $dept_id, $job_group, $job_title);

             //create new employment details row
             $emp_details = new Model_EmploymentDetails();
             $emp_row = $emp_details->createRow();
             $emp_row->emp_id = $emp->emp_id;
             $emp_row->save();

             if($emp) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Employee $emp->surname, $emp->other_name created. Enter other details as necessary");
                 return $this->_redirect('/employee/view-personal-info/emp/' . $emp->emp_id);
             } else {
                 $this->_helper->getHelper('FlashMessenger')
					->addMessage("An unexpected error occured while adding employee");
                 return $this->_redirect('/employee/add');
             }
         }
    }//addAction

    public function viewAction() {
        return $this->_forward('view-personal-info');
    }//viewAction

    public function viewPersonalInfoAction() {
        $this->view->pageTitle = "Employee Personal Info";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $this->_helper->layout()->setLayout('admin');
        $request = $this->getRequest();
	$id  = $request->getParam('emp');
        $emp = $this->_model->getEmployee($id);
        $this->view->employee = $emp;

    }

    public function viewEmploymentInfoAction() {
        $this->view->pageTitle = "Employment Information";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $this->_helper->layout()->setLayout('admin');
        $request = $this->getRequest();
	$id  = $request->getParam('emp');

        //get employee employment details
        $details_model = new Model_EmploymentDetails();
        $details = $details_model->getEmploymentDetails($id);
        $this->view->details = $details;

        $emp = $this->_model->getEmployee($id);
        $this->view->employee = $emp;
    }

    public function viewRemarksAction() {
        $this->view->pageTitle = "Employee Remarks";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $this->_helper->layout()->setLayout('admin');
        $request = $this->getRequest();
	$id  = $request->getParam('emp');

        $emp = $this->_model->getEmployee($id);
        $this->view->employee = $emp;

        //Employee Comments
        $comment_model = new Model_EmployeeComment();
        $comments = $comment_model->getComments($id);
        $this->view->comments = $comments;
    }//viewRemarksAction

    public function updatePersonalInfoAction() {
        $request = $this->getRequest();
        $params = array();
        if($request->isPost()) {
            $emp_id = $request->getPost('emp_id');
            $params['surname'] = $request->getPost('surname');
            $params['other_name'] = $request->getPost('other_name');
            $params['dept_id'] = $request->getPost('dept_id');
            $params['job_title'] = $request->getPost('job_title');
            $params['job_group'] = $request->getPost('job_group');
            $params['gender'] = $request->getPost('gender');
            $params['contact_cell'] = $request->getPost('contact_cell');
            $params['national_id_no'] = $request->getPost('national_id_no');
            $params['passport_no'] = $request->getPost('passport_no');
            $params['bank_id'] = $request->getPost('bank_id');
            $params['bank_branch'] = $request->getPost('bank_branch');
            $params['account_number'] = $request->getPost('account_number');
            $params['address'] = $request->getPost('address');
            $params['county'] = $request->getPost('county');
            $params['location'] = $request->getPost('location');
            $params['village_estate'] = $request->getPost('village_estate');
            $params['contact_person'] = $request->getPost('contact_person');
            $params['contact_person_no'] = $request->getPost('contact_person_no');

            $info = $this->_model->updatePersonalInfo($emp_id, $params);
            if($info) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Employee Personal Details have been updated");
                 return $this->_redirect('/employee/view-personal-info/emp/' . $emp_id);
            } else {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Could not update Employee personal details");
                 return $this->_redirect('/employee/view-personal-info/emp/' . $emp_id);
            }
        }
    }

    public function updateEmploymentDetailsAction() {
        $request = $this->getRequest();
        if($request->isPost()) {
            $emp_id = $request->getPost('emp_id');
            $payment_type = $request->getPost('payment_type');
            $payment_amount = $request->getPost('payment_amount');
            $std_hours = $request->getPost('working_hours');
            $std_period = $request->getPost('pay_period_hours');
            $time_worked = $request->getPost('time_worked');
            $pay_overtime = $request->getPost('pay_overtime');
            $overtime_rate = $request->getPost('overtime_rate');
            $deduct_nssf = $request->getPost('deduct_nssf');
            $deduct_nhif = $request->getPost('deduct_nhif');
            $nssf_no = $request->getPost('nssf_no');
            $nhif_no = $request->getPost('nhif_no');
            
            $emp_details_model = new Model_EmploymentDetails();
            $details = $emp_details_model->updateEmploymentDetails($emp_id, $payment_type, $payment_amount, $std_hours, $std_period, $time_worked, $pay_overtime, $overtime_rate, $deduct_nssf, $deduct_nhif, $nssf_no, $nhif_no);
            if($details) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Employment details have been updated");
                 return $this->_redirect('/employee/view-employment-info/emp/' . $details->emp_id);
            }
        }
    }//updateEmploymentDetailsAction

    public function addCommentAction() {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();
        $comment_model = new Model_EmployeeComment();
        $request = $this->getRequest();
        $query 	 = $request->getParam('query', '');
        $emp_id = $request->getParam('emp');
        $params = Zend_Json::decode($query);
        foreach (array('remark') as $key) {
            if (isset($params[$key]) && $params[$key] == '') {
            	$params[$key] = null;
            }
	}
        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();
        $by_id = $user->id;
        $comment = $comment_model->addComment($emp_id, $by_id, $params['remark']);
        if($comment) {
            $this->view->comments = $comment_model->getComments($emp_id);
            $content = $this->view->render('employee/_comments.phtml');
            $this->getResponse()->setBody($content);
        }
    }//addCommentAction

    public function manageBenefitsAction() {
        $this->view->pageTitle = "Manage Employee Benefits";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $request = $this->getRequest();
        $id  = $request->getParam('emp');
        if($request->isPost()) {
            //benefits
            $benefits = array();
            foreach($_POST as $p => $val) {
                if(substr($p,0,8) == 'Benefit_') {
                    $benefits[] = substr($p,8);
                }
            }
            $benefits = array_values($benefits);
            $benefits_model = new Model_EmployeeBenefit();
            $benefits = $benefits_model->updateBenefits($id, $benefits);
            if($benefits) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Employee Benefits have been updated");
                 return $this->_redirect('/employee/manage-benefits/emp/' . $id);
            }
        }
        $this->_helper->layout()->setLayout('admin');

        $emp = $this->_model->getEmployee($id);
        $this->view->employee = $emp;

        //get the benefits for employee based on pay grade
        $benefits_model = new Model_Benefit();
        $benefits = $benefits_model->getBenefitsJobGroup($emp->job_group);
        $this->view->benefits = $benefits;

        //get all the employee benefits in the system
        $emp_benefit_model = new Model_EmployeeBenefit();
        $emp_benefits = $emp_benefit_model->getEmployeeBenefits($id);
        $this->view->emp_benefits = $emp_benefits;
    }//manageBenefitsAction

    //--------------------------------------------------------------------------
    //  Manage Employee Cooperatives
    //--------------------------------------------------------------------------
    public function manageCoopsAction() {
        $this->view->pageTitle = "Manage Employee Cooperatives";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $employee_coop_model = new Model_EmployeeCoops();
        $request = $this->getRequest();
        $emp_id = $request->getParam('emp');
        $emp = $this->_model->getEmployee($emp_id);
        $this->view->employee = $emp;
        if($request->isPost()) {
            $coop_id = $request->getPost('coop_id');
            $amount = $request->getPost('amount');
            $emp_no = $request->getPost('mem_no');
            $coop = $employee_coop_model->insertCoopDetails($emp_id, $coop_id, $amount, $emp_no);
            if($coop) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Employee Cooperative membership has been added");
                 return $this->_redirect('/employee/manage-coops/emp/' . $emp_id);
            }
        }//if
        $emp_coops = $employee_coop_model->getEmployeeCoops($emp_id);
        $this->view->emp_coops = $emp_coops;

        $coops_model = new Model_Cooperative();
        $coops = $coops_model->getCooperatives();
        $this->view->coops = $coops;

    }//manageCoopsAction

    public function updateCoopsAction() {
        $this->view->pageTitle = "Update Employee Co-operatives";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $employee_coop_model = new Model_EmployeeCoops();
        $request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');
            $coop_id = $request->getPost('coop_id');
            $emp_id = $request->getPost('emp_id');
            $emp_no = $request->getPost('mem_no');
            $amount = $request->getPost('amount');
            $coop = $employee_coop_model->updateCoopDetails($id, $emp_id, $coop_id, $amount, $emp_no);
            if($coop) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Employee Cooperative membership has been updated");
                 return $this->_redirect('/employee/manage-coops/emp/' . $coop->emp_id);
            } else {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("An error occured while updating employee cooperative details");
                 return $this->_redirect('/employee/manage-coops/emp/' . $coop->emp_id);
            }//if
        }//if
        $id = $request->getParam('id');
        $coop = $employee_coop_model->getEmployeeCoopRow($id);
        $this->view->coop = $coop;

        $coops_model = new Model_Cooperative();
        $coops = $coops_model->getCooperatives();
        $this->view->coops = $coops;
    }//updateCoopsAction

    public function deleteCoopsAction() {
        $employee_coop_model = new Model_EmployeeCoops();
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');


            $delete = $employee_coop_model->deleteCoopDetails($id);
            if($delete) {
                $this->getResponse()->setBody('RESULT_OK');
            } else {
                $this->getResponse()->setBody('RESULT_ERROR');
            }
        }
    }//deleteCoopsAction

    //------------------------------------------------------------------------
    //  Manage Employee Pension Funds
    //------------------------------------------------------------------------

    public function managePensionFundsAction() {
        $this->view->pageTitle = "Manage Employee Pension Fund";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $employee_pf_model = new Model_EmployeePensionFunds();
        $request = $this->getRequest();
        $emp_id = $request->getParam('emp');
        $emp = $this->_model->getEmployee($emp_id);
        $this->view->employee = $emp;
        if($request->isPost()) {
            $fund_id = $request->getPost('fund_id');
            $amount = $request->getPost('amount');
            $emp_no = $request->getPost('mem_no');
            $is_tax_exempt = $request->getPost('is_tax_exempt');
            $pf = $employee_pf_model->insertPensionFundDetails($emp_id, $fund_id, $amount, $emp_no);
            if($pf) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Employee Pension Fund has been added");
                 return $this->_redirect('/employee/manage-pension-funds/emp/' . $emp_id);
            }
        }//if
        $emp_pf = $employee_pf_model->getEmployeePensionFunds($emp_id);
        $this->view->emp_pension_funds = $emp_pf;

        $pf_model = new Model_PensionFund();
        $pension_funds = $pf_model->getPensionFunds();
        $this->view->pension_funds = $pension_funds;
    }//managePensionFundsAction


    public function updatePensionFundAction() {
        $this->view->pageTitle = "Update Employee Pension Fund";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $employee_pf_model = new Model_EmployeePensionFunds();
        $request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');
            $coop_id = $request->getPost('pension_fund_id');
            $emp_id = $request->getPost('emp_id');
            $emp_no = $request->getPost('mem_no');
            $amount = $request->getPost('amount');
            $is_tax_exempt = $request->getPost('is_tax_exempt');
            $pf = $employee_pf_model->updatePensionFundDetails($id, $emp_id, $coop_id, $amount, $emp_no, $is_tax_exempt);
            if($pf) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Employee pension fund has been updated");
                 return $this->_redirect('/employee/manage-pension-funds/emp/' . $pf->emp_id);
            } else {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("An error occured while updating employee pension fund details");
                 return $this->_redirect('/employee/manage-pension-funds/emp/' . $pf->emp_id);
            }//if
        }//if
        $id = $request->getParam('id');
        $pf = $employee_pf_model->getEmployeePensionFundRow($id);
        $this->view->pension_fund = $pf;

        $pf_model = new Model_PensionFund();
        $pension_funds = $pf_model->getPensionFunds();
        $this->view->pension_funds = $pension_funds;
    }//updatePensionFundsAction

    public function deletePensionFundAction() {
        $employee_pf_model = new Model_EmployeePensionFunds();
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');


            $delete = $employee_pf_model->deletePensionFundDetails($id);
            if($delete) {
                $this->getResponse()->setBody('RESULT_OK');
            } else {
                $this->getResponse()->setBody('RESULT_ERROR');
            }
        }
    }//deletePensionFund

    //--------------------------------------------------------------------------
    //  Manage Lump Sum payments
    //--------------------------------------------------------------------------
    public function lumpSumPaymentAction() {
        $this->view->pageTitle = "Employee Lump Sum Payments";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $income_model = new Model_EmployeeIncome();
        $request = $this->getRequest();
        $emp_id = $request->getParam('emp');
        if($request->isPost()) {
            $name = $request->getPost('name');
            $amount = $request->getPost('amount');
            $is_tax_exempt = $request->getPost('is_tax_exempt');
            $type = 'lump_sum_payment';
            $income = $income_model->insertPeriodIncomeDetails($emp_id, $this->current_period->id, $name, $amount, $type, $is_tax_exempt);
            if($income) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Employee Lump Sum Payment has been entered");
                 return $this->_redirect('/employee/lump-sum-payment/emp/' . $emp_id);
            } else {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("An error occured while adding Lump sum");
                 return $this->_redirect('/employee/lump-sum-payment/emp/' . $emp_id);
            }
        }//if

        //get all income for an employee for this pay period
        $incomes = $income_model->getEmployeePeriodIncomeDetails($emp_id, $this->current_period->id);
        $this->view->incomes = $incomes;

        //get employee
        $emp= $this->_model->getEmployee($emp_id);
        $this->view->employee = $emp;
        
    }//lumpSumPaymentAction

    public function updateLumpSumPaymentAction() {
        $income_model = new Model_EmployeeIncome();
        $request = $this->getRequest();
        $id = $request->getParam('id');
        $emp_id = $request->getParam('emp');
        if($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $amount = $request->getPost('amount');
            
            $income = $income_model->updatePeriodIncomeDetails($id, $name, $amount, $is_tax_exempt);
            if($income) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Lump Sum payment has been updated");
                 return $this->_redirect('/employee/lump-sum-payment/emp/' . $income->emp_id);
            } else {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("An Error occured while updating Lump Sum Payment");
                 return $this->_redirect('/employee/lump-sum-payment/emp/' . $income->emp_id);
            }//if
        }//if
        $income = $income_model->getIncome($id);
        $this->view->lump_sum = $income;
    }//updateLumpSumPayment

    public function deleteLumpSumPaymentAction() {
        $income_model = new Model_EmployeeIncome();
        $this->_helper->getHelper('viewRenderer')->setNoRender();
	$this->_helper->getHelper('layout')->disableLayout();

	$request = $this->getRequest();
        if($request->isPost()) {
            $id = $request->getPost('id');


            $delete = $income_model->deleteEmployeeIncome($id);
            if($delete) {
                $this->getResponse()->setBody('RESULT_OK');
            } else {
                $this->getResponse()->setBody('RESULT_ERROR');
            }
        }
    }//deleteLumpSumPayment

    //-------------------------------------------------------------------------
    //  Manage Employee Deductions
    //-------------------------------------------------------------------------
    public function manageDeductionsAction() {
        $request = $this->getRequest();
        $id  = $request->getParam('emp');
        if($request->isPost()) {
            //benefits
            $deductions = array();
            foreach($_POST as $p => $val) {
                if(substr($p,0,10) == 'Deduction_') {
                    $deductions[] = substr($p,10);
                }
            }
            $deductions = array_values($deductions);
            $deductions_model = new Model_EmployeeDeduction();
            $deductions = $deductions_model->updateDeductions($id, $deductions);
            if($deductions) {
                $this->_helper->getHelper('FlashMessenger')
					->addMessage("Employee Deductions have been updated");
                 return $this->_redirect('/employee/manage-deductions/emp/' . $id);
            }
        }
        $this->_helper->layout()->setLayout('admin');

        $emp = $this->_model->getEmployee($id);
        $this->view->employee = $emp;

        //get all the deductions in the system
        $deductions_model = new Model_Deduction();
        $deductions = $deductions_model->getDeductions($emp->job_group);
        $this->view->deductions = $deductions;

        //get all the employee deductions
        $emp_deduction_model = new Model_EmployeeDeduction();
        $emp_deductions = $emp_deduction_model->getEmployeeDeductions($id);
        $this->view->emp_deductions = $emp_deductions;
    }//manageDeductions

}
