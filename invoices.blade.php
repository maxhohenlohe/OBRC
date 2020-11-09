@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><table width="100%"><tr><td><b>Invoice List: {{ request()->route('id') }}</b></td><td align="right"> {{ __('You are logged in -') }} {{ Auth::user()->name }}</td></tr></table></div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <?php
                $invoices = app('Spinen\QuickBooks\Client')->getDataService()->Query("Select * From Invoice ORDER BY DocNumber");
                $salesreceipts = app('Spinen\QuickBooks\Client')->getDataService()->Query("Select * From SalesReceipt ORDER BY DocNumber");
                $items = app('Spinen\QuickBooks\Client')->getDataService()->Query("Select * From Items");
                $customers = app('Spinen\QuickBooks\Client')->getDataService()->Query("Select * From Customer");
                $glassbottle = 0;
                $petbottle = 0;
                ?>

                <table class="table table-striped data-table 0DNISVd9 dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                    <thead>
                    <tr role="row"><th align="center" valign="middle" class="head0 sorting_disabled" style="width:20px" rowspan="1" colspan="1" aria-label="">
                        <th align="center" valign="middle" class="head1 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Invoice Number : activate to sort column ascending">Invoice Number</th>
                        <th align="center" valign="middle" class="head2 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Client Name : activate to sort column ascending">Client Name</th>
                        <th align="center" valign="middle" class="head3 sorting_desc" tabindex="0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Date : activate to sort column ascending">Date</th>
                        <th align="center" valign="middle" class="head4 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Amount : activate to sort column ascending">Amount</th>
                        <th align="center" valign="middle" class="head5 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Balance : activate to sort column ascending">Balance</th>
                        <th align="center" valign="middle" class="head6 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Due Date : activate to sort column ascending">Due Date</th>
                        <th align="center" valign="middle" class="head7 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Status : activate to sort column ascending">Status</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($invoices as $invoice)

                        <tr role="row" class="odd"><td><input type="checkbox" name="ids[]" value="269"></td>
                            <td><a href="http://obrc.lifeh2o.net/invoice/{{ $invoice->DocNumber }}">{{ $invoice->DocNumber }}</a></td>
                            @foreach ($customers as $customer)
                                @if ($customer->Id == $invoice->CustomerRef)
                                    <td><a href="https://app.sandbox.qbo.intuit.com/app/customerdetail?nameId={{ $customer->Id }}" target="_blank">{{ $customer->FullyQualifiedName }}</a></td>
                                @endif
                            @endforeach
                            <td>{{ $invoice->TxnDate }}</td>
                            <td>{{ $invoice->TotalAmt }}</td>
                            <td>{{ $invoice->Balance }}</td>
                            <td>{{ $invoice->DueDate }}</td>
                            @if ($invoice->Balance != 0)
                                <td>Unpaid</td>
                            @elseif ($invoice->Balance == 0)
                                <td>Paid?</td>
                            @else
                                <td>Unknown</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @foreach ($invoices as $invoice)
            @foreach ($invoice->Line as $invoiceline)
                @if ($invoiceline->SalesItemLineDetail)
                    <?php
                    $totalamount = $invoice->TotalAmt;
                    $itemname = $invoiceline->SalesItemLineDetail->ItemRef;
                    $products = app('Spinen\QuickBooks\Client')->getDataService()->Query("Select * From Item");
                    if ($invoiceline->SalesItemLineDetail->ItemRef == '22') {
                        $petbottle+= $invoiceline->SalesItemLineDetail->Qty * 12;
                    }
                    if ($invoiceline->SalesItemLineDetail->ItemRef == '23') {
                        $petbottle+= $invoiceline->SalesItemLineDetail->Qty;
                    }
                    if ($invoiceline->SalesItemLineDetail->ItemRef == '24') {
                        $glassbottle+= $invoiceline->SalesItemLineDetail->Qty;
                    }
                    ?>
                @endif
            @endforeach
        @endforeach

        @foreach ($salesreceipts as $salesreceipt)
            @foreach ($salesreceipt->Line as $salesreceiptline)
                @if ($salesreceiptline->SalesItemLineDetail)
                    <?php
                    $totalamount = $salesreceipt->TotalAmt;
                    $itemname = $salesreceiptline->SalesItemLineDetail->ItemRef;
                    $products = app('Spinen\QuickBooks\Client')->getDataService()->Query("Select * From Item");

                    if ($salesreceiptline->SalesItemLineDetail->ItemRef == '22') {
                        $petbottle+= $salesreceiptline->SalesItemLineDetail->Qty * 12;
                    }
                    if ($salesreceiptline->SalesItemLineDetail->ItemRef == '23') {
                        $petbottle+= $salesreceiptline->SalesItemLineDetail->Qty;
                    }
                    if ($salesreceiptline->SalesItemLineDetail->ItemRef == '24') {
                        $glassbottle+= $salesreceiptline->SalesItemLineDetail->Qty;
                    }
                    ?>
                @endif

            @endforeach
        @endforeach

<br><br>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped data-table 0DNISVd9 dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                        <thead>
                        <tr role="row">
                            <th align="center" valign="middle" class="head2 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Product/Service : activate to sort column ascending">Month</th>
                            <th align="center" valign="middle" class="head3 sorting_desc" tabindex="0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Description : activate to sort column ascending">Year</th>
                            <th align="center" valign="middle" class="head4 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="QTY : activate to sort column ascending">Glass Count</th>
                            <th align="center" valign="middle" class="head5 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Rate : activate to sort column ascending">PET Count</th>
                            <th align="center" valign="middle" class="head6 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Amount : activate to sort column ascending">Total QTY</th>
                        </tr>
                        </thead>
                        <tbody>
                        <td><a href="/obrclist">November</a></td>
                        <td>2020</td>
                        <td>{{ $glassbottle }}</td>
                        <td>{{ $petbottle }}</td>
                        <td>{{ $glassbottle + $petbottle }}</td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
