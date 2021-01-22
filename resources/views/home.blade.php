@extends('layouts.app')
@section('title','Dashboard')
@section('css')
<link rel="stylesheet" href="{{asset('css/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('css/pickadate.css')}}">
<style type="text/css">
input[type=number] {
  -moz-appearance: textfield;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
@media (max-width:768px){
    #notes {
        flex-direction: column;
    }
}
</style>
@endsection
@section('content')
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


<div id="success-message"></div>
<div class="w-100 d-block bg-white p-2 mt-2">

    {{-- Display 3 Cards --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center shadow-lg rounded">
                <div class="card-content">
                    <div class="card-body">
                        <div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto my-1">
                            <i class="bx bxs-group font-medium-5"></i>
                        </div>
                        <p class="text-muted mb-0 line-ellipsis">Total</p>
                        <h2 class="mb-0">{{$total_student}}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-lg rounded">
                <div class="card-content">
                    <div class="card-body">
                        <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto my-1">
                            <i class="bx bx-briefcase font-medium-5"></i>
                        </div>
                        <p class="text-muted mb-0 line-ellipsis">Job Description</p>
                        <h2 class="mb-0">{{$total_job}}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-lg rounded" data-toggle="modal" data-target="#default" id="policy-card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto my-1">
                            <i class="bx bx-file font-medium-5"></i>
                        </div>
                        <p class="text-muted mb-0 line-ellipsis">Privacy Policy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Display Student With Filter --}}
    <div class="row my-2">
        @include('layouts.studentsearch')
    </div>
    <div id="filterdate" class="collapse">
        <div class="d-flex">
            <fieldset class="form-group position-relative has-icon-left">
                <input type="text" name="startdate" class="form-control pickadate" placeholder="Select Start Date">
                <div class="form-control-position">
                    <i class='bx bx-calendar'></i>
                </div>
            </fieldset>
            <fieldset class="form-group position-relative has-icon-left">
                <input type="text" name="enddate" class="form-control pickadate" placeholder="Select End Date">
                <div class="form-control-position">
                    <i class='bx bx-calendar'></i>
                </div>
            </fieldset>
        </div>
    </div>


    @include('layouts.studentpagination')
    <button class="btn btn-danger mt-2" id="multipledelete">Delete</button>



</div>
@endsection
@section('js')

<script src="{{asset('js/picker.js')}}"></script>
<script src="{{asset('js/picker.date.js')}}"></script>
<script src="{{asset('js/daterangepicker.js')}}"></script>


<script>
    var current_page='1';
$('.pickadate').pickadate({format: 'yyyy-mm-dd'});

$('#expereincecollpase').on('click', function (e) {
    e.stopPropagation();
    if(this.id == 'expereincecollpase'){
        $('#expereincepanel').collapse('show');
    }
});
$('#freshercollpase').on('click',function(e){
    e.stopPropagation();
    if(this.id=='freshercollpase')
    {
        $('#expereincepanel').collapse('hide');
    }
});
$('#customdate').on('change',function(){
    if($(this).val()==0){
        $('#filterdate').collapse('show');
    }
    else{
        $('#filterdate').collapse('hide');
    }
});

function fetchstudent(page='',technology='',expereince='',state='',date='')
{
    $.ajax({
        url:"{{route('studentsearch')}}",
        method: 'post',
        data:{page:page,technology:technology,expereince:expereince,state:state,date:date},
        success:function(data)
        {
            $('#studentdata').html('');
            $('#studentdata').html(data);
        }
    });
}

    $(document).on('change',function(){
        var technology=$('.tech').val();
        var expereince=$('#expereincesearch').val();
        var state=$('#filterstate').val();
        var date=$('#customdate').val();
        fetchstudent('',technology,expereince,state,date);
    });


    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        current_page=page;
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        fetchstudent(page);
    });

    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length==$('.checkbox').length){
            $('#check_all').prop('checked',true);
        }
        else{
            $('#check_all').prop('checked',false);
        }
    });

    $('#multipledelete').on('click',function(){
        var idsArr=[];
        $('.checkbox:checked').each(function(){
            idsArr.push($(this).attr('data-id'));
        });
        if(idsArr.length<0){
            alert('Select at least one');
        }
        else
        {
            if(confirm('Are you sure ?')){
                var strIds=idsArr.join(',');
                $.ajax({
                    url: "{{route('deletemultiplestudents')}}",
                    type: "DELETE",
                    data:'ids='+strIds,
                    success: function(data){
                        toastr.success(data.success, 'Success Message');
                        $('.checkbox:checked').each(function(){
                            $(this).parents("tr").remove();
                        });
                        fetchstudent(current_page);
                    }
                });
            }
        }
    });


</script>

@endsection

