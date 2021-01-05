(function($) {
  const ScreenExchangesEdit = {
    start: function() {
      
      //exchange data
      this.$id                      =   $('[data-exchange-id]');
      this.$requestedAt             =   $('[data-exchange-requested-at]');

      this.$points                  =   $('[data-exchange-points]');
      this.$product                 =   $('[data-exchange-product]');

      this.$productGroup            =   $('[data-product-group]');

      this.$status                  =   $('[data-delivery-status]');
      
      this.$trackingLabel           =   $('[data-delivery-tracking-label]');
      this.$tracking                =   $('[data-delivery-tracking]');
      this.$deliveryForecastLabel   =   $('[data-delivery-forecast-label]');
      this.$deliveryForecast        =   $('[data-delivery-forecast]');
      
      this.$address                 =   $('[data-delivery-address]');
      this.$number                  =   $('[data-delivery-number]');
      this.$reference               =   $('[data-delivery-reference-point]');
      this.$neighborhood            =   $('[data-delivery-neighborhood]');
      this.$city                    =   $('[data-delivery-city]');
      this.$state                   =   $('[data-delivery-state]');
      this.$cep                     =   $('[data-delivery-postal-code]');
      this.$additionalInformation   =   $('[data-delivery-additional-infomation]');
      
      //customer data
      this.$customerData         =   $('[data-customer-data]');
      this.$customerHistory      =   $('[data-customer-history]');
      this.$customerIndications  =   $('[data-customer-indications]');

      this.$customerDataGroup    =  $('[data-customer-data-group]');
      this.$customerHIstoryGroup =  $('[data-customer-history-group]');
      this.$customerIndicationsGoup =  $('[data-indications]');

      this.$customerId        =   $('[data-customer-id]');
      this.$customerFullname  =   $('[data-customer-fullname]');
      this.$customerEmail     =   $('[data-customer-email]');
      this.$customerDdd       =   $('[data-customer-phone-number-ddd]');      
      this.$customerPhone     =   $('[data-customer-phone-number]');
      this.$customerPoints    =   $('[data-customer-points]');

      //product data
      this.$productPoints = $('[data-product-points]');
      this.$productId     = $('[data-product-id]');

      //form data
      this.$btn  = $('[data-form-submit]');
      this.$form = $('[data-exchange-edit-form]');

      this.bind();
      this.applyMasks();
      this.behaviorByStatus();

    },

    bind: function() {
      this.$form.on('submit', $.proxy(this.onFormSubmit, this));
      this.$product.on('change', $.proxy(this.onEditProduct, this));
      this.$status.on('change', $.proxy(this.onEditStatus, this));
      this.$customerData.on('click', $.proxy(this.onCustomerDataClick, this));
      this.$customerHistory.on('click', $.proxy(this.onCustomerHistoryClick, this));
      this.$customerIndications.on('click', $.proxy(this.onCustomerIndicationsClick, this));

    },

    applyMasks: function () {
      this.$cep.mask('00.000-000');
      this.$customerDdd.mask('00');
      this.$customerPhone.mask('00000-0000');
      this.$deliveryForecast.mask('00/00/0000');

    },

    behaviorByStatus: function () {

      const status = this.$status.val();

      if (
        status == 5 ||
        status == 6 ||
        status == 7
      ) {
        this.$trackingLabel.removeClass('sr-only');
        this.$tracking.removeClass('sr-only');
        this.$deliveryForecastLabel.removeClass('sr-only');
        this.$deliveryForecast.removeClass('sr-only');

      } else if (status == 8) {
        this.$productGroup.addClass('sr-only');

      } else {
        this.$productGroup.removeClass('sr-only');
        this.$trackingLabel.addClass('sr-only');
        this.$tracking.addClass('sr-only');
        this.$deliveryForecastLabel.addClass('sr-only');
        this.$deliveryForecast.addClass('sr-only');
      }
      
    },

    onFormSubmit: function(event) {
      event.preventDefault();

      if (
        '' == this.$product.val()      ||
        '' == this.$status.val()       ||
        '' == this.$address.val()      ||
        '' == this.$number.val()       ||
        '' == this.$reference.val()    ||
        '' == this.$neighborhood.val() ||
        '' == this.$city.val()         ||
        '' == this.$state.val()        ||
        '' == this.$cep.val()          ||
        '' == this.$customerDdd.val()  ||
        '' == this.$customerPhone      
      ) {
        swal({
          title: "Oops?",
          text: "Pode haver campos obrigatórios não preenchidos!",
          icon: "error",
          buttons: true,
          dangerMode: true,
        });

        return;

      }

      swal({
        title: "Tem certeza?",
        text: "Você está prestes à alterar a solicitação de troca de pontos.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {

          const id = this.$id.val();

          $.ajax({
            url: `/exchanges/${id}`,
            type: "put",
            data: this.getValues(),
            dataType: "html",
            success: function () {
              swal("Pronto! Troca de pontos atualizada com sucesso!", {
                icon: "success",
              }).then(function () {
                location.reload();
              });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                swal("Oops!", "Aconteceu um erro!", "error");
            }
          });

        } else {
          swal("A atualização de troca de pontos foi cancelada!");
        }
      });

    },

    onEditProduct: function(event) {
      event.preventDefault();

      const $dropDown = $(event.currentTarget);
      const selectedProductPoints = parseInt($dropDown[0].selectedOptions[0].attributes[1].nodeValue);

      const customerPoints = parseInt(this.$customerPoints.val());
      const exchangePoints = parseInt(this.$productPoints.val());
      const beforeExchange = customerPoints + exchangePoints;

      if ((beforeExchange - selectedProductPoints) >= 0) {
        
        swal({
          title: "Você tem certeza?",
          text: "Essa alteração cancelará a troca de pontos anterior, devolvendo " + exchangePoints + " pontos ao cliente para que seja substituído pelo novo produto, que vale " + selectedProductPoints + " pontos.",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            this.$points.val(selectedProductPoints);
            this.$productId.val(this.$product.val());

            const exchangeId = this.$id.val();
            const selectedProductId = this.$product.val();

            $.ajax({
              url: `/exchanges/update-product/`,
              type: "get",
              data: {
                exchange_id         : exchangeId,
                selected_product_id : selectedProductId 
              },
              dataType: "html",
              success: function () {
                swal("Pronto! O produto foi alterado com sucesso!", {
                  icon: "success",
                }).then(function() {
                  location.reload();
                });
              },
              error: function (xhr, ajaxOptions, thrownError) {
                  swal("Oops!", "Aconteceu um erro!", "error").then(function () {
                    location.reload();
                  });
              }
            });

          } else {
            swal("A troca do produto foi cancelada!").then(function () {
              location.reload();
            });
          }
        });

      } else {
        swal("Oops! O usuário não tem pontuação suficiente para essa troca!", {
          icon: "error",
        }).then(function () {
          location.reload();
        });
      }

    },

    onEditStatus: function(event) {
      event.preventDefault();

      const 
        $dropDown = $(event.currentTarget),
        selectedStatus = parseInt($dropDown[0].selectedOptions[0].attributes[0].nodeValue),
        points = this.$points.val(),
        text = (8 == selectedStatus) ? 'Essa ação devolverá os '  + points + ' pontos para o usuário e cancelará a troca' : 'Você está prestes a alterar o status da troca de pontos. Confirma essa alteração?',
        exchangeId = this.$id.val();
      
      swal({
        title: "Você tem certeza?",
        text: text,
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url: '/exchanges/update-status',
            type: "POST",
            data: {
              _token : $('meta[name="csrf-token"]').attr('content'),
              exchange_id : exchangeId,
              selected_status_id : selectedStatus,
            },
            dataType: "html",
            success: function () {
              swal("Pronto! O status foi alterado com sucesso!", {
                icon: "success",
              })
              .then(function() {
                location.reload();
              });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                swal("Oops!", "Aconteceu um erro!", "error")
                .then(function () {
                  location.reload();
                });
            }
          });

        } else {
          swal("A troca de status foi cancelada!")
          .then(function () {
            location.reload();
          });
        }
      });

    },

    getValues: function() {
      return {
        _token                  :  $('meta[name="csrf-token"]').attr('content'),
        id                      :  this.$id.val(),
        requested_at            :  this.$requestedAt.val(),
        points                  :  this.$points.val(),
        product_id              :  this.$product.val(),
        status_id               :  this.$status.val(),
        tracking_code           :  this.$tracking.val(),
        delivery_forecast       :  this.$deliveryForecast.val(),
        address                 :  this.$address.val(),
        number                  :  this.$number.val(),
        reference               :  this.$reference.val(),
        neighborhood            :  this.$neighborhood.val(),
        city                    :  this.$city.val(),
        state                   :  this.$state.val(),
        cep                     :  this.$cep.val(),
        additional_information  :  this.$additionalInformation.val(),
        customer_id             :  this.$customerId.val(),
        customer_fullname       :  this.$customerFullname.val(),
        customer_email          :  this.$customerEmail.val(),
        customer_ddd            :  this.$customerDdd.val(),
        customer_phone          :  this.$customerPhone.val(),
      };
    },

    onCustomerDataClick: function (event) {
      event.preventDefault();

      this.$customerData.addClass('active');
      this.$customerHistory.removeClass('active');
      this.$customerIndications.removeClass('active');

      this.$customerDataGroup.removeClass('sr-only');
      this.$customerHIstoryGroup.addClass('sr-only');
      this.$customerIndicationsGoup.addClass('sr-only');

    },

    onCustomerIndicationsClick: function(event)  {
      event.preventDefault();

      const customerId = this.$customerId.val();
      
      this.$customerHistory.removeClass('active');
      this.$customerData.removeClass('active');
      this.$customerIndications.addClass('active');

      this.$customerHIstoryGroup.addClass('sr-only');
      this.$customerDataGroup.addClass('sr-only');
      this.$customerIndicationsGoup.removeClass('sr-only');    
      
      const searchStatusIndications = $.ajax({
        method: 'GET',
        url: '/customers/indications/' + customerId + '/2',
        contentType: 'application/json',
        data: JSON.stringify({
          dataType: 'json',
        }),
      })

      searchStatusIndications.done($.proxy(this.onSearchStatusIndicationsSuccess, this));
      searchStatusIndications.fail($.proxy(this.onSearchStatusIndicationsFail, this));
      
    },

    onSearchStatusIndicationsSuccess: function(data) {
      if (data.success) {
        var html = '';
        var indications = data.data;

        $('[data-table-indications] tr').not(':first').remove();       

        for (let index = 0; index < indications.length; index++) {          
          
          html +=
                "<tr>" +
                "<td >" + (indications[index].id) + "</td>" +
                "<td >" + (indications[index].ip_address) + "</td>" +
                "<td >" + (indications[index].fullname) + "</td>" +
                "<td >" + (indications[index].email) + "</td>" +
                "<td >" + (indications[index].cep) + "</td>" +
                "<td >" + (indications[index].cpf) + "</td>" +
                "<td >" + (indications[index].ddd) + "</td>"+
                "<td >" + (indications[index].phone_number) + "</td>" +
                "<td >" + (indications[index].birthdate.split('-').reverse().join('/')) + "</td>" +    
                "<td >" + 
                "<select disabled name='status' id='status' style='display:inherit !important' class='selectpicker form-control' data-id='"+ indications[index].id +"' data-status-change>" ;
                
                var array = ["&#9203;", "&#128309;", "&#128308;"];    

                for (let i = 0; i < array.length; i++) 
                {  
                    html += "<option value='"+ (i+1) + "'" ;

                    if((' ' != indications[index].status_indication) && indications[index].status_indication == (i+1)){
                      html += " selected='selected'";
                    } 
                    html += ">" +  array[i] + "</option>";              
                }
                html += "</select></td></tr>";                 
        }        
        $('[data-table-indications] tr').first().after(html);
      }
    },

    onSearchStatusIndicationsFail: function(error) {
      console.log('Failed to Search status: ', error);
    },           

    onCustomerHistoryClick: function (event) {
      event.preventDefault();

      this.$customerHistory.addClass('active');
      this.$customerData.removeClass('active');
      this.$customerIndications.removeClass('active');

      const customerId = this.$customerId.val();
      const customerPoints = this.$customerPoints.val();
      this.pointsTableRender(customerId, customerPoints);

      this.$customerHIstoryGroup.removeClass('sr-only');
      this.$customerDataGroup.addClass('sr-only');
      this.$customerIndicationsGoup.addClass('sr-only');

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

  };

  $(function() {
    ScreenExchangesEdit.start();
  })  
})(jQuery);