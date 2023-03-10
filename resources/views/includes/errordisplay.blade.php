@if($errors->any())
<div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert" ariel-label="Close">
        <span aria-hidden="true">*</span>
    </button>
    @foreach ($errors->all() as $error)
        {{$error}}
    @endforeach
</div>
@endif