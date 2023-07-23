@extends('layouts.master')
@section('heading')
    
@stop

@section('content')
    <div class="row">
        <form action="{{route('leads.store')}}" method="POST" id="createTaskForm">
            <div class="col-sm-8">
                <div class="tablet">
                    <div class="tablet__body">
                            <div class="form-group">
                                <label for="name" class="control-label thin-weight">@lang('name')</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phone_1" class="control-label thin-weight">@lang('Primary Phone')</label>
                                <input type="text" name="phone_1" id="phone_1" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phone_2" class="control-label thin-weight">@lang('Secondary Phone')</label>
                                <input type="text" name="phone_2" id="phone_2" class="form-control">
                            </div>
                            <div class="form-group form-check-inline">
                                <label for="lead_source" class="control-label thin-weight">@lang('Lead Source')</label>
                                <select name="lead_source" id="lead_source" class="form-control">
                                    <option selected value="campaign">Campaign</option>
                                    <option value="exipition">Exipition</option>
                                    <option value="brokers company">Brokers Company</option>
                                    <option value="calls">Call's</option>
                                    <option value="adverstising">Adverstising (Bill Board)</option>
                                    <option value="referd to our company">Referd to Our Company</option>
                                    <option value="direct customers">Direct Customers</option>
                                </select>
                            </div>
                            <div class="form-inline form-check-inline">
                                <label for="lead_sub_source" class="control-label thin-weight">@lang('Lead Sub source')</label>
                                <select name="lead_sub_source" id="lead_sub_source" class="form-control">
                                    <option selected value="facebook">Facebook</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="snapchat">Snapchat</option>
                                    <option value="tick tok">Tick Tok</option>
                                    <option value="whatsApp">WhatsApp</option>
                                </select>
                            </div>
                            <div class="" style="margin-top: 10px;margin-bottom: 10px">
                                <label for="" class="control-label thin-weight">Interested in :</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="interested_in_our" type="radio" id="inlineCheckbox1" value="1">
                                    <label class="form-check-label thin-weight" for="inlineCheckbox1">
                                        our units
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="interested_in_our"  type="radio" id="inlineCheckbox2" value="0">
                                    <label class="form-check-label thin-weight" for="inlineCheckbox2">
                                        other units
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="control-label thin-weight">@lang('Description')</label>
                                <textarea name="description" id="description" cols="50" rows="10" class="form-control"></textarea>
                            </div>


                            
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="tablet">
                    <div class="tablet__body">

                        <div class="form-group">
                            <label for="user_assigned_id" class="control-label thin-weight">@lang('Assign user')</label>
                            <select name="user_assigned_id" id="user_assigned_id" class="form-control">
                                @foreach($users as $user => $userK)
                                    <option value="{{$user}}">{{$userK}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="projectSelect" style="display: none;">

                            <label for="project_external_id" class="control-label thin-weight">@lang('Assign project')</label>
                            <select name="project_external_id" id="project_external_id" class="form-control">
                                <option value="" selected></option>
                                @foreach($projects as $project => $projectK)
                                    <option value="{{$project}}">{{$projectK}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-inline">
                            <div class="form-group col-sm-7" style="padding-left: 0px;">
                                <label for="deadline" class="control-label thin-weight">@lang('Deadline')</label>
                                <input type="text" id="deadline" name="deadline" data-value="{{now()->addDays(3)}}" class="form-control">
                            </div>
                            <div class="form-group col-sm-5">
                                <label for="contact_time" class="control-label thin-weight">@lang("O'clock")</label>
                                <input type="text" id="contact_time" name="contact_time" value="{{\Carbon\Carbon::today()->setTime(15, 00)->format(carbonTime())}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="control-label thin-weight">@lang('Sales Stage')</label>
                            <select name="status_id" id="status" class="form-control">
                                @foreach($statuses as $status => $statusK)
                                    <option value="{{$status}}">{{$statusK}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{csrf_field()}}
                        <div class="form-group">
                            <input type="submit" class="btn btn-md btn-brand movedown" id="submit" value="{{__('Create lead')}}" disabled='disabled' >
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
@push('style')
    <style>
        .picker, .picker__holder {
            width: 128%;
        }
        .picker--time .picker__holder {
            width: 30%;
        }
        .picker--time {
            min-width: 0px;
            max-width: 0px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $('#client_external_id').selectpicker()
        $('#client_external_id').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            var value = $("#client_external_id").val();
            if(value == "new_client") {
              window.location.href = "/clients/create"
            }
          });
        $('#description').summernote({
            toolbar: [
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'font', [ 'bold', 'italic', 'underline','clear'] ],   
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph'] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'link'] ],
                [ 'view', [ 'fullscreen' ] ]
            ],
             height:300,
             disableDragAndDrop: true

           });
        $('#deadline').pickadate({
            hiddenName:true,
            format: "{{frontendDate()}}",
            formatSubmit: 'yyyy/mm/dd',
            closeOnClear: false,
        });
        $('#contact_time').pickatime({
            format:'{{frontendTime()}}',
            formatSubmit: 'HH:i',
            hiddenName: true
        })
    </script>
    <script>
        const inlineCheckbox1 = document.getElementById('inlineCheckbox1');
        const inlineCheckbox2 = document.getElementById('inlineCheckbox2');
        const projectSelect = document.getElementById('projectSelect');
    
        inlineCheckbox2.addEventListener('change', () => {
            projectSelect.style.display = 'none';
        });
    
        inlineCheckbox1.addEventListener('change', () => {
            projectSelect.style.display = 'block';
        });
    </script>
@endpush

