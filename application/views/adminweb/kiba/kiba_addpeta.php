<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&amp;sensor=true"></script>
<script type="text/javascript" src="http://www.apacari.co.id/assets/js/minified/widgets/other-gmaps.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    var now_lat = <?php echo (empty($Lat)) ? '3.5915405' : $Lat; ?>;
    var now_lng = <?php echo (empty($Lng)) ? '98.66929979999999' : $Lng; ?>; 
    map = new GMaps({
      div: '#map-interaction',
      lat: now_lat,
      lng: now_lng
    });
    map.addMarker({
        lat: now_lat,
        lng: now_lng
    });



    if (map.addMarker) {
                GMaps.on('marker_added', map, function (marker) {
                    $('#lat').val(marker.getPosition().lat().toFixed(7));
                    $('#lng').val(marker.getPosition().lng().toFixed(7));
                });
                GMaps.on('click', map.map, function (event) {
                    var lat = event.latLng.lat();
                    var lng = event.latLng.lng();
                    map.removeMarkers();
                    map.addMarker({
                        lat: lat,
                        lng: lng
                    });
                });
            } else {
                GMaps.on('click', map.map, function (event) {
                    var lat = event.latLng.lat();
                    var lng = event.latLng.lng();

                    map.addMarker({
                        lat: lat,
                        lng: lng
                    });
                });
            };

    $('form').on('submit', function (e) {
      var lat = $('#lat').val();
      var lng = $('#lng').val();
        if(confirm("Lokasi akan di simpan ke dalam database ??"))
        {
            $( "#loading" ).empty().append("tunggu sebentar...");
            $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>kiba/update_peta",
                    data:{lat:lat,lng:lng},
                    cache: false,
                    success: function(html){
                        window.location.replace("<?php echo $this->session->userdata('last_url') ?>");
                },
                error: function(data) {
                    alert("Maaf, gagal disimpan");
                }
            });
            e.preventDefault();
            return false;
            }
    });


    $("#reset").click(function(){
        var now_lat = 3.567721;
        var now_lng = 98.6249542;
        map = new GMaps({
          div: '#map-interaction',
          lat: now_lat,
          lng: now_lng
        });
        map.addMarker({
            lat: now_lat,
            lng: now_lng
        });

        if (map.addMarker) {
                GMaps.on('marker_added', map, function (marker) {
                    $('#lat').val(marker.getPosition().lat().toFixed(7));
                    $('#lng').val(marker.getPosition().lng().toFixed(7));
                });
                GMaps.on('click', map.map, function (event) {
                    var lat = event.latLng.lat();
                    var lng = event.latLng.lng();
                    map.removeMarkers();
                    map.addMarker({
                        lat: lat,
                        lng: lng
                    });
                });
            } else {
                GMaps.on('click', map.map, function (event) {
                    var lat = event.latLng.lat();
                    var lng = event.latLng.lng();

                    map.addMarker({
                        lat: lat,
                        lng: lng
                    });
                });
            };
    });


});

</script>
<?php
	
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
		
	echo ! empty($pagination) ? '<div id="pagination">' . $pagination . '</div>' : '';
?>

<div class="container">

    <div class="panel">
        <div class="panel-header">
        <i class="icon-tasks"></i> <?php echo ! empty($header) ?  $header: ''; ?></div>
        <div class="panel-content">
   
   <div class="row">
    <div class="span8">
       <div id="map-interaction" style="height: 350px;"></div>

    </div>
    <div class="span3">

    <form id="form-peta" action="<?php echo $form_action; ?>" method="POST"/>
          <fieldset>
              <legend>Data Peta <br><small>(klik peta untuk menampilkan koordinat)</small></legend>
              <div class="control-group">
                  <label class="control-label" for="name">Latitude</label>
                  <div class="controls">
                      <input type="text" class="input-xlarge" name="Lat" id="lat" value="<?php echo $Lat; ?>" />
                  </div>
              </div>
              <div class="control-group">
                  <label class="control-label">Longitude</label>
                  <div class="controls">
                      <input type="text" class="input-xlarge" name="Lng" id="lng" value="<?php echo $Lng; ?>" />
                  </div>
              </div>
                            
              <div class="form-actions">
                  <button type="submit" class="btn btn-success">Submit</button>
                  <button type="reset" class="btn">Ulangi</button>
              </div>
          </fieldset>
      </form>

    </div>
  </div>
              

           </div>
    </div>
</div>

