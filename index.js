$( document ).ready(function() {

    var increment = 2;

    $(".button-collapse").sideNav();
    $('.carousel').carousel();
    $(".dropdown-button").dropdown();
    $('select').material_select();
    $('.datepicker').pickadate({
    selectMonths: true,
    selectYears: 50,
    max: new Date(),
    format: 'dd/mm/yyyy',
    formatSubmit: 'dd/mm/yyyy'
  });
   $("input:radio[name='table']").on('change', function() {
      switch($("input:radio[name='table']:checked").val()) {
        case 'type' :
        case 'marque' :
        case 'caracteristique' :
          $("#nomDiv").prop("hidden", false);
          $("#nomInput").prop("disabled", false);
          $("#prixDiv").prop("hidden", true);
          $("#prixInput").prop("disabled", true);
          $("#refDiv").prop("hidden", true);
          $("#refInput").prop("disabled", true);
          $("#ajoutMarqueDiv").prop("hidden", true);
          $("#ajoutMarqueSelect").prop("disabled", true);
          $("#ajoutTypeDiv").prop("hidden", true);
          $("#ajoutTypeSelect").prop("disabled", true);
          $("#caracteristiques").prop("hidden", true);
          break;
        case 'materiel' :
          $("#nomDiv").prop("hidden", true);
          $("#nomInput").prop("disabled", true);
          $("#prixDiv").prop("hidden", false);
          $("#prixInput").prop("disabled", false);
          $("#refDiv").prop("hidden", false);
          $("#refInput").prop("disabled", false);
          $("#ajoutMarqueDiv").prop("hidden", false);
          $("#ajoutMarqueSelect").prop("disabled", false);
          $("#ajoutTypeDiv").prop("hidden", false);
          $("#ajoutTypeSelect").prop("disabled", false);
          $("#caracteristiques").prop("hidden", false);
          break;
      }
   });

   $('#buttonAddCarac').on('click', function() {
    // var selected = $("#ajoutCaracSelect"+ increment-1 +" option:selected").html();
     var htmlNewCarac = '<div class="row">';
htmlNewCarac +='<div id="ajoutCaracDiv" class="input-field col s6" name="carac[]">';
htmlNewCarac +='<select id="ajoutCaracSelect' + increment + '" name="carac[]">';
htmlNewCarac += $("#ajoutCaracSelect"+ (increment-1) +"").html();
htmlNewCarac +='</select>';
htmlNewCarac +='<label for="ajoutCaracSelect">Caractéristique</label>';
htmlNewCarac +='</div>';
htmlNewCarac +='<div class="input-field col s6">';
htmlNewCarac +='<input type="text" class="validate" name="valeurCarac[]">';
htmlNewCarac +='<label for="ValeurCarac[]">Valeur</label>';
htmlNewCarac +='</div>';
htmlNewCarac +='</div>';
    var id = '#ajoutCaracSelect'+ increment;
     increment++;
     $("#champsCarac").append(htmlNewCarac);
     // $("#ajoutCaracSelect"+increment+" option[value='" + selected +"']").prop("disabled", true);
     $('select').material_select();
   });
});
