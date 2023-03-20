@extends('layouts.app')
@section('content')
  <div class="d-grid gap-2 col-6 mx-auto">
      <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('admin/add_user') }}'">Přidat uživatele</button>
      <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('admin/show_users') }}'">Upravit uživatele</button>
      <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('admin/check_kurz') }}'">Potvrdit kurz</button>
      <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('admin/delete_kurz') }}'">Odstranit kurz</button>
      <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('admin/add_mistnost') }}'">Přidat místnost</button>
      <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ url('admin/show_mistnosts') }}'">Upravit místnost</button>
  </div>
@endsection
