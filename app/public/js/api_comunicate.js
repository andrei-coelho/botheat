$("#new-profile").click(function(e){
    e.preventDefault();
    gerar_perfil();
});

var PERFIL;

function salvar_perfil(){
    $("#full-load").removeClass('d-none');
    let perfil = {
        nome:PERFIL.nome + " " + PERFIL.sobrenome,
        email: $("#email-sel").val(),
        senha: PERFIL.senha,
        data: PERFIL.data,
        imagem_id: PERFIL.imagem_id
    } 
    api_request('perfis', 'salvarPerfil', function(response){
        if(response && response == "ok"){
            console.log(response);
            gerar_perfil()
        } else {
            $("#full-load").addClass('d-none');
            alert('nao deu para salvar este perfil')
        }
    }, perfil)
}

function gerar_perfil(){

    $("#full-load").removeClass('d-none');
    $("#btn-div").addClass('d-none');

    api_request('perfis', 'gerarPerfil', function(perfil){

        if(perfil){

            PERFIL = perfil;
            let nome = perfil.nome + " " + perfil.sobrenome;
            
            let template = template_profile(nome, perfil.email, 
                perfil.senha, perfil.data, perfil.imagem);
            
            $("#profile-sel").html(template);

            $("#next-profile").click(function(e){
                e.preventDefault();
                salvar_perfil();
            })
            $("#full-load").addClass('d-none');

        } else {
            console.log(response);
            $("#full-load").addClass('d-none');
            alert('houve um erro')
            
        }
        
    })

}


function template_profile(nome, email, senha, data, linkimg){
    return `
    
    <div class="row mt-4">
        <div class="col-4">
            <img class="w-100" src="${linkimg}" alt="">
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <b>Nome:</b> ${nome}
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><b>email:</b> <input id="email-sel" type="text" value="${email}"></li>
                    <li class="list-group-item"><b>senha:</b> ${senha}</li>
                    <li class="list-group-item"><b>data:</b> ${data}</li>
                    <li class="list-group-item"><a href="${linkimg}" target="_blank"><b>imagem</b></a></li>
                </ul>
            </div>
            <p class="p-3">Só clique em <b>próximo</b> quando o perfil for concluído</p>
            <button id="next-profile" class="btn btn-primary btn-sm mt-5 float-right"> PRÓXIMO > </button>
        </div>
    </div>
    
    `;
}