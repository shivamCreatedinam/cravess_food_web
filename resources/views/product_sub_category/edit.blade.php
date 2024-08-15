@extends('partials.app')
@section('title', 'Sub Category Edit')
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
                        <a href="{{ route('category_list') }}">Sub Category</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Sub Category Edit</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mx-4">
            <div class="card-header">
                <h5><a href="{{ route('subcategory_list') }}"><i class="fas fa-arrow-alt-circle-left"
                            title="Back To Sub Categories List"></i></a> Edit Sub Category</h5>
            </div>
            <div class="card-body">

                @include('status')

                <form action="{{ route('subcat_update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="sub_cat_id" value="{{ $sub_category->id }}">
                    <div class="row">

                        <div class="col-md-4 form-group">
                            <label for="category_id">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="" selected disabled> --Select Category-- </option>
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}" {{ $sub_category->category_id === $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $sub_category->sub_cat_name) }}" placeholder="Enter Category Name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control">
                                <option value="" selected disabled> --Select Status-- </option>
                                <option value="1" {{ $sub_category->sub_status === 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $sub_category->sub_status === 0 ? 'selected' : '' }}>Disable</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-5 form-group">
                            <label for="icon">Icon </label>
                            <input type="file" name="icon" id="icon" class="form-control">
                            @error('icon')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-1 form-group">
                            @if(!is_null($sub_category->sub_icon))
                           <a href="{{$sub_category->sub_icon}}" target="_blank"><img src="{{$sub_category->sub_icon}}" alt="category icon" class='img-fluid' width='64' height='64'></a>
                           @endif
                        </div>

                        <div class="col-md-5 form-group">
                            <label for="banner_image">Banner Image </label>
                            <input type="file" name="banner_image" id="banner_image" class="form-control">
                            @error('banner_image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-1 form-group">
                            @if(!is_null($sub_category->sub_banner_image))
                            <a href="{{$sub_category->sub_banner_image}}" target="_blank"><img src="{{$sub_category->sub_banner_image}}" alt="category banner image" class='img-fluid' width='64' height='64'></a>
                            @endif
                        </div>
                    </div>

                    <div class="sb_btn text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            // Attach event listener to elements with data-toggle="toggle"
            $('input[type="checkbox"]').change(function() {
                let type = $(this).data('type');
                updateStatus(this, type);
            });

            // Initialize toggle buttons if needed
            // $('[data-toggle="toggle"]').bootstrapToggle();
        });

        function updateStatus(input, type, other = null) {
            let inputData = $(this)
            let user_id = $('#user_id').val()
            $.ajax({
                type: "post",
                url: "{{ route('admin_user_status_update') }}",
                data: {
                    "status_type": type,
                    "user_id": user_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endpush
