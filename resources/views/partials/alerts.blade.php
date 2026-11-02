@if(session('success'))
    <div class="alert alert-success"><span>✓</span><div>{{ session('success') }}</div><button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><span>!</span><div>{{ session('error') }}</div><button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button></div>
@endif
@if($errors->any())
    <div class="alert alert-danger"><span>!</span><div><strong>Periksa kembali data Anda.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div><button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button></div>
@endif
