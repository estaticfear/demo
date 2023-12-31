<?php

namespace Cmat\Ecommerce\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Cmat\Ecommerce\Models\Invoice;
use Cmat\Ecommerce\Repositories\Interfaces\InvoiceInterface;
use Illuminate\Http\Request;
use InvoiceHelper;
use SeoHelper;
use Theme;

class InvoiceController extends Controller
{
    public function index()
    {
        SeoHelper::setTitle(__('Invoices'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('My Profile'), route('public.account.dashboard'))
            ->add(__('Manage Invoices'));

        return '';
    }

    public function show($id, InvoiceInterface $invoiceRepository)
    {
        $invoice = $invoiceRepository->findOrFail($id);

        abort_unless($this->canViewInvoice($invoice), 404);

        $title = __('Invoice detail :code', ['code' => $invoice->code]);

        page_title()->setTitle($title);

        SeoHelper::setTitle($title);

        return Theme::scope(
            'ecommerce.customers.invoices.detail',
            compact('invoice'),
            'plugins/ecommerce::themes.customers.invoices.detail'
        )->render();
    }

    public function getGenerateInvoice(int|string $invoiceId, Request $request, InvoiceInterface $invoiceRepository)
    {
        $invoice = $invoiceRepository->findOrFail($invoiceId);

        abort_unless($this->canViewInvoice($invoice), 404);

        if ($request->input('type') === 'print') {
            return InvoiceHelper::streamInvoice($invoice);
        }

        return InvoiceHelper::downloadInvoice($invoice);
    }

    protected function canViewInvoice(Invoice $invoice): bool
    {
        return auth('customer')->id() == $invoice->payment->customer_id;
    }
}
