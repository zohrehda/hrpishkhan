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
                                <div>
                                    <b> Requester:</b>
                                    <div class="card-body bg-light">

                                        <div>
                                            @foreach($requisition->owner->details() as $k=>$v)
                                                <div class="text-dark">
                                                    {{$k}} : {{$v}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <br>
                                </div>


                                <div>

                                    <div class="mb-2 text-right">
                                        @can('edit', $requisition)
                                            <a href="{{ route('requisitions.edit', $requisition->id) }}"
                                               class="btn btn-sm btn-dark border-light">Details</a>
                                        @endcan
                                        @can('accept', $requisition)
                                            <form
                                                action="{{ Route('requisitions.determine', $requisition->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <div class="inline">
                                                    <button
                                                        name="progress_result" value="2"
                                                        class="btn btn-sm btn-warning border-light">Reject
                                                    </button>
                                                </div>
                                                <button
                                                    name="progress_result" value="1"
                                                    class="btn btn-sm btn-success border-light">Accept
                                                </button>
                                            </form>
                                        @endcan
                                        @can('destroy', $requisition)
                                            <a href="{{ route('requisitions.destroy', $requisition->id) }}"
                                               class="btn btn-sm btn-danger border-light"
                                               onclick="return confirm('Are you sure?')">delete</a>
                                        @endcan
                                        @can('view', $requisition)
                                            <button data-toggle="modal"
                                                    data-target="#preview-{{$requisition->id}}"
                                                    class="btn btn-sm btn-light border-light">view
                                            </button>
                                        @endcan
                                        @can('close', $requisition)
                                            <a href="{{ route('requisitions.close', $requisition->id) }}"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure to close the requisition?')">close</a>
                                        @endcan
                                    </div>


                                    @if($requisition->assignments->count())
                                        <div class="alert alert-warning  ">
                                            {!! $requisition->prettyAssignments() !!}
                                        </div>
                                    @endif

                                    @if(($card_class!='bg-success') ||
                               ($card_class=='bg-success' && $requisition->assignments->count()==0)   )
                                        @can('assign_assign', $requisition)
                                            <div class="text-left">
                                                <h5><b>Assignment</b></h5>
                                                <form
                                                    action="{{ Route('requisitions.determine', $requisition->id) }}"
                                                    method="POST" class="">
                                                    @csrf

                                                    <div class="mr-1">
                                                        <div
                                                            class="custom-control custom-radio custom-control-inline custom-switch">
                                                            <input type="radio" id="customRadioInline1"
                                                                   name="assign_type"
                                                                   @if($requisition->assignment_type()=='assign') checked
                                                                   @endif
                                                                   value="assign" class="custom-control-input">
                                                            <label class="custom-control-label"
                                                                   for="customRadioInline1">assign
                                                                to assign</label>
                                                        </div>
                                                        <div
                                                            class="custom-control custom-radio custom-control-inline custom-switch">
                                                            <input type="radio" required id="customRadioInline2"
                                                                   name="assign_type" value="do"
                                                                   @if($requisition->assignment_type()=='do') checked
                                                                   @endif
                                                                   class="custom-control-input">
                                                            <label class="custom-control-label"
                                                                   for="customRadioInline2">assign
                                                                to do</label>
                                                        </div>
                                                    </div>

                                                    <br>
                                                    <div class="select-user"
                                                         data-selected-id="{{$requisition->assigned_to_user()->id??null}}"
                                                         data-selected-email="{{$requisition->assigned_to_user()->email??null}}"
                                                    >
                                                    </div>
                                                    <br>
                                                    <div class=" text-cene">
                                                        <button
                                                            name="progress_result" value="3"
                                                            class="btn btn-sm btn-primary">assign
                                                        </button>
                                                    </div>

                                                </form>

                                            </div>

                                        @endcan

                                        @can('assign_do', $requisition)
                                            <div class="text-left">
                                                <h5><b>Assignment</b></h5>
                                                <form
                                                    action="{{ Route('requisitions.determine', $requisition->id) }}"
                                                    method="POST" class="">
                                                    @csrf
                                                    <input type="hidden" name="assign_type" value="do">
                                                    <div class="select-user"

                                                         data-selected-id="{{$requisition->assigned_to_user()->id??null}}"
                                                         data-selected-email="{{$requisition->assigned_to_user()->email??null}}"
                                                    >

                                                    </div>
                                                    <br>
                                                    <div class="text-center">
                                                        <button
                                                            name="progress_result" value="3"
                                                            class="btn btn-sm btn-primary">assign
                                                        </button>
                                                    </div>

                                                </form>

                                            </div>

                                        @endcan
                                    @endif
                                </div>

                                <div class="card-footer text-center text-white">
                                    Last updated: {{ $requisition->updated_at }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="preview-{{$requisition->id}}" tabindex="-1"
                             aria-labelledby="preview"
                             aria-hidden="true">
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
                                                        <div
                                                            class="col-6">{{$requisition->getOriginal('updated_at')}}</div>
                                                    </div>
                                                </div>

                                                @foreach($requisition_items as $name=>$item)
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-6 text-muted"> {{$item['label']}}</div>
                                                            <div
                                                                class="col-6">{{$requisition->$name }}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

