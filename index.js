$( document ).ready(function() {
    $(".button-collapse").sideNav();
    $('.carousel').carousel();
    $(".dropdown-button").dropdown();
    $('#insMembreSelectGrade').material_select();
    $('#insMembreSelectSexe').material_select();
    $('.datepicker').pickadate({
    selectMonths: true,
    selectYears: 50,
    max: new Date(),
    format: 'dd/mm/yyyy',
    formatSubmit: 'dd/mm/yyyy'
  });
});
