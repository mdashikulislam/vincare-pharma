@extends('layouts.app')
@section('title', __('Sales Commission Agent Invoice'))
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Sales Commission Agent Invoice
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary'])
            @can('user.create')
                @slot('tool')
                    <div class="box-tools">
                        <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full btn-modal pull-right"
                           data-href="{{action([\App\Http\Controllers\SalesCommissionAgentController::class, 'create'])}}" data-container=".commission_agent_modal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg> @lang('messages.add')
                        </a>
                    </div>
                @endslot
            @endcan
            @can('user.view')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="sales_commission_agent_table_invoice">
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Total Invoice</th>
                            <th>Sales Commission Percentage(%)</th>
                            <th>Total Commission</th>
                            <th>Total Payment</th>
                            <th>Total Balance</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            @endcan
        @endcomponent
        <div class="modal fade" id="add-payment-modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{route('invoice-add-payment')}}" method="POST" id="payment-form">
                            @csrf
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Add new Payment</h4>
                            </div>
                            <div class="modal-body" id="append-add-payment">

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">Save</button>
                                <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>


@endsection
@section('javascript')
    <script>
        var id = '{{$user->id}}';
        var userId = '{{$user->id}}';
        var maxPay = 0;
        var sales_commission_agent_table = $('#sales_commission_agent_table_invoice').DataTable({
            processing: true,
            serverSide: true,
            fixedHeader:false,
            ajax: '/sales-commission-agents/invoice/'+id,
            columns: [
                { data: 'invoice_no' },
                { data: 'final_total' },
                { data: 'sales_commission_percentage' },
                { data: 'total_commission' },
                { data: 'total_payment' },
                { data: 'total_balance' },
                { data: 'action' },
            ],
        });
        $(document).on('click','.add-payment',function(e){
            e.preventDefault();
            var id = $(this).data('id');
            maxPay = $(this).data('max');
            console.log(maxPay)
            $('#append-add-payment').html(`<div class="row">
                                    <input type="hidden" name="transaction_id" value="${id}">
                                    <input type="hidden" name="user_id" value="${userId}">
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group">
                                            <label for="amount_0">Amount:*</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fas fa-money-bill-alt"></i>
                                                </span>
                                                <input class="form-control payment-amount input_number" required="" id="amount" placeholder="Amount" name="amount" type="text" value="0.00" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group">
                                            <label for="paid_on_0">Paid on:*</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control paid_on" readonly="" required="" name="paid_on" type="text" value="12/04/2024 06:27" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group">
                                            <label for="method_0">Payment Method:*</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fas fa-money-bill-alt"></i></span>
                                                <select class="form-control col-md-12 payment_types_dropdown" required="" id="method_0" style="width: 100%;" name="method">
                                                    <option value="cash" selected="selected">Cash</option>
                                                    <option value="card">Card</option>
                                                    <option value="cheque">Cheque</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                    <option value="other">Other</option>
                                                    <option value="custom_pay_1">Credit</option>
                                                    <option value="custom_pay_2">Custom Payment 2</option>
                                                    <option value="custom_pay_3">Custom Payment 3</option>
                                                    <option value="custom_pay_4">Custom Payment 4</option>
                                                    <option value="custom_pay_5">Custom Payment 5</option>
                                                    <option value="custom_pay_6">Custom Payment 6</option>
                                                    <option value="custom_pay_7">Custom Payment 7</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>`)
            $('#add-payment-modal').modal('show')
            $('.paid_on').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
            });
        });
        $(document).ready( function(){
            $('.paid_on').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
            });
        });

        $('#add-payment-modal').on('shown.bs.modal', function(e) {
            $('form#payment-form')
                .submit(function(e) {
                    e.preventDefault();
                })
                .validate({
                    rules:{
                      amount:{
                          required:true,
                          max: function() {
                             return maxPay;
                          },
                          min:1
                      }
                    },
                    submitHandler: function(form) {
                        e.preventDefault();
                        var data = $(form).serialize();
                        console.log(maxPay)
                        $.ajax({
                            method: $(form).attr('method'),
                            url: $(form).attr('action'),
                            dataType: 'json',
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    $('#add-payment-modal').modal('hide');
                                    toastr.success(result.msg);
                                    $("form#payment-form").validate().resetForm();
                                    sales_commission_agent_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    },
                });
        });
    </script>
@endsection