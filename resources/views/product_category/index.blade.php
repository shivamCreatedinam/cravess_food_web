@extends('partials.app')
@section('title', 'Category List')
@section('container')
    <div class="container">

        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Dashboard</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="{{ route('dashboard') }}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('category_list') }}">Categories</a>
                    </li>

                </ul>
            </div>
        </div>

        <div class="card mx-4">
            <div class="card-header">
                @include('status')
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between my-4">
                    <h4 class="card-title">Categories List</h4>
                    <a href="{{route('category_add')}}" class="btn btn-primary btn-sm rounded"><i class="fa fa-plus"></i> Add New Category</a>
                </div>

                {{-- <input type="checkbox" checked data-toggle="toggle" data-width="100" data-height="75" data-onlabel="Active" data-offlabel="Block" data-onstyle="success" data-offstyle="danger"> --}}


                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover text-center" id="categoryTable"
                        style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Icon</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>


                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            var table = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // order: [
                //     [0, "desc"]
                // ],
                ajax: {
                    url: "{{ route('category_list') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'icon',
                        name: 'icon',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
