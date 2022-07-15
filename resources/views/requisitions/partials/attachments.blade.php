<div class="container-drop" id="container11">
 </div>
<link rel="stylesheet" href="{{asset('css/dropzone.css')}}">
 <script src="{{asset('js/dropzone.js')}}"></script>
<script>

    dropZone2({
        attachments:@json(isset($requisition)?$requisition->attachments:[] ) ,
        container: '#container11',
        inputName: "attachments[]",
    });

</script>
