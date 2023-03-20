@extends('layouts.app')
@section('content')
  <div class="d-grid gap-2 col-6 mx-auto">
      <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('ucitel/garant') }}'">Garantované kurzy</button>
      <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('ucitel/lektor') }}'">Lektorované termíny</button>
      <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('ucitel/lektor_rozvrh') }}'">Lektorský rozvrh</button>
      <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('ucitel/add_kurz') }}'">Založit nový kurz</button>
  </div>
@endsection
