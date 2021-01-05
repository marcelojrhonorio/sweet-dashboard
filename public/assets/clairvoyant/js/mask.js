(function($) {
  /**
   * Clairvoyant form
   */
  const ClairVoyantForm = {
    $form: null,

    start: function() {
      this.$form    = $('[data-form-register]');
      this.$modal   = $('[data-sweet-modal-clairvoyant]');
      this.applyMasks();
      this.bind();
    },

    applyMasks: function() {
      $('[data-mask-ddd]').mask('00');
      $('[data-mask-tel]').mask('00000-0000');
    },
    
    bind: function() {
      this.$form.on('submit', $.proxy(this.onSubmit, this));
      this.$modal.on('hidden.bs.modal', $.proxy(this.onCloseModal, this));
    },

    onCloseModal: function(){
      this.$form[0].reset();
    },

    getValues: function() {
      return {
        first_name      : this.$form.find('#first_name').val(),
        email_address   : this.$form.find('#email_address').val(),
        ddd_home        : this.$form.find('#ddd_home').val(),
        phone_home      : this.$form.find('#phone_home').val(),
        site_origin: 'sweetmedia.com.br/clairvoyant',
      };
    },

    onSubmit: function(event) {
      event.preventDefault();

      const values = JSON.stringify(this.getValues());

      const headers = {
        'Accept'      : 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      };

      const creating = $.ajax({
        cache      : false,
        type       : 'post',
        dataType   : 'json',
        data       : values,
        headers    : headers,
        url        : '/saveclairvoyant',
        contentType: 'application/json; charset=utf-8',
      });

      creating.done($.proxy(this.onSuccess, this));

      creating.fail($.proxy(this.onFail, this));
    },


    onSuccess: function(data) {
      if ('success' === data.status) {
        $('[data-sweet-modal-clairvoyant]').modal('show');
      }
    },

    onFail: function(xhr) {
      console.log(xhr.responseJSON);
    },

  };

  /**
   * Fires when document is ready.
   */
  $(function() {
    ClairVoyantForm.start();
  });
})(jQuery);
