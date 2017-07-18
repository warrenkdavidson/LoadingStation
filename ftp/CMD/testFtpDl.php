<!DOCTYPE html>
<html>
<head>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<script language="javascript" >

function LoadFinance()
{
    $(function() {
        $.getJSON(
        "DownloadRfid.php",
        function(json){ $('#finance').text(json.query.results)});
        // Patching payload into page element ID = "dog" });
    });
}

setInterval( LoadFinance, 3000 );
  
</script>
<style >

</style>

</head>

<body>

	<div id="finance" >

	</div>

</body>
</html>