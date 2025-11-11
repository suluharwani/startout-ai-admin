<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InvoiceModel;
use App\Models\SubscriptionModel;
use App\Models\ClientModel;

class Invoices extends BaseController
{
    protected $invoiceModel;
    protected $subscriptionModel;
    protected $clientModel;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->subscriptionModel = new SubscriptionModel();
        $this->clientModel = new ClientModel();
    }

    public function index()
    {
        $invoices = $this->invoiceModel->getInvoicesWithDetails();
        $stats = $this->invoiceModel->getInvoiceStats();

        $data = [
            'title' => 'Manage Invoices',
            'invoices' => $invoices,
            'stats' => $stats
        ];

        return view('admin/invoices/index', $data);
    }

    public function create()
    {
        $clients = $this->clientModel->where('status', 'active')->findAll();
        $subscriptions = $this->subscriptionModel->getSubscriptionsForDropdown();
        $invoiceNumber = $this->invoiceModel->generateInvoiceNumber();

        // If no active clients, redirect to create client first
        if (empty($clients)) {
            return redirect()->to('/admin/clients/create')
                           ->with('error', 'Please create at least one active client before creating an invoice.');
        }

        $data = [
            'title' => 'Create New Invoice',
            'clients' => $clients,
            'subscriptions' => $subscriptions,
            'invoiceNumber' => $invoiceNumber
        ];

        return view('admin/invoices/form', $data);
    }

    public function store()
    {
        if (!$this->validate($this->invoiceModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $amount = floatval($this->request->getPost('amount'));
        $taxAmount = floatval($this->request->getPost('tax_amount') ?: 0);
        $totalAmount = $amount + $taxAmount;

        $invoiceData = [
            'invoice_number' => $this->request->getPost('invoice_number'),
            'subscription_id' => $this->request->getPost('subscription_id') ?: null,
            'client_id' => $this->request->getPost('client_id'),
            'issue_date' => $this->request->getPost('issue_date'),
            'due_date' => $this->request->getPost('due_date'),
            'amount' => $amount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'currency' => $this->request->getPost('currency'),
            'status' => $this->request->getPost('status'),
            'payment_method' => $this->request->getPost('payment_method'),
            'notes' => $this->request->getPost('notes'),
            'created_by' => session()->get('admin_id')
        ];

        if ($this->invoiceModel->save($invoiceData)) {
            return redirect()->to('/admin/invoices')->with('success', 'Invoice created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create invoice. Please try again.');
        }
    }

    public function view($id)
    {
        $invoice = $this->invoiceModel->getInvoiceWithDetails($id);
        
        if (!$invoice) {
            return redirect()->to('/admin/invoices')->with('error', 'Invoice not found.');
        }

        $data = [
            'title' => 'Invoice #' . $invoice['invoice_number'],
            'invoice' => $invoice
        ];

        return view('admin/invoices/view', $data);
    }

    public function edit($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            return redirect()->to('/admin/invoices')->with('error', 'Invoice not found.');
        }

        $clients = $this->clientModel->where('status', 'active')->findAll();
        $subscriptions = $this->subscriptionModel->where('status', 'active')->findAll();

        $data = [
            'title' => 'Edit Invoice',
            'invoice' => $invoice,
            'clients' => $clients,
            'subscriptions' => $subscriptions
        ];

        return view('admin/invoices/form', $data);
    }

    public function update($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            return redirect()->to('/admin/invoices')->with('error', 'Invoice not found.');
        }

        if (!$this->validate($this->invoiceModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $amount = floatval($this->request->getPost('amount'));
        $taxAmount = floatval($this->request->getPost('tax_amount') ?: 0);
        $totalAmount = $amount + $taxAmount;

        $invoiceData = [
            'id' => $id,
            'invoice_number' => $this->request->getPost('invoice_number'),
            'subscription_id' => $this->request->getPost('subscription_id') ?: null,
            'client_id' => $this->request->getPost('client_id'),
            'issue_date' => $this->request->getPost('issue_date'),
            'due_date' => $this->request->getPost('due_date'),
            'amount' => $amount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'currency' => $this->request->getPost('currency'),
            'status' => $this->request->getPost('status'),
            'payment_method' => $this->request->getPost('payment_method'),
            'notes' => $this->request->getPost('notes')
        ];

        if ($this->invoiceModel->save($invoiceData)) {
            return redirect()->to('/admin/invoices')->with('success', 'Invoice updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update invoice. Please try again.');
        }
    }

    public function delete($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            return redirect()->to('/admin/invoices')->with('error', 'Invoice not found.');
        }

        if ($this->invoiceModel->delete($id)) {
            return redirect()->to('/admin/invoices')->with('success', 'Invoice deleted successfully!');
        } else {
            return redirect()->to('/admin/invoices')->with('error', 'Failed to delete invoice. Please try again.');
        }
    }

    public function markAsPaid($id)
    {
        $invoice = $this->invoiceModel->find($id);
        
        if (!$invoice) {
            return redirect()->to('/admin/invoices')->with('error', 'Invoice not found.');
        }

        $updateData = [
            'status' => 'paid',
            'paid_date' => date('Y-m-d'),
            'payment_method' => $this->request->getPost('payment_method') ?: 'bank_transfer'
        ];

        if ($this->invoiceModel->update($id, $updateData)) {
            return redirect()->to('/admin/invoices')->with('success', 'Invoice marked as paid successfully!');
        } else {
            return redirect()->to('/admin/invoices')->with('error', 'Failed to mark invoice as paid.');
        }
    }

    // ... method lainnya tetap ...

    public function download($id)
    {
        $invoice = $this->invoiceModel->getInvoiceWithDetails($id);
        
        if (!$invoice) {
            return redirect()->to('/admin/invoices')->with('error', 'Invoice not found.');
        }

        // Load DomPDF
        $dompdf = new \Dompdf\Dompdf();
        
        // HTML content for PDF
        $html = $this->generateInvoiceHTML($invoice);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream("invoice-{$invoice['invoice_number']}.pdf", [
            "Attachment" => true
        ]);
        
        exit;
    }

    /**
     * Generate HTML for PDF invoice
     */
    private function generateInvoiceHTML($invoice)
    {
        $companyInfo = [
            'name' => 'Startout AI',
            'address' => '123 AI Boulevard, San Francisco, CA 94107',
            'phone' => '+1 (800) 123-4567',
            'email' => 'info@startoutai.com',
            'website' => 'www.startoutai.com'
        ];

        $statusColors = [
            'draft' => '#6c757d',
            'sent' => '#0dcaf0',
            'paid' => '#198754',
            'overdue' => '#dc3545',
            'cancelled' => '#ffc107'
        ];

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Invoice ' . $invoice['invoice_number'] . '</title>
            <style>
                body {
                    font-family: "DejaVu Sans", "Arial", sans-serif;
                    font-size: 12px;
                    line-height: 1.4;
                    color: #333;
                    margin: 0;
                    padding: 20px;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                }
                .header {
                    border-bottom: 2px solid #007bff;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .company-info {
                    float: left;
                    width: 50%;
                }
                .invoice-info {
                    float: right;
                    width: 45%;
                    text-align: right;
                }
                .clear {
                    clear: both;
                }
                .section {
                    margin-bottom: 25px;
                }
                .billing-info {
                    display: flex;
                    justify-content: space-between;
                }
                .from-address, .to-address {
                    width: 48%;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                th {
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                    padding: 10px;
                    text-align: left;
                    font-weight: bold;
                }
                td {
                    border: 1px solid #dee2e6;
                    padding: 10px;
                }
                .text-right {
                    text-align: right;
                }
                .text-center {
                    text-align: center;
                }
                .total-row {
                    background-color: #f8f9fa;
                    font-weight: bold;
                }
                .status-badge {
                    display: inline-block;
                    padding: 4px 8px;
                    border-radius: 4px;
                    color: white;
                    font-size: 11px;
                    font-weight: bold;
                }
                .footer {
                    margin-top: 50px;
                    padding-top: 20px;
                    border-top: 1px solid #dee2e6;
                    text-align: center;
                    color: #6c757d;
                    font-size: 11px;
                }
                .notes {
                    background-color: #f8f9fa;
                    padding: 15px;
                    border-radius: 4px;
                    margin-top: 20px;
                }
                h1 {
                    color: #007bff;
                    margin: 0 0 10px 0;
                    font-size: 24px;
                }
                h2 {
                    color: #495057;
                    margin: 0 0 15px 0;
                    font-size: 18px;
                    border-bottom: 1px solid #dee2e6;
                    padding-bottom: 5px;
                }
                .due-date {
                    color: #dc3545;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <!-- Header -->
                <div class="header">
                    <div class="company-info">
                        <h1>' . $companyInfo['name'] . '</h1>
                        <p>' . $companyInfo['address'] . '<br>
                        Phone: ' . $companyInfo['phone'] . '<br>
                        Email: ' . $companyInfo['email'] . '<br>
                        Website: ' . $companyInfo['website'] . '</p>
                    </div>
                    <div class="invoice-info">
                        <h1>INVOICE</h1>
                        <p><strong>Invoice #:</strong> ' . $invoice['invoice_number'] . '</p>
                        <p><strong>Issue Date:</strong> ' . date('F j, Y', strtotime($invoice['issue_date'])) . '</p>
                        <p><strong>Due Date:</strong> <span class="due-date">' . date('F j, Y', strtotime($invoice['due_date'])) . '</span></p>
                        <p><strong>Status:</strong> <span class="status-badge" style="background-color: ' . $statusColors[$invoice['status']] . '">' . strtoupper($invoice['status']) . '</span></p>
                    </div>
                    <div class="clear"></div>
                </div>

                <!-- Billing Information -->
                <div class="section">
                    <h2>Billing Information</h2>
                    <div class="billing-info">
                        <div class="from-address">
                            <strong>From:</strong><br>
                            ' . $companyInfo['name'] . '<br>
                            ' . $companyInfo['address'] . '<br>
                            ' . $companyInfo['phone'] . '<br>
                            ' . $companyInfo['email'] . '
                        </div>
                        <div class="to-address">
                            <strong>To:</strong><br>
                            ' . $invoice['company_name'] . '<br>';
        
        if (!empty($invoice['contact_person'])) {
            $html .= 'Attn: ' . $invoice['contact_person'] . '<br>';
        }
        if (!empty($invoice['address'])) {
            $html .= $invoice['address'] . '<br>';
        }
        if (!empty($invoice['city'])) {
            $html .= $invoice['city'];
            if (!empty($invoice['country'])) {
                $html .= ', ' . $invoice['country'];
            }
            $html .= '<br>';
        }
        if (!empty($invoice['email'])) {
            $html .= $invoice['email'] . '<br>';
        }
        if (!empty($invoice['phone'])) {
            $html .= $invoice['phone'];
        }

        $html .= '
                        </div>
                    </div>
                </div>';

        // Service Information if available
        if (!empty($invoice['service_name'])) {
            $html .= '
                <div class="section">
                    <h2>Service Information</h2>
                    <p><strong>Service:</strong> ' . $invoice['service_name'] . 
                    (!empty($invoice['cooperation_type']) ? ' (' . $invoice['cooperation_type'] . ')' : '') . '</p>
                </div>';
        }

        // Invoice Items
        $html .= '
                <div class="section">
                    <h2>Invoice Items</h2>
                    <table>
                        <thead>
                            <tr>
                                <th width="70%">Description</th>
                                <th width="30%" class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>' . (!empty($invoice['service_name']) ? $invoice['service_name'] : 'Professional Services') . '</strong>';
        
        if (!empty($invoice['notes'])) {
            $html .= '<br><small>' . nl2br($invoice['notes']) . '</small>';
        }

        $html .= '
                                </td>
                                <td class="text-right">' . number_format($invoice['amount'], 2) . ' ' . $invoice['currency'] . '</td>
                            </tr>';

        if ($invoice['tax_amount'] > 0) {
            $html .= '
                            <tr>
                                <td><strong>Tax</strong></td>
                                <td class="text-right">' . number_format($invoice['tax_amount'], 2) . ' ' . $invoice['currency'] . '</td>
                            </tr>';
        }

        $html .= '
                            <tr class="total-row">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-right"><strong>' . number_format($invoice['total_amount'], 2) . ' ' . $invoice['currency'] . '</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>';

        // Payment Information
        $html .= '
                <div class="section">
                    <h2>Payment Information</h2>
                    <div style="display: flex; justify-content: space-between;">
                        <div style="width: 48%;">
                            <strong>Bank Transfer:</strong><br>
                            Bank: ABC Bank<br>
                            Account: 1234567890<br>
                            Name: Startout AI Inc.<br>
                            SWIFT: ABCDEFG123
                        </div>
                        <div style="width: 48%;">
                            <strong>PayPal:</strong><br>
                            Email: payments@startoutai.com<br>
                            <br>
                            <strong>Important:</strong><br>
                            Please include invoice number in payment notes.
                        </div>
                    </div>
                </div>';

        // Payment Status if paid
        if ($invoice['status'] === 'paid' && !empty($invoice['paid_date'])) {
            $html .= '
                <div class="section">
                    <div style="background-color: #d4edda; padding: 15px; border-radius: 4px; text-align: center;">
                        <strong>PAYMENT RECEIVED</strong><br>
                        Paid on: ' . date('F j, Y', strtotime($invoice['paid_date'])) . 
                        (!empty($invoice['payment_method']) ? ' via ' . ucfirst(str_replace('_', ' ', $invoice['payment_method'])) : '') . '
                    </div>
                </div>';
        }

        // Terms and Conditions
        $html .= '
                <div class="footer">
                    <p><strong>Terms & Conditions:</strong></p>
                    <p>Payment is due within 30 days of invoice date. Late payments are subject to fees of 1.5% per month.</p>
                    <p>If you have any questions concerning this invoice, contact our accounting department at accounting@startoutai.com</p>
                    <p>Thank you for your business!</p>
                    <p style="margin-top: 20px;">Generated on ' . date('F j, Y \a\t g:i A') . '</p>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }
}