<?php headerAdmin($data);?>
<?php header_body_Admin($data);?>

    </div>
  </div>
  <?php getModal('modalPlanoteca',$data);?>
</div>

<?php footerAdmin($data);?>

<script>
  objRef = document.body;
  objRef.classList.remove('home');
  objRef.classList.add('admin');
  objRef.setAttribute('data-x','tab');
</script>
