@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp
                          
                            @foreach($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif

                                <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if ($add && isset($row->details->view_add))
                                        @include($row->details->view_add, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'add', 'options' => $row->details])
                                    @elseif ($edit && isset($row->details->view_edit))
                                        @include($row->details->view_edit, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'edit', 'options' => $row->details])
                                    @elseif (isset($row->details->view))
                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                                    @elseif ($row->type == 'relationship')
                                        @include('voyager::formfields.relationship', ['options' => $row->details])
                                    @else
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach
                                    @if ($errors->has($row->field))
                                        @foreach ($errors->get($row->field) as $error)
                                            <span class="help-block">{{ $error }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach

                        </div><!-- panel-body -->
                        <!-- ### Services ### -->
                        <div class="panel panel-bordered panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="icon wb-image"></i> {{ __('Services') }}</h3>
                                <div class="panel-actions">
                                    <a class="panel-action voyager-angle-down" data-toggle="panel-collapse"
                                    aria-hidden="true"></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                @if($dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )})

                                    @php
                                        $syllabus = \App\Models\Syllabus::find($dataTypeContent->id);
                                        if(isset($syllabus->serviceLevelGroupPosition))
                                        {
                                            $services = $syllabus->serviceLevelGroupPosition->pluck('service_id')->toArray();
                                            if(!empty($services))
                                            {
                                                $service_id = json_decode($services[0], true);
                                                $ser_detail = \App\Models\Service::whereIn('id', $service_id)->get();
                                            }
                                            else 
                                            {
                                                $ser_detail = null;
                                            }
                                        }
                                        elseif(isset($syllabus->level))
                                        {
                                            $levels = $syllabus->serviceLevelGroupPosition->pluck('level_id')->toArray();
                                            if(!empty($levels))
                                            {
                                                $level_id = json_decode($levels[0], true);
                                                $lev_detail = \App\Models\Level::whereIn('id', $level_id)->get();
                                            }
                                            else 
                                            {
                                                $lev_detail = null;
                                            }
                                        }
                                        else 
                                        {
                                            $ser_detail = null;
                                        }
                                        // $services_detail = \App\Models\ServiceLevelGroupPositionPivot::where('service_id', $services[0])->get();
                                        // $level_detail = \App\Models\ServiceLevelGroupPositionPivot::where('position_id', $)
                                        $all_services = \App\Models\Service::get();
                                        $all_levels = \App\Models\Level::get();
                                        // dd($all_services);
                                    @endphp
                                @endif
                                <select class="form-control select2 select2-hidden-accessible"
                                        name="services[]" multiple="multiple"
                                        id="services">
                                    @foreach($all_services as $ser)
                                        <option data-services={{$ser->id}} value="{{$ser->id}}" {{ ($ser_detail?($ser_detail->contains('id', $ser->id)):null)?'selected':'' }}>{{ $ser->title  }} </option>
                                    @endforeach
                                </select>
                                
                                <div class="form-group" id="levels">
                                    
                                </div>
                                <div class="form-group" id="groups-container">

                                </div>
                                <div class="form-group" id="positions-container">

                                </div>
                            </div>
                        </div>
                        <!-- Services end -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>

                    <div style="display:none">
                        <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                        <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

      

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script type="text/javascript">
        var selectedServices = $('#services').val();
        var selectedLevels = $('input[name="levels[]"]').value;
        var selectedGroups = $('input[name="groups[]"]').value;
        if (selectedServices.length > 0) {
            $.ajax({
                url: "{{ route('services.get-level') }}?syllabus_id={{$dataTypeContent->id}}",
                method: 'GET',
                data: ({ services: selectedServices, levels: selectedLevels, groups: selectedGroups }),
                success: function (response) {
                    $('#levels').html(response.html);
                    console.log(response);
                    var levelIds = response.levelId;
                    var checked = response.var;
                    $.ajax({
                        url: "{{ route('levels.get-group') }}?syllabus_id={{$dataTypeContent->id}}",
                        method: 'GET',
                        data: { level_id_default: levelIds, checked: checked },
                        success: function(content) {
                            // Update the HTML to display the fetched groups
                            $('#groups-container').html(content.html);
                            var groupIds = content.groupId;
                            var checked = content.var;
                            $.ajax({
                                url: "{{ route('groups.get-position') }}?syllabus_id={{$dataTypeContent->id}}",
                                method: 'GET',
                                data: { group_id_default: groupIds, checked: checked },
                                success: function(position) {
                                    console.log(position);
                                    // Update the HTML to display the fetched groups
                                    $('#positions-container').html(position.html);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error loading positions:', error);
                                }
                            }); 
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading data:', error);
                        }
                    }); 
                }
            });
        }
        $("#services").change(function () {
                    $.ajax({
                        url: "{{ route('services.get-level') }}?service_id=" + $(this).val() + "&syllabus_id={{$dataTypeContent->id }}",
                        method: 'GET',
                        success: function (data) {
                            console.log(data.html);
                            $('#levels').html(data.html);
                        }
                    });
                });

        function loadGroups(checkbox) {
            // Make an AJAX request to fetch groups for the selected level
            var checkedIds = [];
            $('.loadGroups:checked').each(function() {
                checkedIds.push($(this).val());
            });
            var checked = checkbox.checked;
            var levelId = checkbox.value;
            var initial_ids = $('.loadGroups:last').data('initialids');
            $.ajax({
                url: "{{ route('levels.get-group') }}?syllabus_id={{$dataTypeContent->id}}",
                method: 'GET',
                data: { level_id: levelId, checked: checked, checked_ids: checkedIds},
                success: function(response) {
                    // Update the HTML to display the fetched groups
                    $('#groups-container').html(response.html);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading groups:', error);
                }
            });         
        }

        function loadPositions(checkbox) {
            var checkedIds = [];
            // Make an AJAX request to fetch groups for the selected level
            $('.loadPositions:checked').each(function() {
                checkedIds.push($(this).val());
            });
            var checked = checkbox.checked;
            var groupId = checkbox.value;
            var initial_ids = $('.loadPositions:last').data('initialids');
            $.ajax({
                url: "{{ route('groups.get-position') }}?syllabus_id={{$dataTypeContent->id}}",
                method: 'GET',
                data: {group_id: groupId, checked: checked, checked_ids: checkedIds},
                success: function(response) {
                    console.log(response);
                    // Update the HTML to display the fetched groups
                    $('#positions-container').html(response.html);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading positions:', error);
                }
            });         
        }
    </script>
@stop
