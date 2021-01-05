(function($) { 
  const ScreenPixels = {
    inputs: {},
    
    start: function() {
      this.$pixelModal      =  $('[data-modal-pixels]');
      this.$researchModal   =  $('[data-modal-researches]');
      this.$pixelsTable     =  $('[data-pixels-list]');      
      this.$researchesTable =  $('[data-table-researches]');   

      
      this.$modalTitle    =  this.$pixelModal.find('[data-pixel-title]');
      this.$modalSubtitle =  this.$pixelModal.find('[data-pixel-subtitle]');
      this.$form          =  this.$pixelModal.find('[data-form-pixel]');
      this.$btnRegister   =  this.$pixelModal.find('[data-btn-register]'); 
      this.$btnCancel     =  this.$pixelModal.find('[data-btn-cancel]');

      this.inputs.$id        = this.$form.find('[data-input-id]');
      this.inputs.$affiliate = this.$form.find('[data-input-affiliate]');
      this.inputs.$goal      = this.$form.find('[data-input-goal]');
      this.inputs.$redirect  = this.$form.find('[data-input-redirect]');
      this.inputs.$link      = this.$form.find('[data-input-link]');
      this.inputs.$action    = this.$form.find('[data-input-action]');

      this.inputs.$type      = this.$form.find('[data-input-type]');
      this.inputs.$completed = this.$form.find('[data-input-type-completed]');
      this.inputs.$quotafull = this.$form.find('[data-input-type-quotafull]');
      this.inputs.$filtered  = this.$form.find('[data-input-type-filtered]');

      this.bind();    

    },

    bind: function() {
      this.$form.on('submit', $.proxy(this.onFormSubmit, this));
      this.$pixelModal.on('hide.bs.modal', $.proxy(this.onHideModal, this));
      this.inputs.$redirect.on('change', $.proxy(this.onHasRedirectClick, this));
      this.$researchesTable.on('click', '[data-btn-pixel]', $.proxy(this.onBtnPixelClick, this));
      this.$pixelsTable.on('click', '[data-pixel-edit]', $.proxy(this.onBtnPixelEditClick, this));
      this.$pixelsTable.on('click', '[data-pixel-delete]', $.proxy(this.onBtnPixelDestroy, this));
      this.inputs.$type.on('change', $.proxy(this.onCompletedClick, this));

    },

    onCompletedClick: function(event){
      if (this.inputs.$completed.prop('selected')){
        this.inputs.$goal.prop('disabled', true);
        this.inputs.$goal.val('');
      }
      if (this.inputs.$quotafull.prop('selected')){
        this.inputs.$goal.prop('disabled', false);
      }
      if (this.inputs.$filtered.prop('selected')){
        this.inputs.$goal.prop('disabled', false);
      }    

    },    

    pixelsTableRender: function(id){
      $.ajax({
        url      : `/researches/pixel/search/${id}`,
        datatype : `json`,
        success  : function(data){
          $('[data-pixels-list] tr').not(':first').remove();
          var html = '';
          for(var i = 0; i < data.length; i++){
            var type = data[i].type;
            var link = data[i].link_redirect; 
            var redirect = data[i].has_redirect; 
            var goal = data[i].goal_id;          
            if(0 == data[i].has_redirect){
              link = '----'
              redirect = 'Não'
            }
            if(1 == data[i].has_redirect){
              redirect = 'Sim'
            }
            if(1 == data[i].type){
              type = 'Completa'
            }
            if(2 == data[i].type){
              type = 'Quota Full'
            }
            if(3 == data[i].type){
              type = 'Filtrada'
            }      
            if ('' == data[i].goal_id){
              goal = '--'
              data[i].goal_id = '0' 
            };                  
            html += 
              `<tr>` + 
                `<td>` + data[i].id + `</td>` +
                `<td>` + data[i].affiliate_id + `</td>` +
                `<td>` + type + `</td>` +
                `<td>` + goal + `</td>` +
                `<td>` + redirect + `</td>` +
                `<td>` + link + `</td>` +
                `<td> 
                  <button 
                    class="btn btn-xs btn-primary"
                    data-pixel-edit
                    data-pixel =` + data[i].id + `
                    data-research =` + data[i].research_id + `
                    data-affiliate =` + data[i].affiliate_id + `
                    data-type =` + data[i].type + `
                    data-goal =` + data[i].goal_id + `
                    data-redirect =` + data[i].has_redirect + `
                    data-link =` + data[i].link_redirect + `
                  >
                    <span class="sr-only">Editar Pixel</span>
                    <i class="fas fa-pen" aria-hidden="true"></i>
                  </button>                        
                  <button 
                    class="btn btn-xs btn-danger"
                    data-pixel-delete     
                    data-pixel =` + data[i].id + `        
                  >
                    <span class="sr-only">Excluir Pixel</span>
                    <i class="fa fa-trash" aria-hidden="true"></i>
                  </button>                         
                </td>` +
              `</tr>`;
          }
          $('[data-pixels-list] tr').first().after(html);
        }
      }); 
    },

    onBtnPixelClick: function(event) {
      event.preventDefault();  
      
      const $btn      = $(event.currentTarget);
      const id        = $.trim($btn.data('id'));
      const research  = $.trim($btn.data('title'));
      const hasoffers = $.trim($btn.data('hasoffers'));

      this.inputs.$id.val(id);
      this.$btnRegister.text('Cadastrar');
      this.inputs.$action.val('create');
      this.$modalTitle.text('Cadastro de Pixel');

      this.$modalSubtitle.text('Pesquisa: ' + research + ' | Hasoffers: ' + hasoffers);
      
      this.inputs.$link.prop('disabled', true); 
      
      this.pixelsTableRender(id);

      $('[data-pixels-list]').show();      

      this.$pixelModal.modal('show');       
      
    },

    onHasRedirectClick: function() {
      if (this.inputs.$redirect.prop('checked')){
        this.inputs.$link.prop('disabled', false);
        this.inputs.$redirect.val(1);
      } else{
        this.inputs.$link.prop('disabled', true);
        this.inputs.$link.val('');
        this.inputs.$redirect.val(0);
      }

    },

    onFormSubmit: function(event) {
      event.preventDefault();

      if (
        '' === $.trim(this.inputs.$id.val())        ||
        '' === $.trim(this.inputs.$affiliate.val()) ||
        '' === $.trim(this.inputs.$type.val())      
      ) {
        sweet.common.message('error', 'Há campos obrigatórios não preenchidos');
        return;
      }

      if (
        false === this.inputs.$completed.prop('selected') &&
        false === this.inputs.$quotafull.prop('selected') &&
        false === this.inputs.$filtered.prop('selected')
      ) {
        sweet.common.message('error', 'Selecione um tipo de pixel');
        return;
      }

      if (
        this.inputs.$redirect.prop('checked') && 
        this.inputs.$link.val() == ''
      ) {
        sweet.common.message('error', 'Informe a URL de redirect');
        return;
      }

      if ((this.inputs.$quotafull.prop('selected') ||
          this.inputs.$filtered.prop('selected'))  &&
          this.inputs.$goal.val() == ''
      ){
        sweet.common.message('error', 'Informe o goal id');
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

      }      

    },

    onCreateSubmit: function() {
      pixelType = 0;

      if (this.inputs.$completed.prop('selected')){
        pixelType = 1;
      }

      if (this.inputs.$quotafull.prop('selected')){
        pixelType = 2;
      }

      if (this.inputs.$filtered.prop('selected')){
        pixelType = 3;
      }

      const params = {
        research_id   : this.inputs.$id.val(),
        affiliate_id  : this.inputs.$affiliate.val(),
        type          : pixelType,
        goal_id       : this.inputs.$goal.val(),
        has_redirect  : this.inputs.$redirect.val(),
        link_redirect : this.inputs.$link.val(),
      };

      const saving = sweet.common.crud.save({
        params  : params,
        endpoint: '/researches/pixel',
      });

      saving.done($.proxy(this.onCreateSuccess, this));

      saving.fail($.proxy(this.onCreateFail, this));

    },    

    onCreateSuccess: function(data) {
      if (data.success) {
        
        this.pixelsTableRender(data.data.research_id);      

        this.$form[0].reset();

        sweet.common.message('success', 'Dados cadastrados com sucesso!');
      }

    },

    onCreateFail: function(error) {
      console.log('Failed to CREATE pixel to the research: ', error);

    },    

    onBtnPixelEditClick: function(event) {
      event.preventDefault();

      $('[data-pixels-list]').hide();

      this.$modalTitle.text('Editar Pixel');
      this.$btnRegister.text('Editar');
      this.inputs.$action.val('update');

      const $btn      = $(event.currentTarget);
      const id        = $.trim($btn.data('pixel'));
      const affiliate = $.trim($btn.data('affiliate'));
      const type      = $.trim($btn.data('type'));
      const goal      = $.trim($btn.data('goal'));
      const redirect  = $.trim($btn.data('redirect'));
      const link      = $.trim($btn.data('link'));

      this.inputs.$id.val(id);
      this.inputs.$affiliate.val(affiliate);
      this.inputs.$type.val(type);
      this.inputs.$goal.val(goal);

      if(1 == type){
        this.inputs.$completed.prop('selected', true);
        this.inputs.$goal.prop('disabled', true);
        this.inputs.$goal.val('');
      }
      if(2 == type){
        this.inputs.$quotafull.prop('selected', true);
        this.inputs.$goal.prop('disabled', false);
      }
      if(3 == type){
        this.inputs.$filtered.prop('selected', true);
        this.inputs.$goal.prop('disabled', false);
      }      
      if(1 == redirect) {
        this.inputs.$redirect.prop('checked', true);
        this.inputs.$link.prop('disabled', false);
        this.inputs.$link.val(link);
      }
      if(0 == redirect){
        this.inputs.$redirect.prop('checked', false);
        this.inputs.$link.prop('disabled', true);
      }

      this.$researchModal.modal('hide');
      this.$pixelModal.modal('show');

    },

    onBtnPixelDestroy: function(event) {
      event.preventDefault();

      const id    = $(event.currentTarget).data('pixel');
      const token = $('meta[name="csrf-token"]').attr('content');

      const destroying = $.ajax({
        cache  : false,
        method : 'POST',
        url    : `/researches/pixel/${id}`,
        data   : {
          _method: 'delete',
          _token : token,
        },
      });

      destroying.done($.proxy(this.onDestroySuccess, this));

      destroying.fail($.proxy(this.onDestroyFail, this));

    },

    onDestroySuccess: function(data) {
      this.pixelsTableRender(data.data.research_id);      
      if (data.success) {
        this.$researchModal.modal('hide');
        sweet.common.message('success', 'Dados excluídos com sucesso!');
      }
    },

    onDestroyFail: function(error) {
      console.log('Failed to DESTROY pixel: ', error);
    },    
    
    onUpdateSubmit: function() {
      pixelType = 0;

      if (this.inputs.$completed.prop('selected')){
        pixelType = 1;
      }

      if (this.inputs.$quotafull.prop('selected')){
        pixelType = 2;
      }

      if (this.inputs.$filtered.prop('selected')){
        pixelType = 3;
      }

      const params = {
        _method     : 'put',
        _token      : $('meta[name="csrf-token"]').attr('content'),
        id            : this.inputs.$id.val(),
        research_id   : this.inputs.$id.val(),
        affiliate_id  : this.inputs.$affiliate.val(),
        type          : pixelType,
        goal_id       : this.inputs.$goal.val(),
        has_redirect  : this.inputs.$redirect.val(),
        link_redirect : this.inputs.$link.val(),
      };

      const saving = sweet.common.crud.save({
        params  : params,
        endpoint: `/researches/pixel/${params.id}`,
      });      

      saving.done($.proxy(this.onUpdateSuccess, this));

      saving.fail($.proxy(this.onUpdateFail, this));      

    },    

    onUpdateSuccess: function(data) {
      if (data.success) {  
        this.pixelsTableRender(data.data.research_id);              
        
        $('[data-pixels-list]').show(); 
        
        this.$modalTitle.text('Cadastro de Pixel');
        
        this.$form[0].reset();

        sweet.common.message('success', 'Dados atualizados com sucesso!');
      }
    },

    onUpdateFail: function(error) {
      console.log('Failed to UPDATE pixel: ', error);
    },    

    onHideModal: function() {
      this.$form[0].reset();
    },    

  };

  $(function() {
    ScreenPixels.start();
  });
})(jQuery);