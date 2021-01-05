<div class="modal inmodal" id="resource_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="modal-close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>

            <form action="" method="post" id="form-ps" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" id="sweetmedia" name="sweetmedia" value="{{ env('APP_URL') }}">
                <input type="hidden" id="diginf1" name="diginf1" value="{{ env('S_INFLUENCER1') }}">  
                <input type="hidden" id="diginf2" name="diginf2" value="{{ env('S_INFLUENCER2') }}">
                <input type="hidden" id="diginf3" name="diginf3" value="{{ env('S_INFLUENCER3') }}">
                <input type="hidden" id="diginf4" name="diginf4" value="{{ env('S_INFLUENCER4') }}">
                <input type="hidden" id="diginf5" name="diginf5" value="{{ env('S_INFLUENCER5') }}">
                <input type="hidden" id="diginf6" name="diginf6" value="{{ env('S_INFLUENCER6') }}">
                <input type="hidden" id="diginf7" name="diginf7" value="{{ env('S_INFLUENCER7') }}">
                <input type="hidden" id="res1" name="res1" value="{{ env('S_RESEARCHES1') }}">
                <input type="hidden" id="res2" name="res2" value="{{ env('S_RESEARCHES2') }}">
                <input type="hidden" id="res3" name="res3" value="{{ env('S_RESEARCHES3') }}">
                <input type="hidden" id="res4" name="res4" value="{{ env('S_RESEARCHES4') }}">
                <input type="hidden" id="profile" name="profile" value="{{ env('S_PROFILE') }}">

                <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                <input type="hidden" id="action" name="action" value="">
                <div class="modal-body">
                    <div class="form-group" id="primary-key">
                        <div class="col-md-12">
                            <label for="nome" class="col-md-2 control-label" for="id">ID:</label>
                            <div class="col-md-10">
                                <input id="id" name="id" type="text" class="form-control" disabled="disabled">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="nome" class="col-md-2 control-label" for="title">Categoria:</label>
                            <div class="col-md-10">
                                <select title="Selecione Categoria..." name="category" id="category" class="selectpicker form-control" data-live-search="true" data-size="10">
                                    @foreach($resources['categories'] as $category)
                                        <option
                                                {{--@if(isset($campaign->companies_id) && (int)$campaign->companies_id == $company->id) {!! ' selected="selected" ' !!} @endif--}}
                                                value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group sr-only" data-social-network>
                        <div class="col-md-12">
                            <label for="nome" class="col-md-2 control-label" for="title">Rede Social:</label>
                            <div class="col-md-10">
                                <select title="Selecione a Rede Social..." name="social_network" id="social_network" class="selectpicker form-control" data-size="10">
                                    <option value="Facebook"> Facebook </option>
                                    <option value="Instagram"> Instagram </option>
                                    <option value="Youtube"> Youtube </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group sr-only" data-exibition-time>
                        <div class="col-md-12">
                            <label for="css_template" class="col-md-2 control-label" for="exibition_time">Exibição:</label>
                            <div class="col-md-10">
                                <input id="exibition_time" name="exibition_time" type="number" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="nome" class="col-md-2 control-label" for="title">Título:</label>
                            <div class="col-md-10">
                                <input id="title" name="title" type="text" class="form-control" required autofocus="autofocus" maxlength="150">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="css_template" class="col-md-2 control-label" for="description">Descrição:</label>
                            <div class="col-md-10">
                                <input id="description" name="description" type="text" class="form-control" required maxlength="255" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="css_template" class="col-md-2 control-label" for="points">Pontos:</label>
                            <div class="col-md-10">
                                <input id="points" name="points" type="number" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 ">
                            <label for="css_template" class="col-md-2 control-label" for="image">Imagem:</label>
                            <div class="col-md-10 bonus-upload">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <span class="btn btn-default btn-file"><span class="fileinput-new">Selecione a imagem</span>
                                    <span class="fileinput-exists">Alterar imagem</span><input type="file" id="image" name="image" accept="image/*" /></span>
                                    <span class="fileinput-filename"></span>
                                    {{--<a href="#" class="close fileinput-exists delete-image" data-dismiss="fileinput" style="float: none">×</a>--}}
                                </div>
                                <div class="progress progress-bar-default">
                                    <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar">
                                        <span class="sr-only"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10 bonus-image"></div>
                            <div id="preview"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="nome" class="col-md-2 control-label" for="title">Selos:</label>
                            <div class="col-md-10">
                                <select
                                id="stamps"
                                class="selectpicker form-control"
                                name="stamps[]"
                                title="Selecione o(s) selo(s)..."
                                data-live-search="true"
                                data-selected-text-format="count > 3"
                                data-size="10"                                
                                multiple
                                autocomplete="false"
                                >
                                @foreach($stamps as $stamp)
                                    <option
                                    value="{{ $stamp->id }}"
                                    @if (isset($stampsCheck) && in_array($stamp->id, $stampsCheck))
                                        selected="selected"
                                    @endif
                                    >
                                    {{ $stamp->title }} - ({{ $stamp->required_amount }} ações necessárias) 
                                    </option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 ">  
                                <div class="stamp-image"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="ladda-button btn btn-primary"  data-style="zoom-in" type="button" id="action-button">Submit</button>
                    <button class="btn btn-default" type="button" data-dismiss="modal" id="cancel-button">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</div>
