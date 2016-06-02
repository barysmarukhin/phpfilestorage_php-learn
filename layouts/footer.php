    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?>, Борис Марухин</div>
  </body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>