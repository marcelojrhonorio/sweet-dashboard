<div class="modal fade" tabindex="-1" role="dialog" data-sweet-modal-clairvoyant>
  <div class="modal-dialog" role="document">
    <form method="post" action="https://astrocentro.com.br/consultas/?payload[page]=anjo-da-guarda&tracker_id=v2_38404&wl=140">
      {{ csrf_field() }}

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Obrigado pelo cadastro!
          </h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Em breve receberá uma ligação caso tenha sido um dos escolhidos na promoção.
            Enquanto aguarda, gostaria de ver algum especialista que você poderá se consultar?</p>
        </div>
        <div class="modal-footer">        
          <button class="btn btn-secondary" data-dismiss="modal">
            Não.
          </button>  
          <button class="btn btn-info" type="submit">
            Sim, eu quero!
          </button>
        </div>
      </div>
    </form>
  </div>
</div>