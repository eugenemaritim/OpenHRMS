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
 * Payroll Management
 +------------------------------------------------------------------------------
 */

class PayrollController extends Zend_Controller_Action {

    /**
     * Run Payroll
     *
     * @access public
     * @param
     * @return null
     */
    public function runAction() {
        $this->_helper->layout()->setlayout('admin');
        $request = $this->getRequest();
        if($request->isPost() && isset($_POST['confirm'])) {
            $pay_period_model = new Model_PayPeriod();
            $period = $pay_period_model->closePayPeriod();

            //necessary class initializations
            $employee_model = new Model_Employee();
            $employee_payroll_model = new Model_EmployeePayroll();
            $payroll_deduction_model = new Model_PayrollDeductions();
            $payroll_benefit_model = new Model_PayrollBenefit();
            $details_model = new Model_EmploymentDetails();
            $employee_deduction_model = new Model_EmployeeDeduction();
            $emp_benefits_model = new Model_EmployeeBenefit();
            $deduction_model = new Model_Deduction();
            $benefit_model = new Model_Benefit();
            

            //get all the current active employees
            $employees = $employee_model->getActiveEmployees();

            foreach($employees as $k) {
                $emp_id = $k->emp_id;
                //get employee employment details
                $details = $details_model->getEmploymentDetails($emp_id);

                switch($details->payment_type) {
                    case 'salary':
                    $monthly_pay = $details->payment_amount;
                        break;
                    case 'daily_rate':
                    //get the employee number of days
                        $monthly_pay = 0;
                        break;
                    case 'hourly_rate':
                    //get the employee number of hours
                        $monthly_pay = 0;
                        break;
                }//switch

                //insert pay
                $pay = $employee_payroll_model->insertPayrollDetails($emp_id, $period->id, $monthly_pay);
                


                //Employee Benefits
                $emp_benefits = $emp_benefits_model->getEmployeeBenefits($emp_id);
                foreach($emp_benefits as $emp_benefit) {
                    $benefit = $benefit_model->getBenefit($emp_benefit->benefit_id);
                    $payroll_benefit_model->insertPayrollBenefit($emp_id, $period->id, $benefit->name, $benefit->amount, 0);
                }

                
                //get employee deductions
                $emp_deductions = $employee_deduction_model->getEmployeeDeductions($emp_id);

                foreach($emp_deductions as $emp_deduction) {
                    $deduction = $deduction_model->getDeduction($emp_deduction->deduction_id);
                    $payroll_deduction_model->insertPayrollDeduction($emp_id, $period->id, $deduction->name, $deduction->amount, 0);
                }//foreach

               
               
            }//foreach
            $this->_helper->getHelper('FlashMessenger')
					->addMessage("Payroll Run was successfull");
                    return $this->_redirect('/');
        }//if
        
    }
}