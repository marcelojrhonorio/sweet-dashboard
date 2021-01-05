(function($) {
    const Notifications = {

        start: function() {

            this.$typeEnv           = $('[data-type-env]');

            this.$btnSave           = $('[data-btn-app-save]');
            this.$btnSend           = $('[data-btn-app-send]');
            this.$btnSendTest       = $('[data-btn-send-test]');
            this.$btnCancelNot      = $('[btn-cancel-notification]');     
            
            this.$btnRefreshTable   = $('[data-refresh-table]');
            
            this.$table             = $('[data-table-app-notification]');   

            this.$typeCode          = $('input[type="radio"][name="codetype"]'); 

            this.$datatable         = null;

            this.bind();
            this.dataTable();        
        },

        bind: function() {
            this.$btnSave.on('click', $.proxy(this.onBtnSaveClick, this));
            this.$btnSend.on('click', $.proxy(this.onBtnSendClick, this));
            this.$btnSendTest.on('click', $.proxy(this.onBtnSendTestClick, this));
            this.$btnCancelNot.on('click', $.proxy(this.onBtnCancelNotificationClick, this));
            this.$btnRefreshTable.on('click', $.proxy(this.onBtnRefreshTableClick, this));
            this.$table.on('click', '[data-btn-cancel]', $.proxy(this.onBtnCancelClick, this));
            this.$table.on('click', '[data-btn-refresh]', $.proxy(this.onBtnRefreshClick, this)); 
            this.$table.on('click', '[data-btn-send]', $.proxy(this.onBtnSendAgainClick, this)); 
            this.$table.on('click', '[data-btn-send-msg]', $.proxy(this.onBtnSendMessageClick, this));             
            this.$typeCode.on('change', $.proxy(this.onTypeCodeClick, this));            
        },       

        dataTable: function() {
            this.$datatable = this.$table.DataTable({
              processing: true,
              serverSide: true,
              pageLength: 25,
              searching: true,
              responsive: true,
              dom: '<"html5buttons"B>lTfgitp',
              buttons: [
                {
                  extend: 'excel',
                  title: 'exportar para excel',
                  text: '<span class="fa fa-file-excel-o"></span> Excel ',            
                },
                {
                  extend: 'pdf',
                  title: 'exportar para pdf',
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
                url: '/app-notification/search',
              },
              columns: [
                {
                  data: 'id',
                },
                {
                  data: 'title',
                },
                {
                  data: 'total',
                },
                {
                  data: 'already_queue',
                }, 
                {
                  data: 'status',     
                },            
                {
                  data: null,
                  width: '5%',
                  render: function(data, type, full, meta) {
                    const btnRefresh = `
                      <button
                        class="btn btn-xs btn-primary"
                        title="Atualizar"
                        type="button"
                        data-btn-refresh
                        data-id="${data.id}"
                        data-title="${data.title}"
                        data-total="${data.total}"
                        data-already_queue="${data.already_queue}"
                        data-status="${data.status}"
                      >
                        <span class="sr-only">Refresh</span>
                        <i class="fas fa-sync-alt" aria-hidden="true"></i>
                      </button>
                    `;

                    const btnEnviar = `
                    <button
                      class="btn btn-xs btn-success"
                      title="Enviar"
                      type="button"
                      data-btn-send-msg
                      data-id="${data.id}"
                      data-title="${data.title}"
                      data-total="${data.total}"
                      data-already_queue="${data.already_queue}"
                      data-status="${data.status}"
                    > 
                      <span class="sr-only">Enviar</span>
                      <i class="fas fa-paper-plane" aria-hidden="true"></i>
                    </button>
                  `;

                  const btnReenviar = `
                    <button
                      class="btn btn-xs btn-success"
                      title="Reenviar"
                      type="button"
                      data-btn-send
                      data-id="${data.id}"
                      data-title="${data.title}"
                      data-total="${data.total}"
                      data-already_queue="${data.already_queue}"
                      data-status="${data.status}"
                    >
                      <span class="sr-only">Reenviar</span>
                      <i class="fas fa-satellite-dish" aria-hidden="true"></i>
                    </button>
                  `;
      
                    const btnCancel = `
                      <button
                        class="btn btn-xs btn-danger"
                        title="Cancelar"
                        type="button"
                        data-btn-cancel
                        data-id="${data.id}"
                      >
                        <span class="sr-only">Cancelar</span>
                        <i class="fa fa-times" aria-hidden="true"></i>                        
                      </button>
                    `;
      
                    const buttons = `${btnEnviar} ${btnCancel}`;
      
                    return buttons;
                  },            
                },
              ],
            })
          },   

        onBtnSaveClick: function(event) {

            var val = this.getValues();

            const token = $('meta[name="csrf-token"]').attr('content');

            const saving = $.ajax({
                method: 'POST',
                url: '/app-notification/create-notification',
                contentType: 'application/json',
                data: JSON.stringify({
                  _token: token,
                  values: val,
                  action: 'save',
                  dataType: 'json',
                }),
            })
  
            saving.done($.proxy(this.onSavingSuccess, this));
            saving.fail($.proxy(this.onSavingFail, this));    
             
        },

        onTypeCodeClick: function(event) {
          
          var type = $('input[type="radio"][name="codetype"]:checked').val();
         
          if(1 == type) {
            $('[box-incentive-email]').removeClass('sr-only');
            $('[box-research-type]').addClass('sr-only');
          } else {
            $('[box-incentive-email]').addClass('sr-only');
            $('[box-research-type]').removeClass('sr-only');
          }

        },

        onBtnSendClick: function(event) {

            var val = this.getValues();
            
            const token = $('meta[name="csrf-token"]').attr('content');

            const saving = $.ajax({
                method: 'POST',
                url: '/app-notification/create-notification',
                contentType: 'application/json',
                data: JSON.stringify({
                  _token: token,
                  values: val,
                  action: 'send',
                  dataType: 'json',
                }),
            })
  
            saving.done($.proxy(this.onSavingSuccess, this));
            saving.fail($.proxy(this.onSavingFail, this)); 
            
        },

        onSavingSuccess: function(data) {
          if(data.success){                
              window.location.href = '/app-notification';
          }
        },
      
        onSavingFail: function(error) {
          console.log(error);
        },

        getValues: function()
        {  
          //Incentive email or research          
          var type = $('input[type="radio"][name="codetype"]:checked').val();
          var url_link;

          if(1 == type) {
            url_link = $('[code-incentive-email]').val();            
          } else {
            url_link = $('[data-research-selected]').val();  
          }

          return {
              'title' : $('[data-title]').val(),
              'type' : type,
              'url_link' : url_link,
              'filter_gender' : $('[filter_gender]').val(),
              'filter_operation_begin' : $('[filter_operation_begin]').val(),
              'filter_age_begin' : $('[filter_age_begin]').val(),
              'filter_operation_end' : $('[filter_operation_end]').val(),
              'filter_age_end' : $('[filter_age_end]').val(),
              'filter_ddd' : $('[filter_ddd]').val(),
              'filter_cep' : $('[filter_cep]').val(),
              'filter_users' : $('[data-filter-users]').val(), 
              'scheduling': $('[data-scheduling]').val(),
          }
        },

        onBtnCancelClick: function (event) {
          event.preventDefault();
    
          const $btn = $(event.currentTarget);
          const id   = $.trim($btn.data('id'));

          const token = $('meta[name="csrf-token"]').attr('content');

          const cancel = $.ajax({
            method: 'POST',
            url: '/app-notification/cancel',
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              id: id,
              dataType: 'json',
            }),
          })

          cancel.done($.proxy(this.onCancelSuccess, this));
          cancel.fail($.proxy(this.onCancelFail, this)); 
        },

        onCancelSuccess: function(data) {
          sweet.common.message('success', 'O envio das mensagens foi cancelado.');
        },
      
        onCancelFail: function(error) {
          console.log(error);
        },

        onBtnRefreshClick: function (event) {
          event.preventDefault();          
    
          const $btn = $(event.currentTarget);
          var id   = $.trim($btn.data('id'));
          
          const token = $('meta[name="csrf-token"]').attr('content');

          const refresh = $.ajax({
            method: 'POST',
            url: '/app-notification/refresh',
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              id: id,
              dataType: 'json',
            }),
          })

          refresh.done($.proxy(this.onRefreshSuccess, this));
          refresh.fail($.proxy(this.onRefreshFail, this)); 
        },

        onRefreshSuccess: function(data) {
          if(data.success){
            var notification = data.data;
            
            var table = $('#table-notification').DataTable();

            /** fix - started notification id in 5 */
            if('production' === this.$typeEnv.val()){
              var data = table.row( (notification.id - 1) - 4 ).data();
            } else {
              var data = table.row( (notification.id - 1) ).data();
            }

            //data.counter++;

            var newData = {
              'id' : notification.id,
              'already_queue' : notification.already_queue,
              'status' : notification.status,
              'title' : notification.title,
              'total' : notification.total,
            };

            //atualiza apenas a linha
            $('#table-notification').dataTable().fnUpdate(newData,(data['id'] - 1),undefined,false);
            
            //atualiza toda table
            //table.row(  data['id'] - 1 ).data( newData ).draw();          
        }
          
        },
      
        onRefreshFail: function(error) {
          console.log(error);
        },

        onBtnSendAgainClick: function (event) {
          event.preventDefault();
    
          const $btn = $(event.currentTarget);
          const id   = $.trim($btn.data('id'));

          /**
           * Pegar todas as 'messages' desta notification que não foram enviadas e reenviar
           */

          const token = $('meta[name="csrf-token"]').attr('content');

          const send_again = $.ajax({
            method: 'POST',
            url: '/app-notification/send-message',
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              id: id,
              action: 'again',
              dataType: 'json',
            }),
          })

          send_again.done($.proxy(this.onSendAgainSuccess, this));
          send_again.fail($.proxy(this.onSendAgainFail, this)); 
        },

        onSendAgainSuccess: function(data) {
          if(data.success){
            sweet.common.message('success', 'Mensagens enviadas novamente! Acompanhe o status da notificação.');
          } else {
            if(data.status === 'canceled'){
              sweet.common.message('error', 'Esta notificação foi cancelada.');
            } else if(data.status === 'sent'){
              sweet.common.message('error', 'Todas as mensagens desta notificação foram enviadas.');
            }
          }          
        },
      
        onSendAgainFail: function(error) {
          console.log(error);
        },

        onBtnSendMessageClick: function(event) {
          event.preventDefault();
    
          const $btn = $(event.currentTarget);
          const id   = $.trim($btn.data('id'));

          const token = $('meta[name="csrf-token"]').attr('content');

          const send_message = $.ajax({
            method: 'POST',
            url: '/app-notification/send-message',
            contentType: 'application/json',
            data: JSON.stringify({
              _token: token,
              id: id,
              action: 'first',
              dataType: 'json',
            }),
          })

          send_message.done($.proxy(this.onSendMessageSuccess, this));
          send_message.fail($.proxy(this.onSendMessageFail, this)); 
        },

        onSendMessageSuccess: function(data) {
          if(data.success){
            sweet.common.message('success', 'Mensagens enviadas! Acompanhe o status da notificação.');
          } else {
            if(data.status === 'canceled'){
              sweet.common.message('error', 'Esta notificação foi cancelada.');
            } else if(data.status === 'sent'){
              sweet.common.message('error', 'Todas as mensagens desta notificação foram enviadas.');
            }            
          }         
        },
      
        onSendMessageFail: function(error) {
          console.log(error);
        },

        onBtnSendTestClick: function(event) {
          event.preventDefault();
          
          var val = this.getValues();
            
          const token = $('meta[name="csrf-token"]').attr('content');

          const send_test = $.ajax({
              method: 'POST',
              url: '/app-notification/create-notification',
              contentType: 'application/json',
              data: JSON.stringify({
                _token: token,
                values: val,
                action: 'test',
                dataType: 'json',
              }),
          })
  
          send_test.done($.proxy(this.onSendTestSuccess, this));
          send_test.fail($.proxy(this.onSendTestFail, this));           

        },

        onSendTestSuccess: function(data) {
          if(data.success){
            sweet.common.message('success', 'Teste enviado com sucesso!');
          }        
        },
      
        onSendTestFail: function(error) {
          console.log(error);
        },

        onBtnCancelNotificationClick: function(event) {
          event.preventDefault();
          
          window.location.href = '/app-notification';
        },

        onBtnRefreshTableClick: function(event){
          event.preventDefault();

          window.location.href = '/app-notification';
        },

    };

    $(function() {
        Notifications.start();
    })
  })(jQuery);