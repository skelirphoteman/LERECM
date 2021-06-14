var choice_type = $('#choice_type');
var div_company = $('#company_row');
var valeur_choice = $('input[name="add_client[is_company]"]:checked').val();

if(valeur_choice == 1){
    div_company.hide();
}


$('#add_client_is_company_0').click(function (){
    div_company.show();
});


$('#add_client_is_company_1').click(function (){
    div_company.hide();
});