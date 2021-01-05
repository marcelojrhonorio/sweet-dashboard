(function($) {
  const MiddlePages = {

      start: function() {

          this.$btnConfirm            = $('[data-btn-middlepages]');  
          this.$btnSaveMiddle         = $('[btn-save-middlepages]');                        

          this.$btnEditMiddlePage     = $('[btn-middlepage-edit]');
          this.$cancelEditMiddlePage  = $('[cancel-middlepage-edit]');   
          this.$createMiddlePage      = $('[create-middle-page]');  
          this.$loadMiddlePage        = $('[load-middle-page]'); 
          
          this.$listMiddlePage        = $('#list-middle-pages');
          
          this.$form                  = $('[data-form-middlepages]');
          this.$icon                  = this.$form.find('[data-input-icon]');
          this.$title                 = this.$form.find('[data-title-middlepage]');
          this.$description           = this.$form.find('[data-description-middlepage]');
          this.$redirectLink          = this.$form.find('[data-input-redirectlink]');
          this.$path                  = this.$form.find('[data-input-path]');
          this.$id                    = this.$form.find('[data-input-id]');
          this.$action                = this.$form.find('[data-input-action]');

          this.$radio                 =  $('[data-list-middle-page]');
          this.$btnCancelEdit         =  $('[cancel-edit-middlepages]');  
          this.$btnFinishEdit         =  $('[finish-middlepage-edit]');                    
          
          this.$wrapUpload            = $('[data-wrap-upload]');
          this.$wrapFile              = $('[data-wrap-file]');
          this.$wrapPreview           = $('[data-wrap-preview]');
          this.$progress              = $('[data-upload-progress]');
          this.$deleteImg             = $('[data-destroy-img]'); 
          
          this.bind();
      },

      bind: function() {
        this.$btnEditMiddlePage.on('click', $.proxy(this.onBtnEditMiddlePageClick, this));
        this.$cancelEditMiddlePage.on('click', $.proxy(this.onBtnCancelEditMiddlePageClick, this));
        this.$btnConfirm.on('click', $.proxy(this.onConfirmClick, this));
        this.$btnSaveMiddle.on('click', $.proxy(this.onSaveClick, this));
        this.$deleteImg.on('click', $.proxy(this.onDestroyImageClick, this));  
        this.$icon.on('change', $.proxy(this.onIconChange, this));
        this.$form.on('submit', $.proxy(this.onFormSubmit, this));
        this.$createMiddlePage.on('click', $.proxy(this.onCreateMiddlePageClick, this));
        this.$loadMiddlePage.on('click', $.proxy(this.onLoadMiddlePageClick, this));
        this.$radio.on('click', '[rd-middle]', $.proxy(this.onListMiddlePageClick, this)); 
        this.$btnCancelEdit.on('click', $.proxy(this.onCancelEditClick, this));
        this.$btnFinishEdit.on('click', $.proxy(this.onFinishEditClick, this));
      },  

      onFinishEditClick: function(event){
        window.location = '/researches/sponsored';
        sweet.common.message('success', 'Dados atualizados com sucesso!'); 
      },
       
      onCancelEditClick: function(event) {

        $('[data-form-middle]').addClass('sr-only');
        $('[data-question-and-options]').addClass('sr-only');
        $('[data-btn-middlepages]').addClass('sr-only');
        $('[cancel-edit-middlepages]').addClass('sr-only');

        $('[data-list-middle-page]').removeClass('sr-only');
        $('[cancel-middlepage-edit]').removeClass('sr-only');
        $('[finish-middlepage-edit]').removeClass('sr-only');
      },
      
      onListMiddlePageClick: function(event) {

        if('update' == this.$action.val()) {

          const $btn = $(event.currentTarget);
          const id = $.trim($btn.data('id'));

          var data = $("input[name='optradio']:checked").val();  

          var ids = data.split("|", 2);
          var middle_pages_id = ids[0];
          var researches_middle_pages_id = ids[1];

          var researches_id;

          if('' == $('[data-input-research]').val()) {
            researches_id = $('[data-input-researches_id]').val();
          } else {
            researches_id = $('[data-input-research]').val();
          }           
          
          $('[edit-middlepageid]').val(middle_pages_id);
          $('[data-input-middle_page]').val(middle_pages_id);
          $('[researches-middle-pages-id]').val(researches_middle_pages_id);        
          
          //mostrar formulário do middle-page para edição
          $('[data-form-middle]').removeClass('sr-only');
          $('[data-question-and-options]').removeClass('sr-only');
          $('[data-btn-middlepages]').removeClass('sr-only');
          $('[cancel-edit-middlepages]').removeClass('sr-only');
          
          $('[data-list-middle-page]').addClass('sr-only');
          $('[cancel-middlepage-edit]').addClass('sr-only');
          $('[finish-middlepage-edit]').addClass('sr-only');

          const token = $('meta[name="csrf-token"]').attr('content');

          const searchingDataEdit = $.ajax({
            method: 'GET',
            url: '/researches/sponsored/researches-questions/' + researches_id,
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

          searchingDataEdit.done($.proxy(this.onSearchingDataEditSuccess, this));    
          searchingDataEdit.fail($.proxy(this.onSearchingDataEditFail, this));  
        }
      },

      onSearchingDataEditSuccess: function(data) {

        Swal.close()

        if(data.success) 
        {
          var allQuestions = data.data;
          var middle_page = data.middlePages;

          var middle_pages_id = $('[edit-middlepageid]').val();              

          // remover options para criar corretamente
          $("#select_options option").remove();
          $('#select_options').selectpicker('refresh');

          for (let index = 0; index < middle_page.length; index++) {
            
            // preencher formulário
            if(middle_page[index].middle.id == middle_pages_id) { 
              this.$title.val(middle_page[index].middle.title);
              this.$description.val(middle_page[index].middle.description);
              this.$redirectLink.val(middle_page[index].middle.redirect_link);
              this.$path.val(middle_page[index].middle.image_path);

              $('.image-preview').attr('src', $('[data-input-sweetmedia]').val() + '/storage/' + middle_page[index].middle.image_path);
              $('[data-img-edit]').attr('value', middle_page[index].middle.image_path);
            }

            // selecionar relacionamento researches_questions para edição
            if(middle_page[index].researches_middle_pages.id == $('[researches-middle-pages-id]').val()) {

              $('[data-input-options_id]').val(middle_page[index].researches_middle_pages.options_id);
              $('[data-input-questions_id]').val(middle_page[index].researches_middle_pages.questions_id);

              var questions = document.querySelector("#select_questions");
              
              for (let i = 1; i < questions.length + 1; i++) {
                var question = document.querySelector("#select_questions > option:nth-child("+ i +")");
              
                // achar questão correspondente
                if(middle_page[index].researches_middle_pages.questions_id == question.value) {
                  question.selected = "selected";
                  $('#select_questions').selectpicker('refresh');

                  //achar alternativa correspondente
                  for (let x = 0; x < allQuestions.length; x++) {
                    const element = allQuestions[x];

                    for (let z = 0; z < element.length; z++) {

                      if(element[z].questions_id == question.value) {
                        
                        //montar select de opções da questão cadastrada    
                        $('#select_options').append($('<option>', {
                          value: element[z].option.id,
                          text: MiddlePages.formatDescriptionsResearches(element[z].option.description)
                        })); 

                        $('#select_options').selectpicker('refresh');

                        var options = document.querySelector("#select_options");                        

                        for (let y = 1; y < options.length + 1; y++) {
                          var option = document.querySelector("#select_options > option:nth-child("+ y +")");
                         
                          if(middle_page[index].researches_middle_pages.options_id == option.value) {
                            option.selected = "selected";
                            $('#select_options').selectpicker('refresh');
                          }
                        }
                      }                          
                    }                    
                  } 
                }               
              }
            }
          }
        }
      },     

      onSearchingDataEditFail: function(error) {
        console.log('Failed to SEARCH edit_middle_pages: ', error);
      },

      onLoadMiddlePageClick:function(event) {
        event.preventDefault();

        $('[data-btn-middlepages]').removeClass('sr-only');
        $('[data-form-middle]').addClass('sr-only');
        $('[data-type-submit-middle]').val('1');

        $('[data-question-and-options]').removeClass('sr-only');
        $('[btn-save-middlepages]').removeClass('sr-only');  
        $('[data-list-middle-page]').removeClass('sr-only'); 

        if('create' == this.$action.val()){

          const token = $('meta[name="csrf-token"]').attr('content');
        
          const searchingMiddlePage = $.ajax({
            method: 'POST',
            url: '/researches/sponsored/middle-page/get-middle-pages' ,
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              dataType: 'json',
              research_id: $('[data-input-research]').val(),
            }),
          })

          searchingMiddlePage.done($.proxy(this.onSearchingMiddlePageSuccess, this));    
          searchingMiddlePage.fail($.proxy(this.onSearchingMiddlePageFail, this)); 
        }        
          
      },

      onSearchingMiddlePageSuccess: function(data) {
        if(data.success){
          var middle_page = data.data;

         //don't repeat list middle page
         $("#list-middle-pages").empty();

         for (let index = 0; index < middle_page.length; index++) {
           $('#list-middle-pages').append(
             '<label class="radio-middle" style="margin-left:5%;font-size:small;margin-bottom:1.5%;">' 
               + middle_page[index].title + ' <input type="radio" rd-middle name="optradio" value=' 
               + middle_page[index].id + '><span class="checkmark"></span></label>'
           );
         }
        }
      },

      onSearchingMiddlePageFail: function(error) {
        console.log('Failed to SEARCH middle_pages: ', error);
      },

      onCreateMiddlePageClick:function(event) {
        event.preventDefault();
        
        $('[data-form-middle]').removeClass('sr-only');
        $('[data-question-and-options]').removeClass('sr-only'); 
        $('[btn-save-middlepages]').removeClass('sr-only');
        $('[data-list-middle-page]').addClass('sr-only'); 
        $('[data-btn-middlepages]').removeClass('sr-only');

        $('[data-type-submit-middle]').val('0');
      },

      onBtnEditMiddlePageClick: function (event) {
        event.preventDefault();
        
        $('#data-box-middle-page').css('display', '');  
        $('[btn-middlepage-edit]').addClass('sr-only');   
        $('[btn-save-middlepages]').addClass('sr-only'); 
        $('[data-form-middle]').addClass('sr-only'); 
        $('[data-question-and-options]').addClass('sr-only'); 
        $('[cancel-edit-middlepages]').addClass('sr-only'); 
        $('[data-btn-middlepages]').addClass('sr-only'); 

        $('[create-middle-page]').css('background-color', '#fff').addClass('sr-only');  
        $('[load-middle-page]').css('background-color', '#fff').addClass('sr-only');  
        $('[data-list-middle-page]').removeClass('sr-only');          
                
        /**
         * select question and options
         * (for insert on table 'researches_middle_pages')
         * 
         */
         
        const token = $('meta[name="csrf-token"]').attr('content');

        const searchingResearchQuestions = $.ajax({
          method: 'GET',
          url: '/researches/sponsored/researches-questions/' + $('[data-input-researches_id]').val(),
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
      },

      formatDescriptionsResearches: function(description) {

        if(description.length >= 130) {
          description = description.substr(0, 110) + '...';
        }
        return description;   
        
      },

      onSearchingResearchQuestionsSuccess: function(data) {

        Swal.close()

        if(data.success) 
        {
          var questions = data.data;
          var middle_page = data.middlePages;

          $("#list-middle-pages").empty();

          $('#list-middle-pages').append(
            $('<label>').addClass('title-edit-middle').text('Esta pesquisa possui a(s) seguinte(s) página(s) intermediária(s) cadastrada(s):')
          );

          for (let index = 0; index < middle_page.length; index++) {
            
            $('#list-middle-pages').append(
              '<label class="radio-middle" style="margin-left:5%;font-size:small;margin-bottom:1.5%;">' 
                + middle_page[index].middle.title + ' <input type="radio" rd-middle name="optradio" value=' 
                + middle_page[index].middle.id + '|' + middle_page[index].researches_middle_pages.id +' data-id='+ middle_page[index].middle.id +'><span class="checkmark"></span></label>'
            );            
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
                    text: MiddlePages.formatDescriptionsResearches(question[i].option.description)
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

      onBtnCancelEditMiddlePageClick: function (event) {
        event.preventDefault();

        $('.image-preview').removeClass('sr-only');
        $('[data-destroy-img]').removeClass('sr-only');
        this.$wrapUpload.addClass('sr-only');

        $('#data-box-middle-page').css('display', 'none');  
        $('[btn-middlepage-edit]').removeClass('sr-only'); 
      },

      onConfirmClick: function(event) {
        $('[data-save-mp]').val('0');
        this.onFormSubmit(event);  
      },

      onSaveClick: function(event) {
        $('[data-save-mp]').val('1');
        this.onFormSubmit(event);  
      },

      onDestroyImageClick: function(event) {
        event.preventDefault();
  
        this.$icon.val('');
        this.$path.val('');
        
        $('.image-preview').addClass('sr-only');
        $('[data-destroy-img]').addClass('sr-only');
        this.$wrapPreview.addClass('sr-only');
        this.$wrapUpload.removeClass('sr-only');
  
      },

      onFormSubmit: function(event) {
          event.preventDefault();          
    
          if ('create' === this.$action.val()) {
            this.onCreateSubmit();
          } else {
            this.onUpdateSubmit();
          }

          $('.fileinput-filename').text('');
          
         // this.$wrapFile.fileinput('clear');
    
        },

        onCreateSubmit: function() {

          var middle_pages_id = $("input[name='optradio']:checked").val();

          var type_subit = $('[data-type-submit-middle]').val();

          if('1' == type_subit){

            if (
              '' === middle_pages_id ||
              '' === $('[data-input-questions_id]').val() ||
              '' === $('[data-input-options_id]').val() 
            ) {
              sweet.common.message('error', 'Todos os campos são obrigatórios');
              return;
            }

            var r_id;

            if('' == $('[data-input-research]').val()) {
              r_id = $('[data-input-researches_id]').val();
            } else {
              r_id = $('[data-input-research]').val();
            }

            const params = {
              middle_pages_id : middle_pages_id,
              researches_id : r_id,
              options_id :  $('[data-input-options_id]').val(),
              questions_id: $('[data-input-questions_id]').val(),
            };

            this.insertResearchMiddlePage(params);            
            return;

          } else {

            if (
              '' === this.$title.val() ||
              '' === this.$description.val() ||
              '' === this.$icon.val() ||
              '' === this.$redirectLink.val() 
            ) {
              sweet.common.message('error', 'Todos os campos são obrigatórios');
              return;
            }            
          }

          const params = {
            title              : this.$title.val(),
            description        : this.$description.val(),
            image_path         : this.$icon.val(),
            redirect_link      : this.$redirectLink.val(),
          };

          const token = $('meta[name="csrf-token"]').attr('content');

          const saving = $.ajax({
              method: 'POST',
              url: '/researches/sponsored/middle-page/store',
              contentType: 'application/json',
              data: JSON.stringify({
                _token: token,
                params : params,
                dataType: 'json',
              }),
          })
      
          saving.done($.proxy(this.onMiddlePageSuccess, this));        
          saving.fail($.proxy(this.onMiddlePageFail, this));          
    
        },

        onMiddlePageSuccess: function(data) {
          if (data.success) {

            var middle_page = data.data;
            var r_id;

            if('' == $('[data-input-research]').val()) {
              r_id = $('[data-input-researches_id]').val();
            } else {
              r_id = $('[data-input-research]').val();
            }
            
            const params = {
              middle_pages_id : middle_page.id,
              researches_id : r_id,
              options_id :  $('[data-input-options_id]').val(),
              questions_id: $('[data-input-questions_id]').val(),
            };
            
            this.insertResearchMiddlePage(params);               
          }
        },

        insertResearchMiddlePage: function(params) {

          //insert data in researches_middle_pages table
          const token = $('meta[name="csrf-token"]').attr('content');

          const saving_res_middle_pages = $.ajax({
              method: 'POST',
              url: '/researches/sponsored/middle-page/researches-middle-pages',
              contentType: 'application/json',
              data: JSON.stringify({
                _token: token,
                params : params,
                dataType: 'json',
              }),
          })

          saving_res_middle_pages.done($.proxy(this.onResearchesMiddlePageSuccess, this));        
          saving_res_middle_pages.fail($.proxy(this.onResearchesMiddlePageFail, this));  
        
        },

        onResearchesMiddlePageSuccess: function(data) {
          if (data.success) {

            if('0' == $('[data-save-mp]').val()){
              window.location = '/researches/sponsored';
              sweet.common.message('success', 'Dados cadastrados com sucesso!');  
            } else {
              this.$title.val('');
              this.$description.val('');
              this.$icon.val('');
              this.$redirectLink.val('');
              sweet.common.message('success', 'Dados cadastrados com sucesso!');  
            }                      
          }
        },

        onResearchesMiddlePageFail: function(error) {
          console.log(error);
        },
    
        onMiddlePageFail: function(error) {
          console.log(error);
        },

        onCreateSuccess: function(data) {
          if (data.success) {
            sweet.common.message('success', 'Dados cadastrados com sucesso!');
          }
        },
    
        onCreateFail: function(error) {
          console.log(error);
        },
       
        onUpdateSubmit: function() {
          var id = $('[data-input-middle_page]').val();
         

          if(!id) {
            this.onCreateSubmit();
          }          

          var image = '';
          var flagEdit = false;

          if ('' == this.$icon.val()) {
            flagEdit = false;
            image = $('[data-img-edit]').val();
          } else {
            image = this.$path.val();
            flagEdit = true;
          }

          if(flagEdit){
            $('.image-preview').removeClass('sr-only');
            $('[data-destroy-img]').removeClass('sr-only');
            this.$wrapUpload.addClass('sr-only');
          }

          //$('.image-preview').attr('src', $('[data-input-sweetmedia]').val() + '/storage/' + image);
          $('[data-img-edit]').attr('value', image);
          $('[data-path]').attr(image);

          this.verifyMiddlePage(id, this.$title.val(), this.$description.val(), this.$redirectLink.val(), image, $('[data-input-researches_id]').val());

        },

        verifyMiddlePage: function(id, title, description, redirectLink, image, researches_id) {
          
          const token = $('meta[name="csrf-token"]').attr('content');

          const searching = $.ajax({
            method: 'POST',
            url: '/researches/sponsored/middle-page/verify-middle-pages' ,
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              dataType: 'json',
              id: id,
              title: title,
              description: description,
              redirectLink: redirectLink,
              researches_id : researches_id,
              image: image,
            }),
          })

          searching.done($.proxy(this.onSearchingMiddleSuccess, this));    
          searching.fail($.proxy(this.onSearchingMiddleFail, this));          

        },

        onSearchingMiddleSuccess: function(data) {
          if (data.success) {  

            Swal.fire({
              title: 'Esta Página Intermediária é utilizada em outra pesquisa.',
              text: "Deseja atualizar assim mesmo?",
              type: 'warning',
              showCancelButton: true,
              cancelButtonText: 'Não',
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Sim',
              animation: true
            }).then((result) => {
              if (result.value) {
                this.updateOk(data.data);
              } 
            })
          } else {
            this.updateOk(data.data);
          }
        },

        onSearchingMiddleFail:function(error) {
          console.log('Failed to VERIFY middle: ', error);
        },

        updateOk: function(dados) {

          const token = $('meta[name="csrf-token"]').attr('content');

          const updating = $.ajax({
            method: 'PUT',
            url: `/researches/sponsored/middle-page/update/${dados.id}`,
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              id            : dados.id,
              title         : dados.title,
              description   : dados.description,
              redirect_link : dados.redirectLink,
              image_path    : dados.image,
              researches_id : dados.researches_id,
              options_id    : $('[data-input-options_id]').val(),
              questions_id  : $('[data-input-questions_id]').val(),
              dataType      : 'json',
            }),
          })
      
          updating.done($.proxy(this.onUpdateSuccess, this));      
          updating.fail($.proxy(this.onUpdateFail, this));
        },

        onUpdateSuccess: function(data) {
          if (data.success) {   
            
            $('[data-form-middle]').addClass('sr-only');
            $('[data-question-and-options]').addClass('sr-only');
            $('[data-btn-middlepages]').addClass('sr-only');
            $('[cancel-edit-middlepages]').addClass('sr-only');
            
            $('[data-list-middle-page]').removeClass('sr-only');
            $('[cancel-middlepage-edit]').removeClass('sr-only');
            $('[finish-middlepage-edit]').removeClass('sr-only');

            sweet.common.message('success', 'Dados atualizados com sucesso!');
          } 
        },
    
        onUpdateFail: function(error) {
          console.log('Failed to UPDATE research: ', error);
        },

      onIconChange: function(event) {
          event.preventDefault(); 

          if ('' === event.target.value) {
            console.log('não vai upar');
            return;
          }
          
          this.$progress.removeClass('hidden');
    
          const token = $('meta[name="csrf-token"]').attr('content');
    
          const headers = {
            'X-CSRF-TOKEN': token,
          };
    
          const data = new FormData(this.$form[0]);

          const handleProgress = function() {
            const xhr = $.ajaxSettings.xhr();
    
            if (xhr.upload) {
              xhr.upload.addEventListener('progress', function(event) {
                if (event.lengthComputable) {
                  const percentage = Math.round((event.loaded * 100) / event.total);
    
                  $('.progress-bar').attr({
                    'aria-valuenow': percentage,
                    'style'        : `width: ${percentage}%`,
                  });
                }
              }, false);
    
              xhr.upload.addEventListener('load', function(e) {
                $('.progress-bar').attr({
                  'aria-valuenow': '100',
                  'style'        : 'width: 100%',
                });
              }, false);
            
              xhr.upload.addEventListener('loadend', function(e) {
                $('.progress-bar').attr({
                  'aria-valuenow': '100',
                  'style'        : 'width: 100%',
                });
    
                $('.progress').fadeOut(1000);
              }, false);        
            }
          
          return xhr;
          };
    
          const uploading = $.ajax({
            cache      : false,
            dataType   : 'json',
            contentType: false,
            processData: false,
            method     : 'POST',
            url        : '/researches/sponsored/middle-page/icon',
            headers    : headers,
            data       : data,
            xhr        : handleProgress,
          });
    
          uploading.done($.proxy(this.onImageUploadSuccess, this));      
          uploading.fail($.proxy(this.onImageUploadFail, this));
        },

        onImageUploadSuccess: function(data) {
          this.$path.val(data.data.path + data.data.name);
          this.$progress.addClass('hidden');
        },
    
        onImageUploadFail: function(error) {
          console.log(error);
        },
  };

  $(function() {
      MiddlePages.start();
  });
})(jQuery);