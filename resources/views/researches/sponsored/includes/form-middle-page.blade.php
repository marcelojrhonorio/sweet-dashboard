<form class="form-horizontal" method="post" enctype="multipart/form-data" data-form-middlepages>                            
@if('edit' == $action)
<div class="sr-only form-edit-middle-page" data-form-middle>
@else
<div class="sr-only form-create-middle-page" data-form-middle>
@endif
  <div class="form-group">
      <label class="control-label col-md-1" for="title">
        Título:
      </label>
      <div class="col-md-11">       
        <input
          id="title"
          class="form-control"
          name="title"
          type="text"
          placeholder="Título"
          value=""
          required
          data-title-middlepage
        >  
      </div>
    </div>
    @php    
    $txt = "Negrito - <b> Seu texto </b> 
Itálico - <i>  Seu texto  </i>
Sublinhado - <u>  Seu texto  </u>
Tachado - <del>  Seu texto  </del>";
  @endphp
    <div class="form-group">
      <label class="control-label col-md-1" for="description">
        Descrição:
      </label>
      <span data-toggle="tooltip" data-placement="top" data-html="true" title="{{ $txt }}"><i class="fa fa-question-circle"></i></span>
      <div class="col-md-11">
      <input
          id="description"
          class="form-control"
          name="description"
          type="text"
          placeholder="Descrição"
          value=""
          required
          data-description-middlepage
        >
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-1" for="icon">
        Imagem:
      </label>
      @if($action == 'edit')
        @if(!$middle_pages)
          <div class="col-md-10" data-wrap-upload>
        @else
          <div class="col-md-10 sr-only" data-wrap-upload>
        @endif
      @else
        <div class="col-md-10" data-wrap-upload>
      @endif
        <div class="fileinput fileinput-new" data-provides="fileinput" data-wrap-file>
            <span class="btn btn-default btn-file">
              <span class="fileinput-new">
                Selecione uma imagem
              </span>
              <span class="fileinput-exists">
                Alterar imagem
              </span>
              <input
                id="icon"
                name="icon"
                type="file"
                accept="image/*"
                value=""
                data-input-icon
              >
            </span>
            <span class="fileinput-filename"></span>
        </div>
        <div class="progress progress-bar-default hidden" data-upload-progress>
          <div
            class="progress-bar"
            style="width: 0%"
            role="progressbar"
            aria-valuemax="100"
            aria-valuemin="0"
            aria-valuenow="0"
          >
          </div>
        </div>
      </div>
      @if(isset($middle_pages) && $middle_pages)
      <div class="col-md-3">
        <img class="image-preview" src="{{ env('APP_URL') }}/storage/{{ $middle_pages['middle_page']->image_path }}" alt="">
      </div>
      <input name="img-edit" type="hidden" value="{{ $middle_pages['middle_page']->image_path }}" data-img-edit>
      <div class="col-md-9">
        <button class="btn btn-danger btn-destroy-img" type="button" data-path="{{ $middle_pages['middle_page']->image_path }}" data-destroy-img>
          Excluir
        </button>
      </div>
      @else
        <div class="col-md-10 img-preview" data-wrap-preview></div>
      @endif    
    </div>
    <div class="form-group">
    <label class="control-label col-md-1" for="redirect_link">
      Redirect Link:
    </label>
    <div class="col-md-5">
    <input
        id="redirect_link"
        class="form-control"
        name="redirect_link"
        type="text"
        placeholder=""
        value=""
        required
        data-input-redirectlink
      >
    </div>
  </div>  
  </div>

  <div class="sr-only" data-list-middle-page>
    <div id="list-middle-pages" style="overflow:auto;height:267px;margin-bottom:3%;"> 
    </div>
 </div>

 <div class="sr-only" data-question-and-options>
  <div class="form-group">
    <label for="nome" class="control-label col-md-1" for="title">Questão:</label>
    <div class="col-md-11">
      <select title="Selecione a questão..." name="select_questions" id="select_questions" class="selectpicker form-control" data-live-search="true" data-size="5" data-input-questionsid>
      @if(isset($researche_questions) && $researche_questions)
        @foreach($researche_questions as $researche_question)
          <option 
            @if(isset($researche_question->questions_id) && (int) $researche_question->questions_id == $middle_pages['questions_id'])
              selected="selected"
            @endif
            value="{{ $researche_question->questions_id }}"
            >
            {{ $researche_question->question->description }}
          </option>
        @endforeach
      @endif
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="nome" class="control-label col-md-1" for="title">Alternativa:</label>
    <div class="col-md-11">
      <select title="Selecione a alternativa..." name="select_options" id="select_options" class="selectpicker form-control" data-live-search="true" data-size="5" data-input-select-options>
      @if(isset($question_options) && $question_options)
        @foreach($question_options as $question_option)
          @foreach($question_option as $question)
            @if(isset($question->questions_id) && (int) $question->questions_id == $middle_pages['questions_id'])
              <option 
                @if(isset($question->options_id) && (int) $question->options_id == $middle_pages['options_id'])
                  selected="selected"
                @endif
                value="{{ $question->options_id }}"
                >
                {{ $question->option->description }}
              </option>
            @endif
          @endforeach
        @endforeach
      @endif
      </select>
    </div>
  </div> 
  </div> 

  <div class="sr-only">
    {{ csrf_field() }}    
    @if($action == 'create')
      <input name="action" type="hidden" value="create" data-input-action>
      <input name="options_id" type="hidden" value="" data-input-options_id>
      <input name="questions_id" type="hidden" value="" data-input-questions_id>
    @else 
      <input name="action" type="hidden" value="update" data-input-action>      
      @if(isset($middle_pages) && $middle_pages)
        <input name="middle_page" type="hidden" value="{{ $middle_pages['middle_page']->id }}" data-input-middle_page>
        <input name="options_id" type="hidden" value="{{ $middle_pages['option']->id }}" data-input-options_id>
        <input name="questions_id" type="hidden" value="{{ $middle_pages['question']->id }}" data-input-questions_id>
      @else
        <input name="middle_page" type="hidden" value="" data-input-middle_page>
        <input name="options_id" type="hidden" value="" data-input-options_id>
        <input name="questions_id" type="hidden" value="" data-input-questions_id>
      @endif
    @endif
    <input name="id" type="hidden" value="" data-input-id>
    <input name="path" type="hidden" value="some" data-input-path>
    
    @if(isset($researche_questions) && $researche_questions)
      <input name="researches_id" type="hidden" value="{{ $researche_questions[0]->researches_id }}" data-input-researches_id>
    @endif
  </div> 

  <button class="btn btn-primary save-middlepages sr-only" type="submit"  btn-save-middlepages>
    Salvar 
  </button>

  @if($action == 'create')
  <button class="btn btn-primary btn-middle sr-only" type="submit" data-btn-middlepages>
      Salvar e Finalizar
  @else 
  <button class="btn btn-primary btn-middle-ed sr-only" type="submit" data-btn-middlepages>
      Atualizar 
  @endif 
  </button> 
 </form>

 <button class="btn btn-default sr-only edit-middle-cancel" title="Voltar para lista de MiddlePages" cancel-edit-middlepages>
    Cancelar
  </button>

 <input type="hidden" value="" edit-middlepageid>
 <input type="hidden" value="" researches-middle-pages-id>

@section('script')
  <script src="{{ asset('assets/js/app/sponsored_researches/researches.js') }}"></script>
  <script src="{{ asset('assets/js/app/sponsored_researches/questions.js') }}"></script>
  <script src="{{ asset('assets/js/app/sponsored_researches/middlepages.js') }}"></script>
@endsection