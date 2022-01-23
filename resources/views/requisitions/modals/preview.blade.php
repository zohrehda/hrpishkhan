   <div class="modal  fade" id="preview-{{$requisition->id}}" tabindex="-1"
                             aria-labelledby="preview"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable  modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">preview</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @include('requisitions.partials.preview',['requisition_items'=>$requisition_items ,'requisition'=>$requisition])


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>