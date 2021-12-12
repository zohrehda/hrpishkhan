<div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input" value="1" id="shift_checkbox"
           @if(old('shift_checkbox',($requisition)?$requisition->getOriginal('shift'):null)!=0) checked
           @endif name="shift_checkbox">
    <label class="custom-control-label" for="shift_checkbox">Shift</label>
</div>
<select class="custom-select form-space" name="shift_select" id="shift_select" disabled>
    <option selected disabled value="empty">Empty</option>
    @foreach($data['options'] as $key=>$value)
        <option
            @if(old('shift_select',($requisition)?$requisition->getOriginal('shift'):null)==$key) selected @endif
        value="{{$key}}">{{$value}}</option>
    @endforeach
</select>

<input type="hidden" name="shift" >
