process.env.DISABLE_NOTIFIER = true;
require('es6-promise').polyfill();
var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.styles(['mastermdr.css'], 'public/css/mastermdr.css');

    //mix.version('public/js/select-vue.js');
});
