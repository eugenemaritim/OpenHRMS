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
 * Reports Management
 +------------------------------------------------------------------------------
 */
class ReportController extends Zend_Controller_Action {

    public function init() {
        //get a list of all departments
        $dept_model = new Model_Department();
        $departments = $dept_model->getDepartments(false);
        $this->view->departments = $departments;

        //get all job Groups
        $job_group_model = new Model_JobGroup();
        $job_groups = $job_group_model->getJobGroups(false);
        $this->view->job_groups = $job_groups;

        //get all job Titles
        $job_title_model = new Model_JobTitle();
        $job_titles = $job_title_model->getJobTitles();
        $this->view->job_titles = $job_titles;

        $bank_model = new Model_Bank();
        $banks = $bank_model->getBanks();
        $this->view->banks = $banks;

        $this->_helper->layout()->setLayout('admin');
    }//init

    public function employeeListAction() {
        $this->view->pageTitle = "Generate List of Employees";
        $this->view->headTitle($this->view->pageTitle, 'PREPEND');
        $c_model = new Model_Company();
        $details = $c_model->getCompanyDetails();
        $emp_model = new Model_Employee();
        $request = $this->getRequest();
        $pdf  = new Zend_Pdf();
        if($request->isPost()) {
            $job_group = $request->getPost('job_group');
            $bank_id = $request->getPost('bank_id');
            $dept_id = $request->getPost('dept_id');
            $job_title = $request->getPost('job_title');
            $employees = $emp_model->getEmployeeList($dept_id, $bank_id, $job_group, $job_title);
             // create A4 page
                $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
                $page->setLineColor(new Zend_Pdf_Color_Rgb(0,0,0));
                // define font resource
                $font = Zend_Pdf_Font::fontWithPath(APPLICATION_PATH .'/../public/font/verdana.ttf');

                // draw another line near the bottom of the page
                $page->drawLine(15, 30, ($page->getWidth()-15), 30);

                //enter Company information at the top
                $page->setFont($font, 11);
                $page->drawText("$details->company_name", 15, 800);
                $page->setFont($font, 9);
                $page->drawText("Tel: $details->tel_no", 15, 785);
                $page->drawText("Employee Roll", 15, 770);

                $page->setFont($font, 9);
                $page->drawLine(15, 720, ($page->getWidth()-15), 720);
                $page->drawText("Emp No", 25, 705);
                $page->drawText("Name", 85, 705);
                $page->drawText("Job Title", 270, 705);
                $page->drawText("Department", 400, 705);
                $page->drawLine(15, 695, ($page->getWidth()-15), 695);
                $page->drawLine(15, 30, 15, 720);
                $page->drawLine(($page->getWidth()-15), 30, ($page->getWidth()-15), 720);
                $page->drawLine(80, 30, 80, 720);
                $page->drawLine(260, 30, 260, 720);
                $page->drawLine(390, 30, 390, 720);

                $page->setFont($font, 8);
                $start = 675;
                foreach($employees as $emp) {
                    $page->drawText("$emp->emp_id", 35, $start);
                    $page->drawText("$emp->surname, $emp->other_name", 85, $start);
                    $page->drawText($this->view->jobTitle()->getJobTitleName($emp->job_title), 270, $start);
                    $page->drawText($this->view->department()->getDepartmentName($emp->dept_id), 400, $start);
                    $start = $start - 17;
                }

            $pdf->pages[] = $page;
            // save as file
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender();

            $this->getResponse()
             ->setHeader('Content-Disposition:inline', ';filename=employee_roll.pdf')
                         ->setHeader('Content-Type', 'application/x-pdf');

            echo $pdf->render();

        }
       
    }//employeeListAction

    /**
     * Generate Payroll Employee Payslips
     *
     * @access public
     * @param
     * @return
     */
    public function generatePayslipsAction() {
        $this->_helper->layout()->setlayout('admin');
        //get the pay period, default to last pay period
        $request = $this->getRequest();
        if($request->isPost()) {

            $c_model = new Model_Company();
            $details = $c_model->getCompanyDetails();
            $year = $request->getPost('year');
            $month = $request->getPost('month');
            //get the period
            $pp_model = new Model_PayPeriod();
            $period_id = $pp_model->getPayPeriod($year, $month);
            //get details of employees in pay period
            $ep_model = new Model_EmployeePayroll();
            $emp_model = new Model_Employee();
            $employeeList = $ep_model->getEmployeesInPayPeriod($period_id);
            $pdf = new Zend_Pdf();
            foreach($employeeList as $k) {
                //get the employee names
                $employee = $emp_model->getEmployee($k->emp_id);
                // create A4 page
                $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
                $page->setLineColor(new Zend_Pdf_Color_Rgb(0,0,0));
                // define font resource
                $font = Zend_Pdf_Font::fontWithPath(APPLICATION_PATH .'/../public/font/verdana.ttf');

                // draw another line near the bottom of the page
                $page->drawLine(30, 30, ($page->getWidth()-30), 30);
                $page->setFont($font, 7);
                $page->drawText("Confidential: $employee->surname, $employee->other_name payslip", ($page->getWidth()/3), 15);

                //enter Company information at the top
                $page->setFont($font, 11);
                $page->drawText("$details->company_name", 15, 800);
                $page->setFont($font, 9);
                $page->drawText("Tel: $details->tel_no", 15, 785);
                $page->drawText("Payslip for " . $this->view->functions()->getMonthName($month). ', ' . $year , 15, 770);

                //Employee Information
                $page->setFont($font, 8);
                $page->drawText("Name: $employee->surname, $employee->other_name", 15, 715);
                $page->drawText("Emp No: $employee->emp_id", 15, 700);
                $page->drawText($this->view->jobTitle()->getJobTitleName($employee->job_title), 15, 685);
                $page->drawText("Job Group: ". $this->view->jobGroup()->getJobGroupName($employee->job_group), 15, 670);
                $page->drawText("Dept: ".$this->view->department()->getDepartmentName($employee->dept_id), 15, 655);

                //Earnings
                $page->drawLine(15, 620, ($page->getWidth()-15), 620);
                $page->drawText("Earnings", 15, 605);
                $page->drawLine(15, 595, ($page->getWidth()-15), 595);

                //Basic Pay
                $page->drawText("Basic Pay", 15, 575);
                $page->drawText("$k->monthly_pay    ", ($page->getWidth()/3), 575);
                $income = $k->monthly_pay;

                $start = 540;
                $page->drawText("Allowances", 15, 555);
                $emp_benefits_model = new Model_PayrollBenefit();
                $emp_benefits = $emp_benefits_model->getEmployeeBenefitsPayPeriod($k->emp_id, $period_id);
                $spacing = 0;
                $total_benefits = 0;
                foreach($emp_benefits as $benefit) {
                    $page->drawText("$benefit->benefit_name", 30, $start - $spacing);
                    $page->drawText("$benefit->benefit_amount", ($page->getWidth()/3), $start - $spacing );
                    $spacing = $spacing + 15;
                    $total_benefits += $benefit->benefit_amount;
                }

                $start = ($start - $spacing);
                $page->drawLine(($page->getWidth()/3), $start, ($page->getWidth()/3) + 35, $start);
                $start = ($start - $spacing);

                //show total income
                $page->drawText($income + $total_benefits, ($page->getWidth()/3), $start + 15);
                $start -= 15;

                //Deductions
                $emp_deductions_model = new Model_PayrollDeductions();
                $emp_deductions = $emp_deductions_model->getEmployeeDeductionsPayPeriod($k->emp_id, $period_id);
                $page->drawLine(15, $start, ($page->getWidth()-15), $start);
                $page->drawText("Deductions", 15, ($start - 15));
                $page->drawLine(15, ($start - 25), ($page->getWidth()-15), ($start - 25));
                $spacing = 0;
                foreach($emp_deductions as $deduction) {
                    $page->drawText("$deduction->deduction_name", 15, ($start - 45) - $spacing);
                    $page->drawText("$deduction->deduction_amount", ($page->getWidth()/3), ($start - 45) - $spacing );
                    $spacing = $spacing + 15;
                }

                $start = ($start - $spacing) - 30;

                $pdf->pages[] = $page;
            }

            // save as file
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender();

            $this->getResponse()
             ->setHeader('Content-Disposition:inline', ';filename=paylips.pdf')
             ->setHeader('Content-Type', 'application/x-pdf');

            echo $pdf->render();
        }
    }//generatePayslipsAction
}