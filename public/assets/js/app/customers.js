(function($) {
  const ScreenCustomers = {
    inputs: {},

    DataTable: null,

    start: function() {
      this.$table = $('[data-customers-list]');
      this.$statusIndication = $('[data-status-change]');
      
      this.bind();
      this.dataTable();
      this.$modalPoints              = $('[data-modal-points]');
      //this.search();
      //this.clear();
    },

    bind: function(){
      this.$table.on('click', '[data-btn-destroy]', $.proxy(this.onBtnDestroyClick, this));
      this.$table.on('click', '[data-btn-passwd]' , $.proxy(this.onBtnPasswdClick, this));
      this.$statusIndication.on('change', $.proxy(this.onStatusChange, this));      
      this.$table.on('click', '[data-btn-points]' , $.proxy(this.onBtnPoints, this));
    },

    dataTable: function() {
      this.DataTable = this.$table.DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        searching: true,
        responsive: true,
        order:[[3,"desc"]],
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
          {
            text: '<span class="fa fa-file-excel-o"></span> Excel ',
            action: function ( e, dt, button, config ) {
              window.location = '/customers/export';
            }
          },
          {
            extend: 'print',
            text: '<span class="fa fa-print"></span> Imprimir ',
            customize: function (win){
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');

                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
            },
          },
        ],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json',
        },
        ajax: {
          url: '/customers/search',
          dataSrc: function(json) {
            const parsed = json.data.map(function(item) {
              if (item.birthdate) {
                item.birthdate = item.birthdate.split('-').reverse().join('/');
              }

              if (item.gender) {
                item.gender = item.gender === 'M' ? 'Homem' : 'Mulher';
              }

              item.confirmed = item.confirmed ? 'Sim' : 'Não';

              if (item.created_at) {
                item.created_at = (new Date(item.created_at)).toLocaleDateString();
              }

              return item;
            });

            return parsed;
          },          
        },
        columns: [
          {
            data : 'id',
          },
          {
            data : 'fullname',
          },
          {
            data : 'email',
          },
          {
            data : 'cpf',
          },          
          {
            data : null,
            // width: '3%',
            name: 'points',
            targets: [3],
            orderData:3,
            render: function(data, type, full, meta) {
              const btnDestroy = `
                <button
                  class="btn btn-xs btn-success"
                  title="Visualizar"
                  type="button"
                  data-btn-points
                  data-id="${data.id}"
                  data-points="${data.points}"
                >
                  <span> ${ data.points?data.points:0 }</span>
                  <i class="fa fa-star" aria-hidden="true"></i>
                </button>
              `;

              const buttons = `${btnDestroy}`;

              return buttons;
            },
          },
          {
            data : 'gender',
          },
          {
            data : 'birthdate',
          },
          {
            data : 'state',
          },
          {
            data : 'city',
          },
          {
            data : 'phone_number',
          },
          {
            data : 'confirmed',
          },
          {
            data : 'created_at',
          },
          {
            data: null,
            width: '6%',
            render: function(data, type, full, meta) {
              const btnIndications = `
                <a
                  class="btn btn-xs btn-success"
                  title="Mostrar indicações"
                  type="button"
                  data-btn-indications
                  data-id="${data.id}"
                  href="/customers/indications/${data.id}/1"
                  target="_blank"
                >
                  <span class="sr-only">Indicações</span>
                  <i class="far fa-handshake" aria-hidden="true"></i>
                </a>
              `;

              const btnEdit = `
                <button
                  class="btn btn-xs btn-info"
                  title="Resetar senha"
                  type="button"
                  data-btn-passwd
                  data-id="${data.id}"
                >
                  <span class="sr-only">Resetar Senha</span>
                  <i class="fas fa-key" aria-hidden="true"></i>
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

              const buttons =  `${btnIndications} ${btnEdit} ${btnDestroy}`;

              return buttons;
            },            
          }, 
        ],
        initComplete: function () {
          this.api().columns([1,2,3,4,5,6,7,8,9]).every(function () {
              var column = this;
              var input = document.createElement("input");
              $(input).appendTo($(column.footer()))
              .on('change', function () {
                  column.search($(this).val(), false, false, true).draw();
              });
          });
      }             
      })
    },

    search: function() {
      $('.search').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        app.dataSearch = sweet.common.querystringToHash($('#form-search').serialize());
        app.dataTable();
      });
    },
    onBtnPoints: function(event){
      event.preventDefault();
      const $btn      = $(event.currentTarget);
      const id        = $.trim($btn.data('id'));
      const points        = $.trim($btn.data('points'));
      this.pointsTableRender(id,points);
      this.$modalPoints.modal('show'); 
    },
    pointsTableRender:function(id,pointsUser){
      $.ajax({
        url      : `/customers/points/list/${id}`,
        datatype : `json`,
        success  : function(data){
          $('[data-points-list] tr').not(':first').remove();
          var html = '';
          var somaTotal = 0;
          var pontosSubtraidos = 0;
          var saldoTotal = 0;
          for(var i = 0; i < data.length; i++){
                var totalPoints = (data[i].TOTAL_PONTOS?data[i].TOTAL_PONTOS:0);
                var description = (data[i].description);
                var qtde = data[i].QTDE ? data[i].QTDE : 0;
                var sinal = '';

                sinal = (((description.includes('Troca de Pontos')) || (description.includes('Pontos Expirados'))) ? '-' : '+');

                html +=
                `<tr>` +
                `<td >` + (description) + `</td>` +
                `<td style="text-align: right">` + (qtde) + `</td>` +
                 `<td style="text-align: right">` + `(`+sinal+`) ` + (totalPoints) + `</td>` +
                `</tr>`;
                somaTotal+=(1*(data[i].TOTAL_PONTOS ? parseInt(data[i].TOTAL_PONTOS) : 0));
          }
          
          var exchangedPoints = (data[data.length-2].TOTAL_PONTOS) ? parseInt(data[data.length-2].TOTAL_PONTOS) : 0;
          var expiredPoints   = (data[data.length-1].TOTAL_PONTOS) ? parseInt(data[data.length-1].TOTAL_PONTOS) : 0;

          pontosSubtraidos = (parseInt(exchangedPoints) + parseInt(expiredPoints)); 

          saldoTotal = (somaTotal - pontosSubtraidos);

          somaTotal = (parseInt(somaTotal)-parseInt(2 * pontosSubtraidos));

          html += `<tr>` +
              `<td colspan="2" style="font-weight: bold">Pontos Conquistados</td>` +
              `<td style="font-weight: bold;color: #1eacae;text-align: right">` +(parseInt(saldoTotal))+ `</td>` +
              `</tr>`;
          
          html += `<tr>` +
              `<td colspan="2" style="font-weight: bold">Saldo Final</td>` +
              `<td style="font-weight: bold;color: #1eacae;text-align: right">` +(parseInt(somaTotal))+ `</td>` +
              `</tr>`;

          html += `<tr>` +
              `<td colspan="2" style="font-weight: bold">Divergência</td>` +
              `<td style="font-weight: bold;color:#550000;text-align: right">` +(parseInt(pointsUser)-parseInt(somaTotal))+ `</td>` +
              `</tr>`;

          html += `<tr>` +

              `<td colspan="2" style="font-weight: bold">Pontos Visualizados pelo Usuário</td>` +
              `<td style="font-weight: bold;color:#0e9aef;text-align: right">` +(pointsUser)+ `</td>` +
              `</tr>`;
          $('[data-points-list] tr').first().after(html);
        }
      });
    },
    clear: function() {
      $('.clear').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        global.location.reload();
      });
    },

    onBtnDestroyClick: function (event) {
      event.preventDefault();

      const id      = $(event.currentTarget).data('id');
      const token   = $('meta[name="csrf-token"]').attr('content');
      
      const destroying = $.ajax({
        cache  : false,
        method : 'POST',
        url    : `/customers/${id}`,
        data   : {
          _method: 'delete',
          _token : token,
        },
      });
      
      destroying.done($.proxy(this.onDestroySuccess, this));

      destroying.fail($.proxy(this.onDestroyFail, this));        
    },

    onDestroySuccess: function(data) {
      if (data.success) {
        this.DataTable.ajax.reload().desc;
        sweet.common.message('success', 'Dados excluídos com sucesso!');
      }
    },
    
    onDestroyFail: function(error) {
      console.log('Failed to DESTROY research: ', error);
    },    

    onStatusChange: function(event) {
      event.preventDefault();

      const token   = $('meta[name="csrf-token"]').attr('content');
      const $btn = $(event.currentTarget);
      const customer_id   = $.trim($btn.data('id'));
      const status = $btn[0].value;

      const updateStatusIndications = $.ajax({
        method: 'POST',
        url: '/customers/update-status-indications',
        contentType: 'application/json',
        data: JSON.stringify({
          _token: token,
          customer_id: customer_id,
          status: status,
          dataType: 'json',
        }),
      })

      //updateStatusIndications.done($.proxy(this.onUpdateStatusIndicationsSuccess, this));
      updateStatusIndications.fail($.proxy(this.onUpdateStatusIndicationsFail, this));

    },

    onUpdateStatusIndicationsSuccess: function (data) {
      
    },

    onUpdateStatusIndicationsFail: function (error) {
      console.log(error);
    },

    onBtnPasswdClick: function(event) {
      event.preventDefault();

      const id    = $(event.currentTarget).data('id');
      const token = $('meta[name="csrf-token"]').attr('content');

      const updatingPassword = $.ajax({
        cache  : false,
        method : 'POST',
        url    : `/customers/reset-password/${id}`,
        data   : {
          _method: 'post',
          _token : token,
        },
      });

      updatingPassword.done($.proxy(this.onUpdatePasswordSuccess, this));

      updatingPassword.fail($.proxy(this.onUpdatePasswordFail, this));
    },

    onUpdatePasswordSuccess: function (data) {
      this.DataTable.ajax.reload().desc;
    },

    onUpdatePasswordFail: function (error) {
      console.log(error);
    },

  };

  $(function() {
    ScreenCustomers.start();
  });
})(jQuery);