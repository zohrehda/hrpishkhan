@foreach($form_sections_items as $section_title=>$section_content)

    <div id="{{$section_title}}">
        <h3>{{$section_content['title']}}</h3>
        <div class="card form-space">
            <div class="card-header">
                <div class="row">
                    @foreach($section_content['items'] as $name=>$schema)
                        <div class="col-{{$schema['grid_col']}}">
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
                                        <option @if( old($name)=='' || !isset($requisition)  ) selected @endif disabled
                                                value="empty">
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

                                @case('multiple')

                                @include('requisitions.partials.'.$name,['data'=>$schema['data']   ,'requisition' =>$requisition??null  ] )

                                @break
                                @case('radio')
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <label class="pr-1 @if($schema['required']) required @endif"></label>
                                            @foreach($schema['radios'] as $value=>$radio)
                                                <div class="custom-control custom-radio custom-control-inline col ">
                                                    <input type="radio" id="{{$radio}}" name="{{$name}}" value="{{$value}}"
                                                           class="custom-control-input"
                                                           @if((string)old($name,isset($requisition)?$requisition->getOriginal($name):'f' )==(string)$value) checked @endif >
                                                    <label class="custom-control-label" for="{{$radio}}">{{$radio}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                                @break
                                @case('textarea')
                                <label for="{{$name}}"
                                       class=@if($schema['required']) 'required' @endif>{{$schema['label']}}</label>
                                <textarea type="text" id="{{$name}}" name="{{$name}}"
                                          placeholder="{{$schema['placeholder']}}" rows="3"
                                          class="form-control form-space">{{ old($name,$requisition->$name??'')}}</textarea>
                                @break
                            @endswitch
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


@endforeach



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
            <button name="progress_result" value="1" type="submit" class="btn btn-green">Accept
            </button>
            <button name="progress_result" value="2" type="submit" class="btn btn-yellow">Reject
            </button>
        @elsecannot('accept',$requisition??null)
            <button type="submit" class="btn btn-success" onclick="return confirm('Updating will result in status reset on approval_progresses\n' +
                             'Are you sure?')">Update
            </button>
        @endcan
    @endif

    @if($form=='create')
        <button type="submit" id='submit-requisition' class="btn btn-green">Submit</button>
    @endif



</div>

<div class="hover-buttons">
    <button type="button"
            data-toggle="modal" data-target="#DraftNameModal"
            id='draft-requisition' class="btn btn-blue">Draft
    </button>

    <button type="button"
            data-toggle="modal" data-target="#DraftImportModal"
            id='import-requisition' class="btn btn-navy">Template
    </button>

    @can('add_viewer',$requisition??null)
    <button type="button"
            data-toggle="modal" data-target="#AddViewer"
            id='import-requisition' class="btn btn-pink">Add Viewer
    </button>
    @endcan
    @can('hold', $requisition??null)
        <button
            name="progress_result"
            value="{{App\Requisition::HOLDING_STATUS}}"
            class="btn   btn-orange">Hold
        </button>
    @endcan
    @can('close', $requisition??null)
        <button
            name="progress_result"
            value="{{App\Requisition::CLOSED_STATUS}}"
            onclick="return confirm('Are you sure to close the requisition?')"
            class="btn btn-sm btn-black">Close

        </button>
    @endcan

</div>

@section('script')
    <script>
        $(function () {
            var competency = @json($requisition->competency??null) ;
            competency = JSON.parse(competency)
            if (competency) {
                competency_html(competency)
            } else {
                var competency = @json(old('competency')) ;
                for (i=0;i<=5;i++){
                    competency_html(competency)
                }
            }

            var interviewer = @json($requisition->interviewers??null) ;
            interviewer = JSON.parse(interviewer);

            if (interviewer) {
                interviewer_html(interviewer)
            } else {
                var interviewers = @json(old('interviewers')) ;
                interviewer_html(interviewers)

                //   $("#add_interviewer").trigger('click')
            }


            var level = @json($requisition->level??null) ;

            if(level){
                $('select#level').val(level)
            }
console.log($('select#level').val())


        });


    </script>

@endsection



