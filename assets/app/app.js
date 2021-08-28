
const $ = require('jquery');


// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

global.$ = global.jQuery = $;
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$('.delete_alert').click(function(){
    return confirm("Êtes-vous sur de vouloir effectuer cette action ?");
})