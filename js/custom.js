$(document).ready(function() {
    $('#joueurs-dropdown').SumoSelect({
        search: true,
        searchText: "Rechercher un joueur",
    });

    $('select').SumoSelect();
    
    $.datetimepicker.setLocale('fr');

    $('#datetimepicker').datetimepicker({
        step: 30,
        inline:true,
        minDate: 0
    });

    $('#datetimepicker').datetimepicker('reset');

    $('#date-select').change(function(){
        if($('.SumoSelect').find('.selected').hasClass('past-option')){
            $('#inscription-raid').addClass('hidden');
        }else{
            $('#inscription-raid').removeClass('hidden');
        }

        orderTable();
    });

    orderTable();
    afficherPersoSecondaire();

    $('#add-char').click(ajouterPersoSecondaire);
    $('#rem-char').click(supprimerPersoSecondaire);

    $('#replier').click(replierPersoSecond);
    $('#deplier').click(deplierPersoSecond);

    var numberOfDates = $('#date-select > option');
    if(numberOfDates.length <= 0) {
        $('#inscription-raid').addClass('hidden');
        $('#date-select').closest('.SumoSelect').addClass('hidden');
    }

    $('.ligne').each(function(){
        current = $(this);
        var next = $(this).next();
        if(!next.hasClass('second')){
            current.find('.more-char').addClass('hidden');
        }
    });

    $('#new-discussion').click(function(){
        var name = $.trim($('.search').text());

        $('.discussion-list .selected-item').removeClass('selected-item');

        if(!alreadyInDiscussion(name)){
            $('.discussion-list').prepend('<li class="selected-item">' + name + '</li>');
            $('.discussion-list li').unbind().click(refreshChatOnSelectName);

            getMessageWith();
            clearInterval(intervalRefresh);
            intervalRefresh = setInterval(fillChatWithNew, 1000);
        }
    });

    $('.discussion-list li:first-child').click();

    $('#send-message').click(function() {
        if($('.discussion-list .selected-item').length <= 0){
            alert("Aucun destinataire");
            return;
        }

        var msg = $('#content-to-send').val();

        if(!checkCommand(msg))
            sendMessage(msg);

        $('.chat-view').append("<tr><td class='send'><p class='content'>"+displayEnter(escapeHtml($("#content-to-send").val()))+"</td></tr>");
        scrollTableBottom();
        $('#content-to-send').val('');
    });

    if(window.location.href.indexOf("messagerie") > -1) {
        getAllMessage();
    }

    $('.discussion-list li').unbind().click(refreshChatOnSelectName);

    $(window).bind('scroll', fixeMenu);
    fixeMenu();

    $(".close").click(function(){
       $(this).parent().addClass("hidden");
    });

    setInterval(notificationIfNewMessage, 10000);
    notificationIfNewMessage();

    $('#enter-to-send').change(function() {
        enterToSendIfChecked();
    });

    enterToSendIfChecked();
    
    $('#deconnexion').click(function(){
        $.get(
            'ajax/destroy-session.php'
        );

        location.reload();
    });

    $(".team-modify").bind("enterKey", modifyTeamAction);

    $(".team-modify").focusout(modifyTeamAction);

    $('.team-modify').keydown(function (e) {
        if (e.keyCode == 13) { //enter ou tab
            $(this).trigger("enterKey");
            e.preventDefault();
            $(this).blur();
        }else if(e.keyCode == 9){
            $(this).trigger("enterKey");
        }
    });

    $('.team-modify').focus(function(){
        $(this).select();
    });

    selectSameWidth();

    $(".unsubscribe").on('click', removeFromEvent);
});

function removeFromEvent(){
    $.post(
        "ajax/self-remove-event.php",{
            event: getSelectedDate()
        },function(result){
            if(result) {
                $('.unsubscribe').closest('.ligne').remove();
            }
        }
    );
}

function getSelectedDate(){
    var dateText = $('.dropdown-date').find('.opt.selected label').html().trim();
    return dateText.substring(dateText.indexOf(' '), dateText.length).trim();
}

function checkCommand(text){
    $.get(
        "ajax/get-session-power.php",
        function(data){
            var power = parseInt(data);

            if(power < 1){
                $('.chat-view').append('<tr><td class="server-msg">Droits insufisants</td></tr>');
                return false;
            }

            if(!text.match("^/"))
                return false;

            var commandName = text.substring(1, text.indexOf(' '));

            switch(commandName){
                case "sendall":
                    var message = text.substring(text.indexOf(' '), text.length);
                    sendMessageToAll(message);
                    break;
            }

            return true;
        }
    );
}

function sendMessageToAll(content){
    $.post(
        "ajax/send-message-to-all.php",{
            content:content
        },function(result){
            if(result)
                $('.chat-view').append('<tr><td class="server-msg">La commande a terminé avec succès</td></tr>');
            else $('.chat-view').append('<tr><td class="server-msg">Une erreur est survenue lors de l\'exécution de la commande</td></tr>');
        }
    );
}

function modifyTeamAction(){
    var team = $(this).val();
    var player = $(this).closest('tr').find('.player-name').html();

    var date = getSelectedDate();

    var charStr = $(this).closest('tr').find('.opt.selected label').html();
    var options = $(this).closest('tr').find('select option');

    var char = -1;

    $(options).each(function(){
        if($(this).html() === charStr){
            char = $(this).val();
            return false;
        }
    });

    modifyTeamOfPlayer(team, player, date, char);
}

function fixeMenu(){
    if ($(window).scrollTop() > 275) {
        $('.menu').addClass('fixed-top');
        $('.menu').css('padding-left', ($(document).width()-($(".container").width()))/2);
    } else {
        $('.menu').removeClass('fixed-top');
        $('.menu').css('padding-left', 0);
    }
}

function modifyTeamOfPlayer(team, player, date, char){
    $.post(
        'ajax/update-team-of-player.php', {
            team : team,
            player : player,
            date: date.trim(),
            char: char
        },
        function(result){
            if(result){
                $(".done").stop().animate({bottom: -20}, 500);
                $(".done").delay(1000).animate({bottom: -100}, 500);
            }
        }
    );
}

function enterToSendIfChecked(){
    if ($('#enter-to-send').prop('checked')){
        $('#content-to-send').bind("enterKey", function (e) {
            $('#send-message').click();
        });
        $('#content-to-send').keydown(function (e) {
            if (e.keyCode == 13) {
                $(this).trigger("enterKey");
                e.preventDefault();
            }
        });
    }else $('#content-to-send').unbind();

}

if(localStorage['alreadySound'])
    var alreadySound = true;
else var alreadySound = false;

function notificationIfNewMessage(){
    $.get(
        'ajax/get-unwrote-messages.php',

        function retour(data){
            $.each(data, function(i, ligne) {

                if(!alreadySound) {
                    var notif = new Audio('mp3/notif.mp3');
                    notif.play();
                    alreadySound = true;
                    localStorage['alreadySound'] = 'yes';
                }

                if (window.location.href.indexOf("messagerie") > -1)
                    messageNotif(ligne["id"], ligne["ndc"]);
                else globalMessageNotif(ligne["id"], ligne["ndc"]);
            });
        },

        'json'
    );
}

function globalMessageNotif(id, pseudo){
    if(isCurrentUser(id))
        $(".msg-notif #new-msg-name").html("RAIDi");
    else $(".msg-notif #new-msg-name").html(pseudo);
    $(".msg-notif").removeClass("hidden");
}

function messageNotif(id, pseudo){
    //Ajouter à la discussion list si non existent
    var exist = false;

    $('.discussion-list li').each(function(){
       if($(this).val() === id) {
           exist = true;
           $(this).addClass('bold');
       }
    });

    if(!exist){
        if(isCurrentUser(id))
            $(this).prepend('<li class="bold">RAIDi</li>');
        else $(this).prepend('<li class="bold">' + pseudo + '</li>');
    }
        $(this).prepend('<li class="bold">' + pseudo + '</li>');
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function displayEnter(text){
    return text.replace(/(?:\r\n|\r|\n)/g, '<br />');
}

var intervalRefresh;

function fillChatWithNew(){
    $.get(
        'ajax/get-unwrote-messages-with.php', {
            cible : $.trim($('.discussion-list .selected-item').html())
        },

        function retour(data){
            fillChat(data);
        },
        
        'json'
    );
}

function refreshChatOnSelectName(){
    $('.discussion-list li').unbind().click(refreshChatOnSelectName);
    $('.discussion-list .selected-item').removeClass('selected-item');
    $(this).unbind();
    $(this).addClass('selected-item');

    getMessageWith();
    clearInterval(intervalRefresh);
    intervalRefresh = setInterval(fillChatWithNew, 3000);
    return;
}

function selectSameWidth(){
    var higher = 0;
    $('#inscrits .SumoSelect').each(function(){
        var current = $(this).width();
        if(higher < current)
            higher = current;
    });

    $('#inscrits .SumoSelect').width(higher);
}

function isCurrentUser(idToCompare){
    var id = $("#hdnSession").val();

    if(parseInt(idToCompare) == parseInt(id)){
        return false
    }else return true;
}

function getAllMessage() {
    $.get(
        'ajax/get-all-discussions.php',
        function(data){
            $.each(data, function(i, ligne){
                if(isCurrentUser(ligne["id"])){
                    $('.discussion-list').append('<li value="'+ligne["id"]+'">' + ligne["ndc"] + '</li>');
                }else $('.discussion-list').append('<li value="'+ligne["id"]+'">RAIDi</li>');
            });

            $('.discussion-list li').unbind().click(refreshChatOnSelectName);
            notificationIfNewMessage();
        },
        'json'
    );
}

function clearChat(){
    $(".chat-view").html('');
}

function getMessageWith() {
    clearChat();
    $.get(
        'ajax/get-discussion-with.php',
        { target: $('.discussion-list .selected-item').val() },
        function(data){
            fillChat(data);
        },
        'json'
    );
}

function fillChat(data){
    var id = $("#hdnSession").val();

    $.each(data, function(i, ligne){
        if(parseInt(ligne['id_source']) == parseInt(id)){
            $(".chat-view").append('<tr><td class="send"><p class="content">'+displayEnter(ligne["content"])+'</p></td></tr>');
        }else{
            $(".chat-view").append('<tr><td class="received"><p class="content">'+displayEnter(ligne["content"])+'</p></td></tr>');
        }

        setMessagesWrote(ligne["id_message"]);
        $('.discussion-list .selected-item').removeClass('bold');
    });

    scrollTableBottom();
}

function setMessagesWrote(idMessage){
    $.post(
        'ajax/set-wrote-messages.php', {
            cible : $('.discussion-list .selected-item').val(),
            idmessage : idMessage
        }
    );

    alreadySound = false;
    localStorage['alreadySound'] = null;
}

function sendMessage(content){
    $.post(
        'ajax/send-message.php', {
            cible : $.trim($('.discussion-list .selected-item').val()),
            contenu : content
        },

    'retour',
    'text'

    );

    function retour(message){
        $(".chat-feedback").append(message);
    }

}

function alreadyInDiscussion(name){
    var already = false;

    $('.discussion-list li').each(function(){
        var li = $.trim($(this).html());

        if(li === name) {
           $(this).addClass('selected-item');
           already = true;
        }
    });

    return already;
}

function scrollTableBottom(){
    $(".chat-view").stop().animate({ scrollTop: $(".chat-view").prop("scrollHeight") }, 1000);
}

function indexEventTableau(){
    var i = 1;
    $('.index-event').each(function(){
       if(!$(this).closest('.ligne').hasClass('hidden')){
           $(this).html(i);
           i++;
       }
    });
}

var nbrPerso = 0;

function supprimerPersoSecondaire(){
    if(nbrPerso <= 0)
        nbrPerso = $('.seconds-char').length;

    nbrPerso--;

    if(nbrPerso <= 7)
        $('#add-char').removeClass('hidden');

    if(nbrPerso <= 0) {
        $('#rem-char').addClass('hidden');
    }

    $(this).prev().remove();
}

function ajouterPersoSecondaire(){
    if(nbrPerso <= 0)
        nbrPerso = $('.seconds-char').length;

    nbrPerso++;

    if(nbrPerso > 0)
        $('#rem-char').removeClass('hidden');

    if(nbrPerso > 7)
        $('#add-char').addClass('hidden');


    var html =
        '<div class="row">'
        +'<div class="col-xs-6">'
        +'<select name="second-class-'+nbrPerso+'"">'
        +'<option value="Elémentaliste">Elémentaliste</option>'
        +'<option value="Nécromant">Nécromant</option>'
        +'<option value="Envouteur">Envouteur</option>'
        +'<option value="Gardien">Gardien</option>'
        +'<option value="Guerrier">Guerrier</option>'
        +'<option value="Revenant">Revenant</option>'
        +'<option value="Rodeur">Rodeur</option>'
        +'<option value="Voleur">Voleur</option>'
        +'<option value="Ingénieur">Ingénieur</option>'
        +'</select>'
        +'</div>'
        +'<div class="col-xs-6">'
        +'<select name="second-spe-'+nbrPerso+'"">'
        +'<option value="DPS">DPS</option>'
        +'<option value="Altérations">Altérations</option>'
        +'<option value="Tank">Tank</option>'
        +'<option value="Soin">Soin</option>'
        +'</select>'
        +'</div>'
        +'</div>';

    $("#rem-char").before(html);
    $('select').SumoSelect();
}

function replierPersoSecond(){
    $('.second').each(function(){
        $(this).addClass('hidden');
    });
}

function deplierPersoSecond(){
    $('.second').each(function(){
        $(this).removeClass('hidden');
    });
}

function orderTable(){
    replierPersoSecond();

    var date =  $('#date-select option:selected').val();

    $('.date-raid').each(function(){
        var oldDate = $(this).text();
        if(oldDate === date)
            $(this).closest('tr').removeClass('hidden');
        else $(this).closest('tr').addClass('hidden');
    });

    indexEventTableau();
}

function afficherPersoSecondaire(){
    $('.cliquable').click(function(){
        var ligne = $(this).closest('tr');
        var i = ligne.find('td:first-child').html();
        if($('.seconds-char-'+i).hasClass('hidden')){
            $('.seconds-char-'+i).removeClass('hidden');
            //MaJ des flèches
            ligne.find('.more-char').addClass('hidden');
            ligne.find('.less-char').removeClass('hidden');
        }else {
            $('.seconds-char-'+i).addClass('hidden');
            //MaJ Des flèches
            ligne.find('.more-char').removeClass('hidden');
            ligne.find('.less-char').addClass('hidden');
        }
    });
}

function genererProchainSamedi(){
    var nextSaturday;

    var ajd = new Date();
    var date = ajd.getDate();
    var jour = ajd.getDay();
    var mois = ajd.getMonth();
    var an = ajd.getFullYear();

    //Si nous sommes déjà Samedi
    if (jour == 6) {
        nextSaturday = date + 6; //On ajoute 1 semaine

        //Sinon, les autres jours
    } else{
        var diff = 5 - jour; //On prend le nbr de jours qui nous séparent du Samedi suivant

        if (diff < 0)
            nextSaturday = date + 5;
        else
            nextSaturday = date + diff;
    }

    function daysInMonth(month, year) {
        return new Date(year, month, 0).getDate();
    }

    var daysMax = daysInMonth(mois, an);

    if (nextSaturday > daysMax) {
        nextSaturday = nextSaturday - daysMax;
        mois += 1;

        if(mois > 11){
            mois = 0;
            an += 1;
        }
    }

    var date = (nextSaturday+1)+'/'+(mois+1)+'/'+an;
    $('#date-info').append('Inscriptions pour le Samedi '+date+' à 20h00');
    $('#next-event').val(date);

    $.ajax({
        url: 'gestion-table.php', // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'date=' + $('#next-event').val(),
        success : function(){ // code_html contient le HTML renvoyé

        }
    });
}

