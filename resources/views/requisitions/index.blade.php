<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row">
                @foreach($requisitions as $requisition)
                    <div class="col-md-4 form-space">

                        <div class="card {{ $card_class }}  text-white overflow-hidden">
                            <div class="card-header text-center "
                                 data-toggle="collapse" type="button"
                                 data-target="#card-{{$requisition->id}}-{{$card_class}}">
                                {{ $requisition->en_title }}
                                <br>
                                {{$requisition->owner->details()['name']}}
                                <br>
                                <div class="ribbon">
                                    {{$requisition->label}}
                                </div>

                            </div>

                            <div class="card-body collapse multi-collapse"
                                 id="card-{{$requisition->id}}-{{$card_class}}">
                                @foreach($requisition->approval_progresses as $progress)
                                    <p class="card-text">

                                        {{ $progress->user->role=='hr_admin'?'Hr Admin - ':'' }}  {{ $progress->user->name }}
                                        - {{ $progress->status }}
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
                                                    {{ucfirst($k)}} : {{$v}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <br>
                                </div>

                                <div>
                                    <form
                                        action="{{ Route('requisitions.determine', $requisition->id) }}"
                                        method="POST" class="inline w-100">
                                        <div class="  text-right card-btn-containers  ">
                                            @csrf
                                            <div class="pb-1">
                                                @can('edit', $requisition)
                                                    <a href="{{ route('requisitions.edit', $requisition->id) }}"
                                                       class="btn btn-sm btn-purple ">Details</a>
                                                @endcan</div>

                                            @can('accept', $requisition)
                                                <div class="pb-1">
                                                    <button
                                                        name="progress_result"
                                                        value="{{RequisitionStatus::REJECTED_STATUS}}"
                                                        class="btn btn-sm btn-yellow">Reject
                                                    </button>
                                                </div>

                                                <div class="pb-1">
                                                    <button
                                                        name="progress_result"
                                                        value="{{RequisitionStatus::ACCEPTED_STATUS}}"
                                                        class="btn btn-sm btn-green">Accept
                                                    </button>
                                                </div>

                                            @endcan
                                            @can('close', $requisition)
                                                <div class="pb-1">
                                                    <button
                                                        name="progress_result"
                                                        value="{{RequisitionStatus::CLOSED_STATUS}}"
                                                        onclick="return confirm('Are you sure to close the requisition?')"
                                                        class="btn btn-sm btn-black">Close
                                                    </button>
                                                </div>
                                            @endcan

                                            @can('add_viewer',$requisition)
                                            <div class="pb-1">
                                            <button type="button"
                data-toggle="modal" data-target="#AddViewer-{{$requisition->id}}"
                id='import-requisition' class="btn  btn-sm btn-pink">Add Viewer
        </button>
                                            </div>
      
    @endcan

                                            @can('destroy', $requisition)
                                                <div class="pb-1">
                                                    <a href="{{ route('requisitions.destroy', $requisition->id) }}"
                                                       class="btn btn-sm btn-red"
                                                       onclick="return confirm('Are you sure?')">delete</a>
                                                </div>

                                            @endcan

                                            @can('hold', $requisition??null)
                                            <div class="pb-1">
                                            <button
                                                    name="progress_result"
                                                    value="{{RequisitionStatus::HOLDING_STATUS}}"
                                                    class="btn btn-sm btn-orange">Hold
                                                </button>
                                            </div>
                                                
                                            @endcan

                                            @can('open', $requisition??null)
                                            <div class="pb-1">
                                                <button
                                                    name="progress_result"
                                                    value="{{RequisitionStatus::OPEN_STATUS}}"
                                                    class="btn btn-sm btn-coral">Open
                                                </button>
                                            </div>
                                            @endcan

                                            @can('view', $requisition)
                                                <div class="pb-1">
                                                    <button data-toggle="modal" type="button"
                                                            data-target="#preview-{{$requisition->id}}"
                                                            class="btn btn-sm btn-grey  ">view
                                                    </button>
                                                </div>
                                            @endcan
                                        </div>
                                    </form>
                                    @include('requisitions.partials.assignments',['requisition'=>$requisition])
                                </div>

                                <div class="card-footer text-center text-white">
                                    Last updated: {{ $requisition->updated_at }}
                                </div>
                            </div>
                        </div>

                      
                        @include('requisitions.modals.preview',['requisition'=>$requisition])
                        @include('requisitions.modals.add_viewer',['requisition'=>$requisition])
  
 
                    </div>

                @endforeach
            </div>
        </div>
    </div>
</div>

