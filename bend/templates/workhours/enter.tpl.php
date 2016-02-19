<?php echo $form;?>
<script language="javascript">

    $(document).ready(function() {
        $("#category_1").on("change", function(event) {

            // Get/check for extra form fields
            $.getJSON("/bend-workhours/ajax_getchildcategories/" + $("#category_1").val(),
                function(result) {
                    var html = '<option value="">-- Select --</option>';
                    for (var i = 0, len = result.length; i < len; i++) {
                        var cat = result[i];
                        html+=('<option value="' + cat.id + '">' + cat.title + '</option>');
                    }        
                    $('#category_2').html(html);
                    $('#category_3').html("");
                }
            );
        });

        $("#category_2").on("change", function(event) {

            // Get/check for extra form fields
            $.getJSON("/bend-workhours/ajax_getchildcategories/" + $("#category_2").val(),
                function(result) {
                    var html = '<option value="">-- Select --</option>';
                    for (var i = 0, len = result.length; i < len; i++) {
                        var cat = result[i];
                        html+=('<option value="' + cat.id + '">' + cat.title + '</option>');
                    }        
                    $('#category_3').html(html);
                }
            );
        });
        
    });
</script>         