require(['jquery'], function($) {
    $(document).ready(function() {
        if ($('div.login-form-submit').length) {
            $('div.login-form-submit').append('<a href="/local/cadastro/index.php" class="btn btn-secondary btn-lg">Cadastrar</a>');
        }
    });
});

// versão com o button abaixo do button de login
// require(['jquery'], function($) {
//     $(document).ready(function() {
//         if ($('div.login-form-submit').length) {
//             $('<div><a href="/local/cadastro/index.php" class="btn btn-secondary btn-lg">Cadastrar</a></div>').insertAfter('.login-form-submit');
//         }
//     });
// });
