{literal}
  <script type="text/javascript">
    cj(document).ready(function() {
      cj('.crm-submit-buttons a').each(function() {
        var urlSplit = location.search.split('&');
        for (var urli=0; urli < urlSplit.length; urli++) {
          var elementSplit = urlSplit[urli].split('=');
          for (var elementi=0; elementi < elementSplit.length; elementi++) {
            if (elementSplit[elementi] == 'context') {
              var contextValue = elementSplit[elementi + 1];
            }
          }
        }
        if (contextValue == 'smog' || contextValue == 'amtg') {
          cj('.crm-submit-buttons a').hide();
        }
      });
    });
  </script>
{/literal}
