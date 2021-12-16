<div class="container preview">
    <div class="row">
        <div class="col-md-12 p-1">
            <div class="row text-info">
                <div class="col-md-3 font-weight-bold col-6 "> request from</div>
                <div class="col-md-3 col-6 ">{{$requisition->owner->name}}</div>
            </div>
        </div>

        <div class="col-md-6 p-1">
            <div class="row text-warning">
                <div class="col-6 font-weight-bold "> request date</div>
                <div class="col-6">{{$requisition->created_at}}</div>
            </div>
        </div>

        @foreach(RequisitionItems::shortValueItems() as $name=>$item)
            @if($requisition->$name)
                <div class="col-md-6 p-1">
                    <div class="row">
                        <div class="col-6 font-weight-bold text-muted"> {{$item['label']}}</div>
                        <div class="col-6">{{$requisition->$name }}</div>
                    </div>
                </div>
            @endif
        @endforeach
        <br>
        @foreach(RequisitionItems::longValueItems() as $name=>$item)
            @if($requisition->$name)
                <div class="col-md-12 p-1">
                    <div class="row">
                        <div class="col-12 font-weight-bold text-muted"> {{$item['label']}}</div>
                        <div class="col-12">{{$requisition->$name }}</div>
                    </div>
                </div>
            @endif
        @endforeach

        <div class="col-md-12 p-1">
            <div class="row">
                <div class="col-12  font-weight-bold text-muted"> Competency</div>
                <div class="col-12">
                    <ol>
                        @foreach($requisition->competency as $competency )
                            <li>
                                {{$competency['text']}} ({{ ($competency['status'])?'Essential':'Desirable'    }})
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
        @if(count($requisition->interviewers))
            <div class="col-md-12 p-1">
                <div class="row">
                    <div class="col-12  font-weight-bold text-muted"> Interviewers</div>
                    <div class="col-12">
                        <ol>
                            @foreach($requisition->interviewers as $interviewer )
                                <li>
                                    {{$interviewer[0]}} - {{$interviewer[1]}}
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
