<div class="btn-group" style="padding:2%;width:100%;" role="group" aria-label="">
  <button type="button" class="btn btn-primary custom-btn-middle" create-middle-page>Criar Nova Página Intermediária</button>
  <button type="button" class="btn btn-primary custom-btn-middle" load-middle-page>Utilizar Página Intermediária Existente</button>
</div>

  @include('researches.sponsored.includes.form-middle-page')

  <input type="hidden" value="0" data-type-submit-middle>
  <input type="hidden" value="0" data-save-mp>

@section('script') 
  <script src="{{ asset('assets/js/app/sponsored_researches/researches.js') }}"></script>
  <script src="{{ asset('assets/js/app/sponsored_researches/questions.js') }}"></script>
  <script src="{{ asset('assets/js/app/sponsored_researches/middlepages.js') }}"></script>
@endsection           