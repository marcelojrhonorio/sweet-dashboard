(function($) {
  const CatchInputs = {
    ui: {
      $root: null,
      $inputType: null,
      $inputLabel: null,
      $optionTypes: null,
    },

    state: {
      counter: 0,
    },

    start: function start() {
      this.ui.$root        = $('[data-catch-inputs-container]');
      this.ui.$inputType   = this.ui.$root.find('[data-catch-input-type]');
      this.ui.$inputLabel  = this.ui.$root.find('[data-catch-input-label]');
      this.ui.$optionTypes = this.ui.$inputType.find('option');

      this.bind();
    },

    bind: function bind() {
      this.ui.$root.on('click', '[data-btn-catch-create]', this.onClickCreate.bind(this));
      this.ui.$root.on('click', '[data-btn-catch-remove]', this.onClickRemove.bind(this));
    },

    template: function template() {
      const html = `
        <div class="form-group" data-catch-input-row>
          <div class="col-md-4">
            <select
              id="catch-input[${this.state.counter}][type]"
              class="form-control"
              name="catch-input[${this.state.counter}][type]"
              data-catch-input-type></select>
          </div>
          <div class="col-md-4">
            <input
              id="catch-input[${this.state.counter}][label]"
              class="form-control"
              name="catch-input[${this.state.counter}][label]"
              type="text"
              placeholder="Informe o label"
              data-catch-input-label
            >
          </div>
          <div class="col-md-4">
            <button class="btn btn-danger" type="button" data-btn-catch-remove>
              <span class="sr-only">Remover</span>
              <i class="fa fa-minus-circle" aria-hidden="true"></i>
            </button>
          </div>
        </div>
      `;

      return $(html);
    },

    makeNewRow: function makeNewRow() {
      const $html  = this.template();
      const $types = this.ui.$optionTypes.clone();

      $html.find('[data-catch-input-type]').append($types);

      $html.appendTo(this.ui.$root).hide().fadeIn();
    },

    onClickCreate: function onClickCreate(event) {
      event.preventDefault();

      this.state.counter += 1;

      this.makeNewRow();

      return this;
    },

    onClickRemove: function onClickRemove(event) {
      event.preventDefault();

      $(event.target)
        .closest('[data-catch-input-row]')
        .fadeOut(400, function() {
          $(this).remove();
        });
    },
  };

  $(function () {
    CatchInputs.start();
  });
})(jQuery);
