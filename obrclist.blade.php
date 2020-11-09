@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><table width="100%"><tr><td><b>Invoice: {{ request()->route('id') }}</b></td><td align="right"> {{ __('You are logged in -') }} {{ Auth::user()->name }}</td></tr></table></div>
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

                <div class="card">
                <table class="table table-striped data-table 0DNISVd9 dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">

                    <thead>
                    <tr role="row">
                        <th align="center" valign="middle" class="head1 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="# : activate to sort column ascending">INV #
                        </th><th align="center" valign="middle" class="head2 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Product/Service : activate to sort column ascending">Product/Service
                        </th><th align="center" valign="middle" class="head3 sorting_desc" tabindex="0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Description : activate to sort column ascending">Description
                        </th><th align="center" valign="middle" class="head4 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="QTY : activate to sort column ascending">QTY
                        </th><th align="center" valign="middle" class="head5 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Rate : activate to sort column ascending">Rate
                        </th><th align="center" valign="middle" class="head6 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Amount : activate to sort column ascending">Amount
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($invoices as $invoice)
                        @foreach ($invoice->Line as $invoiceline)
                            <td>{{ $invoice->DocNumber }}</td>
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
                                    <td>{{ $invoiceline->SalesItemLineDetail->ItemRef }}</td>

                                    <td>{{ $invoiceline->Description }}</td>
                                    <td>{{ $invoiceline->SalesItemLineDetail->Qty }}</td>
                                    <td>{{ $invoiceline->SalesItemLineDetail->UnitPrice }}</td>
                                    <td>{{ $invoiceline->Amount }}</td>
                            @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                            @endif
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
            </div>

            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped data-table 0DNISVd9 dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                            <thead>
                            <tr role="row">
                                <th align="center" valign="middle" class="head1 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="# : activate to sort column ascending">Sales #</th>
                                <th align="center" valign="middle" class="head2 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Product/Service : activate to sort column ascending">Product/Service</th>
                                <th align="center" valign="middle" class="head3 sorting_desc" tabindex="0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Description : activate to sort column ascending">Description</th>
                                <th align="center" valign="middle" class="head4 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="QTY : activate to sort column ascending">QTY</th>
                                <th align="center" valign="middle" class="head5 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Rate : activate to sort column ascending">Rate</th>
                                <th align="center" valign="middle" class="head6 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Amount : activate to sort column ascending">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($salesreceipts as $salesreceipt)
                                @foreach ($salesreceipt->Line as $salesreceiptline)
                                    <td>{{ $salesreceipt->DocNumber }}</td>
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
                                        <td>{{ $salesreceiptline->SalesItemLineDetail->ItemRef }}</td>

                                        <td>{{ $salesreceiptline->Description }}</td>
                                        <td>{{ $salesreceiptline->SalesItemLineDetail->Qty }}</td>
                                        <td>{{ $salesreceiptline->SalesItemLineDetail->UnitPrice }}</td>
                                        <td>{{ $salesreceiptline->Amount }}</td>
                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        @endif
                                        </tr>
                                        @endforeach
                                        @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>

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
                        <td></td>
                        <td></td>
                        <td>{{ $glassbottle }}</td>
                        <td>{{ $petbottle }}</td>
                        <td>{{ $glassbottle + $petbottle }}</td>
                    </tbody>
                    </table>
                    <br>
                 </div>
            </div>
        </div>
            <div class="card-header"><table width="100%"><tr><td><a href="/invoices"/><b>Back To Invoices</b></a></td></table></div>
        </div>
    </div>
@endsection
