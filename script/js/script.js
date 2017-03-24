$(document).ready(function(){

//    debut index-manage.php---------------------------------------------

    // Ajax qui effectu les test d'inscription

    // Execute la function Ajax lors du clique sur le bouton avec l'id 'submit_ins'
    $('#submit_ins').click(function(e){
        // Annule l'envoie des données du formulaire par default pour les envoyer via ajax
        e.preventDefault();
        $.post(
            "script/php/index-manage.php",
            { 
                // Envoie les valeurs des différents champs du formulaire
                pseudo: $('#pseudo').val(),
                password: $('#password').val(), 
                passwordConfirm: $('#passwordConfirm').val() 
            }, 
            // Réponse de la requête ajax
            function(data) {
                // Si l'inscription est réussi masque le formulaire d'inscription et renvoie le message de réussite
                if (data == "Inscription réussi félicitation!"){
                    $('#inscription-page').hide();
                    $('#connexion').show();
                    $( "#message-retour" ).removeClass().addClass('message-good').fadeIn();
                    $( "#message-retour>span" ).html( data );
                    console.log(data);
                // Sinon reste sur le formulaire d'inscription et renvoie un message d'erreur
                }else if(data == "Ce pseudo existe déjà!" || data == "Attention les mots de passe ne sont pas identiques!" || data == "Attention un des champs est vide!"){
                    $( "#message-inscription" ).removeClass().addClass('message-bad').fadeIn();
                    $( "#message-inscription>span" ).html( data );
                    console.log(data);
                }
            }
        );
    });
    
    // Ajax qui effectu les test de connexion
    $('#submit_con').click(function(e){
        e.preventDefault();
        $.post(
            "script/php/index-manage.php",
            { 
                pseudo_con: $('#pseudo_con').val(),
                password_con: $('#password_con').val()
            }, 
            function(data) {
                if (data == "Attention ce pseudo n'est pas valide!" || data == "Attention un des champs est vide!" || data == "Attention le mot de passe n'est pas valide"){
                    $( "#message-retour" ).removeClass().addClass('message-bad').fadeIn();
                    $( "#message-retour>span" ).html( data );
                    // console.log(data);
                }else{
                    window.location.href ="messagerie.php";
                    console.log(window.location.href);
                    console.log('data : '+data);
                }
            }
        );
    });

    $('#go-inscription').click(function(){
        $('#inscription-page').show();
        $('#connexion').hide();
    });

    $('#inscription-page').click(function(){
        $('#inscription-page').hide();
        $('#connexion').show();
    });

    $("#inscription").click(function(e) {
        e.stopPropagation();
   });
//    fin index-manage.php----------------------------------------------


//    Début messagerie-manage.php---------------------------------------
    console.log('Page chargée');
    
    // Ajax qui charge la liste des membres à gauche
    $.post(
        "script/php/messagerie-manage.php",
        { 
            loadMembre: "ok"
        }, 
        function(data) {
            $( "#messagerie-left" ).html( data );
            // console.log( data );
        }
    );
    
    // Ajax qui charge la liste des messages
    $.post(
        "script/php/messagerie-manage.php",
        { 
            loadMessage: "ok"
        }, 
        function(data) {
            $( "#messagerie-conversation" ).html( data );
            scrollDown('messagerie-conversation');
            console.log( 'data : '+data );
        }
    );

    // Ajax qui met en place le $_SESSION['recepteur']
    $('#messagerie-left').on('click', '#change-membre',function(e){
        e.preventDefault();
        var recepteurName = $(this).find('.contact-name').text();
        console.log(recepteurName);
        $.post(
            "script/php/messagerie-manage.php",
            { 
                recepteur: recepteurName
            },
            function(){
                location.reload();             
            }
        );
    });
    
    // Ajax qui envoit un nouveau message
    $('#send').click(function(e){
        e.preventDefault();
        var contenuVal = $('#contenu').val();
        $.post(
            "script/php/messagerie-manage.php",
            { 
                contenu: contenuVal
            },
            function(data){
                $( "#messagerie-conversation" ).append( data );
                // $('.message-line').slideDown("fast");
                // $( data ).appendTo( $("#messagerie-conversation") );
                scrollDown('messagerie-conversation');
                console.log(data);
                $('#contenu').val('');
            }
        );
    });
    
    // Déclaration la variable 'dataSave'
    var dataSave;
    // La fonction Ajax s'exécute toute les 0,1 seconde vérifiant si un nouveau message est reçu
    window.setInterval(function(){
        // Ajax envoie la chaine de caractère 'ok' via la méthode POST à la page 'messagerie-manage.php'
        $.post(
            "script/php/messagerie-manage.php",
            { 
                refresh: "ok"
            },
            // Fonction qui reçoit les données
            function(data){
                // Si les données reçus ne sont pas vide
                if (data !== ""){
                    // Parse les valeurs des données reçus et les assigne à la variable 'result'
                    var result = $.parseJSON(data);

                    // Vérifie si l'id du message reçu n'a pas déjà été utilisé
                    if (dataSave !== result.id){
                        // Ajoute à l'élément HTML avec l'id 'messagerie-conversation' le contenu du message reçu
                        $( "#messagerie-conversation" ).append(result.msg);
                        // Assigne l'id du message reçu à la variable 'dataSave' pour éviter d'afficher plusieurs fois le même message
                        dataSave = result.id;
                        // Joue le son pour signaler la reception d'un message
                        var audio = new Audio('sound/msg.mp3');
                        audio.play();
                        // Descend le scroll pour afficher le dernier message reçu
                        scrollDown('messagerie-conversation');
                    }
                }
            }
        );
    }, 100);

    // Ajax qui deconnect
    $('#exit-messagerie').click(function(e){
        e.preventDefault();
        $.post(
            "script/php/messagerie-manage.php",
            { 
                exit: 'ok'
            },
            function(){
                document.location.href = 'index.php';
            }
        );
    });

    scrollDown=function(id){
        if(document.getElementById(id) !== null){
            var element = document.getElementById(id);
            element.scrollTop = element.scrollHeight;
        }
    };

//    fin messagerie-manage.php----------------------------------------
});