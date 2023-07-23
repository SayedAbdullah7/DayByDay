@extends('layouts.master')
@section('heading')
    {{isset($project)?__('Edit project'):__('Create project')}} <span class="small">{{$client ? '(' . $client->company_name . ')': ''}}</span>
@stop

@section('content')

    <div class="row">
        <form action="{{isset($project)?route('projects.update.all',$project->external_id):route('projects.store')}}" method="POST" id="createProjectForm">
        @if (isset($project))
        @method('PUT')
        @endif
            <div class="col-sm-8">
                <div class="tablet">
                    <div class="tablet__body">
                            <div class="form-group">
                                <label for="title" class="control-label thin-weight">@lang('Title')</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{isset($project) ? $project->title:''}}">
                            </div>
                            <div class="form-group">
                                <label for="description" class="control-label thin-weight">@lang('Description')</label>
                                <textarea name="description" id="description" cols="50" rows="10" class="form-control">{!!isset($project) ? $project->description:''!!}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="unitType" class="control-label thin-weight">unit type</label>
                                <select name="unit_type" id="unitType" class="form-control">
                                    @if (!isset($project) || !$project->unit_type)
                                        <option value="" selected></option>
                                    @endif
                                    <option value="villa" {{isset($project) && $project->unit_type == 'villa'?'selected':''}} >villa</option>
                                    <option value="apartment" {{isset($project) && $project->unit_type == 'apartment'?'selected':''}} >apartment</option>
                                    <option value="challet" {{isset($project) && $project->unit_type == 'challet'?'selected':''}} >challet</option>
                                    <option value="mall" {{isset($project) && $project->unit_type == 'mall'?'selected':''}} >mall</option>
                                    <option value="office" {{isset($project) && $project->unit_type == 'office'?'selected':''}} >office</option>
                                    <option value="studio" {{isset($project) && $project->unit_type == 'studio'?'selected':''}} >studio</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="subType" class="control-label thin-weight">sub type</label>
                                <select name="sub_type" id="subType" class="form-control">
                                    <option value="" selected></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bedroom" class="control-label thin-weight">bedroom</label>
                                <input type="number" name="bedroom" id="bedroom" class="form-control" min="0" value="{{isset($project) ? $project->bedroom:''}}">
                            </div>
                            <div class="form-group">
                                <label for="bathroom" class="control-label thin-weight">bathroom</label>
                                <input type="number" name="bathroom" id="bathroom" class="form-control" min="0" value="{{isset($project) ? $project->bathroom:''}}">
                            </div>
                            <div class="form-group">
                                <label for="dressingRoom" class="control-label thin-weight">dressing room</label>
                                <input type="number" name="dressing_room" id="dressingRoom" class="form-control" min="0" value="{{isset($project) ? $project->dressing_room:''}}">
                            </div>
                            <div class="form-group">
                                <label for="area" class="control-label thin-weight">area</label>
                                <input type="number" name="area" id="area" class="form-control" min="0" value="{{isset($project) ? $project->area:''}}">
                            </div>
                            <div class="form-group">
                                <label for="bua" class="control-label thin-weight">BUA (Building Area)</label>
                                <input type="number" name="bua" id="bua" class="form-control" min="0" value="{{isset($project) ? $project->bua:''}}">
                            </div>
                            <div class="form-group">
                                <label for="landArea" class="control-label thin-weight">land area</label>
                                <input type="number" name="land_area" id="landArea" class="form-control" min="0" value="{{isset($project) ? $project->land_area:''}}">
                            </div>
                            <div class="form-group">
                                <label for="garage" class="control-label thin-weight">garage</label>
                                <input type="number" name="garage" id="garage" class="form-control" min="0" value="{{isset($project) ? $project->garage:''}}">
                            </div>
                            <div class="form-group">
                                <label for="roofArea" class="control-label thin-weight">roof area</label>
                                <input type="number" name="roof_area" id="roofArea" class="form-control" min="0" value="{{isset($project) ? $project->roof_area:''}}">
                            </div>
                            <div class="form-group">
                                <label for="floorNumber" class="control-label thin-weight">floor number</label>
                                <input type="number" name="floor_number" id="floorNumber" class="form-control" min="0" value="{{isset($project) ? $project->floor_number:''}}">
                            </div>
                            <div class="form-group">
                                <label for="apartmentNumber" class="control-label thin-weight">apartment number</label>
                                <input type="number" name="apartment_number" id="apartmentNumber" class="form-control" min="0" value="{{isset($project) ? $project->apartment_number:''}}">
                            </div>
                            <div class="form-group">
                                <label for="elevator" class="control-label thin-weight">elevator</label>
                                <input type="number" name="elevator" id="elevator" class="form-control" min="0" value="{{isset($project) ? $project->elevator:''}}">
                            </div>
                            <div class="form-group">
                                <label for="view" class="control-label thin-weight">view</label>
                                <select name="view" id="view" class="form-control">
                                    @if (!isset($project) || !$project->view)
                                        <option value="" selected></option>
                                    @endif
                                    <option value="garden" {{isset($project) && $project->view == 'garden'?'selected':''}} >garden</option>
                                    <option value="pool" {{isset($project) && $project->view == 'pool'?'selected':''}} >pool</option>
                                    <option value="sea" {{isset($project) && $project->view == 'sea'?'selected':''}} >sea</option>
                                    <option value="lake {{isset($project) && $project->view == 'lake'?'selected':''}} ">lake</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="finishedStatus" class="control-label thin-weight">finished status</label>
                                <select name="finished_status" id="finishedStatus" class="form-control">
                                    @if (!isset($project) || !$project->finished_status)
                                        <option value="" selected></option>
                                    @endif
                                    <option value="un finished" {{isset($project) && $project->finished_status == 'un finished'?'selected':''}} >un finished</option>
                                    <option value="semi finished" {{isset($project) && $project->finished_status == 'semi finished'?'selected':''}} >semi finished</option>
                                    <option value="fully finished" {{isset($project) && $project->finished_status == 'fully finished'?'selected':''}} >fully finished</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="furnitureStatus" class="control-label thin-weight">furniture status</label>
                                <textarea name="furniture_status" id="furnitureStatus" cols="50" rows="10" class="form-control">{{isset($project) ? $project->furniture_status:''}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="comment" class="control-label thin-weight">comment</label>
                                <textarea name="comment" id="comment" cols="50" rows="10" class="form-control">{{isset($project) ? $project->comment:''}}</textarea>
                            </div>
                    </div>
                    
                </div>
                @if(Entrust::can('project-upload-files') && $filesystem_integration)
                    <div class="tablet">
                        <div class="tablet__body">
                                <label class="control-label">@lang('Files')</label>
                                <div class="dropzone dz-default dz-message" id="dropzone-images">
                                    <span>@lang('Drop files here or click to upload')</span>
                                </div>

                        </div>
                    </div>
                @endif
            </div>
            <div class="col-sm-4">
                <div class="tablet">
                    <div class="tablet__body">
                        <div class="form-group">
                            <label for="user_assigned_id" class="control-label thin-weight">@lang('Assign user')</label>
                            <select name="user_assigned_id" id="user_assigned_id" class="form-control">
                                @foreach($users as $user => $userK)
                                    <option value="{{$user}}" {{isset($project) && $project->user_assigned_id == $user?'selected':''}} >{{$userK}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="form-group">
                            @if(Request::get('client') != "" || $client)
                                <input type="hidden" name="client_external_id" value="{!! Request::get('client') ?: $client->external_id !!}">
                            @else
                                <label for="client_external_id" class="control-label thin-weight">@lang('Assign client')</label>
                                <select 
                                name="client_external_id" 
                                id="client_external_id" 
                                data-container="body" 
                                data-live-search="true" 
                                data-style-base="form-control"
                                data-style=""
                                data-width="100%">
                                @if($clients->isEmpty())
                                        <option value="" default></option>
                                        <option value="new_client">+ @lang('Create New Client')</option>
                                @endif
                                @foreach($clients as $client => $clientK)
                                        <option value="{{$client}}">{{$clientK}}</option>
                                @endforeach
                                </select>
                            @endif
                        </div> --}}
                        {{-- <div class="form-group">
                            <label for="deadline" class="control-label thin-weight">@lang('Deadline')</label>
                            <input type="text" id="deadline" name="deadline" data-value="{{now()->addDays(3)}}" class="form-control">
                        </div> --}}
                        <div class="form-group">
                            <label for="status" class="control-label thin-weight">@lang('Status')</label>
                            <select name="status_id" id="status" class="form-control">
                                @foreach($statuses as $status => $statusK)
                                    <option value="{{$status}}">{{$statusK}}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="form-group" >
                                <label for="paymentMethod" class="control-label thin-weight">payment method</label>
                                <select name="payment_method" id="paymentMethod" class="form-control">
                                    @if (!isset($project) || !$project->payment_method)
                                        <option value="" selected></option>
                                    @endif
                                    <option value="installments" {{isset($project) && $project->payment_method == 'installments'?'selected':''}} >installments</option>
                                    <option value="cash" {{isset($project) && $project->payment_method == 'cash'?'selected':''}} >cash</option>
                                </select>
                            </div>
                            <div class="form-group" id="installmentsFormGroup" style="display: none;">
                                <label for="installmentsPolicy" class="control-label thin-weight">Installments Policy</label>
                                <select name="installments_policy" id="installmentsPolicy" class="form-control">
                                    @if (!isset($project) || !$project->finished_status)
                                        <option value="" selected></option>
                                    @endif
                                    <option value="down payment" {{isset($project) && $project->installments_policy == 'down payment'?'selected':''}} >Down Payment</option>
                                    <option value="quarter payment" {{isset($project) && $project->installments_policy == 'quarter payment'?'selected':''}} >Quarter Payment</option>
                                    <option value="period" {{isset($project) && $project->installments_policy == 'period'?'selected':''}} >Period</option>
                                </select>
                            </div>
                            
                            <div class="form-group" id="periodFormGroup" style="display: none;">
                                <label for="period" class="control-label thin-weight">How many ("period")</label>
                                <input type="number" name="period" id="period" class="form-control" min="0" value="{{isset($project) ? $project->period:''}}">
                            </div>
                            <div class="form-group">
                                <label for="price" class="control-label thin-weight">price</label>
                                <input type="number" name="price" id="price" class="form-control" min="0" value="{{isset($project) ? $project->price:''}}">
                            </div>
                        
                        {{csrf_field()}}
                        <div class="form-group">
                            <input type="submit" class="btn btn-md btn-brand movedown" id="createProject" disabled='disabled' value=" {{isset($project)?__('update project'):__('Create project')}}">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    {{-- <div class="alert alert-danger title-alert" style="display: none;">
        {{__("Title is required")}}
    </div>
    <div class="alert alert-danger description-alert" style="display: none;">
        {{__("Description is required")}}
    </div>
    <div class="alert alert-danger client-alert" style="display: none;">
        {{__("Client is required")}}
    </div> --}}

@stop

@push('scripts')
    <script>
        Dropzone.autoDiscover = false;
        $(document).ready(function () {
          $('#client_external_id').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            var value = $("#client_external_id").val();
            if(value == "new_client") {
              window.location.href = "/clients/create"
            }
          });

          $('#client_external_id').selectpicker()
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
            myDropzone = null;
            @if(Entrust::can('project-upload-files') && $filesystem_integration)
            var myDropzone = new Dropzone("#createProjectForm", {
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 5,
                maxFiles: 50,
                addRemoveLinks: true,
                previewsContainer: "#dropzone-images",
                clickable:'#dropzone-images',
                paramName: 'images',
                acceptedFiles: "image/*,application/pdf, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.spreadsheetml.template, application/vnd.openxmlformats-officedocument.presentationml.template, application/vnd.openxmlformats-officedocument.presentationml.slideshow, application/vnd.openxmlformats-officedocument.presentationml.presentation, application/vnd.openxmlformats-officedocument.presentationml.slide, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.wordprocessingml.template, application/vnd.ms-excel.addin.macroEnabled.12, application/vnd.ms-excel.sheet.binary.macroEnabled.12,text/rtf,text/plain,audio/*,video/*,.csv,.doc,.xls,.ppt,application/vnd.ms-powerpoint,.pptx",

            });

            myDropzone.on("success", function(file, response) {
                window.location.href = ("/projects/"+response.project_external_id)
            });

            myDropzone.on("processing", function(file, response) {
                $('input[type="submit"]').attr("disabled", true);
            });
            myDropzone.on("error", function(file, response) {
                $('input[type="submit"]').attr("disabled", false);
            });

            @endif
            $('input[type="submit"]').on("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (myDropzone != null && myDropzone.getQueuedFiles().length > 0) {
                    myDropzone.processQueue();

                } else {
                    $.ajax({
                        type: 'post',
                        url: '{{isset($project)?route('projects.update.all',$project->external_id):route('projects.store')}}',
                        data: $("#createProjectForm").serialize(),
                        success: function(response){
                            window.location.href = ("/projects/"+response.project_external_id)
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            console.log(textStatus);
                            console.log(errorThrown);

                            var errors = jqXHR.responseJSON.errors;
                            console.log(errors);
                            const validationMsg = document.getElementById('validationMsg');
                            validationMsg.style.display = '';
                            $('#validationMsg').html('');
                            $.each(errors, function(key, values) {
                                console.log(values);
                                $.each(values, function(index, value) {
                                    $('#validationMsg').append('<li>' + value + '</li>');
                                });
                            });
                            // if (jqXHR.responseJSON.errors.title != undefined) {
                            //     $('.title-alert').show();
                            // } else {
                            //     $('.title-alert').hide();
                            // }
                            // if (jqXHR.responseJSON.errors.description != undefined) {
                            //     $('.description-alert').show();
                            // }else {
                            //     $('.description-alert').hide();
                            // }
                            // if (jqXHR.responseJSON.errors.client_external_id != undefined) {
                            //     $('.client-alert').show();
                            // }else {
                            //     $('.client-alert').hide();
                            // }

                        }
                    });
                }

            });



        });


    </script>
        <script>
            // Get the select elements
            var unitTypeSelect = document.getElementById("unitType");
            var subTypeSelect = document.getElementById("subType");
    
            // Add event listener to unitType select element
            unitTypeSelect.addEventListener("change", function() {
            // Get the selected value
            var selectedValue = unitTypeSelect.value;
    
            // Clear subType select element
            subTypeSelect.innerHTML = "";
    
            // Create and append the empty option
            var emptyOption = document.createElement("option");
            emptyOption.value = "";
            emptyOption.text = "";
            emptyOption.selected = true;
            subTypeSelect.appendChild(emptyOption);
    
            // Add options based on the selected value
            if (selectedValue === "apartment") {
                // Add apartment options
                addOption("duplex", "duplex");
                addOption("pent house", "pent house");
                // Add more options as needed
            } else if (selectedValue === "villa") {
                // Add challet options
                addOption("stand alone", "stand alone");
                addOption("twin", "twin");
                addOption("town", "town");
                // Add more options as needed
            } else if (selectedValue === "office") {
                // Add office options
                addOption("administrative", "administrative");
                addOption("medical", "medical");
                addOption("commercial", "commercial");
                
                // Add more options as needed
            }
            });
            // Trigger the change event on page load
            var changeEvent = new Event('change');
            unitTypeSelect.dispatchEvent(changeEvent);
    
            // Function to add options to subType select element
            function addOption(value, text) {
            var option = document.createElement("option");
            option.value = value;
            option.text = text;
            subTypeSelect.appendChild(option);
            }
    

           // Function to change the selected option by value
        function changeSelectedOptionByValue(selectElement, value) {
            var options = selectElement.options;
            for (var i = 0; i < options.length; i++) {
                if (options[i].value === value) {
                    options[i].selected = true;
                    break;
                }
            }
        }
<?php if(isset($project)){ ?>
    // Call the function to change the selected option by value
    changeSelectedOptionByValue(subTypeSelect, "{{$project->sub_type}}"); // Set the option with value "option2" as selected
<?php }?>

        </script>
        <script>
            $(document).ready(function() {
                $('#paymentMethod').on('change', function() {
                    var selectedOption = $(this).val();
                    if (selectedOption === 'installments') {
                        $('#installmentsFormGroup').show();
                    } else {
                        $('#installmentsFormGroup').hide();
                    }
                }).trigger('change');
                $('#installmentsPolicy').on('change', function() {
                    console.log('installmentsPolicy');
                    var selectedOption = $(this).val();
                    if (selectedOption === 'period') {
                        $('#periodFormGroup').show();
                    } else {
                        $('#periodFormGroup').hide();
                    }
                }).trigger('change');

            });
        </script>
@endpush
