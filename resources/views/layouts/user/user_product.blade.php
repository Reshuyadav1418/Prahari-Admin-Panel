@extends('layouts.user.user_master')

@push('page-style')
@endpush

@section('page-content')
    
    <div class="row mt-2">
        <div class="col-sm-12 d-flex align-items-center justify-content-between">
            <h4 class="h4">Product List</h4>
            <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModal" data-bs-keyboard="false"
                aria-hidden="true">
                <!-- Scrollable modal -->
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="modalTitle">Modal title
                            </h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <table class="table" id="usersTable">
                <thead>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Category_id</th>
                    <th>Price</th>
                    <th>Current_stock</th>
                    {{-- <th>Action</th> --}}
                </thead>
                <tbody>
                    {{-- To be stored with Ajax --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('page-script')
<script>
    $(document).ready(function() {

            let table = $('#usersTable').DataTable({
                processing: true, //loader 
                serverSide: true,  //pageination
                ajax: "{{ route('users.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'sku',
                        name: 'sku'
                    },
                    {
                        data: 'category_id',
                        name: 'category_id'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'current_stock',
                        name: 'current_stock'
                    }
                ]
            });
        });

</script>
@endpush