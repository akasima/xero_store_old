<div class="form-group">
    <select name="{{$key['score']}}" class="xe-form-control">
        <option value="">선택</option>
        <option value="1" @if($data['score'] == '1') selected="selected" @endif >1</option>
        <option value="2" @if($data['score'] == '2') selected="selected" @endif >2</option>
        <option value="3" @if($data['score'] == '3') selected="selected" @endif >3</option>
        <option value="4" @if($data['score'] == '4') selected="selected" @endif >4</option>
        <option value="5" @if($data['score'] == '5') selected="selected" @endif >5</option>
    </select>
</div>