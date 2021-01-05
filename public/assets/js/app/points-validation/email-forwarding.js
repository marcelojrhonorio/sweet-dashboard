(function($) {
    const EmailForwarding = {

        DataTable: null,

        start: function() {
            this.$table           = $('[data-table-email-forwarding]');
            this.$tableValidation = $('[data-table-validation-email]');

            this.$modalPrints     = $('[data-modal-prints]');
           
            this.bind();
            this.dataTable();          
        },

        bind: function() {                  
            this.$table.on('click', '[data-btn-edit]', $.proxy(this.onBtnEditClick, this)); 
            this.$tableValidation.on('click', '[data-prints-forwarding]', $.proxy(this.onBtnPrintsClick, this)); 
            this.$tableValidation.on('click', '[data-btn-forwarding-ok]', $.proxy(this.onBtnForwardingOKClick, this)); 
            this.$tableValidation.on('click', '[data-btn-forwarding-not]', $.proxy(this.onBtnForwardingNotOkClick, this)); 
            this.$modalPrints.on('hide.bs.modal', $.proxy(this.onHideModal, this));
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
                url: '/points-validation/email-forwarding/search',
              },
              columns: [
                {
                  data : 'id',
                  width: '3%',    
                },
                {
                  data : 'name',
                  width: '3%',    
                },
                {
                  data : 'email',  
                  width: '3%',                 
                },
                {
                  data : null,
                  width: '3%',
                  name: 'status',
                  targets: [3],
                  orderData:3,
                  render: function(data, type, full, meta) {                   
                    switch (data.status) {
                      case '0':
                        data.status = `
                        <span class="badge badge-light" style="background-color: #ff0000; color: white;">
                            <i class="fas fa-clock"></i> 
                            Há solicitações a serem verificadas
                          </span>`;
                        break;
                      case '1':
                        data.status = `
                        <span class="badge badge-light" style="background-color: #2cc963; color: white;">
                          <i class="fas fa-check"></i> 
                          Todas as solicitações já foram verificadas
                        </span>`;
                        break;              
                      default:
                        data.status = 'Não informado';
                    }
      
                    return data.status;
                    
                  },
                },
                {
                  data : null,
                  width: '1%',
                  render: function(data, type, full, meta) {
                    const btnEdit = `
                      <button
                        class="btn btn-xs btn-primary"
                        title="Editar"
                        type="button"
                        data-btn-edit
                        data-id="${data.id}"
                        data-customer="${data.customers_id}"
                      >
                        <span class="sr-only">Editar</span>
                        <i class="fas fa-pen" aria-hidden="true"></i>
                      </button>
                    `;
      
                    const buttons = `${btnEdit}`;
      
                    return buttons;
                  },
                },
              ],
            });
          }, 

          onBtnEditClick: function (event) {
            event.preventDefault();
  
            const $btn = $(event.currentTarget);
            const id = $.trim($btn.data('id'));
            const customer = $.trim($btn.data('customer'));
            
            window.location = '/points-validation/email-forwarding/edit/' + customer;  
          },

          onBtnPrintsClick(event) {
            event.preventDefault();

            const $btn = $(event.currentTarget);
            const prints = $.trim($btn.data('print'));

            var print = prints.split('|');

            for (let index = 0; index < print.length; index++) 
            {               
                if(print[index]) {
                  this.$modalPrints.modal('show');
                  $('#email-forwarding-prints').append($('<img />').attr('src', $('[data-store-url]').val() + '/storage/' + print[index]))
                }
            }
          },

          onHideModal(event) {
            $('#email-forwarding-prints').children('img').remove();
          },

          onBtnForwardingOKClick: function (event) {
            event.preventDefault();

            const $btn = $(event.currentTarget);
            const id = $.trim($btn.data('id'));
            const customers_id = $.trim($btn.data('customer'));
            
            $btn[0].nextElementSibling.classList.value = 'btn btn-xs btn-danger ok-selected';
            $btn[0].classList.value = 'btn btn-xs btn-primary';

            const token = $('meta[name="csrf-token"]').attr('content');

            const savingOk = $.ajax({
                method: 'POST',
                url: '/points-validation/email-forwarding/forwarding-ok',
                contentType: 'application/json',
                data: JSON.stringify({
                  _token: token,
                  id : id,
                  customers_id : customers_id,
                  dataType: 'json',
                }),
            })
        
            savingOk.done($.proxy(this.onForwardingOKSuccess, this));        
            savingOk.fail($.proxy(this.onForwardingOKFail, this));    
            
          },

          onForwardingOKSuccess: function(data) {
            if(data.success){
              sweet.common.message('success', 'Encaminhamento feito para ' + data.data.name + ' validado.');
            } else {
              sweet.common.message('error', 'Encaminhamento feito para ' + data.data.name + ' já foi verificado.');
            }
          },

          onForwardingOKFail: function(error) {
            console.log(error);
          },

          onBtnForwardingNotOkClick: function (event) {
            event.preventDefault();

            const $btn = $(event.currentTarget);
            const id = $.trim($btn.data('id'));
            const customers_id = $.trim($btn.data('customer'));

            $btn[0].previousElementSibling.classList.value = 'btn btn-xs btn-primary not-selected';
            $btn[0].classList.value = 'btn btn-xs btn-danger';

            const token = $('meta[name="csrf-token"]').attr('content');

            const savingNot = $.ajax({
                method: 'POST',
                url: '/points-validation/email-forwarding/forwarding-not',
                contentType: 'application/json',
                data: JSON.stringify({
                  _token: token,
                  id : id,
                  customers_id : customers_id,
                  dataType: 'json',
                }),
            })
        
            savingNot.done($.proxy(this.onForwardingNotSuccess, this));        
            savingNot.fail($.proxy(this.onForwardingNotFail, this)); 
            
          },

          onForwardingNotSuccess: function(data) {
            if(data.success){
              sweet.common.message('success', 'Encaminhamento feito para ' + data.data.name + ' invalidado.');
            } else {
              sweet.common.message('error', 'Encaminhamento feito para ' + data.data.name + ' já foi verificado.');
            }
          },

          onForwardingNotFail: function(error) {
            console.log(error);
          },
};

$(function() {
    EmailForwarding.start();
});
})(jQuery);