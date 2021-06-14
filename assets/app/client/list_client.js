var url = $('#list_client').data('url');
var profil = $('#list_client').data('profil');
var tbody = $('#list_client').find('tbody');
var change = false;

$('#spinner-list').hide();

function getClientList(search){
    $('#spinner-list').show();
    $.post( url, {search: search},function(data) {
        var json = JSON.parse(data, function (key, value) {
            return (value == null) ? "" : value
        });
        console.log(json);
        addRow(tbody, json);
        $('#spinner-list').hide();
    })
    .fail(function() {

    })
}


function addRow(table, data){
    var html = "";
    $.each(data, function(i, obj) {
        var row = '<tr><td>' +
            obj.id +
            '</td><td>' +
            obj.surname +
            '</td><td>' +
            obj.name +
            '</td><td>' +
            obj.avenue +
            '</td><td>' +
            obj.postal_code +
            '</td><td>' +
            obj.city +
            '</td><td>' +
            obj.company_name +
            '</td><td><a class="btn btn-primary" href="' + profil + '/' + obj.id + '">Consulter</a></td></tr>';
        html = html + row;
    });
    table.html(html);
}

getClientList("");

$('#inputSearchClient').on('input',function(e){
    if(change == false){
        getClientList($(this).val());
    }
});