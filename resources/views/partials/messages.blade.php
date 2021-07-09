@if ($message = Session::get('message'))
<div class="alert alert-danger alert-block">
    <strong>{{ $message }}</strong>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-block">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif