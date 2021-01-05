(function($) {
  const Researches = {
      inputs: {},

      DataTable: null,

      start: function() {
          this.$table                   = $('[data-table-researches-sponsored]');
          this.$boxQuestion             = $('[box-all-questions]');
          
          this.$btnConfirm              = $('[data-btn-research]');
          this.$btnFinishQuestion       = $('[btn-finalizar-question]');
          this.$boxQuestionsResearches  = $('[box-research-questions]');  
          this.$boxQuestionsOptions     = $('[box-question-options]');  
          this.$btnEditResearch         = $('[btn-research-edit]');  
          this.$btnCancelEditResearch   = $('[cancel-research-edit]');

          this.$refreshTableQuestions   = $('[btn-refresh-table-question]');
        
          this.$allQuestion             = $('[data-all-question]');   
          this.$researchQuestion        = $('[data-research-questions]');   

          this.$id                      = $('[data-input-id]');
          this.$action                  = $('[data-input-action]');
          this.$title                   = $('[data-input-title]');
          this.$subtitle                = $('[data-input-subtitle]');
          this.$description             = $('[data-input-description]');
          this.$points                  = $('[data-input-points]');
          this.$finalurl                = $('[data-input-finalurl]');
         
          this.bind();
          this.dataTable();          
      },

      bind: function() {
          this.$btnConfirm.on('click', $.proxy(this.onBtnSponsoredClick, this));
          this.$btnEditResearch.on('click', $.proxy(this.onBtnEditResearchClick, this));
          this.$btnCancelEditResearch.on('click', $.proxy(this.onBtnCancelEditResearchClick, this));
          this.$btnFinishQuestion.on('click', $.proxy(this.onBtnFinishQuestionClick, this));          
          this.$table.on('click', '[data-btn-edit]', $.proxy(this.onBtnEditClick, this));
          this.$table.on('click', '[data-btn-destroy]', $.proxy(this.onBtnDestroyClick, this));
          //this.$allQuestion.on('click', '[data-questionid]', $.proxy(this.onAllQuestionClick, this));
          this.$allQuestion.on('click', '[coll-question]', $.proxy(this.onAllQuestionClick, this));
          this.$researchQuestion.on('click', '[data-questionid]', $.proxy(this.onResearchQuestionClick, this));
          this.$boxQuestionsResearches.on('click', '[btn-close-question]', $.proxy(this.onCloseQuestionClick, this));          
          this.$boxQuestionsOptions.on('click', '[btn-edit-save]', $.proxy(this.onSaveQuestionClick, this));
          this.$allQuestion.on('click', '[btn-add-question]', $.proxy(this.onAddQuestionClick, this));   
      },

      dataTable: function() {
          this.DataTable = this.$table.DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            searching: true,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
              {
                extend: 'excel',
                title: 'empresas',
                text: '<span class="fa fa-file-excel-o"></span> Excel ',
              },
              {
                extend: 'pdf',
                title: 'empresas',
                text: '<span class="fa fa-file-pdf-o"></span> PDF ',
              },
              {
                extend: 'print',
                text: '<span class="fa fa-print"></span> Imprimir ',
                customize: function (win) {
                  const $body = $(win.document.body);
    
                  $body.addClass('white-bg').css('font-size', '10px');
    
                  $body.find('table').addClass('compact').css('font-size', 'inherit');
                },
              },
            ],
            language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json',
            },
            ajax: {
              url: '/researches/sponsored/search',
            },
            columns: [
              {
                data : 'id',
              },
              {
                data : 'title',
              },
              {
                data : 'subtitle',
              },
              {
                data : 'description',
              },
              {
                data : 'points',
              },
              {
                data : 'final_url',
              },
              {
                data : 'enabled',
                width: '3%',
                render: function(data, type, row) {
                    return sweet.common.iconStatusApp(row.enabled);
                },                  
              },
              {
                data : null,
                width: '3%',
                render: function(data, type, full, meta) {
                  const btnEdit = `
                    <button
                      class="btn btn-xs btn-primary"
                      title="Editar"
                      type="button"
                      data-btn-edit
                      data-id="${data.id}"
                      data-title="${data.title}"
                      data-subtitle="${data.subtitle}"
                      data-description="${data.description}"
                      data-points="${data.points}"
                      data-final_url="${data.final_url}"
                      data-enabled="${data.enabled}"
                    >
                      <span class="sr-only">Editar</span>
                      <i class="fas fa-pen" aria-hidden="true"></i>
                    </button>
                  `;
                  const btnDestroy = `
                    <button
                      class="btn btn-xs btn-danger"
                      title="Excluir"
                      type="button"
                      data-btn-destroy
                      data-id="${data.id}"
                    >
                      <span class="sr-only">Excluir</span>
                      <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                  `;
    
                  const buttons = `${btnEdit} ${btnDestroy}`;
    
                  return buttons;
                },
              },
            ],
          });
        },   

      onFormSubmit: function (event) {
          event.preventDefault();

          if (
            '' === $('#enabled').val() ||
            '' === $.trim(this.$title.val()) ||
            '' === $.trim(this.$subtitle.val()) ||
            '' === $.trim(this.$description.val()) ||
            '' === $.trim(this.$points.val()) ||
            '' === $.trim(this.$finalurl.val()) 
          ) {
            sweet.common.message('error', 'Todos os campos são obrigatórios');
            return;
          }

          this.hasResearchWithUrl(this.$finalurl.val());    
         
        },

        hasResearchWithUrl: function(url, title, subtitle) {

          const token = $('meta[name="csrf-token"]').attr('content');

          const verify = $.ajax({
              method: 'POST',
              url: '/researches/sponsored/verify-url',
              contentType: 'application/json',
              data: JSON.stringify({
                _token: token,
                url : url, 
                research_id: $('[data-research-id]').val(),
                dataType: 'json',
              }),
          })
          
          verify.done($.proxy(this.verifySuccess, this));      
          verify.fail($.proxy(this.verifyFail, this));
        },

        verifySuccess: function(data) {

          if(data.success) { 

            var research = data.data;           
         
            sweet.common.message('error', '"' + research.title + '" está utilizando esta url.');
            return;           

          } else {

            const formAction = $.trim(this.$action.val());
    
            switch (formAction) {
              case 'create':
      
                this.onCreateSubmit();
      
                break;
      
              case 'update':
      
                this.onUpdateSubmit();
      
                break;
      
              default:
      
                console.log('Invalid form action');
            }
          }
        },

        verifyFail: function(error) {
          console.log('Failed to VERIFY url: ', error);
        },
        
        onCreateSubmit: function() {
          const token = $('meta[name="csrf-token"]').attr('content');

          const saving = $.ajax({
              method: 'POST',
              url: '/researches/sponsored/store',
              contentType: 'application/json',
              data: JSON.stringify({
                _token: token,
                title : this.$title.val(),
                subtitle: this.$subtitle.val(),
                description : this.$description.val(),
                points : this.$points.val(),
                final_url : this.$finalurl.val(), 
                enabled: $('#enabled').val(),
                dataType: 'json',
              }),
          })
          
          saving.done($.proxy(this.onCreateSuccess, this));      
          saving.fail($.proxy(this.onCreateFail, this));
        },

        onCreateSuccess: function(data) {
          if (data.success) {

            $('[data-input-title]').prop('disabled', true);
            $('[data-input-subtitle]').prop('disabled', true);
            $('[data-input-description]').prop('disabled', true);
            $('[data-input-points]').prop('disabled', true);
            $('[data-input-finalurl]').prop('disabled', true);
            $('#enabled').prop('disabled', true);
            $('[data-btn-research]').prop('disabled', true).text('Cadastrado');
            
            $('#data-box-research').css('display', 'none');              
            $('#box-question').removeClass('sr-only');

            this.DataTable.ajax.reload().desc;
            
            var research = data.result.data;
            $('[title-research').text('Pesquisa: ' + research.title);
            $('[data-input-research]').val(research.id);

            this.createTableQuestions();
                        
          }            
        },

        createTableQuestions: function() {

          const token = $('meta[name="csrf-token"]').attr('content');

            const searching = $.ajax({
                method: 'GET',
                url: '/researches/sponsored/question/getQuestionOptions',
                contentType: 'application/json',
                data: JSON.stringify({
                  _token: token,
                  dataType: 'json',
                }),
            })

            Swal.fire({
              title: 'Aguarde!',
              html: 'Carregando dados...',          
            })
  
            Swal.showLoading()
            
            searching.done($.proxy(this.onSearchingSuccess, this));        
            searching.fail($.proxy(this.onSearchingFail, this)); 

        },

        onSearchingSuccess: function(data) {

          Swal.close()

          if (data.success) 
          {
            var allQuestions = data.questions;
            var allOptions = data.options;

            var letters = [
              'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 
              'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T','U', 'V', 
              'W', 'X',  'Y', 'Z'
            ];
           
            for (let index = 0; index < allQuestions.length; index++) 
            {                
              if('' != allQuestions[index].question_option)
              {
                var options = ''; 

                var question_option = allQuestions[index].question_option;

                /**
                    TRATAMENTO DO CONTEÚDO DA DIV COLLAPSE
                    
                    Para cada 'questions' será percorrido o vetor de 'question_option'.
                    Para cada 'question_option' será percorrido o vetor de 'options',
                    identificar a 'option' correspondente e pegar sua 'description'.
                 */
                 
                for (let i = 0; i < question_option.length; i++) 
                {
                  for (let x = 0; x < allOptions.length; x++)
                  {
                    if(question_option[i].options_id == allOptions[x].id)
                    {
                      var letter = '';
                      for (let a = 0; a < letters.length; a++) {                        
                        if(i == a) {
                          letter = letters[a];
                        }                        
                      }
                      options = options + '<br />' + letter + ') ' + allOptions[x].description; 
                    }                      
                  }
                }    

                $('#all_question').append(
                  $('<div />').addClass('div-question').attr('href', '#ops').attr('data-questionid', allQuestions[index].id).append($('<table />').append($('<tr />').css('width', '600px').append($('<td />').css('width', '550px').append(
                    $('<label />').addClass('label-questions').html(allQuestions[index].description) 
                    ).append($('<div />').css('word-break', 'break-all').attr('id', 'ops').addClass('collapse').append($('<label />').addClass('label-question-collapse').html(options)))).append($('<td />').css('width', '150px').append($('<i />').addClass('close').addClass('fas fa-caret-down').addClass('caret-btn').addClass('icon-close').attr('coll-question', ' ')).append($('<i />').addClass('close').addClass('fas fa-plus-square').addClass('icon-close').attr('btn-add-question', ' ').attr('title', 'Informe 0(zero) para deletar.')).append($('<label />').addClass('sr-only').text('Ordem').addClass('label-order')).append($('<input />').addClass('sr-only').attr('size', '2')))))
                );                                              
              }                   
            }                   
          }
        },

        updateResearchQuestions: function(research_questions) {
          event.preventDefault();

          var research_id;

          if('' ==  $('[data-input-research]').val()) {
            research_id = $('[data-input-researches_id]').val();
          } else {
            research_id = $('[data-input-research]').val();
          }

          const token = $('meta[name="csrf-token"]').attr('content');

          const updateResearchQuest = $.ajax({
            method: 'PUT',
            url: '/researches/sponsored/researches-questions/upResearchQuestions',
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              questions : research_questions,
              researches_id: research_id,
              dataType: 'json',
            }),
          })

          updateResearchQuest.done($.proxy(this.onUpdateResearchQuestionsSuccess, this));        
          updateResearchQuest.fail($.proxy(this.onUpdateResearchQuestionsFail, this));
        },

        onUpdateResearchQuestionsSuccess: function(data) {
          if(data.success){

            $('#data-box-question').css('display', 'none');  
            $('[btn-question-edit]').removeClass('sr-only');
            sweet.common.message('success', 'Dados atualizados com sucesso!');
          }  
        },

        onUpdateResearchQuestionsFail: function(error) {
          console.log('Failed to UPDATE research_questions: ', error);
        },

        onSaveQuestionClick: function (event) {
          event.preventDefault(); 
    
          const $btn = $(event.currentTarget);
  
          /*         
          let string = $btn[0].className;   
          let result = string.includes('far fa-bookmark close-edit');

          if(result)
            $btn[0].className = 'fas fa-bookmark close-edit';
          else 
            $btn[0].className = 'far fa-bookmark close-edit';
          */     
        },


        onCloseQuestionClick: function (event) {
          event.preventDefault();
    
          const $btn = $(event.currentTarget);
          $btn[0].parentElement.remove();          
        },

        onBtnFinishQuestionClick: function (event) {
          event.preventDefault();

          if('insert' == $('[verify-action-type]').val()) {
            $('#data-box-question').css('display', 'none');              
            $('#box-middle-page').removeClass('sr-only');

            /**
             * select question and options
             * (for insert on table 'researches_middle_pages')
             * 
             */
            const token = $('meta[name="csrf-token"]').attr('content');

            const searchingResearchQuestions = $.ajax({
              method: 'GET',
              url: '/researches/sponsored/researches-questions/' + $('[data-input-research]').val(),
              contentType: 'application/json',
              data: JSON.stringify({
                _token: token,
                dataType: 'json',
              }),
            })

            Swal.fire({
              title: 'Aguarde!',
              html: 'Carregando dados...',          
            })

            Swal.showLoading()

            searchingResearchQuestions.done($.proxy(this.onSearchingResearchQuestionsSuccess, this));        
            searchingResearchQuestions.fail($.proxy(this.onSearchingResearchQuestionsFail, this));  
            
          } else {

            location.reload();

            //$('[btn-question-edit]').removeClass('sr-only');
            //$('#data-box-question').css('display', 'none');       
          }
           
        },

        onSearchingResearchQuestionsSuccess: function(data) {
          
          Swal.close()

          if(data.success) 
          {
            var questions = data.data;
            var middle_page = data.middlePages;

            // montar list das middle_pages cadastradas
            for (let index = 0; index < middle_page.length; index++) {
              $('#list-middle-pages').append(
                '<label class="radio-middle" style="margin-left:5%;font-size:small;margin-bottom:1.5%;">' 
                  + middle_page[index].title + ' <input type="radio" name="optradio" value=' 
                  + middle_page[index].id + '><span class="checkmark"></span></label>'
              );
            }

            for (let index = 0; index < questions.length; index++) 
            {
              var question =  questions[index];
              for (let i = 0; i < question.length; i++) 
              {   
                //tratamento para não pegar valor repetido             
                if((i != 0) && (question[i].questions_id == question[i-1].questions_id)) {
                  continue;
                }

                $('#select_questions').append($('<option>', {
                  value: question[i].questions_id,
                  text: this.formatDescriptionsResearches(question[i].question.description)
                }));

                $('#select_questions').selectpicker('refresh');
              }              
            }
          
            $('#select_options').on('change', function() {
              var optionsId = $('#select_options').val();
              $('[data-input-options_id]').val(optionsId);  
            });

            $('#select_questions').on('change', function() {

              var questionsId = $('#select_questions').val();
              $('[data-input-questions_id').val(questionsId);

              $("#select_options option").remove();
              $('#select_options').selectpicker('refresh');

              for (let index = 0; index < questions.length; index++) 
              {
                var question = questions[index];
                for (let i = 0; i < question.length; i++) 
                {   
                  if(question[i].questions_id == $('#select_questions').val()) 
                  {
                    $('#select_options').append($('<option>', {
                      value: question[i].options_id,
                      text: Researches.formatDescriptionsResearches(question[i].option.description)
                    })); 
                    $('#select_options').selectpicker('refresh');
                  }                  
                }
              }
            });
          }
        },

        onSearchingResearchQuestionsFail: function(error) {
          console.log('Failed to SEARCH research_questions: ', error);
        },

        onAddQuestionClick: function(event) {
          event.preventDefault();

          ev = event;
          const $btn = $(event.currentTarget);          

          var r_id;

          if('' == $('[data-input-research]').val()) {
            r_id = $('[data-input-researches_id]').val();
          } else {
            r_id = $('[data-input-research]').val();
          }

          var order;
          var questions_id;
          var researches_id;

          if('insert' == $('[verify-action-type]').val()) {
            order = $btn[0].nextElementSibling.nextElementSibling.value;
            questions_id = $btn[0].parentElement.parentElement.parentElement.parentElement.attributes[2].value;
            researches_id = r_id;
          } else {   
           
            order = $btn[0].parentElement.parentElement.parentElement.children[0].children[1].children[3].value;

            if('0' === order) {
              order = $btn[0].nextElementSibling.nextElementSibling.value;
            }
         
            questions_id = $btn[0].parentElement.parentElement.parentElement.parentElement.parentElement.attributes[5].value;

            if('0' === questions_id) {                
              questions_id = $btn[0].parentElement.parentElement.parentElement.parentElement.parentElement.attributes[5].value;
            }

            researches_id = r_id;  
          
          }          

          /**
           * fazer insert de novas questões
           */

          if('save' == $('[data-type-action]').val()) {
            
            $('[data-order]').val(order);

            this.saveResQuestions(event, order, questions_id, researches_id); 

            var str = $btn[0].className;

            if(str.indexOf("square") > -1){             
              this.getResearchQuestion(questions_id, researches_id);
            } 

            if('' == order){
              Swal.fire({
                title: 'Atenção!',
                html: 'Você precisa informar a ordem da questão!',  
                type: 'warning',        
              })
            } else {             
  
              $('[data-questions_id]').val(questions_id);
              $('[data-order]').val(order);
              $('[data-researches_id]').val(researches_id);
            }

          } else {             
              
              var str = $btn[0].className; 

              if(str.indexOf("fa-edit") > -1){
                this.getResearchQuestion(questions_id, researches_id);
              }  

              $btn[0].nextElementSibling.classList.value = 'label-order'; //remove sr-only              
              $btn[0].nextElementSibling.innerHTML = "Ordem"; //atualizar texto da label
              $btn[0].nextElementSibling.nextElementSibling.classList.value = 'color-input'; //remove sr-only
              $btn[0].classList.value = "close fas fa-check-square icon-close"; //modify icon button
              $('[data-type-action]').val('save');   
                                        
          }                         
        },                 
        
        getResearchQuestion: function(questions_id, researches_id){

          const token = $('meta[name="csrf-token"]').attr('content');

          const searching = $.ajax({
              method: 'POST',
              url: '/researches/sponsored/researches-questions/getResearchQuestion',
              contentType: 'application/json',
              data: JSON.stringify({
                _token: token,
                questions_id: questions_id,
                researches_id: researches_id,
                dataType: 'json',
              }),
          })

          searching.done($.proxy(this.onSearchingResQuesSuccess, this));      
          searching.fail($.proxy(this.onSearchingResQuesFail, this));

        }, 

        onSearchingResQuesSuccess: function(data) {
          if(data.success){
            var question = data.data;
            
            var divs = $(this)[0].$allQuestion[0].children;         

            if('insert' == $('[verify-action-type]').val()) {

              for (let index = 0; index < divs.length; index++) {                

                var val_input = divs[index].children[0].children[0].children[1].children[3].value;

                if('' != val_input) { 
                  if($('[data-order]').val() == val_input){
                    divs[index].children[0].children[0].children[1].children[3].value = question.ordering;
                    $('[val-order-edit]').val(question.ordering);
                  }                  
                }  
              }

            } else {

              for (let index = 0; index < divs.length; index++) {                
                
                var val_input = divs[index].children[0].children[0].children[0].children[1].children[3].value;
               
                if('' != val_input) { 
                  if($('[data-order]').val() == val_input){
                    divs[index].children[0].children[0].children[0].children[1].children[3].value = question.ordering;
                    $('[val-order-edit]').val(question.ordering);
                  }                  
                }                    
              }
            }
          }
        },  

        onSearchingResQuesFail: function(error) {
          console.log('Failed to SEARCH research_questions: ', error);
        },  

        formatButtons: function(event) {
          event.preventDefault(); 
 
          const $btn = $(event.currentTarget);         

          /**
           * tratamento para atualizar label com a ordem da questão
           */
          var divs = $(this)[0].$allQuestion[0].children;        

           if('insert' == $('[verify-action-type]').val()) {

            for (let index = 0; index < divs.length; index++) {                             

              var val_label = divs[index].children[0].children[0].children[1].children[2].textContent; // 1ª Questão
              
              var str = divs[index].children[0].children[0].children[1].children[2].classList.value;

              if(!(str.indexOf("sr-only") > -1)){
                divs[index].children[0].children[0].children[1].children[2].classList.value = "label-order";
              } 

              if('' != val_label) { 
                if(($('[data-order]').val() + 'ª Questão') == val_label){
                  divs[index].children[0].children[0].children[1].children[2].textContent = $('[val-order-edit]').val() + "ª Questão";
                }                  
              }  
            }             

            $btn[0].parentElement.parentElement.parentNode.parentElement.style = "background-color:#3ba689;color:#fff;"; //deixar fonte branca e div verde 
            $btn[0].parentElement.lastChild.classList.value = "sr-only color-input"; //esconder input
            $btn[0].parentElement.parentElement.children[1].children[2].innerHTML = $('[data-order]').val() + "ª Questão" ; //mostrar order cadastrada
            $btn[0].classList.value = "close fas fa-edit icon-close"; //icon button            
          
            $('[data-type-action]').val('edit');              
           
           } else {

            for (let index = 0; index < divs.length; index++) {                             

              var val_label = divs[index].children[0].children[0].children[0].children[1].children[2].textContent; // 1ª Questão
              
              var str = divs[index].children[0].children[0].children[0].children[1].children[2].classList.value;

              if(!(str.indexOf("sr-only") > -1)){
                divs[index].children[0].children[0].children[0].children[1].children[2].classList.value = "label-order";
              }                 

              if('' != val_label) { 
                var data_order = $('[data-order]').val() + 'ª Questão';  
                
                val_label = val_label.trim();                
                
                if(data_order.indexOf(val_label) > -1) { //se conter a string...  
                  
                  var order_aux = $('[val-order-edit]').val();

                  if(!order_aux) {
                    order_aux = $('[new-ordering-edit]').val();
                  }
                 
                  divs[index].children[0].children[0].children[0].children[1].children[2].innerHTML = order_aux + "ª Questão";
                }                  
              }  
            }

            $btn[0].parentElement.parentElement.parentNode.parentElement.style = "background-color:#3ba689;color:#fff;"; //deixar fonte branca e div verde 
            $btn[0].parentElement.parentElement.children[1].children[3].classList.value = "sr-only color-input"; //esconder input
            $btn[0].parentElement.parentElement.children[1].children[2].innerHTML = $('[data-order]').val() + "ª Questão"; //mostrar order cadastrada
            $btn[0].classList.value = "close fas fa-edit icon-close"; //icon button            
          
            $('[data-type-action]').val('edit');  
           }          

        },

        saveResQuestions: function(event, order, questions_id, researches_id) {

          if(0 == order){

            const $btn = $(event.currentTarget);
                       
            Swal.fire({
              title: 'Deseja remover esta questão da pesquisa?',
              type: 'warning',
              showCancelButton: true,
              cancelButtonText: 'Não',
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Sim',
              animation: true
            }).then((result) => {
              if (result.value) {
                this.deleteResearcheQuestion(event, questions_id, researches_id);
                Swal.fire(
                  'Questão removida!',
                  'A questão foi removida da pesquisa.',
                  'success'
                )
              } else {

                $btn[0].parentElement.parentElement.parentNode.parentElement.style = "background-color:#3ba689;color:#fff;"; //deixar fonte branca e div verde 
                $btn[0].parentElement.lastElementChild.classList.value = "sr-only color-input"; //esconder input
                $btn[0].parentElement.parentElement.children[1].children[2].innerHTML = $('[val-order-edit]').val() + "ª Questão"; //mostrar order 
                $btn[0].classList.value = "close fas fa-edit icon-close"; //icon button   
                
                $('[data-type-action]').val('edit');  
              }
            })
            
          } else {

            const token = $('meta[name="csrf-token"]').attr('content');

            const saveResearchQuestions = $.ajax({
              method: 'POST',
              url: '/researches/sponsored/researches-questions/researchQuestions',
              contentType: 'application/json',
              data: JSON.stringify({
                _token: token,
                questions_id : questions_id,
                researches_id: researches_id,
                order: order,
                dataType: 'json',
              }),
            })

            saveResearchQuestions.done($.proxy(this.onCreateResearchQuestionsSuccess, this));            
            saveResearchQuestions.fail($.proxy(this.onCreateResearchQuestionsFail, this)); 

          }           
        },

        onCreateResearchQuestionsSuccess: function(data) {
          if(data.success) {
                         
            $('[data-input-title]').prop('disabled', true);
            $('[data-input-subtitle]').prop('disabled', true);
            $('[data-input-description]').prop('disabled', true);
            $('[data-input-points]').prop('disabled', true);
            $('[data-input-finalurl]').prop('disabled', true);
            $('#enabled').prop('disabled', true);
            $('[data-btn-research]').prop('disabled', true).text('Cadastrado');  

            if(data.data[0]){
              $('[new-ordering-edit]').val(data.data[0].ordering);
            }            

            //recarregar somente a div 
            //if('' == $('[verify-action-type]').val()) {
            //  $('#refresh').load(location.href + ' #all_question');
            //}            
            
            this.formatButtons(ev);

          }  
        },

        deleteResearcheQuestion: function(event, questions_id, researches_id) {

          evento = event;

          const token = $('meta[name="csrf-token"]').attr('content');

          const deleteResearchQuestions = $.ajax({
            method: 'POST',
            url: '/researches/sponsored/researches-questions/deleteResearchQuestion',
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              questions_id : questions_id,
              researches_id: researches_id,
              dataType: 'json',
            }),
          })

          deleteResearchQuestions.done($.proxy(this.onDeleteResearchQuestionsSuccess, this));            
          deleteResearchQuestions.fail($.proxy(this.onDeleteResearchQuestionsFail, this));  

        },

        onDeleteResearchQuestionsSuccess: function(data) {
          
          const $btn = $(evento.currentTarget);

          $btn[0].parentElement.parentElement.parentNode.parentElement.style = "background-color:rgb(243, 243, 244);color:#676a6c;"; //deixar fonte e div cinza
          $btn[0].parentElement.parentElement.children[1].children[3].classList.value = "sr-only color-input"; //esconder input
          $btn[0].parentElement.parentElement.children[1].children[2].innerHTML = "Ordem";
          $btn[0].parentElement.parentElement.children[1].children[2].classList.value = "sr-only label-order";
          $btn[0].classList.value = "close fas fa-plus-square icon-close"; //icon button    

          $('[data-type-action]').val('edit');  
        },

        onDeleteResearchQuestionsFail: function(error) {
          console.log('Failed to DELETE research_questions: ', error);
        },

        formatDescriptionsResearches: function(description) {

          if(description.length >= 130) {
            description = description.substr(0, 110) + '...';
          }

          return description;              
        },

        onCreateResearchQuestionsFail: function(error) {
          console.log('Failed to CREATE research_questions: ', error);
        },
    
        onSearchingFail: function(error) {
          console.log('Failed to SEARCH question: ', error);
        },
    
        onCreateFail: function(error) {
          console.log('Failed to CREATE research: ', error);
        },

        onBtnSponsoredClick: function(event) {

          const $btn = $(event.currentTarget);

          var string = $btn[0].textContent;

          if(string.includes('Próxima etapa')) {
            $('[verify-action-type]').val('insert');  
          } 
          
          this.onFormSubmit(event);       
        },

        onAllQuestionClick: function(event) {
          event.preventDefault();

          const $btn = $(event.currentTarget);

          if(' ' == $btn[0].parentElement.parentElement.children[0].children[1].classList.value) {
            $btn[0].parentElement.parentElement.children[0].children[1].classList.value = "collapse in";
            $btn[0].className = "close fas fa-caret-up caret-btn icon-close";
          }

          if('collapse' == $btn[0].parentElement.parentElement.children[0].children[1].classList.value) { 
            $btn[0].parentElement.parentElement.children[0].children[1].classList.value = "collapse in";
            $btn[0].className = "close fas fa-caret-up caret-btn icon-close"; 
          } else if('collapse in' == $btn[0].parentElement.parentElement.children[0].children[1].classList.value) {
            $btn[0].parentElement.parentElement.children[0].children[1].classList.value = "collapse";
            $btn[0].className = "close fas fa-caret-down caret-btn icon-close";
          }
        },

        onResearchQuestionClick: function(event) {
          event.preventDefault();

          const $btn = $(event.currentTarget);

          if('collapse' == $btn[0].children[2].classList.value) {           
            $('[data-research-questions]').children()[0].children[2].className = "collapse";
            $btn[0].children[2].classList.value = "collapse in";
          } else if('collapse in' == $btn[0].children[2].classList.value) {
            $btn[0].children[2].classList.value = "collapse";
          }

          if('collapse' == $('[data-research-questions]').children()[0].children[2].className) {           
            $('[data-research-questions]').children()[0].children[2].className = "collapse in";
          } else if('collapse in' == $('[data-research-questions]').children()[0].children[2].className) {
            $('[data-research-questions]').children()[0].children[2].className = "collapse";
          }
        },

        onBtnEditResearchClick: function (event) {
          event.preventDefault();

          $('#data-box-research').css('display', '');  
          $('[btn-research-edit]').addClass('sr-only');  
        },

        onBtnCancelEditResearchClick: function (event) {
          event.preventDefault();

          $('#data-box-research').css('display', 'none');  
          $('[btn-research-edit]').removeClass('sr-only'); 
        },

        onBtnEditClick: function (event) {
          event.preventDefault();

          const $btn = $(event.currentTarget);
          const id = $.trim($btn.data('id'));
          
          window.location = '/researches/sponsored/edit/' + id;

        },

        onBtnDestroyClick: function (event) {
          event.preventDefault();
    
          const id    = $(event.currentTarget).data('id');
          const token = $('meta[name="csrf-token"]').attr('content');
    
          const destroying = $.ajax({
            cache  : false,
            method : 'POST',
            url    : `/researches/sponsored/delete/${id}`,
            data   : {
              _method: 'delete',
              _token : token,
            },
          });
    
          destroying.done($.proxy(this.onDestroySuccess, this));      
          destroying.fail($.proxy(this.onDestroyFail, this));
        },

        onDestroySuccess: function(data) {
          if (true == data.success) {
            this.DataTable.ajax.reload().desc;
            sweet.common.message('success', 'Dados deletados com sucesso!');
          } else {
            sweet.common.message('alert', 'Não foi possível deletar a pesquisa!');
          }
        },

        onDestroyFail: function(error) {
          console.log('Failed to DELETE research: ', error);
        },

        onUpdateSubmit: function() {
          var id = this.$id.val();
          const token = $('meta[name="csrf-token"]').attr('content');

          const updating = $.ajax({
            method: 'PUT',
            url: `/researches/sponsored/update/${id}`,
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              id          : id,
              title       : this.$title.val(),
              subtitle    : this.$subtitle.val(),
              description : this.$description.val(),
              points      : this.$points.val(),
              final_url   : this.$finalurl.val(),
              enabled     : $('#enabled').val(),
              dataType: 'json',
            }),     
        })                     
      
          updating.done($.proxy(this.onUpdateSuccess, this));      
          updating.fail($.proxy(this.onUpdateFail, this));
        },
     
        onUpdateSuccess: function(data) {
          if (data.success) {            
            $('#data-box-research').css('display', 'none');  
            $('[btn-research-edit]').removeClass('sr-only'); 
            sweet.common.message('success', 'Dados atualizados com sucesso!');
          } 
        },       
    
        onUpdateFail: function(error) {
          console.log('Failed to UPDATE research: ', error);
        },  
       
  };

$(function() {
  Researches.start();
});
})(jQuery);