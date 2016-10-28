{literal}
  <script type="text/javascript">
    cj(document).ready(function() {
      cj('.custom-group-group_protect td').each(function() {
        if (this.className == "html-adjust") {
          cj(this).children().prop("disabled", true);
        }
      })
    });
  </script>
{/literal}
