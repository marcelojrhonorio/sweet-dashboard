<div class="container" box-all-questions>
    
    <input type="hidden" value="" verify-action-type>
    <input type="hidden" value="" new-ordering-edit>
    
      <div box-question-options>        
        <a class="btn btn-primary new-question" btn-new-question>Criar Nova Questão</a>
        <div id="refresh">
          <div id="all_question" data-all-question>
            @if(isset($all_questions))
              @for($index=0;$index < count($all_questions);$index++)
                @php
                  $options = '';
                  $questions = $all_questions[$index];

                  $letters = [
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 
                    'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T','U', 'V', 
                    'W', 'X',  'Y', 'Z'
                  ];

                  $letter = '';
                @endphp
                
                @for($i=0;$i < count($questions);$i++)
                  @for($a=0;$a < count($letters);$a++)
                    @if($a == $i)
                      @php
                        $letter = $letters[$a];
                      @endphp
                    @endif
                  @endfor

                  @php
                    $q = get_object_vars($questions[$i]);
                    $options = $options . '<br />' . $letter . ') ' .  mb_substr($q['option']->description, 0, 40, "UTF-8");
                  @endphp 
                @endfor

                @for($i=0;$i < count($questions);$i++)
                  @php
                    $q = get_object_vars($questions[$i]);
                  @endphp    

                  @if(($i != 0) && ($questions[$i]->questions_id == $questions[$i-1]->questions_id)) 
                    @php 
                      continue; 
                    @endphp
                  @endif

                  @if($index == 0)                
                    <div class="sr-only div-question" href="#ops" data-toggle="collapse" data-questionid="0">
                      <table>
                        <tr style="width: 600px;">
                          <td style="width: 550px;">
                            <label class="label-questions">
                              xxxxxxx
                            </label>
                            <div id="ops" class="collapse" style="word-break: break-all;">
                              <label class="label-question-collapse">
                                xxxxx
                              </label>
                            </div>
                          </td>
                          <td style="width: 150px;">
                            <i class="close fas fa-caret-down caret-btn icon-close" coll-question=" "></i>
                            <i class="close fas fa-plus-square icon-close" title="Informe 0(zero) para deletar." btn-add-question=" "></i>
                          <label class="sr-only label-order">
                            Ordem
                          </label>
                            <input class="sr-only" size="2">
                          </td>
                        </tr>
                      </table>
                    </div>

                      @php 
                        $style = ''; 
                        $ordering = 0; 
                        $classOrdem = 'sr-only label-order'; 
                        $stringOrdem = 'Ordem';
                      @endphp
                  
                      @if(isset($researche_questions))                     
                        @foreach($researche_questions as $rq)              
                          @if($rq->questions_id === $questions[$i]->questions_id)
                            @php 
                              $style = 'background-color: rgb(59, 166, 137); color: rgb(255, 255, 255);'; 
                              $ordering = $rq->ordering; 
                              $classOrdem = 'label-order'; 
                              $stringOrdem = $rq->ordering . 'ª Questão';
                            @endphp
                          @endif
                        @endforeach
                      @endif

                      <div class="div-question" href="#ops" style="{{ $style }}" data-toggle="collapse" ordering-questions="{{ $ordering }}" data-questionid="{{$questions[$i]->questions_id}}">
                      <table>
                        <tr style="width: 600px;">
                          <td style="width: 550px;">
                            <label class="label-questions">
                              {{ $q['question']->description }}
                            </label>
                            <div id="ops" class="collapse" style="word-break: break-all;">
                              <label class="label-question-collapse">
                                {!! $options !!}
                              </label>
                            </div>
                          </td>
                          <td style="width: 150px;">
                            <i class="close fas fa-caret-down caret-btn icon-close" coll-question=" "></i>
                            
                            @php $icon = 'close fas fa-plus-square icon-close'; @endphp

                            @if(isset($researche_questions))
                              @foreach($researche_questions as $rq)
                                @if($rq->questions_id === $questions[$i]->questions_id)
                                  @php $icon = 'close fas fa-edit icon-close'; @endphp
                                @endif
                              @endforeach
                            @endif  
                            <i class="{{ $icon }}" title="Informe 0(zero) para deletar." btn-add-question=" "></i>                       
                          <label class="{{ $classOrdem }}">
                            {{ $stringOrdem }}
                          </label>
                            <input class="sr-only" size="2">
                          </td>
                        </tr>
                      </table>                      
                    </div>           
                    @else  

                      @php 
                        $style = ''; 
                        $ordering = 0; 
                        $classOrdem = 'sr-only label-order'; 
                        $stringOrdem = 'Ordem';
                      @endphp
                  
                      @if(isset($researche_questions))
                        @foreach($researche_questions as $rq)
                          @if($rq->questions_id === $questions[$i]->questions_id)
                            @php 
                              $style = 'background-color: rgb(59, 166, 137); color: rgb(255, 255, 255);'; 
                              $ordering = $rq->ordering; 
                              $classOrdem = 'label-order';
                              $stringOrdem = $rq->ordering . 'ª Questão';
                            @endphp                           
                          @endif
                        @endforeach
                      @endif

                      <div class="div-question" href="#ops" style="{{ $style }}" data-toggle="collapse" ordering-questions="{{ $ordering }}" data-questionid="{{$questions[$i]->questions_id}}">
                      <table>
                        <tr style="width: 600px;">
                          <td style="width: 550px;">
                            <label class="label-questions">
                              {{ $q['question']->description }}
                            </label>
                            <div id="ops" class="collapse" style="word-break: break-all;">
                              <label class="label-question-collapse">
                                {!! $options !!}
                              </label>
                            </div>
                          </td>
                          <td style="width: 150px;">
                            <i class="close fas fa-caret-down caret-btn icon-close" coll-question=" "></i>
                            
                            @php $icon = 'close fas fa-plus-square icon-close'; @endphp

                            @if(isset($researche_questions))
                              @foreach($researche_questions as $rq)
                                @if($rq->questions_id === $questions[$i]->questions_id)
                                  @php $icon = 'close fas fa-edit icon-close'; @endphp
                                @endif
                              @endforeach
                            @endif                            
                            <i class="{{ $icon }}" title="Informe 0(zero) para deletar." btn-add-question=" "></i>     

                            <label class="{{ $classOrdem }}">
                              {{ $stringOrdem }}
                            </label>
                              <input class="sr-only" size="2">
                            </td>
                          </tr>
                        </table> 
                      </div>
                    @endif
                  
                @endfor
              @endfor
            @endif
          </div>
        </div>
      </div>
    </div> 
  <input name="questions_id" type="hidden" value="" data-questions_id>
  <input name="order" type="hidden" value="" data-order>
  <input name="researches_id" type="hidden" value="" data-researches_id>


@include('researches.sponsored.includes.modal-question')