(function($) {
    const Questions = {
        inputs: {},

        start: function() {
            this.$btnFinalizar       = $('[btn-finalizar-question]');
            this.$btnNewQuestion     = $('[btn-new-question]'); 

            this.$modal              = $('[data-modal-question]');
            this.$modalTitle         = this.$modal.find('[data-modal-title]');
            this.$form               = this.$modal.find('[data-form-question]');  
            this.$btnConfirm         = this.$modal.find('[data-btn-confirm]');

            this.$btnEditQuestion         = $('[btn-question-edit]');
            this.$btnCancelEditQuestion   = $('[cancel-question-edit]');

            this.inputs.$id          = this.$form.find('[data-input-id]');
            this.inputs.$action      = this.$form.find('[data-input-action]');
            this.inputs.$description = this.$form.find('[data-description-question]');
            this.inputs.$information = this.$form.find('[data-extra-information]');
  
            this.bind();
        },

        bind: function() {
            this.$btnConfirm.on('click', $.proxy(this.onBtnQuestionClick, this));
            this.$btnNewQuestion.on('click', $.proxy(this.onNewQuestionClick, this));
            this.$btnEditQuestion.on('click', $.proxy(this.onEditQuestionClick, this));
            this.$btnCancelEditQuestion.on('click', $.proxy(this.onBtnCancelEditQuestionClick, this));
            this.$form.on('submit', $.proxy(this.onFormSubmit, this));
            this.$modal.on('hide.bs.modal', $.proxy(this.onHideModal, this));
        },

        onEditQuestionClick: function(event) {
          event.preventDefault();

          $('#data-box-question').css('display', '');  
          $('[btn-question-edit]').addClass('sr-only'); 
        },

        onBtnCancelEditQuestionClick: function(event) {
          event.preventDefault();

          $('#data-box-question').css('display', 'none');  
          $('[btn-question-edit]').removeClass('sr-only'); 
        },

        onNewQuestionClick: function(event) {
            event.preventDefault();

            this.$modalTitle.text('Cadastrar Nova Pergunta');
            //this.inputs.$action.val('create');
            this.$btnConfirm.text('Cadastrar');
            this.inputs.$description.val('');
            $('[data-option-description]').val('');

            $('.add-input-config').on('click', function() {

                var div = $('#group-config-option');

                $('<div />').attr('class', 'additional').append($('<div />').addClass('col-md-2').addClass('control-label').append($('<label />').html('Alternativa:')))
                .append(
                    $('<div />').addClass('col-md-9').append($('<input />').attr({'type':'text', 'id':'option_description', 'name':'option_description[]', 'placeholder':"Descrição de Alternativa", 'data-option-description':''}).css({'top':'8px'}).addClass('form-control').addClass('input-sm'))
                ).append(
                    $('<div />').addClass('col-md-1').append($('<a />').attr({'href':'javacript:void(0);', 'class':'remove'}).addClass('btn btn-danger').css({'float':'right','right':'47px','top':'-2px'}).append($('<span />').append($('<i />').attr('aria-hidden', 'true').addClass('fa fa-minus-circle'))))
                ).appendTo(div);
                
                return false;
            });

            $(_).on('click', '.remove', function () {
                $(this).parents('div.additional').remove();
                return false;
            });
            
            this.$modal.modal('show');
        },

        onFormSubmit: function (event) {
            event.preventDefault();
      
            if (
              '' === $.trim(this.inputs.$description.val()) ||
              '' === $.trim($('#one_answer').val()) )
             {
              sweet.common.message('error', 'Todos os campos são obrigatórios');
              return;
            } 

            const formAction = $.trim(this.inputs.$action.val());
      
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
          },

          onCreateSubmit: function() {

            var options = [];

            $('input[name^="option_description"]').each(function(index) {
                options[index] = $(this).val();
            }); 
                       
            if (
              '' === this.inputs.$description.val() ||
              '' === $('#one_answer').val() ||
              '' === options[0]
            ) {
              sweet.common.message('error', 'Todos os campos são obrigatórios');
              return;
            }

           const token = $('meta[name="csrf-token"]').attr('content');
            
            const saving = $.ajax({
                method: 'POST',
                url: '/researches/sponsored/question/store',
                contentType: 'application/json',
                data: JSON.stringify({
                  _token: token,
                  description : this.inputs.$description.val(),
                  one_answer: $('#one_answer').val(),
                  extra_information: this.inputs.$information.val(),
                  options: options,
                  dataType: 'json',
                }),
            })
      
            saving.done($.proxy(this.onCreateSuccess, this));
      
            saving.fail($.proxy(this.onCreateFail, this));
          },


            onCreateSuccess: function(data) {
                if (data.success) {
                
                    var question_options = data.data;

                    var id = question_options[0]['data'].questions_id;
                    const token = $('meta[name="csrf-token"]').attr('content');

                    const searching = $.ajax({
                      method: 'GET',
                      url: '/researches/sponsored/question/getQuestionOptionsByQuestion/' + id,
                      contentType: 'application/json',
                      data: JSON.stringify({
                        _token: token,
                        dataType: 'json',
                      }),
                  })
            
                  searching.done($.proxy(this.onSearchingSuccess, this));
            
                  searching.fail($.proxy(this.onSearchingFail, this));

                  this.$modal.modal('hide');
                  
                  sweet.common.message('success', 'Dados cadastrados com sucesso!');
                }
              },

              onSearchingSuccess: function(data) {
                if (data.success) {

                  var letters = [
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 
                    'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T','U', 'V', 
                    'W', 'X',  'Y', 'Z'
                  ];

                  this.$modal.modal('hide');

                  var question = data.data;

                  var options = '';
                  for (let index = 0; index < question.length; index++) 
                  {
                    var letter = '';
                      for (let a = 0; a < letters.length; a++) {                        
                        if(index == a) {
                          letter = letters[a];
                        }                        
                      }
                    const element = question[index].option;
                    options = options + '<br />' + letter + ') ' + element.description; 
                  }
                  
                  let string = $('[btn-finalizar-question]').text();
                  let result = string.includes('Atualizar');
                  var cssClass = '';
                  var classIcon = '';
                  var classClose = '';
                  var seletor = '';

                  if(result) {
                    cssClass = 'close-edit';
                    classIcon = 'fas fa-plus-square';
                    classClose = '';
                    seletor = 'btn-edit-save';
                  } else {                  
                    cssClass = 'icon-close';
                    classIcon = 'fas fa-plus-square';
                    classClose = 'close';
                    seletor = 'btn-add-question';
                  }  

                  $('#all_question').append(
                    $('<div />').addClass('div-question').attr('href', '#ops').attr('data-questionid', question[0].questions_id).append($('<table />').append($('<tr />').css('width', '600px').append($('<td />').css('width', '550px').append(
                      $('<label />').addClass('label-questions').html(question[0].question.description) 
                      ).append($('<div />').css('word-break', 'break-all').attr('id', 'ops').addClass('collapse').append($('<label />').addClass('label-question-collapse').html(options)))).append($('<td />').css('width', '150px').append($('<i />').addClass('close').addClass('fas fa-caret-down').addClass('caret-btn').addClass('icon-close').attr('coll-question', ' ')).append($('<i />').addClass(classClose).addClass(classIcon).addClass('icon-close').attr(seletor, ' ')).append($('<label />').addClass('sr-only').text('Ordem').addClass('label-order')).append($('<input />').addClass('sr-only').attr('size', '2')))))
                  );                    
              }

            },

            onSearchingFail: function(error) {
              console.log('Failed to SEARCH question: ', error);
            },
          
            onCreateFail: function(error) {
              console.log('Failed to CREATE question: ', error);
            },

            onHideModal: function(event) {   
              
             $('.additional').children("div").remove();
             $('.additional').hide();
            
            },

    };

    $(function() {
        Questions.start();
    });
})(jQuery);
    