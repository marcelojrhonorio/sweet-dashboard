(function($) {
  const ScreenExchanges = {
    start: function() {
      this.$table = $('[data-table-exchanges]');
      this.$modal = $('[data-modal-exchange]');
      
      this.$datatable = null;
      
      this.dataTable();
      this.bind();
    },

    bind: function() {
      this.$table.on('click', '[data-btn-edit]', $.proxy(this.onBtnEditClick, this));
    },

    dataTable: function() {
      this.$datatable = this.$table.DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        searching: true,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json',
        },
        ajax: {
          url: '/exchanges/search',
          dataSrc: function(json) {
            const parsed = json.data.map(function(item) {
              if (item.created_at) {
                item.created_at = (new Date(item.created_at)).toLocaleDateString() + ' ' 
                  + (new Date(item.created_at)).getHours()
                  + ':'
                  + (new Date(item.created_at)).getMinutes();
              }

              if (item.updated_at) {
                item.updated_at = (new Date(item.updated_at)).toLocaleDateString() + ' ' 
                + (new Date(item.updated_at)).getHours()
                + ':'
                + (new Date(item.updated_at)).getMinutes();;
              }

              return item;
            });

            return parsed;
          },
        },
        columns: [
          {
            data: 'id',
            render: function(data, type, full, meta) {
                data = '<a href="/exchanges/edit/' + data + '">' + data + '</a>';
                return data;
              }
          },
          {
            data: 'customer_id',
          },
          {
            data: 'fullname',
          },
          {
            data: 'email',
          },
          {
            data : null,
            // width: '3%',
            name: 'status',
            targets: [3],
            orderData:3,
            render: function(data, type, full, meta) {
              switch (data.status) {
                case '1':
                  data.status = `
                    <span class="badge badge-secondary">
                      <i class="fas fa-clock"></i> 
                      <strong>1.</strong> Solicitação em análise
                    </span>`;
                  break;
                case '2':
                  data.status = `
                    <span class="badge badge-danger">
                      <i class="fas fa-bomb"></i> 
                      <strong>2.</strong> Fraude
                    </span>`;
                  break;
                case '3':
                  data.status = `
                    <span class="badge badge-info">
                      <i class="fas fa-clipboard-check"></i> 
                      <strong>3.</strong> Confirmação de informações
                    </span>`;
                  break;
                case '4':
                  data.status = `
                    <span class="badge badge-light" style="background-color: #f7008a; color: white;">
                      <i class="fas fa-box-open"></i> 
                      <strong>4.</strong> Produto separado para envio
                    </span>`;
                  break;
                case '5':
                  data.status = `
                    <span class="badge badge-light" style="background-color: #f4f738;">
                      <i class="fas fa-truck"></i> 
                      <strong>5.</strong> Produto em trânsito
                    </span>`;
                  break;
                case '6':
                  data.status = `
                    <span class="badge badge-light" style="background-color: #F77613; color: white;">
                      <i class="fas fa-times"></i> 
                      <strong>6.</strong> Destinatário não encontrado
                    </span>`;                
                  break;
                case '7':
                  data.status = `
                    <span class="badge badge-light" style="background-color: #2cc963; color: white;">
                      <i class="fas fa-check"></i> 
                      <strong>7.</strong> Entrega concluída
                    </span>`;
                  break;
                case '8':
                  data.status = `
                    <span class="badge badge-light" style="background-color: #ff0000; color: white;">
                      <i class="fas fa-ban"></i> 
                      <strong>8.</strong> Troca cancelada
                    </span>`;
                  break;                  
                default:
                  data.status = 'Não informado';
              }

              return data.status;
              
            },
          },          
          {
            data: 'product',
          },
          {
            data:'points',
          },
          {
            data: 'created_at',
            render: function(data, type, row, meta) {
              return data;
            }
          },
          {
            data: 'updated_at',
          },
          {
            data: null,
            width: '1%',
            render: function(data, type, full, meta) {
              const btnEdit = `
                <button
                  class="btn btn-xs btn-primary"
                  title="Editar"
                  type="button"
                  data-btn-edit
                  data-id="${data.id}"
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
      });
    },
    
    search: function() {
      $('.search').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        app.dataSearch = sweet.common.querystringToHash($('#form-search').serialize());
        app.dataTable();
      });
    },

    onBtnEditClick: function(event){
      event.preventDefault();
      event.stopPropagation();

      const $btn = $(event.currentTarget);
      const id   = $.trim($btn.data('id'));

      location.href = laroute.route('exchanges.edit', {'id':id});

    },

  };

  $(function() {
    ScreenExchanges.start();
  })
})(jQuery);