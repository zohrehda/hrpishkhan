@extends('layouts.panel')

@section('title', 'Edit Requisition')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <form id="form" action="{{ Route('requisitions.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{$requisition->id}}">
                    <h3>Requisition information</h3>
                    <div class="card form-space">
                        <div class="card-header">

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="department">department</label>
                                    <select id="department" name="department" disabled
                                            class="form-space form-control select2">

                                        @foreach($departments as $k=>$v)
                                            <option value="{{ $k }}" {{ ($requisition->department==$k)?'selected':''  }} >{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="level">Level</label>

                                    <select id="level" name="level" disabled
                                            class="form-space form-control select2">

                                        @foreach($levels as $k=>$v)

                                            <option value="{{ $k }}" {{ ($requisition->level==$k)?'selected':''  }} >{{ $v }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>


                        </div>
                    </div>

                    <h3>Position information</h3>
                    <div class="card form-space">
                        <div class="card-header">
                            <label for="fa_title">Fa title</label>
                            <input type="text" id="fa_title" name="fa_title" class="form-control form-space"
                                   value="{{ $requisition->fa_title }}">
                            <label for="en_title">En title</label>
                            <input type="text" id="en_title" name="en_title" class="form-control form-space"
                                   value="{{ $requisition->en_title }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="position_count">Position count</label>
                                    <input type="number" id="position_count" name="position_count"
                                           class="form-control form-space" value="{{ $requisition->position_count }}">
                                </div>

                                <div class="col-md-12">
                                  <label for="report_to" >Report to</label>
                            <input type="text" id="report_to" name="report_to"  placeholder="report to" class="form-control form-space"
                                   value="{{ $requisition->report_to }}">
                            </div>

                             <div class="col-md-12">
                                  <label for="location" >Location</label>
                            <input type="text" id="location" name="location"  placeholder="location" class="form-control form-space"
                                   value="{{ $requisition->location }}">
                            </div>


                                <div class="col-md-12">
                                    <label for="location">City</label>
                                    <input type="text" id="city" name="city" placeholder="city"
                                           class="form-control form-space"
                                           value="{{ $requisition->city }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="location">Direct manager</label>
                                    <input type="text" id="city" name="direct_manger" placeholder="direct manger"
                                           class="form-control form-space"
                                           value="{{ $requisition->direct_manger }}">
                                </div>

                                <div class="col-md-12">
                                    <label for="location">Venture</label>
                                    <input type="text" id="venture" name="venture" placeholder="venture"
                                           class="form-control form-space"
                                           value="{{ $requisition->venture }}">
                                </div>



                                <div class="col-md-12">
                                    <label for="seniority">Seniority</label>
                                    <input type="text" id="city" name="seniority" placeholder="seniority"
                                           class="form-control form-space"
                                           value="{{ $requisition->seniority }}">
                                </div>


                                <div class="col-md-12">
                                <label for="shift" class='optional'>Shift</label>
                            <select id="shift" name="shift"
                                    class="form-space form-control col-md-3">
                                <option value="0" {{($requisition->shift==null )?'selected':'' }} >Empty</option>
                                <option value="1"{{($requisition->shift===1 )?'selected':'' }} >morning</option>
                                <option value="2"{{($requisition->shift==2 )?'selected':'' }}>midday</option>
                                 <option value="3"{{($requisition->shift==3 )?'selected':'' }}>evening</option>
                                   <option value="4" {{($requisition->shift==4 )?'selected':'' }}>night</option>
                            </select>
                            </div>


                                <div class="col-md-12">
                                    {{--<label for="is_full_time">Full time</label>
                                    <input type="checkbox" @if($requisition->is_full_time == 1) checked
                                           @endif  id="is_full_time" name="is_full_time"
                                           class="form-space">--}}
                                           <label class='pr-2'></label>

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="full_time" name="time" value="1"
                                               class="custom-control-input"
                                            {{($requisition->is_full_time == 1)?'checked':''  }}
                                        >
                                        <label class="custom-control-label" for="full_time">Full Time </label>
                                    </div>

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="part_time" name="time" value="0"
                                               class="custom-control-input"
                                            {{($requisition->is_full_time == 0)?'checked':''  }}
                                        >
                                        <label class="custom-control-label" for="part_time">Part Time</label>
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    {{--<label for="is_new">New hiring</label>
                                    <input type="checkbox" @if($requisition->is_new == 1) checked @endif id="is_new"
                                           name="is_new"
                                           class="form-space">--}}
                                    <div id="form-radio-hiring_type">
                                        <label class='pr-2'></label>
                                        <div class="custom-control custom-radio custom-control-inline ">
                                            <input type="radio" id="new" name="hiring_type" value="1"
                                                   class="custom-control-input"
                                                {{($requisition->is_new==1 )?'checked':''  }}
                                            >
                                            <label class="custom-control-label" for="new">New hiring </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline ">
                                            <input type="radio" id="replacement" name="hiring_type" value="0"
                                                   class="custom-control-input"
                                                {{( $requisition->is_new==0 )?'checked':''  }}
                                            >
                                            <label class="custom-control-label mr-2"
                                                   for="replacement">replacement</label>

                                            <input type="text" id="form-input-replacement" name="replacement"
                                                   class="form-control d-none form-input-replacement "
                                                   value="{{ $requisition->replacement }}">
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
                            <input type="text" id="field_of_study" name="field_of_study" class="form-control form-space"
                                   value="{{ $requisition->field_of_study }}">
                            {{--  <select id="field_of_study" name="field_of_study"
                                      class="form-space form-control col-md-3">
                                  <option value="1">Senior</option>
                                  <option value="2">Junior</option>
                              </select>--}}
                            <label for="degree">Degree</label>
                            <select id="degree" name="degree"
                                    class="form-space form-control col-md-3">
                               <!-- <option @if($requisition->degree == 1) selected @endif value="1">Senior</option>
                                <option @if($requisition->degree == 2) selected @endif value="2">Junior</option>-->
                                 <option value="1"{{($requisition->degree==1 )?'selected':'' }}>Diploma</option>
                                <option value="2"{{($requisition->degree==2 )?'selected':'' }}>B.A/BSc.</option>
                                    <option value="3"{{($requisition->degree==3 )?'selected':'' }}> M.A/MSc.</option>
                                        <option value="4"{{($requisition->degree==4 )?'selected':'' }}>PHD</option>


                            </select>
                            <label for="experience_year">Experience year</label>
                            <select id="experience_year" name="experience_year"
                                    class="form-control form-space col-md-3">
                               <!-- @for($x = 1; $x <= 20; $x++)
                                    <option @if($requisition->experience_year == $x) selected
                                            @endif value="{{ $x }}">{{ $x }}</option>
                                @endfor-->

                             <option value="1" {{($requisition->experience_year==1 )?'selected':'' }}>Fresh Graduate</option>
                                <option value="2"{{($requisition->experience_year==2 )?'selected':'' }}>1</option>
                                 <option value="3"{{($requisition->experience_year==3 )?'selected':'' }}>1-2</option>
                                <option value="4"{{($requisition->experience_year==4 )?'selected':'' }}>2-4</option>
                                <option value="5"{{($requisition->experience_year==5 )?'selected':'' }}>4-6</option>
                                <option value="6"{{($requisition->experience_year==6 )?'selected':'' }}>6-10</option>
                                <option value="7"{{($requisition->experience_year==7 )?'selected':'' }}>More than 10</option>


                            </select>
                            <label for="mission">Mission</label>
                            <textarea type="text" id="mission" name="mission"
                                      class="form-control form-space">{{ $requisition->mission }}</textarea>
                            <label for="competency">Competency</label>
                            <textarea type="text" id="competency" name="competency"
                                      class="form-control form-space">{{ $requisition->competency }}</textarea>
                            <label for="outcome">Outcome</label>
                            <textarea type="text" id="outcome" name="outcome"
                                      class="form-control form-space">{{ $requisition->outcome }}</textarea>
                            <label for="about" class='optional'>about the team</label>
                            <textarea type="text" id="about" name="about"
                                      class="form-control form-space">{{ $requisition->about }}</textarea>
                        </div>
                    </div>

                    <h3>Interviewers</h3>
                    <div class="card form-space">
                        <div class="card-header">
                            <div id="interviewer_form_rows">
                                @if($requisition->interviewers)
                                    @foreach(json_decode($requisition->interviewers,true) as $k=>$interviewer)
                                <div class="form-row" data-form-num="{{$k}}">
                                    <div class="form-group col-md-6">
                                        <label for="interviewer_name" class="optional">name</label>
                                        <input type="text" name="interviewers[{{$k}}][]" value="{{$interviewer[0]}}" class="form-control" id="interviewer_name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="interviewer_skype_id" class="optional">skype id</label>
                                        <input type="text" class="form-control" value="{{$interviewer[1]}}" name="interviewers[{{$k}}][]"
                                               id="interviewer_skype_id" >
                                    </div>
                                </div>
                                @endforeach
                                    @endif
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

                    @can('accept',$requisition)
                        <h3>Receiver selection</h3>
                        <div class="card form-space">
                            <div class="card-header">
                                <label for="determiner_comment">Comment</label>
                                <textarea type="checkbox" id="determiner_comment" name="determiner_comment"
                                          class="form-space form-control"></textarea>
                            </div>
                        </div>
                    @endcan

                    <div class="center">
                        @can('accept',$requisition)
                            <button name="progress_result" value="1" type="submit" class="btn btn-success">Accept
                            </button>
                            <button name="progress_result" value="2" type="submit" class="btn btn-dark">Reject
                            </button>
                        @elsecannot('accept',$requisition)
                            <button type="submit" class="btn btn-success" onclick="return confirm('Updating will result in status reset on progresses\n' +
                             'Are you sure?')">Update
                            </button>
                        @endcan
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

@stop
