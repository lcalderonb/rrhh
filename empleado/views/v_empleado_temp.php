<section class="content">
                <a id="EmBtnAdm1" class="btn btn-app btn-lg bg-success EmBtnAdm1 mt-1 mb-1">
                  <span class="badge bg-purple">
                  5
                  </span><i class="fas fa-sync-alt mr-1"></i>
                </a>

                <a  id="EmBtnHosp1" class="btn btn-app btn-lg bg-success EmBtnHosp1 mt-1 mb-1">
                  <span class="badge bg-purple">
                  6
                  </span><i class="fas fa-sync-alt mr-1"></i>
                </a>
</section>
<script type="text/javascript">

  $().on("click", ".EmBtnAdm1", function(e){
      e.preventDefault();
      $.alert("Lista de administrativos")
      return false;
  });

  $("#EmBtnHosp1").click(function(e){
      $.alert("Lista de Hospital");
  });

</script>