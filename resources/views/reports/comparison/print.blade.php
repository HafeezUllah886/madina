@extends('layout.popups')
@section('content')
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header border-bottom-dashed p-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h1>JAFFAR & BROTHERS</h1>
                                    </div>
                                    <div class="flex-shrink-0 mt-sm-0 mt-3">
                                        <h3>Comparison Report</h3>
                                    </div>
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Year One</p>
                                        <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($from1)) }}</h5>
                                        <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($to1)) }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Year Two</p>
                                        <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($from2)) }}</h5>
                                        <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($to2)) }}</h5>
                                    </div>
                                    <!--end col-->
                                    <!--end col-->
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Customer</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">{{ $customer->title }}</span></h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Printed On</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">{{ date("d M Y") }}</span></h5>
                                        {{-- <h5 class="fs-14 mb-0"><span id="total-amount">{{ \Carbon\Carbon::now()->format('h:i A') }}</span></h5> --}}
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0" id="datatables">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" class="text-start">Product</th>
                                                <th scope="col" class="text-start">Category</th>
                                                <th scope="col" class="text-start">Unit</th>
                                                <th scope="col" class="text-end">Year One</th>
                                                <th scope="col" class="text-end">Year Two</th>
                                                <th scope="col" class="text-end">Growth</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                        @foreach ($products as $key => $product)
                                        <tr>
                                            <td class="text-start">{{$product->name}}</td>
                                            <td class="text-start">{{$product->category->name}}</td>
                                            <td class="text-start">{{$product->unit->name}}</td>
                                            <td class="text-end">{{number_format($product->sold1)}}</td>
                                            <td class="text-end">{{number_format($product->sold2)}}</td>
                                            <td class="text-end">{{number_format($product->growth,2)}}%</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <th class="text-end" colspan="3">Total</th>
                                            <td class="text-end">{{number_format($products->sum('sold1'))}}</td>
                                            <td class="text-end">{{number_format($products->sum('sold2'))}}</td>
                                            <td class="text-end">{{number_format(calculateGrowthPercentage($products->sum('sold1'), $products->sum('sold2')),2)}}%</td>
                                        </tfoot>
                                    </table><!--end table-->
                                </div>

                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->

@endsection
<script>
    setTimeout(() => {
        window.print();
        window.history.back();
    }, 1000);
</script>



