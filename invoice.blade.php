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
                $customers = app('Spinen\QuickBooks\Client')->getDataService()->Query("Select * From Customer");

                ?>

                <table class="table table-striped data-table 0DNISVd9 dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">

                    <thead>
                    <tr role="row">

                        <th align="center" valign="middle" class="head1 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="# : activate to sort column ascending">#</th>
                        <th align="center" valign="middle" class="head2 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Product/Service : activate to sort column ascending">Product/Service</th>
                        <th align="center" valign="middle" class="head3 sorting_desc" tabindex="0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Description : activate to sort column ascending">Description</th>
                        <th align="center" valign="middle" class="head4 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="QTY : activate to sort column ascending">QTY</th>
                        <th align="center" valign="middle" class="head5 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Rate : activate to sort column ascending">Rate</th>
                        <th align="center" valign="middle" class="head6 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Amount : activate to sort column ascending">Amount</th>

                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($invoices as $invoice)

                        @if ($invoice->DocNumber == request()->route('id'))
                            @foreach ($invoice->Line as $invoiceline)

                                <td>{{ $invoiceline->Id }}</td>
                                @if ($invoiceline->SalesItemLineDetail)
                                    <?php
                                    $totalamount = $invoice->TotalAmt;
                                    $itemname = $invoiceline->SalesItemLineDetail->ItemRef;
                                    $products = app('Spinen\QuickBooks\Client')->getDataService()->Query("Select * From Item WHERE id='$itemname'");
                                    ?>
                                    <td>{{ $products[0]->Name }}</td>

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
                                @endif
                            @endforeach

                            <tr>
                                <th></th><th></th><th></th><th></th><th><b>Total: </b></th>
                                <td>{{ $totalamount }}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-header"><table width="100%"><tr><td><a href="/invoices"/><b>Back To Invoices</b></a></td></table></div>
    </div>
    </div>
@endsection
