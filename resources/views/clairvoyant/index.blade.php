<!doctype html>
<html lang="en">
<head>
  {{-- Full History --}}
  <script>
    window['_fs_debug'] = false;
    window['_fs_host'] = 'fullstory.com';
    window['_fs_org'] = 'CSWG1';
    window['_fs_namespace'] = 'FS';
    (function(m,n,e,t,l,o,g,y){
        if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
        g=m[e]=function(a,b){g.q?g.q.push([a,b]):g._api(a,b);};g.q=[];
        o=n.createElement(t);o.async=1;o.src='https://'+_fs_host+'/s/fs.js';
        y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
        g.identify=function(i,v){g(l,{uid:i});if(v)g(l,v)};g.setUserVars=function(v){g(l,v)};
        g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
        g.consent=function(a){g("consent",!arguments.length||a)};
        g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
        g.clearUserCookie=function(){};
    })(window,document,window['_fs_namespace'],'script','user');
  </script>

  {{-- Required meta tags --}}
  <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  {{-- Bootstrap CSS --}}
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  {{-- CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/clairvoyant/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/clairvoyant/css/animate.css') }}">

  {{-- Icon --}}
  <link rel="icon" href="{{ asset ('assets/clairvoyant/imgs/favicon.ico') }}" type="image/x-icon }}"/>
  <link rel="shortcut icon" href="{{ asset ('assets/clairvoyant/imgs/favicon.ico') }}" type="image/x-icon"/>

  {{-- Fonts --}}
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
  <title>Clairvoyant Club</title>
</head>
<body>

{{-- Offer --}}
<iframe src="" scrolling="no" frameborder="0" width="1" height="1"></iframe>

  {{-- Main --}}
  <section id="main">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 wow fadeInUp">
          <h1><img src="{{ asset ('assets/clairvoyant/imgs/main-title.png')}}" title="Clairvoyant Club" alt="Clairvoyant Club" class="img-fluid"></h1>
          <h2>Clube de vantagens esotéricas <strong>especialmente</strong> para você!</h2>
        </div>
        <div class="col-lg-4 offset-lg-1 wow fadeInRight" id="form-box">
          <div class="title">
            <h3>Clairvoyant Club + Estrela Fone</h3>
            <p>Concorra a <span>10 minutos</span> de vidência personalizada por <span>R$5,00</span>.</p>
          </div>
          {{-- Form --}}
          <form method="post" action="{{ url('clairvoyant') }}" data-form-register>  
            {!!csrf_field()!!}
            <input type="hidden" name="lp_offer_id" value="" /> 
            <input type="hidden" name="lp_campaign_id" value="" />   
            <!-- The rest of the fields name must match what you have set in the vertical -->   
            <input type="hidden" name="lp_redirect_url" value="http://meu-novo-vw.com.br/sucesso.html" /> 
            <!-- lp_redirect_url: Optional redirect url when the lead is accepted -->  
            <input type="hidden" name="lp_redirect_fail_url" value="" /> 
            <!-- lp_redirect_fail_url: Optional redirect url when the lead is rejected. -->
            
            <div class="form-group">
              <label>Nome</label>
              <input id="first_name" type="text" class="form-control" name="first_name" required="required">
            </div>
            <div class="form-group">
              <label>E-mail</label>
              <input id="email_address" type="email" class="form-control" name="email_address" required="required">
            </div>
            <div class="form-group">
              <div class="row two-inputs">
                <div class="col-4">
                  <label>DDD</label>
                  <input id="ddd_home" type="text" class="form-control mask-ddd" name="ddd_home" required="required" data-mask-ddd>
                </div>
                <div class="col-8">
                  <label>Telefone</label>
                  <input id="phone_home" type="text" class="form-control bfh-phone" name="phone_home" required="required" data-mask-tel>
                </div>
              </div>
            </div>
            <div>
              <button type="submit" data-submit-button><span>Quero concorrer</span></button>
            </div>
          </div>
        </form>
        {{-- End Form --}}
      </div>
    </div>
  </div>

</section>
  {{-- End Main --}}

<!-- Footer -->

<footer class="footer">
  <div class="container">
    <span>© 2018 Clairvoyant Club. Todos os direitos reservados
</footer>
<!-- End Footer -->

@include('layouts.partials.modal-clairvoyant')

<!-- JQuery -->
{{-- jQuery first, then Popper.js, then Bootstrap JS --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<!-- Mask -->
<!--script src="js/jquery.mask.min.js"></script-->
<script src="{{ asset('assets/js/jquery.mask/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/clairvoyant/js/mask.js') }}"></script>

<!-- Wow -->
<script src="{{ asset('assets/clairvoyant/js/wow.js') }}"></script>
<script type="text/javascript">
  new WOW().init();
</script>

</body>
</html>