@foreach($form_sections_items as $section_title=>$section_content)
    <h3>{{$section_title}}</h3>
    <div class="card form-space">
        <div class="card-header">

            <div class="row">
                @foreach($section_content as $name=>$schema)

                    <div class="col-12">
                        @if(1)
                            @switch($schema['type'])
                                @case('text')
                                <label for="{{$name}}"
                                       class=@if($schema['required']) 'required' @endif>{{$schema['label']}}</label>

                                <input type="text" id="{{$name}}" name="{{$name}}" class="form-control form-space"
                                       placeholder="{{$schema['label']}}"
                                       value="{{ old($name,$requisition->$name??'') }}">
                                @break

                                @case('number')
                                <label for="{{$name}}"
                                       class=@if($schema['required']) 'required' @endif>{{$schema['label']}}</label>

                                <input type="number" id="{{$name}}" name="{{$name}}" class="form-control form-space"
                                       placeholder="{{$schema['label']}}"
                                       value="{{ old($name,$requisition->$name??'') }}">
                                @break

                                @case('select')
                                <label for="{{$name}}"
                                       class=@if($schema['required']) 'required' @endif>{{$schema['label']}}</label>


                                <select id="{{$name}}" name="{{$name}}"

                                        class="form-space custom-select">
                                    @if($schema['required'] )
                                        <option @if( old($name)=='' || !isset($requisition)  ) selected @endif disabled>
                                            Empty
                                        </option>
                                    @endif
                                    @foreach($schema['options'] as $value=>$option)
                                        {{$value}}
                                        <option value="{{$value}}"
                                                @if( old($name,isset($requisition)?$requisition->getOriginal($name):'' )==$value) selected @endif
                                        >{{$option}}</option>
                                    @endforeach
                                </select>
                                @break


                                @case('radio')

                                <label class="pr-1 @if($schema['required']) required @endif"></label>

                                @foreach($schema['radios'] as $value=>$radio)
                                    <div class="custom-control custom-radio custom-control-inline ">
                                        <input type="radio" id="{{$radio}}" name="{{$name}}" value="{{$value}}"
                                               class="custom-control-input"
                                               @if( old($name,isset($requisition)?$requisition->getOriginal($name):'empty' )==$value) checked @endif>
                                        <label class="custom-control-label" for="{{$radio}}">{{$radio}}</label>

                                    </div>
                                @endforeach


                                @break

                                @case('textarea')
                                <label for="{{$name}}"
                                       class=@if($schema['required']) 'required' @endif>{{$schema['label']}}</label>
                                <textarea type="text" id="{{$name}}" name="{{$name}}"
                                          placeholder="{{$schema['placeholder']}}" rows="3"
                                          class="form-control form-space">{{ old($name,$requisition->$name??'')}}</textarea>
                                @break


                            @endswitch
                        @endif


                    </div>
                @endforeach
            </div>

        </div>
    </div>

@endforeach



<h3>Competency</h3>
<div class="card form-space">
    <div class="card-header">
        <div id="competency_form_row">
        </div>
        <div class="row">
            <div class="col-12">
                <button type="button" id="add_competency" class="btn btn-sm btn-success">add
                </button>
            </div>
        </div>
    </div>
</div>

<h3>Interviewers</h3>
<div class="card form-space">
    <div class="card-header">

        @if(!empty($requisition->interviewers))
            @foreach(json_decode($requisition->interviewers,true) as $k=>$interviewer)
                <div class="form-row" data-form-num="{{$k}}">
                    <div class="form-group col-md-6">
                        <label for="interviewer_name" class="optional">name</label>
                        <input type="text" name="interviewers[{{$k}}][]" value="{{$interviewer[0]}}"
                               class="form-control" id="interviewer_name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="interviewer_skype_id" class="optional">skype id</label>
                        <input type="text" class="form-control" value="{{$interviewer[1]}}"
                               name="interviewers[{{$k}}][]"
                               id="interviewer_skype_id">
                    </div>
                </div>
            @endforeach
        @else
            <div id="interviewer_form_rows">
                <div class="form-row" data-form-num="1">
                    <div class="form-group col-md-6">
                        <label for="interviewer_name" class="optional">name</label>
                        <input type="text" name="interviewers[1][]" class="form-control" id="interviewer_name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="interviewer_skype_id" class="optional">skype id</label>
                        <input type="text" class="form-control" name="interviewers[1][]"
                               id="interviewer_skype_id">
                    </div>
                </div>
            </div>

        @endif
        <div class="row">
            <div class="col-12">
                <button type="button" id="add_interviewer" class="btn btn-sm btn-success">add
                </button>
            </div>
        </div>
    </div>
</div>

<h3>Receiver selection</h3>
<div class="card form-space">
    <div class="card-header">

        @if(isset($requisition))

            <div class="row">
                @foreach($requisition->determiners as $determiner)
                    <div class="col-md-6">
                        <label for="determiners">Receiver</label>
                        <select id="" name="determiners[]"
                                class="form-space form-control " disabled>
                            <option selected disabled>{{$determiner->email}}</option>

                        </select>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row form-receivers-part">
                <div class="col-md-6">
                    <label for="determiners">Receiver</label>
                    <select id="" name="determiners[]"
                            class="form-space form-control select2 approver">
                        <option selected disabled>Empty</option>

                    </select>
                </div>
                <div class="col-md-6">
                    <label for="determiners">Receiver</label>
                    <select id="" name="determiners[]"
                            class="form-space form-control select2 approver">
                        <option selected disabled>Empty</option>

                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12">
                    <button type="button" id="add_receiver" class="btn btn-sm btn-success">add
                    </button>
                </div>
            </div>

        @endif


        <div class="row">
            <div class="col-md-12 text-center">
                <a href="#" onclick="return false;" class="clear">clear</a>
            </div>
        </div>
    </div>
</div>


@can('accept',$requisition??null)
    <h3>Comment</h3>
    <div class="card form-space">
        <div class="card-header">
            <label for="determiner_comment">Comment</label>
            <textarea type="checkbox" id="determiner_comment" name="determiner_comment"
                      class="form-space form-control"></textarea>
        </div>
    </div>
@endcan

<div class="center">
    @if($form=='edit')
        @can('accept',$requisition??null)
            <button name="progress_result" value="1" type="submit" class="btn btn-success">Accept
            </button>
            <button name="progress_result" value="2" type="submit" class="btn btn-dark">Reject
            </button>
        @elsecannot('accept',$requisition??null)
            <button type="submit" class="btn btn-success" onclick="return confirm('Updating will result in status reset on progresses\n' +
                             'Are you sure?')">Update
            </button>
        @endcan
    @endif

    @if($form=='create')
        <button type="submit" id='submit-requisition' class="btn btn-success">Submit</button>
    @endif

    <button type="button"
            data-toggle="modal" data-target="#DraftNameModal"
            id='draft-requisition' class="btn btn-primary">Draft
    </button>

    <button type="button"
            data-toggle="modal" data-target="#DraftImportModal"
            id='import-requisition' class="btn btn-warning">Import
    </button>

</div>
@section('script')
    <script>
        $(function () {
            var competency = @json($requisition->competency??null) ;
            competency = JSON.parse(competency)
            if (competency) {
             ff(competency)
            } else {

                $("#add_competency").trigger('click');
            }
        });


    </script>

@endsection



