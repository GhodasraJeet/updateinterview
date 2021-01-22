@extends('layouts.app')
@section('title','All Notes')
@section('css')

    <link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">
    <style>
        .checkbox-input{cursor: pointer}
    </style>
@endsection
@section('content')

<div class="row">

    <div class="d-block w-100">
        <div id="success-message"></div>
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has($msg))

                <div id="toast-container" class="toast-container toast-top-right">
                    <div class="toast toast-success" aria-live="polite" style="display: block;">
                        <div class="toast-title">Success </div>
                        <div class="toast-message"> {{ Session::get($msg) }}</div>
                    </div>
                </div>

                @endif
        @endforeach
    </div>
</div>


<div class="row">
    <div class="col-12">

            <form method="POST" action="{{route('note.search')}}" class="mb-2">
                {{ csrf_field() }}
                @component('layouts.search', ['title' => 'Search'])
                    @component('layouts.searchnow', ['items' => ['title'],
                    'oldVals' => [isset($searchingVals) ? $searchingVals['title'] : '']])
                    @endcomponent
                @endcomponent
             </form>
             <div class="table-responsive">
            <table class="table zero-configuration">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Favourite</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notes as $index=>$note)
                    <tr>
                        <td>{{$note->title}}</td>
                        <td>{{$note->description}}</td>
                        <td>
                            <div class="checkbox">
                            <input type="checkbox" class="checkbox-input" data-id="{{$note->id}}" id="checkbox{{$index}}" @if($note->favourite)checked @endif>
                                <label for="checkbox{{$index}}"></label>
                            </div>
                        </td>
                        <td>{{$note->created_at}}</td>
                        <td><div class="dropdown">
                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{route('notes.edit',$note->id)}}" class="dropdown-item"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#default"><i class="bx bx-trash mr-1" ></i> Delete</button>
                            </div>
                        </div>
  <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel1">Basic Modal</h3>
                    <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Are you sure you want to delete a note ?
                    </p>
                </div>
                <div class="modal-footer">
                    <form action="{{route('notes.destroy',$note->id)}}" method="post" id="deletemodal">
                        @csrf
                        @method('delete')
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Accept</span>
                    </button>
                </form>
                </div>
            </div>
        </div>
    </div>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row justify-content-between mx-2">
                <div>
                  <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($notes)}} of {{count($notes)}} entries</div>
                </div>
                <div>
                  <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                    {{ $notes->links() }}
                  </div>
                </div>
              </div>

        </div>
    </div>
</div>

@endsection


@section('js')

    <script src="{{asset('js/datatables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.buttons.min.js')}}"></script>
    <script>
        $(document).ready(function(){


            $('#errors').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Add Or Remove Favourite on Note
            $('.checkbox-input').on('change',function(){
                $('#success-message').html('');
                var current='';
                var notesid=$(this).attr('data-id');
                var checkbox=$(this);
                if($(this).is(":checked")){
                    current='check';
                }
                else
                {
                    current='uncheck';
                }
                $.ajax({
                    url: "{{ route('note.favourite')}}",
                    method: 'post',
                    data: {"noteid":notesid,"current":current},
                    success: function(data){
                        if(data.success){
                            $('#success-message').append();
                            toastr.success(data.success, 'Success Message');
                           setTimeout(() => { $('.toast').hide(); }, 2000);

                        }
                        if(data.danger){
                            $('#success-message').append();

                           setTimeout(() => { $('.toast').hide(); }, 2000);

                        }
                    }
                });
            });

        });
    </script>

<script>setTimeout(() => { $('.toast').hide(); }, 2000);</script>
@endsection
