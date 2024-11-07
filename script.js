document.getElementById('toggleButton').addEventListener('click', function() {
    var content = document.getElementById('content');
    if (content.style.display === 'none') {
      content.style.display = 'block';
    } else {
      content.style.display = 'none';
    }
  });
  // Codigo da aba de localizações
  function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: -22.8456, lng: -47.6754}, // Coordenadas de Saltinho
      zoom: 13 // Zoom inicial
    });
  
    // Marcadores para os postos de saúde em Saltinho
    var locations = [
      {lat: -22.843221, lng: -47.673128, name: 'Posto de Saúde 1'},
      {lat: -22.847319, lng: -47.675693, name: 'Posto de Saúde 2'},
      {lat: -22.848923, lng: -47.678106, name: 'Posto de Saúde 3'}
    ];
  
    // Adicionando marcadores ao mapa
    locations.forEach(function(location) {
      var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: location.name
      });
    });
  }
  
  //script do botao login
  window.addEventListener('scroll', function() {
    var botaoFixo = document.getElementById('botaoFixo');
    botaoFixo.style.top = (20 + window.scrollY) + 'px';
  });