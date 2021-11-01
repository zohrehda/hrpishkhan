@extends('layouts.panel')

@section('title', 'Create Requisition')

@section('content')


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <form action="{{ Route('requisitions.store') }}" method="POST" id="form">
                    @csrf
                    <h3>Requisition information</h3>
                    <div class="card form-space">
                        <div class="card-header">

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="department">Department </label>
                                    <select id="department" name="department"
                                            class="form-space form-control select2">

                                        <option selected disabled> Empty</option>
                                        @foreach($departments as $k=>$v)
                                            <option value="{{ $k }}"
                                                    @if(old('department')!=null  &&  old('department')==$k ) selected @endif >{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="level">Level</label>
                                    <select id="level" name="level"
                                            class="form-space form-control select2">
                                        <option selected disabled> Empty</option>

                                    </select>
                                </div>
                            </div>


                        </div>
                    </div>

                    <h3>Position information</h3>
                    <div class="card form-space">
                        <div class="card-header">
                            <label for="fa_title">Persian Job Title</label>
                            <input type="text" id="fa_title" name="fa_title" placeholder="Fa title"
                                   class="form-control form-space"
                                   value="{{ old('fa_title') }}">
                            <label for="en_title">English Job Title</label>
                            <input type="text" id="en_title" name="en_title" placeholder="En title"
                                   class="form-control form-space"
                                   value="{{ old('en_title') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="position_count">Position count</label>
                                    <input type="number" id="position_count" name="position_count"
                                           class="form-control form-space" value="{{ old('position_count') }}">
                                </div>

                                <div class="col-md-12">
                                    <label for="report_to">Report to</label>
                                    <input type="text" id="report_to" name="report_to" placeholder="report to"
                                           class="form-control form-space"
                                           value="{{ old('report_to') }}">
                                </div>


                                <div class="col-md-12">
                                    <label for="location">Location</label>
                                    <input type="text" id="location" name="location" placeholder="location"
                                           class="form-control form-space"
                                           value="{{ old('location') }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="location">City</label>
                                    <input type="text" id="city" name="city" placeholder="city"
                                           class="form-control form-space"
                                           value="{{ old('city') }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="location">Direct manager</label>
                                    <input type="text" id="city" name="direct_manger" placeholder="direct manger"
                                           class="form-control form-space"
                                           value="{{ old('direct_manger') }}">
                                </div>

                                <div class="col-md-12">
                                    <label for="location">Venture</label>
                                    <input type="text" id="venture" name="venture" placeholder="venture"
                                           class="form-control form-space"
                                           value="{{ old('venture') }}">
                                </div>


                                <div class="col-md-12">
                                    <label for="seniority">Seniority</label>
                                    <input type="text" id="city" name="seniority" placeholder="seniority"
                                           class="form-control form-space"
                                           value="{{ old('seniority') }}">
                                </div>


                                <div class="col-md-12">
                                    <label for="shift" class='optional'>Shift (Only for Call Center Positions)</label>
                                    <select id="shift" name="shift"
                                            class="form-space form-control col-md-3">
                                        <option value="0" selected>Empty</option>
                                        <option value="1" @if( old('shift' )==1 ) selected @endif >Morning (Women)
                                        </option>
                                        <option value="2" @if( old('shift' )==2 ) selected @endif >Evening & Night
                                            (Men)
                                        </option>
                                        <option value="3" @if( old('shift' )==3 ) selected @endif >Holiday (Women)
                                        </option>
                                        <option value="4" @if( old('shift' )==4 ) selected @endif >Holiday (Men)
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    {{--  <label for="is_full_time">Full time</label>
                                      <input type="checkbox" id="is_full_time" name="is_full_time"
                                             class="form-space">--}}
                                    <label class='pr-2'></label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="full_time" name="time" value="1"
                                               class="custom-control-input"
                                               @if(old('time')=='1') checked @endif
                                        >

                                        <label class="custom-control-label" for="full_time">Full Time </label>
                                    </div>


                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="part_time" name="time" value="0"
                                               class="custom-control-input"
                                               @if(old('time')=='0') checked @endif

                                        >
                                        <label class="custom-control-label" for="part_time">Part Time</label>
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    {{--  <label for="is_new">New hiring</label>
                                      <input type="checkbox" id="is_new" name="is_new"
                                             class="form-space">--}}

                                    <div id="form-radio-hiring_type">
                                        <label class='pr-2'></label>
                                        <div class="custom-control custom-radio custom-control-inline ">
                                            <input type="radio" id="new" name="hiring_type" value="1"
                                                   class="custom-control-input"
                                                   @if(old('hiring_type')=='1') checked @endif
                                            >
                                            <label class="custom-control-label" for="new">New hiring </label>

                                        </div>

                                        <div class="custom-control custom-radio custom-control-inline ">
                                            <input type="radio" id="replacement" name="hiring_type" value="0"
                                                   class="custom-control-input"
                                                   @if(old('hiring_type')=='0') checked @endif

                                            >

                                            <label class="custom-control-label mr-2"
                                                   for="replacement">Replacement</label>

                                            <input type="text" id="form-input-replacement" name="replacement"
                                                   placeholder="replace with"
                                                   class="form-control d-none form-input-replacement "
                                                   value="{{ old('replacement') }}">

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <h3>Job requirements</h3>
                    <div class="card form-space">
                        <div class="card-header">
                            <label for="field_of_study">Field of study</label>
                            {{--<select id="field_of_study" name="field_of_study"
                                    class="form-space form-control col-md-3">
                                <option value="1">Senior</option>
                                <option value="2">Junior</option>
                            </select>--}}
                            <input type="text" placeholder="Field of study" id="field_of_study" name="field_of_study"
                                   class="form-control form-space"
                                   value="{{ old('field_of_study') }}">
                            <label for="degree">Minimum Degree</label>
                            <select id="degree" name="degree"
                                    class="form-space form-control col-md-3">

                                <option value="1" @if( old('degree') ==1 ) selected @endif>Diploma</option>
                                <option value="2" @if( old('degree') ==2 ) selected @endif>B.A/BSc.</option>
                                <option value="3" @if( old('degree') ==3 ) selected @endif> M.A/MSc.</option>
                                <option value="4" @if( old('degree') ==4 ) selected @endif>PHD</option>

                            </select>
                            <label for="experience_year">Minimum Work Experience</label>
                            <select id="experience_year" name="experience_year"
                                    class="form-control   form-space col-md-3">

                                <option value="1" @if( old('experience_year') ==1 ) selected @endif >Fresh Graduate
                                </option>
                                <option value="2" @if( old('experience_year') ==2 ) selected @endif >1</option>
                                <option value="3" @if( old('experience_year') ==3 ) selected @endif>1-2</option>
                                <option value="4" @if( old('experience_year') ==4 ) selected @endif>2-4</option>
                                <option value="5" @if( old('experience_year') ==5 ) selected @endif>4-6</option>
                                <option value="6" @if( old('experience_year') ==6 ) selected @endif>6-10</option>
                                <option value="7" @if( old('experience_year') ==7 ) selected @endif >More than 10
                                </option>
                            </select>
                            <label for="mission">Mission</label>
                            <textarea type="text" id="mission" name="mission"
                                      placeholder="{{config('placeholders.mission')}}" rows="3"
                                      class="form-control form-space">{{ old('mission') }}</textarea>
                            <label for="competency">Competency</label>
                            <textarea type="text" id="competency" name="competency" rows="3"
                                      placeholder="{{config('placeholders.competency')}}"
                                      class="form-control form-space">{{ old('competency') }}</textarea>
                            <label for="outcome">Outcome</label>
                            <textarea type="text" id="outcome" name="outcome" rows="3"
                                      placeholder="{{config('placeholders.outcome')}}"
                                      class="form-control form-space">{{ old('outcome') }}</textarea>
                            <label for="about" class='optional'>about the team</label>
                            <textarea type="text" id="about" name="about"
                                      placeholder="about the team"
                                      class="form-control form-space">{{ old('about') }}</textarea>
                        </div>
                    </div>

                    <h3>Interviewers</h3>
                    <div class="card form-space">
                        <div class="card-header">

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

                            <div class="row">
                                <div class="col-12">
                                    <button type="button" id="add_interviewer" class="btn btn-sm btn-success">add
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                   @include('partials.tmp_interviewers_form')
                    <h3>Receiver selection</h3>
                    <div class="card form-space">
                        <div class="card-header">
                            <div class="row form-receivers-part">
                                {{--     <div class="col-md-4">
                                      <label for="determiners">Hiring manager</label>
                                      <select id="determiners" name="determiners[1]"
                                              class="form-space form-control select2">
                                          <option selected disabled>Empty</option>
                                          @foreach($users as $user)
                                              <option value="{{ $user->id }}">{{ $user->email }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                               <div class="col-md-4">
                                      <label for="determiners">Head of hiring manager</label>
                                      <select id="determiners" name="determiners[2]"
                                              class="form-space form-control select2">
                                          <option selected disabled>Empty</option>
                                          @foreach($users as $user)
                                              <option value="{{ $user->id }}">{{ $user->email }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                  <div class="col-md-4">
                                      <label for="determiners">Department manager</label>
                                      <select id="determiners" name="determiners[3]"
                                              class="form-space form-control select2">
                                          <option selected disabled>Empty</option>
                                          @foreach($users as $user)
                                              <option value="{{ $user->id }}">{{ $user->email }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                  <div class="col-md-4">
                                      <label for="determiners">cxo</label>
                                      <select id="determiners" name="determiners[4]"
                                              class="form-space form-control select2">
                                          <option selected disabled>Empty</option>
                                          @foreach($users as $user)
                                              <option value={{ $user->id }}>{{ $user->email }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                  <div class="col-md-4">
                                      <label for="determiners">HR manager</label>
                                      <select id="determiners" disabled
                                              class="form-space form-control select2">
                                          <option
                                              value="{{ $hr_manager_user->id }}">{{ $hr_manager_user->email }}</option>
                                      </select>
                                  </div>--}}
                                <div class="col-md-12 text-center">
                                    <a href="#" onclick="return false;" class="clear">clear</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="center">
                        <button type="submit" id='submit-requisition' class="btn btn-success">Submit</button>
                        <button type="button"
                                data-toggle="modal" data-target="#DraftNameModal"
                                id='draft-requisition' class="btn btn-primary">Draft
                        </button>

                        <button type="button"
                                data-toggle="modal" data-target="#DraftImportModal"
                                id='import-requisition' class="btn btn-warning">Import
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('a.clear').click(function () {
            $(".select2").each(function () {
                $(this).find('option:first').each(function () {
                    $(this).prop("selected", "selected");
                });
            });
        })
    </script>

    <div class="modal fade" id="termsModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">terms and conditions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="firstTermsModel" data-backdrop="static" data-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">terms and conditions</h5>

                </div>
                <div class="modal-body">

                    @include('requisitions.terms')

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">accept</button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DraftNameModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-draft">
                <div class="modal-content">

                    <div class="modal-body">


                        <div class="custom-control custom-checkbox d-none ">
                            <input type="checkbox" class="custom-control-input" name="draft_update" value="1"
                                   id="draft_update" data-draft-name="">
                            <label class="custom-control-label" for="draft_update">update</label>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="draft_public" value="1"
                                   id="draft-public">
                            <label class="custom-control-label" for="draft-public">public</label>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label optional">name the draft:</label>
                            <input type="text" class="form-control " id="draft-name" name="draft_name" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="save-draft-requisition" class="btn btn-primary">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="modal fade" id="DraftImportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <ul class="list-group">
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @if(config('app.required_terms'))

        <script>

            var termAccepted =  @json(session('termAccepted') ) ;
            console.log(termAccepted);
            if (termAccepted == 0) {
                $('#firstTermsModel').modal('show');

            }

            $("#submit-requisition").click(function (e) {
                e.preventDefault();

                $(this).prop('disabled', true);

                $('#form').submit();


            })


        </script>
    @endif

@stop
