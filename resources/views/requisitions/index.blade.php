<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row">
                @foreach($requisitions as $requisition)
              
                    <div class="col-md-4 form-space">
                        <div class="card {{ $card_class }} text-white">
                            <div class="card-header text-center">
                                {{ $requisition->en_title }}
                            </div>
                            <div class="card-body">
                                @foreach($requisition->progresses as $progress)
                                    <p class="card-text">
                                        {{ $progress->role }} - {{ $progress->user->name }} - {{ $progress->status }}
                                        @if($progress->determiner_comment)
                                            -
                                            <a class="btn btn-sm btn-dark" data-toggle="collapse"
                                               href="#comment{{$progress->id}}"
                                               role="button" aria-expanded="false" aria-controls="comment">
                                                view comment
                                            </a>
                                    </p>
                                    <div class="collapse" id="comment{{$progress->id}}">
                                        <div class="card-body">
                                            <p>
                                                {{ $progress->determiner_comment }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                                <div class="text-right">
                                    <div class="inline">
                                        @can('edit', $requisition)
                                            <a href="{{ route('requisitions.edit', $requisition->id) }}"
                                               class="btn btn-sm btn-dark">Details</a>
                                        @endcan
                                    </div>
                                    @can('accept', $requisition)
                                        <form action="{{ Route('requisitions.determine', $requisition->id) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            <div class="inline">
                                                <button
                                                    name="progress_result" value="2"
                                                    class="btn btn-sm btn-warning">Reject
                                                </button>
                                            </div>
                                            <button
                                                name="progress_result" value="1"
                                                class="btn btn-sm btn-success">Accept
                                            </button>
                                        </form>
                                    @endcan
                                    @can('destroy', $requisition)
                                        <a href="{{ route('requisitions.destroy', $requisition->id) }}"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure?')">delete</a>
                                    @endcan
                                    @can('accepted', $requisition)
                                        <button data-toggle="modal" data-target="#preview-{{$requisition->id}}"
                                                class="btn btn-sm btn-light">view
                                        </button>
                                    @endcan


                                </div>
                            </div>
                            <div class="card-footer text-center text-white">
                                Last updated: {{ $requisition->updated_at }}
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                  
                    <div class="modal fade" id="preview-{{$requisition->id}}" tabindex="-1" aria-labelledby="preview" aria-hidden="true">
                        <div class="modal-dialog  modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">preview</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row text-info">
                                                    <div class="col-md-3 col-6 "> request from</div>
                                                    <div class="col-md-3 col-6 ">{{$requisition->owner->name}}</div>
                                                </div>
                                            </div>
                                           
                                           
                                            <div class="col-md-6">
                                                <div class="row text-warning">
                                                    <div class="col-6 "> request date</div>
                                                    <div class="col-6">{{$requisition->created_at}}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="row text-warning">
                                                    <div class="col-6 "> accept date</div>
                                                    <div class="col-6">{{$requisition->getOriginal('updated_at')}}</div>
                                                </div>
                                            </div>
                                            
                                           
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> department</div>
                                                    <div class="col-6">{{$departments[$requisition->department]}}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> level</div>
                                                    <div class="col-6">{{$levels_array[$requisition->level]}}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> en_title</div>
                                                    <div class="col-6">{{$requisition->en_title}}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> fa_title</div>
                                                    <div class="col-6">{{$requisition->fa_title}}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> position_count</div>
                                                    <div class="col-6">{{$requisition->position_count}}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> report_to</div>
                                                    <div class="col-6">{{$requisition->report_to}}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> location</div>
                                                    <div class="col-6">{{$requisition->location}}</div>
                                                </div>
                                            </div>
                                            
                                            @if($requisition->shift)
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> shift</div>
                                                    <div class="col-6">{{$requisition->get_shift()}}</div>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> time </div>
                                                    <div class="col-6">{{($requisition->is_full_time)?'full time':'part time'  }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> hiring type</div>
                                                    <div class="col-6">{{($requisition->is_new)?'new hiring':'replacement' }}</div>
                                                </div>
                                            </div>
                                            @if(!$requisition->is_new)
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-6 text-muted"> replace with </div>
                                                        <div class="col-6">{{$requisition->replacement }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> field of study</div>
                                                    <div class="col-6">{{$requisition->field_of_study }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 ">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> degree </div>
                                                    <div class="col-6">{{$requisition->get_degree()}}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 ">
                                                <div class="row">
                                                    <div class="col-6 text-muted"> experience year</div>
                                                    <div class="col-6">{{$requisition->get_experience_year() }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">

                                                <div class="row">
                                                    <div class="col-3 text-muted">  	mission </div>
                                                    <div class="col-9">{{$requisition-> 	mission  }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-3 text-muted">  	outcome  </div>
                                                    <div class="col-9">{{$requisition-> 	outcome   }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-3 text-muted">  	about the team  </div>
                                                    <div class="col-9">{{$requisition->about   }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-3 text-muted">  	competency   </div>
                                                    <div class="col-9">{{$requisition->competency    }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>
    </div>
</div>

