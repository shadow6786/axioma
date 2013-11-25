      <script src="<?= cdn_base('js/jquery.min.js'); ?>"></script>
      <script src="<?= cdn_base('js/bootstrap.js'); ?>"></script>
      <script src="<?= cdn_base('js/jquery.parallax.js'); ?>"></script> 
      <script src="<?= cdn_base('js/modernizr-2.6.2.min.js'); ?>"></script> 
      <script src="<?= cdn_base('js/revolution-slider/js/jquery.themepunch.revolution.min.js'); ?>"></script>
      <script src="<?= cdn_base('js/jquery.nivo.slider.pack.js'); ?>"></script>
      <script src="<?= cdn_base('js/jquery.prettyPhoto.js'); ?>"></script>
      <script src="<?= cdn_base('js/superfish.js'); ?>"></script>
      <script src="<?= cdn_base('js/tweetMachine.js'); ?>"></script>
      <script src="<?= cdn_base('js/tytabs.js'); ?>"></script>
      <script src="<?= cdn_base('js/jquery.gmap.min.js'); ?>"></script>
      <script src="<?= cdn_base('js/circularnav.js'); ?>"></script>
      <script src="<?= cdn_base('js/jquery.sticky.js'); ?>"></script>
      <script src="<?= cdn_base('js/jflickrfeed.js'); ?>"></script>
      <script src="<?= cdn_base('js/imagesloaded.pkgd.min.js'); ?>"></script>
      <script src="<?= cdn_base('js/waypoints.min.js'); ?>"></script>
      <script src="<?= cdn_base('js/spectrum.js'); ?>"></script>
      <script src="<?= cdn_base('js/switcher.js'); ?>"></script>
      <script src="<?= cdn_base('js/custom.js'); ?>"></script>
      <?php
            if (isset($scripts) && is_array($scripts)) {
                  //Auxiliar Scripts - Call JS you will only use in this page in the controller.
                  foreach ($scripts as $script) {
                        $cad = substr($script, 0, 4);
                        if ($cad == "http")
                              echo "<script src=\"" . $script . "\" type=\"text/javascript\"></script>";
                        else
                              echo "<script src=\"" . cdn_base($script) . "\" type=\"text/javascript\"></script>";
                  }
      }
      ?>
   </body>
</html>