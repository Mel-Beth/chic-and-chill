<?php

namespace Controllers;

use Models\PaymentsModel;

class PaymentsController
{
    public function managePayments()
    {
        $paymentsModel = new PaymentsModel();
        $payments = $paymentsModel->getAllPayments();
        include('src/app/Views/admin/admin_payments.php');
    }
}
